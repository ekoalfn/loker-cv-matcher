<x-layout title="Portal Loker - Temukan Lowongan Kerja Terbaik di Indonesia">

    {{-- Hero Section — Gradient Mesh + Grain --}}
    <section class="relative overflow-hidden texture-grain-strong">
        {{-- Animated gradient mesh background --}}
        <div class="absolute inset-0 bg-[length:200%_200%] animate-mesh"
             style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 25%, #0e7490 50%, #0d9488 75%, #115e59 100%);">
        </div>

        {{-- Radial light source overlays --}}
        <div class="absolute inset-0"
             style="background: radial-gradient(ellipse 80% 60% at 20% 10%, rgba(153, 246, 228, 0.15) 0%, transparent 60%),
                                 radial-gradient(ellipse 60% 50% at 80% 80%, rgba(8, 145, 178, 0.12) 0%, transparent 50%);">
        </div>

        {{-- Decorative Blur Blobs --}}
        <div class="blob w-[500px] h-[500px] bg-teal-400/15 -top-[15%] -left-[10%] animate-float"></div>
        <div class="blob w-[600px] h-[600px] bg-cyan-400/10 top-[30%] -right-[15%] animate-float-slow"></div>
        <div class="blob w-[300px] h-[300px] bg-teal-300/15 -bottom-[10%] left-[25%] animate-float" style="animation-delay: -4s;"></div>
        <div class="blob w-[200px] h-[200px] bg-amber-400/8 top-[5%] left-[65%] animate-glow-pulse"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-36 lg:py-40">
            <div class="max-w-3xl mx-auto text-center">

                {{-- Eyebrow badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-sm mb-8 animate-fade-up">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[0.8125rem] font-semibold text-teal-100 tracking-wide">{{ number_format($totalJobs) }}+ lowongan aktif</span>
                </div>

                {{-- Headline --}}
                <h1 class="font-[family-name:var(--font-display)] text-4xl md:text-5xl lg:text-6xl font-extrabold leading-[1.08] tracking-tight animate-fade-up delay-100">
                    <span class="text-white">Temukan Karier</span>
                    <br class="hidden sm:block">
                    <span class="bg-gradient-to-r from-teal-100 via-white to-cyan-200 bg-clip-text text-transparent">Impian Anda</span>
                </h1>

                {{-- Subheadline --}}
                <p class="mt-6 text-lg md:text-xl text-teal-100/85 animate-fade-up delay-200 font-medium leading-relaxed max-w-xl mx-auto">
                    Ribuan lowongan dari perusahaan terpercaya, dirangkum AI agar Anda bisa fokus melamar.
                </p>

                {{-- Search Bar — elevated, prominent --}}
                <div class="mt-10 md:mt-12 max-w-2xl mx-auto animate-scale-in delay-300">
                    <x-search-bar :action="route('jobs.index')" />
                </div>

                {{-- Stats row — clean grid with dividers --}}
                <div class="mt-14 md:mt-16 grid grid-cols-3 max-w-md mx-auto animate-fade-up delay-400">
                    <div class="text-center px-4 border-r border-white/15">
                        <p class="font-[family-name:var(--font-display)] text-3xl md:text-4xl font-extrabold text-white tracking-tighter leading-none animate-count-up">{{ number_format($totalJobs) }}</p>
                        <p class="stat-label text-teal-200/70 mt-2">Lowongan</p>
                    </div>
                    <div class="text-center px-4 border-r border-white/15">
                        <p class="font-[family-name:var(--font-display)] text-3xl md:text-4xl font-extrabold text-white tracking-tighter leading-none animate-count-up" style="animation-delay: 150ms">100+</p>
                        <p class="stat-label text-teal-200/70 mt-2">Perusahaan</p>
                    </div>
                    <div class="text-center px-4">
                        <p class="font-[family-name:var(--font-display)] text-3xl md:text-4xl font-extrabold text-white tracking-tighter leading-none animate-count-up" style="animation-delay: 300ms">34</p>
                        <p class="stat-label text-teal-200/70 mt-2">Provinsi</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom transition curve --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-[40px] md:h-[60px]" preserveAspectRatio="none">
                <path d="M0 60V30C240 5 480 0 720 10C960 20 1200 45 1440 30V60H0Z" fill="rgb(248, 250, 252)" />
            </svg>
        </div>
    </section>

    {{-- Lowongan Terbaru --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="font-[family-name:var(--font-display)] text-2xl md:text-3xl font-bold text-teal-700 tracking-tight">
                    Lowongan Terbaru
                </h2>
                <p class="mt-2 text-slate-500 text-sm font-medium">Diperbaharui secara real-time dari berbagai sumber</p>
            </div>
            <x-button variant="ghost" :href="route('jobs.index')">
                Lihat Semua
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </x-button>
        </div>

        @if($recentJobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6" data-reveal-stagger>
                @foreach($recentJobs as $job)
                    <x-job-card :job="$job" />
                @endforeach
            </div>
        @else
            <div class="glass-prominent rounded-3xl text-center py-16 px-6">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-teal-100 to-cyan-100 flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="font-[family-name:var(--font-display)] text-lg font-bold text-slate-700">Belum ada lowongan tersedia</p>
                <p class="mt-2 text-slate-500 text-sm">Silakan kembali lagi nanti.</p>
            </div>
        @endif

        {{-- CTA --}}
        <div class="mt-12 text-center">
            <x-button :href="route('jobs.index')">
                Lihat Semua Lowongan
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </x-button>
        </div>
    </section>

</x-layout>
