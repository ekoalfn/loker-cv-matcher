<x-layout>
    <x-slot:title>Mock Interview AI - Simulasi Interview Kerja Berbasis CV | Lamaraja</x-slot:title>
    <x-slot:description>Upload CV PDF dan latihan interview kerja dengan AI interviewer Lamaraja. Mode voice, transkrip, dan feedback akhir untuk persiapan interview.</x-slot:description>

    <div class="relative overflow-hidden bg-[#07130f] text-white" x-data="mockInterview()">
        <div class="absolute inset-0 opacity-70" style="background: radial-gradient(circle at 18% 18%, rgba(52,211,153,.34), transparent 30%), radial-gradient(circle at 88% 8%, rgba(20,184,166,.22), transparent 28%), linear-gradient(135deg, #07130f 0%, #0b221b 48%, #06100d 100%);"></div>
        <div class="absolute inset-0 opacity-[.08]" style="background-image: linear-gradient(rgba(255,255,255,.7) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.7) 1px, transparent 1px); background-size: 44px 44px;"></div>

        <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_.82fr] gap-10 items-center">
                <div>
                    <p class="inline-flex rounded-full border border-emerald-300/30 bg-emerald-300/10 px-4 py-2 text-sm font-bold text-emerald-100">Mock Interview Voice Beta</p>
                    <h1 class="mt-6 font-[family-name:var(--font-display)] text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-[0.95]">
                        Latihan interview seperti <span class="text-emerald-300">telepon HR</span>.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-emerald-50/80">
                        Upload CV, pilih target role, lalu Lamaraja membuat sesi tanya jawab personal. AI interviewer membacakan pertanyaan lewat browser voice dan kamu bisa menjawab dengan teks atau dikte suara.
                    </p>
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur"><div class="text-2xl font-black">6</div><div class="text-sm text-emerald-50/70">pertanyaan adaptif</div></div>
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur"><div class="text-2xl font-black">Voice</div><div class="text-sm text-emerald-50/70">TTS browser</div></div>
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur"><div class="text-2xl font-black">Skor</div><div class="text-sm text-emerald-50/70">feedback akhir</div></div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-white/10 bg-white/95 p-5 sm:p-7 text-slate-950 shadow-2xl shadow-emerald-950/40">
                    <template x-if="!session">
                        <form @submit.prevent="start" class="space-y-5">
                            <div>
                                <label class="block text-sm font-black text-slate-900 mb-2">Upload CV PDF</label>
                                <label class="group flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-emerald-200 bg-emerald-50 px-5 py-8 text-center transition hover:border-emerald-500">
                                    <input type="file" accept="application/pdf,.pdf" class="sr-only" @change="handleFile">
                                    <div class="h-14 w-14 rounded-2xl bg-slate-950 text-emerald-200 flex items-center justify-center shadow-lg transition group-hover:scale-105">
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

                            <button type="submit" :disabled="loading || !file" class="w-full rounded-2xl bg-slate-950 px-5 py-4 font-black text-white shadow-lg transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                                <span x-show="!loading">Mulai Simulasi Interview</span>
                                <span x-show="loading" x-cloak>Menganalisis CV dan menyiapkan interviewer...</span>
                            </button>
                        </form>
                    </template>

                    <template x-if="session">
                        <div class="space-y-5">
                            <div class="rounded-[1.5rem] bg-slate-950 p-5 text-white">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-bold text-emerald-200">Lamaraja Interview Coach</p>
                                        <h2 class="mt-1 text-xl font-black" x-text="session.target_role"></h2>
                                    </div>
                                    <div class="rounded-2xl bg-emerald-300 px-4 py-2 text-sm font-black text-slate-950" x-text="session.current_question_count + '/' + session.max_questions"></div>
                                </div>
                                <div class="mt-5 flex items-center justify-center">
                                    <div :class="speaking ? 'animate-pulse scale-105' : ''" class="h-28 w-28 rounded-full border-4 border-emerald-300 bg-gradient-to-br from-emerald-300 to-teal-200 shadow-2xl shadow-emerald-400/30 flex items-center justify-center text-4xl">☎</div>
                                </div>
                                <p class="mt-4 text-center text-sm text-emerald-50/70" x-text="speaking ? 'AI sedang berbicara...' : 'Mode call simulation aktif'"></p>
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
                                    <textarea x-model="answer" rows="4" placeholder="Jawab pertanyaan interviewer di sini. Kamu juga bisa pakai tombol dikte jika browser mendukung." class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <button type="button" @click="speakLastQuestion" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm font-black text-slate-800 hover:bg-slate-50">Ulangi Suara</button>
                                        <button type="button" @click="toggleDictation" class="rounded-2xl border border-emerald-200 px-4 py-3 text-sm font-black text-emerald-700 hover:bg-emerald-50" x-text="listening ? 'Stop Dikte' : 'Dikte Jawaban'"></button>
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
                listening: false,
                recognition: null,
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
                speakLastQuestion() {
                    if (this.deliveryMode !== 'voice' || !('speechSynthesis' in window)) return;
                    const text = this.lastQuestion();
                    if (!text) return;
                    window.speechSynthesis.cancel();
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID';
                    utterance.rate = 0.95;
                    utterance.onstart = () => this.speaking = true;
                    utterance.onend = () => this.speaking = false;
                    utterance.onerror = () => this.speaking = false;
                    window.speechSynthesis.speak(utterance);
                },
                toggleDictation() {
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (!SpeechRecognition) {
                        this.error = 'Browser ini belum mendukung speech recognition. Pakai input teks dulu.';
                        return;
                    }
                    if (this.listening && this.recognition) {
                        this.recognition.stop();
                        return;
                    }
                    this.recognition = new SpeechRecognition();
                    this.recognition.lang = 'id-ID';
                    this.recognition.interimResults = true;
                    this.recognition.onstart = () => this.listening = true;
                    this.recognition.onend = () => this.listening = false;
                    this.recognition.onresult = event => {
                        let transcript = '';
                        for (let i = event.resultIndex; i < event.results.length; i++) transcript += event.results[i][0].transcript;
                        this.answer = (this.answer + ' ' + transcript).trim();
                    };
                    this.recognition.start();
                },
                label(role) {
                    return role === 'candidate' ? 'Kandidat' : (role === 'system' ? 'Coach' : 'Interviewer');
                },
            };
        }
    </script>
</x-layout>
