<x-layout
    title="Lowongan Kerja Terbaru {{ date('F Y') }} di Indonesia - Cari Loker | Portal Loker"
    description="Temukan {{ number_format($totalJobs) }}+ lowongan kerja terbaru di Indonesia. Portal Loker mengumpulkan loker dari berbagai situs karir terpercaya, diperkaya AI CV Matcher gratis."
>

    {{-- Hero --}}
    <section class="bg-teal-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="max-w-2xl">
                <h1 class="font-[family-name:var(--font-display)] text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-[1.12] tracking-tight animate-fade-up">
                    Temukan {{ number_format($totalJobs) }}+ lowongan kerja di Indonesia
                </h1>
                <p class="mt-4 text-lg text-teal-100 leading-relaxed animate-fade-up delay-100">
                    Cari loker dari berbagai sumber terpercaya, diperkaya AI agar Anda bisa fokus melamar pekerjaan yang tepat.
                </p>

                <div class="mt-8 max-w-xl animate-fade-up delay-200">
                    <x-search-bar :action="route('jobs.index')" size="lg" />
                </div>

                <div class="mt-6 flex flex-wrap gap-x-1 gap-y-2 items-center animate-fade-up delay-300">
                    <span class="text-sm text-teal-200 mr-1">Populer:</span>
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
                    <span><span class="font-semibold text-teal-700">{{ $jobsAddedToday }}</span> lowongan baru hari ini</span>
                @endif
                <span>Total: <span class="font-semibold text-slate-700">{{ number_format($totalJobs) }}</span> lowongan aktif</span>
            </div>
        </div>
    @endif

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
            <div class="surface rounded-xl text-center py-16 px-6">
                <p class="font-[family-name:var(--font-display)] text-lg font-bold text-slate-700">Belum ada lowongan</p>
                <p class="mt-1.5 text-sm text-slate-500">Lowongan baru akan muncul setelah proses scraping berjalan.</p>
            </div>
        @endif
    </section>

    {{-- Lowongan Berdasarkan Lokasi --}}
    @if($topLocations->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 border-t border-slate-100">
            <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-900 tracking-tight mb-6">
                Lowongan Berdasarkan Lokasi
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
                Cari Berdasarkan Jenis Pekerjaan
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
            Cara Kerja Portal Loker
        </h2>
        <div class="grid sm:grid-cols-3 gap-6 md:gap-8 max-w-3xl mx-auto">
            <div class="text-center">
                <div class="w-10 h-10 mx-auto rounded-lg bg-teal-50 border border-teal-100 flex items-center justify-center mb-3">
                    <span class="text-sm font-bold text-teal-700">1</span>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 mb-1">Cari Lowongan</h3>
                <p class="text-sm text-slate-500">Telusuri {{ number_format($totalJobs) }}+ lowongan dari berbagai sumber terpercaya di Indonesia.</p>
            </div>
            <div class="text-center">
                <div class="w-10 h-10 mx-auto rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center mb-3">
                    <span class="text-sm font-bold text-amber-700">2</span>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 mb-1">Cek Kecocokan CV</h3>
                <p class="text-sm text-slate-500">Upload CV, AI analisis kecocokan dengan lowongan yang Anda pilih. Gratis.</p>
            </div>
            <div class="text-center">
                <div class="w-10 h-10 mx-auto rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center mb-3">
                    <span class="text-sm font-bold text-emerald-700">3</span>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 mb-1">Lamar Langsung</h3>
                <p class="text-sm text-slate-500">Langsung ke halaman resmi perusahaan. Tanpa akun, tanpa perantara.</p>
            </div>
        </div>
    </section>

    {{-- Pencarian Populer --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 border-t border-slate-100">
        <h2 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-900 tracking-tight mb-6">
            Pencarian Populer
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
            Pertanyaan Umum
        </h2>
        <div class="space-y-3 max-w-3xl" x-data="{ openFaq: null }">
            @php
                $faqs = [
                    ['q' => 'Apa itu Portal Loker dan bagaimana cara kerjanya?', 'a' => 'Portal Loker adalah agregator lowongan kerja yang mengumpulkan informasi lowongan dari berbagai sumber terpercaya di Indonesia. Setiap lowongan diperkaya dengan ringkasan AI dan fitur CV Matcher yang membantu Anda menilai kesesuaian CV dengan posisi yang dilamar. Anda bisa langsung melamar di situs resmi perusahaan tanpa biaya.'],
                    ['q' => 'Apakah Portal Loker gratis untuk pencari kerja?', 'a' => 'Ya, Portal Loker sepenuhnya gratis untuk pencari kerja. Anda bisa mencari lowongan, menggunakan fitur AI CV Matcher, dan melamar pekerjaan tanpa dipungut biaya.'],
                    ['q' => 'Apa itu fitur AI CV Matcher?', 'a' => 'AI CV Matcher menganalisis CV Anda dan mencocokkannya dengan persyaratan lowongan kerja. Fitur ini memberikan skor kesesuaian, daftar kekuatan dan kekurangan, serta saran perbaikan agar peluang Anda diterima lebih besar. CV Anda dihapus otomatis setelah analisis selesai.'],
                    ['q' => 'Seberapa sering lowongan kerja diperbarui?', 'a' => 'Lowongan kerja diperbarui secara otomatis setiap hari dari berbagai sumber. Lowongan yang sudah kedaluwarsa akan otomatis dihapus sehingga Anda hanya melihat lowongan yang masih aktif.'],
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
    {!! '<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebSite","name":"Portal Loker","url":"' . url('/') . '","inLanguage":"id","potentialAction":{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"' . url('/jobs') . '?keyword={search_term_string}"},"query-input":"required name=search_term_string"}}</script>' !!}
    {!! '<script type="application/ld+json">{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"Apa itu Portal Loker?","acceptedAnswer":{"@type":"Answer","text":"Portal Loker adalah agregator lowongan kerja yang mengumpulkan informasi dari berbagai sumber terpercaya di Indonesia, diperkaya dengan ringkasan AI dan fitur CV Matcher."}},{"@type":"Question","name":"Apakah Portal Loker gratis?","acceptedAnswer":{"@type":"Answer","text":"Ya, sepenuhnya gratis. Anda bisa mencari lowongan, menggunakan AI CV Matcher, dan melamar tanpa biaya."}},{"@type":"Question","name":"Apa itu AI CV Matcher?","acceptedAnswer":{"@type":"Answer","text":"Fitur yang menganalisis CV dan mencocokkannya dengan persyaratan lowongan. Memberikan skor kesesuaian dan saran perbaikan. CV dihapus otomatis setelah analisis."}},{"@type":"Question","name":"Seberapa sering lowongan diperbarui?","acceptedAnswer":{"@type":"Answer","text":"Diperbarui otomatis setiap hari. Lowongan kedaluwarsa otomatis dihapus."}}]}</script>' !!}

</x-layout>
