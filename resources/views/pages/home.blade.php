<x-layout
    title="Lowongan Kerja Terbaru {{ date('F Y') }} - Cari Loker | Lamaraja"
    description="Lamaraja bantu kamu cari lowongan kerja dari berbagai sumber. Dirangkum AI, gratis, dan update setiap hari."
>

    {{-- ============================================================
         HERO
         ============================================================ --}}
    <section class="relative overflow-hidden min-h-[68vh] flex items-center">

        {{-- Decorative orbs --}}
        <div class="hero-orb w-[700px] h-[700px] animate-orb"
             style="background: radial-gradient(circle, rgba(20,184,166,0.22) 0%, transparent 70%); top: -200px; left: -250px;"></div>
        <div class="hero-orb w-[600px] h-[600px] animate-orb"
             style="background: radial-gradient(circle, rgba(139,92,246,0.18) 0%, transparent 70%); bottom: -180px; right: -200px; animation-delay: -9s;"></div>
        <div class="hero-orb w-[400px] h-[400px]"
             style="background: radial-gradient(circle, rgba(99,102,241,0.14) 0%, transparent 70%); top: 50%; right: 20%; animation-delay: -4s;"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 w-full">
            <div class="max-w-2xl">

                {{-- Pill badge --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full mb-6 animate-fade-up"
                     style="background: rgba(45,212,191,0.10); border: 1px solid rgba(45,212,191,0.22);">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                    <span class="text-xs font-semibold text-teal-300 tracking-wide">{{ number_format($totalJobs) }}+ lowongan aktif</span>
                </div>

                <h1 class="font-[family-name:var(--font-display)] text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-[1.08] tracking-tight animate-fade-up delay-100"
                    style="text-shadow: 0 0 60px rgba(45,212,191,0.12);">
                    Temukan<br>
                    <span style="background: linear-gradient(135deg, #2dd4bf 0%, #818cf8 60%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Loker Impianmu</span>
                </h1>

                <p class="mt-5 text-lg text-white/60 leading-relaxed animate-fade-up delay-200 max-w-xl">
                    Kumpulan loker dari berbagai sumber, dirangkum AI biar kamu nggak perlu buka banyak situs.
                </p>

                {{-- Search bar --}}
                <div class="mt-8 max-w-xl animate-fade-up delay-300">
                    <x-search-bar :action="route('jobs.index')" size="lg" />
                </div>

                {{-- Popular keywords --}}
                <div class="mt-6 flex flex-wrap gap-x-1 gap-y-2 items-center animate-fade-up delay-400">
                    <span class="text-sm text-white/40 mr-1">Populer:</span>
                    @php $popularKeywords = ['Developer', 'Marketing', 'Admin', 'Accounting', 'Design', 'Customer Service', 'IT', 'Sales', 'HRD', 'Data Entry']; @endphp
                    @foreach($popularKeywords as $i => $kw)
                        @if($i > 0)<span class="text-white/20">&middot;</span>@endif
                        <a href="{{ route('jobs.index', ['keyword' => strtolower($kw)]) }}"
                           class="text-sm text-white/55 hover:text-teal-300 transition-colors font-medium">{{ $kw }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         STATS BAR
         ============================================================ --}}
    @if($jobsAddedToday > 0 || $totalJobs > 0)
        <div style="border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.02);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center gap-x-6 gap-y-1 text-sm text-white/45">
                @if($jobsAddedToday > 0)
                    <span><span class="font-semibold text-teal-400">{{ $jobsAddedToday }}</span> loker baru hari ini</span>
                @endif
                <span>Total: <span class="font-semibold text-white/75">{{ number_format($totalJobs) }}</span> lowongan aktif</span>
            </div>
        </div>
    @endif

    {{-- ============================================================
         RECENT JOBS
         ============================================================ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
        <div class="flex items-center justify-between mb-8">
            <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-white tracking-tight">
                Loker Terbaru
            </h2>
            <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-teal-400 hover:text-teal-300 transition-colors flex items-center gap-1">
                Lihat semua
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>

        @if($recentJobs->count() > 0)
            <div class="space-y-3" data-reveal-stagger>
                @foreach($recentJobs->take(8) as $job)
                    <x-job-card :job="$job" />
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <x-button :href="route('jobs.index')">Lihat Semua Loker</x-button>
            </div>
        @else
            <div class="surface rounded-2xl text-center py-16 px-6">
                <p class="font-[family-name:var(--font-display)] text-lg font-bold text-white/75">Belum ada loker nih</p>
                <p class="mt-1.5 text-sm text-white/45">Tenang, loker baru bakal muncul dalam waktu dekat!</p>
            </div>
        @endif
    </section>

    {{-- ============================================================
         LOCATIONS
         ============================================================ --}}
    @if($topLocations->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20" style="border-top: 1px solid rgba(255,255,255,0.05);">
            <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-white tracking-tight mb-6">
                Cari Berdasarkan Kota
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($topLocations as $loc)
                    <a href="{{ route('jobs.index', ['location' => $loc->location]) }}"
                       class="surface rounded-2xl p-4 card-hover group glass-shimmer">
                        <p class="text-sm font-semibold text-white/85 group-hover:text-teal-300 transition-colors">
                            {{ $loc->location }}
                        </p>
                        <p class="text-xs text-white/40 mt-1">{{ number_format($loc->job_count) }} lowongan</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============================================================
         EMPLOYMENT TYPES
         ============================================================ --}}
    @if($employmentTypeCounts->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20" style="border-top: 1px solid rgba(255,255,255,0.05);">
            <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-white tracking-tight mb-6">
                Mau Kerja Apa?
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                @foreach($employmentTypeCounts as $type)
                    <a href="{{ route('jobs.index', ['employment_type' => [$type->employment_type]]) }}"
                       class="surface rounded-2xl p-4 card-hover text-center group glass-shimmer">
                        <p class="text-sm font-semibold text-white/85 group-hover:text-teal-300 transition-colors">
                            {{ employment_label($type->employment_type) }}
                        </p>
                        <p class="text-xs text-white/40 mt-1">{{ number_format($type->job_count) }} lowongan</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============================================================
         HOW IT WORKS
         ============================================================ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20" style="border-top: 1px solid rgba(255,255,255,0.05);">
        <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-white tracking-tight mb-10 text-center">
            Gimana Cara Pakainya?
        </h2>
        <div class="grid sm:grid-cols-3 gap-5 md:gap-6 max-w-3xl mx-auto">
            @php
                $steps = [
                    ['num' => '1', 'color' => 'rgba(45,212,191,0.85)', 'glow' => 'rgba(45,212,191,0.30)', 'title' => 'Cari Loker', 'desc' => 'Ketik posisi atau kota yang kamu mau, langsung muncul dari berbagai sumber.'],
                    ['num' => '2', 'color' => 'rgba(245,158,11,0.85)', 'glow' => 'rgba(245,158,11,0.28)', 'title' => 'Cek CV Kamu', 'desc' => 'Upload CV, AI kasih tau seberapa cocok kamu sama lowongannya. Gratis!'],
                    ['num' => '3', 'color' => 'rgba(52,211,153,0.85)',  'glow' => 'rgba(52,211,153,0.28)',  'title' => 'Langsung Lamar', 'desc' => 'Klik lamar, langsung ke situs resmi perusahaan. Nggak pakai ribet.'],
                ];
            @endphp
            @foreach($steps as $step)
                <div class="surface rounded-2xl p-6 text-center glass-shimmer">
                    <div class="w-11 h-11 mx-auto rounded-xl flex items-center justify-center mb-4"
                         style="background: {{ $step['color'] }}; box-shadow: 0 4px 16px {{ $step['glow'] }}, inset 0 1px 0 rgba(255,255,255,0.25);">
                        <span class="text-sm font-extrabold text-white">{{ $step['num'] }}</span>
                    </div>
                    <h3 class="text-sm font-bold text-white mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-white/50 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============================================================
         POPULAR SEARCHES
         ============================================================ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20" style="border-top: 1px solid rgba(255,255,255,0.05);">
        <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-white tracking-tight mb-6">
            Yang Lagi Rame Dicari
        </h2>
        <div class="flex flex-wrap gap-2">
            @php
                $searches = ['Developer','Marketing','Admin','Accounting','Design','Customer Service','Data Entry','HRD','Sales','IT','Finance','Content Writer','Digital Marketing','Logistik','Warehouse'];
            @endphp
            @foreach($searches as $term)
                <a href="{{ route('jobs.index', ['keyword' => strtolower($term)]) }}"
                   class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-sm text-white/65 transition-all"
                   style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.09); hover:background: rgba(255,255,255,0.09);"
                   onmouseover="this.style.background='rgba(45,212,191,0.10)'; this.style.borderColor='rgba(45,212,191,0.22)'; this.style.color='rgb(94,234,212)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.09)'; this.style.color='rgba(255,255,255,0.65)';">
                    Lowongan {{ $term }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- ============================================================
         FAQ
         ============================================================ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20" style="border-top: 1px solid rgba(255,255,255,0.05);">
        <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-white tracking-tight mb-6">
            Sering Ditanya
        </h2>
        <div class="space-y-2 max-w-3xl" x-data="{ openFaq: null }">
            @php
                $faqs = [
                    ['q' => 'Lamaraja itu apa sih?',    'a' => 'Lamaraja itu semacam mesin pencari khusus lowongan kerja. Kita ngumpulin loker dari berbagai situs, terus AI bantu ringkasin biar kamu nggak perlu baca satu-satu. Ada juga fitur CV Matcher yang bisa cek seberapa cocok CV kamu sama lowongan.'],
                    ['q' => 'Gratis nggak?',             'a' => '100% gratis. Cari loker gratis, pakai AI CV Matcher gratis, lamar kerja juga gratis. Nggak ada biaya tersembunyi.'],
                    ['q' => 'AI CV Matcher itu gimana?', 'a' => 'Kamu upload CV (PDF), terus AI analisis dan kasih skor kecocokan sama lowongan yang kamu pilih. Dikasih tau juga kelebihan, kekurangan, dan saran perbaikan. CV kamu langsung dihapus setelah dianalisis, jadi aman.'],
                    ['q' => 'Lokernya update nggak?',    'a' => 'Update otomatis setiap hari. Yang udah expired langsung dihapus, jadi kamu cuma lihat yang masih buka.'],
                ];
            @endphp
            @foreach($faqs as $index => $faq)
                <div class="surface rounded-2xl overflow-hidden glass-shimmer">
                    <button
                        @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                        class="w-full flex items-center justify-between p-5 text-left cursor-pointer hover:bg-white/03 transition-colors"
                        :aria-expanded="openFaq === {{ $index }}"
                    >
                        <span class="text-sm font-semibold text-white/85 pr-4">{{ $faq['q'] }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white/35 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': openFaq === {{ $index }} }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="openFaq === {{ $index }}" x-cloak x-collapse>
                        <div class="px-5 pb-5">
                            <p class="text-sm text-white/55 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Structured Data --}}
    {!! '<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebSite","name":"Lamaraja","url":"' . url('/') . '","inLanguage":"id","potentialAction":{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"' . url('/jobs') . '?keyword={search_term_string}"},"query-input":"required name=search_term_string"}}</script>' !!}
    {!! '<script type="application/ld+json">{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"Apa itu Lamaraja?","acceptedAnswer":{"@type":"Answer","text":"Lamaraja adalah agregator lowongan kerja yang mengumpulkan informasi dari berbagai sumber terpercaya di Indonesia, diperkaya dengan ringkasan AI dan fitur CV Matcher."}},{"@type":"Question","name":"Apakah Lamaraja gratis?","acceptedAnswer":{"@type":"Answer","text":"Ya, sepenuhnya gratis."}},{"@type":"Question","name":"Apa itu AI CV Matcher?","acceptedAnswer":{"@type":"Answer","text":"Fitur yang menganalisis CV dan mencocokkannya dengan persyaratan lowongan."}},{"@type":"Question","name":"Seberapa sering lowongan diperbarui?","acceptedAnswer":{"@type":"Answer","text":"Diperbarui otomatis setiap hari."}}]}</script>' !!}

</x-layout>
