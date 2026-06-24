<x-layout
    title="Latihan Interview AI - Live Call Interview Kerja Gratis | Lamaraja"
    description="Simulasi interview kerja dengan AI: AI bertanya dengan suara, kamu jawab lisan, lalu dapat skor dan feedback. Gratis, tanpa daftar. Mulai sekarang!"
    :robots="request()->hasAny(['role', 'job_id']) ? 'noindex, follow' : 'index, follow'"
    canonical="{{ route('mock-interview.landing') }}"
    ogImage="{{ url('/images/og-latihan-interview.jpg') }}"
>
    @php
        $faqLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'Apa itu latihan interview AI Lamaraja?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Fitur simulasi interview kerja live yang dipandu AI: AI bertanya dengan suara, kamu menjawab dengan bicara, lalu mendapat skor dan feedback.']],
                ['@type' => 'Question', 'name' => 'Apakah latihan interview gratis?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Ya, kamu bisa mencoba sesi live call interview gratis tanpa daftar. Cukup upload CV dan pilih target role.']],
                ['@type' => 'Question', 'name' => 'Apakah perlu mengetik jawaban?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Tidak. Mode live call bersifat hands-free: kamu cukup berbicara dan AI akan mentranskrip serta melanjutkan otomatis.']],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $maxQuestions = (int) config('mock_interview.max_questions', 6);
    @endphp
    <script type="application/ld+json">{!! $faqLd !!}</script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Latihan Interview AI Lamaraja",
        "applicationCategory": "BusinessApplication",
        "operatingSystem": "Web",
        "description": "Simulasi interview kerja dengan AI. Latihan menjawab pertanyaan interview, dapatkan skor dan feedback. Gratis.",
        "offers": {"@type": "Offer", "price": "0", "priceCurrency": "IDR"},
        "url": "https://lamaraja.web.id/latihan-interview"
    }
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "HowTo",
        "name": "Cara Latihan Interview Kerja dengan AI",
        "description": "Simulasi interview kerja dengan AI gratis — latihan menjawab pertanyaan HR dan dapatkan feedback",
        "step": [
            {"@type": "HowToStep", "name": "Pilih jenis interview", "text": "Pilih tipe interview: umum, teknikal, atau spesifik industri"},
            {"@type": "HowToStep", "name": "Mulai sesi interview", "text": "Klik mulai dan AI akan memperkenalkan diri seperti HR sungguhan"},
            {"@type": "HowToStep", "name": "Jawab pertanyaan dengan suara", "text": "AI mengajukan pertanyaan dengan suara, kamu menjawab secara lisan"},
            {"@type": "HowToStep", "name": "Dapatkan skor dan feedback", "text": "Setelah sesi selesai, dapatkan skor performa dan feedback detail untuk improvement"}
        ]
    }
    </script>

    <div x-data="liveInterview('{{ $targetRole }}')" x-init="init()">
        {{-- HERO + SETUP --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-teal-50/60 to-white">
            <div class="absolute -top-24 -right-24 h-80 w-80 rounded-full bg-emerald-200/50 blur-3xl" aria-hidden="true"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
                <div class="grid lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100 shadow-sm">Live Call Interview · Beta</span>
                        <h1 class="mt-5 font-[family-name:var(--font-display)] text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-950 leading-[1.1]">Latihan Interview Kerja <span class="text-emerald-600">dengan AI</span> — Seperti Telepon HR Sungguhan</h1>
                        <p class="mt-4 text-lg leading-8 text-slate-600">Hands-free: AI interviewer membacakan pertanyaan, kamu menjawab dengan suara, dan percakapan berlanjut otomatis. Di akhir sesi kamu dapat skor dan feedback.</p>
                        <ul class="mt-5 space-y-2 text-sm text-slate-600">
                            <li class="flex gap-2"><span class="text-emerald-600 font-bold">✓</span> AI bicara, kamu jawab dengan suara</li>
                            <li class="flex gap-2"><span class="text-emerald-600 font-bold">✓</span> Deteksi suara otomatis — berhenti bicara, AI langsung lanjut</li>
                            <li class="flex gap-2"><span class="text-emerald-600 font-bold">✓</span> Berbasis CV dan target role kamu</li>
                        </ul>
                        <div class="mt-7 flex flex-col sm:flex-row gap-3">
                            <a href="#mulai" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-7 py-4 text-base font-bold text-white shadow-lg shadow-emerald-600/25 transition hover:bg-emerald-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.18z"/></svg>
                                Mulai Live Call
                            </a>
                            <a href="{{ route('cv-matcher.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-emerald-200 bg-white px-7 py-4 text-base font-bold text-emerald-700 transition hover:bg-emerald-50">Cek CV Dulu</a>
                        </div>
                    </div>

                    {{-- SETUP CARD --}}
                    <div id="mulai" class="rounded-[2rem] border border-emerald-100 bg-white p-6 sm:p-7 shadow-2xl shadow-emerald-900/10 scroll-mt-24">
                        <h2 class="font-[family-name:var(--font-display)] text-xl font-bold text-slate-900">Mulai sesi interview</h2>
                        <p class="mt-1 text-sm text-slate-500">Upload CV, pilih target role, lalu mulai panggilan. Butuh akses mikrofon.</p>

                        <form @submit.prevent="startCall" class="mt-5 space-y-4">
                            <label class="group flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-emerald-200 bg-emerald-50/70 px-5 py-7 text-center transition hover:border-emerald-400 hover:bg-emerald-50">
                                <input type="file" accept="application/pdf,.pdf" class="sr-only" @change="handleFile">
                                <div class="h-12 w-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200 transition group-hover:scale-105">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.5a6 6 0 006-6v-1m-12 1v1a6 6 0 006 6zm0 0V22m-4 0h8M12 3a3 3 0 00-3 3v6a3 3 0 006 0V6a3 3 0 00-3-3z"/></svg>
                                </div>
                                <div class="mt-3 font-bold text-slate-900 text-sm" x-text="file ? file.name : 'Klik untuk memilih CV (PDF, maks 5MB)'"></div>
                                <div class="mt-1 text-xs text-slate-500">File dihapus otomatis setelah teks terbaca.</div>
                            </label>

                            <input x-model="role" type="text" placeholder="Target role, mis. Frontend Developer" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none">

                            <select x-model="jobId" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none">
                                <option value="">Tanpa target lowongan spesifik</option>
                                @foreach ($jobs as $job)
                                    <option value="{{ $job->id }}" @selected($selectedJobId === $job->id)>{{ $job->title }} — {{ $job->company }}</option>
                                @endforeach
                            </select>

                            <select x-model="interviewMode" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none">
                                <option value="mixed">Mode: Mixed</option>
                                <option value="hr">Mode: HR</option>
                                <option value="technical">Mode: Technical</option>
                                <option value="behavioral">Mode: Behavioral</option>
                            </select>

                            <div x-show="error" x-cloak class="rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700" x-text="error"></div>

                            <button type="submit" :disabled="loading || !file" class="w-full rounded-xl bg-emerald-600 px-5 py-4 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                                <span x-show="!loading">Mulai Panggilan Interview</span>
                                <span x-show="loading" x-cloak>Menyiapkan interviewer...</span>
                            </button>
                            <p class="text-center text-xs text-slate-400">Pakai headphone agar suara AI tidak terekam ulang oleh mikrofon.</p>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        {{-- BENEFITS --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="font-[family-name:var(--font-display)] text-3xl font-extrabold text-slate-950">Kenapa latihan interview dengan AI?</h2>
            </div>
            <div class="mt-10 grid sm:grid-cols-3 gap-5">
                @foreach([
                    ['Latihan kapan saja', 'Tidak perlu janji dengan mentor. Berlatih sesuai jadwalmu, sebanyak yang kamu mau.'],
                    ['Pertanyaan relevan', 'AI menyesuaikan pertanyaan dengan posisi dan profil kamu, bukan template generik.'],
                    ['Feedback jujur', 'Dapatkan skor dan saran konkret agar jawabanmu makin meyakinkan.'],
                ] as $benefit)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $benefit[0] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $benefit[1] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- EXAMPLE QUESTIONS --}}
        <section class="bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 py-14 md:py-16">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-center font-[family-name:var(--font-display)] text-2xl md:text-3xl font-bold text-slate-900">Contoh pertanyaan interview</h2>
                <div class="mt-8 grid sm:grid-cols-2 gap-4">
                    @foreach([
                        ['HR', 'Ceritakan tentang diri Anda dan kenapa tertarik dengan posisi ini.'],
                        ['Behavioral', 'Ceritakan situasi saat Anda menghadapi konflik dalam tim dan cara menyelesaikannya.'],
                        ['Teknis', 'Bagaimana Anda memastikan kualitas pekerjaan di bawah tenggat waktu yang ketat?'],
                        ['Motivasi', 'Apa pencapaian yang paling Anda banggakan dan kenapa?'],
                    ] as $q)
                        <div class="rounded-2xl bg-white p-5 shadow-sm border border-emerald-100">
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100">{{ $q[0] }}</span>
                            <p class="mt-3 font-semibold text-slate-800">{{ $q[1] }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 text-center">
                    <a href="#mulai" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-7 py-4 text-base font-bold text-white shadow-lg shadow-emerald-600/25 transition hover:bg-emerald-700">Mulai Live Call Sekarang</a>
                </div>
            </div>
        </section>

        {{-- ============ FULL-SCREEN LIVE CALL OVERLAY ============ --}}
        <template x-teleport="body">
            <div x-show="phase === 'call' || phase === 'ended'" x-cloak
                 class="fixed inset-0 z-[60] bg-slate-950 text-white overflow-y-auto">

                {{-- CALL --}}
                <template x-if="phase === 'call'">
                    <section class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 flex flex-col items-center min-h-screen">
                        <div class="w-full flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span class="font-semibold" x-text="statusLabel()"></span>
                            </div>
                            <div class="rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-emerald-300" x-text="'Pertanyaan ' + Math.min(questionNumber, maxQuestions) + '/' + maxQuestions"></div>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center gap-8 w-full">
                            <div class="relative flex items-center justify-center">
                                <div class="absolute h-64 w-64 rounded-full bg-emerald-500/10" :class="callState === 'ai_speaking' ? 'animate-ping' : ''"></div>
                                <div class="absolute rounded-full bg-emerald-500/20 transition-all duration-150" :style="`height: ${orbSize()}px; width: ${orbSize()}px;`"></div>
                                <div class="relative h-44 w-44 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 shadow-2xl shadow-emerald-500/40 flex items-center justify-center transition-transform duration-150" :class="callState === 'ai_speaking' ? 'scale-105' : ''">
                                    <template x-if="callState === 'ai_speaking'">
                                        <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z"/></svg>
                                    </template>
                                    <template x-if="callState === 'listening'">
                                        <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z"/></svg>
                                    </template>
                                    <template x-if="callState === 'processing' || callState === 'connecting'">
                                        <svg class="h-14 w-14 text-white animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    </template>
                                </div>
                            </div>

                            <div class="w-full max-w-xl text-center min-h-[5rem]">
                                <template x-if="callState === 'ai_speaking' || callState === 'connecting'">
                                    <p class="text-lg leading-7 text-white" x-text="currentQuestion || 'Menyambungkan...'"></p>
                                </template>
                                <template x-if="callState === 'listening'">
                                    <div>
                                        <p class="text-sm font-semibold text-emerald-300">Giliran kamu — silakan jawab</p>
                                        <p class="mt-2 text-base leading-7 text-slate-300" x-text="liveTranscript || (heardSpeech ? '...' : 'Mendengarkan...')"></p>
                                    </div>
                                </template>
                                <template x-if="callState === 'processing'">
                                    <p class="text-base text-slate-300" x-text="transcribing ? 'Mentranskrip jawaban...' : 'AI sedang menyiapkan pertanyaan berikutnya...'"></p>
                                </template>
                            </div>
                        </div>

                        <div class="w-full">
                            <div x-show="error" x-cloak class="mb-4 rounded-2xl border border-amber-400/30 bg-amber-500/10 p-3 text-sm text-amber-200 text-center" x-text="error"></div>
                            <div class="flex items-center justify-center gap-4">
                                <button type="button" @click="toggleMute()" :disabled="callState !== 'listening'"
                                    class="flex h-14 w-14 items-center justify-center rounded-full border border-white/15 bg-white/10 text-white transition hover:bg-white/20 disabled:opacity-40"
                                    :class="muted ? 'bg-amber-500/20 border-amber-400/40' : ''" :aria-label="muted ? 'Aktifkan mikrofon' : 'Bisukan mikrofon'">
                                    <template x-if="!muted"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z"/></svg></template>
                                    <template x-if="muted"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 9.75L19.5 12m0 0l2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25m-10.5-6l9 9M6.75 6.75v5.25a3 3 0 005.25 2.012M3 3l18 18"/></svg></template>
                                </button>

                                <button type="button" @click="endCall()" class="flex h-16 w-16 items-center justify-center rounded-full bg-red-500 text-white shadow-lg shadow-red-500/30 transition hover:bg-red-600" aria-label="Akhiri panggilan">
                                    <svg class="h-7 w-7 rotate-[135deg]" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.18z"/></svg>
                                </button>

                                <button type="button" @click="finishSpeakingNow()" :disabled="callState !== 'listening' || !heardSpeech"
                                    class="flex h-14 w-14 items-center justify-center rounded-full border border-emerald-400/40 bg-emerald-500/20 text-emerald-200 transition hover:bg-emerald-500/30 disabled:opacity-40" aria-label="Selesai bicara">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </button>
                            </div>
                            <div class="mt-3 flex items-center justify-center gap-6 text-xs text-slate-500">
                                <span x-show="callState === 'listening'">Tekan ✓ kalau sudah selesai bicara</span>
                                <button type="button" @click="repeatQuestion()" :disabled="callState === 'processing'" class="hover:text-slate-300 disabled:opacity-40">Ulangi pertanyaan</button>
                            </div>
                        </div>
                    </section>
                </template>

                {{-- ENDED / FEEDBACK --}}
                <template x-if="phase === 'ended'">
                    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
                        <div class="text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/20 ring-1 ring-emerald-400/30">
                                <svg class="h-8 w-8 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                            <h2 class="mt-5 font-[family-name:var(--font-display)] text-3xl sm:text-4xl font-extrabold">Panggilan interview selesai</h2>
                            <p class="mt-3 text-slate-300" x-text="loading ? 'Menyusun feedback akhir...' : 'Berikut hasil sesi latihanmu.'"></p>
                        </div>

                        <template x-if="feedback">
                            <div class="mt-8 space-y-5">
                                <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6 text-center">
                                    <p class="text-sm font-bold uppercase tracking-wider text-emerald-300">Skor keseluruhan</p>
                                    <div class="mt-2 text-5xl font-black text-emerald-300" x-text="(feedback.overall_score || 0) + '/100'"></div>
                                    <p class="mt-3 text-sm leading-6 text-slate-300" x-text="feedback.summary"></p>
                                </div>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <template x-if="feedback.strengths?.length">
                                        <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                            <h3 class="font-bold text-emerald-300">Kekuatan</h3>
                                            <ul class="mt-2 space-y-1.5 text-sm text-slate-300"><template x-for="(item, i) in feedback.strengths" :key="i"><li x-text="'• ' + item"></li></template></ul>
                                        </div>
                                    </template>
                                    <template x-if="feedback.weaknesses?.length">
                                        <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                            <h3 class="font-bold text-amber-300">Perlu dilatih</h3>
                                            <ul class="mt-2 space-y-1.5 text-sm text-slate-300"><template x-for="(item, i) in feedback.weaknesses" :key="i"><li x-text="'• ' + item"></li></template></ul>
                                        </div>
                                    </template>
                                </div>
                                <template x-if="feedback.action_plan?.length">
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                        <h3 class="font-bold text-white">Rencana aksi</h3>
                                        <ul class="mt-2 space-y-1.5 text-sm text-slate-300"><template x-for="(item, i) in feedback.action_plan" :key="i"><li x-text="'• ' + item"></li></template></ul>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="transcript.length">
                            <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-5">
                                <h3 class="font-bold text-white">Transkrip percakapan</h3>
                                <div class="mt-3 space-y-3 max-h-80 overflow-y-auto pr-1">
                                    <template x-for="(m, i) in transcript" :key="i">
                                        <div :class="m.role === 'candidate' ? 'ml-6 bg-emerald-500/10 border-emerald-400/20' : 'mr-6 bg-white/5 border-white/10'" class="rounded-xl border p-3">
                                            <div class="text-xs font-bold uppercase tracking-wide" :class="m.role === 'candidate' ? 'text-emerald-300' : 'text-slate-400'" x-text="label(m.role)"></div>
                                            <p class="mt-1 text-sm leading-6 text-slate-200 whitespace-pre-line" x-text="m.content_text"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                            <button type="button" @click="resetAll()" class="rounded-2xl bg-emerald-500 px-6 py-3 font-bold text-white transition hover:bg-emerald-400">Mulai Panggilan Baru</button>
                            <button type="button" @click="exportPdf()" class="rounded-2xl border border-emerald-400/40 bg-emerald-500/10 px-6 py-3 font-bold text-emerald-300 text-center transition hover:bg-emerald-500/20">⬇ Export PDF</button>
                            <a href="{{ route('cv-matcher.index') }}" class="rounded-2xl border border-white/15 bg-white/5 px-6 py-3 font-bold text-white text-center transition hover:bg-white/10">Cek CV Saya</a>
                        </div>
                    </section>
                </template>
            </div>
        </template>
    </div>

    <script>
        function liveInterview(initialRole) {
            return {
                // setup
                file: null,
                role: initialRole || '',
                jobId: @json($selectedJobId ? (string) $selectedJobId : ''),
                interviewMode: 'mixed',

                // flow
                phase: 'setup',            // setup | call | ended
                callState: 'connecting',   // connecting | ai_speaking | listening | processing
                loading: false,
                error: null,

                // session
                token: null,
                questionNumber: 0,
                maxQuestions: {{ $maxQuestions }},
                currentQuestion: '',
                feedback: null,
                transcript: [],

                // audio
                currentAudio: null,
                mediaStream: null,
                mediaRecorder: null,
                audioChunks: [],
                audioContext: null,
                analyser: null,
                vadRaf: null,
                muted: false,
                heardSpeech: false,
                liveTranscript: '',
                transcribing: false,
                silenceStart: null,
                speechStart: null,
                recordStartedAt: 0,
                shouldSubmitAfterStop: false,
                inputLevel: 0,

                // VAD tuning
                silenceThreshold: 0.012,
                silenceDuration: 1500,
                maxAnswerMs: 60000,

                init() {
                    window.addEventListener('beforeunload', () => this.teardownAudio());
                },

                handleFile(event) {
                    const selected = event.target.files[0];
                    this.error = null;
                    if (!selected) return;
                    if (selected.type !== 'application/pdf' && !selected.name.toLowerCase().endsWith('.pdf')) {
                        this.error = 'CV harus berupa file PDF.';
                        return;
                    }
                    if (selected.size > 5 * 1024 * 1024) {
                        this.error = 'Ukuran PDF maksimal 5MB.';
                        return;
                    }
                    this.file = selected;
                },

                statusLabel() {
                    return { connecting: 'Menyambungkan...', ai_speaking: 'AI sedang berbicara', listening: 'Mendengarkan kamu', processing: 'Memproses' }[this.callState] || 'Live';
                },
                orbSize() {
                    if (this.callState === 'listening') return 200 + this.inputLevel * 180;
                    if (this.callState === 'ai_speaking') return 230;
                    return 200;
                },
                label(role) {
                    return role === 'candidate' ? 'Kamu' : (role === 'system' ? 'Coach' : 'Interviewer');
                },

                async startCall() {
                    if (!this.file) return;
                    this.loading = true;
                    this.error = null;

                    try {
                        this.mediaStream = await navigator.mediaDevices.getUserMedia({ audio: { echoCancellation: true, noiseSuppression: true } });
                    } catch (e) {
                        this.loading = false;
                        this.error = 'Mikrofon tidak bisa diakses. Izinkan akses mikrofon untuk memulai panggilan.';
                        return;
                    }

                    const form = new FormData();
                    form.append('pdf_file', this.file);
                    form.append('target_role', this.role);
                    form.append('job_id', this.jobId);
                    form.append('interview_mode', this.interviewMode);
                    form.append('delivery_mode', 'voice');

                    try {
                        const res = await fetch('{{ route('mock-interview.start') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                            body: form,
                        });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Gagal memulai interview.');

                        this.token = data.token;
                        this.transcript = data.messages || [];
                        this.questionNumber = data.current_question_count || 1;
                        this.maxQuestions = data.max_questions || this.maxQuestions;
                        this.phase = 'call';
                        this.callState = 'connecting';
                        document.body.style.overflow = 'hidden';
                        this.$nextTick(() => this.playQuestion(this.lastInterviewerText()));
                    } catch (e) {
                        this.error = e.message;
                        this.teardownAudio();
                    } finally {
                        this.loading = false;
                    }
                },

                lastInterviewerText() {
                    return [...(this.transcript || [])].reverse().find(m => m.role === 'interviewer')?.content_text || '';
                },

                async playQuestion(text) {
                    if (!text) { this.beginListening(); return; }
                    this.currentQuestion = text;
                    this.callState = 'ai_speaking';
                    this.stopCurrentAudio();
                    try {
                        const res = await fetch('{{ route('mock-interview.speech') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'audio/mpeg', 'Content-Type': 'application/json' },
                            body: JSON.stringify({ input: text }),
                        });
                        if (!res.ok) throw new Error('tts_failed');
                        const url = URL.createObjectURL(await res.blob());
                        this.currentAudio = new Audio(url);
                        this.currentAudio.onended = () => { URL.revokeObjectURL(url); this.beginListening(); };
                        this.currentAudio.onerror = () => { URL.revokeObjectURL(url); this.beginListening(); };
                        await this.currentAudio.play();
                    } catch (e) {
                        const waitMs = Math.min(9000, 2500 + text.length * 35);
                        setTimeout(() => this.beginListening(), waitMs);
                    }
                },

                stopCurrentAudio() {
                    if (this.currentAudio) { try { this.currentAudio.pause(); } catch (_) {} this.currentAudio = null; }
                },

                repeatQuestion() {
                    if (this.callState === 'listening') this.stopListening(false);
                    this.playQuestion(this.currentQuestion);
                },

                async beginListening() {
                    this.callState = 'listening';
                    this.heardSpeech = false;
                    this.liveTranscript = '';
                    this.silenceStart = null;
                    this.speechStart = null;
                    this.audioChunks = [];
                    if (this.muted) return;
                    try {
                        if (!this.mediaStream) {
                            this.mediaStream = await navigator.mediaDevices.getUserMedia({ audio: { echoCancellation: true, noiseSuppression: true } });
                        }
                        this.startRecorder();
                        this.startVad();
                    } catch (e) {
                        this.error = 'Mikrofon tidak tersedia. Coba muat ulang halaman.';
                    }
                },

                startRecorder() {
                    const mime = MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : '';
                    this.mediaRecorder = mime ? new MediaRecorder(this.mediaStream, { mimeType: mime }) : new MediaRecorder(this.mediaStream);
                    this.audioChunks = [];
                    this.mediaRecorder.ondataavailable = (ev) => { if (ev.data.size > 0) this.audioChunks.push(ev.data); };
                    this.mediaRecorder.onstop = () => this.handleRecordingStop();
                    this.mediaRecorder.start();
                    this.recordStartedAt = Date.now();
                },

                startVad() {
                    try {
                        this.audioContext = this.audioContext || new (window.AudioContext || window.webkitAudioContext)();
                        const source = this.audioContext.createMediaStreamSource(this.mediaStream);
                        this.analyser = this.audioContext.createAnalyser();
                        this.analyser.fftSize = 1024;
                        source.connect(this.analyser);
                        const buffer = new Uint8Array(this.analyser.fftSize);
                        const tick = () => {
                            if (this.callState !== 'listening') return;
                            this.analyser.getByteTimeDomainData(buffer);
                            let sum = 0;
                            for (let i = 0; i < buffer.length; i++) { const v = (buffer[i] - 128) / 128; sum += v * v; }
                            const rms = Math.sqrt(sum / buffer.length);
                            this.inputLevel = Math.min(1, rms * 6);
                            const now = Date.now();
                            if (rms > this.silenceThreshold) {
                                if (!this.speechStart) this.speechStart = now;
                                if (now - this.speechStart > 120) this.heardSpeech = true;
                                this.silenceStart = null;
                            } else if (this.heardSpeech) {
                                if (!this.silenceStart) this.silenceStart = now;
                                else if (now - this.silenceStart > this.silenceDuration) { this.stopListening(true); return; }
                            }
                            if (this.heardSpeech && now - this.recordStartedAt > this.maxAnswerMs) { this.stopListening(true); return; }
                            this.vadRaf = requestAnimationFrame(tick);
                        };
                        this.vadRaf = requestAnimationFrame(tick);
                    } catch (e) { /* manual button fallback */ }
                },

                finishSpeakingNow() {
                    if (this.callState === 'listening' && this.heardSpeech) this.stopListening(true);
                },

                stopListening(submit) {
                    if (this.vadRaf) cancelAnimationFrame(this.vadRaf);
                    this.vadRaf = null;
                    this.inputLevel = 0;
                    this.shouldSubmitAfterStop = submit;
                    if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') this.mediaRecorder.stop();
                    else if (submit) this.handleRecordingStop();
                },

                async handleRecordingStop() {
                    if (!this.shouldSubmitAfterStop) return;
                    if (!this.audioChunks.length) { this.beginListening(); return; }
                    this.callState = 'processing';
                    this.transcribing = true;
                    let text = '';
                    try {
                        const blob = new Blob(this.audioChunks, { type: this.audioChunks[0]?.type || 'audio/webm' });
                        const form = new FormData();
                        form.append('audio', blob, 'jawaban.webm');
                        const res = await fetch('{{ route('mock-interview.transcribe') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                            body: form,
                        });
                        const data = await res.json();
                        if (res.ok) text = (data.text || '').trim();
                    } catch (e) { /* fall through */ }
                    this.transcribing = false;
                    if (!text) { this.error = 'Suara tidak terdengar jelas. Coba jawab lagi.'; this.beginListening(); return; }
                    this.error = null;
                    this.liveTranscript = text;
                    await this.sendAnswer(text);
                },

                async sendAnswer(answer) {
                    this.callState = 'processing';
                    try {
                        const res = await fetch(`/mock-interview/${this.token}/reply`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                            body: JSON.stringify({ answer }),
                        });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Gagal mengirim jawaban.');
                        const session = data.session;
                        this.transcript = session.messages || this.transcript;
                        this.questionNumber = session.current_question_count || this.questionNumber;
                        if (session.status === 'completed') { this.feedback = session.final_feedback; this.endToFeedback(); return; }
                        this.liveTranscript = '';
                        this.playQuestion(this.lastInterviewerText());
                    } catch (e) {
                        this.error = e.message;
                        this.beginListening();
                    }
                },

                toggleMute() {
                    this.muted = !this.muted;
                    if (this.muted) this.stopListening(false);
                    else if (this.callState === 'listening') this.beginListening();
                },

                async endCall() {
                    this.stopCurrentAudio();
                    this.stopListening(false);
                    this.phase = 'ended';
                    this.loading = true;
                    this.callState = 'processing';
                    try {
                        const res = await fetch(`/mock-interview/${this.token}/finish`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                        });
                        const data = await res.json();
                        if (res.ok) { this.feedback = data.final_feedback; this.transcript = data.messages || this.transcript; }
                    } catch (e) { /* ignore */ }
                    this.teardownAudio();
                    this.loading = false;
                },

                endToFeedback() {
                    this.phase = 'ended';
                    this.teardownAudio();
                },

                teardownAudio() {
                    if (this.vadRaf) cancelAnimationFrame(this.vadRaf);
                    this.vadRaf = null;
                    this.stopCurrentAudio();
                    try { if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') this.mediaRecorder.stop(); } catch (_) {}
                    if (this.mediaStream) { this.mediaStream.getTracks().forEach(t => t.stop()); this.mediaStream = null; }
                    if (this.audioContext) { try { this.audioContext.close(); } catch (_) {} this.audioContext = null; }
                },

                resetAll() {
                    if (this.currentAudio) { this.currentAudio.pause(); this.currentAudio = null; }
                    this.file = null;
                    this.token = null;
                    this.questionNumber = 0;
                    this.currentQuestion = '';
                    this.feedback = null;
                    this.transcript = [];
                    this.error = null;
                    this.muted = false;
                    this.phase = 'setup';
                    this.callState = 'connecting';
                    document.body.style.overflow = '';
                },

                async exportPdf() {
                    if (!this.feedback) return;
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
                    const W = doc.internal.pageSize.getWidth();
                    const margin = 18;
                    const maxW = W - margin * 2;
                    let y = 20;

                    const addText = (text, opts = {}) => {
                        const { size = 10, bold = false, color = [30,30,30], indent = 0 } = opts;
                        doc.setFontSize(size);
                        doc.setFont('helvetica', bold ? 'bold' : 'normal');
                        doc.setTextColor(...color);
                        const lines = doc.splitTextToSize(String(text), maxW - indent);
                        lines.forEach(line => {
                            if (y > 275) { doc.addPage(); y = 20; }
                            doc.text(line, margin + indent, y);
                            y += size * 0.45;
                        });
                        y += 2;
                    };

                    const addSection = (title, color = [16,185,129]) => {
                        if (y > 260) { doc.addPage(); y = 20; }
                        y += 3;
                        doc.setFillColor(...color);
                        doc.rect(margin, y - 4, 3, 6, 'F');
                        addText(title, { size: 12, bold: true, color });
                    };

                    // Header
                    doc.setFillColor(15, 23, 42);
                    doc.rect(0, 0, W, 28, 'F');
                    doc.setFontSize(16); doc.setFont('helvetica', 'bold');
                    doc.setTextColor(52, 211, 153);
                    doc.text('Lamaraja', margin, 12);
                    doc.setFontSize(9); doc.setFont('helvetica', 'normal');
                    doc.setTextColor(148, 163, 184);
                    doc.text('Hasil Latihan Interview AI', margin, 19);
                    const dateStr = new Date().toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });
                    doc.text(dateStr, W - margin, 19, { align: 'right' });
                    y = 38;

                    // Skor keseluruhan
                    const score = this.feedback.overall_score || 0;
                    const scoreColor = score >= 80 ? [16,185,129] : score >= 60 ? [245,158,11] : [239,68,68];
                    doc.setFontSize(36); doc.setFont('helvetica', 'bold');
                    doc.setTextColor(...scoreColor);
                    doc.text(`${score}`, margin, y + 10);
                    doc.setFontSize(14); doc.setTextColor(100,100,100);
                    doc.text('/100', margin + 20, y + 10);
                    doc.setFontSize(10); doc.setFont('helvetica', 'normal');
                    doc.setTextColor(60,60,60);
                    const role = this.role || 'Umum';
                    doc.text(`Target Role: ${role}`, margin + 40, y + 5);
                    doc.text(`Mode: ${this.interviewMode || 'Mixed'}`, margin + 40, y + 11);
                    y += 24;

                    // Sub-scores
                    const subScores = [
                        ['Komunikasi', this.feedback.communication_score],
                        ['Relevansi', this.feedback.relevance_score],
                        ['Kepercayaan Diri', this.feedback.confidence_score],
                        ['Kesesuaian Role', this.feedback.role_fit_score],
                    ].filter(([,v]) => v != null);
                    if (subScores.length) {
                        const colW = maxW / subScores.length;
                        subScores.forEach(([label, val], i) => {
                            const x = margin + i * colW;
                            doc.setFillColor(240,253,244);
                            doc.roundedRect(x, y, colW - 3, 16, 2, 2, 'F');
                            doc.setFontSize(14); doc.setFont('helvetica','bold'); doc.setTextColor(...scoreColor);
                            doc.text(`${val}`, x + colW/2 - 1.5, y + 8, { align: 'center' });
                            doc.setFontSize(7); doc.setFont('helvetica','normal'); doc.setTextColor(80,80,80);
                            doc.text(label, x + colW/2 - 1.5, y + 13, { align: 'center' });
                        });
                        y += 22;
                    }

                    // Ringkasan
                    if (this.feedback.summary) {
                        addSection('Ringkasan');
                        addText(this.feedback.summary, { color: [50,50,50] });
                    }

                    // Kekuatan
                    if (this.feedback.strengths?.length) {
                        addSection('Kekuatan', [16,185,129]);
                        this.feedback.strengths.forEach(s => addText(`• ${s}`, { indent: 4 }));
                    }

                    // Kelemahan
                    if (this.feedback.weaknesses?.length) {
                        addSection('Perlu Dilatih', [245,158,11]);
                        this.feedback.weaknesses.forEach(s => addText(`• ${s}`, { indent: 4, color: [80,60,10] }));
                    }

                    // Improved answers
                    if (this.feedback.improved_answers?.length) {
                        addSection('Saran Jawaban Lebih Baik', [99,102,241]);
                        this.feedback.improved_answers.forEach((item, i) => {
                            addText(`${i+1}. Isu: ${item.original_issue}`, { bold: true, color: [80,80,80], indent: 2 });
                            addText(`Lebih baik: ${item.better_answer}`, { color: [60,60,120], indent: 6 });
                            y += 2;
                        });
                    }

                    // Action plan
                    if (this.feedback.action_plan?.length) {
                        addSection('Rencana Aksi', [59,130,246]);
                        this.feedback.action_plan.forEach((s, i) => addText(`${i+1}. ${s}`, { indent: 4, color: [30,60,100] }));
                    }

                    // Transkrip
                    if (this.transcript.length) {
                        addSection('Transkrip Percakapan', [100,100,100]);
                        this.transcript.filter(m => m.role !== 'system').forEach(m => {
                            const isCandidate = m.role === 'candidate';
                            addText(isCandidate ? 'Kamu:' : 'Interviewer:', { bold: true, size: 9, color: isCandidate ? [16,120,80] : [60,60,60], indent: 2 });
                            addText(m.content_text, { size: 9, color: [50,50,50], indent: 6 });
                            y += 1;
                        });
                    }

                    // Footer
                    const pageCount = doc.internal.getNumberOfPages();
                    for (let i = 1; i <= pageCount; i++) {
                        doc.setPage(i);
                        doc.setFontSize(8); doc.setFont('helvetica','normal'); doc.setTextColor(150,150,150);
                        doc.text(`Lamaraja — lamaraja.web.id | Halaman ${i} dari ${pageCount}`, W/2, 290, { align: 'center' });
                    }

                    const filename = `lamaraja-interview-${role.toLowerCase().replace(/\s+/g,'-')}-${new Date().toISOString().slice(0,10)}.pdf`;
                    doc.save(filename);
                },
            };
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>
</x-layout>
