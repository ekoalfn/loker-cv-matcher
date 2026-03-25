<x-layout
    :title="$job->title . ' di ' . $job->company . ' - Lamaraja'"
    :description="Str::limit($job->summary_ai ?? $job->title . ' di ' . $job->company, 160)"
    ogType="article"
>

    @php
        $avatarColors = [
            'bg-teal-600', 'bg-blue-600', 'bg-emerald-600',
            'bg-amber-600', 'bg-rose-600', 'bg-cyan-600',
        ];
        $colorIndex = crc32($job->company ?? '') % count($avatarColors);
        $avatarBg = $avatarColors[$colorIndex];
        $companyInitial = strtoupper(mb_substr($job->company ?? '?', 0, 1));
    @endphp

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-slate-500" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="hover:text-teal-600 transition-colors">Beranda</a></li>
                <li class="text-slate-300">/</li>
                <li><a href="{{ route('jobs.index') }}" class="hover:text-teal-600 transition-colors">Lowongan</a></li>
                <li class="text-slate-300">/</li>
                <li class="text-slate-700 font-medium truncate max-w-[200px] sm:max-w-none" aria-current="page">{{ $job->title }}</li>
            </ol>
        </nav>

        {{-- Job Header --}}
        <header class="mb-8 animate-fade-up">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl {{ $avatarBg }} flex items-center justify-center shrink-0">
                    <span class="text-white font-bold text-lg md:text-xl">{{ $companyInitial }}</span>
                </div>

                <div class="min-w-0 flex-1">
                    <h1 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-extrabold text-slate-900 leading-tight tracking-tight">
                        {{ $job->title }}
                    </h1>

                    <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm text-slate-500">
                        <span class="font-medium text-slate-700">{{ $job->company }}</span>
                        @if($job->location)
                            <span class="text-slate-300">&middot;</span>
                            <span>{{ $job->location }}</span>
                        @endif
                        @if($job->employment_type)
                            <span class="text-slate-300">&middot;</span>
                            <x-badge :text="employment_label($job->employment_type)" color="blue" />
                        @endif
                        <span class="text-slate-300">&middot;</span>
                        <time datetime="{{ $job->created_at->toISOString() }}">{{ $job->created_at->diffForHumans() }}</time>
                    </div>
                </div>
            </div>

            {{-- Salary --}}
            @if($job->salary_min || $job->salary_max)
                <div class="mt-5 inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-emerald-50 border border-emerald-100">
                    <span class="text-base font-bold text-emerald-700">
                        @if($job->salary_min && $job->salary_max)
                            Rp {{ number_format($job->salary_min, 0, ',', '.') }} - {{ number_format($job->salary_max, 0, ',', '.') }}
                        @elseif($job->salary_min)
                            Mulai Rp {{ number_format($job->salary_min, 0, ',', '.') }}
                        @else
                            Hingga Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                        @endif
                    </span>
                    <span class="text-xs text-emerald-600">/bulan</span>
                </div>
            @endif

            {{-- Tags --}}
            @if(!empty($job->tags))
                <div class="mt-4 flex flex-wrap gap-1.5">
                    @foreach($job->tags as $tag)
                        <x-badge :text="$tag" color="gray" />
                    @endforeach
                </div>
            @endif

            {{-- Share buttons --}}
            <div class="mt-5 pt-4 border-t border-slate-100">
                <x-share-buttons
                    :url="route('jobs.show', $job)"
                    :title="$job->title"
                    :company="$job->company"
                    :location="$job->location"
                />
            </div>
        </header>

        <hr class="border-slate-100 mb-8">

        {{-- AI Summary --}}
        @if($job->summary_ai)
            <section class="mb-8 animate-fade-up delay-100">
                <div class="bg-amber-50/60 border border-amber-100 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-amber-700 mb-1">Ringkasan AI</p>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $job->summary_ai }}</p>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Description --}}
        @if($job->description_raw)
            <section class="mb-8 animate-fade-up delay-200">
                <h2 class="text-lg font-bold text-slate-900 mb-3">Deskripsi Pekerjaan</h2>
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $job->description_raw }}</div>
            </section>
        @endif

        <hr class="border-slate-100 mb-8">

        {{-- CTA: Apply --}}
        <section class="mb-10 animate-fade-up delay-300">
            <div class="surface rounded-xl p-6 md:p-8 text-center">
                <h2 class="text-lg font-bold text-slate-900 mb-1.5">Tertarik dengan posisi ini?</h2>
                <p class="text-sm text-slate-500 mb-5">Lamar langsung di situs resmi perusahaan.</p>
                <x-button variant="accent" :href="route('jobs.apply', $job)">
                    Lamar Sekarang
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </x-button>
            </div>
        </section>

        {{-- CV Matcher --}}
        <section
            class="surface rounded-xl overflow-hidden transition-colors"
            :class="{ 'scanning-shimmer': scanning }"
            x-data="cvMatcher()"
        >
            <div class="p-6 md:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-1">Cek Kecocokan CV</h2>
                <p class="text-sm text-slate-500 mb-6">Upload CV dan lihat seberapa cocok dengan lowongan ini.</p>

                {{-- Upload Area --}}
                <div x-show="!result">
                    <div
                        class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center transition-colors cursor-pointer hover:border-teal-300 hover:bg-teal-50/30"
                        :class="{ 'border-teal-400 bg-teal-50 drag-active': isDragging }"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        @click="$refs.fileInput.click()"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-sm text-slate-600 mb-1">
                            Seret file CV ke sini, atau <span class="text-teal-600 font-medium">pilih file</span>
                        </p>
                        <p class="text-xs text-slate-400">Hanya PDF, maksimal 5 MB</p>

                        <input type="file" accept=".pdf,application/pdf" class="sr-only" x-ref="fileInput" @change="handleFileSelect($event)">
                    </div>

                    {{-- Selected File --}}
                    <div x-show="file" x-cloak class="mt-3 flex items-center gap-3 text-sm text-slate-700 bg-slate-50 rounded-lg px-4 py-2.5 border border-slate-100">
                        <span class="truncate font-medium" x-text="file?.name"></span>
                        <span class="text-slate-400 text-xs shrink-0" x-text="file ? (file.size / 1024 / 1024).toFixed(1) + ' MB' : ''"></span>
                        <button @click.stop="file = null" class="text-slate-400 hover:text-red-500 ml-auto shrink-0 p-1 rounded hover:bg-red-50 transition-colors" aria-label="Hapus file">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <p class="mt-3 text-xs text-slate-400 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        CV dihapus otomatis setelah dianalisis.
                    </p>

                    <div class="mt-4">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center w-full sm:w-auto min-h-[2.5rem] px-5 py-2 rounded-lg font-semibold text-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-amber-500 active:scale-[0.97]"
                            :class="!file || scanning ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-amber-500 text-white hover:bg-amber-600 shadow-sm cursor-pointer'"
                            :disabled="!file || scanning"
                            @click="uploadAndScan()"
                        >
                            <span x-show="!scanning">Analisis CV Saya</span>
                            <span x-show="scanning" x-cloak class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Menganalisis...
                            </span>
                        </button>
                    </div>

                    <div x-show="error" x-cloak x-transition:enter="animate-shake"
                        class="mt-3 text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg p-3 flex items-start gap-2" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span x-text="error"></span>
                    </div>
                </div>

                {{-- Results --}}
                <div x-show="result" x-cloak
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="space-y-5"
                    aria-live="polite" aria-atomic="true"
                >
                    {{-- Score --}}
                    <div class="text-center py-4">
                        <div class="relative inline-flex items-center justify-center w-32 h-32">
                            <svg class="w-32 h-32 -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="54" fill="none" stroke="rgb(241 245 249)" stroke-width="8" />
                                <circle cx="60" cy="60" r="54" fill="none"
                                    :stroke="result?.match_score >= 70 ? '#10b981' : result?.match_score >= 40 ? '#f59e0b' : '#ef4444'"
                                    stroke-width="8" stroke-linecap="round"
                                    :stroke-dasharray="339.292"
                                    :stroke-dashoffset="339.292 - (339.292 * (result?.match_score || 0) / 100)"
                                    class="gauge-circle gauge-animate" />
                            </svg>
                            <div class="absolute flex flex-col items-center">
                                <span class="font-[family-name:var(--font-display)] text-3xl font-extrabold" :class="{
                                    'text-emerald-600': result?.match_score >= 70,
                                    'text-amber-500': result?.match_score >= 40 && result?.match_score < 70,
                                    'text-red-500': result?.match_score < 40
                                }" x-text="(result?.match_score || 0) + '%'"></span>
                                <span class="text-xs text-slate-400 font-medium">kecocokan</span>
                            </div>
                        </div>
                    </div>

                    {{-- Strengths --}}
                    <div>
                        <h3 class="text-sm font-semibold text-emerald-700 mb-2">Kekuatan</h3>
                        <ul class="space-y-1.5">
                            <template x-for="(item, i) in result?.strengths" :key="'s-'+i">
                                <li class="text-sm text-slate-700 flex items-start gap-2 bg-emerald-50 rounded-lg px-3 py-2.5 border border-emerald-100">
                                    <span class="text-emerald-500 font-semibold shrink-0">+</span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    {{-- Weaknesses --}}
                    <div>
                        <h3 class="text-sm font-semibold text-red-700 mb-2">Kekurangan</h3>
                        <ul class="space-y-1.5">
                            <template x-for="(item, i) in result?.weaknesses" :key="'w-'+i">
                                <li class="text-sm text-slate-700 flex items-start gap-2 bg-red-50 rounded-lg px-3 py-2.5 border border-red-100">
                                    <span class="text-red-500 font-semibold shrink-0">&minus;</span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    {{-- Suggestions --}}
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 mb-2">Saran Perbaikan</h3>
                        <ul class="space-y-1.5">
                            <template x-for="(item, i) in result?.suggestions" :key="'sg-'+i">
                                <li class="text-sm text-slate-700 flex items-start gap-2 bg-slate-50 rounded-lg px-3 py-2.5 border border-slate-100">
                                    <span class="text-teal-500 font-semibold shrink-0">&rarr;</span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <x-button variant="secondary" type="button" @click="resetMatcher()">
                            Analisis CV Lainnya
                        </x-button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function cvMatcher() {
            return {
                file: null, scanning: false, result: null, error: null,
                isDragging: false, pollInterval: null, pollCount: 0, maxPolls: 60,

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
                    this.scanning = true; this.error = null; this.pollCount = 0;
                    const formData = new FormData();
                    formData.append('pdf_file', this.file);
                    formData.append('job_id', '{{ $job->id }}');
                    try {
                        const response = await fetch('/cv-scan', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                            body: formData,
                        });
                        if (!response.ok) { const data = await response.json(); throw new Error(data.message || 'Gagal mengupload CV.'); }
                        const data = await response.json();
                        this.pollStatus(data.scan_id);
                    } catch (err) { this.error = err.message || 'Terjadi kesalahan.'; this.scanning = false; }
                },
                pollStatus(scanId) {
                    this.pollInterval = setInterval(async () => {
                        this.pollCount++;
                        if (this.pollCount >= this.maxPolls) { clearInterval(this.pollInterval); this.error = 'Analisis memakan waktu terlalu lama.'; this.scanning = false; return; }
                        try {
                            const response = await fetch(`/cv-scan/${scanId}/status`, { headers: { 'Accept': 'application/json' } });
                            if (!response.ok) throw new Error('Gagal memeriksa status.');
                            const data = await response.json();
                            if (data.status === 'completed') { clearInterval(this.pollInterval); this.result = data.result; this.scanning = false; }
                            else if (data.status === 'failed') { clearInterval(this.pollInterval); this.error = 'Analisis gagal. Coba file PDF lain.'; this.scanning = false; }
                        } catch (err) { clearInterval(this.pollInterval); this.error = err.message; this.scanning = false; }
                    }, 2000);
                },
                resetMatcher() { this.file = null; this.result = null; this.error = null; this.scanning = false; this.pollCount = 0; if (this.pollInterval) clearInterval(this.pollInterval); },
            };
        }
    </script>

    @php
        $jobLd = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $job->title,
            'description' => Str::limit($job->description_raw ?? $job->summary_ai ?? $job->title, 5000),
            'datePosted' => $job->created_at->toIso8601String(),
            'hiringOrganization' => ['@type' => 'Organization', 'name' => $job->company],
            'jobLocation' => ['@type' => 'Place', 'address' => ['@type' => 'PostalAddress', 'addressLocality' => $job->location ?? 'Indonesia', 'addressCountry' => 'ID']],
            'employmentType' => strtoupper(str_replace('-', '_', is_object($job->employment_type) ? $job->employment_type->value : ($job->employment_type ?? 'FULL_TIME'))),
            'directApply' => false,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $breadcrumbLd = json_encode([
            '@context' => 'https://schema.org',
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
