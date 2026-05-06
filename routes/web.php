<?php

use App\Http\Controllers\CvScanController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Home
Route::get('/', [JobController::class, 'index'])->name('home');

// Jobs
Route::get('/jobs', [JobController::class, 'list'])->name('jobs.index');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{slug}/apply', [JobController::class, 'apply'])->name('jobs.apply');

// CV Scan
Route::post('/cv-scan', [CvScanController::class, 'store'])->name('cv-scan.store');
Route::get('/cv-scan/{id}/status', [CvScanController::class, 'status'])->name('cv-scan.status');

// Scraper Admin (password-protected via session)
Route::prefix('scraper')->group(function () {
    Route::get('/login', [ScraperController::class, 'login'])->name('scraper.login');
    Route::post('/login', [ScraperController::class, 'authenticate'])->name('scraper.authenticate');
    Route::get('/dashboard', [ScraperController::class, 'dashboard'])->name('scraper.dashboard');
    Route::post('/logout', [ScraperController::class, 'logout'])->name('scraper.logout');

    // AJAX scraping tools
    Route::post('/scrape-url', [ScraperController::class, 'scrapeUrl'])->name('scraper.scrape-url');
    Route::post('/scrape-html', [ScraperController::class, 'scrapeHtml'])->name('scraper.scrape-html');
    Route::post('/fetch-preview', [ScraperController::class, 'fetchPreview'])->name('scraper.fetch-preview');
    Route::post('/ingest-jobs', [ScraperController::class, 'ingestJobs'])->name('scraper.ingest-jobs');
    Route::get('/queue-status', [ScraperController::class, 'queueStatus'])->name('scraper.queue-status');
});
