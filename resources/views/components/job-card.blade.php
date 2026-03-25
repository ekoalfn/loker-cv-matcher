@props([
    'job',
])

@php
    $avatarColors = [
        'from-teal-500 to-cyan-500',
        'from-blue-500 to-cyan-500',
        'from-emerald-500 to-teal-500',
        'from-amber-500 to-orange-500',
        'from-rose-500 to-pink-500',
        'from-cyan-500 to-teal-600',
    ];
    $colorIndex = crc32($job->company ?? '') % count($avatarColors);
    $avatarGradient = $avatarColors[$colorIndex];
    $companyInitial = strtoupper(mb_substr($job->company ?? '?', 0, 1));
@endphp

<article class="glass rounded-2xl card-hover card-shine accent-top flex flex-col reveal group">
    {{-- Card body --}}
    <a href="{{ route('jobs.show', $job) }}" class="block p-5 pb-4 flex-1 interactive-focus rounded-t-2xl">

        {{-- Top row: Avatar + Company + Time --}}
        <div class="flex items-center justify-between gap-3 mb-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $avatarGradient }} flex items-center justify-center shadow-sm shrink-0
                            transition-transform duration-300 group-hover:scale-105 group-hover:shadow-md">
                    <span class="text-white font-bold text-sm">{{ $companyInitial }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-700 truncate">{{ $job->company }}</p>
                    <time class="text-[0.6875rem] text-slate-400 font-medium tracking-wide" datetime="{{ $job->created_at->toISOString() }}">
                        {{ $job->created_at->diffForHumans() }}
                    </time>
                </div>
            </div>

            @if($job->employment_type)
                <x-badge :text="employment_label($job->employment_type)" color="blue" class="shrink-0" />
            @endif
        </div>

        {{-- Job title --}}
        <h3 class="text-base font-bold text-slate-900 leading-snug mb-2 group-hover:text-teal-700 transition-colors duration-200 line-clamp-2">
            {{ $job->title }}
        </h3>

        {{-- Location --}}
        @if($job->location)
            <div class="flex items-center gap-1.5 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-[0.8125rem] text-slate-500">{{ $job->location }}</span>
            </div>
        @endif

        {{-- AI Summary preview --}}
        @if($job->summary_ai)
            <p class="text-[0.8125rem] text-slate-500 line-clamp-2 leading-relaxed">
                {{ $job->summary_ai }}
            </p>
        @endif
    </a>

    {{-- Card footer: Salary + Tags --}}
    <div class="px-5 py-3.5 border-t border-slate-100/60 flex items-center justify-between gap-3">
        @if($job->salary_min || $job->salary_max)
            <span class="text-[0.8125rem] font-bold text-emerald-600">
                @if($job->salary_min && $job->salary_max)
                    Rp {{ number_format($job->salary_min / 1000000, 0) }}-{{ number_format($job->salary_max / 1000000, 0) }} jt
                @elseif($job->salary_min)
                    Rp {{ number_format($job->salary_min / 1000000, 0) }} jt+
                @else
                    s/d Rp {{ number_format($job->salary_max / 1000000, 0) }} jt
                @endif
            </span>
        @else
            <span class="text-[0.6875rem] text-slate-400 font-medium">Gaji tidak dicantumkan</span>
        @endif

        @if(!empty($job->tags))
            <div class="flex items-center gap-1 shrink-0">
                @foreach(array_slice($job->tags, 0, 2) as $tag)
                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[0.625rem] font-semibold bg-teal-50/70 text-teal-600 border border-teal-100/50">
                        {{ $tag }}
                    </span>
                @endforeach
                @if(count($job->tags) > 2)
                    <span class="text-[0.625rem] text-slate-400 font-semibold">+{{ count($job->tags) - 2 }}</span>
                @endif
            </div>
        @endif
    </div>
</article>
