<x-layout
    :title="$job->title . ' di ' . $job->company . ' - Lamaraja'"
    :description="Str::limit($job->summary_ai ?? $job->title . ' di ' . $job->company, 160)"
    ogType="article"
    :canonical="route('jobs.show', $job->slug)"
    :robots="($jobClosed ?? false) ? 'noindex, follow' : 'index, follow'"
>
    @php
        $gradients = [
            'from-teal-500 to-cyan-600',
            'from-indigo-500 to-blue-600',
            'from-emerald-500 to-teal-600',
            'from-amber-500 to-orange-600',
            'from-rose-500 to-pink-600',
            'from-violet-500 to-purple-600',
        ];
        $colorIndex = crc32($job->company ?? '') % count($gradients);
        $gradient = $gradients[$colorIndex];
        $companyInitial = strtoupper(mb_substr($job->company ?? '?', 0, 1));
        $employmentLabel = employment_label($job->employment_type) ?: 'Full Time';
        $summaryText = trim(strip_tags(html_entity_decode($job->summary_ai ?? '', ENT_QUOTES | ENT_HTML5)));
        $shortSummary = $summaryText !== '' ? Str::limit($summaryText, 210) : 'Temukan detail tanggung jawab, kualifikasi, dan informasi perusahaan untuk posisi ini.';
        $safeDescription = \App\Support\JobDescriptionFormatter::toHtml($job->description_raw ?? '');
        $visibleTags = collect($job->tags ?? [])->take(5);
    @endphp

    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-teal-50/60 to-white">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-28 right-[-10rem] h-96 w-96 rounded-full bg-emerald-200/60 blur-3xl"></div>
            <div class="absolute top-1/3 left-[-12rem] h-80 w-80 rounded-full bg-cyan-200/45 blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <nav class="mb-6 text-sm text-slate-500" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ route('home') }}" class="font-medium hover:text-emerald-700 transition-colors">Beranda</a></li>
                    <li class="text-slate-300">/</li>
                    <li><a href="{{ route('jobs.index') }}" class="font-medium hover:text-emerald-700 transition-colors">Lowongan</a></li>
                    <li class="text-slate-300">/</li>
                    <li class="text-slate-700 font-semibold truncate max-w-[220px] sm:max-w-lg" aria-current="page">{{ $job->title }}</li>
                </ol>
            </nav>

            <div class="grid lg:grid-cols-[minmax(0,1fr)_370px] gap-6 lg:gap-8 items-start">
                <main class="space-y-6">
                    <article class="rounded-[2rem] border border-white/80 bg-white/95 p-5 md:p-8 shadow-xl shadow-emerald-950/5 animate-fade-up">
                        @if($jobClosed ?? false)
                            <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                                <p class="text-sm font-bold">Lowongan ini sudah ditutup</p>
                                <p class="mt-1 text-sm">Detail lama tetap tersedia sebagai referensi. Lihat lowongan terkait atau kembali ke daftar lowongan aktif sebelum melamar.</p>
                            </div>
                        @endif

                        <header class="flex flex-col sm:flex-row gap-5">
                            @if($job->company_logo)
                                <img src="{{ $job->company_logo }}"
                                     alt="{{ $job->company }}"
                                     width="72"
                                     height="72"
                                     decoding="async"
                                     fetchpriority="high"
                                     referrerpolicy="no-referrer"
                                     class="h-20 w-20 sm:h-20 sm:w-20 rounded-2xl object-contain shrink-0 border border-slate-200 bg-white p-2 shadow-sm"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="hidden h-20 w-20 sm:h-20 sm:w-20 rounded-2xl bg-gradient-to-br {{ $gradient }} items-center justify-center shrink-0 shadow-sm">
                                    <span class="text-white font-bold text-2xl">{{ $companyInitial }}</span>
                                </div>
                            @else
                                <div class="h-20 w-20 sm:h-20 sm:w-20 rounded-2xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center shrink-0 shadow-sm">
                                    <span class="text-white font-bold text-2xl">{{ $companyInitial }}</span>
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="mb-3 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100">{{ $jobClosed ?? false ? 'Referensi lowongan' : 'Lowongan aktif' }}</span>
                                    <span class="inline-flex items-center rounded-full bg-teal-50 px-3 py-1 text-xs font-bold text-teal-700 ring-1 ring-teal-100">{{ $employmentLabel }}</span>
                                </div>

                                <h1 class="font-[family-name:var(--font-display)] text-3xl md:text-5xl font-extrabold tracking-tight leading-tight text-slate-950">{{ $job->title }}</h1>

                                <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-600">
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-slate-800">
                                        {{ $job->company }}
                                        <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.86-9.9a.75.75 0 00-1.22-.87l-3.2 4.48-1.66-1.66a.75.75 0 10-1.06 1.06l2.29 2.29a.75.75 0 001.14-.1l3.71-5.2z" clip-rule="evenodd" /></svg>
                                    </span>
                                    @if($job->location)
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            {{ $job->location }}
                                        </span>
                                    @endif
                                    <time datetime="{{ $job->created_at->toISOString() }}" class="inline-flex items-center gap-1.5">
                                        <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" /></svg>
                                        {{ $job->created_at->diffForHumans() }}
                                    </time>
                                </div>

                                @if($visibleTags->isNotEmpty())
                                    <div class="mt-5 flex flex-wrap gap-2">
                                        @foreach($visibleTags as $tag)
                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </header>

                        <div class="mt-7 border-t border-slate-100 pt-5">
                            <x-share-buttons :url="route('jobs.show', $job)" :title="$job->title" :company="$job->company" :location="$job->location" />
                        </div>

                        <section class="mt-8 border-t border-slate-100 pt-8">
                            <div class="mb-6 flex items-center gap-3">
                                <div class="h-10 w-10 rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 flex items-center justify-center">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" /></svg>
                                </div>
                                <h2 class="font-[family-name:var(--font-display)] text-2xl font-bold text-slate-900 tracking-tight">Deskripsi Pekerjaan</h2>
                            </div>

                            @if($summaryText !== '')
                                <p class="mb-6 text-[15px] leading-7 text-slate-600">{{ $shortSummary }}</p>
                            @endif

                            <div class="job-description-content text-sm md:text-[15px] text-slate-600 leading-relaxed">
                                {!! $safeDescription ?: '<p>Detail lowongan belum tersedia. Gunakan ringkasan cepat untuk melihat informasi utama posisi ini.</p>' !!}
                            </div>
                        </section>
                    </article>

                    @if(isset($relatedJobs) && $relatedJobs->count() > 0)
                        <section class="animate-fade-up delay-200">
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <div>
                                    <h2 class="font-[family-name:var(--font-display)] text-2xl font-bold text-slate-900">Lowongan Terkait</h2>
                                    <p class="text-sm text-slate-500">Peluang lain yang mungkin cocok untuk Anda.</p>
                                </div>
                                <a href="{{ route('jobs.index') }}" class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                                    Lihat semua
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </a>
                            </div>
                            <div class="grid md:grid-cols-3 gap-4">
                                @foreach($relatedJobs as $relatedJob)
                                    <x-job-card :job="$relatedJob" compact />
                                @endforeach
                            </div>
                        </section>
                    @endif
                </main>

                <aside class="lg:sticky lg:top-24 space-y-5 animate-fade-up delay-100">
                    {{-- AI Funnel: primary actions for this job --}}
                    <section class="rounded-[1.75rem] border border-emerald-200 bg-gradient-to-br from-emerald-50 to-teal-50/50 p-5 shadow-xl shadow-emerald-900/5">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13 7H7v6h6V7z"/><path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/></svg>
                            Persiapan dengan AI
                        </div>
                        <h2 class="mt-2 font-[family-name:var(--font-display)] text-lg font-bold text-slate-900">Tingkatkan peluang di posisi ini</h2>
                        <div class="mt-4 space-y-2.5">
                            <a href="#cv-match" @click.prevent="document.getElementById('cv-match')?.scrollIntoView({behavior:'smooth'})" class="flex items-center gap-3 rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Cek Kecocokan CV dengan Lowongan Ini
                            </a>
                            <a href="{{ route('ai-tools.interview-practice', ['job_id' => $job->id]) }}" class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-white px-4 py-3 text-sm font-bold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-50">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                Latihan Interview untuk Posisi Ini
                            </a>
                            <a href="{{ route('ai-tools.cover-letter') }}?job_id={{ $job->id }}" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50/40">
                                <svg class="w-5 h-5 shrink-0 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Buat Surat Lamaran dengan AI
                            </a>
                        </div>
                    </section>

                    <section id="cv-match" class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-xl shadow-slate-950/5 scroll-mt-24" x-data="cvMatcher()">
                        <h2 class="font-[family-name:var(--font-display)] text-lg font-bold text-slate-900">Cek Kecocokan CV dengan Lowongan Ini</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Upload CV dan dapatkan skor kecocokan, kekuatan, gap, dan draft ringkasan CV yang lebih ATS-friendly.</p>

                        <div x-show="!result" class="mt-5">
                            <div class="rounded-2xl border-2 border-dashed border-emerald-200 bg-emerald-50/40 p-6 text-center transition-all cursor-pointer hover:border-emerald-300 hover:bg-emerald-50"
                                :class="{ 'border-emerald-400 bg-emerald-50': isDragging }"
                                @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                @drop.prevent="handleDrop($event)"
                                @click="$refs.fileInput.click()">
                                <svg class="mx-auto mb-3 h-9 w-9 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                <p class="text-sm text-slate-600">Seret file CV ke sini, atau <span class="font-semibold text-emerald-600 underline">pilih file</span></p>
                                <p class="mt-1 text-xs text-slate-400">Hanya PDF, maksimal 5 MB</p>
                                <input type="file" accept=".pdf,application/pdf" class="sr-only" x-ref="fileInput" @change="handleFileSelect($event)">
                            </div>

                            <div x-show="file" x-cloak class="mt-3 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600">
                                <span class="truncate font-medium" x-text="file?.name"></span>
                                <span class="shrink-0 text-xs text-slate-400" x-text="file ? (file.size / 1024 / 1024).toFixed(1) + ' MB' : ''"></span>
                                <button @click.stop="file = null" class="ml-auto rounded-lg p-1 text-slate-400 hover:bg-red-50 hover:text-red-500" aria-label="Hapus file">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <p class="mt-3 flex items-center gap-1.5 text-xs text-slate-400">
                                <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A12 12 0 0112 2.944a12 12 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                CV dihapus otomatis setelah dianalisis.
                            </p>

                            <button type="button" class="mt-4 inline-flex w-full items-center justify-center rounded-xl px-5 py-3 text-sm font-bold transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400"
                                :class="!file || scanning ? 'cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20 hover:bg-emerald-700'"
                                :disabled="!file || scanning"
                                @click="uploadAndScan()">
                                <span x-show="!scanning">Analisis CV Saya</span>
                                <span x-show="scanning" x-cloak class="flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    Menganalisis...
                                </span>
                            </button>

                            <div x-show="error" x-cloak class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-600" role="alert" x-text="error"></div>
                        </div>

                        <div x-show="result" x-cloak x-transition class="mt-5 space-y-5" aria-live="polite" aria-atomic="true">
                            <div class="text-center">
                                <div class="relative inline-flex h-32 w-32 items-center justify-center">
                                    <svg class="h-32 w-32 -rotate-90" viewBox="0 0 120 120">
                                        <circle cx="60" cy="60" r="54" fill="none" stroke="#e5e7eb" stroke-width="8" />
                                        <circle cx="60" cy="60" r="54" fill="none" :stroke="result?.match_score >= 70 ? '#059669' : result?.match_score >= 40 ? '#d97706' : '#ef4444'" stroke-width="8" stroke-linecap="round" :stroke-dasharray="339.292" :stroke-dashoffset="339.292 - (339.292 * (result?.match_score || 0) / 100)" />
                                    </svg>
                                    <div class="absolute flex flex-col items-center">
                                        <span class="font-[family-name:var(--font-display)] text-3xl font-extrabold text-emerald-600" x-text="(result?.match_score || 0) + '%'"></span>
                                        <span class="text-xs font-medium text-slate-400">kecocokan</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="mb-2 text-sm font-bold text-emerald-700">Kekuatan Anda</h3>
                                <ul class="space-y-2">
                                    <template x-for="(item, i) in result?.strengths" :key="'s-'+i">
                                        <li class="flex items-start gap-2 rounded-xl bg-emerald-50 px-3 py-2 text-sm text-slate-600"><span class="font-bold text-emerald-600">✓</span><span x-text="item"></span></li>
                                    </template>
                                </ul>
                            </div>

                            <div>
                                <h3 class="mb-2 text-sm font-bold text-amber-700">Yang Bisa Ditingkatkan</h3>
                                <ul class="space-y-2">
                                    <template x-for="(item, i) in result?.weaknesses" :key="'w-'+i">
                                        <li class="flex items-start gap-2 rounded-xl bg-amber-50 px-3 py-2 text-sm text-slate-600"><span class="font-bold text-amber-600">!</span><span x-text="item"></span></li>
                                    </template>
                                </ul>
                            </div>

                            <template x-if="result?.suggestions?.length">
                                <div>
                                    <h3 class="mb-2 text-sm font-bold text-emerald-700">Rekomendasi Perbaikan CV</h3>
                                    <ul class="space-y-2">
                                        <template x-for="(item, i) in result?.suggestions" :key="'sg-'+i">
                                            <li class="flex items-start gap-2 rounded-xl bg-slate-50 px-3 py-2 text-sm text-slate-600"><span class="font-bold text-emerald-600">→</span><span x-text="item"></span></li>
                                        </template>
                                    </ul>
                                </div>
                            </template>

                            <template x-if="result?.ats_summary?.summary">
                                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="text-sm font-bold text-emerald-800">Draft Ringkasan CV (ATS-friendly)</h3>
                                        <button type="button" class="text-xs font-bold text-emerald-700 hover:underline" @click="copyText(result.ats_summary.summary, $event)">Salin</button>
                                    </div>
                                    <p class="mt-2 text-sm leading-6 text-slate-700" x-text="result.ats_summary.summary"></p>
                                    <div class="mt-3 flex flex-wrap gap-1.5">
                                        <template x-for="(kw, i) in result.ats_summary.keywords" :key="'kw-'+i">
                                            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100" x-text="kw"></span>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <a href="{{ route('jobs.apply', $job) }}" target="_blank" rel="nofollow noopener noreferrer" class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                                Lamar Sekarang
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>

                            <button type="button" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50" @click="resetMatcher()">Analisis CV Lainnya</button>
                        </div>
                    </section>

                    <section class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-xl shadow-slate-950/5">
                        <h2 class="font-[family-name:var(--font-display)] text-lg font-bold text-slate-900">Ringkasan Cepat</h2>
                        <dl class="mt-5 space-y-4 text-sm">
                            <div class="flex items-start justify-between gap-4"><dt class="text-slate-500">Tipe</dt><dd class="font-semibold text-slate-800 text-right">{{ $employmentLabel }}</dd></div>
                            <div class="flex items-start justify-between gap-4"><dt class="text-slate-500">Lokasi</dt><dd class="font-semibold text-slate-800 text-right">{{ $job->location ?: 'Indonesia' }}</dd></div>
                            <div class="flex items-start justify-between gap-4"><dt class="text-slate-500">Perusahaan</dt><dd class="font-semibold text-slate-800 text-right">{{ $job->company }}</dd></div>
                            <div class="flex items-start justify-between gap-4"><dt class="text-slate-500">Diposting</dt><dd class="font-semibold text-slate-800 text-right">{{ $job->created_at->diffForHumans() }}</dd></div>
                        </dl>
                        <div class="mt-5">
                            @if($jobClosed ?? false)
                                <x-button variant="secondary" :href="route('jobs.index')" class="w-full justify-center">Cari Lowongan Aktif</x-button>
                            @else
                                <x-button variant="accent" :href="route('jobs.apply', $job)" target="_blank" rel="nofollow noopener noreferrer" class="w-full justify-center">Lamar Sekarang <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg></x-button>
                            @endif
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </div>

    <script>
        function cvMatcher() {
            return {
                file: null, scanning: false, result: null, error: null, isDragging: false,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) this.setFile(file);
                },
                handleDrop(event) {
                    this.isDragging = false;
                    const file = event.dataTransfer.files[0];
                    if (file) this.setFile(file);
                },
                setFile(file) {
                    this.error = null;
                    if (file.type !== 'application/pdf') { this.error = 'Hanya file PDF yang diperbolehkan.'; return; }
                    if (file.size > 5 * 1024 * 1024) { this.error = 'Ukuran file maksimal 5 MB.'; return; }
                    this.file = file;
                },
                async uploadAndScan() {
                    if (!this.file) return;
                    this.scanning = true;
                    this.error = null;

                    const formData = new FormData();
                    formData.append('pdf_file', this.file);
                    formData.append('job_id', '{{ $job->id }}');

                    try {
                        const response = await fetch('/cv-scan', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || 'Gagal menganalisis CV.');
                        }
                        if (data.result) {
                            this.result = data.result;
                        } else {
                            throw new Error('Tidak ada hasil analisis.');
                        }
                    } catch (err) {
                        this.error = err.message || 'Terjadi kesalahan.';
                    } finally {
                        this.scanning = false;
                    }
                },
                resetMatcher() {
                    this.file = null;
                    this.result = null;
                    this.error = null;
                    this.scanning = false;
                },
                copyText(text, event) {
                    navigator.clipboard?.writeText(text);
                    if (event?.target) {
                        const el = event.target;
                        const original = el.textContent;
                        el.textContent = 'Tersalin!';
                        setTimeout(() => { el.textContent = original; }, 1500);
                    }
                },
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            const descContainer = document.querySelector('.job-description-content');
            if (!descContainer) return;

            const config = [
                { header: 'Tentang Pekerjaan', class: 'section-teal' },
                { header: 'Tanggung Jawab', class: 'section-blue' },
                { header: 'Responsibilities', class: 'section-blue' },
                { header: 'Kualifikasi', class: 'section-amber' },
                { header: 'Qualifications', class: 'section-amber' },
                { header: 'Requirement', class: 'section-amber' },
                { header: 'Persyaratan', class: 'section-amber' },
                { header: 'Tentang Perusahaan', class: 'section-blue' },
                { header: 'Company', class: 'section-blue' },
                { header: 'Keuntungan', class: 'section-green' },
                { header: 'Benefit', class: 'section-green' }
            ];

            if (!descContainer.querySelector('h3')) {
                let html = descContainer.innerHTML;
                config.forEach(item => {
                    const regex = new RegExp(`(^|<br>|<p>|\\n)(${item.header})(:)?(\\s|\\n|<|$)`, 'gi');
                    html = html.replace(regex, (match, p1, p2) => `${p1}<h3>${p2}</h3>`);
                });
                descContainer.innerHTML = html;
            }

            const children = Array.from(descContainer.children);
            let currentCard = null;

            children.forEach(child => {
                if (child.tagName === 'H3') {
                    currentCard = document.createElement('div');
                    currentCard.className = 'rounded-3xl border border-slate-100 bg-slate-50/70 p-5 md:p-6 mb-5 shadow-sm';
                    const text = child.textContent.toLowerCase();
                    const match = config.find(item => text.includes(item.header.toLowerCase()));
                    child.classList.add(match ? match.class : 'section-teal');
                    descContainer.insertBefore(currentCard, child);
                    currentCard.appendChild(child);
                } else if (currentCard) {
                    currentCard.appendChild(child);
                }
            });

            if (!descContainer.querySelector('.rounded-3xl')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'rounded-3xl border border-slate-100 bg-slate-50/70 p-5 md:p-6 shadow-sm';
                while (descContainer.firstChild) {
                    wrapper.appendChild(descContainer.firstChild);
                }
                descContainer.appendChild(wrapper);
            }
        });
    </script>

    @php
        $schemaDescription = trim(strip_tags(html_entity_decode($job->description_raw ?? $job->summary_ai ?? $job->title, ENT_QUOTES | ENT_HTML5)));

        $jobPosting = [
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $job->title,
            'description' => Str::limit($schemaDescription, 5000),
            'identifier' => [
                '@type' => 'PropertyValue',
                'name' => 'Lamaraja',
                'value' => (string) $job->id,
            ],
            'url' => route('jobs.show', $job->slug),
            'datePosted' => $job->created_at->toIso8601String(),
            'validThrough' => ($job->expires_at ?? $job->created_at->copy()->addDays(60))->toIso8601String(),
            'hiringOrganization' => array_filter([
                '@type' => 'Organization',
                'name' => $job->company,
                'logo' => $job->company_logo,
            ]),
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $job->location ?? 'Indonesia',
                    'addressCountry' => 'ID',
                ],
            ],
            'employmentType' => strtoupper(str_replace('-', '_', is_object($job->employment_type) ? $job->employment_type->value : ($job->employment_type ?? 'FULL_TIME'))),
            'directApply' => false,
        ];

        if (str_contains(strtolower((string) $job->location), 'remote') || (is_object($job->employment_type) && $job->employment_type->value === 'remote')) {
            $jobPosting['jobLocationType'] = 'TELECOMMUTE';
            $jobPosting['applicantLocationRequirements'] = [
                '@type' => 'Country',
                'name' => 'Indonesia',
            ];
        }

        if ($job->salary_min || $job->salary_max) {
            $jobPosting['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => $job->salary_currency ?? 'IDR',
                'value' => array_filter([
                    '@type' => 'QuantitativeValue',
                    'minValue' => $job->salary_min,
                    'maxValue' => $job->salary_max,
                    'unitText' => 'MONTH',
                ], fn ($value) => ! is_null($value)),
            ];
        }

        $jobLd = json_encode($jobPosting, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $breadcrumbLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Lowongan', 'item' => route('jobs.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $job->title],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp
    {!! '<script type="application/ld+json">' . $jobLd . '</script>' !!}
    {!! '<script type="application/ld+json">' . $breadcrumbLd . '</script>' !!}
</x-layout>
