@props([
    'text' => '',
    'color' => 'blue',
])

@php
    $colorClasses = match($color) {
        'blue' => 'bg-teal-50/70 text-teal-700 border-teal-200/40 hover:bg-teal-50',
        'green' => 'bg-emerald-50/70 text-emerald-700 border-emerald-200/40 hover:bg-emerald-50',
        'red' => 'bg-red-50/70 text-red-700 border-red-200/40 hover:bg-red-50',
        'yellow' => 'bg-amber-50/70 text-amber-700 border-amber-200/40 hover:bg-amber-50',
        'gray' => 'bg-slate-50/70 text-slate-600 border-slate-200/40 hover:bg-slate-50',
        'emerald' => 'bg-emerald-50/70 text-emerald-700 border-emerald-200/40 hover:bg-emerald-50',
        'teal' => 'bg-teal-50/70 text-teal-700 border-teal-200/40 hover:bg-teal-50',
        default => 'bg-teal-50/70 text-teal-700 border-teal-200/40 hover:bg-teal-50',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-3 py-1 text-[0.6875rem] font-semibold border backdrop-blur-sm transition-all duration-200 hover:scale-[1.03] {$colorClasses}"]) }}>
    {{ $text ?: $slot }}
</span>
