<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'Cari lowongan kerja terbaru di Indonesia dari berbagai sumber terpercaya. Lamaraja mengumpulkan loker dan menyediakan AI CV Matcher gratis.' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d9488">
    <meta name="robots" content="{{ $robots ?? 'index, follow' }}">

    <title>{{ $title ?? 'Lowongan Kerja Terbaru di Indonesia - Cari Loker | Lamaraja' }}</title>

    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    <link rel="alternate" hreflang="id" href="{{ $canonical ?? url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ $canonical ?? url()->current() }}">

    <meta property="og:type"        content="{{ $ogType ?? 'website' }}">
    <meta property="og:title"       content="{{ $title ?? 'Lowongan Kerja Terbaru di Indonesia | Lamaraja' }}">
    <meta property="og:description" content="{{ $description ?? 'Cari lowongan kerja terbaru di Indonesia. Diperkaya AI CV Matcher gratis.' }}">
    <meta property="og:url"         content="{{ $canonical ?? url()->current() }}">
    <meta property="og:site_name"   content="Lamaraja">
    <meta property="og:locale"      content="id_ID">
    <meta name="twitter:card"       content="summary_large_image">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    
    {{-- Google Sitelinks Search Box Structured Data --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Lamaraja',
        'url' => url('/'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => url('/jobs') . '?keyword={search_term_string}'
            ],
            'query-input' => 'required name=search_term_string'
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col antialiased">

    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:bg-teal-600 focus:text-white focus:px-4 focus:py-2 focus:rounded-lg focus:text-sm focus:font-semibold">
        Langsung ke konten utama
    </a>

    {{-- Header --}}
    <header class="glass-header sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 md:h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg interactive-focus rounded-lg">
                    <div class="w-8 h-8 rounded-lg bg-teal-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-slate-800 font-[family-name:var(--font-display)]">Lamaraja</span>
                </a>

                <nav class="hidden md:flex items-center gap-1" aria-label="Navigasi utama">
                    <a href="{{ route('home') }}" class="nav-pill px-3.5 py-2 text-sm font-medium interactive-focus {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                    <a href="{{ route('jobs.index') }}" class="nav-pill px-3.5 py-2 text-sm font-medium interactive-focus {{ request()->routeIs('jobs.*') ? 'active' : '' }}">Cari Loker</a>
                </nav>

                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors interactive-focus"
                    :aria-expanded="mobileMenuOpen" aria-label="Toggle menu">
                    <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="md:hidden glass-mobile-menu">
            <nav class="px-4 py-2 space-y-1" aria-label="Navigasi mobile">
                <a href="{{ route('home') }}" class="block px-3 py-3 rounded-lg text-sm font-medium min-h-[2.75rem] transition-colors {{ request()->routeIs('home') ? 'text-teal-600 bg-teal-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}">Beranda</a>
                <a href="{{ route('jobs.index') }}" class="block px-3 py-3 rounded-lg text-sm font-medium min-h-[2.75rem] transition-colors {{ request()->routeIs('jobs.*') ? 'text-teal-600 bg-teal-50' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}">Cari Loker</a>
            </nav>
        </div>
    </header>

    <main id="main-content" class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="mt-auto border-t border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 mb-3">Lamaraja</h3>
                    <ul class="space-y-2 text-sm text-stone-500">
                        <li><a href="{{ route('home') }}" class="hover:text-teal-600 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('jobs.index') }}" class="hover:text-teal-600 transition-colors">Semua Lowongan</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 mb-3">Lokasi</h3>
                    <ul class="space-y-2 text-sm text-stone-500">
                        @foreach(['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang'] as $city)
                            <li><a href="{{ route('jobs.index', ['location' => $city]) }}" class="hover:text-teal-600 transition-colors">Loker {{ $city }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 mb-3">Jenis Pekerjaan</h3>
                    <ul class="space-y-2 text-sm text-stone-500">
                        <li><a href="{{ route('jobs.index', ['employment_type' => ['full-time']]) }}" class="hover:text-teal-600 transition-colors">Full Time</a></li>
                        <li><a href="{{ route('jobs.index', ['employment_type' => ['part-time']]) }}" class="hover:text-teal-600 transition-colors">Part Time</a></li>
                        <li><a href="{{ route('jobs.index', ['employment_type' => ['contract']]) }}" class="hover:text-teal-600 transition-colors">Kontrak</a></li>
                        <li><a href="{{ route('jobs.index', ['employment_type' => ['internship']]) }}" class="hover:text-teal-600 transition-colors">Magang</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 mb-3">Pencarian Populer</h3>
                    <ul class="space-y-2 text-sm text-stone-500">
                        @foreach(['Developer', 'Marketing', 'Admin', 'Accounting', 'Design'] as $kw)
                            <li><a href="{{ route('jobs.index', ['keyword' => strtolower($kw)]) }}" class="hover:text-teal-600 transition-colors">Lowongan {{ $kw }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="pt-6 border-t border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                <span class="text-sm text-stone-400">&copy; {{ date('Y') }} #Lamaraja</span>
                <p class="text-xs text-stone-400 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    CV dihapus otomatis setelah analisis
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
