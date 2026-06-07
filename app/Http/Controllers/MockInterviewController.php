<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\MockInterviewSession;
use App\Services\MockInterviewService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class MockInterviewController extends Controller
{
    public function __construct(
        private readonly MockInterviewService $interviews,
    ) {}

    public function login(): View
    {
        if (session('mock_interview_authenticated')) {
            return $this->index(request());
        }

        return view('pages.mock-interview-login');
    }

    /**
     * Public Mock Interview AI page: marketing/SEO content + the full
     * hands-free live call experience inline (no password required).
     */
    public function landing(Request $request): View
    {
        return view('pages.mock-interview-landing', [
            'targetRole' => (string) $request->query('role', ''),
            'jobs' => Job::query()->active()->latest()->limit(30)->get(['id', 'title', 'company', 'location']),
            'selectedJobId' => $request->integer('job_id') ?: null,
        ]);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if ($request->password !== config('mock_interview.testing_password')) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        session(['mock_interview_authenticated' => true]);

        return redirect()->route('mock-interview.index');
    }

    public function index(Request $request): View
    {
        if (! session('mock_interview_authenticated')) {
            return view('pages.mock-interview-login');
        }

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

    public function speech(Request $request)
    {
        $data = $request->validate([
            'input' => ['required', 'string', 'max:3000'],
        ]);

        $response = Http::withToken((string) config('mock_interview.voice.api_key'))
            ->timeout((int) config('mock_interview.voice.timeout', 90))
            ->post(rtrim((string) config('mock_interview.voice.base_url'), '/').'/speech', [
                'model' => config('mock_interview.voice.tts_model'),
                'input' => $data['input'],
                'language' => config('mock_interview.voice.language', 'Indonesian'),
            ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Gagal membuat suara interviewer.',
                'detail' => mb_substr($response->body(), 0, 300),
            ], 502);
        }

        return response($response->body(), 200, [
            'Content-Type' => $response->header('Content-Type', 'audio/mpeg'),
            'Cache-Control' => 'no-store',
        ]);
    }

    public function transcribe(Request $request): JsonResponse
    {
        $data = $request->validate([
            'audio' => ['required', 'file', 'max:10240'],
        ]);

        $file = $data['audio'];
        $response = Http::withToken((string) config('mock_interview.voice.api_key'))
            ->timeout((int) config('mock_interview.voice.timeout', 90))
            ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName() ?: 'answer.webm')
            ->post(rtrim((string) config('mock_interview.voice.base_url'), '/').'/transcriptions', [
                'model' => config('mock_interview.voice.stt_model'),
                'language' => config('mock_interview.voice.stt_language', 'id'),
                'response_format' => 'json',
            ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Gagal membaca rekaman jawaban.',
                'detail' => mb_substr($response->body(), 0, 300),
            ], 502);
        }

        $payload = $response->json();

        return response()->json([
            'text' => $payload['text'] ?? $payload['transcript'] ?? '',
            'raw' => $payload,
        ]);
    }

    public function logout()
    {
        session()->forget('mock_interview_authenticated');

        return redirect()->route('mock-interview.login');
    }

    private function extractPdfText(string $path): string
    {
        $parser = new PdfParser;
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
