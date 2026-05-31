<?php

namespace App\Http\Controllers;

use App\Contracts\JobRepositoryInterface;
use App\DTOs\JobFilterDTO;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class JobController extends Controller
{
    public function __construct(
        private readonly JobRepositoryInterface $jobRepository,
    ) {}

    /**
     * GET / -- halaman utama dengan lowongan terbaru.
     */
    public function index(Request $request): View
    {
        $recentJobs = $this->jobRepository->getRecentJobs(12);
        $totalJobs = $this->jobRepository->getActiveCount();

        $topLocations = Job::query()
            ->active()
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->select('location', DB::raw('COUNT(*) as job_count'))
            ->groupBy('location')
            ->orderByDesc('job_count')
            ->limit(12)
            ->get();

        $employmentTypeCounts = Job::query()
            ->active()
            ->whereNotNull('employment_type')
            ->select('employment_type', DB::raw('COUNT(*) as job_count'))
            ->groupBy('employment_type')
            ->orderByDesc('job_count')
            ->get();

        $jobsAddedToday = Job::query()
            ->active()
            ->whereDate('created_at', today())
            ->count();

        return view('pages.home', compact(
            'recentJobs', 'totalJobs', 'topLocations',
            'employmentTypeCounts', 'jobsAddedToday'
        ));
    }

    /**
     * GET /jobs -- listing dengan search & filter.
     */
    public function list(Request $request): View
    {
        $filters = JobFilterDTO::fromRequest($request->all());
        $jobs = $this->jobRepository->search($filters);
        $locations = $this->activeLocations();

        return view('pages.jobs.index', compact('jobs', 'filters', 'locations'));
    }

    /**
     * GET /lowongan/{slug} -- curated SEO landing pages from GSC signals.
     */
    public function landing(string $slug): View
    {
        $landing = config("seo.job_landing_pages.$slug");

        if (! is_array($landing)) {
            abort(404);
        }

        $filters = JobFilterDTO::fromRequest([
            'keyword' => $landing['keyword'] ?? null,
            'location' => $landing['location'] ?? null,
            'employment_type' => $landing['employment_type'] ?? null,
        ]);
        $jobs = $this->jobRepository->search($filters);
        $locations = $this->activeLocations();

        return view('pages.jobs.index', compact('jobs', 'filters', 'locations', 'landing'));
    }

    /**
     * GET /jobs/{slug} -- detail lowongan.
     */
    public function show(string $slug): View
    {
        $job = $this->jobRepository->findAnyBySlug($slug);

        if (! $job) {
            abort(404);
        }

        $jobClosed = $job->trashed()
            || ! $job->is_active
            || ($job->expires_at && $job->expires_at->isPast());
        $relatedJobs = $this->jobRepository->getRelatedJobs($job, 3);

        return view('pages.jobs.show', compact('job', 'relatedJobs', 'jobClosed'));
    }

    /**
     * GET /jobs/{slug}/apply -- redirect ke source_url (tracked).
     */
    public function apply(string $slug): RedirectResponse
    {
        $job = $this->jobRepository->findBySlug($slug);

        if (! $job) {
            abort(404);
        }

        // TODO: track click event

        return redirect()->away($job->source_url);
    }

    private function activeLocations(): \Illuminate\Support\Collection
    {
        return Job::query()
            ->active()
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->sort()
            ->values();
    }
}
