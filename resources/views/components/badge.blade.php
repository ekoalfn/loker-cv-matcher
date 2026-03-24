@props([
    'text' => '',
    'color' => 'blue',
])

@php
    $colorClasses = match($color) {
        'blue' => 'bg-indigo-50/70 text-indigo-700 border-indigo-200/40 hover:bg-indigo-50',
        'green' => 'bg-emerald-50/70 text-emerald-700 border-emerald-200/40 hover:bg-emerald-50',
        'red' => 'bg-red-50/70 text-red-700 border-red-200/40 hover:bg-red-50',
        'yellow' => 'bg-amber-50/70 text-amber-700 border-amber-200/40 hover:bg-amber-50',
        'gray' => 'bg-slate-50/70 text-slate-600 border-slate-200/40 hover:bg-slate-50',
        'emerald' => 'bg-emerald-50/70 text-emerald-700 border-emerald-200/40 hover:bg-emerald-50',
        'purple' => 'bg-violet-50/70 text-violet-700 border-violet-200/40 hover:bg-violet-50',
        default => 'bg-indigo-50/70 text-indigo-700 border-indigo-200/40 hover:bg-indigo-50',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-3 py-1 text-xs font-medium border backdrop-blur-sm transition-all duration-200 {$colorClasses}"]) }}>
    {{ $text ?: $slot }}
</span>
