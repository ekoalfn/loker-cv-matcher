@props(['job'])

@php
    $gradients = [
        'from-slate-700 to-slate-900',
        'from-indigo-600 to-blue-700',
        'from-violet-600 to-purple-700',
        'from-amber-600 to-orange-700',
        'from-rose-600 to-pink-700',
        'from-cyan-600 to-blue-700',
    ];
    $colorIndex    = crc32($job->company ?? '') % count($gradients);
    $gradient      = $gradients[$colorIndex];
    $companyInitial = strtoupper(mb_substr($job->company ?? '?', 0, 1));
    $isNew         = $job->created_at->isToday() || $job->created_at->isYesterday();
    
    // Format salary
    $salaryText = null;
    if ($job->salary_min || $job->salary_max) {
        if ($job->salary_min && $job->salary_max) {
            if ($job->salary_min >= 1000000) {
                $salaryText = number_format($job->salary_min / 1000000, 0) . '–' . number_format($job->salary_max / 1000000, 0) . 'M IDR';
            } else {
                $salaryText = 'Rp ' . number_format($job->salary_min, 0, ',', '.') . ' - ' . number_format($job->salary_max, 0, ',', '.');
            }
        } elseif ($job->salary_min) {
            $salaryText = number_format($job->salary_min / 1000000, 0) . 'M+ IDR';
        } else {
            $salaryText = 'Up to ' . number_format($job->salary_max / 1000000, 0) . 'M IDR';
        }
    }
@endphp

<article class="bg-white rounded-xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-200 group">
    <a href="{{ route('jobs.show', $job) }}" class="block p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
            {{-- Company Logo --}}
            @if($job->company_logo)
                <img src="{{ $job->company_logo }}"
                     alt="{{ $job->company }}"
                     width="64"
                     height="64"
                     loading="lazy"
                     decoding="async"
                     referrerpolicy="no-referrer"
                     class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl object-contain shrink-0 border border-slate-200 bg-white p-1"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-gradient-to-br {{ $gradient }} items-center justify-center shrink-0 shadow-sm hidden">
                    <span class="text-white font-bold text-xl">{{ $companyInitial }}</span>
                </div>
            @else
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center shrink-0 shadow-sm">
                    <span class="text-white font-bold text-xl">{{ $companyInitial }}</span>
                </div>
            @endif

            {{-- Job Info --}}
            <div class="flex-1 min-w-0">
                {{-- Title & Salary --}}
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 sm:gap-3 mb-1">
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors leading-snug break-words">
                        {{ $job->title }}
                    </h3>
                    @if($salaryText)
                        <span class="inline-flex items-center self-start px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-xs sm:text-sm font-semibold border border-emerald-200 whitespace-nowrap shrink-0">
                            {{ $salaryText }}
                        </span>
                    @endif
                </div>

                {{-- Company & Location --}}
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm text-slate-600 mb-3">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="font-medium">{{ $job->company }}</span>
                        @if($job->company)
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                    @if($job->location)
                        <div class="flex items-center gap-1 text-slate-500">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="break-words">{{ $job->location }}</span>
                        </div>
                    @endif
                    @if($job->employment_type)
                        <div class="flex items-center gap-1 text-slate-500">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ employment_label($job->employment_type) }}
                        </div>
                    @endif
                </div>

                {{-- AI Summary --}}
                @if($job->ai_summary)
                    <div class="mb-4 flex items-start gap-2 text-sm">
                        <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 7H7v6h6V7z"/>
                            <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-slate-600 leading-relaxed">
                            <span class="font-semibold text-emerald-600">AI Summary:</span> {{ Str::limit($job->ai_summary, 150) }}
                        </span>
                    </div>
                @endif

                {{-- Tags --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if(!empty($job->tags))
                        @foreach(array_slice($job->tags, 0, 4) as $tag)
                            <span class="inline-flex items-center px-3 py-1 bg-slate-50 text-slate-700 rounded-lg text-xs font-medium border border-slate-200">
                                {{ $tag }}
                            </span>
                        @endforeach
                        @if(count($job->tags) > 4)
                            <span class="inline-flex items-center px-3 py-1 bg-slate-50 text-slate-500 rounded-lg text-xs font-medium border border-slate-200">
                                +{{ count($job->tags) - 4 }}
                            </span>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Bookmark Button --}}
            <button class="hidden sm:inline-flex p-2 hover:bg-slate-50 rounded-lg transition-colors shrink-0 group/bookmark" aria-label="Simpan lowongan">
                <svg class="w-6 h-6 text-slate-400 group-hover/bookmark:text-slate-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
            </button>
        </div>
    </a>
</article>
