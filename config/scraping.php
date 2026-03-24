<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Key untuk n8n Ingestion
    |--------------------------------------------------------------------------
    |
    | API key yang digunakan n8n untuk mengautentikasi request
    | ke endpoint /api/v1/jobs/ingest.
    |
    */

    'api_key' => env('SCRAPING_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Ingest Configuration
    |--------------------------------------------------------------------------
    */

    'ingest' => [
        'max_payload_size' => env('INGEST_MAX_PAYLOAD_MB', 10),
        'rate_limit_per_minute' => env('INGEST_RATE_LIMIT', 100),
    ],

];
