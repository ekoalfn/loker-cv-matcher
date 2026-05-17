<x-layout>
    <x-slot:title>CV Matcher - Cari Lowongan dari CV | Lamaraja</x-slot:title>
    <x-slot:description>Upload CV PDF dan Lamaraja akan mencarikan lowongan aktif yang paling cocok berdasarkan skill, pengalaman, dan profilmu.</x-slot:description>

    <div class="relative min-h-screen overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.25),_transparent_34%),radial-gradient(circle_at_80%_10%,_rgba(20,184,166,0.20),_transparent_28%),linear-gradient(135deg,_#0f172a_0%,_#10221f_45%,_#111827_100%)]"></div>
        <div class="absolute inset-0 opacity-[0.08]" style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 48px 48px;"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20" x-data="cvMatcher()">
            <div class="grid grid-cols-1 lg:grid-cols-[1.05fr_0.95fr] gap-10 items-start">
                <section>
                    <div class="inline-flex items-center gap-2 rounded-full border border-emerald-300/20 bg-white/8 px-4 py-2 text-sm text-emerald-100 shadow-lg shadow-emerald-950/20 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Upload CV, Lamaraja carikan lowongan terbaik
                    </div>

                    <h1 class="mt-7 text-4xl sm:text-5xl lg:text-6xl font-[family-name:var(--font-display)] font-bold tracking-tight leading-tight">
                        Tidak perlu pilih job. Biarkan CV kamu yang bicara.
                    </h1>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">
                        Upload CV PDF sekali, lalu sistem akan membaca profilmu, memilih beberapa lowongan aktif yang paling relevan, dan mengurutkannya berdasarkan skor kecocokan AI.
                    </p>

                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                        <div class="rounded-2xl border border-white/10 bg-white/8 p-4 backdrop-blur">
                            <div class="text-2xl font-bold text-emerald-300">Top 5</div>
                            <div class="mt-1 text-slate-300">rekomendasi lowongan</div>
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
                            <label class="block text-sm font-bold text-slate-800 mb-2">Upload CV PDF</label>
                            <label class="group flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-emerald-200 bg-emerald-50/70 px-5 py-10 text-center transition hover:border-emerald-400 hover:bg-emerald-50">
                                <input type="file" accept="application/pdf,.pdf" class="sr-only" @change="handleFile">
                                <div class="h-16 w-16 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200 transition group-hover:scale-105">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <div class="mt-4 font-bold text-slate-900" x-text="file ? file.name : 'Klik untuk memilih CV' "></div>
                                <div class="mt-1 text-sm text-slate-500">PDF saja, maksimal 5MB.</div>
                            </label>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                            Lamaraja akan otomatis mengambil kandidat lowongan aktif, membandingkan CV dengan tiap lowongan, lalu menampilkan hasil terbaik beserta alasan kecocokannya.
                        </div>

                        <div x-show="error" x-cloak class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" x-text="error"></div>

                        <button type="submit" :disabled="loading || !file" class="w-full rounded-2xl bg-emerald-600 px-5 py-4 font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                            <span x-show="!loading">Cari Lowongan yang Cocok</span>
                            <span x-show="loading" x-cloak>Mencari dan menganalisis lowongan...</span>
                        </button>
                    </form>
                </section>
            </div>

            <section x-show="matches.length" x-cloak class="mt-10 space-y-5">
                <div class="rounded-[2rem] border border-white/10 bg-white p-6 sm:p-8 text-slate-900 shadow-2xl">
                    <p class="text-sm font-bold uppercase tracking-wider text-emerald-600">Hasil CV Matcher</p>
                    <h2 class="mt-2 text-3xl font-bold">Lowongan paling cocok untuk CV kamu</h2>
                    <p class="mt-2 text-slate-600">Urutan berdasarkan skor kecocokan AI dan relevansi profil dari CV yang kamu upload.</p>
                </div>

                <template x-for="match in matches" :key="match.scan_id">
                    <article class="rounded-[1.75rem] border border-white/10 bg-white p-5 sm:p-6 text-slate-900 shadow-xl">
                        <div class="flex flex-col lg:flex-row lg:items-start gap-5 justify-between">
                            <div class="flex gap-4 min-w-0">
                                <div class="h-14 w-14 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 flex items-center justify-center text-sm font-bold text-slate-500">
                                    <template x-if="match.job.company_logo"><img :src="match.job.company_logo" :alt="match.job.company" class="h-full w-full object-contain p-1"></template>
                                    <template x-if="!match.job.company_logo"><span x-text="initials(match.job.company)"></span></template>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xl font-bold text-slate-900" x-text="match.job.title"></h3>
                                    <p class="mt-1 text-sm text-slate-500">
                                        <span x-text="match.job.company"></span>
                                        <span x-show="match.job.location"> • <span x-text="match.job.location"></span></span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-center">
                                    <div class="text-2xl font-black text-emerald-700" x-text="match.match_score + '%' "></div>
                                    <div class="text-xs font-bold uppercase tracking-wide text-emerald-700">match</div>
                                </div>
                                <a :href="match.job.url" class="rounded-2xl border border-emerald-600 px-4 py-3 text-sm font-bold text-emerald-700 hover:bg-emerald-50">Lihat Job</a>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <template x-for="section in sections(match)" :key="section.title">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <h4 class="font-bold text-slate-900" x-text="section.title"></h4>
                                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                        <template x-for="item in section.items" :key="item">
                                            <li class="flex gap-2"><span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span><span x-text="item"></span></li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </article>
                </template>
            </section>
        </div>
    </div>

    <script>
        function cvMatcher() {
            return {
                file: null,
                loading: false,
                error: null,
                matches: [],
                initials(name) {
                    return (name || '?').split(/\s+/).slice(0, 2).map(part => part[0]).join('').toUpperCase();
                },
                sections(match) {
                    return [
                        { title: 'Kekuatan', items: match.strengths || [] },
                        { title: 'Gap', items: match.weaknesses || [] },
                        { title: 'Saran', items: match.suggestions || [] },
                    ];
                },
                handleFile(event) {
                    const selected = event.target.files[0];
                    this.error = null;
                    this.matches = [];
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
                    if (!this.file || this.loading) return;
                    this.loading = true;
                    this.error = null;
                    this.matches = [];

                    const formData = new FormData();
                    formData.append('pdf_file', this.file);

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
                        this.matches = data.result?.matches || [];
                        this.$nextTick(() => document.querySelector('[x-show="matches.length"]')?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
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
