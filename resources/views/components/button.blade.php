@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center min-h-[2.75rem] px-6 py-2.5 rounded-xl font-semibold text-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 cursor-pointer';

    $variantClasses = match($variant) {
        'primary' => 'btn-gradient text-white focus:ring-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30',
        'secondary' => 'glass bg-white/60 text-indigo-600 border border-indigo-200/30 hover:bg-white/80 hover:border-indigo-300/50 hover:shadow-md focus:ring-indigo-500 btn-press',
        'ghost' => 'bg-transparent text-slate-600 hover:bg-indigo-50/50 hover:text-indigo-600 focus:ring-indigo-500 btn-press rounded-xl',
        default => 'btn-gradient text-white focus:ring-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "{$baseClasses} {$variantClasses}"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "{$baseClasses} {$variantClasses}"]) }}>
        {{ $slot }}
    </button>
@endif
