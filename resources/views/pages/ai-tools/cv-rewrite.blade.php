<x-layout
    title="AI CV Rewrite & ATS Optimizer Gratis | Lamaraja"
    description="Ubah pengalaman kerja di CV jadi bullet point ATS-friendly dengan AI. Optimalkan CV agar lolos screening ATS dan lebih menonjol di mata recruiter."
    robots="index, follow"
    canonical="{{ route('ai-tools.cv-rewrite') }}"
>
    <div class="bg-gradient-to-br from-emerald-50 via-white to-teal-50" x-data="cvRewriteTool()">
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="text-center max-w-2xl mx-auto">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-600">AI CV Rewrite / ATS Optimizer</p>
                <h1 class="mt-2 font-[family-name:var(--font-display)] text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-950">Perbaiki CV agar lolos ATS</h1>
                <p class="mt-4 text-lg leading-8 text-slate-600">AI menulis ulang pengalaman kerjamu menjadi bullet point berbasis pencapaian yang ramah ATS.</p>
            </div>

            <div class="mt-10 rounded-[2rem] border border-emerald-100 bg-white p-5 sm:p-7 shadow-xl shadow-emerald-900/10">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Upload CV PDF</label>
                        <label class="group flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-emerald-200 bg-emerald-50/70 px-5 py-8 text-center transition hover:border-emerald-400 hover:bg-emerald-50">
                            <input type="file" accept="application/pdf,.pdf" class="sr-only" @change="handleFile">
                            <div class="h-14 w-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200 transition group-hover:scale-105">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div class="mt-3 font-bold text-slate-900" x-text="file ? file.name : 'Klik untuk memilih CV'"></div>
                            <div class="mt-1 text-sm text-slate-500">PDF saja, maksimal 5MB.</div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Target role (opsional)</label>
                        <input type="text" x-model="targetRole" placeholder="Contoh: Backend Developer, Digital Marketing" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none">
                    </div>

                    <div x-show="error" x-cloak class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" x-text="error"></div>

                    <button type="submit" :disabled="loading || !file" class="w-full rounded-2xl bg-emerald-600 px-5 py-4 font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                        <span x-show="!loading">Optimalkan CV Saya</span>
                        <span x-show="loading" x-cloak>Menulis ulang CV...</span>
                    </button>
                </form>
            </div>

            <div x-show="result" x-cloak x-transition class="mt-8 space-y-5">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 sm:p-8 shadow-xl shadow-slate-200/60">
                    <h2 class="font-[family-name:var(--font-display)] text-2xl font-extrabold text-slate-950">Bullet ATS-friendly</h2>
                    <ul class="mt-4 space-y-2 text-sm leading-7 text-slate-700">
                        <template x-for="(item, i) in result?.rewritten_bullets" :key="i">
                            <li class="flex gap-2 rounded-xl bg-slate-50 px-4 py-2.5"><span class="font-bold text-emerald-600">✓</span><span x-text="item"></span></li>
                        </template>
                    </ul>
                </div>

                <template x-if="result?.before_after?.length">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="font-bold text-slate-900">Before → After</h3>
                        <div class="mt-4 space-y-4">
                            <template x-for="(pair, i) in result.before_after" :key="i">
                                <div class="grid sm:grid-cols-2 gap-3">
                                    <div class="rounded-xl border border-rose-100 bg-rose-50/60 p-3 text-sm text-slate-600"><span class="text-xs font-bold uppercase text-rose-500">Before</span><p class="mt-1" x-text="pair.before"></p></div>
                                    <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 p-3 text-sm text-slate-600"><span class="text-xs font-bold uppercase text-emerald-600">After</span><p class="mt-1" x-text="pair.after"></p></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="result?.tips?.length">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="font-bold text-slate-900">Tips ATS</h3>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600">
                            <template x-for="(item, i) in result.tips" :key="i">
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
        function cvRewriteTool() {
            return {
                ...aiToolBase('{{ route('ai-tools.cv-rewrite.run') }}'),
                targetRole: '',
                extraFields(form) { form.append('target_role', this.targetRole); },
            };
        }
    </script>
</x-layout>
