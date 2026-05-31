<?php

return [
    'testing_password' => env('MOCK_INTERVIEW_TESTING_PASSWORD', env('SCRAPER_ADMIN_PASSWORD', 'changeme')),
    'max_questions' => env('MOCK_INTERVIEW_MAX_QUESTIONS', 6),
    'max_cv_chars' => env('MOCK_INTERVIEW_MAX_CV_CHARS', 12000),
    'daily_guest_limit' => env('MOCK_INTERVIEW_DAILY_GUEST_LIMIT', 3),

    'voice' => [
        'driver' => env('MOCK_INTERVIEW_VOICE_DRIVER', 'api'),
        'base_url' => env('MOCK_INTERVIEW_VOICE_BASE_URL', 'http://localhost:20128/v1/audio'),
        'api_key' => env('MOCK_INTERVIEW_VOICE_API_KEY'),
        'tts_model' => env('MOCK_INTERVIEW_TTS_MODEL', 'gemini/gemini-2.5-flash-preview-tts/Zephyr'),
        'stt_model' => env('MOCK_INTERVIEW_STT_MODEL', 'groq/whisper-large-v3'),
        'language' => env('MOCK_INTERVIEW_VOICE_LANGUAGE', 'Indonesian'),
        'stt_language' => env('MOCK_INTERVIEW_STT_LANGUAGE', 'id'),
        'timeout' => env('MOCK_INTERVIEW_VOICE_TIMEOUT', 90),
    ],
];
