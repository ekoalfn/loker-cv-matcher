<?php

namespace App\Http\Controllers;

use App\Contracts\AiServiceInterface;
use App\Enums\CvScanStatus;
use App\Http\Requests\CvScanRequest;
use App\Models\CvScan;
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class CvScanController extends Controller
{
    public function __construct(
        private readonly AiServiceInterface $ai,
        private readonly \App\Services\CareerToolsService $tools,
    ) {}

    /**
     * Show the CV Matcher page.
     */
    public function index(): View
    {
        return view('pages.cv-matcher');
    }

    /**
     * POST /cv-scan — upload CV, proses langsung (synchronous).
     */
    public function store(CvScanRequest $request): JsonResponse
    {
        $ip = $request->ip();
        $todayScans = CvScan::forIpToday($ip)->count();

        if ($todayScans >= 3 && ! auth()->check()) {
            return response()->json([
                'message' => 'Kamu sudah menggunakan 3 scan gratis hari ini.',
            ], 429);
        }

        $path = $request->file('pdf_file')->store('temp-cv');
        $startTime = microtime(true);

        $scans = collect();

        try {
            // 1. Extract text from PDF (pure PHP, no external binary)
            $fullPath = Storage::path($path);
            $parser = new PdfParser();
            $pdf = $parser->parseFile($fullPath);
            $cvText = $pdf->getText();

            if (empty(trim($cvText))) {
                throw new \RuntimeException('Tidak bisa membaca teks dari PDF. File mungkin berupa gambar.');
            }

            $targetJob = $request->integer('job_id')
                ? Job::query()->active()->find($request->integer('job_id'))
                : null;
            $jobs = $targetJob ? collect([$targetJob]) : $this->candidateJobs($cvText);

            if ($jobs->isEmpty()) {
                throw new \RuntimeException($request->integer('job_id')
                    ? 'Lowongan ini sudah tidak aktif atau tidak bisa dicocokkan.'
                    : 'Belum ada lowongan aktif yang bisa dicocokkan.');
            }

            $matches = [];
            $totalTokens = 0;

            foreach ($jobs as $job) {
                $scan = CvScan::create([
                    'user_id' => auth()->id(),
                    'job_id' => $job->id,
                    'status' => CvScanStatus::Processing,
                    'ip_address' => $ip,
                ]);
                $scans->push($scan);

                $jobDescription = $this->jobPromptText($job);

                Log::info('CV Matcher input', [
                    'scan_id' => $scan->id,
                    'job_id' => $job->id,
                    'cv_length' => strlen($cvText),
                    'job_desc_length' => strlen($jobDescription),
                    'cv_preview' => mb_substr($cvText, 0, 200),
                    'job_preview' => mb_substr($jobDescription, 0, 200),
                ]);

                $result = $this->ai->matchCv($cvText, $jobDescription);
                $totalTokens += $result['tokens_used'];

                $scan->update([
                    'status' => CvScanStatus::Completed,
                    'match_score' => $result['match_score'],
                    'strengths' => $result['strengths'],
                    'weaknesses' => $result['weaknesses'],
                    'suggestions' => $result['suggestions'],
                    'ai_model_used' => $result['model'],
                    'tokens_used' => $result['tokens_used'],
                    'processing_time_ms' => (int) ((microtime(true) - $startTime) * 1000),
                ]);

                $matches[] = [
                    'scan_id' => $scan->id,
                    'job' => [
                        'id' => $job->id,
                        'title' => $job->title,
                        'company' => $job->company,
                        'location' => $job->location,
                        'employment_type' => $job->employment_type?->value ?? (string) $job->employment_type,
                        'company_logo' => $job->company_logo,
                        'url' => route('jobs.show', $job),
                    ],
                    'match_score' => $result['match_score'],
                    'strengths' => $result['strengths'],
                    'weaknesses' => $result['weaknesses'],
                    'suggestions' => $result['suggestions'],
                ];
            }

            usort($matches, fn (array $a, array $b): int => $b['match_score'] <=> $a['match_score']);

            Log::info('CV Matcher completed', [
                'matches_count' => count($matches),
                'top_score' => $matches[0]['match_score'] ?? null,
                'tokens_used' => $totalTokens,
            ]);

            // For a targeted job scan, also draft an ATS-friendly CV summary
            // so users get an immediate, actionable improvement they can copy.
            $atsSummary = null;
            if ($targetJob && ! empty($matches)) {
                try {
                    $atsSummary = $this->tools->atsSummary($cvText, $targetJob);
                } catch (\Throwable $e) {
                    Log::warning('ATS summary generation failed', ['error' => $e->getMessage()]);
                }
            }

            $topMatch = $matches[0] ?? [];
            if ($atsSummary) {
                $topMatch['ats_summary'] = [
                    'summary' => $atsSummary['summary'],
                    'keywords' => $atsSummary['keywords'],
                ];
            }

            return response()->json([
                'status' => 'completed',
                'result' => $targetJob ? $topMatch : [
                    'matches' => $matches,
                ],
            ]);

        } catch (\Throwable $e) {
            $scans->each(function (CvScan $scan) use ($startTime): void {
                $scan->update([
                    'status' => CvScanStatus::Failed,
                    'processing_time_ms' => (int) ((microtime(true) - $startTime) * 1000),
                ]);
            });

            Log::error('CV scan failed', [
                'scan_ids' => $scans->pluck('id')->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'failed',
                'message' => 'Analisis gagal. ' . $e->getMessage(),
            ], 500);

        } finally {
            // Zero-retention: always delete temp PDF
            Storage::delete($path);
        }
    }

    /**
     * Pick a small, relevant candidate set before using AI so users do not need to select a job manually.
     */
    private function candidateJobs(string $cvText)
    {
        $keywords = collect(preg_split('/[^a-z0-9+#.]+/i', strtolower($cvText)))
            ->filter(fn (?string $word): bool => is_string($word) && strlen($word) >= 3)
            ->reject(fn (string $word): bool => in_array($word, [
                'and', 'the', 'for', 'with', 'from', 'this', 'that', 'you', 'your', 'ini', 'dan', 'yang', 'untuk', 'dengan', 'dari', 'atau',
                'curriculum', 'vitae', 'resume', 'email', 'phone', 'address', 'linkedin',
            ], true))
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(20)
            ->values();

        $jobs = Job::query()
            ->active()
            ->latest()
            ->limit(80)
            ->get();

        return $jobs
            ->map(function (Job $job) use ($keywords) {
                $haystack = strtolower(implode(' ', array_filter([
                    $job->title,
                    $job->company,
                    $job->location,
                    $job->summary_ai,
                    is_array($job->tags) ? implode(' ', $job->tags) : null,
                    mb_substr((string) $job->description_raw, 0, 1200),
                ])));

                $score = $keywords->reduce(
                    fn (int $carry, string $keyword): int => $carry + (str_contains($haystack, $keyword) ? 1 : 0),
                    0
                );

                $job->setAttribute('candidate_score', $score);

                return $job;
            })
            ->sortByDesc('candidate_score')
            ->take(5)
            ->values();
    }

    private function jobPromptText(Job $job): string
    {
        return trim(sprintf(
            "Posisi: %s\nPerusahaan: %s\nLokasi: %s\nTipe: %s\nTags: %s\nRingkasan: %s\nDeskripsi: %s",
            $job->title,
            $job->company,
            $job->location ?: '-',
            $job->employment_type?->value ?? (string) $job->employment_type,
            is_array($job->tags) ? implode(', ', $job->tags) : '-',
            $job->summary_ai ?: '-',
            mb_substr((string) ($job->description_raw ?: $job->title), 0, 2500)
        ));
    }

    /**
     * GET /cv-scan/{id}/status — polling status (backward compatible).
     */
    public function status(int $id, Request $request): JsonResponse
    {
        $scan = CvScan::findOrFail($id);

        $isOwner = ($scan->ip_address === $request->ip())
            || (auth()->check() && $scan->user_id === auth()->id());

        if (! $isOwner) {
            abort(403);
        }

        $response = ['status' => $scan->status->value];

        if ($scan->status === CvScanStatus::Completed) {
            $response['result'] = [
                'match_score' => $scan->match_score,
                'strengths' => $scan->strengths,
                'weaknesses' => $scan->weaknesses,
                'suggestions' => $scan->suggestions,
            ];
        }

        return response()->json($response);
    }
}
