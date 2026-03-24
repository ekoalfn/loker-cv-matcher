<?php

namespace App\Http\Controllers;

use App\Contracts\JobRepositoryInterface;
use App\DTOs\JobFilterDTO;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('pages.home', compact('recentJobs', 'totalJobs'));
    }

    /**
     * GET /jobs -- listing dengan search & filter.
     */
    public function list(Request $request): View
    {
        $filters = JobFilterDTO::fromRequest($request->all());
        $jobs = $this->jobRepository->search($filters);

        $locations = Job::query()
            ->active()
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->sort()
            ->values();

        return view('pages.jobs.index', compact('jobs', 'filters', 'locations'));
    }

    /**
     * GET /jobs/{slug} -- detail lowongan.
     */
    public function show(string $slug): View
    {
        $job = $this->jobRepository->findBySlug($slug);

        if (! $job) {
            abort(404);
        }

        return view('pages.jobs.show', compact('job'));
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
}
