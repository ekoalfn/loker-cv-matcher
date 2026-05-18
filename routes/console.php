<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('lamaraja:refresh-jobs {--limit= : Optional per-source limit for smoke tests}', function (): int {
    $limit = $this->option('limit') !== null ? (int) $this->option('limit') : null;
    $stats = app(\App\Services\DailyJobRefreshService::class)->refresh($limit);

    $this->info(json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    return empty($stats['errors']) ? 0 : 1;
})->purpose('Refresh Lamaraja job listings from trusted public feeds with complete company logos.');


Artisan::command('lamaraja:job-quality', function (): int {
    $active = \App\Models\Job::active()->count();
    $missingLogo = \App\Models\Job::active()
        ->where(function ($query): void {
            $query->whereNull('company_logo')->orWhere('company_logo', '');
        })
        ->count();

    $this->info(json_encode([
        'active' => $active,
        'missing_logo' => $missingLogo,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    return $missingLogo === 0 ? 0 : 1;
})->purpose('Report active job quality counts for Lamaraja.');
