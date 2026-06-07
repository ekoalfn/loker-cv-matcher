<?php

namespace App\Services;

use App\Contracts\AiServiceInterface;
use App\Models\Job;
use Illuminate\Support\Facades\Log;

/**
 * Career tools powered by AI: ATS summary, cover letter, CV rewrite,
 * skill gap, job fit, career path, interview question generator and a
 * lightweight public interview demo.
 *
 * Uses the generic chat() method on the AI service so we don't bloat
 * the AiServiceInterface contract with one method per feature.
 */
class CareerToolsService
{
    public function __construct(
        private readonly AiServiceInterface $ai,
    ) {}

    /**
     * Draft an ATS-friendly professional summary.
     *
     * @return array{summary: string, keywords: string[], model: string, tokens_used: int}
     */
    public function atsSummary(string $cvText, ?Job $job = null): array
    {
        $data = $this->run('ats_summary', [
            '{{cv_text}}' => $this->trimCv($cvText),
            '{{job_description}}' => $job ? $this->jobText($job) : '-',
        ]);

        return [
            'summary' => $this->str($data['parsed']['summary'] ?? ''),
            'keywords' => $this->stringList($data['parsed']['keywords'] ?? []),
            'model' => $data['model'],
            'tokens_used' => $data['tokens_used'],
        ];
    }

    /**
     * Generate a cover letter.
     *
     * @return array{cover_letter: string, highlights: string[], model: string, tokens_used: int}
     */
    public function coverLetter(string $cvText, Job $job, string $tone = 'profesional'): array
    {
        $data = $this->run('cover_letter', [
            '{{cv_text}}' => $this->trimCv($cvText),
            '{{job_description}}' => $this->jobText($job),
            '{{tone}}' => $tone,
        ]);

        return [
            'cover_letter' => $this->str($data['parsed']['cover_letter'] ?? ''),
            'highlights' => $this->stringList($data['parsed']['highlights'] ?? []),
            'model' => $data['model'],
            'tokens_used' => $data['tokens_used'],
        ];
    }

    /**
     * Rewrite work experience into ATS-friendly bullets.
     */
    public function cvRewrite(string $cvText, string $targetRole): array
    {
        $data = $this->run('cv_rewrite', [
            '{{cv_text}}' => $this->trimCv($cvText),
            '{{target_role}}' => $targetRole ?: 'Posisi sesuai CV',
        ]);

        $parsed = $data['parsed'];

        return [
            'rewritten_bullets' => $this->stringList($parsed['rewritten_bullets'] ?? []),
            'before_after' => $this->pairs($parsed['before_after'] ?? [], 'before', 'after'),
            'tips' => $this->stringList($parsed['tips'] ?? []),
            'model' => $data['model'],
            'tokens_used' => $data['tokens_used'],
        ];
    }

    /**
     * Analyze the skill gap for a target role.
     */
    public function skillGap(string $cvText, string $targetRole, ?Job $job = null): array
    {
        $data = $this->run('skill_gap', [
            '{{cv_text}}' => $this->trimCv($cvText),
            '{{target_role}}' => $targetRole ?: ($job?->title ?? 'Posisi sesuai CV'),
            '{{job_description}}' => $job ? $this->jobText($job) : '-',
        ]);

        $parsed = $data['parsed'];

        return [
            'readiness_score' => $this->score($parsed['readiness_score'] ?? 0),
            'matched_skills' => $this->stringList($parsed['matched_skills'] ?? []),
            'missing_skills' => $this->stringList($parsed['missing_skills'] ?? []),
            'learning_plan' => $this->objects($parsed['learning_plan'] ?? [], ['skill', 'why', 'how']),
            'model' => $data['model'],
            'tokens_used' => $data['tokens_used'],
        ];
    }

    /**
     * Explain why a candidate fits (or not) a job.
     */
    public function jobFit(string $cvText, Job $job): array
    {
        $data = $this->run('job_fit', [
            '{{cv_text}}' => $this->trimCv($cvText),
            '{{job_description}}' => $this->jobText($job),
        ]);

        $parsed = $data['parsed'];

        return [
            'fit_score' => $this->score($parsed['fit_score'] ?? 0),
            'verdict' => $this->str($parsed['verdict'] ?? ''),
            'reasons_fit' => $this->stringList($parsed['reasons_fit'] ?? []),
            'reasons_gap' => $this->stringList($parsed['reasons_gap'] ?? []),
            'next_steps' => $this->stringList($parsed['next_steps'] ?? []),
            'model' => $data['model'],
            'tokens_used' => $data['tokens_used'],
        ];
    }

    /**
     * Recommend the next career steps.
     */
    public function careerPath(string $cvText): array
    {
        $data = $this->run('career_path', [
            '{{cv_text}}' => $this->trimCv($cvText),
        ]);

        $parsed = $data['parsed'];

        return [
            'current_level' => $this->str($parsed['current_level'] ?? ''),
            'next_roles' => $this->objects($parsed['next_roles'] ?? [], ['role', 'why', 'timeline']),
            'long_term_goal' => $this->str($parsed['long_term_goal'] ?? ''),
            'skills_to_build' => $this->stringList($parsed['skills_to_build'] ?? []),
            'model' => $data['model'],
            'tokens_used' => $data['tokens_used'],
        ];
    }

