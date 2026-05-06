<div class="surface rounded-2xl p-6 lg:p-8 mb-8" x-data="queueStatusData()">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold font-[family-name:var(--font-display)] text-slate-900 tracking-tight">Status Ekstraksi Detail (Queue)</h2>
            <p class="text-slate-500 text-sm mt-1">Memantau proses AI mengambil informasi detail secara otomatis.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
            </span>
            <span class="text-sm font-medium text-teal-600">Live Updates</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Job Title</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Company</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Di-ingest pada</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Status Detail</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="job in jobs" :key="job.id">
                    <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/50 transition-colors">
                        <td class="py-3 px-4 text-sm font-medium text-slate-900" x-text="job.title"></td>
                        <td class="py-3 px-4 text-sm text-slate-600" x-text="job.company"></td>
                        <td class="py-3 px-4 text-sm text-slate-500" x-text="job.time"></td>
                        <td class="py-3 px-4 text-right">
                            <template x-if="job.status === 'pending'">
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                                    <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Menunggu Ekstraksi...
                                </span>
                            </template>
                            <template x-if="job.status === 'success'">
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-teal-50 text-teal-700 border border-teal-200/50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Selesai
                                </span>
                            </template>
                            <template x-if="job.status === 'error'">
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200/50" :title="job.error">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Gagal (Hover)
                                </span>
                            </template>
                        </td>
                    </tr>
                </template>
                <tr x-show="jobs.length === 0">
                    <td colspan="4" class="py-8 text-center text-slate-500 text-sm">
                        Belum ada antrean. Silakan ingest data baru terlebih dahulu.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('queueStatusData', () => ({
            jobs: [],
            interval: null,
            init() {
                this.fetchData();
                // Poll every 3 seconds
                this.interval = setInterval(() => this.fetchData(), 3000);
            },
            destroy() {
                if(this.interval) clearInterval(this.interval);
            },
            async fetchData() {
                try {
                    const response = await fetch('{{ route('scraper.queue-status') }}');
                    if(response.ok) {
                        const data = await response.json();
                        if(data.success) {
                            this.jobs = data.jobs;
                        }
                    }
                } catch(e) {
                    console.error('Failed to fetch queue status', e);
                }
            }
        }));
    });
</script>
