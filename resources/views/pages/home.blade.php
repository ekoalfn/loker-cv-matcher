<x-layout
    title="Lowongan Kerja Terbaru {{ date('F Y') }} - Cari Loker | Lamaraja"
    description="Lamaraja bantu kamu cari lowongan kerja dari berbagai sumber. Dirangkum AI, gratis, dan update setiap hari."
>

    {{-- Hero --}}
    <section class="bg-teal-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="max-w-2xl">
                <h1 class="font-[family-name:var(--font-display)] text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-[1.12] tracking-tight animate-fade-up">
                    Cari kerja pertama? Lamaraja bantu kamu temukan dari {{ number_format($totalJobs) }}+ lowongan
                </h1>
                <p class="mt-4 text-lg text-teal-100 leading-relaxed animate-fade-up delay-100">
                    Kumpulan loker dari berbagai sumber, dirangkum AI biar kamu nggak perlu buka banyak situs.
                </p>

                <div class="mt-8 max-w-xl animate-fade-up delay-200">
                    <x-search-bar :action="route('jobs.index')" size="lg" />
                </div>

                <div class="mt-6 flex flex-wrap gap-x-1 gap-y-2 items-center animate-fade-up delay-300">
                    <span class="text-sm text-teal-200 mr-1">Lagi banyak dicari:</span>
                    @php
                        $popularKeywords = ['Developer', 'Marketing', 'Admin', 'Accounting', 'Design', 'Customer Service', 'IT', 'Sales', 'HRD', 'Data Entry'];
                    @endphp
                    @foreach($popularKeywords as $i => $kw)
                        @if($i > 0)<span class="text-teal-400/40">&middot;</span>@endif
                        <a href="{{ route('jobs.index', ['keyword' => strtolower($kw)]) }}" class="text-sm text-teal-100 hover:text-white transition-colors font-medium">{{ $kw }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Activity stats bar --}}
    @if($jobsAddedToday > 0 || $totalJobs > 0)
        <div class="border-b border-slate-100 bg-slate-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center gap-x-6 gap-y-1 text-sm text-slate-500">
                @if($jobsAddedToday > 0)
                    <span><span class="font-semibold text-teal-700">{{ $jobsAddedToday }}</span> loker baru hari ini</span>
                @endif
                <span>Total: <span class="font-semibold text-slate-700">{{ number_format($totalJobs) }}</span> lowongan aktif</span>
            </div>
        </div>
    @endif

    {{-- Lowongan Terbaru --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-900 tracking-tight">
                Loker Terbaru
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
                    Lihat Semua Loker
                </x-button>
            </div>
        @else
            <div class="surface rounded-xl text-center py-16 px-6">
                <p class="font-[family-name:var(--font-display)] text-lg font-bold text-slate-700">Belum ada loker nih</p>
                <p class="mt-1.5 text-sm text-slate-500">Tenang, loker baru bakal muncul dalam waktu dekat!</p>
            </div>
        @endif
    </section>

    {{-- Lowongan Berdasarkan Lokasi --}}
    @if($topLocations->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 border-t border-slate-100">
            <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-900 tracking-tight mb-6">
                Cari Berdasarkan Kota
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($topLocations as $loc)
                    <a href="{{ route('jobs.index', ['location' => $loc->location]) }}"
                       class="surface rounded-xl p-4 card-hover group">
                        <p class="text-sm font-semibold text-slate-900 group-hover:text-teal-700 transition-colors">
                            {{ $loc->location }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">{{ number_format($loc->job_count) }} lowongan</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Lowongan Berdasarkan Jenis Pekerjaan --}}
    @if($employmentTypeCounts->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 border-t border-slate-100">
            <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-900 tracking-tight mb-6">
                Mau Kerja Apa?
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                @foreach($employmentTypeCounts as $type)
                    <a href="{{ route('jobs.index', ['employment_type' => [$type->employment_type]]) }}"
                       class="surface rounded-xl p-4 card-hover text-center group">
                        <p class="text-sm font-semibold text-slate-900 group-hover:text-teal-700 transition-colors">
                            {{ employment_label($type->employment_type) }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">{{ number_format($type->job_count) }} lowongan</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Cara Kerja --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 border-t border-slate-100">
        <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-900 tracking-tight mb-8 text-center">
            Gimana Cara Pakainya?
        </h2>
        <div class="grid sm:grid-cols-3 gap-6 md:gap-8 max-w-3xl mx-auto">
            <div class="text-center">
                <div class="w-10 h-10 mx-auto rounded-lg bg-teal-50 border border-teal-100 flex items-center justify-center mb-3">
                    <span class="text-sm font-bold text-teal-700">1</span>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 mb-1">Cari Loker</h3>
                <p class="text-sm text-slate-500">Ketik posisi atau kota yang kamu mau, langsung muncul dari berbagai sumber.</p>
            </div>
            <div class="text-center">
                <div class="w-10 h-10 mx-auto rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center mb-3">
                    <span class="text-sm font-bold text-amber-700">2</span>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 mb-1">Cek CV Kamu</h3>
                <p class="text-sm text-slate-500">Upload CV, AI kasih tau seberapa cocok kamu sama lowongannya. Gratis!</p>
            </div>
            <div class="text-center">
                <div class="w-10 h-10 mx-auto rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center mb-3">
                    <span class="text-sm font-bold text-emerald-700">3</span>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 mb-1">Langsung Lamar</h3>
                <p class="text-sm text-slate-500">Klik lamar, langsung ke situs resmi perusahaan. Nggak pakai ribet.</p>
            </div>
        </div>
    </section>

    {{-- Pencarian Populer --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 border-t border-slate-100">
        <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-900 tracking-tight mb-6">
            Yang Lagi Rame Dicari
        </h2>
        <div class="flex flex-wrap gap-2">
            @php
                $searches = [
                    'Developer', 'Marketing', 'Admin', 'Accounting', 'Design',
                    'Customer Service', 'Data Entry', 'HRD', 'Sales', 'IT',
                    'Finance', 'Content Writer', 'Digital Marketing', 'Logistik', 'Warehouse',
                ];
            @endphp
            @foreach($searches as $term)
                <a href="{{ route('jobs.index', ['keyword' => strtolower($term)]) }}"
                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm text-slate-600 bg-slate-50 border border-slate-100 hover:border-teal-200 hover:text-teal-700 hover:bg-teal-50 transition-colors">
                    Lowongan {{ $term }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- FAQ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 border-t border-slate-100">
        <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-900 tracking-tight mb-6">
            Sering Ditanya
        </h2>
        <div class="space-y-3 max-w-3xl" x-data="{ openFaq: null }">
            @php
                $faqs = [
                    ['q' => 'Lamaraja itu apa sih?', 'a' => 'Lamaraja itu semacam mesin pencari khusus lowongan kerja. Kita ngumpulin loker dari berbagai situs, terus AI bantu ringkasin biar kamu nggak perlu baca satu-satu. Ada juga fitur CV Matcher yang bisa cek seberapa cocok CV kamu sama lowongan.'],
                    ['q' => 'Gratis nggak?', 'a' => '100% gratis. Cari loker gratis, pakai AI CV Matcher gratis, lamar kerja juga gratis. Nggak ada biaya tersembunyi.'],
                    ['q' => 'AI CV Matcher itu gimana?', 'a' => 'Kamu upload CV (PDF), terus AI analisis dan kasih skor kecocokan sama lowongan yang kamu pilih. Dikasih tau juga kelebihan, kekurangan, dan saran perbaikan. CV kamu langsung dihapus setelah dianalisis, jadi aman.'],
                    ['q' => 'Lokernya update nggak?', 'a' => 'Update otomatis setiap hari. Yang udah expired langsung dihapus, jadi kamu cuma lihat yang masih buka.'],
                ];
            @endphp

            @foreach($faqs as $index => $faq)
                <div class="surface rounded-xl overflow-hidden">
                    <button
                        @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                        class="w-full flex items-center justify-between p-4 text-left cursor-pointer"
                        :aria-expanded="openFaq === {{ $index }}"
                    >
                        <span class="text-sm font-semibold text-slate-900 pr-4">{{ $faq['q'] }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': openFaq === {{ $index }} }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="openFaq === {{ $index }}" x-cloak x-collapse>
                        <div class="px-4 pb-4">
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Structured Data --}}
    {!! '<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebSite","name":"Lamaraja","url":"' . url('/') . '","inLanguage":"id","potentialAction":{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"' . url('/jobs') . '?keyword={search_term_string}"},"query-input":"required name=search_term_string"}}</script>' !!}
    {!! '<script type="application/ld+json">{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"Apa itu Lamaraja?","acceptedAnswer":{"@type":"Answer","text":"Lamaraja adalah agregator lowongan kerja yang mengumpulkan informasi dari berbagai sumber terpercaya di Indonesia, diperkaya dengan ringkasan AI dan fitur CV Matcher."}},{"@type":"Question","name":"Apakah Lamaraja gratis?","acceptedAnswer":{"@type":"Answer","text":"Ya, sepenuhnya gratis. Anda bisa mencari lowongan, menggunakan AI CV Matcher, dan melamar tanpa biaya."}},{"@type":"Question","name":"Apa itu AI CV Matcher?","acceptedAnswer":{"@type":"Answer","text":"Fitur yang menganalisis CV dan mencocokkannya dengan persyaratan lowongan. Memberikan skor kesesuaian dan saran perbaikan. CV dihapus otomatis setelah analisis."}},{"@type":"Question","name":"Seberapa sering lowongan diperbarui?","acceptedAnswer":{"@type":"Answer","text":"Diperbarui otomatis setiap hari. Lowongan kedaluwarsa otomatis dihapus."}}]}</script>' !!}

</x-layout>
