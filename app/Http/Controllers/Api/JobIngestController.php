<?php

namespace App\Http\Controllers\Api;

use App\Contracts\JobRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobIngestRequest;
use App\Models\JobSource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JobIngestController extends Controller
{
    public function __construct(
        private readonly JobRepositoryInterface $jobRepository,
    ) {}

    /**
     * Ingest scraped jobs from n8n.
     *
     * POST /api/v1/jobs/ingest
     */
    public function store(JobIngestRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $source = JobSource::firstOrCreate(
            ['name' => $validated['source']],
            ['is_active' => true],
        );

        // Update last_scraped_at timestamp on the source
        $source->update(['last_scraped_at' => $validated['scraped_at']]);

        $stats = $this->jobRepository->upsertFromSource(
            jobs: $validated['jobs'],
            sourceId: (string) $source->id,
            scrapedAt: $validated['scraped_at'],
        );

        return response()->json([
            'message' => 'Ingest completed.',
            'source' => $source->name,
            'stats' => $stats,
        ], Response::HTTP_OK);
    }
}
