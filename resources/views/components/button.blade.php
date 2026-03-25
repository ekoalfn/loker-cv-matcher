@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center min-h-[2.5rem] px-5 py-2 rounded-lg font-semibold text-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 active:scale-[0.97] cursor-pointer';

    $variantClasses = match($variant) {
        'primary' => 'btn-primary focus-visible:ring-teal-500',
        'secondary' => 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:border-slate-300 focus-visible:ring-teal-500',
        'ghost' => 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 focus-visible:ring-teal-500',
        'accent' => 'bg-amber-500 text-white hover:bg-amber-600 focus-visible:ring-amber-500 shadow-sm',
        default => 'btn-primary focus-visible:ring-teal-500',
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
