<?php

namespace App\Contracts;

use App\DTOs\JobFilterDTO;
use App\Models\Job;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface JobRepositoryInterface
{
    public function search(JobFilterDTO $filters): LengthAwarePaginator;

    public function findBySlug(string $slug): ?Job;

    public function upsertFromSource(array $jobs, string $sourceId, ?string $scrapedAt = null): array;

    public function getActiveCount(): int;

    public function getRecentJobs(int $limit = 10): Collection;
}
