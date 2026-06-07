<?php

use App\Http\Controllers\AiToolController;
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
Route::view('/privacy-policy', 'pages.legal.privacy')->name('legal.privacy');
Route::view('/terms-of-service', 'pages.legal.terms')->name('legal.terms');
Route::view('/cookie-policy', 'pages.legal.cookies')->name('legal.cookies');

// CV Scan
Route::get('/cv-matcher', [CvScanController::class, 'index'])->name('cv-matcher.index');
Route::post('/cv-scan', [CvScanController::class, 'store'])->name('cv-scan.store');
Route::get('/cv-scan/{id}/status', [CvScanController::class, 'status'])->name('cv-scan.status');

// AI Career Tools (public, guest rate-limited)
Route::prefix('ai-tools')->name('ai-tools.')->group(function () {
    Route::get('/', [AiToolController::class, 'hub'])->name('index');
    Route::get('/cover-letter', [AiToolController::class, 'coverLetterPage'])->name('cover-letter');
    Route::get('/cv-rewrite', [AiToolController::class, 'cvRewritePage'])->name('cv-rewrite');
    Route::get('/skill-gap', [AiToolController::class, 'skillGapPage'])->name('skill-gap');
    Route::get('/career-path', [AiToolController::class, 'careerPathPage'])->name('career-path');
    Route::get('/interview-practice', [AiToolController::class, 'interviewPracticePage'])->name('interview-practice');

    // AJAX actions
    Route::post('/cover-letter', [AiToolController::class, 'coverLetter'])->name('cover-letter.run');
    Route::post('/cv-rewrite', [AiToolController::class, 'cvRewrite'])->name('cv-rewrite.run');
    Route::post('/skill-gap', [AiToolController::class, 'skillGap'])->name('skill-gap.run');
    Route::post('/career-path', [AiToolController::class, 'careerPath'])->name('career-path.run');
    Route::post('/job-fit', [AiToolController::class, 'jobFit'])->name('job-fit.run');
    Route::post('/interview-questions', [AiToolController::class, 'interviewQuestions'])->name('interview-questions.run');
    Route::post('/interview-demo/question', [AiToolController::class, 'interviewDemoQuestion'])->name('interview-demo.question');
    Route::post('/interview-demo/feedback', [AiToolController::class, 'interviewDemoFeedback'])->name('interview-demo.feedback');
});

// Mock Interview public landing (no password)
Route::get('/latihan-interview', [MockInterviewController::class, 'landing'])->name('mock-interview.landing');

// Mock Interview (public live call + password-protected testing playground)
Route::prefix('mock-interview')->group(function () {
    Route::get('/', [MockInterviewController::class, 'login']);
    Route::get('/login', [MockInterviewController::class, 'login'])->name('mock-interview.login');
    Route::post('/login', [MockInterviewController::class, 'authenticate'])->name('mock-interview.authenticate');
    Route::get('/testing', [MockInterviewController::class, 'index'])->name('mock-interview.index');
    Route::post('/logout', [MockInterviewController::class, 'logout'])->name('mock-interview.logout');

    // Public interview session + voice endpoints (throttled to limit abuse).
    Route::middleware('throttle:30,1')->group(function () {
        Route::post('/start', [MockInterviewController::class, 'start'])->name('mock-interview.start');
        Route::post('/speech', [MockInterviewController::class, 'speech'])->name('mock-interview.speech');
        Route::post('/transcribe', [MockInterviewController::class, 'transcribe'])->name('mock-interview.transcribe');
        Route::post('/{token}/reply', [MockInterviewController::class, 'reply'])->name('mock-interview.reply');
        Route::post('/{token}/finish', [MockInterviewController::class, 'finish'])->name('mock-interview.finish');
    });
    Route::get('/{token}', [MockInterviewController::class, 'show'])->name('mock-interview.show');
});

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
