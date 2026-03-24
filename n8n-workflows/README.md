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

## Alur Workflow (AI-Powered)

```
Cron 02:00 WIB
    ↓
Daftar Sumber (konfigurasi portal target)
    ↓
Scrape HTML (HTTP GET ke portal)
    ↓
Bersihkan & Potong HTML (hapus script/style/nav, potong ~3000 char)
    ↓
AI Extract Lowongan (OpenRouter membaca HTML, extract data terstruktur)
    ↓
Parse & Deduplicate (gabung chunks, hapus duplikat by URL, parse gaji)
    ↓
Batch per 5 (kirim ke AI per batch)
    ↓
AI Ringkasan + Tags (2 kalimat + 3-5 tags per lowongan)
    ↓
Gabung Enrichment (merge tags + summary ke data loker)
    ↓
Bangun Payload (format untuk Laravel API)
    ↓
POST ke Laravel (/api/v1/jobs/ingest)
    ↓
Log Hasil
```

## Keunggulan AI Extraction

Workflow ini **TIDAK menggunakan CSS selectors** untuk parsing HTML. Sebagai gantinya, AI (OpenRouter) langsung membaca HTML mentah dan mengekstrak data lowongan secara cerdas. Keuntungan:

- **Fleksibel**: bekerja dengan portal apapun tanpa perlu tahu struktur HTML
- **Tahan perubahan**: jika portal mengubah layout/class name, AI tetap bisa extract
- **Cukup tambahkan URL**: tidak perlu riset CSS selector setiap portal baru

Untuk menambahkan sumber baru, cukup edit node **"Daftar Sumber"** dan tambahkan URL.

## Catatan
- Workflow berjalan setiap hari pukul **02:00 WIB** (sesuaikan timezone di n8n Cloud settings)
- Deduplikasi dilakukan di 2 level: di n8n (by source_url per run) dan di Laravel (unique index)
- AI menggunakan model **gratis** dari OpenRouter (`meta-llama/llama-3.1-8b-instruct:free`)
- HTML dibersihkan (hapus script/svg/img) dan dipotong per ~3000 char agar muat context window
- Retry: scraping 3x, AI extraction 2x, AI enrichment 2x, ingest 3x
- Batch size 5: menghindari rate limit OpenRouter
- **Tanpa module eksternal** -- kompatibel dengan n8n Cloud
- Gaji otomatis diparsing dari teks (misal "Rp 5,4 jt" → salary_min: 5400000)
