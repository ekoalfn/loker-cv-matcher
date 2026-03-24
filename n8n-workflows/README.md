# n8n Workflows - Portal Loker

## File Workflow

| File | Fungsi |
|---|---|
| `01-scraping-enrichment-ingest.json` | Workflow utama: scraping + AI enrichment + kirim ke Laravel API |

## Cara Import ke n8n Cloud

1. Buka **n8n Cloud** dashboard
2. Klik **+ Add Workflow** (atau Ctrl+K → "Import")
3. Pilih **Import from File**
4. Upload file `01-scraping-enrichment-ingest.json`

## Setup Credentials di n8n

Setelah import, kamu perlu setup 2 credentials:

### 1. OpenRouter API Key
- Buka **Settings → Credentials → Add Credential**
- Pilih **Header Auth**
- Name: `OpenRouter API Key`
- Header Name: `Authorization`
- Header Value: `Bearer sk-or-v1-XXXXXXXXXXXXXXX` (ambil dari https://openrouter.ai/keys)
- Di workflow, klik node **"AI Enrichment (OpenRouter)"** → pilih credential ini

### 2. Laravel Scraping API Key
- Buka **Settings → Credentials → Add Credential**
- Pilih **Header Auth**
- Header Name: `Authorization`
- Header Value: `Bearer SCRAPING_API_KEY_KAMU` (sama dengan `SCRAPING_API_KEY` di `.env` Laravel)
- Di workflow, klik node **"POST ke Laravel API"** → pilih credential ini

### 3. Set Environment Variable
- Buka **Settings → Variables**
- Tambahkan: `LARAVEL_API_URL` = `https://namaprojek.my.id` (URL production)

## Kustomisasi Sumber Scraping

### Ubah Portal Target
Edit node **"Daftar Sumber Scraping"** → ubah array `sources`:

```javascript
const sources = [
  {
    name: 'jobstreet',
    scrape_url: 'https://www.jobstreet.co.id/lowongan-kerja',
  },
  {
    name: 'glints',
    scrape_url: 'https://glints.com/id/lowongan',
  },
];
```

### Ubah CSS Selector
Edit node **"Parse HTML ke Data Loker"** → sesuaikan selector CSS:

```javascript
// Contoh untuk Jobstreet:
$('article[data-job-id]').each((i, el) => {
  const title = $(el).find('h1, [data-automation="jobTitle"]').text().trim();
  const company = $(el).find('[data-automation="jobCompany"]').text().trim();
  // dst...
});
```

## Alur Workflow

```
Cron 02:00 WIB
    ↓
Daftar Sumber Scraping (konfigurasi portal target)
    ↓
Scrape Halaman Index (HTTP GET ke portal)
    ↓
Extract Data dari HTML (n8n built-in HTML node, CSS selectors)
    ↓
Bangun Array Jobs (gabungkan hasil extract)
    ↓
Batch per 5 Item (kirim ke AI per batch)
    ↓
AI Enrichment via OpenRouter (ringkasan 2 kalimat + tags)
    ↓
Parse AI Response (JSON parsing + regex fallback)
    ↓
Bangun Payload Ingest (format untuk Laravel API)
    ↓
POST ke Laravel API (/api/v1/jobs/ingest)
    ↓
Log Hasil
```

## Kustomisasi CSS Selector

Edit node **"Extract Data dari HTML"** untuk mengubah CSS selector sesuai portal target.
Node ini menggunakan n8n built-in HTML extraction (bukan cheerio/library eksternal).

## Catatan
- Workflow berjalan setiap hari pukul **02:00 WIB** (sesuaikan timezone di n8n Cloud settings)
- Deduplikasi dilakukan di sisi Laravel (by `source_url`), bukan di n8n
- AI enrichment menggunakan model **gratis** dari OpenRouter (`meta-llama/llama-3.1-8b-instruct:free`)
- Retry: scraping 3x, AI 2x, ingest 3x
- Batch size 5: menghindari rate limit OpenRouter
- **Tidak menggunakan cheerio/module eksternal** -- kompatibel dengan n8n Cloud
