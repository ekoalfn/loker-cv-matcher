<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'Portal Loker - Temukan lowongan kerja terbaik di Indonesia dengan bantuan AI' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portal Loker - Lowongan Kerja Indonesia' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }</style>
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 via-indigo-50/30 to-white text-slate-800 antialiased">

    {{-- Skip to Content (A11y) --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:bg-indigo-600 focus:text-white focus:px-4 focus:py-2 focus:rounded-xl focus:text-sm focus:font-semibold focus:shadow-lg focus:shadow-indigo-500/25">
        Langsung ke konten utama
    </a>

    {{-- Header — Liquid Glass --}}
    <header class="glass-header sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-extrabold text-xl transition-all duration-300 hover:scale-[1.02] group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:shadow-indigo-500/30 transition-shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-gradient">Portal Loker</span>
                </a>

                {{-- Desktop Nav --}}
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="nav-pill px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'active text-indigo-600' : 'text-slate-600 hover:text-indigo-600' }}">
                        Beranda
                    </a>
                    <a href="{{ route('jobs.index') }}" class="nav-pill px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('jobs.*') ? 'active text-indigo-600' : 'text-slate-600 hover:text-indigo-600' }}">
                        Cari Loker
                    </a>
                </nav>

                {{-- Mobile Hamburger --}}
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all duration-200"
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

        {{-- Mobile Menu — Glass --}}
        <div
            x-show="mobileMenuOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden glass-light border-t border-white/20"
        >
            <nav class="px-4 py-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('home') ? 'text-indigo-600 bg-indigo-50/60' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/40' }} transition-all duration-200">
                    Beranda
                </a>
                <a href="{{ route('jobs.index') }}" class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('jobs.*') ? 'text-indigo-600 bg-indigo-50/60' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/40' }} transition-all duration-200">
                    Cari Loker
                </a>
            </nav>
        </div>
    </header>

    {{-- Main Content --}}
    <main id="main-content" class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer — Liquid Glass --}}
    <footer class="relative mt-auto">
        <div class="absolute inset-0 bg-gradient-to-t from-indigo-50/50 to-transparent pointer-events-none"></div>
        <div class="relative glass-light border-t border-white/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2.5 text-slate-500 text-sm">
                        <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        &copy; {{ date('Y') }} Portal Loker
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Privasi terlindungi. CV dihapus setelah analisis.
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
