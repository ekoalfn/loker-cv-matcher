<x-layout
    title="Terms of Service - Lamaraja"
    description="Syarat penggunaan Lamaraja untuk pencarian lowongan, CV Matcher, ringkasan AI, dan tautan lamaran ke situs pihak ketiga."
    canonical="{{ route('legal.terms') }}"
>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-teal-50/60 to-white">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-24 right-[-8rem] h-80 w-80 rounded-full bg-emerald-200/60 blur-3xl"></div>
            <div class="absolute top-1/2 -left-20 h-64 w-64 rounded-full bg-teal-200/40 blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 00-.993.883L9 3v1H7a1 1 0 00-.117 1.993L7 6v1H5a2 2 0 00-2 2v9a2 2 0 002 2h10a2 2 0 002-2V9a2 2 0 00-2-2h-2V6a1 1 0 00-1.117-.993L10 5V3a1 1 0 00-1-1zm0 2v1h1V4h-1zm-1 3v1h2V7H9zm-2 3a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                    Legal Center
                </span>
                <h1 class="mt-5 font-[family-name:var(--font-display)] text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-950">
                    Terms of <span class="text-emerald-600">Service</span>
                </h1>
                <p class="mt-5 max-w-xl text-lg leading-8 text-slate-600">
                    Gunakan Lamaraja sebagai alat bantu cari kerja: cepat, praktis, dan tetap dengan penilaian pribadi sebelum melamar.
                </p>
                <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1 ring-1 ring-slate-200 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                        Terakhir diperbarui: {{ now()->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- Highlight cards --}}
    <section class="border-y border-slate-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach([
                    [
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>',
                        'title' => 'Lowongan agregasi',
                        'text'  => 'Kami menampilkan informasi dari berbagai sumber publik dan tautan resmi.',
                        'color' => 'emerald',
                    ],
                    [
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>',
                        'title' => 'AI sebagai bantuan',
                        'text'  => 'Ringkasan dan skor cocok bukan nasihat karier final atau jaminan hasil.',
                        'color' => 'teal',
                    ],
                    [
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/>',
                        'title' => 'Apply di luar Lamaraja',
                        'text'  => 'Lamaran final biasanya terjadi di situs perusahaan atau platform sumber.',
                        'color' => 'indigo',
                    ],
                ] as $item)
                    <div class="flex items-start gap-4 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm hover:border-emerald-100 hover:shadow-md transition">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-{{ $item['color'] }}-50 text-{{ $item['color'] }}-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $item['icon'] !!}</svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-900">{{ $item['title'] }}</h2>
                            <p class="mt-1 text-sm leading-6 text-slate-500">{{ $item['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section class="bg-gradient-to-b from-white to-emerald-50/40 py-14 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-5 lg:grid-cols-2">
                @foreach([
                    [
                        'title' => 'Penggunaan layanan',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        'body'  => 'Kamu setuju memakai Lamaraja untuk tujuan yang wajar, tidak menyalahgunakan sistem, tidak melakukan scraping agresif, tidak mengirim file berbahaya, dan tidak mencoba mengakses area yang dilindungi tanpa izin.',
                    ],
                    [
                        'title' => 'Akurasi informasi lowongan',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
                        'body'  => 'Kami berusaha menjaga informasi tetap relevan, tetapi detail lowongan dapat berubah di situs sumber. Selalu cek ulang syarat, deadline, gaji, lokasi, dan proses rekrutmen di halaman resmi sebelum melamar.',
                    ],
                    [
                        'title' => 'Fitur AI dan CV Matcher',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>',
                        'body'  => 'Output AI dapat mengandung kesalahan atau interpretasi yang belum sempurna. Gunakan hasilnya sebagai referensi awal, bukan keputusan tunggal. Kamu tetap bertanggung jawab atas dokumen dan keputusan lamaran yang dikirim.',
                    ],
                    [
                        'title' => 'Tautan pihak ketiga',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/>',
                        'body'  => 'Lamaraja dapat mengarahkan kamu ke website perusahaan, job board, atau sumber lain. Kami tidak mengontrol konten, keamanan, atau kebijakan situs pihak ketiga tersebut.',
                    ],
                    [
                        'title' => 'Perubahan layanan',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
                        'body'  => 'Kami dapat memperbaiki, menghentikan, atau mengubah fitur kapan saja untuk alasan keamanan, kualitas, atau operasional. Perubahan besar akan dicerminkan di halaman legal ini jika relevan.',
                    ],
                    [
                        'title' => 'Kontak',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                        'body'  => 'Untuk pertanyaan terkait syarat penggunaan, hubungi hello@lamaraja.web.id.',
                    ],
                ] as $section)
                    <article class="group rounded-3xl border border-slate-100 bg-white p-6 shadow-sm hover:border-emerald-100 hover:shadow-md transition sm:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $section['icon'] !!}</svg>
                            </div>
                            <h2 class="font-[family-name:var(--font-display)] text-xl font-extrabold text-slate-950">{{ $section['title'] }}</h2>
                        </div>
                        <p class="text-base leading-8 text-slate-600">{{ $section['body'] }}</p>
                    </article>
                @endforeach
            </div>

            {{-- CTA bottom --}}
            <div class="mt-10 rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-600 to-teal-600 p-8 text-white shadow-xl shadow-emerald-900/15">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <h2 class="text-2xl font-extrabold">Ada pertanyaan tentang ToS?</h2>
                        <p class="mt-2 text-emerald-100">Hubungi kami, kami siap membantu.</p>
                    </div>
                    <a href="mailto:hello@lamaraja.web.id"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-bold text-emerald-700 shadow-sm hover:bg-emerald-50 transition shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        hello@lamaraja.web.id
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layout>
