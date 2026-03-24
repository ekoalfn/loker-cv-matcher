@props([
    'keyword' => '',
    'action' => '',
])

<form action="{{ $action }}" method="GET" class="w-full">
    <div class="relative flex items-center">
        {{-- Search Icon --}}
        <div class="absolute left-3 pointer-events-none text-gray-400">
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
            class="w-full min-h-[2.75rem] pl-10 pr-24 py-3 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
            aria-label="Cari lowongan kerja"
        >

        {{-- Submit Button --}}
        <button
            type="submit"
            class="absolute right-1.5 inline-flex items-center justify-center min-h-[2.25rem] px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer"
        >
            Cari
        </button>
    </div>
</form>
