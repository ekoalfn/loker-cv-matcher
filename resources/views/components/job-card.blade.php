@props([
    'job',
])

<article class="bg-white border border-gray-200 rounded-xl p-5 hover:border-blue-200 card-hover flex flex-col gap-3 reveal">
    {{-- Title --}}
    <h3 class="text-lg font-semibold text-gray-900">
        <a href="{{ route('jobs.show', $job) }}" class="hover:text-blue-600 transition-colors">
            {{ $job->title }}
        </a>
    </h3>

    {{-- Company --}}
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        <span>{{ $job->company_name }}</span>
    </div>

    {{-- Location --}}
    <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <x-badge :text="$job->location" color="gray" />
    </div>

    {{-- Tags --}}
    @if(!empty($job->tags))
        <div class="flex flex-wrap gap-1.5">
            @foreach($job->tags as $tag)
                <x-badge :text="$tag" color="blue" />
            @endforeach
        </div>
    @endif

    {{-- AI Summary --}}
    @if($job->summary_ai)
        <p class="text-sm text-gray-500 line-clamp-2">
            {{ $job->summary_ai }}
        </p>
    @endif

    {{-- Date --}}
    <div class="mt-auto pt-2 border-t border-gray-100">
        <time class="text-xs text-gray-400" datetime="{{ $job->created_at->toISOString() }}">
            {{ $job->created_at->diffForHumans() }}
        </time>
    </div>
</article>
