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
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 interactive-focus rounded-lg group">
                    <img src="{{ asset('images/crown-logo.svg') }}" alt="Lamaraja Crown" class="w-12 h-12 transition-transform group-hover:scale-105">
                    <div class="flex flex-col">
                        <span class="text-slate-900 font-[family-name:var(--font-display)] text-xl font-bold leading-none">Lamaraja</span>
                        <span class="text-xs text-emerald-600 font-medium leading-none mt-0.5">Lamar aja!</span>
                    </div>
                </a>

                {{-- Navigation --}}
                <nav class="hidden md:flex items-center gap-8" aria-label="Navigasi utama">
                    <a href="{{ route('home') }}" class="relative text-sm font-semibold {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-slate-700 hover:text-slate-900' }} transition-colors py-1">
                        Home
                        @if(request()->routeIs('home'))
                            <span class="absolute -bottom-[1.125rem] left-0 right-0 h-0.5 bg-emerald-600"></span>
                        @endif
                    </a>
                    <a href="{{ route('jobs.index') }}" class="relative text-sm font-semibold {{ request()->routeIs('jobs.*') ? 'text-emerald-600' : 'text-slate-700 hover:text-slate-900' }} transition-colors py-1">
                        Jobs
                        @if(request()->routeIs('jobs.*'))
                            <span class="absolute -bottom-[1.125rem] left-0 right-0 h-0.5 bg-emerald-600"></span>
                        @endif
                    </a>
                    <a href="#" class="text-sm font-semibold text-slate-700 hover:text-slate-900 transition-colors">CV Matcher</a>
                    <a href="#" class="text-sm font-semibold text-slate-700 hover:text-slate-900 transition-colors">About</a>
                </nav>

                {{-- CTA Buttons --}}
                <div class="hidden md:flex items-center gap-3">
                    <button class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload CV
                    </button>
                    <a href="{{ route('jobs.index') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md active:scale-[0.98]">
                        Find Jobs
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors"
                    :aria-expanded="mobileMenuOpen" aria-label="Toggle menu">
                    <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="md:hidden border-t border-slate-200 bg-white">
            <nav class="px-4 py-3 space-y-1" aria-label="Navigasi mobile">
                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'text-emerald-600 bg-emerald-50' : 'text-slate-700 hover:bg-slate-50' }}">Home</a>
                <a href="{{ route('jobs.index') }}" class="block px-4 py-3 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('jobs.*') ? 'text-emerald-600 bg-emerald-50' : 'text-slate-700 hover:bg-slate-50' }}">Jobs</a>
                <a href="#" class="block px-4 py-3 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">CV Matcher</a>
                <a href="#" class="block px-4 py-3 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">About</a>
                <div class="pt-3 space-y-2">
                    <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-50 rounded-lg">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload CV
                    </button>
                    <a href="{{ route('jobs.index') }}" class="block w-full text-center px-4 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-lg">
                        Find Jobs
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main id="main-content" class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="mt-auto bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-8">
                {{-- Logo & Description --}}
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <img src="{{ asset('images/crown-logo.svg') }}" alt="Lamaraja Crown" class="w-12 h-12 brightness-0 invert">
                        <div class="flex flex-col">
                            <span class="text-white font-[family-name:var(--font-display)] text-xl font-bold leading-none">Lamaraja</span>
                            <span class="text-xs text-emerald-400 font-medium leading-none mt-0.5">Lamar aja!</span>
                        </div>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                        AI-powered job search that saves your time and gets you better opportunities.
                    </p>
                </div>

                {{-- Company --}}
                <div>
                    <h3 class="text-white font-semibold text-sm mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">About Us</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">How It Works</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Careers</a></li>
                    </ul>
                </div>

                {{-- Resources --}}
                <div>
                    <h3 class="text-white font-semibold text-sm mb-4">Resources</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Blog</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Contact</a></li>
                    </ul>
                </div>

                {{-- Legal --}}
                <div>
                    <h3 class="text-white font-semibold text-sm mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>

            {{-- Newsletter --}}
            <div class="border-t border-slate-800 pt-8 mb-8">
                <div class="max-w-md">
                    <h3 class="text-white font-semibold text-sm mb-2">Stay in the loop</h3>
                    <p class="text-slate-400 text-sm mb-4">Get the latest job tips and opportunities.</p>
                    <form class="flex gap-2">
                        <input 
                            type="email" 
                            placeholder="Enter your email" 
                            class="flex-1 px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                        >
                        <button 
                            type="submit"
                            class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-sm transition-all active:scale-[0.98]"
                        >
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-slate-500 text-sm">
                    © {{ date('Y') }} Lamaraja. All rights reserved.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors" aria-label="LinkedIn">
                        <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors" aria-label="Twitter">
                        <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors" aria-label="Facebook">
                        <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors" aria-label="Instagram">
                        <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
