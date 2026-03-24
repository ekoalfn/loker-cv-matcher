@props([
    'text' => '',
    'color' => 'blue',
])

@php
    $colorClasses = match($color) {
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'green' => 'bg-green-50 text-green-700 ring-green-600/20',
        'red' => 'bg-red-50 text-red-700 ring-red-600/20',
        'yellow' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
        'gray' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'purple' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
        default => 'bg-blue-50 text-blue-700 ring-blue-600/20',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {$colorClasses}"]) }}>
    {{ $text ?: $slot }}
</span>
