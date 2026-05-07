<x-layout title="Scraper Dashboard - Admin" robots="noindex, nofollow">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8 animate-fade-up">
        <div>
            <h1 class="font-[family-name:var(--font-display)] text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">
                Scraper Dashboard
            </h1>
            <p class="text-sm text-stone-500 mt-1">Monitoring data lowongan kerja & sumber scraping</p>
        </div>
        <form action="{{ route('scraper.logout') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 min-h-[2.5rem] px-4 py-2 rounded-xl text-sm font-medium text-stone-600 hover:text-red-600 hover:bg-red-50 transition-colors interactive-focus" style="background: #f5f3f0; border: 1px solid #eae7e3;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Logout
            </button>
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-8 animate-fade-up delay-100">
        @php
            $stats = [
                ['label' => 'Total Loker', 'value' => number_format($totalJobs), 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'teal'],
                ['label' => 'Aktif', 'value' => number_format($activeJobs), 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green'],
                ['label' => 'Nonaktif', 'value' => number_format($inactiveJobs), 'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636', 'color' => 'amber'],
                ['label' => 'Dihapus', 'value' => number_format($deletedJobs), 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16', 'color' => 'red'],
                ['label' => 'Hari Ini', 'value' => number_format($jobsToday), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'blue'],
                ['label' => 'Minggu Ini', 'value' => number_format($jobsThisWeek), 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'indigo'],
            ];
            $colorMap = [
                'teal' => ['bg' => 'rgba(13,148,136,0.10)', 'text' => '#0d9488', 'icon_bg' => 'rgba(13,148,136,0.15)'],
                'green' => ['bg' => 'rgba(5,150,105,0.10)', 'text' => '#059669', 'icon_bg' => 'rgba(5,150,105,0.15)'],
                'amber' => ['bg' => 'rgba(217,119,6,0.10)', 'text' => '#d97706', 'icon_bg' => 'rgba(217,119,6,0.15)'],
                'red' => ['bg' => 'rgba(220,38,38,0.10)', 'text' => '#dc2626', 'icon_bg' => 'rgba(220,38,38,0.15)'],
                'blue' => ['bg' => 'rgba(37,99,235,0.10)', 'text' => '#2563eb', 'icon_bg' => 'rgba(37,99,235,0.15)'],
                'indigo' => ['bg' => 'rgba(79,70,229,0.10)', 'text' => '#4f46e5', 'icon_bg' => 'rgba(79,70,229,0.15)'],
            ];
        @endphp
        @foreach($stats as $stat)
            @php $c = $colorMap[$stat['color']]; @endphp
            <div class="surface rounded-2xl p-4 card-hover">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background: {{ $c['icon_bg'] }};">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" style="color: {{ $c['text'] }}; width:18px; height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" /></svg>
                    </div>
                </div>
                <p class="font-[family-name:var(--font-display)] text-2xl font-extrabold tracking-tight" style="color: {{ $c['text'] }}">{{ $stat['value'] }}</p>
                <p class="stat-label mt-0.5">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Scraping Tools --}}
    @include('pages.scraper._tools')

    {{-- Queue Status Monitoring --}}
    @include('pages.scraper._queue_status')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- Chart: Jobs Per Day --}}
        <div class="lg:col-span-2 surface rounded-2xl p-5 animate-fade-up delay-200">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Loker Baru per Hari (14 hari terakhir)</h2>
            <div class="h-48 flex items-end gap-1.5">
                @php $maxCount = $jobsPerDay->max('count') ?: 1; @endphp
                @foreach($jobsPerDay as $day)
                    @php $pct = ($day->count / $maxCount) * 100; @endphp
                    <div class="flex-1 flex flex-col items-center gap-1 group" title="{{ $day->date }}: {{ $day->count }} loker">
                        <span class="text-xs font-semibold text-teal-700 opacity-0 group-hover:opacity-100 transition-opacity">{{ $day->count }}</span>
                        <div class="w-full rounded-t-lg transition-all duration-300 group-hover:opacity-80"
                             style="height: {{ max($pct, 4) }}%; background: linear-gradient(to top, #0d9488, #14b8a6); min-height: 4px;"></div>
                        <span class="text-[10px] text-stone-400 truncate w-full text-center">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</span>
                    </div>
                @endforeach
                @if($jobsPerDay->isEmpty())
                    <div class="flex-1 flex items-center justify-center text-sm text-stone-400">Belum ada data</div>
                @endif
            </div>
        </div>

        {{-- Top Locations --}}
        <div class="surface rounded-2xl p-5 animate-fade-up delay-200">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Top Lokasi</h2>
            <div class="space-y-2.5">
                @forelse($topLocations as $loc)
                    @php $locPct = ($loc->count / ($topLocations->first()->count ?: 1)) * 100; @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-stone-600 truncate mr-2">{{ $loc->location }}</span>
                            <span class="text-stone-400 text-xs font-medium shrink-0">{{ $loc->count }}</span>
                        </div>
                        <div class="h-1.5 rounded-full" style="background: #f0eeeb;">
                            <div class="h-full rounded-full transition-all duration-500" style="width: {{ $locPct }}%; background: linear-gradient(to right, #0d9488, #14b8a6);"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-stone-400">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Sources --}}
        <div class="surface rounded-2xl p-5 animate-fade-up delay-300">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Sumber Scraping</h2>
            @if($sources->isEmpty())
                <p class="text-sm text-stone-400">Belum ada sumber terdaftar.</p>
            @else
                <div class="space-y-3">
                    @foreach($sources as $source)
                        <div class="rounded-xl p-3.5" style="background: #f5f3f0; border: 1px solid #eae7e3;">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-semibold text-slate-700">{{ $source->name }}</span>
                                <x-badge :text="$source->is_active ? 'Aktif' : 'Nonaktif'" :color="$source->is_active ? 'green' : 'red'" />
                            </div>
                            <div class="flex items-center gap-4 text-xs text-stone-500">
                                <span>{{ $source->jobs_count }} loker</span>
                                <span>{{ $source->active_jobs_count }} aktif</span>
                                @if($source->last_scraped_at)
                                    <span>Scrape: {{ $source->last_scraped_at->diffForHumans() }}</span>
                                @endif
                            </div>
                            @if($source->base_url)
                                <p class="text-xs text-stone-400 mt-1 truncate">{{ $source->base_url }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Employment Types --}}
        <div class="surface rounded-2xl p-5 animate-fade-up delay-300">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Distribusi Tipe Kerja</h2>
            @php
                $typeColors = [
                    'full-time' => '#0d9488',
                    'part-time' => '#2563eb',
                    'contract' => '#d97706',
                    'internship' => '#7c3aed',
                    'freelance' => '#059669',
                ];
                $typeLabels = [
                    'full-time' => 'Full Time',
                    'part-time' => 'Part Time',
                    'contract' => 'Kontrak',
                    'internship' => 'Magang',
                    'freelance' => 'Freelance',
                ];
                $totalEmpType = $employmentTypes->sum('count') ?: 1;
            @endphp
            @forelse($employmentTypes as $et)
                @php
                    $etKey = is_object($et->employment_type) ? $et->employment_type->value : $et->employment_type;
                    $etColor = $typeColors[$etKey] ?? '#78716c';
                    $etLabel = $typeLabels[$etKey] ?? ucfirst($etKey);
                    $etPct = round(($et->count / $totalEmpType) * 100);
                @endphp
                <div class="mb-3">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-stone-600">{{ $etLabel }}</span>
                        <span class="text-xs font-medium" style="color: {{ $etColor }}">{{ $et->count }} ({{ $etPct }}%)</span>
                    </div>
                    <div class="h-2 rounded-full" style="background: #f0eeeb;">
                        <div class="h-full rounded-full transition-all duration-500" style="width: {{ $etPct }}%; background: {{ $etColor }};"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-stone-400">Belum ada data</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Jobs --}}
    <div class="surface rounded-2xl p-5 animate-fade-up delay-400">
        <h2 class="text-sm font-semibold text-slate-700 mb-4">25 Loker Terbaru</h2>
        <div class="overflow-x-auto -mx-5 px-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-stone-400 uppercase tracking-wider">
                        <th class="pb-3 pr-4 font-semibold">Posisi</th>
                        <th class="pb-3 pr-4 font-semibold">Perusahaan</th>
                        <th class="pb-3 pr-4 font-semibold hidden md:table-cell">Lokasi</th>
                        <th class="pb-3 pr-4 font-semibold hidden lg:table-cell">Sumber</th>
                        <th class="pb-3 pr-4 font-semibold hidden sm:table-cell">Tipe</th>
                        <th class="pb-3 font-semibold">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: #eae7e3;">
                    @forelse($recentJobs as $job)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-3">
                                    @if($job->company_logo)
                                        <img src="{{ $job->company_logo }}"
                                             alt="{{ $job->company }}"
                                             class="w-8 h-8 rounded-lg object-contain bg-white border border-slate-200 p-0.5 shrink-0"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 items-center justify-center shrink-0 hidden">
                                            <span class="text-slate-500 font-bold text-xs">{{ strtoupper(mb_substr($job->company ?? '?', 0, 1)) }}</span>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                                            <span class="text-slate-500 font-bold text-xs">{{ strtoupper(mb_substr($job->company ?? '?', 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="text-slate-700 font-medium hover:text-teal-600 transition-colors truncate-safe block max-w-[160px] lg:max-w-[240px]" target="_blank">
                                        {{ $job->title }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-3 pr-4 text-stone-500 truncate-safe max-w-[140px]">{{ $job->company }}</td>
                            <td class="py-3 pr-4 text-stone-500 hidden md:table-cell truncate-safe max-w-[120px]">{{ $job->location ?? '-' }}</td>
                            <td class="py-3 pr-4 hidden lg:table-cell">
                                @if($job->source)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium badge-teal">{{ $job->source->name }}</span>
                                @else
                                    <span class="text-stone-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 pr-4 hidden sm:table-cell">
                                @if($job->employment_type)
                                    <x-badge :text="is_object($job->employment_type) ? ($typeLabels[$job->employment_type->value] ?? $job->employment_type->value) : ($typeLabels[$job->employment_type] ?? $job->employment_type)" color="blue" />
                                @else
                                    <span class="text-stone-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 text-stone-400 text-xs whitespace-nowrap">{{ $job->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-stone-400">Belum ada loker.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-layout>
