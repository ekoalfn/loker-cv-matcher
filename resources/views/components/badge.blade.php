@props([
    'text'  => '',
    'color' => 'blue',
])

@php
    $colorClass = match($color) {
        'blue'            => 'badge-teal',
        'green', 'emerald'=> 'badge-green',
        'red'             => 'badge-red',
        'yellow'          => 'badge-amber',
        'gray'            => 'badge-gray',
        'teal'            => 'badge-teal',
        default           => 'badge-teal',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {$colorClass}"]) }}>
    {{ $text ?: $slot }}
</span>
