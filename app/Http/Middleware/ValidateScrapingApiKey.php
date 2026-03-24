<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateScrapingApiKey
{
    /**
     * Validate the scraping API key from the Authorization Bearer header.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('scraping.api_key');

        if (empty($apiKey)) {
            return new JsonResponse([
                'message' => 'Scraping API key is not configured.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $token = $request->bearerToken();

        if (empty($token) || ! hash_equals((string) $apiKey, $token)) {
            return new JsonResponse([
                'message' => 'Invalid or missing API key.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
