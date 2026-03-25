@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center min-h-[2.75rem] px-6 py-2.5 rounded-xl font-semibold text-sm transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 active:scale-[0.97] cursor-pointer';

    $variantClasses = match($variant) {
        'primary' => 'btn-gradient text-white focus-visible:ring-teal-500 hover:shadow-lg hover:shadow-teal-500/30',
        'secondary' => 'glass bg-white/60 text-teal-600 border border-teal-200/30 hover:bg-white/80 hover:border-teal-300/50 hover:shadow-md focus-visible:ring-teal-500 btn-press',
        'ghost' => 'bg-transparent text-slate-600 hover:bg-teal-50/50 hover:text-teal-600 focus-visible:ring-teal-500 btn-press rounded-xl',
        'accent' => 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:from-amber-600 hover:to-amber-700 focus-visible:ring-amber-500',
        default => 'btn-gradient text-white focus-visible:ring-teal-500 hover:shadow-lg hover:shadow-teal-500/30',
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
