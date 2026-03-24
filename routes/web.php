<?php

use App\Http\Controllers\CvScanController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [JobController::class, 'index'])->name('home');

// Jobs
Route::get('/jobs', [JobController::class, 'list'])->name('jobs.index');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{slug}/apply', [JobController::class, 'apply'])->name('jobs.apply');

// CV Scan
Route::post('/cv-scan', [CvScanController::class, 'store'])->name('cv-scan.store');
Route::get('/cv-scan/{id}/status', [CvScanController::class, 'status'])->name('cv-scan.status');
