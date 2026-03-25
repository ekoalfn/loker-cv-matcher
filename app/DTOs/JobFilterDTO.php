<?php

namespace App\DTOs;

class JobFilterDTO
{
    public function __construct(
        public readonly ?string $keyword = null,
        public readonly ?string $location = null,
        public readonly ?array $employmentType = null,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}

    public static function fromRequest(array $data): self
    {
        $empType = $data['employment_type'] ?? null;

        // Normalize: string → array, empty array → null
        if (is_string($empType)) {
            $empType = [$empType];
        }
        if (is_array($empType) && count($empType) === 0) {
            $empType = null;
        }

        return new self(
            keyword: $data['keyword'] ?? null,
            location: $data['location'] ?? null,
            employmentType: $empType,
            perPage: (int) ($data['per_page'] ?? 15),
            page: (int) ($data['page'] ?? 1),
        );
    }
}
