<x-layout>
    <x-slot:title>CV Matcher - Cocokkan CV dengan Lowongan | Lamaraja</x-slot:title>
    <x-slot:description>Upload CV PDF, pilih lowongan aktif di Lamaraja, lalu dapatkan analisis kecocokan, kekuatan, gap, dan saran perbaikan dari AI.</x-slot:description>

    <div class="relative min-h-screen overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.25),_transparent_34%),radial-gradient(circle_at_80%_10%,_rgba(20,184,166,0.20),_transparent_28%),linear-gradient(135deg,_#0f172a_0%,_#10221f_45%,_#111827_100%)]"></div>
        <div class="absolute inset-0 opacity-[0.08]" style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 48px 48px;"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20" x-data="cvMatcher({ jobs: @js($jobs) })">
            <div class="grid grid-cols-1 lg:grid-cols-[1.05fr_0.95fr] gap-10 items-start">
                <section>
                    <div class="inline-flex items-center gap-2 rounded-full border border-emerald-300/20 bg-white/8 px-4 py-2 text-sm text-emerald-100 shadow-lg shadow-emerald-950/20 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Analisis CV berbasis lowongan aktif Lamaraja
                    </div>

                    <h1 class="mt-7 text-4xl sm:text-5xl lg:text-6xl font-[family-name:var(--font-display)] font-bold tracking-tight leading-tight">
                        Temukan lowongan yang benar-benar cocok dengan CV kamu.
                    </h1>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">
                        Upload CV PDF, pilih satu lowongan aktif, lalu AI akan membaca kecocokan skill, pengalaman, gap, dan langkah kecil agar peluang lamaranmu meningkat.
                    </p>

                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                        <div class="rounded-2xl border border-white/10 bg-white/8 p-4 backdrop-blur">
                            <div class="text-2xl font-bold text-emerald-300">3x</div>
                            <div class="mt-1 text-slate-300">scan gratis per hari</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/8 p-4 backdrop-blur">
                            <div class="text-2xl font-bold text-emerald-300">PDF</div>
                            <div class="mt-1 text-slate-300">maksimal 5MB</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/8 p-4 backdrop-blur">
                            <div class="text-2xl font-bold text-emerald-300">Privat</div>
                            <div class="mt-1 text-slate-300">file dihapus setelah scan</div>
                        </div>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-white/10 bg-white/95 p-5 sm:p-7 text-slate-900 shadow-2xl shadow-emerald-950/40">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">1. Pilih lowongan target</label>
                            <div class="relative">
                                <input type="search" x-model="query" placeholder="Cari posisi, perusahaan, atau lokasi..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100">
                            </div>
                            <div class="mt-3 max-h-72 overflow-y-auto rounded-2xl border border-slate-200 divide-y divide-slate-100 bg-white">
                                <template x-for="job in filteredJobs" :key="job.id">
                                    <button type="button" @click="selectedJobId = job.id" class="w-full p-4 text-left transition hover:bg-emerald-50" :class="selectedJobId === job.id ? 'bg-emerald-50 ring-1 ring-inset ring-emerald-300' : ''">
                                        <div class="flex items-start gap-3">
                                            <div class="h-11 w-11 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-100 flex items-center justify-center text-sm font-bold text-slate-500">
                                                <template x-if="job.company_logo"><img :src="job.company_logo" :alt="job.company" class="h-full w-full object-contain p-1"></template>
                                                <template x-if="!job.company_logo"><span x-text="initials(job.company)"></span></template>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-bold text-slate-900 line-clamp-1" x-text="job.title"></div>
                                                <div class="mt-1 text-sm text-slate-500 line-clamp-1">
                                                    <span x-text="job.company"></span>
                                                    <span x-show="job.location"> • <span x-text="job.location"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </template>
                                <div x-show="filteredJobs.length === 0" class="p-4 text-sm text-slate-500">Tidak ada lowongan yang cocok dengan pencarian.</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">2. Upload CV PDF</label>
                            <label class="group flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-emerald-200 bg-emerald-50/70 px-5 py-8 text-center transition hover:border-emerald-400 hover:bg-emerald-50">
                                <input type="file" accept="application/pdf,.pdf" class="sr-only" @change="handleFile">
                                <div class="h-14 w-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200 transition group-hover:scale-105">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <div class="mt-4 font-bold text-slate-900" x-text="file ? file.name : 'Klik untuk memilih CV' "></div>
                                <div class="mt-1 text-sm text-slate-500">PDF saja, maksimal 5MB.</div>
                            </label>
                        </div>

                        <div x-show="error" x-cloak class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" x-text="error"></div>

                        <button type="submit" :disabled="loading || !file || !selectedJobId" class="w-full rounded-2xl bg-emerald-600 px-5 py-4 font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                            <span x-show="!loading">Analisis Kecocokan CV</span>
                            <span x-show="loading" x-cloak>Menganalisis CV...</span>
                        </button>
                    </form>
                </section>
            </div>

            <section x-show="result" x-cloak class="mt-10 rounded-[2rem] border border-white/10 bg-white p-6 sm:p-8 text-slate-900 shadow-2xl">
                <div class="flex flex-col lg:flex-row lg:items-center gap-6 justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wider text-emerald-600">Hasil CV Matcher</p>
                        <h2 class="mt-2 text-3xl font-bold">Skor kecocokan: <span x-text="result?.match_score + '%'" class="text-emerald-600"></span></h2>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-emerald-600 px-5 py-3 font-bold text-emerald-700 hover:bg-emerald-50">Lihat lowongan lain</a>
                </div>

                <div class="mt-7 grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <template x-for="section in resultSections" :key="section.title">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <h3 class="font-bold text-slate-900" x-text="section.title"></h3>
                            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                <template x-for="item in section.items" :key="item">
                                    <li class="flex gap-2"><span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span><span x-text="item"></span></li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
            </section>
        </div>
    </div>

    <script>
        function cvMatcher({ jobs }) {
            return {
                jobs,
                query: '',
                selectedJobId: jobs[0]?.id ?? null,
                file: null,
                loading: false,
                error: null,
                result: null,
                get filteredJobs() {
                    const q = this.query.toLowerCase().trim();
                    if (!q) return this.jobs.slice(0, 20);
                    return this.jobs.filter(job => [job.title, job.company, job.location, job.employment_type].filter(Boolean).join(' ').toLowerCase().includes(q)).slice(0, 30);
                },
                get resultSections() {
                    if (!this.result) return [];
                    return [
                        { title: 'Kekuatan Utama', items: this.result.strengths || [] },
                        { title: 'Gap yang Perlu Ditutup', items: this.result.weaknesses || [] },
                        { title: 'Saran Perbaikan', items: this.result.suggestions || [] },
                    ];
                },
                initials(name) {
                    return (name || '?').split(/\s+/).slice(0, 2).map(part => part[0]).join('').toUpperCase();
                },
                handleFile(event) {
                    const selected = event.target.files[0];
                    this.error = null;
                    this.result = null;
                    if (!selected) return;
                    if (selected.type !== 'application/pdf' && !selected.name.toLowerCase().endsWith('.pdf')) {
                        this.error = 'CV harus berupa file PDF.';
                        event.target.value = '';
                        return;
                    }
                    if (selected.size > 5 * 1024 * 1024) {
                        this.error = 'Ukuran file CV maksimal 5MB.';
                        event.target.value = '';
                        return;
                    }
                    this.file = selected;
                },
                async submit() {
                    if (!this.file || !this.selectedJobId || this.loading) return;
                    this.loading = true;
                    this.error = null;
                    this.result = null;

                    const formData = new FormData();
                    formData.append('pdf_file', this.file);
                    formData.append('job_id', this.selectedJobId);

                    try {
                        const response = await fetch('{{ route('cv-scan.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });
                        const data = await response.json();
                        if (!response.ok) {
                            const validation = data.errors ? Object.values(data.errors).flat().join(' ') : '';
                            throw new Error(data.message || validation || 'Analisis CV gagal.');
                        }
                        this.result = data.result;
                        this.$nextTick(() => document.querySelector('[x-show="result"]')?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
                    } catch (err) {
                        this.error = err.message || 'Analisis CV gagal. Coba lagi beberapa saat lagi.';
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
</x-layout>
