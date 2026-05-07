<?php

namespace App\Services;

use App\Contracts\AiServiceInterface;
use App\Contracts\JobRepositoryInterface;
use App\Models\JobSource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebScraperService
{
    public function __construct(
        private readonly AiServiceInterface $ai,
        private readonly JobRepositoryInterface $jobRepository,
    ) {}

    /**
     * Fetch a URL's content as plain text (HTML stripped).
     *
     * @return array{success: bool, content?: string, error?: string}
     */
    public function fetchUrl(string $url): array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
            ])
                ->timeout(60)
                ->get($url);

            if ($response->failed()) {
                return ['success' => false, 'error' => "HTTP {$response->status()}"];
            }

            $html = $response->body();
            $text = $this->stripHtmlToText($html, $url);

            return ['success' => true, 'content' => $text];
        } catch (\Throwable $e) {
            Log::warning('WebScraper fetch error', ['url' => $url, 'error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Extract job data from raw text using AI.
     *
     * @return array{success: bool, jobs?: array, raw_response?: string, error?: string, tokens_used?: int}
     */
    public function extractJobsFromText(string $text, string $sourceUrl): array
    {
        // Extract logo URLs from text (they were appended in stripHtmlToText)
        $logoUrls = $this->extractLogosFromText($text);

        $prompt = <<<PROMPT
Kamu adalah ekstraktor data lowongan kerja. Dari teks halaman web berikut, ekstrak SEMUA lowongan kerja yang ditemukan.

Untuk setiap lowongan, ekstrak dalam format JSON array:
```json
[
  {
    "title": "Judul posisi",
    "company": "Nama perusahaan",
    "company_logo": "URL logo perusahaan (ambil URL gambar logo saja). Null jika tidak ditemukan.",
    "location": "Lokasi kerja (kota/provinsi)",
    "employment_type": "full-time|part-time|contract|internship|freelance",
    "salary_min": null,
    "salary_max": null,
    "description_raw": "Deskripsi lengkap pekerjaan (gabungkan requirement, kualifikasi, dll)",
    "source_url": "URL asli lowongan (WAJIB berupa absolute URL lengkap dengan https:// domain-nya)",
    "tags": ["tag1", "tag2"]
  }
]
```

ATURAN SANGAT PENTING (BACA DENGAN TELITI):
1. HANYA kembalikan array JSON murni. TIDAK BOLEH ada teks lain sebelum atau sesudah JSON.
2. DILARANG KERAS menggunakan tag <think> atau melakukan reasoning/penjelasan! Langsung tulis `[` untuk memulai JSON.
3. Jika hanya ada 1 lowongan di halaman, kembalikan array dengan 1 elemen.
4. salary_min dan salary_max harus angka bulat (integer) tanpa format, atau null jika tidak disebutkan.
5. employment_type harus salah satu dari: full-time, part-time, contract, internship, freelance. Jika tidak jelas, gunakan "full-time".
6. Jika source_url spesifik (link ke detail loker) ditemukan, gabungkan dengan domain utama dari `{$sourceUrl}` agar menjadi Absolute URL (contoh: https://domain.com/loker/123). Jika benar-benar tidak ada link detail, gunakan `{$sourceUrl}`.
7. tags: maksimal 5 tag relevan.
8. Jika tidak ada lowongan ditemukan, kembalikan array kosong: []
9. Untuk company_logo: WAJIB menggunakan URL dari bagian "## LOGO URLS DITEMUKAN:" di bagian akhir teks jika tersedia. Jika tidak ada di bagian tersebut, cari di teks halaman.

TEKS HALAMAN:
{$text}
PROMPT;

        try {
            Log::info('Sending prompt to AI for extraction', [
                'text_length' => strlen($text),
                'source_url' => $sourceUrl,
                'logo_urls_extracted' => $logoUrls,
            ]);

            $result = $this->ai->chat($prompt);

            if (empty($result['content'])) {
                Log::error('AI extraction failed: Empty response from AI', [
                    'source_url' => $sourceUrl,
                    'result' => $result,
                    'prompt_preview' => mb_substr($prompt, 0, 500)
                ]);
                return ['success' => false, 'error' => 'AI tidak memberikan response'];
            }

            Log::info('AI extraction successful', [
                'tokens_used' => $result['tokens_used'] ?? 0,
                'content_length' => strlen($result['content'])
            ]);

            $jobs = $this->parseJobsFromAiResponse($result['content']);

            // Post-processing: if company_logo is null, use extracted logo URLs
            if (!empty($logoUrls)) {
                foreach ($jobs as &$job) {
                    if (empty($job['company_logo'])) {
                        $job['company_logo'] = $logoUrls[0];
                        Log::info('Manual logo URL assigned', [
                            'company' => $job['company'] ?? 'unknown',
                            'logo_url' => $logoUrls[0]
                        ]);
                    }
                }
            }

            return [
                'success' => true,
                'jobs' => $jobs,
                'raw_response' => $result['content'],
                'tokens_used' => $result['tokens_used'] ?? 0,
            ];
        } catch (\Throwable $e) {
            Log::error('AI extraction error (Exception)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Parse HTML string to plain text for AI.
     */
    private function stripHtmlToText(string $html, string $baseUrl = ''): string
    {
        // Extract logo URLs before stripping tags
        $logoUrls = $this->extractLogoUrls($html, $baseUrl);

        // Remove noise
        $text = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);
        $text = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $text);
        $text = preg_replace('/<nav\b[^>]*>.*?<\/nav>/is', '', $text);
        $text = preg_replace('/<footer\b[^>]*>.*?<\/footer>/is', '', $text);
        $text = preg_replace('/<header\b[^>]*>.*?<\/header>/is', '', $text);

        // Convert block elements to newlines
        $text = preg_replace('/<(p|div|br|tr|section|article|header|footer)\b[^>]*>/i', "\n", $text);

        // Convert headings to Markdown-style markers to help AI identify sections
        $text = preg_replace('/<h1\b[^>]*>(.*?)<\/h1>/is', "\n# $1\n", $text);
        $text = preg_replace('/<h2\b[^>]*>(.*?)<\/h2>/is', "\n## $1\n", $text);
        $text = preg_replace('/<h3\b[^>]*>(.*?)<\/h3>/is', "\n### $1\n", $text);

        // Convert list items to bullet markers
        $text = preg_replace('/<li\b[^>]*>(.*?)<\/li>/is', "\n- $1", $text);

        // Convert anchor tags to inline text with URLs
        $text = preg_replace('/<a\b[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', '$2 ($1)', $text);

        // Strip remaining tags
        $text = strip_tags($text);

        // Decode entities
        $text = html_entity_decode($text);

        // Normalize whitespace but preserve newlines
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\n\s*\n+/', "\n\n", $text);
        $text = trim($text);

        // Append extracted logo URLs for AI to use
        if (!empty($logoUrls)) {
            $text .= "\n\n## LOGO URLS DITEMUKAN:\n";
            foreach ($logoUrls as $url) {
                $text .= "- $url\n";
            }
        }

        // Limit to ~10000 chars
        return mb_substr($text, 0, 10000);
    }

    /**
     * Extract logo URLs from text (from the "## LOGO URLS DITEMUKAN:" section).
     *
     * @return array<string>
     */
    private function extractLogosFromText(string $text): array
    {
        $urls = [];

        if (preg_match('/## LOGO URLS DITEMUKAN:\n(.*?)(?:\n\n|\Z)/is', $text, $match)) {
            $logoSection = $match[1];
            preg_match_all('/- (.+)/', $logoSection, $urlMatches);
            foreach ($urlMatches[1] as $url) {
                $urls[] = trim($url);
            }
        }

        return array_unique($urls);
    }

    /**
     * Extract potential company logo URLs from HTML.
     * Simple approach using regex to avoid DOMDocument issues.
     *
     * @param string $baseUrl Base URL to make relative URLs absolute
     * @return array<string>
     */
    private function extractLogoUrls(string $html, string $baseUrl = ''): array
    {
        $urls = [];

        Log::info('extractLogoUrls called', ['html_length' => strlen($html), 'base_url' => $baseUrl]);

        try {
            // Find all <img> tags (using regex that handles multi-line)
            preg_match_all('/<img\b[^>]*>/is', $html, $imgTags, PREG_SET_ORDER);

            foreach ($imgTags as $imgMatch) {
                $imgTag = $imgMatch[0];

                // Check if this img tag has "logo" in any attribute
                if (stripos($imgTag, 'logo') === false) {
                    continue;
                }

                // Extract src attribute
                if (preg_match('/src=["\']([^"\']+)["\']/i', $imgTag, $srcMatch)) {
                    $urls[] = $srcMatch[1];
                }
            }

            // Also check for logo in CSS background-image
            preg_match_all('/background-image:\s*url\(["\']?([^"\'\)]+)["\']?\)/is', $html, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                if (!empty($match[1]) && stripos($match[1], 'logo') !== false) {
                    $urls[] = $match[1];
                }
            }

            // Make URLs absolute using base URL
            if ($baseUrl) {
                $parsedBase = parse_url($baseUrl);
                $baseSchemeHost = ($parsedBase['scheme'] ?? 'https') . '://' . ($parsedBase['host'] ?? '');
            }

            $urls = array_map(function ($url) use ($baseUrl, $baseSchemeHost) {
                // If already absolute, return as-is
                if (preg_match('/^https?:\/\//i', $url)) {
                    return $url;
                }
                // If protocol-relative, prepend https:
                if (str_starts_with($url, '//')) {
                    return 'https:' . $url;
                }
                // If path-relative (starts with /), prepend scheme+host
                if ($baseUrl && !empty($url) && $url[0] === '/') {
                    return ($baseSchemeHost ?? '') . $url;
                }
                return $url;
            }, $urls);

            // Log for debugging
            Log::info('Logo URLs extracted', [
                'urls' => $urls,
                'base_url' => $baseUrl,
                'img_tags_found' => count($imgTags),
            ]);
        } catch (\Throwable $e) {
            Log::error('Logo extraction error', ['error' => $e->getMessage()]);
        }

        return array_unique($urls);
    }

    public function scrapeUrl(string $url): array
    {
        $fetch = $this->fetchUrl($url);

        if (! $fetch['success']) {
            return ['success' => false, 'error' => "Gagal fetch: {$fetch['error']}"];
        }

        return $this->extractJobsFromText($fetch['content'], $url);
    }

    /**
     * Fetch and extract full details of a SINGLE job page.
     */
    public function scrapeDetailUrl(string $url): array
    {
        $fetch = $this->fetchUrl($url);

        if (! $fetch['success']) {
            return ['success' => false, 'error' => "Gagal fetch: {$fetch['error']}"];
        }

        return $this->extractJobDetailFromText($fetch['content'], $url);
    }

    public function extractJobDetailFromText(string $text, string $sourceUrl): array
    {
        // Extract logo URLs from text
        $logoUrls = $this->extractLogosFromText($text);

        $prompt = <<<PROMPT
Kamu adalah AI spesialis HR yang bertugas merangkum lowongan kerja. Dari teks halaman web berikut (yang sudah disederhanakan dengan penanda struktur seperti # untuk header dan - untuk list), buatlah ringkasan yang SANGAT RAPI, PADAT, dan MUDAH DIBACA dalam BAHASA INDONESIA.

Kembalikan dalam format JSON object (BUKAN array):
```json
{
  "title": "Judul posisi dalam Bahasa Indonesia yang profesional",
  "company": "Nama perusahaan",
  "company_logo": "URL logo perusahaan (ambil URL gambar logo saja, contoh: https://example.com/logo.png). Null jika tidak ditemukan.",
  "location": "Lokasi kerja (kota/provinsi)",
  "employment_type": "full-time|part-time|contract|internship|freelance",
  "salary_min": null,
  "salary_max": null,
  "description_raw": "Gunakan Markdown yang sangat rapi. Fokus pada poin-poin penting saja. WAJIB menggunakan struktur berikut (jika ada informasinya):\n\n### Tentang Pekerjaan\n(Penjelasan singkat 1-2 kalimat)\n\n### Tanggung Jawab\n- (Poin utama 1)\n- (Poin utama 2)\n\n### Kualifikasi\n- (Kualifikasi utama 1)\n- (Kualifikasi utama 2)\n\n### Keuntungan\n- (Benefit 1)\n- (Benefit 2)",
  "tags": ["tag1", "tag2"]
}
```

ATURAN SANGAT PENTING:
1. SEMUA teks dalam `description_raw` WAJIB diterjemahkan/ditulis ulang dalam BAHASA INDONESIA yang profesional, sopan, dan enak dibaca.
2. JANGAN menggunakan bullet points yang terlalu banyak; ambil 3-5 poin terpenting saja untuk setiap bagian agar tidak membosankan.
3. GUNAKAN EXACT HEADERS seperti di atas: `### Tentang Pekerjaan`, `### Tanggung Jawab`, `### Kualifikasi`, dan `### Keuntungan`. Jangan gunakan variasi lain agar sistem styling konsisten.
4. HANYA kembalikan JSON object murni. TIDAK BOLEH ada teks lain.
5. DILARANG KERAS menggunakan tag <think> atau reasoning! Langsung tulis `{` untuk memulai JSON.
6. Untuk company_logo: WAJIB menggunakan URL dari bagian "## LOGO URLS DITEMUKAN:" di bagian akhir teks jika tersedia. Jika tidak ada, cari di teks halaman.

TEKS HALAMAN:
{$text}
PROMPT;

        try {
            Log::info('Sending detail prompt to AI', ['url' => $sourceUrl, 'logo_urls' => $logoUrls]);
            $result = $this->ai->chat($prompt);

            if (empty($result['content'])) {
                return ['success' => false, 'error' => 'AI tidak memberikan response'];
            }

            $content = trim($result['content']);
            if (preg_match('/```(?:json)?\s*\n?([\s\S]*?)\n?\s*```/', $content, $matches)) {
                $content = trim($matches[1]);
            }
            if (preg_match('/\{\s*\S*\}/', $content, $matches)) {
                $content = $matches[0];
            }

            $decoded = json_decode($content, true);

            // Post-processing: if company_logo is null or missing, use extracted logo URLs
            if (!empty($logoUrls) && (empty($decoded['company_logo']) || $decoded['company_logo'] === null)) {
                $decoded['company_logo'] = $logoUrls[0];
                Log::info('Manual logo URL assigned (detail)', [
                    'url' => $sourceUrl,
                    'logo_url' => $logoUrls[0]
                ]);
            }

            return [
                'success' => true,
                'job' => $decoded,
            ];
        } catch (\Throwable $e) {
            Log::error('AI detail extraction error', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Full pipeline: process raw HTML string → extract with AI → return structured data.
     */
    public function scrapeHtml(string $html, string $sourceUrl): array
    {
        if (empty(trim($html))) {
            return ['success' => false, 'error' => 'HTML kosong.'];
        }

        $text = $this->stripHtmlToText($html, $sourceUrl);

        return $this->extractJobsFromText($text, $sourceUrl);
    }

    /**
     * Ingest extracted jobs into the database.
     *
     * @param  array<int, array<string, mixed>>  $jobs
     * @return array{total_received: int, inserted: int, updated: int, skipped: int, errors: array<int, array<string, mixed>>}
     */
    public function ingestJobs(array $jobs, string $sourceName): array
    {
        $source = JobSource::firstOrCreate(
            ['name' => $sourceName],
            ['is_active' => true],
        );

        $source->update(['last_scraped_at' => now()]);

        return $this->jobRepository->upsertFromSource(
            jobs: $jobs,
            sourceId: (string) $source->id,
            scrapedAt: now()->toISOString(),
        );
    }

    /**
     * Parse AI response into structured job array.
     */
    private function parseJobsFromAiResponse(string $content): array
    {
        $content = trim($content);

        // Try direct JSON decode
        $decoded = json_decode($content, true);
        if (is_array($decoded)) {
            return $this->isAssociative($decoded) ? [$decoded] : $decoded;
        }

        // Try extracting from markdown code block
        if (preg_match('/```(?:json)?\s*\n?([\s\S]*?)\n?\s*```/', $content, $matches)) {
            $decoded = json_decode(trim($matches[1]), true);
            if (is_array($decoded)) {
                return $this->isAssociative($decoded) ? [$decoded] : $decoded;
            }
        }

        // Try extracting JSON array from content
        if (preg_match('/\[[\s\S]*\]/', $content, $matches)) {
            $decoded = json_decode($matches[0], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    private function isAssociative(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
