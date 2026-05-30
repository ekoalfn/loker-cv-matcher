<?php

namespace Tests\Feature;

use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_curated_landing_page_is_indexable_and_preserves_job_filter_results(): void
    {
        $job = $this->createJob([
            'title' => 'Officer Bank Mandiri',
            'company' => 'Bank Mandiri',
            'source_url' => 'https://example.com/mandiri',
        ]);

        $response = $this->get('/lowongan/bank-mandiri');

        $response->assertOk();
        $response->assertSee('Lowongan Bank Mandiri');
        $response->assertSee($job->title);
        $response->assertSee('content="index, follow"', false);
        $response->assertSee('href="https://lamaraja.web.id/lowongan/bank-mandiri"', false);
    }

    public function test_ad_hoc_filtered_jobs_page_stays_noindex(): void
    {
        $response = $this->get('/jobs?keyword=bank+mandiri');

        $response->assertOk();
        $response->assertSee('content="noindex, follow"', false);
    }

    public function test_closed_job_detail_returns_noindex_reference_page_without_apply_link(): void
    {
        $job = $this->createJob([
            'title' => 'Expired Analyst',
            'slug' => 'expired-analyst',
            'is_active' => false,
            'source_url' => 'https://example.com/expired',
        ]);

        $response = $this->get('/jobs/' . $job->slug);

        $response->assertOk();
        $response->assertSee('Lowongan ini sudah ditutup');
        $response->assertSee('content="noindex, follow"', false);
        $response->assertDontSee(route('jobs.apply', $job), false);
    }

    private function createJob(array $overrides = []): Job
    {
        return Job::query()->create(array_merge([
            'title' => 'Backend Developer',
            'slug' => 'backend-developer-' . uniqid(),
            'company' => 'Lamaraja Test',
            'location' => 'Jakarta',
            'employment_type' => 'full-time',
            'description_raw' => 'Build and maintain Laravel applications.',
            'summary_ai' => 'Laravel role for product engineering.',
            'source_url' => 'https://example.com/job-' . uniqid(),
            'is_active' => true,
            'scraped_at' => now(),
        ], $overrides));
    }
}
