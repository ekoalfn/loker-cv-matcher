<?php

namespace App\Services;

use App\Contracts\AiServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService implements AiServiceInterface
{
    private string $baseUrl;
    private string $apiKey;
    private int $timeout;
    private int $maxTokens;
    private AiResponseParser $parser;

    public function __construct(AiResponseParser $parser)
    {
        $this->parser = $parser;
        $this->baseUrl = rtrim(config('laravel-openrouter.api_endpoint', 'https://openrouter.ai/api/v1/'), '/');
        $this->apiKey = config('laravel-openrouter.api_key', '');
        $this->timeout = (int) config('laravel-openrouter.api_timeout', 60);
        $this->maxTokens = (int) config('ai.limits.max_tokens_per_request', 2048);
    }

    public function chat(string $prompt, ?string $model = null): array
    {
        $model = $model ?? config('ai.models.summarizer');
        return $this->callApi([
            ['role' => 'user', 'content' => $prompt],
        ], $model);
    }

    public function summarizeJob(string $jobDescription): array
    {
        $system = config('prompts.job_summary.system', 'Ringkas lowongan dalam JSON.');
        $user = str_replace('{{job_description}}', $jobDescription, config('prompts.job_summary.user', $jobDescription));

        $result = $this->callApi([
            ['role' => 'user', 'content' => "[Instruksi]\n{$system}\n\n[Permintaan]\n{$user}"],
        ], config('ai.models.summarizer'));

        $parsed = $this->parser->parseJobSummary($result['content']);

        return [
            'summary' => $parsed['summary'],
            'tags' => $parsed['tags'],
            'model' => $result['model'],
            'tokens_used' => $result['tokens_used'],
        ];
    }

    public function matchCv(string $cvText, string $jobDescription): array
    {
        $system = config('prompts.cv_matcher.system', 'Analisis kecocokan CV.');
        $user = str_replace(
            ['{{cv_text}}', '{{job_description}}'],
            [$cvText, $jobDescription],
            config('prompts.cv_matcher.user', "CV:\n{$cvText}\n\nLowongan:\n{$jobDescription}")
        );

        $result = $this->callApi([
            ['role' => 'user', 'content' => "[Instruksi]\n{$system}\n\n[Permintaan]\n{$user}"],
        ], config('ai.models.cv_matcher'));

        Log::info('OpenRouter raw CV match response', [
            'model' => $result['model'],
            'tokens' => $result['tokens_used'],
            'content_length' => strlen($result['content']),
            'content_preview' => mb_substr($result['content'], 0, 500),
        ]);

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
     * Direct HTTP call to OpenRouter API.
     * Same pattern as Meta-Trader-Bot: OpenAI-compatible endpoint.
     */
    private function callApi(array $messages, string $model): array
    {
        $fallback = config('ai.models.fallback');

        // Try primary model
        $result = $this->attempt($messages, $model);
        if ($result !== null) {
            return $result;
        }

        // Try fallback
        if ($fallback && $fallback !== $model) {
            Log::warning('OpenRouter: primary failed, trying fallback', compact('model', 'fallback'));
            $result = $this->attempt($messages, $fallback);
            if ($result !== null) {
                return $result;
            }
        }

        return ['content' => '', 'model' => $model, 'tokens_used' => 0];
    }

    private function attempt(array $messages, string $model): ?array
    {
        try {
            $payload = [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.3,
                'max_tokens' => $this->maxTokens,
            ];

            Log::info('OpenRouter API request', [
                'model' => $model,
                'max_tokens' => $this->maxTokens,
                'message_count' => count($messages),
                'first_message_length' => isset($messages[0]['content']) ? strlen($messages[0]['content']) : 0,
            ]);

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'HTTP-Referer' => config('app.url', 'http://localhost'),
                'X-Title' => config('laravel-openrouter.title', 'Lamaraja'),
            ])
                ->timeout($this->timeout)
                ->post("{$this->baseUrl}/chat/completions", $payload);

            if ($response->failed()) {
                Log::warning('OpenRouter HTTP failed', [
                    'model' => $model,
                    'status' => $response->status(),
                    'body' => mb_substr($response->body(), 0, 300),
                ]);
                return null;
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '';
            $usedModel = $data['model'] ?? $model;
            $tokensUsed = $data['usage']['total_tokens'] ?? 0;

            if (empty(trim($content))) {
                Log::error('OpenRouter empty content', [
                    'model' => $usedModel,
                    'tokens_used' => $tokensUsed,
                    'finish_reason' => $data['choices'][0]['finish_reason'] ?? 'unknown',
                    'reasoning_length' => isset($data['choices'][0]['message']['reasoning']) ? strlen($data['choices'][0]['message']['reasoning']) : 0,
                    'raw_choices' => json_encode($data['choices'] ?? []),
                    'full_response' => json_encode($data),
                    'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                    'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
                ]);
                return null;
            }

            return [
                'content' => $content,
                'model' => $usedModel,
                'tokens_used' => (int) $tokensUsed,
            ];

        } catch (\Throwable $e) {
            Log::warning('OpenRouter exception', ['model' => $model, 'error' => $e->getMessage()]);
            return null;
        }
    }
}
