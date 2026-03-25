<x-layout title="Portal Loker - Temukan Lowongan Kerja Terbaik di Indonesia">

    {{-- Hero — Left-aligned, content-focused --}}
    <section class="bg-teal-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="max-w-2xl">
                <h1 class="font-[family-name:var(--font-display)] text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-[1.12] tracking-tight animate-fade-up">
                    Temukan {{ number_format($totalJobs) }}+ lowongan kerja di Indonesia
                </h1>
                <p class="mt-4 text-lg text-teal-100/80 leading-relaxed animate-fade-up delay-100">
                    Dirangkum dari berbagai sumber, diperkaya AI agar Anda bisa fokus melamar.
                </p>

                <div class="mt-8 max-w-xl animate-fade-up delay-200">
                    <x-search-bar :action="route('jobs.index')" />
                </div>

                <div class="mt-6 flex flex-wrap gap-2 animate-fade-up delay-300">
                    <span class="text-sm text-teal-200/70">Populer:</span>
                    <a href="{{ route('jobs.index', ['keyword' => 'developer']) }}" class="text-sm text-teal-100 hover:text-white transition-colors font-medium">Developer</a>
                    <span class="text-teal-400/40">&middot;</span>
                    <a href="{{ route('jobs.index', ['keyword' => 'marketing']) }}" class="text-sm text-teal-100 hover:text-white transition-colors font-medium">Marketing</a>
                    <span class="text-teal-400/40">&middot;</span>
                    <a href="{{ route('jobs.index', ['keyword' => 'accounting']) }}" class="text-sm text-teal-100 hover:text-white transition-colors font-medium">Accounting</a>
                    <span class="text-teal-400/40">&middot;</span>
                    <a href="{{ route('jobs.index', ['keyword' => 'admin']) }}" class="text-sm text-teal-100 hover:text-white transition-colors font-medium">Admin</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Lowongan Terbaru --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-900 tracking-tight">
                Lowongan Terbaru
            </h2>
            <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-700 transition-colors">
                Lihat semua &rarr;
            </a>
        </div>

        @if($recentJobs->count() > 0)
            <div class="space-y-3" data-reveal-stagger>
                @foreach($recentJobs->take(8) as $job)
                    <x-job-card :job="$job" />
                @endforeach
            </div>

            <div class="mt-8 text-center">
                <x-button :href="route('jobs.index')">
                    Lihat Semua Lowongan
                </x-button>
            </div>
        @else
            <div class="surface rounded-2xl text-center py-16 px-6">
                <p class="text-lg font-semibold text-slate-700">Belum ada lowongan tersedia</p>
                <p class="mt-2 text-slate-500 text-sm">Silakan kembali lagi nanti.</p>
            </div>
        @endif
    </section>

</x-layout>
