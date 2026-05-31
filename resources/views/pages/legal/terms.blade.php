<x-layout
    title="Terms of Service - Lamaraja"
    description="Syarat penggunaan Lamaraja untuk pencarian lowongan, CV Matcher, ringkasan AI, dan tautan lamaran ke situs pihak ketiga."
    canonical="{{ route('legal.terms') }}"
>
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 opacity-70" style="background-image: radial-gradient(circle at 15% 15%, rgba(20,184,166,.35), transparent 26%), radial-gradient(circle at 85% 0%, rgba(251,191,36,.22), transparent 24%), linear-gradient(120deg, rgba(255,255,255,.07) 0 1px, transparent 1px 100%); background-size: auto, auto, 38px 38px;"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-[0.28em] text-amber-200">Legal Center</p>
                <h1 class="mt-4 font-[family-name:var(--font-display)] text-4xl font-extrabold tracking-tight sm:text-6xl">Terms of Service</h1>
                <p class="mt-5 text-lg leading-8 text-slate-300">Gunakan Lamaraja sebagai alat bantu cari kerja: cepat, praktis, dan tetap dengan penilaian pribadi sebelum melamar.</p>
                <p class="mt-6 inline-flex rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm text-slate-200">Terakhir diperbarui: {{ now()->format('d M Y') }}</p>
            </div>
        </div>
    </section>

    <section class="bg-[#fbfaf6] py-14 lg:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-5 md:grid-cols-3">
                @foreach([
                    ['title' => 'Lowongan agregasi', 'text' => 'Kami menampilkan informasi dari berbagai sumber publik dan tautan resmi.'],
                    ['title' => 'AI sebagai bantuan', 'text' => 'Ringkasan dan skor cocok bukan nasihat karier final atau jaminan hasil.'],
                    ['title' => 'Apply di luar Lamaraja', 'text' => 'Lamaran final biasanya terjadi di situs perusahaan atau platform sumber.'],
                ] as $item)
                    <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-5 h-2 w-14 rounded-full bg-teal-500"></div>
                        <h2 class="text-xl font-extrabold text-slate-950">{{ $item['title'] }}</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                @foreach([
                    ['title' => 'Penggunaan layanan', 'body' => 'Kamu setuju memakai Lamaraja untuk tujuan yang wajar, tidak menyalahgunakan sistem, tidak melakukan scraping agresif, tidak mengirim file berbahaya, dan tidak mencoba mengakses area yang dilindungi tanpa izin.'],
                    ['title' => 'Akurasi informasi lowongan', 'body' => 'Kami berusaha menjaga informasi tetap relevan, tetapi detail lowongan dapat berubah di situs sumber. Selalu cek ulang syarat, deadline, gaji, lokasi, dan proses rekrutmen di halaman resmi sebelum melamar.'],
                    ['title' => 'Fitur AI dan CV Matcher', 'body' => 'Output AI dapat mengandung kesalahan atau interpretasi yang belum sempurna. Gunakan hasilnya sebagai referensi awal, bukan keputusan tunggal. Kamu tetap bertanggung jawab atas dokumen dan keputusan lamaran yang dikirim.'],
                    ['title' => 'Tautan pihak ketiga', 'body' => 'Lamaraja dapat mengarahkan kamu ke website perusahaan, job board, atau sumber lain. Kami tidak mengontrol konten, keamanan, atau kebijakan situs pihak ketiga tersebut.'],
                    ['title' => 'Perubahan layanan', 'body' => 'Kami dapat memperbaiki, menghentikan, atau mengubah fitur kapan saja untuk alasan keamanan, kualitas, atau operasional. Perubahan besar akan dicerminkan di halaman legal ini jika relevan.'],
                    ['title' => 'Kontak', 'body' => 'Untuk pertanyaan terkait syarat penggunaan, hubungi hello@lamaraja.web.id.'],
                ] as $section)
                    <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="font-[family-name:var(--font-display)] text-2xl font-extrabold text-slate-950">{{ $section['title'] }}</h2>
                        <p class="mt-4 text-base leading-8 text-slate-600">{{ $section['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</x-layout>
