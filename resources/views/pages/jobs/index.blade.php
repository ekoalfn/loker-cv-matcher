<x-layout title="Cari Lowongan Kerja - Portal Loker">

    {{-- Page Header --}}
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/5 via-violet-600/5 to-transparent"></div>
        <div class="blob w-64 h-64 bg-indigo-200/20 top-[-20%] right-[-5%] animate-float-slow"></div>
        <div class="blob w-48 h-48 bg-violet-200/15 bottom-[-10%] left-[-3%] animate-float" style="animation-delay: -2s;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10" x-data="{ showFilters: false }">

        {{-- Search Bar --}}
        <div class="sticky top-16 z-40 py-3 -mx-4 px-4 sm:mx-0 sm:px-0 md:relative md:top-auto md:z-auto md:py-0 md:mb-8">
            <x-search-bar :keyword="$filters->keyword ?? ''" :action="route('jobs.index')" />
        </div>

        {{-- Results Count + Filter Toggle --}}
        <div class="flex items-center justify-between mt-4 mb-5 md:mb-8">
            <p class="text-sm text-slate-500 font-medium">
                <span class="text-lg font-bold text-gradient">{{ $jobs->total() }}</span>
                <span class="ml-1">lowongan ditemukan</span>
            </p>
            <button
                @click="showFilters = !showFilters"
                class="md:hidden inline-flex items-center gap-2 min-h-[2.75rem] px-4 py-2 text-sm font-semibold text-indigo-600 glass rounded-xl hover:bg-white/70 transition-all duration-200 cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
        </div>

        <div class="flex flex-col md:flex-row gap-6 md:gap-8">

            {{-- Filter Sidebar — Glass --}}
            <aside
                x-show="showFilters"
                x-cloak
                x-transition
                class="md:!block md:w-72 md:shrink-0"
                :class="{ 'block': showFilters, 'hidden': !showFilters }"
            >
                <form action="{{ route('jobs.index') }}" method="GET" class="glass rounded-2xl p-6 space-y-6 sticky top-24">

                    @if($filters->keyword ?? false)
                        <input type="hidden" name="keyword" value="{{ $filters->keyword }}">
                    @endif

                    {{-- Section Title --}}
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center">
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
                                <label class="flex items-center gap-3 cursor-pointer min-h-[2.75rem] px-3 py-2 rounded-xl hover:bg-indigo-50/40 transition-colors duration-200">
                                    <input
                                        type="checkbox"
                                        name="employment_type[]"
                                        value="{{ $value }}"
                                        @checked(in_array($value, $filters->employmentType ?? []))
                                        class="w-4 h-4 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0"
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
                    <div class="glass rounded-3xl text-center py-20 px-6">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-slate-100 to-indigo-100 flex items-center justify-center mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-lg font-bold text-slate-700">Tidak ada lowongan ditemukan</p>
                        <p class="mt-2 text-slate-500">Coba ubah kata kunci atau filter pencarian Anda.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-layout>
