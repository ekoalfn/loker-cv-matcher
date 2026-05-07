<?php

namespace App\DTOs;

class JobFilterDTO
{
    public function __construct(
        public readonly ?string $keyword = null,
        public readonly ?array $location = null,
        public readonly ?array $employmentType = null,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}

    public static function fromRequest(array $data): self
    {
        $empType = $data['employment_type'] ?? null;
        $location = $data['location'] ?? null;

        // Normalize employment type: string → array, empty array → null
        if (is_string($empType)) {
            $empType = [$empType];
        }
        if (is_array($empType) && count($empType) === 0) {
            $empType = null;
        }

        // Normalize location: string → array, empty array → null
        if (is_string($location)) {
            $location = [$location];
        }
        if (is_array($location) && count($location) === 0) {
            $location = null;
        }

        return new self(
            keyword: $data['keyword'] ?? null,
            location: $location,
            employmentType: $empType,
            perPage: (int) ($data['per_page'] ?? 15),
            page: (int) ($data['page'] ?? 1),
        );
    }
}
