<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | Provider yang digunakan untuk semua AI calls.
    | Saat ini hanya mendukung OpenRouter.
    |
    */

    'default' => env('AI_PROVIDER', 'openrouter'),

    /*
    |--------------------------------------------------------------------------
    | Provider Configurations
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'openrouter' => [
            'api_key' => env('OPENROUTER_API_KEY'),
            'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1'),
            'timeout' => env('AI_TIMEOUT', 60),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Configuration
    |--------------------------------------------------------------------------
    |
    | Model gratis dari OpenRouter yang digunakan.
    | Fallback models digunakan jika model utama gagal.
    |
    */

    'models' => [
        'summarizer' => env('AI_MODEL_SUMMARIZER', 'meta-llama/llama-3.1-8b-instruct:free'),
        'cv_matcher' => env('AI_MODEL_CV_MATCHER', 'meta-llama/llama-3.1-8b-instruct:free'),
        'mock_interview' => env('AI_MODEL_MOCK_INTERVIEW', env('AI_MODEL_CV_MATCHER', 'meta-llama/llama-3.1-8b-instruct:free')),
        'fallback' => env('AI_MODEL_FALLBACK', 'mistralai/mistral-7b-instruct:free'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limits & Budget
    |--------------------------------------------------------------------------
    */

    'limits' => [
        'max_tokens_per_request' => env('AI_MAX_TOKENS', 2048),
        'daily_request_limit' => env('AI_DAILY_LIMIT', 1000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    */

    'retry' => [
        'max_attempts' => 3,
        'delay_ms' => 1000,
        'multiplier' => 2,
    ],

];
