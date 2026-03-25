<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate a dynamic XML sitemap with all indexable pages.
     *
     * Includes:
     *  - Homepage (priority 1.0, daily)
     *  - Jobs listing page (priority 0.8, daily)
     *  - All active individual job pages (priority 0.6, weekly)
     */
    public function index(): Response
    {
        $jobs = Job::query()
            ->active()
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $xml .= '<url>';
        $xml .= '<loc>' . url('/') . '</loc>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';

        // Jobs listing page
        $xml .= '<url>';
        $xml .= '<loc>' . route('jobs.index') . '</loc>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';

        // Individual job detail pages
        foreach ($jobs as $job) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('jobs.show', $job->slug) . '</loc>';
            $xml .= '<lastmod>' . $job->updated_at->toW3cString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
