<x-layout
    title="AI Generator Surat Lamaran Kerja Gratis | Lamaraja"
    description="Buat surat lamaran kerja (cover letter) otomatis dengan AI berdasarkan CV dan lowongan yang dituju. Gratis, instan, dan dalam Bahasa Indonesia."
    :robots="request()->has('job_id') ? 'noindex, follow' : 'index, follow'"
    canonical="{{ route('ai-tools.cover-letter') }}"
>
    <div class="bg-gradient-to-br from-emerald-50 via-white to-teal-50" x-data="coverLetterTool()">
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="text-center max-w-2xl mx-auto">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-600">AI Cover Letter Generator</p>
                <h1 class="mt-2 font-[family-name:var(--font-display)] text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-950">Generator surat lamaran kerja</h1>
                <p class="mt-4 text-lg leading-8 text-slate-600">Upload CV, pilih lowongan, dan AI akan menulis cover letter personal dalam hitungan detik.</p>
            </div>

            <div class="mt-10 rounded-[2rem] border border-emerald-100 bg-white p-5 sm:p-7 shadow-xl shadow-emerald-900/10">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">1. Upload CV PDF</label>
                        <label class="group flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-emerald-200 bg-emerald-50/70 px-5 py-8 text-center transition hover:border-emerald-400 hover:bg-emerald-50">
                            <input type="file" accept="application/pdf,.pdf" class="sr-only" @change="handleFile">
                            <div class="h-14 w-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200 transition group-hover:scale-105">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div class="mt-3 font-bold text-slate-900" x-text="file ? file.name : 'Klik untuk memilih CV'"></div>
                            <div class="mt-1 text-sm text-slate-500">PDF saja, maksimal 5MB.</div>
                        </label>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">2. Pilih lowongan</label>
                            <select x-model="jobId" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none">
                                <option value="">— Pilih lowongan —</option>
                                @foreach($jobs as $job)
                                    <option value="{{ $job->id }}" @selected($selectedJobId === $job->id)>{{ $job->title }} — {{ $job->company }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">3. Nada surat</label>
                            <select x-model="tone" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none">
                                <option value="profesional">Profesional</option>
                                <option value="antusias">Antusias</option>
                                <option value="formal">Formal</option>
                                <option value="ramah">Ramah</option>
                            </select>
                        </div>
                    </div>

                    <div x-show="error" x-cloak class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" x-text="error"></div>

                    <button type="submit" :disabled="loading || !file || !jobId" class="w-full rounded-2xl bg-emerald-600 px-5 py-4 font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                        <span x-show="!loading">Buat Surat Lamaran</span>
                        <span x-show="loading" x-cloak>Menulis surat lamaran...</span>
                    </button>
                </form>
            </div>

            <div x-show="result" x-cloak x-transition class="mt-8 space-y-5">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 sm:p-8 shadow-xl shadow-slate-200/60">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="font-[family-name:var(--font-display)] text-2xl font-extrabold text-slate-950">Surat lamaran kamu</h2>
                        <button type="button" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-bold text-emerald-700 hover:bg-emerald-100" @click="copy($event)">Salin</button>
                    </div>
                    <div class="mt-4 whitespace-pre-wrap rounded-2xl bg-slate-50 p-5 text-sm leading-7 text-slate-700" x-text="result?.cover_letter"></div>
                </div>
                <template x-if="result?.highlights?.length">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="font-bold text-slate-900">Poin jual yang ditonjolkan</h3>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600">
                            <template x-for="(item, i) in result.highlights" :key="i">
                                <li class="flex gap-2"><span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span><span x-text="item"></span></li>
                            </template>
                        </ul>
                    </div>
                </template>
            </div>
        </section>
    </div>

    <x-ai-tool-script />
    <script>
        function coverLetterTool() {
            return {
                ...aiToolBase('{{ route('ai-tools.cover-letter.run') }}'),
                jobId: '{{ $selectedJobId }}',
                tone: 'profesional',
                extraFields(form) {
                    form.append('job_id', this.jobId);
                    form.append('tone', this.tone);
                },
                copy(event) { this.copyText(this.result?.cover_letter, event); },
            };
        }
    </script>
</x-layout>
