<?php

namespace App\DTOs;

class JobFilterDTO
{
    public function __construct(
        public readonly ?string $keyword = null,
        public readonly ?string $location = null,
        public readonly ?string $employmentType = null,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            keyword: $data['keyword'] ?? null,
            location: $data['location'] ?? null,
            employmentType: $data['employment_type'] ?? null,
            perPage: (int) ($data['per_page'] ?? 15),
            page: (int) ($data['page'] ?? 1),
        );
    }
}
