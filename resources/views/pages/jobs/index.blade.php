<x-layout title="Cari Lowongan Kerja - Portal Loker">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10" x-data="{ showFilters: false }">

        {{-- Search Bar (sticky on mobile) --}}
        <div class="sticky top-16 z-40 bg-white py-3 -mx-4 px-4 sm:mx-0 sm:px-0 md:relative md:top-auto md:z-auto md:py-0 md:mb-6">
            <x-search-bar :keyword="$filters->keyword ?? ''" :action="route('jobs.index')" />
        </div>

        {{-- Results Count + Filter Toggle --}}
        <div class="flex items-center justify-between mt-4 mb-4 md:mb-6">
            <p class="text-sm text-gray-600">
                <span class="font-semibold text-gray-900">{{ $jobs->total() }}</span> lowongan ditemukan
            </p>
            <button
                @click="showFilters = !showFilters"
                class="md:hidden inline-flex items-center gap-1.5 min-h-[2.75rem] px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
        </div>

        <div class="flex flex-col md:flex-row gap-6">

            {{-- Filter Sidebar --}}
            <aside
                x-show="showFilters"
                x-cloak
                x-transition
                class="md:!block md:w-64 md:shrink-0"
                :class="{ 'block': showFilters, 'hidden': !showFilters }"
            >
                <form action="{{ route('jobs.index') }}" method="GET" class="bg-white border border-gray-200 rounded-xl p-5 space-y-5">

                    @if($filters->keyword ?? false)
                        <input type="hidden" name="keyword" value="{{ $filters->keyword }}">
                    @endif

                    {{-- Lokasi --}}
                    <div>
                        <label for="filter-location" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Lokasi
                        </label>
                        <select
                            id="filter-location"
                            name="location"
                            class="w-full min-h-[2.75rem] px-3 py-2 rounded-lg border border-gray-300 bg-white text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Tipe Kerja
                        </label>
                        <div class="space-y-2">
                            @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Kontrak', 'internship' => 'Magang'] as $value => $label)
                                <label class="flex items-center gap-2 cursor-pointer min-h-[2.75rem] px-2">
                                    <input
                                        type="checkbox"
                                        name="employment_type[]"
                                        value="{{ $value }}"
                                        @checked(in_array($value, $filters->employmentType ?? []))
                                        class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    >
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($jobs as $job)
                            <x-job-card :job="$job" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $jobs->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-16 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-lg font-medium">Tidak ada lowongan ditemukan</p>
                        <p class="mt-1">Coba ubah kata kunci atau filter pencarian Anda.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-layout>
