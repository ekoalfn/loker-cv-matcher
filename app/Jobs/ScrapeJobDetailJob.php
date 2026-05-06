<?php

namespace App\Jobs;

use App\Models\Job;
use App\Services\WebScraperService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ScrapeJobDetailJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 300;

    public function __construct(
        public readonly Job $jobModel
    ) {}

    public function handle(WebScraperService $scraper): void
    {
        Log::info('Background Job: Memulai ekstraksi detail loker', [
            'job_id' => $this->jobModel->id, 
            'title' => $this->jobModel->title,
            'url' => $this->jobModel->source_url
        ]);

        if (empty($this->jobModel->source_url)) {
            Log::warning('Background Job: Loker tidak memiliki source_url', ['job_id' => $this->jobModel->id]);
            $this->jobModel->update(['detail_fetch_error' => 'Loker tidak memiliki source_url']);
            return;
        }

        $result = $scraper->scrapeDetailUrl($this->jobModel->source_url);

        if (!$result['success']) {
            Log::error('Background Job: Gagal mengekstrak detail', [
                'job_id' => $this->jobModel->id,
                'error' => $result['error'] ?? 'Unknown error'
            ]);
            $this->jobModel->update([
                'detail_fetch_error' => $result['error'] ?? 'Unknown error',
                'detail_fetched_at' => now(), // marked as fetched (failed) so it doesn't stay pending forever
            ]);
            return;
        }

        $detail = $result['job'];

        $this->jobModel->update([
            'description_raw' => $detail['description_raw'] ?? $this->jobModel->description_raw,
            'title' => $detail['title'] ?? $this->jobModel->title,
            'company' => $detail['company'] ?? $this->jobModel->company,
            'location' => $detail['location'] ?? $this->jobModel->location,
            'employment_type' => $detail['employment_type'] ?? $this->jobModel->employment_type,
            'salary_min' => $detail['salary_min'] ?? $this->jobModel->salary_min,
            'salary_max' => $detail['salary_max'] ?? $this->jobModel->salary_max,
            'detail_fetched_at' => now(),
            'detail_fetch_error' => null,
        ]);

        if (isset($detail['tags']) && is_array($detail['tags'])) {
            $this->jobModel->update(['tags' => $detail['tags']]);
        }

        Log::info('Background Job: Berhasil memperbarui detail loker', [
            'job_id' => $this->jobModel->id,
            'title' => $this->jobModel->title
        ]);
    }
}
