{{-- Scraping Tools Section --}}
<div class="surface rounded-2xl p-5 mb-8 animate-fade-up delay-200" x-data="scraperTools()">

    <div class="flex items-center gap-3 mb-5">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #0d9488, #06b6d4);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
        </div>
        <div>
            <h2 class="text-sm font-semibold text-slate-700">Scraping Tools</h2>
            <p class="text-xs text-stone-400">Fetch URL → AI ekstraksi → Simpan ke database</p>
        </div>
    </div>

    {{-- Input Options (URL or HTML File) --}}
    <div class="space-y-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- URL Input --}}
            <div>
                <label for="scrape-url" class="block text-sm font-medium text-stone-600 mb-1.5">Dari URL</label>
                <div class="flex gap-2">
                    <input type="url" id="scrape-url" x-model="url" placeholder="https://contoh.com/lowongan-kerja"
                        class="flex-1 min-h-[2.75rem] px-4 py-2.5 rounded-xl input-glass text-sm focus:outline-none interactive-focus"
                        @keydown.enter="scrapeData()" :disabled="htmlFile !== null">
                </div>
            </div>

            {{-- HTML File Upload --}}
            <div>
                <label class="block text-sm font-medium text-stone-600 mb-1.5">Atau Upload File HTML</label>
                <div class="relative">
                    <input type="file" id="html-file" accept=".html,.htm" x-ref="htmlInput" @change="handleFileChange"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" :disabled="url.trim() !== ''">
                    <div class="flex items-center min-h-[2.75rem] px-4 py-2.5 rounded-xl input-glass text-sm"
                         :class="{ 'opacity-50': url.trim() !== '' }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span x-text="htmlFile ? htmlFile.name : 'Pilih file HTML...'" class="text-stone-500 truncate flex-1"></span>
                        <button x-show="htmlFile" @click.stop.prevent="clearFile" type="button" class="ml-2 text-stone-400 hover:text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label for="source-name" class="block text-sm font-medium text-stone-600 mb-1.5">Nama Sumber</label>
                <input type="text" id="source-name" x-model="sourceName" placeholder="Contoh: Jobstreet, Glints, LinkedIn..."
                    class="w-full min-h-[2.75rem] px-4 py-2 rounded-xl input-glass text-sm focus:outline-none interactive-focus">
            </div>
            
            <button @click="scrapeData()" :disabled="loading || (!url.trim() && !htmlFile)"
                class="w-full sm:w-auto min-h-[2.75rem] px-6 py-2.5 rounded-xl font-semibold text-sm btn-primary btn-press interactive-focus flex items-center justify-center gap-2 whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed">
                <template x-if="!loading">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Scrape & Ekstrak
                    </span>
                </template>
                <template x-if="loading">
                    <span class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span x-text="loadingText"></span>
                    </span>
                </template>
            </button>
        </div>
    </div>

    {{-- Error --}}
    <div x-show="error" x-cloak class="mb-4 text-sm text-red-600 rounded-xl p-3 flex items-start gap-2" style="background: #fef2f2; border: 1px solid #fecaca;" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <span x-text="error"></span>
    </div>

    {{-- Success --}}
    <div x-show="successMsg" x-cloak class="mb-4 text-sm text-emerald-700 rounded-xl p-3 flex items-start gap-2" style="background: #ecfdf5; border: 1px solid #d1fae5;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span x-text="successMsg"></span>
    </div>

    {{-- Extracted Jobs Preview --}}
    <div x-show="jobs.length > 0" x-cloak>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-slate-700">
                Hasil Ekstraksi: <span class="text-teal-600" x-text="jobs.length"></span> lowongan
            </h3>
            <div class="flex gap-2">
                <button @click="selectAll()" class="text-xs px-3 py-1.5 rounded-lg font-medium text-stone-600 hover:bg-slate-100 transition-colors" style="background: #f5f3f0; border: 1px solid #eae7e3;">
                    <span x-text="selectedJobs.length === jobs.length ? 'Batal Pilih' : 'Pilih Semua'"></span>
                </button>
                <button @click="ingestSelected()" :disabled="selectedJobs.length === 0 || ingesting || !sourceName.trim()"
                    class="text-xs px-4 py-1.5 rounded-lg font-semibold text-white btn-primary btn-press disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5">
                    <template x-if="!ingesting">
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            Simpan (<span x-text="selectedJobs.length"></span>)
                        </span>
                    </template>
                    <template x-if="ingesting"><span>Menyimpan...</span></template>
                </button>
            </div>
        </div>

        <div class="space-y-2 max-h-[500px] overflow-y-auto pr-1">
            <template x-for="(job, idx) in jobs" :key="idx">
                <div class="rounded-xl p-3.5 cursor-pointer transition-all"
                     :style="selectedJobs.includes(idx)
                        ? 'background: rgba(13,148,136,0.06); border: 1.5px solid rgba(13,148,136,0.30);'
                        : 'background: #f5f3f0; border: 1.5px solid #eae7e3;'"
                     @click="toggleSelect(idx)">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" :checked="selectedJobs.includes(idx)" class="glass-check mt-0.5 shrink-0" @click.stop="toggleSelect(idx)">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-700 truncate" x-text="job.title"></p>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-stone-500">
                                <span x-text="job.company"></span>
                                <span x-show="job.location" class="flex items-center gap-1">
                                    <span class="text-stone-300">&middot;</span>
                                    <span x-text="job.location"></span>
                                </span>
                                <span x-show="job.employment_type" class="flex items-center gap-1">
                                    <span class="text-stone-300">&middot;</span>
                                    <span x-text="job.employment_type" class="badge-blue px-1.5 py-0.5 rounded text-[10px] font-medium"></span>
                                </span>
                            </div>
                            <p x-show="job.description_raw" class="text-xs text-stone-400 mt-1.5 line-clamp-2" x-text="job.description_raw?.substring(0, 200) + '...'"></p>
                            <div x-show="job.tags && job.tags.length" class="flex flex-wrap gap-1 mt-1.5">
                                <template x-for="tag in (job.tags || []).slice(0, 5)" :key="tag">
                                    <span class="badge-gray px-1.5 py-0.5 rounded text-[10px] font-medium" x-text="tag"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function scraperTools() {
    return {
        url: '',
        sourceName: '',
        htmlFile: null,
        htmlContent: null,
        loading: false,
        loadingText: 'Memproses...',
        ingesting: false,
        error: null,
        successMsg: null,
        jobs: [],
        selectedJobs: [],

        handleFileChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            if (file.type !== 'text/html' && !file.name.endsWith('.html') && !file.name.endsWith('.htm')) {
                this.error = 'Hanya file HTML yang didukung.';
                this.clearFile();
                return;
            }

            this.error = null;
            this.htmlFile = file;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                this.htmlContent = e.target.result;
                if (!this.sourceName.trim()) {
                    this.sourceName = file.name.replace(/\.html?$/, '');
                }
            };
            reader.onerror = () => {
                this.error = 'Gagal membaca file.';
                this.clearFile();
            };
            reader.readAsText(file);
        },

        clearFile() {
            this.htmlFile = null;
            this.htmlContent = null;
            if (this.$refs.htmlInput) this.$refs.htmlInput.value = '';
        },

        async scrapeData() {
            if (this.loading || (!this.url.trim() && !this.htmlContent)) return;
            this.loading = true;
            this.error = null;
            this.successMsg = null;
            this.jobs = [];
            this.selectedJobs = [];

            try {
                let endpoint, payload;
                
                if (this.htmlContent) {
                    this.loadingText = 'Mengekstrak HTML dengan AI...';
                    endpoint = '{{ route("scraper.scrape-html") }}';
                    payload = { html: this.htmlContent, source_url: this.sourceName || 'local-file' };
                } else {
                    this.loadingText = 'Mengambil halaman & mengekstrak dengan AI...';
                    endpoint = '{{ route("scraper.scrape-url") }}';
                    payload = { url: this.url };
                }

                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || data.error || 'Gagal scrape');
                }

                if (!data.success) {
                    throw new Error(data.error || 'Gagal mengekstrak data');
                }

                this.jobs = data.jobs || [];

                if (this.jobs.length === 0) {
                    this.error = 'Tidak ada lowongan ditemukan di sumber ini.';
                } else {
                    this.selectedJobs = this.jobs.map((_, i) => i);
                    if (!this.sourceName.trim() && this.url) {
                        try {
                            this.sourceName = new URL(this.url).hostname.replace('www.', '');
                        } catch(e) {}
                    }
                }
            } catch (err) {
                this.error = err.message || 'Terjadi kesalahan.';
            } finally {
                this.loading = false;
            }
        },

        toggleSelect(idx) {
            const i = this.selectedJobs.indexOf(idx);
            if (i > -1) this.selectedJobs.splice(i, 1);
            else this.selectedJobs.push(idx);
        },

        selectAll() {
            if (this.selectedJobs.length === this.jobs.length) {
                this.selectedJobs = [];
            } else {
                this.selectedJobs = this.jobs.map((_, i) => i);
            }
        },

        async ingestSelected() {
            if (this.selectedJobs.length === 0 || this.ingesting || !this.sourceName.trim()) return;
            this.ingesting = true;
            this.error = null;
            this.successMsg = null;

            const selected = this.selectedJobs.map(i => this.jobs[i]);

            try {
                const res = await fetch('{{ route("scraper.ingest-jobs") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        source_name: this.sourceName,
                        jobs: selected,
                    }),
                });

                const data = await res.json();

                if (!res.ok) {
                    const errors = data.errors ? Object.values(data.errors).flat().join(', ') : '';
                    throw new Error(data.message || errors || 'Gagal menyimpan');
                }

                const s = data.stats || {};
                this.successMsg = `Berhasil! ${s.created || 0} baru, ${s.updated || 0} diperbarui, ${s.skipped || 0} dilewati.`;
                this.jobs = [];
                this.selectedJobs = [];
            } catch (err) {
                this.error = err.message || 'Gagal menyimpan data.';
            } finally {
                this.ingesting = false;
            }
        },
    };
}
</script>
