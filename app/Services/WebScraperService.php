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
                ->timeout(30)
                ->get($url);

            if ($response->failed()) {
                return ['success' => false, 'error' => "HTTP {$response->status()}"];
            }

            $html = $response->body();
            $text = $this->stripHtmlToText($html);

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
        $prompt = <<<PROMPT
Kamu adalah ekstraktor data lowongan kerja. Dari teks halaman web berikut, ekstrak SEMUA lowongan kerja yang ditemukan.

Untuk setiap lowongan, ekstrak dalam format JSON array:
```json
[
  {
    "title": "Judul posisi",
    "company": "Nama perusahaan",
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

TEKS HALAMAN:
{$text}
PROMPT;

        try {
            Log::info('Sending prompt to AI for extraction', [
                'text_length' => strlen($text),
                'source_url' => $sourceUrl,
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
    private function stripHtmlToText(string $html): string
    {
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
        $text = preg_replace('/[ \t]+/', ' ', $text); // multiple spaces/tabs to single space
        $text = preg_replace('/\n\s*\n+/', "\n\n", $text); // multiple newlines to double newline
        $text = trim($text);

        // Limit to ~10000 chars
        return mb_substr($text, 0, 10000);
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
        $prompt = <<<PROMPT
Kamu adalah AI spesialis HR yang bertugas merangkum lowongan kerja. Dari teks halaman web berikut (yang sudah disederhanakan dengan penanda struktur seperti # untuk header dan - untuk list), buatlah ringkasan yang SANGAT RAPI, PADAT, dan MUDAH DIBACA dalam BAHASA INDONESIA.

Kembalikan dalam format JSON object (BUKAN array):
```json
{
  "title": "Judul posisi dalam Bahasa Indonesia yang profesional",
  "company": "Nama perusahaan",
  "location": "Lokasi kerja (kota/provinsi)",
  "employment_type": "full-time|part-time|contract|internship|freelance",
  "salary_min": null,
  "salary_max": null,
  "description_raw": "Gunakan Markdown yang sangat rapi. Fokus pada poin-poin penting saja. WAJIB menggunakan struktur berikut (jika ada informasinya):\\n\\n### Tentang Pekerjaan\\n(Penjelasan singkat 1-2 kalimat)\\n\\n### Tanggung Jawab\\n- (Poin utama 1)\\n- (Poin utama 2)\\n\\n### Kualifikasi\\n- (Kualifikasi utama 1)\\n- (Kualifikasi utama 2)\\n\\n### Keuntungan\\n- (Benefit 1)\\n- (Benefit 2)",
  "tags": ["tag1", "tag2"]
}
```

ATURAN SANGAT PENTING:
1. SEMUA teks dalam `description_raw` WAJIB diterjemahkan/ditulis ulang dalam BAHASA INDONESIA yang profesional, sopan, dan enak dibaca.
2. JANGAN menggunakan bullet points yang terlalu banyak; ambil 3-5 poin terpenting saja untuk setiap bagian agar tidak membosankan.
3. GUNAKAN EXACT HEADERS seperti di atas: `### Tentang Pekerjaan`, `### Tanggung Jawab`, `### Kualifikasi`, dan `### Keuntungan`. Jangan gunakan variasi lain agar sistem styling konsisten.
4. HANYA kembalikan JSON object murni. TIDAK BOLEH ada teks lain.
5. DILARANG KERAS menggunakan tag <think> atau reasoning! Langsung tulis `{` untuk memulai JSON.

TEKS HALAMAN:
{$text}
PROMPT;

        try {
            Log::info('Sending detail prompt to AI', ['url' => $sourceUrl]);
            $result = $this->ai->chat($prompt);

            if (empty($result['content'])) {
                return ['success' => false, 'error' => 'AI tidak memberikan response'];
            }

            $content = trim($result['content']);
            if (preg_match('/```(?:json)?\s*\n?([\s\S]*?)\n?\s*```/', $content, $matches)) {
                $content = trim($matches[1]);
            }
            if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
                $content = $matches[0];
            }

            $decoded = json_decode($content, true);

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

        $text = $this->stripHtmlToText($html);

        return $this->extractJobsFromText($text, $sourceUrl);
    }

    /**
     * Ingest extracted jobs into the database.
     *
     * @param  array  $jobs  Array of job data
     * @param  string  $sourceName  Source name
     * @return array{created: int, updated: int, skipped: int}
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
