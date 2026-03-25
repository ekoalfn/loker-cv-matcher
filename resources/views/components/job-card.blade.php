@props(['job'])

@php
    $gradients = [
        'from-teal-500 to-cyan-600',
        'from-indigo-500 to-blue-600',
        'from-emerald-500 to-teal-600',
        'from-amber-500 to-orange-600',
        'from-rose-500 to-pink-600',
        'from-violet-500 to-purple-600',
    ];
    $colorIndex    = crc32($job->company ?? '') % count($gradients);
    $gradient      = $gradients[$colorIndex];
    $companyInitial = strtoupper(mb_substr($job->company ?? '?', 0, 1));
    $isNew         = $job->created_at->isToday() || $job->created_at->isYesterday();
@endphp

<article class="surface rounded-2xl card-hover glass-shimmer reveal group">
    <a href="{{ route('jobs.show', $job) }}" class="flex items-start gap-4 p-4 md:p-5 interactive-focus rounded-2xl">

        {{-- Company avatar --}}
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center shrink-0"
             style="box-shadow: 0 4px 12px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.20);">
            <span class="text-white font-bold text-sm">{{ $companyInitial }}</span>
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <h3 class="text-[0.9375rem] font-semibold text-white leading-snug group-hover:text-teal-300 transition-colors truncate">
                        {{ $job->title }}
                    </h3>
                    <p class="text-sm text-white/55 mt-0.5">
                        {{ $job->company }}
                        @if($job->location)
                            <span class="text-white/20 mx-1">&middot;</span>
                            {{ $job->location }}
                        @endif
                    </p>
                </div>

                @if($job->salary_min || $job->salary_max)
                    <span class="badge-green inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold whitespace-nowrap shrink-0">
                        @if($job->salary_min && $job->salary_max)
                            @if($job->salary_min >= 1000000)
                                {{ number_format($job->salary_min / 1000000, 1) }}–{{ number_format($job->salary_max / 1000000, 1) }} jt
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

            <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                @if($isNew)
                    <span class="badge-new inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold">Baru</span>
                @endif

                @if($job->employment_type)
                    <span class="badge-teal inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium">
                        {{ employment_label($job->employment_type) }}
                    </span>
                @endif

                @if(!empty($job->tags))
                    @foreach(array_slice($job->tags, 0, 2) as $tag)
                        <span class="badge-gray inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium">{{ $tag }}</span>
                    @endforeach
                @endif

                <time class="text-xs text-white/35 ml-auto" datetime="{{ $job->created_at->toISOString() }}">
                    {{ $job->created_at->diffForHumans() }}
                </time>
            </div>
        </div>
    </a>
</article>
