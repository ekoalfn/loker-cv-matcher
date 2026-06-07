<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    private const MAX_URLS_PER_SITEMAP = 45000;

    /**
     * Generate a dynamic XML sitemap with all indexable pages.
     */
    public function index(): Response
    {
        $activeJobCount = Job::query()->active()->count();

        if ($activeJobCount > self::MAX_URLS_PER_SITEMAP) {
            return $this->sitemapIndex($activeJobCount);
        }

        return $this->urlSet(array_merge($this->staticUrls(), $this->jobUrls()));
    }

    public function pages(): Response
    {
        return $this->urlSet($this->staticUrls());
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
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            });

        return $this->urlSet($urls);
    }

    /**
     * @return array<int, array{loc: string, lastmod: string, changefreq: string, priority: string}>
     */
    private function staticUrls(): array
    {
        $latestJobDate = Job::query()->active()->max('updated_at');
        $latestJobDate = $latestJobDate ? \Illuminate\Support\Carbon::parse($latestJobDate)->toW3cString() : now()->toW3cString();

        $urls = [
            [
                'loc' => url('/'),
                'lastmod' => $latestJobDate,
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => route('jobs.index'),
                'lastmod' => $latestJobDate,
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => route('cv-matcher.index'),
                'lastmod' => $this->viewLastModified('pages/cv-matcher.blade.php', $latestJobDate),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('mock-interview.landing'),
                'lastmod' => $this->viewLastModified('pages/mock-interview-landing.blade.php', $latestJobDate),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('ai-tools.index'),
                'lastmod' => $this->viewLastModified('pages/ai-tools/index.blade.php', $latestJobDate),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('ai-tools.cover-letter'),
                'lastmod' => $this->viewLastModified('pages/ai-tools/cover-letter.blade.php', $latestJobDate),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('ai-tools.cv-rewrite'),
                'lastmod' => $this->viewLastModified('pages/ai-tools/cv-rewrite.blade.php', $latestJobDate),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('ai-tools.skill-gap'),
                'lastmod' => $this->viewLastModified('pages/ai-tools/skill-gap.blade.php', $latestJobDate),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('ai-tools.career-path'),
                'lastmod' => $this->viewLastModified('pages/ai-tools/career-path.blade.php', $latestJobDate),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('ai-tools.interview-practice'),
                'lastmod' => $this->viewLastModified('pages/ai-tools/interview-practice.blade.php', $latestJobDate),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('about'),
                'lastmod' => $this->viewLastModified('pages/about.blade.php', $latestJobDate),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ],
            [
                'loc' => route('legal.privacy'),
                'lastmod' => $this->viewLastModified('pages/legal/privacy.blade.php', $latestJobDate),
                'changefreq' => 'monthly',
                'priority' => '0.3',
            ],
            [
                'loc' => route('legal.terms'),
                'lastmod' => $this->viewLastModified('pages/legal/terms.blade.php', $latestJobDate),
                'changefreq' => 'monthly',
                'priority' => '0.3',
            ],
            [
                'loc' => route('legal.cookies'),
                'lastmod' => $this->viewLastModified('pages/legal/cookies.blade.php', $latestJobDate),
                'changefreq' => 'monthly',
                'priority' => '0.3',
            ],
        ];

        foreach (config('seo.job_landing_pages', []) as $slug => $landing) {
            $urls[] = [
                'loc' => route('jobs.landing', $slug),
                'lastmod' => $latestJobDate,
                'changefreq' => 'daily',
                'priority' => (string) ($landing['priority'] ?? '0.7'),
            ];
        }

        return $urls;
    }

    /**
     * @return array<int, array{loc: string, lastmod: string, changefreq: string, priority: string}>
     */
    private function jobUrls(): array
    {
        $urls = [];
        Job::query()
            ->active()
            ->select(['id', 'slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->chunkById(1000, function ($jobs) use (&$urls): void {
                foreach ($jobs as $job) {
                    $urls[] = [
                        'loc' => route('jobs.show', $job->slug),
                        'lastmod' => $job->updated_at->toW3cString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                    ];
                }
            });

        return $urls;
    }

    /**
     * @param  array<int, array{loc: string, lastmod: string, changefreq: string, priority: string}>  $urls
     */
    private function urlSet(array $urls): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . e($url['loc']) . '</loc>';
            $xml .= '<lastmod>' . e($url['lastmod']) . '</lastmod>';
            $xml .= '<changefreq>' . e($url['changefreq']) . '</changefreq>';
            $xml .= '<priority>' . e($url['priority']) . '</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return $this->xmlResponse($xml);
    }

    private function sitemapIndex(int $activeJobCount): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $xml .= $this->sitemapEntry(route('sitemap.pages'));

        $pages = (int) ceil($activeJobCount / self::MAX_URLS_PER_SITEMAP);
        for ($page = 1; $page <= $pages; $page++) {
            $xml .= $this->sitemapEntry(route('sitemap.jobs', $page));
        }

        $xml .= '</sitemapindex>';

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
