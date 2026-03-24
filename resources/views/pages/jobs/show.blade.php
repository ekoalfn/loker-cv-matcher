<x-layout :title="$job->title . ' di ' . $job->company . ' - Portal Loker'">

    @php
        $employmentLabels = [
            'full-time' => 'Full Time',
            'part-time' => 'Part Time',
            'contract' => 'Kontrak',
            'freelance' => 'Freelance',
            'internship' => 'Magang',
        ];
    @endphp

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-1">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Beranda</a></li>
                <li class="text-gray-300">/</li>
                <li><a href="{{ route('jobs.index') }}" class="hover:text-blue-600 transition-colors">Lowongan</a></li>
                <li class="text-gray-300">/</li>
                <li class="text-gray-700 font-medium truncate max-w-[200px] sm:max-w-none" aria-current="page">{{ $job->title }}</li>
            </ol>
        </nav>

        {{-- Job Header --}}
        <header class="mb-8 animate-fade-up">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                {{ $job->title }}
            </h1>

            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-gray-600">
                {{-- Company --}}
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="font-medium text-gray-700">{{ $job->company }}</span>
                </div>

                {{-- Location --}}
                @if($job->location)
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

            {{-- Salary Range --}}
            @if($job->salary_min || $job->salary_max)
                <div class="mt-3 flex items-center gap-1.5 text-lg font-semibold text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @if($job->salary_min && $job->salary_max)
                        Rp {{ number_format($job->salary_min, 0, ',', '.') }} – Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                    @elseif($job->salary_min)
                        Mulai Rp {{ number_format($job->salary_min, 0, ',', '.') }}
                    @else
                        Hingga Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                    @endif
                </div>
            @endif

            {{-- Tags --}}
            @if(!empty($job->tags))
                <div class="mt-4 flex flex-wrap gap-1.5">
                    @foreach($job->tags as $tag)
                        <x-badge :text="$tag" color="purple" />
                    @endforeach
                </div>
            @endif
        </header>

        {{-- AI Summary --}}
        @if($job->summary_ai)
            <section class="mb-8 bg-blue-50 border border-blue-100 rounded-xl p-5 animate-fade-up delay-100">
                <div class="flex items-start gap-3">
                    <div class="shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-blue-800 mb-1">Ringkasan AI</h2>
                        <p class="text-sm text-blue-700 leading-relaxed">{{ $job->summary_ai }}</p>
                    </div>
                </div>
            </section>
        @endif

        {{-- Description --}}
        @if($job->description_raw)
            <section class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Pekerjaan</h2>
                <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $job->description_raw }}</div>
            </section>
        @endif

        {{-- CTA: Apply --}}
        <section class="mb-10 bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
            <h2 class="text-lg font-bold text-gray-900 mb-2">Tertarik dengan posisi ini?</h2>
            <p class="text-sm text-gray-600 mb-4">Lamar langsung di situs resmi perusahaan.</p>
            <x-button :href="route('jobs.apply', $job)">
                Lamar di Situs Resmi
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </x-button>
        </section>

        {{-- CV Matcher --}}
        <section
            class="bg-white border border-gray-200 rounded-xl p-6 transition-all duration-300"
            :class="{ 'scanning-shimmer': scanning }"
            x-data="cvMatcher()"
        >
            <h2 class="text-xl font-bold text-gray-900 mb-1">Cek Kecocokan CV</h2>
            <p class="text-sm text-gray-600 mb-5">
                Upload CV dan cek seberapa cocok dengan lowongan ini.
            </p>

            {{-- Upload Area --}}
            <div x-show="!result">
                <div
                    class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-all duration-300 cursor-pointer"
                    :class="{ 'border-blue-400 bg-blue-50 drag-active': isDragging }"
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop($event)"
                    @click="$refs.fileInput.click()"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>

                    <p class="text-sm text-gray-600 mb-2">
                        Seret file CV ke sini, atau <span class="text-blue-600 font-medium">pilih file</span>
                    </p>
                    <p class="text-xs text-gray-500">Hanya PDF, maksimal 5 MB</p>

                    <input
                        type="file"
                        accept=".pdf,application/pdf"
                        class="sr-only"
                        x-ref="fileInput"
                        @change="handleFileSelect($event)"
                    >
                </div>

                {{-- Selected File --}}
                <div x-show="file" x-cloak class="mt-3 flex items-center gap-2 text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="truncate" x-text="file?.name"></span>
                    <span class="text-gray-400 text-xs shrink-0" x-text="file ? (file.size / 1024 / 1024).toFixed(1) + ' MB' : ''"></span>
                    <button @click.stop="file = null" class="text-gray-400 hover:text-red-500 ml-auto shrink-0 p-1 rounded transition-colors" aria-label="Hapus file">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Privacy Notice --}}
                <p class="mt-3 text-xs text-gray-500 flex items-start gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0 mt-0.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    CV dihapus otomatis setelah dianalisis. Data tidak disimpan di server.
                </p>

                {{-- Submit --}}
                <div class="mt-4">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center w-full sm:w-auto min-h-[2.75rem] px-6 py-2.5 rounded-lg font-semibold text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 btn-press"
                        :class="!file || scanning ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700 cursor-pointer'"
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
                    class="mt-3 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3 flex items-start gap-2"
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
                class="space-y-6"
                aria-live="polite"
                aria-atomic="true"
            >

                {{-- Match Score Gauge --}}
                <div class="text-center animate-fade-up">
                    <div class="relative inline-flex items-center justify-center w-36 h-36">
                        <svg class="w-36 h-36 -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#f3f4f6" stroke-width="8" />
                            <circle
                                cx="60" cy="60" r="54" fill="none"
                                :stroke="result?.match_score >= 70 ? '#10b981' : result?.match_score >= 40 ? '#f59e0b' : '#ef4444'"
                                stroke-width="8"
                                stroke-linecap="round"
                                :stroke-dasharray="339.292"
                                :stroke-dashoffset="339.292 - (339.292 * (result?.match_score || 0) / 100)"
                                class="gauge-circle gauge-animate"
                            />
                        </svg>
                        <div class="absolute flex flex-col items-center">
                            <span class="text-3xl font-bold" :class="{
                                'text-emerald-600': result?.match_score >= 70,
                                'text-amber-500': result?.match_score >= 40 && result?.match_score < 70,
                                'text-red-500': result?.match_score < 40
                            }" x-text="(result?.match_score || 0) + '%'"></span>
                            <span class="text-xs text-gray-500 mt-0.5">kecocokan</span>
                        </div>
                    </div>
                </div>

                {{-- Strengths --}}
                <div class="animate-fade-up delay-200">
                    <h3 class="text-sm font-semibold text-emerald-700 mb-2.5 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Kekuatan
                    </h3>
                    <ul class="space-y-2">
                        <template x-for="(item, i) in result?.strengths" :key="'s-'+i">
                            <li class="text-sm text-gray-700 flex items-start gap-2 bg-emerald-50/50 rounded-lg px-3 py-2">
                                <span class="text-emerald-500 font-bold mt-px shrink-0">+</span>
                                <span x-text="item"></span>
                            </li>
                        </template>
                    </ul>
                </div>

                {{-- Weaknesses --}}
                <div class="animate-fade-up delay-300">
                    <h3 class="text-sm font-semibold text-red-700 mb-2.5 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Kekurangan
                    </h3>
                    <ul class="space-y-2">
                        <template x-for="(item, i) in result?.weaknesses" :key="'w-'+i">
                            <li class="text-sm text-gray-700 flex items-start gap-2 bg-red-50/50 rounded-lg px-3 py-2">
                                <span class="text-red-500 font-bold mt-px shrink-0">−</span>
                                <span x-text="item"></span>
                            </li>
                        </template>
                    </ul>
                </div>

                {{-- Suggestions --}}
                <div class="animate-fade-up delay-400">
                    <h3 class="text-sm font-semibold text-blue-700 mb-2.5 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        Saran Perbaikan
                    </h3>
                    <ul class="space-y-2">
                        <template x-for="(item, i) in result?.suggestions" :key="'sg-'+i">
                            <li class="text-sm text-gray-700 flex items-start gap-2 bg-blue-50/50 rounded-lg px-3 py-2">
                                <span class="text-blue-500 font-bold mt-px shrink-0">→</span>
                                <span x-text="item"></span>
                            </li>
                        </template>
                    </ul>
                </div>

                {{-- Reset --}}
                <div class="pt-4 border-t border-gray-200">
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
