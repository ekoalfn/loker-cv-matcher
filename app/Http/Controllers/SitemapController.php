<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    private const MAX_URLS_PER_SITEMAP = 45000;
    private const MIN_LANDING_JOBS = 1;

    /**
     * Sitemap index only. Keep URL groups separated for crawl clarity.
     */
    public function index(): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $xml .= $this->sitemapEntry(route('sitemap.pages'));
        $xml .= $this->sitemapEntry(route('sitemap.locations'));
        $xml .= $this->sitemapEntry(route('sitemap.categories'));

        $pages = max(1, (int) ceil(Job::query()->active()->count() / self::MAX_URLS_PER_SITEMAP));
        for ($page = 1; $page <= $pages; $page++) {
            $xml .= $this->sitemapEntry(route('sitemap.jobs', $page));
        }

        $xml .= '</sitemapindex>';

        return $this->xmlResponse($xml);
    }

    public function pages(): Response
    {
        return $this->urlSet($this->staticUrls());
    }

    public function locations(): Response
    {
        return $this->urlSet($this->landingUrls(true));
    }

    public function categories(): Response
    {
        return $this->urlSet($this->landingUrls(false));
    }

    public function jobs(int $page = 1): Response
    {
        $offset = max(0, $page - 1) * self::MAX_URLS_PER_SITEMAP;

        $urls = [];
        Job::query()
            ->active()
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->offset($offset)
            ->limit(self::MAX_URLS_PER_SITEMAP)
            ->get()
            ->each(function (Job $job) use (&$urls): void {
                $urls[] = [
                    'loc' => route('jobs.show', $job->slug),
                    'lastmod' => $job->updated_at->toW3cString(),
                ];
            });

        return $this->urlSet($urls);
    }

    private function staticUrls(): array
    {
        $latestJobDate = $this->latestActiveJobDate();

        return [
            ['loc' => url('/'), 'lastmod' => $latestJobDate],
            ['loc' => route('jobs.index'), 'lastmod' => $latestJobDate],
            ['loc' => route('cv-matcher.index'), 'lastmod' => $this->viewLastModified('pages/cv-matcher.blade.php', $latestJobDate)],
            ['loc' => route('mock-interview.landing'), 'lastmod' => $this->viewLastModified('pages/mock-interview-landing.blade.php', $latestJobDate)],
            ['loc' => route('ai-tools.index'), 'lastmod' => $this->viewLastModified('pages/ai-tools/index.blade.php', $latestJobDate)],
            ['loc' => route('ai-tools.cover-letter'), 'lastmod' => $this->viewLastModified('pages/ai-tools/cover-letter.blade.php', $latestJobDate)],
            ['loc' => route('ai-tools.cv-rewrite'), 'lastmod' => $this->viewLastModified('pages/ai-tools/cv-rewrite.blade.php', $latestJobDate)],
            ['loc' => route('ai-tools.skill-gap'), 'lastmod' => $this->viewLastModified('pages/ai-tools/skill-gap.blade.php', $latestJobDate)],
            ['loc' => route('ai-tools.career-path'), 'lastmod' => $this->viewLastModified('pages/ai-tools/career-path.blade.php', $latestJobDate)],
            ['loc' => route('ai-tools.interview-practice'), 'lastmod' => $this->viewLastModified('pages/ai-tools/interview-practice.blade.php', $latestJobDate)],
            ['loc' => route('about'), 'lastmod' => $this->viewLastModified('pages/about.blade.php', $latestJobDate)],
            ['loc' => route('legal.privacy'), 'lastmod' => $this->viewLastModified('pages/legal/privacy.blade.php', $latestJobDate)],
            ['loc' => route('legal.terms'), 'lastmod' => $this->viewLastModified('pages/legal/terms.blade.php', $latestJobDate)],
            ['loc' => route('legal.cookies'), 'lastmod' => $this->viewLastModified('pages/legal/cookies.blade.php', $latestJobDate)],
        ];
    }

    private function landingUrls(bool $locations): array
    {
        $urls = [];

        foreach (config('seo.job_landing_pages', []) as $slug => $landing) {
            $isLocation = str_starts_with((string) $slug, 'loker-') || (isset($landing['location']) && ! isset($landing['keyword']));
            if ($isLocation !== $locations) {
                continue;
            }

            $lastmod = $this->landingLastModified($landing);
            if (! $lastmod) {
                continue;
            }

            $urls[] = [
                'loc' => route('jobs.landing', $slug),
                'lastmod' => $lastmod,
            ];
        }

        return $urls;
    }

    private function landingLastModified(array $landing): ?string
    {
        $query = Job::query()->active();

        if (! empty($landing['keyword'])) {
            $keyword = '%' . str_replace(' ', '%', (string) $landing['keyword']) . '%';
            $query->where(function ($q) use ($keyword): void {
                $q->where('title', 'ilike', $keyword)
                    ->orWhere('company', 'ilike', $keyword)
                    ->orWhere('summary_ai', 'ilike', $keyword)
                    ->orWhere('description_raw', 'ilike', $keyword)
                    ->orWhereRaw('tags::text ilike ?', [$keyword]);
            });
        }

        if (! empty($landing['location'])) {
            $query->where('location', 'ilike', '%' . (string) $landing['location'] . '%');
        }

        if (! empty($landing['employment_type'])) {
            $types = (array) $landing['employment_type'];
            $query->whereIn('employment_type', $types);
        }

        $count = (clone $query)->count();
        if ($count < (int) ($landing['min_jobs'] ?? self::MIN_LANDING_JOBS)) {
            return null;
        }

        $latest = (clone $query)->max('updated_at');

        return $latest ? Carbon::parse($latest)->toW3cString() : null;
    }

    private function latestActiveJobDate(): string
    {
        $latest = Job::query()->active()->max('updated_at');

        return $latest ? Carbon::parse($latest)->toW3cString() : now()->toW3cString();
    }

    private function urlSet(array $urls): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . e($url['loc']) . '</loc>';
            $xml .= '<lastmod>' . e($url['lastmod']) . '</lastmod>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return $this->xmlResponse($xml);
    }

    private function sitemapEntry(string $loc): string
    {
        return '<sitemap><loc>' . e($loc) . '</loc><lastmod>' . now()->toW3cString() . '</lastmod></sitemap>';
    }

    private function viewLastModified(string $viewPath, string $fallback): string
    {
        $path = resource_path('views/' . $viewPath);

        return file_exists($path) ? date(DATE_W3C, filemtime($path)) : $fallback;
    }

    private function xmlResponse(string $xml): Response
    {
        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600, s-maxage=3600',
        ]);
    }
}
