<?php

use App\Http\Controllers\CvScanController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MockInterviewController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;


Route::domain('promo.lamaraja.web.id')->group(function (): void {
    Route::view('/', 'pages.portfolio')->name('portfolio.promo');
});

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-jobs-{page}.xml', [SitemapController::class, 'jobs'])->whereNumber('page')->name('sitemap.jobs');

// Home
Route::get('/', [JobController::class, 'index'])->name('home');

// Jobs
Route::get('/jobs', [JobController::class, 'list'])->name('jobs.index');
Route::get('/lowongan/{slug}', [JobController::class, 'landing'])->name('jobs.landing');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{slug}/apply', [JobController::class, 'apply'])->name('jobs.apply');

// Static Pages
Route::view('/about', 'pages.about')->name('about');

// CV Scan
Route::get('/cv-matcher', [CvScanController::class, 'index'])->name('cv-matcher.index');
Route::post('/cv-scan', [CvScanController::class, 'store'])->name('cv-scan.store');
Route::get('/cv-scan/{id}/status', [CvScanController::class, 'status'])->name('cv-scan.status');

// Mock Interview
Route::get('/mock-interview', [MockInterviewController::class, 'index'])->name('mock-interview.index');
Route::post('/mock-interview/start', [MockInterviewController::class, 'start'])->name('mock-interview.start');
Route::get('/mock-interview/{token}', [MockInterviewController::class, 'show'])->name('mock-interview.show');
Route::post('/mock-interview/{token}/reply', [MockInterviewController::class, 'reply'])->name('mock-interview.reply');
Route::post('/mock-interview/{token}/finish', [MockInterviewController::class, 'finish'])->name('mock-interview.finish');

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
