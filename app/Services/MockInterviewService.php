<?php

namespace App\Services;

use App\Contracts\AiServiceInterface;
use App\Models\Job;
use App\Models\MockInterviewMessage;
use App\Models\MockInterviewSession;
use Illuminate\Support\Str;

class MockInterviewService
{
    public function __construct(
        private readonly AiServiceInterface $ai,
    ) {}

    public function start(array $data, string $cvText, ?Job $job = null): MockInterviewSession
    {
        $targetRole = $data['target_role'] ?: ($job?->title ?? 'Posisi yang sesuai dengan CV');
        $cvSnapshot = mb_substr($cvText, 0, (int) config('mock_interview.max_cv_chars', 12000));
        $profile = $this->analyzeProfile($cvSnapshot, $targetRole, $job);

        $session = MockInterviewSession::create([
            'user_id' => auth()->id(),
            'job_id' => $job?->id,
            'session_token' => Str::random(48),
            'ip_address' => request()->ip(),
            'target_role' => $targetRole,
            'interview_mode' => $data['interview_mode'] ?? 'mixed',
            'delivery_mode' => $data['delivery_mode'] ?? 'voice',
            'status' => 'active',
            'max_questions' => (int) config('mock_interview.max_questions', 6),
            'cv_text_snapshot' => $cvSnapshot,
            'profile_summary' => $profile['profile'],
            'ai_model_used' => $profile['model'],
            'tokens_used' => $profile['tokens_used'],
            'started_at' => now(),
        ]);

        $question = $this->nextQuestion($session);
        $session->messages()->create([
            'role' => 'interviewer',
            'content_text' => $question['content'],
            'tokens_used' => $question['tokens_used'],
        ]);

        $session->increment('current_question_count');
        $session->increment('tokens_used', $question['tokens_used']);
        $session->update(['ai_model_used' => $question['model']]);

        return $session->refresh();
    }

    public function answer(MockInterviewSession $session, string $answer): MockInterviewMessage
    {
        $session->messages()->create([
            'role' => 'candidate',
            'content_text' => $answer,
        ]);

        if ($session->current_question_count >= $session->max_questions) {
            return $this->finish($session);
        }

        $question = $this->nextQuestion($session->refresh());
        $message = $session->messages()->create([
            'role' => 'interviewer',
            'content_text' => $question['content'],
            'tokens_used' => $question['tokens_used'],
        ]);

        $session->increment('current_question_count');
        $session->increment('tokens_used', $question['tokens_used']);
        $session->update(['ai_model_used' => $question['model']]);

        return $message;
    }

    public function finish(MockInterviewSession $session): MockInterviewMessage
    {
        $result = $this->ai->chat($this->finishPrompt($session), config('ai.models.mock_interview'));
        $feedback = $this->parseJson($result['content']) ?: $this->fallbackFeedback($result['content']);

        $session->update([
            'status' => 'completed',
            'final_feedback' => $feedback,
            'ai_model_used' => $result['model'],
            'tokens_used' => $session->tokens_used + $result['tokens_used'],
            'completed_at' => now(),
        ]);

        return $session->messages()->create([
            'role' => 'system',
            'content_text' => $feedback['summary'] ?? 'Sesi selesai. Feedback sudah dibuat.',
            'feedback' => $feedback,
            'tokens_used' => $result['tokens_used'],
        ]);
    }

    private function analyzeProfile(string $cvText, string $targetRole, ?Job $job): array
    {
        $prompt = "Kamu adalah interview coach Indonesia. Analisis CV untuk persiapan mock interview.\n".
            "Kembalikan JSON valid dengan keys: headline, seniority, core_skills, experience_highlights, risk_gaps, suggested_focus.\n\n".
            "Target role: {$targetRole}\n".
            "Target job: ".$this->jobContext($job)."\n\n".
            "CV:\n{$cvText}";

        $result = $this->ai->chat($prompt, config('ai.models.mock_interview'));

        return [
            'profile' => $this->parseJson($result['content']) ?: ['headline' => mb_substr($result['content'], 0, 500)],
            'model' => $result['model'],
            'tokens_used' => $result['tokens_used'],
        ];
    }

    private function nextQuestion(MockInterviewSession $session): array
    {
        $prompt = "Kamu adalah interviewer profesional Indonesia untuk simulasi interview kerja.\n".
            "Tanyakan SATU pertanyaan saja. Natural, realistis, dan relevan. Jangan beri evaluasi akhir.\n".
            "Mode interview: {$session->interview_mode}. Pertanyaan ke-".($session->current_question_count + 1)." dari {$session->max_questions}.\n".
            "Target role: {$session->target_role}.\n".
            "Profil kandidat JSON: ".json_encode($session->profile_summary, JSON_UNESCAPED_UNICODE)."\n".
            "Riwayat interview:\n".$this->conversationText($session)."\n\n".
            "Balas hanya kalimat pertanyaan interviewer, maksimal 2 kalimat.";

        return $this->ai->chat($prompt, config('ai.models.mock_interview'));
    }

    private function finishPrompt(MockInterviewSession $session): string
    {
        return "Kamu adalah interview coach Indonesia. Evaluasi sesi mock interview berikut.\n".
            "Kembalikan JSON valid dengan keys: overall_score, communication_score, relevance_score, confidence_score, role_fit_score, summary, strengths, weaknesses, improved_answers, action_plan.\n".
            "Skor 0-100. strengths/weaknesses/action_plan berupa array string. improved_answers array object dengan keys original_issue dan better_answer.\n\n".
            "Target role: {$session->target_role}\n".
            "Profil kandidat: ".json_encode($session->profile_summary, JSON_UNESCAPED_UNICODE)."\n".
            "Transcript:\n".$this->conversationText($session);
    }

    private function conversationText(MockInterviewSession $session): string
    {
        return $session->messages()
            ->oldest()
            ->get(['role', 'content_text'])
            ->map(fn (MockInterviewMessage $message): string => strtoupper($message->role).': '.$message->content_text)
            ->implode("\n");
    }

    private function jobContext(?Job $job): string
    {
        if (! $job) {
            return '-';
        }

        return trim(sprintf('%s di %s, lokasi %s. %s', $job->title, $job->company, $job->location ?: '-', mb_substr((string) $job->description_raw, 0, 1200)));
    }

    private function parseJson(string $content): ?array
    {
        if (preg_match('/```(?:json)?\s*(.*?)```/is', $content, $matches)) {
            $content = $matches[1];
        } elseif (preg_match('/\{.*\}/s', $content, $matches)) {
            $content = $matches[0];
        }

        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : null;
    }

    private function fallbackFeedback(string $content): array
    {
        return [
            'overall_score' => 70,
            'summary' => trim($content) ?: 'Interview selesai. Jawaban sudah terekam dan bisa dilatih lagi.',
            'strengths' => [],
            'weaknesses' => [],
            'improved_answers' => [],
            'action_plan' => [],
        ];
    }
}
