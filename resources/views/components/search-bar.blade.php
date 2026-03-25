@props([
    'keyword' => '',
    'action' => '',
])

<form action="{{ $action }}" method="GET" class="w-full">
    <div class="relative flex items-center glass-floating rounded-2xl focus-glow search-expand p-1.5">
        {{-- Search Icon --}}
        <div class="absolute left-4 pointer-events-none text-teal-400 transition-transform duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        {{-- Input --}}
        <input
            type="text"
            name="keyword"
            value="{{ $keyword }}"
            placeholder="Cari posisi, perusahaan, atau lokasi..."
            class="w-full min-h-[3rem] pl-12 pr-28 py-3.5 rounded-xl bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none text-sm font-medium"
            aria-label="Cari lowongan kerja"
        >

        {{-- Submit Button --}}
        <button
            type="submit"
            class="absolute right-2 inline-flex items-center justify-center min-h-[2.5rem] px-5 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-teal-500/25 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 active:scale-[0.97] cursor-pointer"
        >
            Cari
        </button>
    </div>
</form>
