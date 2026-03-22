<?php

namespace App\Services;

class AiResponseParser
{
    /**
     * Parse ringkasan lowongan dan tags dari AI response.
     *
     * Mencoba JSON decode terlebih dahulu, fallback ke regex parsing.
     *
     * @param  string  $content  Raw AI response content
     * @return array{summary: string, tags: string[]}
     */
    public function parseJobSummary(string $content): array
    {
        $result = $this->tryJsonDecode($content);

        if ($result !== null && isset($result['summary'])) {
            return [
                'summary' => $this->sanitizeString($result['summary']),
                'tags' => $this->sanitizeTags($result['tags'] ?? []),
            ];
        }

        return $this->parseJobSummaryWithRegex($content);
    }

    /**
     * Parse CV match analysis dari AI response.
     *
     * Mencoba JSON decode terlebih dahulu, fallback ke regex parsing.
     *
     * @param  string  $content  Raw AI response content
     * @return array{match_score: int, strengths: string[], weaknesses: string[], suggestions: string[]}
     */
    public function parseCvMatch(string $content): array
    {
        $result = $this->tryJsonDecode($content);

        if ($result !== null && isset($result['match_score'])) {
            return [
                'match_score' => $this->clampScore($result['match_score']),
                'strengths' => $this->sanitizeStringArray($result['strengths'] ?? []),
                'weaknesses' => $this->sanitizeStringArray($result['weaknesses'] ?? []),
                'suggestions' => $this->sanitizeStringArray($result['suggestions'] ?? []),
            ];
        }

        return $this->parseCvMatchWithRegex($content);
    }

    /**
     * Coba decode JSON dari content, termasuk dari code block.
     */
    private function tryJsonDecode(string $content): ?array
    {
        $content = trim($content);

        // Coba langsung decode
        $decoded = json_decode($content, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        // Coba extract JSON dari markdown code block ```json ... ```
        if (preg_match('/```(?:json)?\s*\n?([\s\S]*?)\n?\s*```/', $content, $matches)) {
            $decoded = json_decode(trim($matches[1]), true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        // Coba extract JSON object pertama dari content { ... }
        if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
            $decoded = json_decode($matches[0], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }

    /**
     * Fallback regex parsing untuk job summary.
     */
    private function parseJobSummaryWithRegex(string $content): array
    {
        $summary = '';
        $tags = [];

        // Extract summary: ambil teks sebelum "tags" atau semua teks sebagai summary
        if (preg_match('/(?:summary|ringkasan)[:\s]*(.+?)(?=\n\s*(?:tags|label|kata kunci)|$)/is', $content, $matches)) {
            $summary = trim($matches[1]);
        } else {
            // Ambil 2 kalimat pertama sebagai summary
            if (preg_match('/^(.+?\.)\s*(.+?\.)/s', $content, $matches)) {
                $summary = trim($matches[1] . ' ' . $matches[2]);
            } else {
                $summary = trim($content);
            }
        }

        // Extract tags: cari pola list atau comma-separated setelah keyword tags
        if (preg_match('/(?:tags|label|kata kunci)[:\s]*(.+?)$/is', $content, $matches)) {
            $tagLine = trim($matches[1]);
            $tags = $this->extractTagsFromLine($tagLine);
        }

        return [
            'summary' => $this->sanitizeString($summary),
            'tags' => $this->sanitizeTags($tags),
        ];
    }

    /**
     * Fallback regex parsing untuk CV match.
     */
    private function parseCvMatchWithRegex(string $content): array
    {
        $matchScore = 0;
        $strengths = [];
        $weaknesses = [];
        $suggestions = [];

        // Extract match score
        if (preg_match('/(?:match[_\s]?score|skor|score|kecocokan)[:\s]*(\d+)/i', $content, $matches)) {
            $matchScore = (int) $matches[1];
        }

        // Extract strengths
        $strengths = $this->extractListSection($content, 'strengths|kekuatan|kelebihan');

        // Extract weaknesses
        $weaknesses = $this->extractListSection($content, 'weaknesses|kelemahan|kekurangan');

        // Extract suggestions
        $suggestions = $this->extractListSection($content, 'suggestions|saran|rekomendasi');

        return [
            'match_score' => $this->clampScore($matchScore),
            'strengths' => $this->sanitizeStringArray($strengths),
            'weaknesses' => $this->sanitizeStringArray($weaknesses),
            'suggestions' => $this->sanitizeStringArray($suggestions),
        ];
    }

    /**
     * Extract list items dari section tertentu dalam teks.
     *
     * @return string[]
     */
    private function extractListSection(string $content, string $sectionPattern): array
    {
        $items = [];

        // Cari section header dan ambil list items setelahnya
        $pattern = '/(?:' . $sectionPattern . ')[:\s]*\n?((?:\s*[-*]\s*.+\n?)+)/i';
        if (preg_match($pattern, $content, $matches)) {
            preg_match_all('/[-*]\s*(.+)/', $matches[1], $itemMatches);
            $items = array_map('trim', $itemMatches[1] ?? []);
        }

        return $items;
    }

    /**
     * Extract tags dari satu baris teks (comma-separated atau list items).
     *
     * @return string[]
     */
    private function extractTagsFromLine(string $line): array
    {
        // Hapus bullet points dan list markers
        $line = preg_replace('/^\s*[-*]\s*/', '', $line);

        // Split by comma, newline, atau bullet points
        $parts = preg_split('/[,\n]+/', $line);

        return array_values(array_filter(array_map(function (string $part): string {
            return trim(preg_replace('/^\s*[-*]\s*/', '', $part));
        }, $parts)));
    }

    /**
     * Clamp score ke range 0-100.
     */
    private function clampScore(mixed $score): int
    {
        $score = is_numeric($score) ? (int) $score : 0;

        return max(0, min(100, $score));
    }

    /**
     * Sanitasi string output -- strip tags dan trim.
     */
    private function sanitizeString(string $value): string
    {
        return trim(strip_tags($value));
    }

    /**
     * Sanitasi array of tags -- filter empty, trim, strip tags.
     *
     * @param  mixed  $tags
     * @return string[]
     */
    private function sanitizeTags(mixed $tags): array
    {
        if (! is_array($tags)) {
            return [];
        }

        return array_values(array_filter(array_map(function (mixed $tag): string {
            return trim(strip_tags((string) $tag));
        }, $tags)));
    }

    /**
     * Sanitasi array of strings -- filter empty, trim, strip tags.
     *
     * @param  mixed  $items
     * @return string[]
     */
    private function sanitizeStringArray(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return array_values(array_filter(array_map(function (mixed $item): string {
            return trim(strip_tags((string) $item));
        }, $items)));
    }
}
