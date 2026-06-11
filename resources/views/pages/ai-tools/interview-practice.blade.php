<x-layout
    title="AI Interview Question Generator - Latihan Interview per Posisi | Lamaraja"
    description="Generate pertanyaan interview spesifik dari deskripsi lowongan dengan AI. Latihan interview untuk posisi yang kamu tuju, lengkap dengan tips menjawab."
    :robots="request()->has('job_id') ? 'noindex, follow' : 'index, follow'"
    canonical="{{ route('ai-tools.interview-practice') }}"
>
    @php
        $faqLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'Apa itu AI Interview Question Generator Lamaraja?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'AI Interview Question Generator adalah tool gratis yang menghasilkan pertanyaan interview spesifik berdasarkan deskripsi lowongan yang kamu tuju, lengkap dengan tips menjawab untuk setiap pertanyaan.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Bagaimana cara menggunakan Interview Question Generator?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Paste deskripsi lowongan atau masukkan nama posisi, lalu AI akan menghasilkan pertanyaan interview yang relevan untuk posisi tersebut beserta panduan menjawabnya.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Berapa banyak pertanyaan yang dihasilkan?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'AI menghasilkan pertanyaan interview yang mencakup berbagai aspek: pertanyaan teknis, behavioral, situational, dan pertanyaan umum sesuai posisi yang dituju.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Apakah pertanyaan interview yang dihasilkan relevan dengan posisi saya?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Ya, pertanyaan disesuaikan dengan deskripsi lowongan yang kamu masukkan. Semakin detail deskripsi lowongannya, semakin relevan pertanyaan yang dihasilkan.',
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $softwareLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => 'AI Interview Question Generator Lamaraja',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'url' => route('ai-tools.interview-practice'),
            'description' => 'Tool gratis untuk generate pertanyaan interview spesifik per posisi menggunakan AI, lengkap dengan tips menjawab.',
            'offers' => [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'IDR',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp

    <script type="application/ld+json">{!! $faqLd !!}</script>
    <script type="application/ld+json">{!! $softwareLd !!}</script>

    <div class="bg-gradient-to-br from-emerald-50 via-white to-teal-50" x-data="interviewQuestionTool()">
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="text-center max-w-2xl mx-auto">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-600">AI Interview Question Generator</p>
                <h1 class="mt-2 font-[family-name:var(--font-display)] text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-950">
                    @if($job)
                        Latihan Interview untuk {{ $job->title }}
                    @else
                        Latihan interview untuk posisi tujuanmu
                    @endif
                </h1>
                <p class="mt-4 text-lg leading-8 text-slate-600">AI membuat pertanyaan interview spesifik dari deskripsi lowongan, lengkap dengan tips menjawab.</p>
            </div>

            @if($job)
                <div class="mt-8 rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <p class="font-bold text-slate-900">{{ $job->title }}</p>
                        <p class="text-sm text-slate-500">{{ $job->company }} @if($job->location) • {{ $job->location }} @endif</p>
                    </div>
                    <button type="button" @click="generate({{ $job->id }})" :disabled="loading" class="rounded-xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 disabled:opacity-50">
                        <span x-show="!loading">Buat Pertanyaan Interview</span>
                        <span x-show="loading" x-cloak>Membuat pertanyaan...</span>
                    </button>
                </div>
            @else
                <div class="mt-8 rounded-2xl border border-amber-100 bg-amber-50 p-5 text-sm text-amber-800">
                    Pilih sebuah lowongan dari halaman detail untuk membuat pertanyaan interview yang spesifik, atau coba
                    <a href="{{ route('mock-interview.landing') }}" class="font-bold underline">simulasi interview interaktif</a>.
                </div>
                <div class="mt-4">
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                        Jelajahi lowongan
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            @endif

            <div x-show="error" x-cloak class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" x-text="error"></div>

            <div x-show="questions.length" x-cloak x-transition class="mt-8 space-y-3">
                <h2 class="font-[family-name:var(--font-display)] text-2xl font-extrabold text-slate-950">Pertanyaan interview</h2>
                <template x-for="(q, i) in questions" :key="i">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100" x-text="q.category"></span>
                        </div>
                        <p class="mt-3 font-semibold text-slate-900" x-text="(i + 1) + '. ' + q.question"></p>
                        <p class="mt-2 text-sm text-slate-500" x-show="q.tip"><span class="font-semibold text-slate-600">Tips:</span> <span x-text="q.tip"></span></p>
                    </div>
                </template>

                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-center">
                    <p class="text-sm font-semibold text-emerald-800">Ingin latihan menjawab langsung dengan feedback AI?</p>
                    <a href="{{ route('mock-interview.landing') }}" class="mt-3 inline-flex rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Coba Mock Interview AI</a>
                </div>
            </div>
        </section>
    </div>

    <script>
        function interviewQuestionTool() {
            return {
                loading: false,
                error: null,
                questions: [],
                @if($job) autoJobId: {{ $job->id }}, @else autoJobId: null, @endif
                init() {
                    // Auto-generate when arriving from a job page.
                    if (this.autoJobId) { this.generate(this.autoJobId); }
                },
                async generate(jobId) {
                    if (this.loading) return;
                    this.loading = true;
                    this.error = null;
                    this.questions = [];
                    try {
                        const form = new FormData();
                        form.append('job_id', jobId);
                        const response = await fetch('{{ route('ai-tools.interview-questions.run') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: form,
                        });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Gagal membuat pertanyaan.');
                        this.questions = data.result?.questions || [];
                        if (!this.questions.length) this.error = 'Belum ada pertanyaan yang bisa dibuat. Coba lagi.';
                    } catch (err) {
                        this.error = err.message || 'Gagal membuat pertanyaan.';
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
</x-layout>
