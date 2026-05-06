<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobSource;
use App\Services\WebScraperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ScraperController extends Controller
{
    public function __construct(
        private readonly WebScraperService $scraper,
    ) {}

    /**
     * Show the password gate for the scraper admin page.
     */
    public function login(): View
    {
        if (session('scraper_authenticated')) {
            return $this->dashboard();
        }

        return view('pages.scraper.login');
    }

    /**
     * Authenticate with the scraper admin password.
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $correctPassword = config('services.scraper.admin_password');

        if ($request->password !== $correctPassword) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        session(['scraper_authenticated' => true]);

        return redirect()->route('scraper.dashboard');
    }

    /**
     * Show the scraper dashboard.
     */
    public function dashboard(): View
    {
        if (! session('scraper_authenticated')) {
            return view('pages.scraper.login');
        }

        // Stats
        $totalJobs = Job::count();
        $activeJobs = Job::active()->count();
        $inactiveJobs = Job::where('is_active', false)->count();
        $deletedJobs = Job::onlyTrashed()->count();
        $jobsToday = Job::whereDate('created_at', today())->count();
        $jobsThisWeek = Job::where('created_at', '>=', now()->startOfWeek())->count();

        // Sources
        $sources = JobSource::withCount(['jobs', 'jobs as active_jobs_count' => function ($q) {
            $q->where('is_active', true);
        }])
            ->orderByDesc('last_scraped_at')
            ->get();

        // Recent jobs (latest 25)
        $recentJobs = Job::with('source')
            ->orderByDesc('created_at')
            ->limit(25)
            ->get();

        // Jobs per day (last 14 days)
        $jobsPerDay = Job::select(
            DB::raw("DATE(created_at) as date"),
            DB::raw("COUNT(*) as count")
        )
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy('date')
            ->get();

        // Top locations
        $topLocations = Job::active()
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->select('location', DB::raw('COUNT(*) as count'))
            ->groupBy('location')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Employment type distribution
        $employmentTypes = Job::active()
            ->whereNotNull('employment_type')
            ->select('employment_type', DB::raw('COUNT(*) as count'))
            ->groupBy('employment_type')
            ->orderByDesc('count')
            ->get();

        return view('pages.scraper.dashboard', compact(
            'totalJobs', 'activeJobs', 'inactiveJobs', 'deletedJobs',
            'jobsToday', 'jobsThisWeek', 'sources', 'recentJobs',
            'jobsPerDay', 'topLocations', 'employmentTypes'
        ));
    }

    // -----------------------------------------------------------------
    // Scraping API endpoints (AJAX from dashboard)
    // -----------------------------------------------------------------

    /**
     * Scrape a single URL → extract jobs via AI.
     */
    public function scrapeUrl(Request $request): JsonResponse
    {
        if (! session('scraper_authenticated')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'url' => 'required|url|max:2048',
        ]);

        $result = $this->scraper->scrapeUrl($request->url);

        return response()->json($result);
    }

    /**
     * Scrape raw HTML content → extract jobs via AI.
     */
    public function scrapeHtml(Request $request): JsonResponse
    {
        if (! session('scraper_authenticated')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'html' => 'required|string',
            'source_url' => 'nullable|string|max:2048',
        ]);

        $result = $this->scraper->scrapeHtml($request->html, $request->source_url ?? 'local-file');

        return response()->json($result);
    }

    /**
     * Fetch URL content only (preview, no AI extraction).
     */
    public function fetchPreview(Request $request): JsonResponse
    {
        if (! session('scraper_authenticated')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'url' => 'required|url|max:2048',
        ]);

        $result = $this->scraper->fetchUrl($request->url);

        return response()->json($result);
    }

    /**
     * Ingest extracted jobs into the database.
     */
    public function ingestJobs(Request $request): JsonResponse
    {
        if (! session('scraper_authenticated')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'source_name' => 'required|string|max:255',
            'jobs' => 'required|array|min:1',
            'jobs.*.title' => 'required|string|max:255',
            'jobs.*.company' => 'required|string|max:255',
            'jobs.*.source_url' => 'required|url|max:2048',
        ]);

        $stats = $this->scraper->ingestJobs(
            jobs: $request->jobs,
            sourceName: $request->source_name,
        );

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }

    /**
     * Get recent jobs queue status.
     */
    public function queueStatus(Request $request): JsonResponse
    {
        if (! session('scraper_authenticated')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $jobs = Job::orderByDesc('created_at')
            ->limit(10)
            ->get(['id', 'title', 'company', 'detail_fetched_at', 'detail_fetch_error', 'created_at'])
            ->map(function ($job) {
                $status = 'pending';
                if ($job->detail_fetched_at && !$job->detail_fetch_error) {
                    $status = 'success';
                } elseif ($job->detail_fetch_error) {
                    $status = 'error';
                }
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'company' => $job->company,
                    'status' => $status,
                    'error' => $job->detail_fetch_error,
                    'time' => $job->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'jobs' => $jobs,
        ]);
    }

    /**
     * Logout from scraper admin.
     */
    public function logout()
    {
        session()->forget('scraper_authenticated');

        return redirect()->route('scraper.login');
    }
}
