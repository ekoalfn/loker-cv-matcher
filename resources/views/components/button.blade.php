@props([
    'variant' => 'primary',
    'href'    => null,
    'type'    => 'button',
])

@php
    $base = 'inline-flex items-center justify-center min-h-[2.5rem] px-5 py-2 rounded-xl font-semibold text-sm transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 active:scale-[0.97] cursor-pointer';

    $variants = [
        'primary'   => 'btn-primary focus-visible:ring-teal-400',
        'secondary' => 'text-white/80 hover:text-white focus-visible:ring-teal-400',
        'ghost'     => 'text-white/60 hover:text-white hover:bg-white/07 focus-visible:ring-teal-400',
        'accent'    => 'focus-visible:ring-amber-400',
    ];

    $variantClasses = $variants[$variant] ?? $variants['primary'];

    // Build inline style for glass buttons
    $styles = [
        'primary'   => '',
        'secondary' => 'background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); box-shadow: inset 0 1px 0 rgba(255,255,255,0.08);',
        'ghost'     => '',
        'accent'    => 'background: rgba(245,158,11,0.85); color: white; border: 1px solid rgba(251,191,36,0.40); box-shadow: 0 2px 12px rgba(245,158,11,0.30), inset 0 1px 0 rgba(255,255,255,0.20);',
    ];
    $inlineStyle = $styles[$variant] ?? '';
@endphp

@if($href)
    <a href="{{ $href }}" style="{{ $inlineStyle }}" {{ $attributes->merge(['class' => "{$base} {$variantClasses}"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" style="{{ $inlineStyle }}" {{ $attributes->merge(['class' => "{$base} {$variantClasses}"]) }}>
        {{ $slot }}
    </button>
@endif
