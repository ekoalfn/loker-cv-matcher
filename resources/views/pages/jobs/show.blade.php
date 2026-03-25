<x-layout
    :title="$job->title . ' di ' . $job->company . ' - Lamaraja'"
    :description="Str::limit($job->summary_ai ?? $job->title . ' di ' . $job->company, 160)"
    ogType="article"
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
        $colorIndex    = crc32($job->company ?? '') % count($gradients);
        $gradient      = $gradients[$colorIndex];
        $companyInitial = strtoupper(mb_substr($job->company ?? '?', 0, 1));
    @endphp

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-stone-400" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}"       class="hover:text-teal-600 transition-colors">Beranda</a></li>
                <li class="text-stone-300">/</li>
                <li><a href="{{ route('jobs.index') }}" class="hover:text-teal-600 transition-colors">Lowongan</a></li>
                <li class="text-stone-300">/</li>
                <li class="text-slate-600 font-medium truncate max-w-[200px] sm:max-w-none" aria-current="page">{{ $job->title }}</li>
            </ol>
        </nav>

        {{-- Job Header --}}
        <header class="mb-8 animate-fade-up">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center shrink-0"
                     style="box-shadow: 0 2px 8px rgba(0,0,0,0.10);">
                    <span class="text-white font-bold text-xl">{{ $companyInitial }}</span>
                </div>

                <div class="min-w-0 flex-1">
                    <h1 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-extrabold text-slate-800 leading-tight tracking-tight">
                        {{ $job->title }}
                    </h1>
                    <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm text-stone-500">
                        <span class="font-medium text-slate-700">{{ $job->company }}</span>
                        @if($job->location)
                            <span class="text-stone-300">&middot;</span>
                            <span>{{ $job->location }}</span>
                        @endif
                        @if($job->employment_type)
                            <span class="text-stone-300">&middot;</span>
                            <x-badge :text="employment_label($job->employment_type)" color="blue" />
                        @endif
                        <span class="text-stone-300">&middot;</span>
                        <time datetime="{{ $job->created_at->toISOString() }}">{{ $job->created_at->diffForHumans() }}</time>
                    </div>
                </div>
            </div>

            {{-- Salary --}}
            @if($job->salary_min || $job->salary_max)
                <div class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-xl badge-green">
                    <span class="text-base font-bold">
                        @if($job->salary_min && $job->salary_max)
                            Rp {{ number_format($job->salary_min, 0, ',', '.') }} – {{ number_format($job->salary_max, 0, ',', '.') }}
                        @elseif($job->salary_min)
                            Mulai Rp {{ number_format($job->salary_min, 0, ',', '.') }}
                        @else
                            Hingga Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                        @endif
                    </span>
                    <span class="text-xs opacity-70">/bulan</span>
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

            {{-- Share --}}
            <div class="mt-5 pt-4" style="border-top: 1px solid #eae7e3;">
                <x-share-buttons
                    :url="route('jobs.show', $job)"
                    :title="$job->title"
                    :company="$job->company"
                    :location="$job->location"
                />
            </div>
        </header>

        <div class="mb-8" style="border-top: 1px solid #eae7e3;"></div>

        {{-- AI Summary --}}
        @if($job->summary_ai)
            <section class="mb-8 animate-fade-up delay-100">
                <div class="rounded-2xl p-5 "
                     style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.18);">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 w-8 h-8 rounded-xl flex items-center justify-center"
                             style="background: rgba(245,158,11,0.80); box-shadow: 0 2px 8px rgba(245,158,11,0.30);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-amber-700 mb-1.5">Ringkasan AI</p>
                            <p class="text-sm text-stone-600 leading-relaxed">{{ $job->summary_ai }}</p>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Description --}}
        @if($job->description_raw)
            <section class="mb-8 animate-fade-up delay-200">
                <h2 class="text-lg font-bold text-slate-800 mb-3">Deskripsi Pekerjaan</h2>
                <div class="text-sm text-stone-600 leading-relaxed whitespace-pre-line">{{ $job->description_raw }}</div>
            </section>
        @endif

        <div class="mb-8" style="border-top: 1px solid #eae7e3;"></div>

        {{-- Apply CTA --}}
        <section class="mb-8 animate-fade-up delay-300">
            <div class="surface rounded-2xl p-6 md:p-8 text-center  ">
                <h2 class="text-lg font-bold text-slate-800 mb-1.5">Tertarik dengan posisi ini?</h2>
                <p class="text-sm text-stone-500 mb-5">Lamar langsung di situs resmi perusahaan.</p>
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
            class="surface rounded-2xl overflow-hidden  transition-colors"
            :class="{ 'scanning-shimmer': scanning }"
            x-data="cvMatcher()"
        >
            <div class="p-6 md:p-8">
                <h2 class="text-lg font-bold text-slate-800 mb-1">Cek Kecocokan CV</h2>
                <p class="text-sm text-stone-500 mb-6">Upload CV dan lihat seberapa cocok dengan lowongan ini.</p>

                {{-- Upload Area --}}
                <div x-show="!result">
                    <div
                        class="rounded-2xl p-8 text-center transition-all cursor-pointer"
                        style="border: 2px dashed #d5d1cc;"
                        :class="{ 'drag-active': isDragging }"
                        @mouseover="this.style.borderColor='rgba(13,148,136,0.40)'; this.style.background='rgba(13,148,136,0.03)'"
                        @mouseout="!isDragging && (this.style.borderColor='#d5d1cc', this.style.background='')"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        @click="$refs.fileInput.click()"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-stone-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-sm text-stone-500 mb-1">
                            Seret file CV ke sini, atau <span class="text-teal-600 font-medium">pilih file</span>
                        </p>
                        <p class="text-xs text-stone-400">Hanya PDF, maksimal 5 MB</p>
                        <input type="file" accept=".pdf,application/pdf" class="sr-only" x-ref="fileInput" @change="handleFileSelect($event)">
                    </div>

                    {{-- Selected File --}}
                    <div x-show="file" x-cloak class="mt-3 flex items-center gap-3 text-sm text-slate-600 rounded-xl px-4 py-2.5"
                         style="background: #f5f3f0; border: 1px solid #eae7e3;">
                        <span class="truncate font-medium" x-text="file?.name"></span>
                        <span class="text-stone-400 text-xs shrink-0" x-text="file ? (file.size / 1024 / 1024).toFixed(1) + ' MB' : ''"></span>
                        <button @click.stop="file = null" class="text-stone-400 hover:text-red-500 ml-auto shrink-0 p-1 rounded-lg hover:bg-red-50 transition-colors" aria-label="Hapus file">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <p class="mt-3 text-xs text-stone-400 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-teal-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        CV dihapus otomatis setelah dianalisis.
                    </p>

                    <div class="mt-4">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center w-full sm:w-auto min-h-[2.5rem] px-5 py-2 rounded-xl font-semibold text-sm transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-amber-400 active:scale-[0.97]"
                            :class="!file || scanning ? 'cursor-not-allowed text-stone-400' : 'cursor-pointer text-white'"
                            :style="!file || scanning ? 'background: #f0eeeb; border: 1px solid #e0ddd9;' : 'background: rgba(245,158,11,0.85); border: 1px solid rgba(251,191,36,0.40); box-shadow: 0 2px 12px rgba(245,158,11,0.28), inset 0 1px 0 rgba(255,255,255,0.20);'"
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

                    <div x-show="error" x-cloak class="mt-3 text-sm text-red-600 rounded-xl p-3 flex items-start gap-2"
                         style="background: #fef2f2; border: 1px solid #fecaca;" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
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
                                <circle cx="60" cy="60" r="54" fill="none" stroke="#eae7e3" stroke-width="8" />
                                <circle cx="60" cy="60" r="54" fill="none"
                                    :stroke="result?.match_score >= 70 ? '#0d9488' : result?.match_score >= 40 ? '#d97706' : '#ef4444'"
                                    stroke-width="8" stroke-linecap="round"
                                    :stroke-dasharray="339.292"
                                    :stroke-dashoffset="339.292 - (339.292 * (result?.match_score || 0) / 100)"
                                    class="gauge-circle gauge-animate" />
                            </svg>
                            <div class="absolute flex flex-col items-center">
                                <span class="font-[family-name:var(--font-display)] text-3xl font-extrabold" :class="{
                                    'text-teal-600':  result?.match_score >= 70,
                                    'text-amber-600': result?.match_score >= 40 && result?.match_score < 70,
                                    'text-red-500':   result?.match_score < 40
                                }" x-text="(result?.match_score || 0) + '%'"></span>
                                <span class="text-xs text-stone-400 font-medium">kecocokan</span>
                            </div>
                        </div>
                    </div>

                    {{-- Strengths --}}
                    <div>
                        <h3 class="text-sm font-semibold text-emerald-600 mb-2">Kekuatan</h3>
                        <ul class="space-y-1.5">
                            <template x-for="(item, i) in result?.strengths" :key="'s-'+i">
                                <li class="text-sm text-slate-600 flex items-start gap-2 rounded-xl px-3 py-2.5"
                                    style="background: #ecfdf5; border: 1px solid #d1fae5;">
                                    <span class="text-emerald-600 font-semibold shrink-0">+</span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    {{-- Weaknesses --}}
                    <div>
                        <h3 class="text-sm font-semibold text-red-600 mb-2">Kekurangan</h3>
                        <ul class="space-y-1.5">
                            <template x-for="(item, i) in result?.weaknesses" :key="'w-'+i">
                                <li class="text-sm text-slate-600 flex items-start gap-2 rounded-xl px-3 py-2.5"
                                    style="background: #fef2f2; border: 1px solid #fecaca;">
                                    <span class="text-red-600 font-semibold shrink-0">&minus;</span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    {{-- Suggestions --}}
                    <div>
                        <h3 class="text-sm font-semibold text-slate-600 mb-2">Saran Perbaikan</h3>
                        <ul class="space-y-1.5">
                            <template x-for="(item, i) in result?.suggestions" :key="'sg-'+i">
                                <li class="text-sm text-stone-600 flex items-start gap-2 rounded-xl px-3 py-2.5"
                                    style="background: #f5f3f0; border: 1px solid #eae7e3;">
                                    <span class="text-teal-600 font-semibold shrink-0">&rarr;</span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <div class="pt-4" style="border-top: 1px solid #eae7e3;">
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

                        // Synchronous: result comes back immediately
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
            };
        }
    </script>

    @php
        $jobLd = json_encode(['@context'=>'https://schema.org','@type'=>'JobPosting','title'=>$job->title,'description'=>Str::limit($job->description_raw??$job->summary_ai??$job->title,5000),'datePosted'=>$job->created_at->toIso8601String(),'hiringOrganization'=>['@type'=>'Organization','name'=>$job->company],'jobLocation'=>['@type'=>'Place','address'=>['@type'=>'PostalAddress','addressLocality'=>$job->location??'Indonesia','addressCountry'=>'ID']],'employmentType'=>strtoupper(str_replace('-','_',is_object($job->employment_type)?$job->employment_type->value:($job->employment_type??'FULL_TIME'))),'directApply'=>false], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $breadcrumbLd = json_encode(['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>[['@type'=>'ListItem','position'=>1,'name'=>'Beranda','item'=>url('/')],['@type'=>'ListItem','position'=>2,'name'=>'Lowongan','item'=>route('jobs.index')],['@type'=>'ListItem','position'=>3,'name'=>$job->title]]], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    @endphp
    {!! '<script type="application/ld+json">' . $jobLd . '</script>' !!}
    {!! '<script type="application/ld+json">' . $breadcrumbLd . '</script>' !!}

</x-layout>
