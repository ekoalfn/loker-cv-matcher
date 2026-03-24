<?php

namespace App\Jobs;

use App\Contracts\AiServiceInterface;
use App\Enums\CvScanStatus;
use App\Models\CvScan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;

class ProcessCvScan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The queue this job should be dispatched to.
     */
    public string $queue = 'cv-processing';

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public CvScan $scan,
        public string $filePath,
    ) {}

    /**
     * Execute the job.
     *
     * Extracts text from the uploaded PDF, sends it to the AI service
     * for CV-job matching analysis, and updates the CvScan record
     * with the results. The temporary PDF file is always deleted
     * after processing (zero-retention policy).
     */
    public function handle(AiServiceInterface $ai): void
    {
        $startTime = microtime(true);

        try {
            $this->scan->update(['status' => CvScanStatus::Processing]);

            // 1. Extract text from PDF
            $fullPath = storage_path('app/' . $this->filePath);
            $cvText = Pdf::getText($fullPath);

            if (empty(trim($cvText))) {
                throw new \RuntimeException('Could not extract text from PDF. The file may be image-based or corrupted.');
            }

            // 2. Get job description (fallback chain: description_raw -> summary_ai -> title)
            $job = $this->scan->job;

            if (! $job) {
                throw new \RuntimeException('Associated job not found (may have been deleted).');
            }

            $jobDescription = $job->description_raw ?: $job->summary_ai ?: $job->title;

            // 3. Call AI service for CV matching
            $result = $ai->matchCv($cvText, $jobDescription);

            // 4. Update scan record with results
            $this->scan->update([
                'status' => CvScanStatus::Completed,
                'match_score' => $result['match_score'],
                'strengths' => $result['strengths'],
                'weaknesses' => $result['weaknesses'],
                'suggestions' => $result['suggestions'],
                'ai_model_used' => $result['model'],
                'tokens_used' => $result['tokens_used'],
                'processing_time_ms' => (int) ((microtime(true) - $startTime) * 1000),
            ]);
        } catch (\Throwable $e) {
            $this->scan->update([
                'status' => CvScanStatus::Failed,
                'processing_time_ms' => (int) ((microtime(true) - $startTime) * 1000),
            ]);

            Log::error('CV scan processing failed', [
                'scan_id' => $this->scan->id,
                'error' => $e->getMessage(),
            ]);

            throw $e; // Re-throw for queue retry mechanism
        } finally {
            // Zero-retention: delete temp PDF on success or final attempt
            if ($this->scan->status === CvScanStatus::Completed || $this->attempts() >= $this->tries) {
                Storage::delete($this->filePath);
            }
        }
    }
}
