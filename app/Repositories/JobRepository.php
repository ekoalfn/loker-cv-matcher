<?php

namespace App\Repositories;

use App\Contracts\JobRepositoryInterface;
use App\DTOs\JobFilterDTO;
use App\Models\Job;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        $query = $this->filteredBaseQuery($filters);

        if ($filters->keyword) {
            $query->search($filters->keyword);
        }

        $results = $query
            ->orderByDesc('created_at')
            ->paginate(
                perPage: $filters->perPage,
                page: $filters->page,
            );

        if ($filters->keyword && $results->total() === 0) {
            return $this->typoTolerantSearch($filters);
        }

        return $results;
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

    private function filteredBaseQuery(JobFilterDTO $filters): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->model->newQuery()->active();

        if ($filters->location) {
            $query->inLocation($filters->location);
        }

        if ($filters->employmentType) {
            $query->whereIn('employment_type', $filters->employmentType);
        }

        return $query;
    }

    private function typoTolerantSearch(JobFilterDTO $filters): LengthAwarePaginator
    {
        $keyword = Str::of($filters->keyword ?? '')->squish()->lower()->toString();
        $terms = collect(preg_split('/[^a-z0-9]+/i', $keyword) ?: [])
            ->map(fn (string $term): string => Str::lower($term))
            ->filter(fn (string $term): bool => strlen($term) >= 3)
            ->reject(fn (string $term): bool => in_array($term, ['job', 'jobs', 'karir', 'kerja', 'loker', 'lowongan'], true))
            ->values();

        if ($terms->isEmpty()) {
            return new Paginator([], 0, $filters->perPage, $filters->page, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        }

        $matches = $this->filteredBaseQuery($filters)
            ->select(['id', 'title', 'slug', 'company', 'location', 'employment_type', 'salary_min', 'salary_max', 'salary_currency', 'description_raw', 'summary_ai', 'tags', 'source_url', 'company_logo', 'created_at', 'updated_at', 'expires_at', 'is_active'])
            ->orderByDesc('created_at')
            ->limit(400)
            ->get()
            ->map(fn (Job $job): array => ['job' => $job, 'score' => $this->fuzzyScore($job, $terms)])
            ->filter(fn (array $match): bool => $match['score'] >= 62)
            ->sortByDesc('score')
            ->values();

        $items = $matches->pluck('job');
        $pageItems = $items->forPage($filters->page, $filters->perPage)->values();

        return new Paginator($pageItems, $items->count(), $filters->perPage, $filters->page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }

    /**
     * @param  Collection<int, string>  $terms
     */
    private function fuzzyScore(Job $job, Collection $terms): int
    {
        $haystack = Str::of(implode(' ', array_filter([
            $job->title,
            $job->company,
            $job->location,
            is_array($job->tags) ? implode(' ', $job->tags) : (string) $job->tags,
            $job->summary_ai,
            Str::limit((string) $job->description_raw, 1200, ''),
        ])))->lower()->ascii()->toString();

        $words = collect(preg_split('/[^a-z0-9]+/i', $haystack) ?: [])
            ->filter(fn (string $word): bool => strlen($word) >= 3)
            ->unique()
            ->values();

        $total = 0;
        foreach ($terms as $term) {
            $needle = Str::ascii($term);
            $best = $words->max(function (string $word) use ($needle): int {
                similar_text($needle, $word, $percent);

                return (int) round($percent);
            }) ?? 0;
            $total += $best;
        }

        return (int) floor($total / max(1, $terms->count()));
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
