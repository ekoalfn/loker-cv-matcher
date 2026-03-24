<x-layout :title="$job->title . ' di ' . $job->company . ' - Portal Loker'">

    @php
        $employmentLabels = [
            'full-time' => 'Full Time',
            'part-time' => 'Part Time',
            'contract' => 'Kontrak',
            'freelance' => 'Freelance',
            'internship' => 'Magang',
        ];

        $avatarColors = [
            'from-indigo-500 to-violet-500',
            'from-blue-500 to-cyan-500',
            'from-emerald-500 to-teal-500',
            'from-amber-500 to-orange-500',
            'from-rose-500 to-pink-500',
            'from-violet-500 to-purple-500',
        ];
        $colorIndex = crc32($job->company ?? '') % count($avatarColors);
        $avatarGradient = $avatarColors[$colorIndex];
        $companyInitial = strtoupper(mb_substr($job->company ?? '?', 0, 1));
    @endphp

    {{-- Subtle background decoration --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div class="blob w-96 h-96 bg-indigo-200/15 top-[5%] right-[-10%] animate-float-slow"></div>
        <div class="blob w-72 h-72 bg-violet-200/10 bottom-[10%] left-[-5%] animate-float" style="animation-delay: -4s;"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">

        {{-- Breadcrumb --}}
        <nav class="mb-8 text-sm text-slate-400 font-medium" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors duration-200">Beranda</a></li>
                <li class="text-slate-300">/</li>
                <li><a href="{{ route('jobs.index') }}" class="hover:text-indigo-600 transition-colors duration-200">Lowongan</a></li>
                <li class="text-slate-300">/</li>
                <li class="text-slate-600 font-semibold truncate max-w-[200px] sm:max-w-none" aria-current="page">{{ $job->title }}</li>
            </ol>
        </nav>

        {{-- Job Header — Glass Card --}}
        <header class="glass rounded-3xl p-6 md:p-8 mb-8 animate-fade-up">
            <div class="flex items-start gap-4">
                {{-- Company Avatar --}}
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $avatarGradient }} flex items-center justify-center shadow-lg shadow-indigo-500/15 shrink-0">
                    <span class="text-white font-bold text-xl">{{ $companyInitial }}</span>
                </div>

                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight tracking-tight">
                        {{ $job->title }}
                    </h1>

                    <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                        {{-- Company --}}
                        <div class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="font-semibold text-slate-700">{{ $job->company }}</span>
                        </div>

                        {{-- Location --}}
                        @if($job->location)
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $job->location }}</span>
                            </div>
                        @endif

                        {{-- Employment Type --}}
                        @if($job->employment_type)
                            <x-badge :text="$employmentLabels[$job->employment_type->value ?? $job->employment_type] ?? $job->employment_type" color="blue" />
                        @endif
                    </div>
                </div>
            </div>

            {{-- Salary Range --}}
            @if($job->salary_min || $job->salary_max)
                <div class="mt-5 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-50/80 to-teal-50/80 border border-emerald-200/30 backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-lg font-bold text-emerald-700">
                        @if($job->salary_min && $job->salary_max)
                            Rp {{ number_format($job->salary_min, 0, ',', '.') }} – Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                        @elseif($job->salary_min)
                            Mulai Rp {{ number_format($job->salary_min, 0, ',', '.') }}
                        @else
                            Hingga Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                        @endif
                    </span>
                </div>
            @endif

            {{-- Tags --}}
            @if(!empty($job->tags))
                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach($job->tags as $tag)
                        <x-badge :text="$tag" color="purple" />
                    @endforeach
                </div>
            @endif
        </header>

        {{-- AI Summary — Glass with Gradient Accent --}}
        @if($job->summary_ai)
            <section class="mb-8 relative animate-fade-up delay-100">
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-indigo-500/5 to-violet-500/5"></div>
                <div class="relative glass rounded-2xl p-6 border-l-4 border-l-indigo-500">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 w-9 h-9 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center shadow-md shadow-indigo-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-gradient mb-1.5">Ringkasan AI</h2>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $job->summary_ai }}</p>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Description --}}
        @if($job->description_raw)
            <section class="mb-8 animate-fade-up delay-200">
                <h2 class="text-xl font-extrabold text-slate-900 mb-4 tracking-tight">Deskripsi Pekerjaan</h2>
                <div class="glass rounded-2xl p-6">
                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $job->description_raw }}</div>
                </div>
            </section>
        @endif

        {{-- CTA: Apply — Glass with Gradient --}}
        <section class="mb-10 relative animate-fade-up delay-300">
            <div class="absolute inset-0 rounded-3xl bg-gradient-to-r from-indigo-500/5 via-violet-500/5 to-purple-500/5"></div>
            <div class="relative glass-strong rounded-3xl p-8 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center shadow-lg shadow-indigo-500/25 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </div>
                <h2 class="text-xl font-extrabold text-slate-900 mb-2">Tertarik dengan posisi ini?</h2>
                <p class="text-sm text-slate-500 mb-6 font-medium">Lamar langsung di situs resmi perusahaan.</p>
                <x-button :href="route('jobs.apply', $job)">
                    Lamar di Situs Resmi
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </x-button>
            </div>
        </section>

        {{-- CV Matcher — Glass --}}
        <section
            class="glass-strong rounded-3xl p-6 md:p-8 transition-all duration-300"
            :class="{ 'scanning-shimmer': scanning }"
            x-data="cvMatcher()"
        >
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center shadow-md shadow-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Cek Kecocokan CV</h2>
                    <p class="text-sm text-slate-500 font-medium">Upload CV dan cek seberapa cocok dengan lowongan ini.</p>
                </div>
            </div>

            {{-- Upload Area --}}
            <div x-show="!result" class="mt-6">
                <div
                    class="border-2 border-dashed border-slate-200/60 rounded-2xl p-10 text-center transition-all duration-300 cursor-pointer hover:border-indigo-300/50 hover:bg-indigo-50/20"
                    :class="{ 'border-indigo-400 bg-indigo-50/40 drag-active': isDragging }"
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop($event)"
                    @click="$refs.fileInput.click()"
                >
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-indigo-100 to-violet-100 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>

                    <p class="text-sm text-slate-600 mb-2 font-medium">
                        Seret file CV ke sini, atau <span class="text-indigo-600 font-semibold">pilih file</span>
                    </p>
                    <p class="text-xs text-slate-400 font-medium">Hanya PDF, maksimal 5 MB</p>

                    <input
                        type="file"
                        accept=".pdf,application/pdf"
                        class="sr-only"
                        x-ref="fileInput"
                        @change="handleFileSelect($event)"
                    >
                </div>

                {{-- Selected File --}}
                <div x-show="file" x-cloak class="mt-4 flex items-center gap-3 text-sm text-slate-700 glass-light rounded-xl px-4 py-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="truncate font-medium" x-text="file?.name"></span>
                    <span class="text-slate-400 text-xs shrink-0 font-medium" x-text="file ? (file.size / 1024 / 1024).toFixed(1) + ' MB' : ''"></span>
                    <button @click.stop="file = null" class="text-slate-400 hover:text-red-500 ml-auto shrink-0 p-1.5 rounded-lg hover:bg-red-50/50 transition-all duration-200" aria-label="Hapus file">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Privacy Notice --}}
                <p class="mt-4 text-xs text-slate-400 flex items-start gap-2 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    CV dihapus otomatis setelah dianalisis. Data tidak disimpan di server.
                </p>

                {{-- Submit --}}
                <div class="mt-5">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center w-full sm:w-auto min-h-[2.75rem] px-6 py-2.5 rounded-xl font-semibold text-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :class="!file || scanning ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'btn-gradient text-white cursor-pointer'"
                        :disabled="!file || scanning"
                        @click="uploadAndScan()"
                    >
                        <span x-show="!scanning">Analisis CV Saya</span>
                        <span x-show="scanning" x-cloak class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Menganalisis CV...
                        </span>
                    </button>
                </div>

                {{-- Error --}}
                <div x-show="error" x-cloak
                    x-transition:enter="animate-shake"
                    class="mt-4 text-sm text-red-600 glass rounded-xl p-4 flex items-start gap-2 border border-red-200/30 bg-red-50/50"
                    role="alert"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span x-text="error"></span>
                </div>
            </div>

            {{-- Results --}}
            <div x-show="result" x-cloak
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="space-y-6 mt-6"
                aria-live="polite"
                aria-atomic="true"
            >

                {{-- Match Score Gauge --}}
                <div class="text-center animate-fade-up">
                    <div class="relative inline-flex items-center justify-center w-40 h-40">
                        <svg class="w-40 h-40 -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="54" fill="none" stroke="rgba(241, 245, 249, 0.8)" stroke-width="8" />
                            <circle
                                cx="60" cy="60" r="54" fill="none"
                                :stroke="result?.match_score >= 70 ? 'url(#gauge-green)' : result?.match_score >= 40 ? 'url(#gauge-amber)' : 'url(#gauge-red)'"
                                stroke-width="8"
                                stroke-linecap="round"
                                :stroke-dasharray="339.292"
                                :stroke-dashoffset="339.292 - (339.292 * (result?.match_score || 0) / 100)"
                                class="gauge-circle gauge-animate"
                            />
                            <defs>
                                <linearGradient id="gauge-green" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#10b981" />
                                    <stop offset="100%" stop-color="#06b6d4" />
                                </linearGradient>
                                <linearGradient id="gauge-amber" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#f59e0b" />
                                    <stop offset="100%" stop-color="#f97316" />
                                </linearGradient>
                                <linearGradient id="gauge-red" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#ef4444" />
                                    <stop offset="100%" stop-color="#f43f5e" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute flex flex-col items-center">
                            <span class="text-4xl font-extrabold" :class="{
                                'text-emerald-600': result?.match_score >= 70,
                                'text-amber-500': result?.match_score >= 40 && result?.match_score < 70,
                                'text-red-500': result?.match_score < 40
                            }" x-text="(result?.match_score || 0) + '%'"></span>
                            <span class="text-xs text-slate-400 mt-1 font-semibold uppercase tracking-wider">kecocokan</span>
                        </div>
                    </div>
                </div>

                {{-- Strengths --}}
                <div class="animate-fade-up delay-200">
                    <div class="glass rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-emerald-700 mb-3 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            Kekuatan
                        </h3>
                        <ul class="space-y-2">
                            <template x-for="(item, i) in result?.strengths" :key="'s-'+i">
                                <li class="text-sm text-slate-700 flex items-start gap-2.5 bg-emerald-50/40 rounded-xl px-4 py-3 border border-emerald-100/40">
                                    <span class="text-emerald-500 font-bold mt-px shrink-0">+</span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                {{-- Weaknesses --}}
                <div class="animate-fade-up delay-300">
                    <div class="glass rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-red-700 mb-3 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-red-500 to-rose-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            Kekurangan
                        </h3>
                        <ul class="space-y-2">
                            <template x-for="(item, i) in result?.weaknesses" :key="'w-'+i">
                                <li class="text-sm text-slate-700 flex items-start gap-2.5 bg-red-50/40 rounded-xl px-4 py-3 border border-red-100/40">
                                    <span class="text-red-500 font-bold mt-px shrink-0">-</span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                {{-- Suggestions --}}
                <div class="animate-fade-up delay-400">
                    <div class="glass rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-indigo-700 mb-3 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            Saran Perbaikan
                        </h3>
                        <ul class="space-y-2">
                            <template x-for="(item, i) in result?.suggestions" :key="'sg-'+i">
                                <li class="text-sm text-slate-700 flex items-start gap-2.5 bg-indigo-50/40 rounded-xl px-4 py-3 border border-indigo-100/40">
                                    <span class="text-indigo-500 font-bold mt-px shrink-0">&rarr;</span>
                                    <span x-text="item"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                {{-- Reset --}}
                <div class="pt-5 border-t border-slate-100/60">
                    <x-button variant="secondary" type="button" @click="resetMatcher()">
                        Analisis CV Lainnya
                    </x-button>
                </div>
            </div>
        </section>

    </div>

    <script>
        function cvMatcher() {
            return {
                file: null,
                scanning: false,
                result: null,
                error: null,
                isDragging: false,
                pollInterval: null,
                pollCount: 0,
                maxPolls: 60,

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
                    if (file.type !== 'application/pdf') {
                        this.error = 'Hanya file PDF yang diperbolehkan.';
                        return;
                    }
                    if (file.size > 5 * 1024 * 1024) {
                        this.error = 'Ukuran file maksimal 5 MB.';
                        return;
                    }
                    this.file = file;
                },

                async uploadAndScan() {
                    if (!this.file) return;

                    this.scanning = true;
                    this.error = null;
                    this.pollCount = 0;

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

                        if (!response.ok) {
                            const data = await response.json();
                            throw new Error(data.message || 'Gagal mengupload CV.');
                        }

                        const data = await response.json();
                        this.pollStatus(data.scan_id);
                    } catch (err) {
                        this.error = err.message || 'Terjadi kesalahan. Silakan coba lagi.';
                        this.scanning = false;
                    }
                },

                pollStatus(scanId) {
                    this.pollInterval = setInterval(async () => {
                        this.pollCount++;

                        if (this.pollCount >= this.maxPolls) {
                            clearInterval(this.pollInterval);
                            this.error = 'Analisis memakan waktu terlalu lama. Silakan coba lagi.';
                            this.scanning = false;
                            return;
                        }

                        try {
                            const response = await fetch(`/cv-scan/${scanId}/status`, {
                                headers: { 'Accept': 'application/json' },
                            });

                            if (!response.ok) throw new Error('Gagal memeriksa status.');

                            const data = await response.json();

                            if (data.status === 'completed') {
                                clearInterval(this.pollInterval);
                                this.result = data.result;
                                this.scanning = false;
                            } else if (data.status === 'failed') {
                                clearInterval(this.pollInterval);
                                this.error = 'Analisis gagal. Silakan coba lagi dengan file PDF yang berbeda.';
                                this.scanning = false;
                            }
                        } catch (err) {
                            clearInterval(this.pollInterval);
                            this.error = err.message || 'Terjadi kesalahan.';
                            this.scanning = false;
                        }
                    }, 2000);
                },

                resetMatcher() {
                    this.file = null;
                    this.result = null;
                    this.error = null;
                    this.scanning = false;
                    this.pollCount = 0;
                    if (this.pollInterval) clearInterval(this.pollInterval);
                },
            };
        }
    </script>

</x-layout>
