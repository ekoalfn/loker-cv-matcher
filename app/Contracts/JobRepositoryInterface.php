<?php

namespace App\Contracts;

use App\DTOs\JobFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface JobRepositoryInterface
{
    public function search(JobFilterDTO $filters): LengthAwarePaginator;

    public function findBySlug(string $slug): ?object;

    public function upsertFromSource(array $jobs, string $sourceId): array;

    public function getActiveCount(): int;

    public function getRecentJobs(int $limit = 10): Collection;
}
