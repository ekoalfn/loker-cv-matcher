@php
    $bulanIndonesia = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ][(int) date('n')];
@endphp

<x-layout
    title="Lowongan Kerja Terbaru {{ $bulanIndonesia }} {{ date('Y') }} - Cari Loker | Lamaraja"
    description="Cari lowongan kerja terbaru di Indonesia dari berbagai sumber. Lamaraja merangkum loker dengan AI dan menyediakan CV Matcher gratis."
>

    {{-- HERO --}}
    <section class="hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                {{-- Left: Heading & Search --}}
                <div class="animate-fade-up">
                    <h1 class="font-[family-name:var(--font-display)] text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.1] tracking-tight">
                        Cari lowongan kerja,<br>
                        <span class="text-emerald-600">lebih cepat</span> dengan AI. <span class="inline-block">✨</span>
                    </h1>
                    <p class="mt-6 text-base md:text-lg text-slate-600 leading-relaxed max-w-xl">
                        Lamaraja mengumpulkan loker dari berbagai sumber dan merangkumnya dengan AI, supaya kamu bisa memahami peluang kerja dalam hitungan detik.
                    </p>

                    {{-- Search Bar --}}
                    <div class="mt-8 animate-fade-up delay-100">
                        <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                            <div class="flex-1 relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input
                                    type="text"
                                    name="keyword"
                                    placeholder="Posisi, skill, atau kata kunci"
                                    class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all outline-none"
                                >
                            </div>
                            <div class="flex-1 relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input
                                    type="text"
                                    name="location"
                                    placeholder="Lokasi"
                                    class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all outline-none"
                                >
                            </div>
                            <button
                                type="submit"
                                class="h-14 px-8 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-600/25 hover:shadow-xl hover:shadow-emerald-600/30 active:scale-[0.98]"
                            >
                                Cari Loker
                            </button>
                        </form>
                    </div>

                    {{-- Job Sources --}}
                    <div class="mt-8 flex flex-wrap items-center gap-4 animate-fade-up delay-200">
                        <span class="text-sm text-slate-500 font-medium">We aggregate jobs from</span>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                            <span class="text-[#0077B5] font-bold text-lg">Linked<span class="bg-[#0077B5] text-white px-1 rounded">in</span></span>
                            <span class="text-[#2164f3] font-bold text-lg">indeed</span>
                            <span class="text-[#0caa41] font-bold text-lg">glassdoor</span>
                            <span class="text-slate-400 text-sm">and more</span>
                        </div>
                    </div>
                </div>

                {{-- Right: AI Summary Card --}}
                <div class="animate-fade-up delay-300 hidden lg:block">
                    <div class="ai-summary-card group relative">
                        <div class="absolute -top-3 left-6 flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-md border border-emerald-100 z-9999">
                            <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 7H7v6h6V7z"/>
                                <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-bold text-emerald-600">AI Job Summary</span>
                        </div>
                        
                        <div class="ai-card-inner bg-white rounded-2xl shadow-xl border border-slate-200 p-6 mt-4 transition-all duration-300">
                            <div class="grid grid-cols-2 gap-4">
                                {{-- Original Job Description --}}
                                <div>
                                    <div class="text-xs font-semibold text-slate-500 mb-3">Original Job Description</div>
                                    <div class="space-y-2">
                                        <div class="h-2 skeleton-shimmer rounded w-full" style="animation-delay: 0s"></div>
                                        <div class="h-2 skeleton-shimmer rounded w-5/6" style="animation-delay: 0.15s"></div>
                                        <div class="h-2 skeleton-shimmer rounded w-full" style="animation-delay: 0.3s"></div>
                                        <div class="h-2 skeleton-shimmer rounded w-4/6" style="animation-delay: 0.45s"></div>
                                        <div class="h-2 skeleton-shimmer rounded w-full" style="animation-delay: 0.6s"></div>
                                        <div class="h-2 skeleton-shimmer rounded w-3/6" style="animation-delay: 0.75s"></div>
                                        <div class="h-2 skeleton-shimmer rounded w-5/6" style="animation-delay: 0.9s"></div>
                                        <div class="h-2 skeleton-shimmer rounded w-full" style="animation-delay: 1.05s"></div>
                                    </div>
                                </div>

                                {{-- AI Summary --}}
                                <div class="relative">
                                    <div class="text-xs font-semibold text-slate-500 mb-3">AI Summary</div>
                                    <div class="space-y-3">
                                        <div class="flex items-start gap-2 ai-summary-item">
                                            <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-xs text-slate-600">Key responsibilities</span>
                                        </div>
                                        <div class="flex items-start gap-2 ai-summary-item">
                                            <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-xs text-slate-600">Required skills</span>
                                        </div>
                                        <div class="flex items-start gap-2 ai-summary-item">
                                            <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-xs text-slate-600">Experience</span>
                                        </div>
                                        <div class="flex items-start gap-2 ai-summary-item">
                                            <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-xs text-slate-600">Nice to have</span>
                                        </div>
                                        <div class="flex items-start gap-2 ai-summary-item">
                                            <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-xs text-slate-600">What you'll get</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bottom CTA --}}
                            <div class="mt-6 pt-4 border-t border-slate-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-emerald-600 font-semibold">Save time. Understand faster. Apply smarter.</span>
                                    <div class="robot-illustration">
                                        <svg class="w-16 h-16" viewBox="0 0 64 64" fill="none">
                                            <circle cx="32" cy="32" r="28" fill="#10B981" opacity="0.1"/>
                                            <rect x="20" y="24" width="24" height="20" rx="4" fill="#10B981"/>
                                            <g class="robot-eye">
                                                <circle cx="26" cy="32" r="3" fill="#000"/>
                                                <circle cx="38" cy="32" r="3" fill="#000"/>
                                            </g>
                                            <path d="M26 38h12" stroke="#000" stroke-width="2" stroke-linecap="round"/>
                                            <circle cx="32" cy="44" r="2" fill="#10B981"/>
                                            <rect x="16" y="28" width="4" height="8" rx="2" fill="#10B981"/>
                                            <rect x="44" y="28" width="4" height="8" rx="2" fill="#10B981"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS BAR --}}
    @if($jobsAddedToday > 0 || $totalJobs > 0)
        <div class="border-b border-stone-200 bg-stone-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center gap-x-6 gap-y-1 text-sm text-stone-500">
                @if($jobsAddedToday > 0)
                    <span><span class="font-semibold text-teal-600">{{ $jobsAddedToday }}</span> loker baru hari ini</span>
                @endif
                <span>Total: <span class="font-semibold text-slate-700">{{ number_format($totalJobs) }}</span> lowongan aktif</span>
            </div>
        </div>
    @endif

    {{-- RECENT JOBS --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="font-[family-name:var(--font-display)] text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">
                    Latest Jobs <span class="text-emerald-600">for You</span>
                </h2>
                <p class="text-sm text-slate-500 mt-1">AI summarized. Personalized. Relevant.</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                View all jobs
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        @if($recentJobs->count() > 0)
            <div class="space-y-4" data-reveal-stagger>
                @foreach($recentJobs->take(8) as $job)
                    <x-job-card :job="$job" />
                @endforeach
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-600/25 hover:shadow-xl hover:shadow-emerald-600/30 active:scale-[0.98]">
                    View All Jobs
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 text-center py-16 px-6">
                <p class="font-[family-name:var(--font-display)] text-lg font-bold text-slate-700">No jobs available yet</p>
                <p class="mt-1.5 text-sm text-slate-400">New jobs will appear soon!</p>
            </div>
        @endif
    </section>

    {{-- WHY LAMARAJA --}}
    <section class="bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center font-[family-name:var(--font-display)] text-3xl md:text-4xl font-bold text-slate-900 mb-12">
                Why <span class="text-emerald-600">Lamaraja</span>?
            </h2>

            <div class="grid md:grid-cols-2 gap-8 mb-16">
                {{-- AI Job Summarization --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-emerald-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 rounded-full bg-emerald-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">AI Job Summarization</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Our AI reads and summarizes job descriptions so you can quickly understand the role, requirements, and benefits.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- CV Matcher --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-emerald-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 rounded-full bg-emerald-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">CV Matcher (AI-Powered)</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Upload your CV and our AI analyzes your skills, experience and achievements to match you with the most relevant jobs.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Get Matched Section --}}
            <div class="bg-white rounded-2xl p-8 md:p-12 shadow-lg border border-emerald-100">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    {{-- Left: Illustration --}}
                    <div class="flex justify-center">
                        <div class="relative">
                            <div class="bg-slate-50 rounded-2xl p-8 border-2 border-slate-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-full bg-slate-300"></div>
                                    <div class="flex-1">
                                        <div class="h-3 bg-slate-300 rounded w-20 mb-2"></div>
                                        <div class="h-2 bg-slate-200 rounded w-32"></div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-2 bg-slate-200 rounded"></div>
                                    <div class="h-2 bg-slate-200 rounded w-5/6"></div>
                                    <div class="h-2 bg-slate-200 rounded w-4/6"></div>
                                </div>
                            </div>
                            
                            <div class="absolute -right-4 top-1/2 -translate-y-1/2">
                                <svg class="w-12 h-12 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </div>

                            <div class="absolute -bottom-6 -right-6 w-24 h-24 rounded-full bg-emerald-600 flex items-center justify-center shadow-xl">
                                <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>

                            <div class="absolute -top-6 -right-8 bg-white rounded-xl p-3 shadow-lg border border-emerald-100">
                                <div class="text-xs font-semibold text-emerald-600 mb-1">Get better matches</div>
                                <div class="text-xs text-slate-500 mb-2">with your CV</div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="h-1.5 bg-slate-200 rounded w-16"></div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="h-1.5 bg-slate-200 rounded w-12"></div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="h-1.5 bg-slate-200 rounded w-14"></div>
                                    </div>
                                </div>
                                <div class="mt-2 w-10 h-10 rounded-full bg-emerald-600 ml-auto"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Content --}}
                    <div>
                        <h3 class="font-[family-name:var(--font-display)] text-2xl md:text-3xl font-bold text-slate-900 mb-4">
                            Get <span class="text-emerald-600">matched</span>. Get noticed.
                        </h3>
                        <p class="text-slate-600 leading-relaxed mb-6">
                            Upload your CV and let our AI do the heavy lifting. We'll match you with the best jobs and show you how well you fit.
                        </p>
                        <button class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-600/25 hover:shadow-xl hover:shadow-emerald-600/30 active:scale-[0.98]">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Upload Your CV
                        </button>
                        <div class="flex items-center gap-2 mt-4 text-sm text-slate-500">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Your data is secure and private.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layout>
