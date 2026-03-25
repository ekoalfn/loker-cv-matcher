@props([
    'text' => '',
    'color' => 'blue',
])

@php
    $colorClasses = match($color) {
        'blue' => 'bg-teal-50 text-teal-700 border-teal-100',
        'green', 'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'red' => 'bg-red-50 text-red-700 border-red-100',
        'yellow' => 'bg-amber-50 text-amber-700 border-amber-100',
        'gray' => 'bg-slate-50 text-slate-600 border-slate-100',
        'teal' => 'bg-teal-50 text-teal-700 border-teal-100',
        default => 'bg-teal-50 text-teal-700 border-teal-100',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium border {$colorClasses}"]) }}>
    {{ $text ?: $slot }}
</span>
