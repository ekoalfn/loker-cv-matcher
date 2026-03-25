@props([
    'job',
])

@php
    $avatarColors = [
        'bg-teal-600', 'bg-blue-600', 'bg-emerald-600',
        'bg-amber-600', 'bg-rose-600', 'bg-cyan-600',
    ];
    $colorIndex = crc32($job->company ?? '') % count($avatarColors);
    $avatarBg = $avatarColors[$colorIndex];
    $companyInitial = strtoupper(mb_substr($job->company ?? '?', 0, 1));
@endphp

<article class="surface rounded-xl card-hover reveal group">
    <a href="{{ route('jobs.show', $job) }}" class="flex items-start gap-4 p-4 md:p-5 interactive-focus rounded-xl">

        {{-- Company Avatar --}}
        <div class="w-10 h-10 rounded-lg {{ $avatarBg }} flex items-center justify-center shrink-0">
            <span class="text-white font-bold text-sm">{{ $companyInitial }}</span>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <h3 class="text-[0.9375rem] font-semibold text-slate-900 leading-snug group-hover:text-teal-700 transition-colors truncate">
                        {{ $job->title }}
                    </h3>
                    <p class="text-sm text-slate-500 mt-0.5">
                        {{ $job->company }}
                        @if($job->location)
                            <span class="text-slate-300 mx-1">&middot;</span>
                            {{ $job->location }}
                        @endif
                    </p>
                </div>

                {{-- Salary (right-aligned) --}}
                @if($job->salary_min || $job->salary_max)
                    <span class="text-sm font-semibold text-emerald-600 whitespace-nowrap shrink-0">
                        @if($job->salary_min && $job->salary_max)
                            @if($job->salary_min >= 1000000)
                                {{ number_format($job->salary_min / 1000000, 1) }}-{{ number_format($job->salary_max / 1000000, 1) }} jt
                            @else
                                Rp {{ number_format($job->salary_min, 0, ',', '.') }}
                            @endif
                        @elseif($job->salary_min)
                            {{ number_format($job->salary_min / 1000000, 1) }} jt+
                        @else
                            s/d {{ number_format($job->salary_max / 1000000, 1) }} jt
                        @endif
                    </span>
                @endif
            </div>

            {{-- Meta row --}}
            <div class="mt-2 flex flex-wrap items-center gap-2">
                @if($job->employment_type)
                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium bg-teal-50 text-teal-700 border border-teal-100">
                        {{ employment_label($job->employment_type) }}
                    </span>
                @endif

                @if(!empty($job->tags))
                    @foreach(array_slice($job->tags, 0, 2) as $tag)
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium bg-slate-50 text-slate-600 border border-slate-100">
                            {{ $tag }}
                        </span>
                    @endforeach
                @endif

                <time class="text-xs text-slate-400 ml-auto" datetime="{{ $job->created_at->toISOString() }}">
                    {{ $job->created_at->diffForHumans() }}
                </time>
            </div>
        </div>
    </a>
</article>
