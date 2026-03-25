@props([
    'keyword' => '',
    'action' => '',
])

<form action="{{ $action }}" method="GET" class="w-full">
    <div class="relative flex items-center surface-search rounded-xl focus-glow">
        <div class="absolute left-3.5 pointer-events-none text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <input
            type="text"
            name="keyword"
            value="{{ $keyword }}"
            placeholder="Cari posisi, perusahaan, atau lokasi..."
            class="w-full min-h-[2.75rem] pl-11 pr-24 py-2.5 rounded-xl bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none text-sm"
            aria-label="Cari lowongan kerja"
        >

        <button
            type="submit"
            class="absolute right-1.5 inline-flex items-center justify-center min-h-[2.25rem] px-4 py-1.5 btn-primary text-sm font-semibold rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 active:scale-[0.97] cursor-pointer"
        >
            Cari
        </button>
    </div>
</form>
