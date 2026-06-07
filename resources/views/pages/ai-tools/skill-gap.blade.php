<x-layout
    title="AI Skill Gap Analyzer - Cek Skill yang Kurang | Lamaraja"
    description="Analisis skill gap dengan AI: dari CV dan target role, lihat skill yang sudah dimiliki, yang masih kurang, dan rencana belajar untuk menutup kesenjangan."
>
    <div class="bg-gradient-to-br from-emerald-50 via-white to-teal-50" x-data="skillGapTool()">
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="text-center max-w-2xl mx-auto">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-600">AI Skill Gap Analyzer</p>
                <h1 class="mt-2 font-[family-name:var(--font-display)] text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-950">Analisis skill gap kamu</h1>
                <p class="mt-4 text-lg leading-8 text-slate-600">Bandingkan skill di CV dengan target role, lalu dapatkan rencana belajar untuk menutup gap.</p>
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
                        <label class="block text-sm font-bold text-slate-800 mb-2">Target role</label>
                        <input type="text" x-model="targetRole" placeholder="Contoh: Data Analyst, Product Manager" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none">
                    </div>

                    <div x-show="error" x-cloak class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" x-text="error"></div>

                    <button type="submit" :disabled="loading || !file" class="w-full rounded-2xl bg-emerald-600 px-5 py-4 font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                        <span x-show="!loading">Analisis Skill Gap</span>
                        <span x-show="loading" x-cloak>Menganalisis skill...</span>
                    </button>
                </form>
            </div>

            <div x-show="result" x-cloak x-transition class="mt-8 space-y-5">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 sm:p-8 shadow-xl shadow-slate-200/60 text-center">
                    <p class="text-sm font-bold uppercase tracking-wider text-emerald-600">Readiness Score</p>
                    <div class="mt-2 text-5xl font-black text-emerald-700" x-text="(result?.readiness_score || 0) + '%'"></div>
                    <p class="mt-1 text-sm text-slate-500">Seberapa siap kamu untuk target role saat ini.</p>
                </div>

                <div class="grid sm:grid-cols-2 gap-5">
                    <div class="rounded-[1.5rem] border border-emerald-100 bg-white p-6 shadow-sm">
                        <h3 class="font-bold text-emerald-700">Skill yang sudah cocok</h3>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <template x-for="(s, i) in result?.matched_skills" :key="i">
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-100" x-text="s"></span>
                            </template>
                        </div>
                    </div>
                    <div class="rounded-[1.5rem] border border-amber-100 bg-white p-6 shadow-sm">
                        <h3 class="font-bold text-amber-700">Skill yang masih kurang</h3>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <template x-for="(s, i) in result?.missing_skills" :key="i">
                                <span class="rounded-full bg-amber-50 px-3 py-1 text-sm font-semibold text-amber-700 ring-1 ring-amber-100" x-text="s"></span>
                            </template>
                        </div>
                    </div>
                </div>

                <template x-if="result?.learning_plan?.length">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="font-bold text-slate-900">Rencana belajar</h3>
                        <div class="mt-4 space-y-3">
                            <template x-for="(plan, i) in result.learning_plan" :key="i">
                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                                    <p class="font-bold text-slate-900" x-text="plan.skill"></p>
                                    <p class="mt-1 text-sm text-slate-600"><span class="font-semibold text-slate-700">Kenapa:</span> <span x-text="plan.why"></span></p>
                                    <p class="mt-1 text-sm text-slate-600"><span class="font-semibold text-slate-700">Cara:</span> <span x-text="plan.how"></span></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </section>
    </div>

    <x-ai-tool-script />
    <script>
        function skillGapTool() {
            return {
                ...aiToolBase('{{ route('ai-tools.skill-gap.run') }}'),
                targetRole: '',
                extraFields(form) { form.append('target_role', this.targetRole); },
            };
        }
    </script>
</x-layout>
