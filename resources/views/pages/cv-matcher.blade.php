<x-layout>
    <x-slot:title>CV Matcher AI & ATS Checker - Cek Kecocokan CV dengan Lowongan | Lamaraja</x-slot:title>
    <x-slot:description>Upload CV PDF dan gunakan CV Matcher AI Lamaraja: cek skor kecocokan, skill cocok, skill gap, dan saran perbaikan CV agar lebih ATS-friendly. Gratis.</x-slot:description>
    <x-slot:robots>index, follow</x-slot:robots>
    <x-slot:canonical>{{ route('cv-matcher.index') }}</x-slot:canonical>

    @php
        $faqLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'Apa itu CV Matcher Lamaraja?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'CV Matcher Lamaraja adalah alat gratis untuk mencocokkan CV PDF dengan lowongan kerja aktif di Lamaraja menggunakan AI.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Apakah saya harus memilih lowongan manual?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Tidak. Upload CV sekali dan Lamaraja akan otomatis mencari lowongan yang paling cocok berdasarkan skill, pengalaman, dan profil di CV.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Apakah CV saya aman?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'File CV dipakai untuk analisis kecocokan dan dihapus setelah proses scan selesai.',
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $softwareLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => 'CV Matcher Lamaraja',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'url' => route('cv-matcher.index'),
            'description' => 'Alat gratis untuk mencocokkan CV dengan lowongan kerja aktif menggunakan AI.',
            'offers' => [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'IDR',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp

    <script type="application/ld+json">{!! $faqLd !!}</script>
    <script type="application/ld+json">{!! $softwareLd !!}</script>

    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-teal-50" x-data="cvMatcher()">
        <div class="absolute -top-24 -right-24 h-80 w-80 rounded-full bg-emerald-200/50 blur-3xl"></div>
        <div class="absolute top-40 -left-24 h-72 w-72 rounded-full bg-teal-100/70 blur-3xl"></div>

        <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="font-[family-name:var(--font-display)] text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-950 leading-tight">
                        CV Matcher <span class="text-emerald-600">AI</span>
                    </h1>
                    <p class="mt-5 text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        Cek kecocokan CV dengan lowongan, lebih cerdas dengan AI.
                    </p>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                        Upload CV sekali, dan AI akan menemukan lowongan yang paling cocok plus menampilkan skor kecocokan, skill yang cocok, skill gap, dan saran agar CV-mu lebih ATS-friendly.
                    </p>

                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm">
                            <div class="text-2xl font-extrabold text-emerald-600">Top 5</div>
                            <div class="mt-1 text-sm text-slate-500">rekomendasi lowongan</div>
                        </div>
                        <div class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm">
                            <div class="text-2xl font-extrabold text-emerald-600">PDF</div>
                            <div class="mt-1 text-sm text-slate-500">maksimal 5MB</div>
                        </div>
                        <div class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm">
                            <div class="text-2xl font-extrabold text-emerald-600">Privat</div>
                            <div class="mt-1 text-sm text-slate-500">file dihapus setelah scan</div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-emerald-100 bg-white p-5 sm:p-7 shadow-2xl shadow-emerald-900/10">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">Upload CV PDF</label>
                            <label class="group flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-emerald-200 bg-emerald-50/70 px-5 py-10 text-center transition hover:border-emerald-400 hover:bg-emerald-50">
                                <input type="file" accept="application/pdf,.pdf" class="sr-only" @change="handleFile">
                                <div class="h-16 w-16 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200 transition group-hover:scale-105">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <div class="mt-4 font-bold text-slate-900" x-text="file ? file.name : 'Klik untuk memilih CV' "></div>
                                <div class="mt-1 text-sm text-slate-500">PDF saja, maksimal 5MB.</div>
                            </label>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                            Lamaraja akan otomatis membandingkan CV kamu dengan lowongan aktif dan menampilkan alasan kenapa job tersebut cocok.
                        </div>

                        <div x-show="error" x-cloak class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" x-text="error"></div>

                        <button type="submit" :disabled="loading || !file" class="w-full rounded-2xl bg-emerald-600 px-5 py-4 font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                            <span x-show="!loading">Cari Lowongan yang Cocok</span>
                            <span x-show="loading" x-cloak>Mencari dan menganalisis lowongan...</span>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section x-show="matches.length" x-cloak class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 lg:pb-20 space-y-5">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 sm:p-8 text-slate-900 shadow-xl shadow-slate-200/60">
                <p class="text-sm font-bold uppercase tracking-wider text-emerald-600">Hasil CV Matcher</p>
                <h2 class="mt-2 font-[family-name:var(--font-display)] text-3xl font-extrabold text-slate-950">Lowongan paling cocok untuk CV kamu</h2>
                <p class="mt-2 text-slate-600">Urutan berdasarkan skor kecocokan AI dan relevansi profil dari CV yang kamu upload.</p>
            </div>

            <template x-for="match in matches" :key="match.scan_id">
                <article class="rounded-[1.75rem] border border-slate-200 bg-white p-5 sm:p-6 text-slate-900 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-5 justify-between">
                        <div class="flex gap-4 min-w-0">
                            <div class="h-14 w-14 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 flex items-center justify-center text-sm font-bold text-slate-500">
                                <template x-if="match.job.company_logo"><img :src="match.job.company_logo" :alt="match.job.company" class="h-full w-full object-contain p-1"></template>
                                <template x-if="!match.job.company_logo"><span x-text="initials(match.job.company)"></span></template>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xl font-bold text-slate-900" x-text="match.job.title"></h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    <span x-text="match.job.company"></span>
                                    <span x-show="match.job.location"> • <span x-text="match.job.location"></span></span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-center">
                                <div class="text-2xl font-black text-emerald-700" x-text="match.match_score + '%' "></div>
                                <div class="text-xs font-bold uppercase tracking-wide text-emerald-700">match</div>
                            </div>
                            <a :href="match.job.url" class="rounded-2xl border border-emerald-600 px-4 py-3 text-sm font-bold text-emerald-700 hover:bg-emerald-50">Lihat Job</a>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <template x-for="section in sections(match)" :key="section.title">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <h4 class="font-bold text-slate-900" x-text="section.title"></h4>
                                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                    <template x-for="item in section.items" :key="item">
                                        <li class="flex gap-2"><span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span><span x-text="item"></span></li>
                                    </template>
                                </ul>
                            </div>
                        </template>
                    </div>
                </article>
            </template>
        </section>

        <section class="relative bg-white/80 border-t border-emerald-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
                <div class="grid grid-cols-1 lg:grid-cols-[0.85fr_1.15fr] gap-10 items-start">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wider text-emerald-600">CV Matcher AI & ATS Checker Indonesia</p>
                        <h2 class="mt-3 font-[family-name:var(--font-display)] text-3xl sm:text-4xl font-extrabold text-slate-950">Cek kecocokan CV dengan lowongan sebelum melamar kerja</h2>
                        <p class="mt-4 text-slate-600 leading-8">
                            Banyak perusahaan memakai sistem ATS (Applicant Tracking System) untuk menyaring kandidat. CV Matcher AI Lamaraja membantu kamu sebagai ATS checker: melihat lowongan mana yang paling sesuai dengan pengalaman, skill, dan kata kunci di CV, lalu memberi saran cara memperbaiki CV agar lebih ATS-friendly.
                        </p>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ route('ai-tools.cv-rewrite') }}" class="rounded-full border border-emerald-100 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-100">Cara memperbaiki CV ATS</a>
                            <a href="{{ route('ai-tools.cover-letter') }}" class="rounded-full border border-emerald-100 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-100">Generator surat lamaran kerja</a>
                            <a href="{{ route('mock-interview.landing') }}" class="rounded-full border border-emerald-100 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-100">Latihan interview AI</a>
                            <a href="{{ route('ai-tools.skill-gap') }}" class="rounded-full border border-emerald-100 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-100">Skill gap analyzer</a>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="font-bold text-slate-950">ATS & keyword fit</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Analisis menyorot apakah CV kamu punya kata kunci dan pengalaman yang relevan dengan lowongan aktif.</p>
                        </div>
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="font-bold text-slate-950">Rekomendasi job otomatis</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Tidak perlu paste job description satu per satu. Lamaraja mencari kandidat lowongan dari database aktif.</p>
                        </div>
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="font-bold text-slate-950">Saran yang actionable</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Hasil scan berisi kekuatan, gap, dan saran agar CV lebih siap sebelum kamu melamar.</p>
                        </div>
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="font-bold text-slate-950">Gratis dan privat</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">CV diproses untuk analisis kecocokan dan file upload dihapus setelah proses selesai.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-14 rounded-[2rem] border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                    <h2 class="font-[family-name:var(--font-display)] text-2xl sm:text-3xl font-extrabold text-slate-950">FAQ CV Matcher</h2>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <h3 class="font-bold text-slate-950">Apa itu CV Matcher?</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">CV Matcher adalah alat untuk mencocokkan CV dengan lowongan kerja aktif menggunakan AI.</p>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-950">Apakah perlu memilih lowongan?</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Tidak. Upload CV dan Lamaraja akan otomatis mencarikan lowongan yang paling cocok.</p>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-950">Format CV apa yang didukung?</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Saat ini mendukung CV PDF hingga 5MB agar teks dapat dibaca dan dianalisis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function cvMatcher() {
            return {
                file: null,
                loading: false,
                error: null,
                matches: [],
                initials(name) {
                    return (name || '?').split(/\s+/).slice(0, 2).map(part => part[0]).join('').toUpperCase();
                },
                sections(match) {
                    return [
                        { title: 'Kekuatan', items: match.strengths || [] },
                        { title: 'Gap', items: match.weaknesses || [] },
                        { title: 'Saran', items: match.suggestions || [] },
                    ];
                },
                handleFile(event) {
                    const selected = event.target.files[0];
                    this.error = null;
                    this.matches = [];
                    if (!selected) return;
                    if (selected.type !== 'application/pdf' && !selected.name.toLowerCase().endsWith('.pdf')) {
                        this.error = 'CV harus berupa file PDF.';
                        event.target.value = '';
                        return;
                    }
                    if (selected.size > 5 * 1024 * 1024) {
                        this.error = 'Ukuran file CV maksimal 5MB.';
                        event.target.value = '';
                        return;
                    }
                    this.file = selected;
                },
                async submit() {
                    if (!this.file || this.loading) return;
                    this.loading = true;
                    this.error = null;
                    this.matches = [];

                    const formData = new FormData();
                    formData.append('pdf_file', this.file);

                    try {
                        const response = await fetch('{{ route('cv-scan.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });
                        const data = await response.json();
                        if (!response.ok) {
                            const validation = data.errors ? Object.values(data.errors).flat().join(' ') : '';
                            throw new Error(data.message || validation || 'Analisis CV gagal.');
                        }
                        this.matches = data.result?.matches || [];
                        this.$nextTick(() => document.querySelector('[x-show="matches.length"]')?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
                    } catch (err) {
                        this.error = err.message || 'Analisis CV gagal. Coba lagi beberapa saat lagi.';
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
</x-layout>
