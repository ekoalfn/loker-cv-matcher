@props([
    'keyword' => '',
    'action'  => '',
    'size'    => 'default',
])

@php
    $isLg        = $size === 'lg';
    $inputClasses = $isLg ? 'min-h-[3.25rem] pl-12 pr-28 py-3 text-base' : 'min-h-[2.75rem] pl-11 pr-24 py-2.5 text-sm';
    $iconClasses  = $isLg ? 'left-4' : 'left-3.5';
    $btnClasses   = $isLg ? 'min-h-[2.75rem] px-5 py-2' : 'min-h-[2.25rem] px-4 py-1.5';
@endphp

<form action="{{ $action }}" method="GET" class="w-full">
    <div class="relative flex items-center surface-search {{ $isLg ? 'rounded-2xl' : 'rounded-xl' }} focus-glow">

        {{-- Search icon --}}
        <div class="absolute {{ $iconClasses }} pointer-events-none text-stone-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <input
            type="text"
            name="keyword"
            value="{{ $keyword }}"
            placeholder="Cari posisi, perusahaan, atau lokasi..."
            class="w-full {{ $inputClasses }} rounded-{{ $isLg ? '2xl' : 'xl' }} bg-transparent text-slate-800 placeholder-stone-400 focus:outline-none"
            aria-label="Cari lowongan kerja"
            autocomplete="off"
        >

        <button
            type="submit"
            class="absolute right-1.5 inline-flex items-center justify-center {{ $btnClasses }} btn-primary text-sm font-semibold rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-400 active:scale-[0.97] cursor-pointer"
        >
            Cari
        </button>
    </div>
</form>
