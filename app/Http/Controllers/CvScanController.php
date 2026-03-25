<?php

namespace App\Http\Controllers;

use App\Contracts\AiServiceInterface;
use App\Enums\CvScanStatus;
use App\Http\Requests\CvScanRequest;
use App\Models\CvScan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class CvScanController extends Controller
{
    public function __construct(
        private readonly AiServiceInterface $ai,
    ) {}

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

        $scan = CvScan::create([
            'user_id' => auth()->id(),
            'job_id' => $request->job_id,
            'status' => CvScanStatus::Processing,
            'ip_address' => $ip,
        ]);

        try {
            // 1. Extract text from PDF (pure PHP, no external binary)
            $fullPath = Storage::path($path);
            $parser = new PdfParser();
            $pdf = $parser->parseFile($fullPath);
            $cvText = $pdf->getText();

            if (empty(trim($cvText))) {
                throw new \RuntimeException('Tidak bisa membaca teks dari PDF. File mungkin berupa gambar.');
            }

            // 2. Get job description
            $job = $scan->job;
            if (! $job) {
                throw new \RuntimeException('Lowongan tidak ditemukan.');
            }
            $jobDescription = $job->description_raw ?: $job->summary_ai ?: $job->title;

            // 3. Call AI (synchronous)
            Log::info('CV Matcher input', [
                'scan_id' => $scan->id,
                'cv_length' => strlen($cvText),
                'job_desc_length' => strlen($jobDescription),
                'cv_preview' => mb_substr($cvText, 0, 200),
                'job_preview' => mb_substr($jobDescription, 0, 200),
            ]);

            $result = $this->ai->matchCv($cvText, $jobDescription);

            Log::info('CV Matcher AI response', [
                'scan_id' => $scan->id,
                'model' => $result['model'],
                'tokens_used' => $result['tokens_used'],
                'match_score' => $result['match_score'],
                'strengths_count' => count($result['strengths']),
                'weaknesses_count' => count($result['weaknesses']),
            ]);

            // 4. Save results
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

            return response()->json([
                'scan_id' => $scan->id,
                'status' => 'completed',
                'result' => [
                    'match_score' => $result['match_score'],
                    'strengths' => $result['strengths'],
                    'weaknesses' => $result['weaknesses'],
                    'suggestions' => $result['suggestions'],
                ],
            ]);

        } catch (\Throwable $e) {
            $scan->update([
                'status' => CvScanStatus::Failed,
                'processing_time_ms' => (int) ((microtime(true) - $startTime) * 1000),
            ]);

            Log::error('CV scan failed', [
                'scan_id' => $scan->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'scan_id' => $scan->id,
                'status' => 'failed',
                'message' => 'Analisis gagal. ' . $e->getMessage(),
            ], 500);

        } finally {
            // Zero-retention: always delete temp PDF
            Storage::delete($path);
        }
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
