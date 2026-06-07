@php
    $bulanIndonesia = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ][(int) date('n')];
@endphp

<x-layout
    title="AI Career Assistant - Cek CV, Latihan Interview & Cari Kerja | Lamaraja"
    description="Lamaraja adalah AI career copilot untuk pencari kerja Indonesia. Cek kecocokan CV dengan ATS checker, latihan interview AI, dan temukan lowongan yang paling pas."
>
    @php
        $aiAppLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'WebApplication',
            'name' => 'Lamaraja AI Career Assistant',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'url' => url('/'),
            'description' => 'AI career assistant untuk cek kecocokan CV, latihan interview, generator surat lamaran, dan rekomendasi lowongan kerja.',
            'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'IDR'],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp
    <script type="application/ld+json">{!! $aiAppLd !!}</script>

    {{-- HERO: AI-first --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-teal-50/60 to-white">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-28 right-[-10rem] h-96 w-96 rounded-full bg-emerald-200/60 blur-3xl"></div>
            <div class="absolute top-1/3 left-[-12rem] h-80 w-80 rounded-full bg-cyan-200/45 blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
            <div class="grid lg:grid-cols-[1.05fr_0.95fr] gap-10 lg:gap-12 items-center">
                <div class="animate-fade-up">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        AI Career Copilot untuk Pencari Kerja Indonesia
                    </span>

                    <h1 class="mt-5 font-[family-name:var(--font-display)] text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-950 leading-[1.08] tracking-tight">
                        Cari kerja lebih cerdas <span class="text-emerald-600">dengan AI.</span>
                    </h1>
                    <p class="mt-5 max-w-xl text-lg leading-8 text-slate-600">
                        Cocokkan CV, latihan interview, dan temukan lowongan yang paling pas. Semua dipandu AI, gratis untuk dicoba.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('cv-matcher.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-7 py-4 text-base font-bold text-white shadow-lg shadow-emerald-600/25 transition hover:bg-emerald-700 hover:shadow-xl active:scale-[0.98]">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Cek CV Sekarang
                        </a>
                        <a href="{{ route('mock-interview.landing') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-emerald-200 bg-white px-7 py-4 text-base font-bold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-50 active:scale-[0.98]">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            Latihan Interview AI
                        </a>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-500">
                        <span class="inline-flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Tanpa daftar</span>
                        <span class="inline-flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> CV langsung dihapus</span>
                        @if($totalJobs > 0)
                            <span class="inline-flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> {{ number_format($totalJobs) }}+ lowongan aktif</span>
                        @endif
                    </div>
                </div>

                {{-- Right: AI feature stack --}}
                <div class="animate-fade-up delay-200">
                    <div class="rounded-[2rem] border border-white/80 bg-white/90 p-5 sm:p-6 shadow-2xl shadow-emerald-900/10 backdrop-blur">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13 7H7v6h6V7z"/><path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/></svg>
                            Powered by AI
                        </div>
                        <div class="mt-4 space-y-3">
                            <a href="{{ route('cv-matcher.index') }}" class="flex items-center gap-4 rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 transition hover:border-emerald-300 hover:bg-emerald-50">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                <div><p class="font-bold text-slate-900">CV Matcher & ATS Checker</p><p class="text-sm text-slate-500">Skor kecocokan, skill cocok, gap & saran.</p></div>
                            </a>
                            <a href="{{ route('mock-interview.landing') }}" class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 transition hover:border-emerald-200 hover:bg-emerald-50/40">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-teal-600 text-white shadow-sm"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg></div>
                                <div><p class="font-bold text-slate-900">Mock Interview AI</p><p class="text-sm text-slate-500">Latihan interview + feedback instan.</p></div>
                            </a>
                            <a href="{{ route('ai-tools.cover-letter') }}" class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 transition hover:border-emerald-200 hover:bg-emerald-50/40">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                                <div><p class="font-bold text-slate-900">Cover Letter Generator</p><p class="text-sm text-slate-500">Surat lamaran instan dari CV + lowongan.</p></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- QUICK SEARCH (supporting) --}}
    <section class="border-y border-slate-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" name="keyword" placeholder="Posisi, skill, atau kata kunci" class="w-full h-12 pl-12 pr-4 rounded-xl border-2 border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all outline-none">
                </div>
                <div class="flex-1 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <input type="text" name="location" placeholder="Lokasi" class="w-full h-12 pl-12 pr-4 rounded-xl border-2 border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all outline-none">
                </div>
                <button type="submit" class="h-12 px-8 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl transition-all active:scale-[0.98]">Cari Loker</button>
            </form>
        </div>
    </section>

    {{-- AI TOOLS GRID --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
        <div class="text-center max-w-2xl mx-auto">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-600">AI Career Tools</p>
            <h2 class="mt-2 font-[family-name:var(--font-display)] text-3xl md:text-4xl font-extrabold text-slate-950 tracking-tight">Semua yang kamu butuhkan untuk melamar lebih siap</h2>
            <p class="mt-3 text-slate-600">Pakai AI di tiap tahap pencarian kerja, dari memperbaiki CV sampai latihan interview.</p>
        </div>

        <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @php
                $tools = [
                    ['route' => route('cv-matcher.index'), 'title' => 'CV Matcher (ATS Checker)', 'desc' => 'Cek kecocokan CV dengan lowongan, lihat skill cocok, gap, dan saran perbaikan.', 'color' => 'emerald'],
                    ['route' => route('mock-interview.landing'), 'title' => 'Latihan Interview AI', 'desc' => 'Simulasi interview dengan pertanyaan relevan dan feedback otomatis.', 'color' => 'teal'],
                    ['route' => route('ai-tools.cover-letter'), 'title' => 'Generator Surat Lamaran', 'desc' => 'Buat cover letter personal dari CV dan deskripsi lowongan dalam hitungan detik.', 'color' => 'indigo'],
                    ['route' => route('ai-tools.cv-rewrite'), 'title' => 'CV Rewrite / ATS Optimizer', 'desc' => 'Ubah pengalaman kerja jadi bullet point ATS-friendly berbasis pencapaian.', 'color' => 'violet'],
                    ['route' => route('ai-tools.skill-gap'), 'title' => 'Skill Gap Analyzer', 'desc' => 'Dari CV + target role, lihat skill yang kurang dan rencana belajarnya.', 'color' => 'amber'],
                    ['route' => route('ai-tools.career-path'), 'title' => 'AI Career Path', 'desc' => 'Rekomendasi role berikutnya yang realistis berdasarkan profil CV-mu.', 'color' => 'rose'],
                ];
                $colorMap = [
                    'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                    'teal' => 'bg-teal-50 text-teal-700 ring-teal-100',
                    'indigo' => 'bg-indigo-50 text-indigo-700 ring-indigo-100',
                    'violet' => 'bg-violet-50 text-violet-700 ring-violet-100',
                    'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
                    'rose' => 'bg-rose-50 text-rose-700 ring-rose-100',
                ];
            @endphp
            @foreach($tools as $tool)
                <a href="{{ $tool['route'] }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl ring-1 {{ $colorMap[$tool['color']] }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900 group-hover:text-emerald-700">{{ $tool['title'] }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $tool['desc'] }}</p>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-emerald-600">Coba sekarang
                        <svg class="w-4 h-4 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section class="bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 py-14 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center font-[family-name:var(--font-display)] text-2xl md:text-3xl font-bold text-slate-900">Cara kerjanya</h2>
            <div class="mt-10 grid md:grid-cols-3 gap-6">
                @foreach([
                    ['1', 'Upload CV kamu', 'Unggah CV PDF. AI membaca skill, pengalaman, dan profilmu secara otomatis.'],
                    ['2', 'Dapatkan insight AI', 'Lihat skor kecocokan, skill gap, saran perbaikan CV, dan latihan interview.'],
                    ['3', 'Lamar lebih yakin', 'Perbaiki CV, buat surat lamaran, lalu lamar lowongan yang paling pas.'],
                ] as $step)
                    <div class="rounded-2xl bg-white p-6 shadow-sm border border-emerald-100">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-white font-bold">{{ $step[0] }}</div>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $step[1] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $step[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- SUPPORTING: SEO HUBS --}}
    @php
        $seoHubs = collect(config('seo.job_landing_pages', []))->take(10);
    @endphp
    @if($seoHubs->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
            <div class="rounded-3xl border border-emerald-100 bg-white p-5 md:p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-600">Pencarian populer</p>
                        <h2 class="mt-1 font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-900">Jelajahi lowongan yang sering dicari</h2>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Lihat semua lowongan</a>
                </div>
                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach($seoHubs as $slug => $hub)
                        <a href="{{ route('jobs.landing', $slug) }}" class="rounded-full border border-emerald-100 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 transition hover:border-emerald-300 hover:bg-emerald-100">
                            {{ $hub['heading'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- SUPPORTING: RECENT JOBS --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="font-[family-name:var(--font-display)] text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">
                    Lowongan Terbaru <span class="text-emerald-600">untuk Kamu</span>
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    @if($jobsAddedToday > 0)<span class="font-semibold text-emerald-600">{{ $jobsAddedToday }}</span> loker baru hari ini · @endif
                    Diringkas AI, relevan, dan siap dicocokkan dengan CV-mu.
                </p>
            </div>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                Lihat semua lowongan
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        @if($recentJobs->count() > 0)
            <div class="space-y-4" data-reveal-stagger>
                @foreach($recentJobs->take(8) as $job)
                    <x-job-card :job="$job" />
                @endforeach
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl transition-all active:scale-[0.98]">
                    Lihat Semua Lowongan
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 text-center py-16 px-6">
                <p class="font-[family-name:var(--font-display)] text-lg font-bold text-slate-700">Belum ada lowongan tersedia</p>
                <p class="mt-1.5 text-sm text-slate-400">Sementara itu, kamu tetap bisa cek CV dan latihan interview di atas.</p>
            </div>
        @endif
    </section>

    {{-- FINAL CTA --}}
    <section class="bg-slate-900">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16 text-center">
            <h2 class="font-[family-name:var(--font-display)] text-3xl md:text-4xl font-extrabold text-white">Siap melamar dengan lebih percaya diri?</h2>
            <p class="mt-3 text-slate-300 max-w-2xl mx-auto">Mulai dari cek kecocokan CV gratis. Tidak perlu daftar, dan datamu tetap privat.</p>
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('cv-matcher.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-7 py-4 text-base font-bold text-white shadow-lg transition hover:bg-emerald-700 active:scale-[0.98]">Cek CV Sekarang</a>
                <a href="{{ route('mock-interview.landing') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-700 bg-slate-800 px-7 py-4 text-base font-bold text-white transition hover:bg-slate-700 active:scale-[0.98]">Latihan Interview AI</a>
            </div>
        </div>
    </section>

</x-layout>
