@props([
    'job',
])

@php
    $employmentLabels = [
        'full-time' => 'Full Time',
        'part-time' => 'Part Time',
        'contract' => 'Kontrak',
        'freelance' => 'Freelance',
        'internship' => 'Magang',
    ];

    // Generate a consistent color for company avatar based on company name
    $avatarColors = [
        'from-indigo-500 to-violet-500',
        'from-blue-500 to-cyan-500',
        'from-emerald-500 to-teal-500',
        'from-amber-500 to-orange-500',
        'from-rose-500 to-pink-500',
        'from-violet-500 to-purple-500',
    ];
    $colorIndex = crc32($job->company ?? '') % count($avatarColors);
    $avatarGradient = $avatarColors[$colorIndex];
    $companyInitial = strtoupper(mb_substr($job->company ?? '?', 0, 1));
@endphp

<article class="glass rounded-2xl p-6 card-hover flex flex-col gap-3.5 reveal group">
    {{-- Company Row --}}
    <div class="flex items-center gap-3">
        {{-- Company Avatar --}}
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $avatarGradient }} flex items-center justify-center shadow-sm shrink-0">
            <span class="text-white font-bold text-sm">{{ $companyInitial }}</span>
        </div>
        <div class="min-w-0">
            <h3 class="text-base font-bold text-slate-900 leading-snug">
                <a href="{{ route('jobs.show', $job) }}" class="hover:text-indigo-600 transition-colors duration-200">
                    {{ $job->title }}
                </a>
            </h3>
            <p class="text-sm text-slate-500 truncate">{{ $job->company }}</p>
        </div>
    </div>

    {{-- Location + Type --}}
    <div class="flex flex-wrap items-center gap-1.5">
        @if($job->location)
            <x-badge :text="$job->location" color="gray" />
        @endif
        @if($job->employment_type)
            <x-badge :text="$employmentLabels[$job->employment_type->value ?? $job->employment_type] ?? $job->employment_type" color="blue" />
        @endif
    </div>

    {{-- Tags --}}
    @if(!empty($job->tags))
        <div class="flex flex-wrap gap-1.5">
            @foreach(array_slice($job->tags, 0, 4) as $tag)
                <x-badge :text="$tag" color="purple" />
            @endforeach
            @if(count($job->tags) > 4)
                <span class="text-xs text-slate-400 self-center font-medium">+{{ count($job->tags) - 4 }}</span>
            @endif
        </div>
    @endif

    {{-- AI Summary --}}
    @if($job->summary_ai)
        <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed">
            {{ $job->summary_ai }}
        </p>
    @endif

    {{-- Date --}}
    <div class="mt-auto pt-3.5 border-t border-slate-100/60">
        <time class="text-xs text-slate-400 font-medium" datetime="{{ $job->created_at->toISOString() }}">
            {{ $job->created_at->diffForHumans() }}
        </time>
    </div>
</article>
