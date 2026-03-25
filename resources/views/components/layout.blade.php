<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'Portal Loker - Temukan lowongan kerja terbaik di Indonesia dengan bantuan AI' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d9488">
    <title>{{ $title ?? 'Portal Loker - Lowongan Kerja Indonesia' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 via-slate-100/30 to-white text-slate-800 antialiased">

    {{-- Skip to Content (A11y) --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:bg-teal-600 focus:text-white focus:px-4 focus:py-2 focus:rounded-xl focus:text-sm focus:font-semibold focus:shadow-lg focus:shadow-teal-500/25">
        Langsung ke konten utama
    </a>

    {{-- Header — Liquid Glass with scroll awareness --}}
    <header
        class="glass-header sticky top-0 z-50 transition-shadow duration-300"
        x-data="{ mobileMenuOpen: false, scrolled: false }"
        @scroll.window="scrolled = (window.scrollY > 20)"
        :class="{ 'shadow-md': scrolled }"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-[4.25rem]">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-extrabold text-xl transition-all duration-300 hover:scale-[1.02] group interactive-focus rounded-lg -ml-1 pl-1">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-600 to-cyan-600 flex items-center justify-center shadow-md shadow-teal-500/20 group-hover:shadow-teal-500/30 group-hover:scale-105 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-gradient font-[family-name:var(--font-display)]">Portal Loker</span>
                </a>

                {{-- Desktop Nav --}}
                <nav class="hidden md:flex items-center gap-1" aria-label="Navigasi utama">
                    <a href="{{ route('home') }}" class="nav-pill px-4 py-2 rounded-xl text-sm font-semibold interactive-focus {{ request()->routeIs('home') ? 'active text-teal-600' : 'text-slate-600 hover:text-teal-600' }}">
                        Beranda
                    </a>
                    <a href="{{ route('jobs.index') }}" class="nav-pill px-4 py-2 rounded-xl text-sm font-semibold interactive-focus {{ request()->routeIs('jobs.*') ? 'active text-teal-600' : 'text-slate-600 hover:text-teal-600' }}">
                        Cari Loker
                    </a>

                    {{-- CTA in nav --}}
                    <div class="ml-3 pl-3 border-l border-slate-200/60">
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold bg-teal-50/80 text-teal-700 hover:bg-teal-100/80 transition-all duration-200 interactive-focus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari Kerja
                        </a>
                    </div>
                </nav>

                {{-- Mobile Hamburger --}}
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-xl text-slate-600 hover:text-teal-600 hover:bg-teal-50/50 transition-all duration-200 interactive-focus"
                    :aria-expanded="mobileMenuOpen"
                    aria-label="Toggle menu"
                >
                    <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu — Glass Elevated --}}
        <div
            x-show="mobileMenuOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden glass-elevated border-t border-slate-100/40"
        >
            <nav class="px-4 py-3 space-y-1" aria-label="Navigasi mobile">
                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('home') ? 'text-teal-600 bg-teal-50/60' : 'text-slate-600 hover:text-teal-600 hover:bg-teal-50/40' }} transition-all duration-200">
                    Beranda
                </a>
                <a href="{{ route('jobs.index') }}" class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('jobs.*') ? 'text-teal-600 bg-teal-50/60' : 'text-slate-600 hover:text-teal-600 hover:bg-teal-50/40' }} transition-all duration-200">
                    Cari Loker
                </a>
            </nav>
        </div>
    </header>

    {{-- Main Content --}}
    <main id="main-content" class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer — Multi-column, trust-building --}}
    <footer class="relative mt-auto">
        <div class="section-divider"></div>

        <div class="relative bg-gradient-to-b from-slate-50 to-white texture-dots">
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">

                {{-- Footer grid --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-8">

                    {{-- Brand column --}}
                    <div class="md:col-span-5">
                        <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-extrabold text-lg mb-4 group">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-600 to-cyan-600 flex items-center justify-center shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-gradient font-[family-name:var(--font-display)]">Portal Loker</span>
                        </a>
                        <p class="text-sm text-slate-500 leading-relaxed max-w-xs">
                            Platform pencarian kerja modern dengan teknologi AI. Temukan lowongan yang tepat untuk karier Anda.
                        </p>

                        {{-- Trust badges --}}
                        <div class="mt-6 flex flex-wrap gap-3">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50/80 border border-emerald-100/50 text-[0.6875rem] font-semibold text-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Data Privasi Aman
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-50/80 border border-teal-100/50 text-[0.6875rem] font-semibold text-teal-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                AI-Powered
                            </div>
                        </div>
                    </div>

                    {{-- Navigation column --}}
                    <div class="md:col-span-3">
                        <h4 class="stat-label text-slate-400 mb-4">Navigasi</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('home') }}" class="text-sm text-slate-600 hover:text-teal-600 transition-colors duration-200 font-medium">Beranda</a></li>
                            <li><a href="{{ route('jobs.index') }}" class="text-sm text-slate-600 hover:text-teal-600 transition-colors duration-200 font-medium">Cari Lowongan</a></li>
                        </ul>
                    </div>

                    {{-- Info column --}}
                    <div class="md:col-span-4">
                        <h4 class="stat-label text-slate-400 mb-4">Tentang</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-sm text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Data lowongan dikumpulkan otomatis dari berbagai sumber terpercaya.
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                CV yang diupload dihapus otomatis setelah dianalisis.
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Bottom bar --}}
                <div class="mt-10 pt-6 border-t border-slate-100/80 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-[0.6875rem] text-slate-400 font-medium">
                        &copy; {{ date('Y') }} Portal Loker. Hak cipta dilindungi.
                    </p>
                    <p class="text-[0.6875rem] text-slate-400 font-medium">
                        Dibuat dengan teknologi AI di Indonesia
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
