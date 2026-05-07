<x-layout title="CV Matcher - Cek Kecocokan CV | Lamaraja" description="Upload CV dan gunakan AI untuk mengecek kecocokan dengan lowongan kerja yang tersedia di Lamaraja.">

<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="text-center mb-12">
        <h1 class="font-[family-name:var(--font-display)] text-3xl md:text-4xl font-bold text-slate-900 mb-4">AI-Powered CV Matcher</h1>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto">
            Upload CV Anda dan biarkan AI kami menganalisis seberapa cocok dengan lowongan yang tersedia.
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8 max-w-2xl mx-auto">
        <div x-data="cvMatcherLanding()" class="space-y-6">
            <div
                class="rounded-2xl p-8 text-center transition-all cursor-pointer border-2 border-dashed border-slate-300 hover:border-emerald-400"
                @click="$refs.fileInput.click()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="text-base text-slate-600 mb-2">
                    Seret file CV ke sini, atau <span class="text-emerald-600 font-medium">pilih file</span>
                </p>
                <p class="text-sm text-slate-400">Hanya PDF, maksimal 5 MB</p>
                <input type="file" accept=".pdf,application/pdf" class="hidden" x-ref="fileInput">
            </div>

            <p class="text-sm text-slate-500 text-center">
                CV akan dianalisis dan hasilnya ditampilkan secara instan. Data Anda aman dan privat.
            </p>
        </div>
    </div>

    <div class="mt-12 text-center">
        <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all">
            Atau Cari Lowongan Dulu
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

<script>
    function cvMatcherLanding() {
        return {
            handleFileSelect(event) {
                // Redirect to a job page with CV matcher
                const file = event.target.files[0];
                if (file) {
                    alert('Silakan pilih lowongan kerja terlebih dahulu, kemudian upload CV di halaman detail lowongan.');
                }
            }
        };
    }
</script>

</x-layout>
