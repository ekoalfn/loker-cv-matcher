<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal Loker - Temukan lowongan kerja terbaik di Indonesia">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portal Loker - Lowongan Kerja Indonesia' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-white text-gray-800">

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-blue-600 font-bold text-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Portal Loker
                </a>

                {{-- Desktop Nav --}}
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors font-medium">
                        Beranda
                    </a>
                    <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-blue-600 transition-colors font-medium">
                        Cari Loker
                    </a>
                </nav>

                {{-- Mobile Hamburger --}}
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-gray-100 transition-colors"
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

        {{-- Mobile Menu --}}
        <div
            x-show="mobileMenuOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="md:hidden border-t border-gray-200 bg-white"
        >
            <nav class="px-4 py-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-3 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-gray-50 font-medium transition-colors">
                    Beranda
                </a>
                <a href="{{ route('jobs.index') }}" class="block px-3 py-3 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-gray-50 font-medium transition-colors">
                    Cari Loker
                </a>
            </nav>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Portal Loker. Hak cipta dilindungi.
                </p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-gray-500 hover:text-blue-600 text-sm transition-colors">
                        Kebijakan Privasi
                    </a>
                    <a href="#" class="text-gray-500 hover:text-blue-600 text-sm transition-colors">
                        Syarat & Ketentuan
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
