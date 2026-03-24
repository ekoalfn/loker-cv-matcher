<?php

use App\Http\Controllers\Api\JobIngestController;
use App\Http\Middleware\ValidateScrapingApiKey;
use Illuminate\Support\Facades\Route;

Route::middleware(ValidateScrapingApiKey::class)
    ->prefix('v1')
    ->group(function () {
        Route::post('/jobs/ingest', [JobIngestController::class, 'store']);
    });
