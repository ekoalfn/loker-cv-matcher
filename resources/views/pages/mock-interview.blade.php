<x-layout robots="noindex, nofollow">
    <x-slot:title>Mock Interview AI - Simulasi Interview Kerja Berbasis CV | Lamaraja</x-slot:title>
    <x-slot:description>Upload CV PDF dan latihan interview kerja dengan AI interviewer Lamaraja. Mode voice, transkrip, dan feedback akhir untuk persiapan interview.</x-slot:description>

    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-teal-50 text-slate-950" x-data="mockInterview()">
        <div class="absolute -top-24 -right-24 h-80 w-80 rounded-full bg-emerald-200/50 blur-3xl"></div>
        <div class="absolute top-44 -left-24 h-72 w-72 rounded-full bg-teal-100/70 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 flex justify-end">
            <form action="{{ route('mock-interview.logout') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 min-h-[2.5rem] px-4 py-2 rounded-xl text-sm font-medium text-stone-600 hover:text-red-600 hover:bg-red-50 transition-colors interactive-focus" style="background: #ffffff; border: 1px solid #d1fae5;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Logout
                </button>
            </form>
        </div>

        <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-18">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_.82fr] gap-10 items-center">
                <div>
                    <p class="inline-flex rounded-full border border-emerald-100 bg-white px-4 py-2 text-sm font-bold text-emerald-600 shadow-sm">Mock Interview Voice Beta</p>
                    <h1 class="mt-6 font-[family-name:var(--font-display)] text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-slate-950">
                        Latihan interview seperti <span class="text-emerald-600">telepon HR</span>.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                        Upload CV, pilih target role, lalu Lamaraja membuat sesi tanya jawab personal. AI interviewer membacakan pertanyaan via API TTS dan jawaban rekamanmu ditranskrip dengan STT Indonesia.
                    </p>
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm"><div class="text-2xl font-extrabold text-emerald-600">6</div><div class="text-sm text-slate-500">pertanyaan adaptif</div></div>
                        <div class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm"><div class="text-2xl font-extrabold text-emerald-600">Voice</div><div class="text-sm text-slate-500">TTS + STT API</div></div>
                        <div class="rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm"><div class="text-2xl font-extrabold text-emerald-600">Skor</div><div class="text-sm text-slate-500">feedback akhir</div></div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-emerald-100 bg-white p-5 sm:p-7 text-slate-950 shadow-2xl shadow-emerald-900/10">
                    <template x-if="!session">
                        <form @submit.prevent="start" class="space-y-5">
                            <div>
                                <label class="block text-sm font-black text-slate-900 mb-2">Upload CV PDF</label>
                                <label class="group flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-emerald-200 bg-emerald-50 px-5 py-8 text-center transition hover:border-emerald-500">
                                    <input type="file" accept="application/pdf,.pdf" class="sr-only" @change="handleFile">
                                    <div class="h-14 w-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200 transition group-hover:scale-105">
                                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.5a6 6 0 006-6v-1m-12 1v1a6 6 0 006 6zm0 0V22m-4 0h8M12 3a3 3 0 00-3 3v6a3 3 0 006 0V6a3 3 0 00-3-3z"/></svg>
                                    </div>
                                    <div class="mt-4 font-bold" x-text="file ? file.name : 'Klik untuk memilih CV'"></div>
                                    <div class="mt-1 text-sm text-slate-500">PDF maksimal 5MB. File dihapus setelah teks terbaca.</div>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-black text-slate-900 mb-2">Target role</label>
                                <input x-model="targetRole" type="text" placeholder="Contoh: Product Manager, Backend Engineer, Admin Finance" class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <div>
                                <label class="block text-sm font-black text-slate-900 mb-2">Atau pilih lowongan Lamaraja</label>
                                <select x-model="jobId" class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">Tanpa target lowongan spesifik</option>
                                    @foreach ($jobs as $job)
                                        <option value="{{ $job->id }}" @selected($selectedJobId === $job->id)>{{ $job->title }} — {{ $job->company }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <label class="block"><span class="block text-sm font-black text-slate-900 mb-2">Mode</span><select x-model="interviewMode" class="w-full rounded-2xl border border-slate-200 px-4 py-3"><option value="mixed">Mixed</option><option value="hr">HR</option><option value="technical">Technical</option><option value="behavioral">Behavioral</option></select></label>
                                <label class="block"><span class="block text-sm font-black text-slate-900 mb-2">Delivery</span><select x-model="deliveryMode" class="w-full rounded-2xl border border-slate-200 px-4 py-3"><option value="voice">Voice</option><option value="text">Text</option></select></label>
                            </div>

                            <div x-show="error" x-cloak class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" x-text="error"></div>

                            <button type="submit" :disabled="loading || !file" class="w-full rounded-2xl bg-emerald-600 px-5 py-4 font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                                <span x-show="!loading">Mulai Simulasi Interview</span>
                                <span x-show="loading" x-cloak>Menganalisis CV dan menyiapkan interviewer...</span>
                            </button>
                        </form>
                    </template>

                    <template x-if="session">
                        <div class="space-y-5">
                            <div class="rounded-[1.5rem] border border-emerald-100 bg-emerald-50 p-5 text-slate-950">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-bold text-emerald-700">Lamaraja Interview Coach</p>
                                        <h2 class="mt-1 text-xl font-black" x-text="session.target_role"></h2>
                                    </div>
                                    <div class="rounded-2xl bg-white px-4 py-2 text-sm font-black text-emerald-700 shadow-sm" x-text="session.current_question_count + '/' + session.max_questions"></div>
                                </div>
                                <div class="mt-5 flex items-center justify-center">
                                    <div :class="speaking ? 'animate-pulse scale-105' : ''" class="h-28 w-28 rounded-full border-4 border-white bg-gradient-to-br from-emerald-500 to-teal-400 shadow-2xl shadow-emerald-300/40 flex items-center justify-center text-4xl text-white">☎</div>
                                </div>
                                <p class="mt-4 text-center text-sm text-slate-600" x-text="speaking ? 'AI sedang berbicara...' : 'Mode call simulation aktif'"></p>
                            </div>

                            <div class="max-h-[24rem] space-y-3 overflow-y-auto pr-1">
                                <template x-for="(message, index) in session.messages" :key="index">
                                    <div :class="message.role === 'candidate' ? 'ml-8 bg-emerald-50 border-emerald-100' : 'mr-8 bg-slate-50 border-slate-200'" class="rounded-2xl border p-4">
                                        <div class="text-xs font-black uppercase tracking-wide" :class="message.role === 'candidate' ? 'text-emerald-700' : 'text-slate-500'" x-text="label(message.role)"></div>
                                        <div class="mt-2 text-sm leading-6 text-slate-800 whitespace-pre-line" x-text="message.content_text"></div>
                                    </div>
                                </template>
                            </div>

                            <template x-if="session.status === 'active'">
                                <form @submit.prevent="reply" class="space-y-3">
                                    <textarea x-model="answer" rows="4" placeholder="Jawab pertanyaan interviewer di sini, atau rekam suara lalu biarkan STT mengisi transkripnya." class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <button type="button" @click="speakLastQuestion" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm font-black text-slate-800 hover:bg-slate-50">Ulangi Suara</button>
                                        <button type="button" @click="toggleRecording" :disabled="transcribing" class="rounded-2xl border border-emerald-200 px-4 py-3 text-sm font-black text-emerald-700 hover:bg-emerald-50 disabled:opacity-50" x-text="recording ? 'Stop Rekaman' : (transcribing ? 'Transkrip...' : 'Rekam Jawaban')"></button>
                                        <button type="submit" :disabled="loading || !answer.trim()" class="rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-black text-white hover:bg-emerald-700 disabled:opacity-50">Kirim Jawaban</button>
                                    </div>
                                    <button type="button" @click="finish" class="w-full rounded-2xl bg-slate-100 px-4 py-3 text-sm font-black text-slate-700 hover:bg-slate-200">Akhiri dan Lihat Feedback</button>
                                </form>
                            </template>

                            <template x-if="session.status === 'completed' && session.final_feedback">
                                <div class="rounded-[1.5rem] border border-emerald-100 bg-emerald-50 p-5 text-slate-900">
                                    <div class="text-sm font-black uppercase tracking-wide text-emerald-700">Feedback Akhir</div>
                                    <div class="mt-2 text-4xl font-black text-emerald-700" x-text="(session.final_feedback.overall_score || 0) + '/100'"></div>
                                    <p class="mt-3 text-sm leading-6" x-text="session.final_feedback.summary"></p>
                                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div><h3 class="font-black">Kekuatan</h3><ul class="mt-2 space-y-1 text-sm"><template x-for="item in session.final_feedback.strengths || []"><li x-text="'• ' + item"></li></template></ul></div>
                                        <div><h3 class="font-black">Perlu dilatih</h3><ul class="mt-2 space-y-1 text-sm"><template x-for="item in session.final_feedback.weaknesses || []"><li x-text="'• ' + item"></li></template></ul></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </section>
    </div>

    <script>
        function mockInterview() {
            return {
                file: null,
                loading: false,
                error: null,
                session: null,
                targetRole: '',
                jobId: @json($selectedJobId ? (string) $selectedJobId : ''),
                interviewMode: 'mixed',
                deliveryMode: 'voice',
                answer: '',
                speaking: false,
                recording: false,
                transcribing: false,
                mediaRecorder: null,
                audioChunks: [],
                currentAudio: null,
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
                async start() {
                    this.loading = true;
                    this.error = null;
                    const form = new FormData();
                    form.append('pdf_file', this.file);
                    form.append('target_role', this.targetRole);
                    form.append('job_id', this.jobId);
                    form.append('interview_mode', this.interviewMode);
                    form.append('delivery_mode', this.deliveryMode);
                    try {
                        const response = await fetch('{{ route('mock-interview.start') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: form });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Gagal memulai interview.');
                        this.session = data;
                        this.$nextTick(() => this.speakLastQuestion());
                    } catch (error) {
                        this.error = error.message;
                    } finally {
                        this.loading = false;
                    }
                },
                async reply() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const response = await fetch(`/mock-interview/${this.session.token}/reply`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
                            body: JSON.stringify({ answer: this.answer }),
                        });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Gagal mengirim jawaban.');
                        this.session = data.session;
                        this.answer = '';
                        this.$nextTick(() => this.speakLastQuestion());
                    } catch (error) {
                        this.error = error.message;
                    } finally {
                        this.loading = false;
                    }
                },
                async finish() {
                    this.loading = true;
                    try {
                        const response = await fetch(`/mock-interview/${this.session.token}/finish`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Gagal menyelesaikan sesi.');
                        this.session = data;
                    } catch (error) {
                        this.error = error.message;
                    } finally {
                        this.loading = false;
                    }
                },
                lastQuestion() {
                    return [...(this.session?.messages || [])].reverse().find(message => message.role === 'interviewer')?.content_text || '';
                },
                async speakLastQuestion() {
                    if (this.deliveryMode !== 'voice') return;
                    const text = this.lastQuestion();
                    if (!text) return;
                    if (this.currentAudio) this.currentAudio.pause();
                    this.speaking = true;
                    try {
                        const response = await fetch('{{ route('mock-interview.speech') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'audio/mpeg', 'Content-Type': 'application/json' },
                            body: JSON.stringify({ input: text }),
                        });
                        if (!response.ok) {
                            let message = 'Gagal memutar suara interviewer.';
                            try { message = (await response.json()).message || message; } catch (_) {}
                            throw new Error(message);
                        }
                        const audioUrl = URL.createObjectURL(await response.blob());
                        this.currentAudio = new Audio(audioUrl);
                        this.currentAudio.onended = () => { this.speaking = false; URL.revokeObjectURL(audioUrl); };
                        this.currentAudio.onerror = () => { this.speaking = false; URL.revokeObjectURL(audioUrl); };
                        await this.currentAudio.play();
                    } catch (error) {
                        this.speaking = false;
                        this.error = error.message;
                    }
                },
                async toggleRecording() {
                    if (this.recording && this.mediaRecorder) {
                        this.mediaRecorder.stop();
                        return;
                    }

                    if (!navigator.mediaDevices?.getUserMedia) {
                        this.error = 'Browser ini belum mendukung rekaman suara. Pakai input teks dulu.';
                        return;
                    }

                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        this.audioChunks = [];
                        this.mediaRecorder = new MediaRecorder(stream);
                        this.mediaRecorder.ondataavailable = event => {
                            if (event.data.size > 0) this.audioChunks.push(event.data);
                        };
                        this.mediaRecorder.onstop = () => {
                            stream.getTracks().forEach(track => track.stop());
                            this.recording = false;
                            this.transcribeRecording();
                        };
                        this.mediaRecorder.start();
                        this.recording = true;
                        this.error = null;
                    } catch (error) {
                        this.error = 'Tidak bisa mengakses mikrofon: ' + error.message;
                    }
                },
                async transcribeRecording() {
                    if (!this.audioChunks.length) return;
                    this.transcribing = true;
                    try {
                        const blob = new Blob(this.audioChunks, { type: this.audioChunks[0]?.type || 'audio/webm' });
                        const form = new FormData();
                        form.append('audio', blob, 'jawaban.webm');
                        const response = await fetch('{{ route('mock-interview.transcribe') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: form,
                        });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Gagal transkrip rekaman.');
                        this.answer = [this.answer, data.text].filter(Boolean).join(' ').trim();
                    } catch (error) {
                        this.error = error.message;
                    } finally {
                        this.transcribing = false;
                        this.audioChunks = [];
                    }
                },
                label(role) {
                    return role === 'candidate' ? 'Kandidat' : (role === 'system' ? 'Coach' : 'Interviewer');
                },
            };
        }
    </script>
</x-layout>
