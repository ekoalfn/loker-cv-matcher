<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\MockInterviewSession;
use App\Services\MockInterviewService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class MockInterviewController extends Controller
{
    public function __construct(
        private readonly MockInterviewService $interviews,
    ) {}

    public function index(Request $request): View
    {
        return view('pages.mock-interview', [
            'jobs' => Job::query()->active()->latest()->limit(30)->get(['id', 'title', 'company', 'location']),
            'selectedJobId' => $request->integer('job_id') ?: null,
        ]);
    }

    public function start(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'target_role' => ['nullable', 'string', 'max:160'],
            'job_id' => ['nullable', 'integer', 'exists:jobs,id'],
            'interview_mode' => ['nullable', 'in:hr,technical,behavioral,mixed'],
            'delivery_mode' => ['nullable', 'in:voice,text'],
        ]);

        $limit = (int) config('mock_interview.daily_guest_limit', 3);
        $todaySessions = MockInterviewSession::query()
            ->where('ip_address', $request->ip())
            ->whereDate('created_at', today())
            ->count();

        if (! auth()->check() && $todaySessions >= $limit) {
            return response()->json(['message' => 'Kamu sudah mencapai batas latihan gratis hari ini.'], 429);
        }

        $path = $request->file('pdf_file')->store('temp-cv');

        try {
            $cvText = $this->extractPdfText($path);
            $job = isset($data['job_id']) ? Job::find($data['job_id']) : null;
            $session = $this->interviews->start($data, $cvText, $job);

            return response()->json($this->sessionPayload($session));
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Mock interview gagal dimulai. '.$e->getMessage(),
            ], 500);
        } finally {
            Storage::delete($path);
        }
    }

    public function show(string $token, Request $request): JsonResponse
    {
        $session = $this->findOwnedSession($token, $request);

        return response()->json($this->sessionPayload($session));
    }

    public function reply(string $token, Request $request): JsonResponse
    {
        $session = $this->findOwnedSession($token, $request);

        if ($session->status !== 'active') {
            return response()->json(['message' => 'Sesi interview sudah selesai.'], 422);
        }

        $data = $request->validate([
            'answer' => ['required', 'string', 'min:2', 'max:6000'],
        ]);

        $message = $this->interviews->answer($session, $data['answer']);
        $session = $session->refresh()->load('messages');

        return response()->json([
            'session' => $this->sessionPayload($session),
            'message' => [
                'role' => $message->role,
                'content_text' => $message->content_text,
                'feedback' => $message->feedback,
            ],
        ]);
    }

    public function finish(string $token, Request $request): JsonResponse
    {
        $session = $this->findOwnedSession($token, $request);

        if ($session->status !== 'completed') {
            $this->interviews->finish($session);
        }

        return response()->json($this->sessionPayload($session->refresh()));
    }

    private function extractPdfText(string $path): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile(Storage::path($path));
        $text = trim($pdf->getText());

        if ($text === '') {
            throw new \RuntimeException('Tidak bisa membaca teks dari PDF. File mungkin berupa scan gambar.');
        }

        return $text;
    }

    private function findOwnedSession(string $token, Request $request): MockInterviewSession
    {
        $session = MockInterviewSession::query()
            ->where('session_token', $token)
            ->with(['messages' => fn ($query) => $query->oldest(), 'job'])
            ->firstOrFail();

        $isOwner = ($session->ip_address === $request->ip())
            || (auth()->check() && $session->user_id === auth()->id());

        abort_unless($isOwner, 403);

        return $session;
    }

    private function sessionPayload(MockInterviewSession $session): array
    {
        $session->loadMissing(['messages' => fn ($query) => $query->oldest(), 'job']);

        return [
            'token' => $session->session_token,
            'status' => $session->status,
            'target_role' => $session->target_role,
            'interview_mode' => $session->interview_mode,
            'delivery_mode' => $session->delivery_mode,
            'current_question_count' => $session->current_question_count,
            'max_questions' => $session->max_questions,
            'profile_summary' => $session->profile_summary,
            'final_feedback' => $session->final_feedback,
            'job' => $session->job ? [
                'id' => $session->job->id,
                'title' => $session->job->title,
                'company' => $session->job->company,
                'url' => route('jobs.show', $session->job),
            ] : null,
            'messages' => $session->messages->map(fn ($message): array => [
                'role' => $message->role,
                'content_text' => $message->content_text,
                'feedback' => $message->feedback,
                'created_at' => $message->created_at?->toIso8601String(),
            ])->values(),
        ];
    }
}