    /**
     * Generate interview questions from a job description.
     */
    public function interviewQuestions(Job $job): array
    {
        $data = $this->run('interview_questions', [
            '{{job_description}}' => $this->jobText($job),
            '{{target_role}}' => $job->title,
        ]);

        return [
            'questions' => $this->objects($data['parsed']['questions'] ?? [], ['category', 'question', 'tip']),
            'model' => $data['model'],
            'tokens_used' => $data['tokens_used'],
        ];
    }

    /**
     * Public demo: generate a single opening interview question for a role.
     */
    public function demoQuestion(string $targetRole): string
    {
        $system = config('prompts.interview_demo.question_system');
        $result = $this->ai->chat("[Instruksi]\n{$system}\n\n[Permintaan]\nTarget role: {$targetRole}", config('ai.models.mock_interview'));
        $parsed = $this->decode($result['content']);

        return $this->str($parsed['question'] ?? '')
            ?: "Ceritakan tentang diri Anda dan kenapa tertarik dengan posisi {$targetRole}.";
    }

    /**
     * Public demo: give feedback on a single answer.
     *
     * @return array{score: int, feedback: string, better_answer: string}
     */
    public function demoFeedback(string $targetRole, string $question, string $answer): array
    {
        $system = config('prompts.interview_demo.feedback_system');
        $prompt = "[Instruksi]\n{$system}\n\n[Permintaan]\nTarget role: {$targetRole}\nPertanyaan: {$question}\nJawaban kandidat: {$answer}";
        $result = $this->ai->chat($prompt, config('ai.models.mock_interview'));
        $parsed = $this->decode($result['content']);

        return [
            'score' => $this->score($parsed['score'] ?? 0),
            'feedback' => $this->str($parsed['feedback'] ?? '') ?: 'Jawaban sudah cukup baik. Coba tambahkan contoh konkret agar lebih meyakinkan.',
            'better_answer' => $this->str($parsed['better_answer'] ?? ''),
        ];
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Run a configured prompt and return parsed JSON + metadata.
     *
     * @param  array<string, string>  $replacements
     * @return array{parsed: array<string, mixed>, model: string, tokens_used: int}
     */
    private function run(string $key, array $replacements): array
    {
        $system = config("prompts.$key.system", '');
        $user = strtr(config("prompts.$key.user", ''), $replacements);

        $result = $this->ai->chat("[Instruksi]\n{$system}\n\n[Permintaan]\n{$user}", config('ai.models.cv_matcher'));
        $parsed = $this->decode($result['content']);

        if ($parsed === null) {
            Log::warning('CareerTools: failed to parse AI JSON', [
                'tool' => $key,
                'preview' => mb_substr($result['content'], 0, 300),
            ]);
        }

        return [
            'parsed' => $parsed ?? [],
            'model' => $result['model'],
            'tokens_used' => $result['tokens_used'],
        ];
    }

    /**
     * Decode JSON from a raw AI response, tolerating markdown/code fences.
     *
     * @return array<string, mixed>|null
     */
    private function decode(string $content): ?array
    {
        $content = trim($content);

        $decoded = json_decode($content, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/```(?:json)?\s*\n?([\s\S]*?)\n?\s*```/', $content, $m)) {
            $decoded = json_decode(trim($m[1]), true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        if (preg_match('/\{[\s\S]*\}/', $content, $m)) {
            $decoded = json_decode($m[0], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }

    private function trimCv(string $cvText): string
    {
        return mb_substr(trim($cvText), 0, (int) config('mock_interview.max_cv_chars', 12000));
    }

    private function jobText(Job $job): string
    {
        return trim(sprintf(
            "Posisi: %s\nPerusahaan: %s\nLokasi: %s\nTipe: %s\nTags: %s\nRingkasan: %s\nDeskripsi: %s",
            $job->title,
            $job->company,
            $job->location ?: '-',
            $job->employment_type?->value ?? (string) $job->employment_type,
            is_array($job->tags) ? implode(', ', $job->tags) : '-',
            $job->summary_ai ?: '-',
            mb_substr((string) ($job->description_raw ?: $job->title), 0, 2500),
        ));
    }

    private function str(mixed $value): string
    {
        return is_string($value) ? trim(strip_tags($value)) : '';
    }

    private function score(mixed $value): int
    {
        return max(0, min(100, is_numeric($value) ? (int) $value : 0));
    }

    /**
     * @return string[]
     */
    private function stringList(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn (mixed $item): string => is_scalar($item) ? trim(strip_tags((string) $item)) : '',
            $items,
        )));
    }

    /**
     * Normalize an array of objects keeping only allowed string keys.
     *
     * @param  list<string>  $keys
     * @return list<array<string, string>>
     */
    private function objects(mixed $items, array $keys): array
    {
        if (! is_array($items)) {
            return [];
        }

        $out = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }
            $row = [];
            foreach ($keys as $key) {
                $row[$key] = $this->str($item[$key] ?? '');
            }
            if (implode('', $row) !== '') {
                $out[] = $row;
            }
        }

        return $out;
    }

    /**
     * @return list<array{before: string, after: string}>
     */
    private function pairs(mixed $items, string $a, string $b): array
    {
        return $this->objects($items, [$a, $b]);
    }
}
