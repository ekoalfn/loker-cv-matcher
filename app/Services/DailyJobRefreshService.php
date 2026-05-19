<?php

namespace App\Services;

use App\Contracts\JobRepositoryInterface;
use App\Models\Job;
use App\Models\JobSource;
use Illuminate\Support\Carbon;
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
                    'dealls' => $this->fetchDeallsJobs($sourceConfig),
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
     * Deactivate active jobs whose source URL is dead/closed or whose page indicates expiration.
     *
     * @return array{checked:int,deactivated:int,expired:int,url_dead:int,errors:int}
     */
    public function pruneExpiredJobs(?int $limit = null): array
    {
        $stats = ['checked' => 0, 'deactivated' => 0, 'expired' => 0, 'url_dead' => 0, 'errors' => 0];

        $query = Job::active()->whereNotNull('source_url')->orderBy('scraped_at');
        if ($limit !== null) {
            $query->limit($limit);
        }

        $jobs = $query->get();

        foreach ($jobs as $job) {
            $stats['checked']++;
            try {
                $response = Http::timeout(20)
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0 LamarajaBot/1.0'])
                    ->get((string) $job->source_url);

                if (! $response->successful()) {
                    $job->update(['is_active' => false]);
                    $stats['deactivated']++;
                    $stats['url_dead']++;
                    continue;
                }

                $body = Str::lower(strip_tags($response->body()));
                $expiredByText = collect([
                    'this job is no longer available',
                    'position has been filled',
                    'job has expired',
                    'lowongan ditutup',
                    'posisi sudah terisi',
                    'vacancy closed',
                ])->contains(fn (string $signal) => Str::contains($body, $signal));

                $expiredAt = $this->extractExpiredDate($body);
                $isExpiredByDate = $expiredAt !== null && $expiredAt->isPast();

                if ($expiredByText || $isExpiredByDate) {
                    $job->update([
                        'is_active' => false,
                        'expires_at' => $expiredAt?->toDateTimeString() ?? now()->toDateTimeString(),
                    ]);
                    $stats['deactivated']++;
                    $stats['expired']++;
                }
            } catch (\Throwable $e) {
                $stats['errors']++;
                Log::warning('Prune expired job check failed', [
                    'job_id' => $job->id,
                    'source_url' => $job->source_url,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $stats;
    }

    private function extractExpiredDate(string $text): ?Carbon
    {
        if (! preg_match('/(deadline|berakhir|ditutup|expired)\s*[:\-]?\s*(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/i', $text, $m)) {
            return null;
        }

        $raw = str_replace('/', '-', $m[2]);
        foreach (['d-m-Y', 'd-m-y', 'm-d-Y', 'Y-m-d'] as $format) {
            try {
                return Carbon::createFromFormat($format, $raw);
            } catch (\Throwable) {
            }
        }

        return null;
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
     * @param array<string, mixed> $sourceConfig
     * @return array<int, array<string, mixed>>
     */
    private function fetchDeallsJobs(array $sourceConfig): array
    {
        $response = Http::timeout(45)
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 LamarajaBot/1.0'])
            ->get($sourceConfig['list_url']);

        if ($response->failed()) {
            throw new \RuntimeException("Dealls returned HTTP {$response->status()}");
        }

        $docs = $this->extractDeallsDocs($response->body());
        $jobs = [];

        foreach ($docs as $doc) {
            $company = $doc['company'] ?? [];
            $logo = $company['logoUrl'] ?? $company['headerLogoUrl'] ?? $company['faviconUrl'] ?? null;
            $companyName = $company['name'] ?? null;
            $companySlug = $company['slug'] ?? null;
            $roleSlug = $doc['slug'] ?? null;
            $detailSlug = $roleSlug && $companySlug ? "{$roleSlug}~{$companySlug}" : $roleSlug;
            $sourceUrl = $detailSlug ? 'https://dealls.com/loker/' . $detailSlug : null;
            $location = $doc['city']['name'] ?? $doc['location']['city']['name'] ?? $doc['country']['name'] ?? 'Indonesia';
            $salaryRange = $doc['salaryRange'] ?? null;

            if ($sourceUrl && $this->deallsDescription($doc) === '') {
                $doc = array_replace_recursive($doc, $this->fetchDeallsJobDetail($sourceUrl));
                $company = $doc['company'] ?? $company;
                $logo = $logo ?: ($company['logoUrl'] ?? $company['headerLogoUrl'] ?? $company['faviconUrl'] ?? null);
                $location = $doc['city']['name'] ?? $doc['location']['city']['name'] ?? $location;
                $salaryRange = $doc['salaryRange'] ?? $salaryRange;
            }

            $skills = collect($doc['skills'] ?? [])->pluck('name')->filter()->take(3)->values()->all();
            $description = $this->deallsDescription($doc);
            $tags = array_values(array_unique(array_filter(array_merge(
                $sourceConfig['default_tags'] ?? [],
                [$doc['workplaceType'] ?? null],
                $skills
            ))));

            $jobs[] = [
                'title' => trim((string) ($doc['role'] ?? '')),
                'company' => trim((string) $companyName),
                'company_logo' => $logo,
                'location' => $location,
                'employment_type' => $this->mapEmploymentType($doc['employmentTypes'][0] ?? null),
                'salary_min' => is_array($salaryRange) ? ($salaryRange['start'] ?? null) : null,
                'salary_max' => is_array($salaryRange) ? ($salaryRange['end'] ?? null) : null,
                'salary_currency' => 'IDR',
                'description_raw' => $description,
                'summary_ai' => null,
                'tags' => array_slice($tags, 0, 5),
                'source_url' => $sourceUrl,
            ];
        }

        return $jobs;
    }


    /**
     * @return array<string, mixed>
     */
    private function fetchDeallsJobDetail(string $url): array
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 LamarajaBot/1.0'])
                ->get($url);

            if ($response->failed()) {
                return [];
            }

            if (! preg_match('/<script[^>]+id=["\']__NEXT_DATA__["\'][^>]*>(.*?)<\/script>/s', $response->body(), $match)) {
                return [];
            }

            $payload = json_decode($match[1], true);
            if (! is_array($payload)) {
                return [];
            }

            return $payload['props']['pageProps']['dehydratedState']['queries'][0]['state']['data'] ?? [];
        } catch (\Throwable $e) {
            Log::warning('Dealls detail fetch failed', ['url' => $url, 'error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function extractDeallsDocs(string $html): array
    {
        if (! preg_match('/<script[^>]+id=["\']__NEXT_DATA__["\'][^>]*>(.*?)<\/script>/s', $html, $match)) {
            throw new \RuntimeException('Dealls Next.js data not found');
        }

        $payload = json_decode($match[1], true);
        if (! is_array($payload)) {
            throw new \RuntimeException('Dealls Next.js data is invalid JSON');
        }

        return $payload['props']['pageProps']['dehydratedState']['queries'][0]['state']['data']['pages'][0]['docs'] ?? [];
    }

    /**
     * @param array<string, mixed> $doc
     */
    private function deallsDescription(array $doc): string
    {
        $parts = [];
        if (! empty($doc['description'])) {
            $parts[] = '<h3>Tentang Pekerjaan</h3>' . $doc['description'];
        }
        if (! empty($doc['responsibilities'])) {
            $parts[] = '<h3>Tanggung Jawab</h3>' . $doc['responsibilities'];
        }
        if (! empty($doc['requirements'])) {
            $parts[] = '<h3>Kualifikasi</h3>' . $doc['requirements'];
        }
        if (! empty($doc['company']['description'])) {
            $parts[] = '<h3>Tentang Perusahaan</h3>' . $doc['company']['description'];
        }

        return trim(implode("\n", $parts));
    }

    private function mapEmploymentType(?string $type): string
    {
        return match ($type) {
            'partTime' => 'part-time',
            'contract' => 'contract',
            'internship' => 'internship',
            'freelance' => 'freelance',
            default => 'full-time',
        };
    }

    /**
     * @param array<int, array<string, mixed>> $jobs
     * @param array<string, mixed> $sourceConfig
     * @return array<int, array<string, mixed>>
     */
    private function completeJobsOnly(array $jobs, array $sourceConfig): array
    {
        $valid = [];
        $defaultLogo = $sourceConfig['company_logo'] ?? null;

        foreach ($jobs as $job) {
            $job['company_logo'] = $job['company_logo'] ?: $defaultLogo;

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
