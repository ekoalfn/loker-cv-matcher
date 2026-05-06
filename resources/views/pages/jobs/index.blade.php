@php
    $pageTitle = 'Cari Lowongan Kerja Terbaru';
    if ($filters->keyword ?? false) {
        $pageTitle = 'Lowongan ' . ucfirst($filters->keyword);
    }
    if ($filters->location ?? false) {
        $locationStr = is_array($filters->location) ? implode(', ', $filters->location) : $filters->location;
        $pageTitle .= ' di ' . $locationStr;
    }
    $pageTitle .= ' - Lamaraja';

    // Prevent Google from indexing filtered/paginated pages to save crawl budget
    $hasFilters = ($filters->keyword ?? false) || ($filters->location ?? false) || !empty($filters->employmentType ?? []);
    $isPaginated = request()->has('page') && request()->get('page') > 1;
    $shouldNoindex = $hasFilters || $isPaginated;
    $robotsMeta = $shouldNoindex ? 'noindex, follow' : 'index, follow';
    $canonicalUrl = route('jobs.index');
@endphp
<x-layout :title="$pageTitle" :robots="$robotsMeta" :canonical="$canonicalUrl">

    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex-1">
                    <h1 class="font-[family-name:var(--font-display)] text-4xl md:text-5xl font-bold text-slate-900 mb-3">
                        All <span class="text-emerald-600">Jobs</span>
                    </h1>
                    <p class="text-slate-600 text-base md:text-lg max-w-2xl">
                        Discover jobs from top companies and multiple sources.<br>
                        AI summaries help you understand each opportunity in seconds.
                    </p>
                </div>
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-2xl bg-white/80 backdrop-blur-sm border border-emerald-100 shadow-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -left-1 w-6 h-6 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4">
                <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            type="text"
                            name="keyword"
                            value="{{ $filters->keyword ?? '' }}"
                            placeholder="Job title, keyword or skill"
                            class="w-full h-12 pl-12 pr-4 rounded-xl border-2 border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all outline-none"
                        >
                    </div>
                    <div class="flex-1 relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input
                            type="text"
                            name="location"
                            value="{{ is_array($filters->location ?? null) ? implode(', ', $filters->location) : ($filters->location ?? '') }}"
                            placeholder="Location"
                            class="w-full h-12 pl-12 pr-4 rounded-xl border-2 border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all outline-none"
                        >
                    </div>
                    <select
                        name="employment_type[]"
                        class="md:w-48 h-12 px-4 rounded-xl border-2 border-slate-200 bg-white text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all outline-none"
                    >
                        <option value="">Job type</option>
                        <option value="full-time" @selected(in_array('full-time', $filters->employmentType ?? []))>Full-time</option>
                        <option value="part-time" @selected(in_array('part-time', $filters->employmentType ?? []))>Part-time</option>
                        <option value="contract" @selected(in_array('contract', $filters->employmentType ?? []))>Contract</option>
                        <option value="internship" @selected(in_array('internship', $filters->employmentType ?? []))>Internship</option>
                        <option value="remote" @selected(in_array('remote', $filters->employmentType ?? []))>Remote</option>
                    </select>
                    <button
                        type="submit"
                        class="h-12 px-8 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all shadow-md hover:shadow-lg active:scale-[0.98]"
                    >
                        Search Jobs
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        {{-- Results header --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-slate-600">
                Showing <span class="font-semibold text-slate-900">{{ $jobs->firstItem() ?? 0 }}–{{ $jobs->lastItem() ?? 0 }}</span> of <span class="font-semibold text-slate-900">{{ $jobs->total() }}</span> jobs
            </p>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-600">Sort by:</span>
                <select class="h-10 px-4 pr-10 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all outline-none">
                    <option>Newest</option>
                    <option>Oldest</option>
                    <option>Most Relevant</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            {{-- Job List --}}
            <div class="flex-1">
                @if($jobs->count() > 0)
                    <div class="space-y-4">
                        @foreach($jobs as $job)
                            <x-job-card :job="$job" />
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $jobs->withQueryString()->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-slate-200 text-center py-16 px-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <h2 class="font-[family-name:var(--font-display)] text-xl font-bold text-slate-900 mb-2">No jobs found</h2>
                        <p class="text-sm text-slate-500 mb-6">Try adjusting your search or filters to find what you're looking for.</p>
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </div>

            {{-- Filters Sidebar --}}
            <aside class="lg:w-80 shrink-0">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-bold text-slate-900 text-lg">Filters</h2>
                        <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                            Reset all
                        </a>
                    </div>

                    <form action="{{ route('jobs.index') }}" method="GET" class="space-y-6" x-data="{ searchLocation: '' }">
                        @if($filters->keyword ?? false)
                            <input type="hidden" name="keyword" value="{{ $filters->keyword }}">
                        @endif

                        {{-- Job Type --}}
                        <div x-data="{ open: true }">
                            <button @click="open = !open" type="button" class="w-full flex items-center justify-between text-left mb-4">
                                <h3 class="font-semibold text-slate-900 text-base">Job Type</h3>
                                <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="space-y-3">
                                @foreach(['full-time' => 'Full-time', 'part-time' => 'Part-time', 'contract' => 'Contract', 'internship' => 'Internship'] as $value => $label)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input
                                            type="checkbox"
                                            name="employment_type[]"
                                            value="{{ $value }}"
                                            @checked(in_array($value, $filters->employmentType ?? []))
                                            class="w-5 h-5 rounded border-2 border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-0 transition-colors checked:bg-emerald-600 checked:border-emerald-600"
                                        >
                                        <span class="text-sm text-slate-700 group-hover:text-slate-900 transition-colors">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Location --}}
                        <div x-data="{ open: true, showAll: false }" class="border-t border-slate-100 pt-6">
                            <button @click="open = !open" type="button" class="w-full flex items-center justify-between text-left mb-4">
                                <h3 class="font-semibold text-slate-900 text-base">Location</h3>
                                <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse>
                                <div class="relative mb-4">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input
                                        type="text"
                                        placeholder="Search location"
                                        x-model="searchLocation"
                                        class="w-full h-10 pl-10 pr-4 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all outline-none"
                                    >
                                </div>
                                <div class="space-y-3 max-h-64 overflow-y-auto">
                                    {{-- Remote --}}
                                    <label class="location-item flex items-center gap-3 cursor-pointer group" 
                                           x-show="searchLocation === '' || 'remote'.includes(searchLocation.toLowerCase())">
                                        <input
                                            type="checkbox"
                                            name="location[]"
                                            value="Remote"
                                            @checked(in_array('Remote', $filters->location ?? []))
                                            class="w-5 h-5 rounded border-2 border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-0 transition-colors checked:bg-emerald-600 checked:border-emerald-600"
                                        >
                                        <span class="text-sm text-slate-700 group-hover:text-slate-900 transition-colors">Remote</span>
                                    </label>
                                    
                                    {{-- Location List --}}
                                    @foreach($locations as $index => $location)
                                        <label class="location-item flex items-center gap-3 cursor-pointer group" 
                                               x-show="(showAll || {{ $index }} < 4) && (searchLocation === '' || '{{ strtolower($location) }}'.includes(searchLocation.toLowerCase()))">
                                            <input
                                                type="checkbox"
                                                name="location[]"
                                                value="{{ $location }}"
                                                @checked(in_array($location, $filters->location ?? []))
                                                class="w-5 h-5 rounded border-2 border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-0 transition-colors checked:bg-emerald-600 checked:border-emerald-600"
                                            >
                                            <span class="text-sm text-slate-700 group-hover:text-slate-900 transition-colors">{{ $location }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @if($locations->count() > 4)
                                    <button 
                                        type="button" 
                                        @click="showAll = !showAll" 
                                        class="mt-4 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors"
                                        x-text="showAll ? 'Show less' : 'Show more'"
                                    >
                                        Show more
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Experience Level --}}
                        <div x-data="{ open: true }" class="border-t border-slate-100 pt-6">
                            <button @click="open = !open" type="button" class="w-full flex items-center justify-between text-left mb-4">
                                <h3 class="font-semibold text-slate-900 text-base">Experience Level</h3>
                                <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="space-y-3">
                                @foreach(['entry' => 'Entry Level', 'mid' => 'Mid Level', 'senior' => 'Senior Level', 'lead' => 'Lead', 'manager' => 'Manager'] as $value => $label)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input
                                            type="checkbox"
                                            name="experience_level[]"
                                            value="{{ $value }}"
                                            class="w-5 h-5 rounded border-2 border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-0 transition-colors checked:bg-emerald-600 checked:border-emerald-600"
                                        >
                                        <span class="text-sm text-slate-700 group-hover:text-slate-900 transition-colors">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-6">
                            <button
                                type="submit"
                                class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md active:scale-[0.98]"
                            >
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </aside>
        </div>
    </div>

</x-layout>
