<x-layout title="Cari Lowongan Kerja - Portal Loker">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12" x-data="{ showFilters: false }">

        {{-- Search Bar --}}
        <div class="sticky top-16 z-40 py-3 -mx-4 px-4 sm:mx-0 sm:px-0 md:relative md:top-auto md:z-auto md:py-0 md:mb-8">
            <x-search-bar :keyword="$filters->keyword ?? ''" :action="route('jobs.index')" />
        </div>

        {{-- Results header --}}
        <div class="flex items-center justify-between mt-4 mb-6 md:mb-8">
            <div class="flex items-baseline gap-2">
                <span class="font-[family-name:var(--font-display)] text-2xl font-extrabold text-teal-700 tracking-tight">{{ $jobs->total() }}</span>
                <span class="text-sm text-slate-500 font-medium">lowongan ditemukan</span>
            </div>
            <button
                @click="showFilters = !showFilters"
                class="md:hidden inline-flex items-center gap-2 min-h-[2.75rem] px-4 py-2 text-sm font-semibold text-teal-600 glass rounded-xl hover:bg-white/70 transition-all duration-200 cursor-pointer interactive-focus"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
                <span x-show="showFilters" x-cloak class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
            </button>
        </div>

        <div class="flex flex-col md:flex-row gap-6 md:gap-8">

            {{-- Filter Sidebar — Glass Recessed (sinks behind cards) --}}
            <aside
                x-show="showFilters"
                x-cloak
                x-transition
                class="md:!block md:w-72 md:shrink-0"
                :class="{ 'block': showFilters, 'hidden': !showFilters }"
            >
                <form action="{{ route('jobs.index') }}" method="GET" class="glass-recessed rounded-2xl p-5 space-y-5 sticky top-24">

                    @if($filters->keyword ?? false)
                        <input type="hidden" name="keyword" value="{{ $filters->keyword }}">
                    @endif

                    {{-- Section Title --}}
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-500 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800">Filter</h3>
                    </div>

                    {{-- Lokasi --}}
                    <div>
                        <label for="filter-location" class="block text-sm font-semibold text-slate-700 mb-2">
                            Lokasi
                        </label>
                        <select
                            id="filter-location"
                            name="location"
                            class="w-full min-h-[2.75rem] px-4 py-2.5 rounded-xl input-glass text-sm text-slate-800 focus:outline-none"
                        >
                            <option value="">Semua Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}" @selected(($filters->location ?? '') === $location)>
                                    {{ $location }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipe Kerja --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tipe Kerja
                        </label>
                        <div class="space-y-1.5">
                            @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Kontrak', 'internship' => 'Magang'] as $value => $label)
                                <label class="flex items-center gap-3 cursor-pointer min-h-[2.75rem] px-3 py-2 rounded-xl hover:bg-teal-50/40 transition-colors duration-200">
                                    <input
                                        type="checkbox"
                                        name="employment_type[]"
                                        value="{{ $value }}"
                                        @checked(in_array($value, $filters->employmentType ?? []))
                                        class="w-4 h-4 rounded-md border-slate-300 text-teal-600 focus:ring-teal-500 focus:ring-offset-0"
                                    >
                                    <span class="text-sm text-slate-700 font-medium">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Apply Filter --}}
                    <x-button type="submit" class="w-full">
                        Terapkan Filter
                    </x-button>
                </form>
            </aside>

            {{-- Job Cards Grid --}}
            <div class="flex-1">
                @if($jobs->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5" data-reveal-stagger>
                        @foreach($jobs as $job)
                            <x-job-card :job="$job" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        {{ $jobs->withQueryString()->links() }}
                    </div>
                @else
                    <div class="glass-prominent rounded-3xl text-center py-20 px-8">
                        <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-br from-slate-100 to-teal-50 flex items-center justify-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="font-[family-name:var(--font-display)] text-xl font-bold text-slate-700 mb-2">
                            Tidak ada hasil ditemukan
                        </h3>
                        <p class="text-sm text-slate-500 mb-6 max-w-sm mx-auto">
                            Coba ubah kata kunci atau hapus beberapa filter untuk memperluas pencarian Anda.
                        </p>
                        <x-button variant="secondary" :href="route('jobs.index')">
                            Hapus Semua Filter
                        </x-button>
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-layout>
