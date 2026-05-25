<?php

return [
    'max_questions' => env('MOCK_INTERVIEW_MAX_QUESTIONS', 6),
    'max_cv_chars' => env('MOCK_INTERVIEW_MAX_CV_CHARS', 12000),
    'daily_guest_limit' => env('MOCK_INTERVIEW_DAILY_GUEST_LIMIT', 3),

    'voice' => [
        'driver' => env('MOCK_INTERVIEW_VOICE_DRIVER', 'browser'),
        'stt_driver' => env('MOCK_INTERVIEW_STT_DRIVER', 'browser'),
        'tts_driver' => env('MOCK_INTERVIEW_TTS_DRIVER', 'browser'),
        'stt_api_key' => env('MOCK_INTERVIEW_STT_API_KEY'),
        'tts_api_key' => env('MOCK_INTERVIEW_TTS_API_KEY'),
    ],
];
