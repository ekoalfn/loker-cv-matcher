<?php

namespace App\Contracts;

interface AiServiceInterface
{
    /**
     * Kirim prompt ke AI dan dapatkan response.
     *
     * @param  string  $prompt
     * @param  string|null  $model  Override model default
     * @return array{content: string, model: string, tokens_used: int}
     */
    public function chat(string $prompt, ?string $model = null): array;

    /**
     * Ringkas deskripsi lowongan menjadi 2 kalimat.
     *
     * @param  string  $jobDescription
     * @return array{summary: string, tags: string[], model: string, tokens_used: int}
     */
    public function summarizeJob(string $jobDescription): array;

    /**
     * Analisis kecocokan CV terhadap lowongan.
     *
     * @param  string  $cvText
     * @param  string  $jobDescription
     * @return array{match_score: int, strengths: string[], weaknesses: string[], suggestions: string[], model: string, tokens_used: int}
     */
    public function matchCv(string $cvText, string $jobDescription): array;
}
