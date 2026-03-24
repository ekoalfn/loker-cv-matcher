<?php

namespace App\Http\Controllers;

use App\Enums\CvScanStatus;
use App\Http\Requests\CvScanRequest;
use App\Jobs\ProcessCvScan;
use App\Models\CvScan;
use Illuminate\Http\JsonResponse;

class CvScanController extends Controller
{
    /**
     * POST /cv-scan -- upload CV untuk matching.
     */
    public function store(CvScanRequest $request): JsonResponse
    {
        // Rate limit check (1 scan/day for guests)
        $ip = $request->ip();
        $todayScans = CvScan::forIpToday($ip)->count();

        if ($todayScans >= 1 && ! auth()->check()) {
            return response()->json([
                'message' => 'Kamu sudah menggunakan scan gratis hari ini. Daftar untuk scan lebih banyak.',
            ], 429);
        }

        // Store temp file
        $path = $request->file('pdf_file')->store('temp-cv');

        // Create scan record
        $scan = CvScan::create([
            'user_id' => auth()->id(),
            'job_id' => $request->job_id,
            'status' => CvScanStatus::Pending,
            'ip_address' => $ip,
        ]);

        // Dispatch queue job
        ProcessCvScan::dispatch($scan, $path);

        return response()->json([
            'scan_id' => $scan->id,
            'status' => 'processing',
        ]);
    }

    /**
     * GET /cv-scan/{id}/status -- polling status.
     */
    public function status(int $id): JsonResponse
    {
        $scan = CvScan::findOrFail($id);

        $response = [
            'status' => $scan->status->value,
        ];

        if ($scan->status === CvScanStatus::Completed) {
            $response['result'] = [
                'match_score' => $scan->match_score,
                'strengths' => $scan->strengths,
                'weaknesses' => $scan->weaknesses,
                'suggestions' => $scan->suggestions,
            ];
        }

        return response()->json($response);
    }
}
