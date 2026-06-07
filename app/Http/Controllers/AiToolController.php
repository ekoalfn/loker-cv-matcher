<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\CareerToolsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class AiToolController extends Controller
{
    public function __construct(
        private readonly CareerToolsService $tools,
    ) {}

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    public function hub(): View
    {
        return view('pages.ai-tools.index');
    }

    public function coverLetterPage(Request $request): View
    {
        return view('pages.ai-tools.cover-letter', [
            'jobs' => $this->recentJobs(),
            'selectedJobId' => $request->integer('job_id') ?: null,
        ]);
    }

    public function cvRewritePage(): View
    {
        return view('pages.ai-tools.cv-rewrite');
    }

    public function skillGapPage(): View
    {
        return view('pages.ai-tools.skill-gap');
    }

    public function careerPathPage(): View
    {
        return view('pages.ai-tools.career-path');
    }

    public function interviewPracticePage(Request $request): View
    {
        $job = $request->integer('job_id')
            ? Job::query()->active()->find($request->integer('job_id'))
            : null;

        return view('pages.ai-tools.interview-practice', [
            'targetRole' => $job?->title ?? '',
            'job' => $job,
        ]);
    }

    // -------------------------------------------------------------------------
    // AJAX endpoints (CV upload based)
    // -------------------------------------------------------------------------

    public function coverLetter(Request $request): JsonResponse
    {
        return $this->handleCvTool($request, function (string $cvText) use ($request): array {
            $job = $this->resolveJob($request);
            if (! $job) {
                throw new \RuntimeException('Pilih lowongan yang valid terlebih dahulu.');
            }
            $tone = (string) $request->input('tone', 'profesional');

            return $this->tools->coverLetter($cvText, $job, $tone);
        });
    }

    public function cvRewrite(Request $request): JsonResponse
    {
        return $this->handleCvTool($request, function (string $cvText) use ($request): array {
            return $this->tools->cvRewrite($cvText, (string) $request->input('target_role', ''));
        });
    }

    public function skillGap(Request $request): JsonResponse
    {
        return $this->handleCvTool($request, function (string $cvText) use ($request): array {
            return $this->tools->skillGap($cvText, (string) $request->input('target_role', ''), $this->resolveJob($request));
        });
    }

    public function careerPath(Request $request): JsonResponse
    {
        return $this->handleCvTool($request, function (string $cvText): array {
            return $this->tools->careerPath($cvText);
        });
    }

    public function jobFit(Request $request): JsonResponse
    {
        return $this->handleCvTool($request, function (string $cvText) use ($request): array {
            $job = $this->resolveJob($request);
            if (! $job) {
                throw new \RuntimeException('Lowongan tidak ditemukan atau sudah tidak aktif.');
            }

            return $this->tools->jobFit($cvText, $job);
        });
    }

    // -------------------------------------------------------------------------
    // Interview question generator (no CV needed, job description based)
    // -------------------------------------------------------------------------

    public function interviewQuestions(Request $request): JsonResponse
    {
        $job = $this->resolveJob($request);
        if (! $job) {
            return response()->json(['message' => 'Lowongan tidak ditemukan.'], 404);
        }

        if (! $this->allow($request, 'iq')) {
            return response()->json(['message' => 'Kamu sudah mencapai batas percobaan gratis hari ini. Coba lagi besok.'], 429);
        }

        try {
            return response()->json(['result' => $this->tools->interviewQuestions($job)]);
        } catch (\Throwable $e) {
            Log::error('Interview question generation failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Gagal membuat pertanyaan. Coba lagi sebentar.'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // Public mock interview demo (1 free question)
    // -------------------------------------------------------------------------

    public function interviewDemoQuestion(Request $request): JsonResponse
    {
        $request->validate(['target_role' => ['nullable', 'string', 'max:120']]);

        if (! $this->allow($request, 'demo-q')) {
            return response()->json(['message' => 'Batas demo gratis tercapai. Coba lagi besok atau buka mode latihan lengkap.'], 429);
        }

        $role = trim((string) $request->input('target_role')) ?: 'posisi yang kamu tuju';

        try {
            return response()->json(['question' => $this->tools->demoQuestion($role)]);
        } catch (\Throwable $e) {
            Log::error('Interview demo question failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Gagal memuat pertanyaan. Coba lagi.'], 500);
        }
    }

    public function interviewDemoFeedback(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'target_role' => ['nullable', 'string', 'max:120'],
            'question' => ['required', 'string', 'max:600'],
            'answer' => ['required', 'string', 'max:4000'],
        ]);

        try {
            $feedback = $this->tools->demoFeedback(
                trim((string) ($validated['target_role'] ?? '')) ?: 'posisi yang kamu tuju',
                $validated['question'],
                $validated['answer'],
            );

            return response()->json(['result' => $feedback]);
        } catch (\Throwable $e) {
            Log::error('Interview demo feedback failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Gagal menilai jawaban. Coba lagi.'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // Shared helpers
    // -------------------------------------------------------------------------

    /**
     * Validate a CV upload, extract text, run the callback and clean up.
     */
    private function handleCvTool(Request $request, \Closure $callback): JsonResponse
    {
        $request->validate([
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'job_id' => ['nullable', 'integer', 'exists:jobs,id'],
            'target_role' => ['nullable', 'string', 'max:120'],
            'tone' => ['nullable', 'string', 'max:40'],
        ]);

        if (! $this->allow($request, 'cv-tool')) {
            return response()->json([
                'message' => 'Kamu sudah memakai jatah gratis hari ini. Coba lagi besok.',
            ], 429);
        }

        $path = $request->file('pdf_file')->store('temp-cv');

        try {
            $cvText = $this->extractPdfText(Storage::path($path));

            return response()->json(['result' => $callback($cvText)]);
        } catch (\Throwable $e) {
            Log::error('AI tool failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Pemrosesan gagal. ' . $e->getMessage(),
            ], $e instanceof \RuntimeException ? 422 : 500);
        } finally {
            Storage::delete($path);
        }
    }

    private function extractPdfText(string $fullPath): string
    {
        $parser = new PdfParser();
        $cvText = $parser->parseFile($fullPath)->getText();

        if (trim($cvText) === '') {
            throw new \RuntimeException('Tidak bisa membaca teks dari PDF. File mungkin berupa gambar.');
        }

        return $cvText;
    }

    private function resolveJob(Request $request): ?Job
    {
        $id = $request->integer('job_id');

        return $id ? Job::query()->active()->find($id) : null;
    }

    /**
     * @return \Illuminate\Support\Collection<int, Job>
     */
    private function recentJobs(): \Illuminate\Support\Collection
    {
        return Job::query()
            ->active()
            ->latest()
            ->limit(50)
            ->get(['id', 'title', 'company', 'location']);
    }

    /**
     * Simple per-IP daily rate limit shared across the free AI tools.
     */
    private function allow(Request $request, string $bucket): bool
    {
        if (auth()->check()) {
            return true;
        }

        $key = "ai-tool:{$bucket}:" . $request->ip();
        $limit = (int) config('ai.limits.guest_daily_tool_limit', 5);

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return false;
        }

        RateLimiter::hit($key, 86400);

        return true;
    }
}
