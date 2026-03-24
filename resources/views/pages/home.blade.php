<x-layout title="Portal Loker - Temukan Lowongan Kerja Terbaik di Indonesia">

    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-blue-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight animate-fade-up">
                    Temukan Lowongan Kerja Terbaik di Indonesia
                </h1>
                <p class="mt-4 text-lg md:text-xl text-blue-100 animate-fade-up delay-100">
                    Ribuan lowongan kerja dari perusahaan terpercaya, dirangkum oleh AI untuk memudahkan pencarian Anda.
                </p>

                {{-- Search Bar --}}
                <div class="mt-8 max-w-xl mx-auto animate-fade-up delay-200">
                    <x-search-bar :action="route('jobs.index')" />
                </div>

                {{-- Stats --}}
                <div class="mt-10 flex flex-wrap justify-center gap-8 animate-fade-up delay-300">
                    <div class="text-center">
                        <p class="text-3xl md:text-4xl font-bold">{{ number_format($totalJobs) }}</p>
                        <p class="text-sm text-blue-200 mt-1">Lowongan Tersedia</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl md:text-4xl font-bold">100+</p>
                        <p class="text-sm text-blue-200 mt-1">Perusahaan</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl md:text-4xl font-bold">34</p>
                        <p class="text-sm text-blue-200 mt-1">Provinsi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Lowongan Terbaru --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                Lowongan Terbaru
            </h2>
            <x-button variant="ghost" :href="route('jobs.index')">
                Lihat Semua
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </x-button>
        </div>

        @if($recentJobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" data-reveal-stagger>
                @foreach($recentJobs as $job)
                    <x-job-card :job="$job" />
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <p class="text-lg font-medium">Belum ada lowongan tersedia</p>
                <p class="mt-1">Silakan kembali lagi nanti.</p>
            </div>
        @endif

        {{-- CTA --}}
        <div class="mt-10 text-center">
            <x-button :href="route('jobs.index')">
                Lihat Semua Lowongan
            </x-button>
        </div>
    </section>

</x-layout>
