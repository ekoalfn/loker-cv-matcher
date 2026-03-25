@php
    $pageTitle = 'Cari Lowongan Kerja Terbaru';
    if ($filters->keyword ?? false) {
        $pageTitle = 'Lowongan ' . ucfirst($filters->keyword);
    }
    if ($filters->location ?? false) {
        $pageTitle .= ' di ' . $filters->location;
    }
    $pageTitle .= ' - Lamaraja';
@endphp
<x-layout :title="$pageTitle">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10" x-data="{ showFilters: false }">

        {{-- Sticky Search --}}
        <div class="sticky top-14 md:top-16 z-40 py-3 -mx-4 px-4 sm:mx-0 sm:px-0 md:relative md:top-auto md:z-auto md:py-0 md:mb-6"
             style="background: rgba(250,248,246,0.88); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);"
             :style="{ background: 'rgba(250,248,246,0.88)', backdropFilter: 'blur(24px)' }">
            <x-search-bar :keyword="$filters->keyword ?? ''" :action="route('jobs.index')" />
        </div>

        {{-- Page heading (H1 for SEO) --}}
        <h1 class="font-[family-name:var(--font-display)] text-xl md:text-2xl font-bold text-slate-800 tracking-tight mt-3 mb-4 md:mt-0">
            @if($filters->keyword)
                Lowongan &ldquo;{{ $filters->keyword }}&rdquo;
                @if($filters->location) di {{ $filters->location }} @endif
            @elseif($filters->location)
                Lowongan Kerja di {{ $filters->location }}
            @else
                Cari Lowongan Kerja Terbaru
            @endif
        </h1>

        {{-- Results header --}}
        <div class="flex items-center justify-between mb-5">
            <p class="text-sm text-stone-500">
                <span class="font-semibold text-slate-700">{{ $jobs->total() }}</span> lowongan ditemukan
            </p>
            <button
                @click="showFilters = !showFilters"
                class="md:hidden inline-flex items-center gap-2 min-h-[2.5rem] px-3.5 py-2 text-sm font-medium text-stone-600 rounded-xl transition-colors cursor-pointer interactive-focus"
                style="background: #f5f3f0; border: 1px solid #eae7e3;"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
        </div>

        <div class="flex flex-col md:flex-row gap-6 md:gap-8">

            {{-- Filter Sidebar --}}
            <aside
                x-show="showFilters"
                x-cloak
                x-transition
                class="md:!block md:w-64 md:shrink-0"
                :class="{ 'block': showFilters, 'hidden': !showFilters }"
            >
                <form action="{{ route('jobs.index') }}" method="GET" class="surface-recessed rounded-2xl p-5 space-y-5 sticky top-24">

                    @if($filters->keyword ?? false)
                        <input type="hidden" name="keyword" value="{{ $filters->keyword }}">
                    @endif

                    <h2 class="font-semibold text-slate-700 text-sm">Filter</h2>

                    {{-- Lokasi --}}
                    <div>
                        <label for="filter-location" class="block text-sm font-medium text-stone-500 mb-1.5">Lokasi</label>
                        <select
                            id="filter-location"
                            name="location"
                            class="w-full min-h-[2.5rem] px-3 py-2 rounded-xl input-glass text-sm focus:outline-none"
                        >
                            <option value="">Semua Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}" @selected(($filters->location ?? '') === $location)>{{ $location }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipe Kerja --}}
                    <div>
                        <label class="block text-sm font-medium text-stone-500 mb-1.5">Tipe Kerja</label>
                        <div class="space-y-1">
                            @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Kontrak', 'internship' => 'Magang'] as $value => $label)
                                <label class="flex items-center gap-2.5 cursor-pointer min-h-[2.25rem] px-2 py-1.5 rounded-xl hover:bg-slate-50 transition-colors">
                                    <input
                                        type="checkbox"
                                        name="employment_type[]"
                                        value="{{ $value }}"
                                        @checked(in_array($value, $filters->employmentType ?? []))
                                        class="glass-check"
                                    >
                                    <span class="text-sm text-stone-600">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <x-button type="submit" class="w-full">Terapkan Filter</x-button>
                </form>
            </aside>

            {{-- Job List --}}
            <div class="flex-1">
                @if($jobs->count() > 0)
                    <div class="space-y-3" data-reveal-stagger>
                        @foreach($jobs as $job)
                            <x-job-card :job="$job" />
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $jobs->withQueryString()->links() }}
                    </div>
                @else
                    <div class="surface rounded-2xl text-center py-16 px-6 ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-stone-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <h2 class="font-[family-name:var(--font-display)] text-lg font-bold text-slate-700 mb-1">Tidak ada hasil ditemukan</h2>
                        <p class="text-sm text-stone-400 mb-5">Coba ubah kata kunci atau hapus beberapa filter.</p>
                        <x-button variant="secondary" :href="route('jobs.index')">Hapus Filter</x-button>
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-layout>
