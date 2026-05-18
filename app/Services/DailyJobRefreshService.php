<?php

namespace App\Services;

use App\Contracts\JobRepositoryInterface;
use App\Models\JobSource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DailyJobRefreshService
{
    public function __construct(
        private readonly JobRepositoryInterface $jobRepository,
    ) {}

    /**
     * Refresh trusted public job feeds and ingest only complete jobs with company logos.
     *
     * @return array{sources: int, received: int, accepted: int, rejected: int, inserted: int, updated: int, skipped: int, errors: array<int, mixed>}
     */
    public function refresh(?int $limit = null): array
    {
        $stats = [
            'sources' => 0,
            'received' => 0,
            'accepted' => 0,
            'rejected' => 0,
            'inserted' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        foreach (config('lamaraja_jobs.daily_sources', []) as $sourceConfig) {
            $stats['sources']++;

            try {
                $jobs = match ($sourceConfig['type'] ?? null) {
                    'greenhouse' => $this->fetchGreenhouseJobs($sourceConfig),
                    default => [],
                };

                if ($limit !== null) {
                    $jobs = array_slice($jobs, 0, $limit);
                }

                $stats['received'] += count($jobs);
                $validated = $this->completeJobsOnly($jobs, $sourceConfig);
                $stats['accepted'] += count($validated);
                $stats['rejected'] += count($jobs) - count($validated);

                if ($validated === []) {
                    continue;
                }

                $source = JobSource::firstOrCreate(
                    ['name' => $sourceConfig['name']],
                    ['is_active' => true],
                );
                $source->update(['last_scraped_at' => now()]);

                $ingestStats = $this->jobRepository->upsertFromSource(
                    jobs: $validated,
                    sourceId: (string) $source->id,
                    scrapedAt: now()->toIso8601String(),
                );

                $stats['inserted'] += $ingestStats['inserted'];
                $stats['updated'] += $ingestStats['updated'];
                $stats['skipped'] += $ingestStats['skipped'];
                $stats['errors'] = array_merge($stats['errors'], $ingestStats['errors']);
            } catch (\Throwable $e) {
                Log::error('Daily job refresh source failed', [
                    'source' => $sourceConfig['name'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
                $stats['errors'][] = [
                    'source' => $sourceConfig['name'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $stats;
    }

    /**
     * @param array<string, mixed> $sourceConfig
     * @return array<int, array<string, mixed>>
     */
    private function fetchGreenhouseJobs(array $sourceConfig): array
    {
        $board = $sourceConfig['board'];
        $response = Http::timeout(45)
            ->acceptJson()
            ->get("https://boards-api.greenhouse.io/v1/boards/{$board}/jobs", ['content' => 'true']);

        if ($response->failed()) {
            throw new \RuntimeException("Greenhouse {$board} returned HTTP {$response->status()}");
        }

        $payload = $response->json();
        $jobs = [];

        foreach (($payload['jobs'] ?? []) as $job) {
            $location = collect($job['offices'] ?? [])
                ->pluck('name')
                ->filter()
                ->implode('; ');

            $department = collect($job['departments'] ?? [])
                ->pluck('name')
                ->filter()
                ->first();

            $tags = array_values(array_unique(array_filter(array_merge(
                $sourceConfig['default_tags'] ?? [],
                [$department ? Str::slug($department) : null]
            ))));

            $jobs[] = [
                'title' => trim((string) ($job['title'] ?? '')),
                'company' => $sourceConfig['company'],
                'company_logo' => $sourceConfig['company_logo'],
                'location' => $location ?: 'Remote / Global',
                'employment_type' => 'full-time',
                'salary_min' => null,
                'salary_max' => null,
                'salary_currency' => 'IDR',
                'description_raw' => trim(strip_tags((string) ($job['content'] ?? ''))),
                'summary_ai' => null,
                'tags' => array_slice($tags, 0, 5),
                'source_url' => $job['absolute_url'] ?? null,
            ];
        }

        return $jobs;
    }

    /**
     * @param array<int, array<string, mixed>> $jobs
     * @param array<string, mixed> $sourceConfig
     * @return array<int, array<string, mixed>>
     */
    private function completeJobsOnly(array $jobs, array $sourceConfig): array
    {
        $valid = [];
        $logo = $sourceConfig['company_logo'] ?? null;

        if (!$this->logoLooksValid($logo)) {
            throw new \RuntimeException("Invalid or missing logo for {$sourceConfig['name']}");
        }

        foreach ($jobs as $job) {
            $job['company_logo'] = $job['company_logo'] ?: $logo;

            if (!$this->isComplete($job)) {
                continue;
            }

            $valid[] = $job;
        }

        return $valid;
    }

    /**
     * @param array<string, mixed> $job
     */
    private function isComplete(array $job): bool
    {
        foreach (['title', 'company', 'location', 'description_raw', 'source_url', 'company_logo'] as $field) {
            if (empty($job[$field]) || !is_string($job[$field])) {
                return false;
            }
        }

        return filter_var($job['source_url'], FILTER_VALIDATE_URL) !== false
            && $this->logoLooksValid($job['company_logo']);
    }

    private function logoLooksValid(?string $url): bool
    {
        if (!$url || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        try {
            $response = Http::timeout(15)->head($url);
            if ($response->successful()) {
                return str_starts_with((string) $response->header('Content-Type'), 'image/');
            }
        } catch (\Throwable) {
            // Some CDNs reject HEAD; fall back to extension check.
        }

        return (bool) preg_match('/\.(png|jpe?g|webp|svg)(\?.*)?$/i', $url);
    }
}
