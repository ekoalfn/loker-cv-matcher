<x-layout title="Portal Loker - Temukan Lowongan Kerja Terbaik di Indonesia">

    {{-- Hero Section — Liquid Glass --}}
    <section class="relative overflow-hidden">
        {{-- Background Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-teal-600 via-teal-700 to-cyan-800"></div>

        {{-- Decorative Blur Blobs --}}
        <div class="blob w-80 h-80 bg-teal-400/20 top-[-10%] left-[-5%] animate-float"></div>
        <div class="blob w-96 h-96 bg-cyan-400/15 top-[20%] right-[-10%] animate-float-slow"></div>
        <div class="blob w-64 h-64 bg-teal-300/20 bottom-[-15%] left-[30%] animate-float" style="animation-delay: -3s;"></div>
        <div class="blob w-48 h-48 bg-amber-400/10 top-[10%] left-[60%] animate-glow-pulse"></div>

        {{-- Mesh overlay --}}
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(255,255,255,0.1)_0%,_transparent_60%)]"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight animate-fade-up tracking-tight">
                    <span class="text-white">Temukan</span>
                    <span class="block mt-2 bg-gradient-to-r from-white via-teal-100 to-cyan-200 bg-clip-text text-transparent">Lowongan Kerja Terbaik</span>
                    <span class="text-white">di Indonesia</span>
                </h1>
                <p class="mt-6 text-lg md:text-xl text-teal-100/90 animate-fade-up delay-100 font-medium leading-relaxed max-w-2xl mx-auto">
                    Ribuan lowongan kerja dari perusahaan terpercaya, dirangkum oleh AI untuk memudahkan pencarian Anda.
                </p>

                {{-- Search Bar --}}
                <div class="mt-10 max-w-xl mx-auto animate-fade-up delay-200">
                    <x-search-bar :action="route('jobs.index')" />
                </div>

                {{-- Stats — Glass Cards --}}
                <div class="mt-12 flex flex-wrap justify-center gap-4 md:gap-6 animate-fade-up delay-300">
                    <div class="glass-subtle rounded-2xl px-6 py-4 text-center min-w-[120px]">
                        <p class="text-3xl md:text-4xl font-extrabold text-white">{{ number_format($totalJobs) }}</p>
                        <p class="text-sm text-teal-200/80 mt-1 font-medium">Lowongan</p>
                    </div>
                    <div class="glass-subtle rounded-2xl px-6 py-4 text-center min-w-[120px]">
                        <p class="text-3xl md:text-4xl font-extrabold text-white">100+</p>
                        <p class="text-sm text-teal-200/80 mt-1 font-medium">Perusahaan</p>
                    </div>
                    <div class="glass-subtle rounded-2xl px-6 py-4 text-center min-w-[120px]">
                        <p class="text-3xl md:text-4xl font-extrabold text-white">34</p>
                        <p class="text-sm text-teal-200/80 mt-1 font-medium">Provinsi</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom gradient fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-slate-50 via-slate-50/50 to-transparent"></div>
    </section>

    {{-- Lowongan Terbaru --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-teal-700 tracking-tight">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-reveal-stagger>
                @foreach($recentJobs as $job)
                    <x-job-card :job="$job" />
                @endforeach
            </div>
        @else
            <div class="glass rounded-3xl text-center py-16 px-6">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-teal-100 to-cyan-100 flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="text-lg font-bold text-slate-700">Belum ada lowongan tersedia</p>
                <p class="mt-2 text-slate-500">Silakan kembali lagi nanti.</p>
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
