<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('lamaraja:refresh-jobs {--limit= : Optional per-source limit for smoke tests} {--prune-limit= : Optional limit for source URL expiry checks} {--skip-prune : Skip source URL expiry checks for quick ingestion-only runs}', function (): int {
    $limit = $this->option('limit') !== null ? (int) $this->option('limit') : null;
    $pruneLimit = $this->option('prune-limit') !== null ? (int) $this->option('prune-limit') : null;
    $service = app(\App\Services\DailyJobRefreshService::class);
    $stats = $service->refresh($limit);
    $stats['pruned'] = $this->option('skip-prune')
        ? ['checked' => 0, 'deactivated' => 0, 'expired' => 0, 'url_dead' => 0, 'errors' => 0, 'skipped' => true]
        : $service->pruneExpiredJobs($pruneLimit);

    $this->info(json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    return empty($stats['errors']) && $stats['pruned']['errors'] === 0 ? 0 : 1;
})->purpose('Refresh Lamaraja job listings from trusted public feeds with complete company logos, then deactivate expired source URLs unless skipped.');


Artisan::command('lamaraja:job-quality', function (): int {
    $active = \App\Models\Job::active()->count();
    $withLogo = \App\Models\Job::active()
        ->whereNotNull('company_logo')
        ->where('company_logo', '<>', '')
        ->count();
    $missingLogo = $active - $withLogo;

    $this->info(json_encode([
        'active' => $active,
        'with_logo' => $withLogo,
        'missing_logo' => $missingLogo,
        'logo_coverage_percent' => $active > 0 ? round(($withLogo / $active) * 100, 2) : 0,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    return $missingLogo === 0 ? 0 : 1;
})->purpose('Report active job quality counts and logo coverage for Lamaraja.');
