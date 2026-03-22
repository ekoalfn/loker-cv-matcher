<?php

namespace App\Services;

use App\Contracts\AiServiceInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService implements AiServiceInterface
{
    private string $baseUrl;

    private string $apiKey;

    private int $timeout;

    private int $maxAttempts;

    private int $retryDelayMs;

    private int $retryMultiplier;

    private int $maxTokens;

    private AiResponseParser $parser;

    public function __construct(AiResponseParser $parser)
    {
        $this->parser = $parser;
        $this->baseUrl = rtrim(config('ai.providers.openrouter.base_url', 'https://openrouter.ai/api/v1'), '/');
        $this->apiKey = config('ai.providers.openrouter.api_key', '');
        $this->timeout = (int) config('ai.providers.openrouter.timeout', 60);
        $this->maxAttempts = (int) config('ai.retry.max_attempts', 3);
        $this->retryDelayMs = (int) config('ai.retry.delay_ms', 1000);
        $this->retryMultiplier = (int) config('ai.retry.multiplier', 2);
        $this->maxTokens = (int) config('ai.limits.max_tokens_per_request', 2048);
    }

    /**
     * {@inheritDoc}
     */
    public function chat(string $prompt, ?string $model = null): array
    {
        $model = $model ?? config('ai.models.summarizer', 'meta-llama/llama-3.1-8b-instruct:free');
        $fallbackModel = config('ai.models.fallback', 'mistralai/mistral-7b-instruct:free');

        // Coba model utama
        $result = $this->attemptChat($prompt, $model);
        if ($result !== null) {
            return $result;
        }

        // Fallback ke model cadangan jika model utama gagal
        if ($model !== $fallbackModel) {
            Log::warning('OpenRouter: primary model failed, trying fallback', [
                'primary_model' => $model,
                'fallback_model' => $fallbackModel,
            ]);

            $result = $this->attemptChat($prompt, $fallbackModel);
            if ($result !== null) {
                return $result;
            }
        }

        // Semua model gagal
        Log::error('OpenRouter: all models failed', [
            'primary_model' => $model,
            'fallback_model' => $fallbackModel,
        ]);

        return [
            'content' => '',
            'model' => $model,
            'tokens_used' => 0,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function summarizeJob(string $jobDescription): array
    {
        $model = config('ai.models.summarizer', 'meta-llama/llama-3.1-8b-instruct:free');

        $systemPrompt = 'Kamu adalah asisten HR profesional. Tugasmu adalah meringkas deskripsi lowongan kerja. '
            . 'Balas HANYA dalam format JSON valid berikut, tanpa teks tambahan: '
            . '{"summary": "Ringkasan dalam 2 kalimat.", "tags": ["tag1", "tag2", "tag3"]}';

        $userPrompt = "Ringkas deskripsi lowongan berikut menjadi 2 kalimat dan extract 3-5 tags relevan:\n\n{$jobDescription}";

        $prompt = $this->composePrompt($systemPrompt, $userPrompt);

        $result = $this->chat($prompt, $model);

        $parsed = $this->parser->parseJobSummary($result['content']);

        return [
            'summary' => $parsed['summary'],
            'tags' => $parsed['tags'],
            'model' => $result['model'],
            'tokens_used' => $result['tokens_used'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function matchCv(string $cvText, string $jobDescription): array
    {
        $model = config('ai.models.cv_matcher', 'meta-llama/llama-3.1-8b-instruct:free');

        $systemPrompt = 'Kamu adalah recruiter profesional yang menganalisis kecocokan CV dengan lowongan kerja. '
            . 'Balas HANYA dalam format JSON valid berikut, tanpa teks tambahan: '
            . '{"match_score": 75, "strengths": ["strength1"], "weaknesses": ["weakness1"], "suggestions": ["suggestion1"]}';

        $userPrompt = "Analisis kecocokan CV berikut dengan deskripsi lowongan.\n\n"
            . "=== CV ===\n{$cvText}\n\n"
            . "=== Deskripsi Lowongan ===\n{$jobDescription}\n\n"
            . "Berikan match_score (0-100), strengths, weaknesses, dan suggestions.";

        $prompt = $this->composePrompt($systemPrompt, $userPrompt);

        $result = $this->chat($prompt, $model);

        $parsed = $this->parser->parseCvMatch($result['content']);

        return [
            'match_score' => $parsed['match_score'],
            'strengths' => $parsed['strengths'],
            'weaknesses' => $parsed['weaknesses'],
            'suggestions' => $parsed['suggestions'],
            'model' => $result['model'],
            'tokens_used' => $result['tokens_used'],
        ];
    }

    /**
     * Attempt chat request dengan retry logic (exponential backoff).
     *
     * @return array{content: string, model: string, tokens_used: int}|null
     */
    private function attemptChat(string $prompt, string $model): ?array
    {
        $delayMs = $this->retryDelayMs;

        for ($attempt = 1; $attempt <= $this->maxAttempts; $attempt++) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'HTTP-Referer' => config('app.url', 'http://localhost'),
                    'X-Title' => config('app.name', 'Portal Loker'),
                    'Content-Type' => 'application/json',
                ])
                    ->timeout($this->timeout)
                    ->post("{$this->baseUrl}/chat/completions", [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.3,
                        'max_tokens' => $this->maxTokens,
                    ]);

                if ($response->failed()) {
                    Log::warning('OpenRouter: HTTP request failed', [
                        'model' => $model,
                        'attempt' => $attempt,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    if ($attempt < $this->maxAttempts) {
                        usleep($delayMs * 1000);
                        $delayMs *= $this->retryMultiplier;
                    }

                    continue;
                }

                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';
                $usedModel = $data['model'] ?? $model;
                $tokensUsed = $data['usage']['total_tokens'] ?? 0;

                Log::info('OpenRouter: request successful', [
                    'model' => $usedModel,
                    'tokens_used' => $tokensUsed,
                    'attempt' => $attempt,
                ]);

                return [
                    'content' => $content,
                    'model' => $usedModel,
                    'tokens_used' => (int) $tokensUsed,
                ];

            } catch (RequestException $e) {
                Log::warning('OpenRouter: request exception', [
                    'model' => $model,
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                ]);
            } catch (\Throwable $e) {
                Log::error('OpenRouter: unexpected error', [
                    'model' => $model,
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                ]);
            }

            if ($attempt < $this->maxAttempts) {
                usleep($delayMs * 1000);
                $delayMs *= $this->retryMultiplier;
            }
        }

        Log::error('OpenRouter: all retry attempts exhausted', [
            'model' => $model,
            'max_attempts' => $this->maxAttempts,
        ]);

        return null;
    }

    /**
     * Compose prompt dengan system prompt dan user prompt.
     *
     * Format gabungan untuk dikirim sebagai single user message,
     * karena beberapa model free tidak mendukung system messages.
     */
    private function composePrompt(string $systemPrompt, string $userPrompt): string
    {
        return "[System Instructions]\n{$systemPrompt}\n\n[User Request]\n{$userPrompt}";
    }
}
