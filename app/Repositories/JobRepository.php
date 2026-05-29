<?php

namespace App\Repositories;

use App\Contracts\JobRepositoryInterface;
use App\DTOs\JobFilterDTO;
use App\Models\Job;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobRepository implements JobRepositoryInterface
{
    public function __construct(
        private readonly Job $model,
    ) {}

    /**
     * Bulk upsert jobs from a scraping source, deduplicating by source_url.
     *
     * @param  array<int, array<string, mixed>>  $jobs
     * @return array{total_received: int, inserted: int, updated: int, skipped: int, errors: array<int, array<string, mixed>>}
     */
    public function upsertFromSource(array $jobs, string $sourceId, ?string $scrapedAt = null): array
    {
        return DB::transaction(function () use ($jobs, $sourceId, $scrapedAt) {
            $stats = [
                'total_received' => count($jobs),
                'inserted' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => [],
            ];

            foreach ($jobs as $index => $jobData) {
                try {
                    $sourceUrl = $jobData['source_url'] ?? null;

                    if (empty($sourceUrl)) {
                        $stats['errors'][] = [
                            'index' => $index,
                            'error' => 'Missing source_url',
                        ];
                        continue;
                    }

                    $existing = $this->model
                        ->withTrashed()
                        ->where('source_url', $sourceUrl)
                        ->first();

                    $attributes = $this->mapJobAttributes($jobData, $sourceId, $scrapedAt);

                    if ($existing) {
                        if ($existing->trashed()) {
                            $existing->restore();
                        }

                        $existing->update($attributes);
                        $stats['updated']++;
                        \App\Jobs\ScrapeJobDetailJob::dispatch($existing);
                    } else {
                        $newJob = $this->model->create($attributes);
                        $stats['inserted']++;
                        \App\Jobs\ScrapeJobDetailJob::dispatch($newJob);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Job upsert failed', [
                        'index' => $index,
                        'source_url' => $jobData['source_url'] ?? 'unknown',
                        'error' => $e->getMessage(),
                    ]);

                    $stats['errors'][] = [
                        'index' => $index,
                        'source_url' => $jobData['source_url'] ?? 'unknown',
                        'error' => 'Failed to process job entry.',
                    ];
                }
            }

            return $stats;
        });
    }

    /**
     * Full-text search with filters and pagination.
     */
    public function search(JobFilterDTO $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->active();

        if ($filters->keyword) {
            $query->search($filters->keyword);
        }

        if ($filters->location) {
            $query->inLocation($filters->location);
        }

        if ($filters->employmentType) {
            $query->whereIn('employment_type', $filters->employmentType);
        }

        return $query
            ->orderByDesc('created_at')
            ->paginate(
                perPage: $filters->perPage,
                page: $filters->page,
            );
    }

    /**
     * Find a job by its unique slug.
     */
    public function findBySlug(string $slug): ?Job
    {
        return $this->model
            ->where('slug', $slug)
            ->active()
            ->first();
    }

    /**
     * Find a job by slug even if it has expired, so detail pages can preserve
     * index equity and guide users to current alternatives instead of 404ing.
     */
    public function findAnyBySlug(string $slug): ?Job
    {
        return $this->model
            ->withTrashed()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Count all active, non-expired jobs.
     */
    public function getActiveCount(): int
    {
        return $this->model->active()->count();
    }

    /**
     * Get recent active jobs ordered by creation date.
     */
    public function getRecentJobs(int $limit = 10): Collection
    {
        return $this->model
            ->active()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get related jobs by same company or same location.
     *
     * Prioritizes same-company matches, then fills remaining slots
     * with same-location matches. Excludes the current job.
     */
    public function getRelatedJobs(Job $job, int $limit = 3): Collection
    {
        // First, try same company
        $related = $this->model
            ->active()
            ->where('id', '!=', $job->id)
            ->where('company', $job->company)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        // If not enough, fill with same location
        if ($related->count() < $limit && $job->location) {
            $remaining = $limit - $related->count();
            $excludeIds = $related->pluck('id')->push($job->id)->toArray();

            $locationJobs = $this->model
                ->active()
                ->whereNotIn('id', $excludeIds)
                ->where('location', $job->location)
                ->orderByDesc('created_at')
                ->limit($remaining)
                ->get();

            $related = $related->merge($locationJobs);
        }

        return $related;
    }

    /**
     * Map raw job data from the ingest payload to model attributes.
     *
     * @param  array<string, mixed>  $jobData
     * @return array<string, mixed>
     */
    private function mapJobAttributes(array $jobData, string $sourceId, ?string $scrapedAt = null): array
    {
        return [
            'source_id' => $sourceId,
            'title' => $jobData['title'],
            'company' => $jobData['company'],
            'location' => $jobData['location'] ?? null,
            'employment_type' => $jobData['employment_type'] ?? null,
            'salary_min' => $jobData['salary_min'] ?? null,
            'salary_max' => $jobData['salary_max'] ?? null,
            'salary_currency' => $jobData['salary_currency'] ?? 'IDR',
            'description_raw' => $jobData['description_raw'] ?? null,
            'summary_ai' => $jobData['summary_ai'] ?? null,
            'tags' => $jobData['tags'] ?? null,
            'company_logo' => $jobData['company_logo'] ?? null,
            'source_url' => $jobData['source_url'],
            'is_active' => true,
            'expires_at' => $jobData['expires_at'] ?? null,
            'scraped_at' => $scrapedAt ?? now(),
        ];
    }
}
