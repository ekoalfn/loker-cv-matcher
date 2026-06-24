<x-layout
    title="AI Skill Gap Analyzer - Cek Skill yang Kurang | Lamaraja"
    description="Analisis skill gap dengan AI: dari CV dan target role, lihat skill yang sudah dimiliki, yang masih kurang, dan rencana belajar untuk menutup kesenjangan."
    robots="index, follow"
    canonical="{{ route('ai-tools.skill-gap') }}"
    ogImage="{{ url('/images/og-skill-gap.jpg') }}"
>
    @php
        $howToLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => 'Cara Menganalisis Skill Gap dengan AI',
            'description' => 'Analisis skill gap kamu berdasarkan posisi yang dituju secara gratis menggunakan AI Lamaraja',
            'step' => [
                ['@type' => 'HowToStep', 'name' => 'Masukkan posisi yang dituju', 'text' => 'Ketik posisi pekerjaan yang ingin kamu lamar, misalnya Frontend Developer atau Marketing Manager'],
                ['@type' => 'HowToStep', 'name' => 'Upload CV kamu', 'text' => 'Upload file CV dalam format PDF atau paste teks CV kamu'],
                ['@type' => 'HowToStep', 'name' => 'Klik Analisis Skill Gap', 'text' => 'Klik tombol analisis dan tunggu beberapa detik'],
                ['@type' => 'HowToStep', 'name' => 'Baca hasil analisis', 'text' => 'Dapatkan daftar skill yang sudah kamu kuasai, skill yang kurang, dan rekomendasi cara mengisinya'],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $faqLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'Apa itu Skill Gap Analyzer Lamaraja?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Skill Gap Analyzer adalah tool AI gratis yang menganalisis CV kamu dan membandingkannya dengan target role atau lowongan, lalu menunjukkan skill yang sudah dimiliki, yang masih kurang, dan rencana belajar untuk menutup kesenjangan.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Bagaimana cara menggunakan Skill Gap Analyzer?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Upload CV PDF atau paste teks CV, lalu masukkan target role atau deskripsi lowongan yang dituju. AI akan menganalisis dan menghasilkan laporan skill gap dalam hitungan detik.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Apakah hasil analisis skill gap akurat?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Hasil analisis didasarkan pada CV dan deskripsi yang kamu berikan. Semakin lengkap CV dan deskripsi lowongan, semakin akurat rekomendasinya.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Skill gap apa saja yang bisa dideteksi?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Tool ini bisa mendeteksi skill teknis (hard skills), soft skills, sertifikasi, pengalaman industri, dan tools spesifik yang dibutuhkan untuk posisi yang dituju.',
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $softwareLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => 'AI Skill Gap Analyzer Lamaraja',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'url' => route('ai-tools.skill-gap'),
            'description' => 'Tool gratis untuk menganalisis skill gap antara CV dan target role menggunakan AI, lengkap dengan rencana pengembangan skill.',
            'offers' => [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'IDR',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp

    <script type="application/ld+json">{!! $howToLd !!}</script>
    <script type="application/ld+json">{!! $faqLd !!}</script>
    <script type="application/ld+json">{!! $softwareLd !!}</script>

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

        {{-- Content Section: Informatif & SEO --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 border-t border-emerald-100">

            <h2 class="font-[family-name:var(--font-display)] text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950 mt-0">Apa itu Skill Gap Analyzer?</h2>
            <p class="mt-4 text-base leading-7 text-slate-600">
                Skill Gap Analyzer adalah alat berbasis kecerdasan buatan (AI) yang membantu kamu memahami kesenjangan antara kemampuan yang kamu miliki saat ini dengan kualifikasi yang dibutuhkan untuk posisi pekerjaan yang ingin kamu lamar. Alat ini bekerja dengan cara membandingkan isi CV kamu — mulai dari pengalaman kerja, pendidikan, hingga keterampilan teknis — dengan persyaratan yang biasanya dibutuhkan untuk sebuah target role tertentu.
            </p>
            <p class="mt-3 text-base leading-7 text-slate-600">
                Hasil analisis akan menampilkan tiga hal utama: skill yang sudah kamu kuasai dan relevan dengan posisi yang dituju, skill yang masih kurang atau belum tercantum di CV, serta rencana belajar konkret untuk menutup kesenjangan tersebut. Dengan informasi ini, kamu bisa membuat prioritas pengembangan diri yang tepat sasaran — tidak perlu lagi belajar hal-hal yang tidak relevan atau meraba-raba kualifikasi apa yang sebenarnya dibutuhkan oleh recruiter.
            </p>
            <p class="mt-3 text-base leading-7 text-slate-600">
                Skill Gap Analyzer Lamaraja dirancang khusus untuk pencari kerja di Indonesia yang ingin meningkatkan peluang mendapatkan pekerjaan impian. Tool ini gratis digunakan dan tidak memerlukan akun berbayar.
            </p>

            <h2 class="font-[family-name:var(--font-display)] text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950 mt-10">Cara Menggunakan Skill Gap Analyzer</h2>
            <p class="mt-4 text-base leading-7 text-slate-600">Ikuti langkah-langkah berikut untuk mendapatkan analisis skill gap yang akurat:</p>
            <ol class="mt-4 space-y-3 list-decimal list-inside text-base leading-7 text-slate-600">
                <li><span class="font-semibold text-slate-800">Upload CV PDF kamu</span> — Pilih file CV dalam format PDF dari perangkatmu. Pastikan CV memuat pengalaman kerja, pendidikan, dan skill yang relevan untuk hasil analisis yang optimal.</li>
                <li><span class="font-semibold text-slate-800">Masukkan target role</span> — Ketik posisi pekerjaan yang ingin kamu lamar, misalnya "Data Analyst", "Product Manager", atau "Frontend Developer". Semakin spesifik, semakin akurat rekomendasinya.</li>
                <li><span class="font-semibold text-slate-800">Klik tombol Analisis Skill Gap</span> — AI akan memproses CV dan target role kamu dalam hitungan detik.</li>
                <li><span class="font-semibold text-slate-800">Baca dan gunakan hasil analisis</span> — Lihat daftar skill yang sudah cocok, skill yang masih kurang, dan ikuti rencana belajar yang diberikan untuk meningkatkan kesiapanmu.</li>
            </ol>

            <h2 class="font-[family-name:var(--font-display)] text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950 mt-10">Mengapa Penting Mengetahui Skill Gap Kamu?</h2>
            <p class="mt-4 text-base leading-7 text-slate-600">
                Di pasar kerja yang semakin kompetitif, memahami skill gap bukan sekadar pilihan — ini adalah strategi yang wajib dimiliki setiap pencari kerja. Tanpa mengetahui kesenjangan kemampuan, kamu bisa menghabiskan waktu melamar posisi yang sebenarnya belum kamu kualifikasikan, atau sebaliknya, meremehkan kemampuan diri sendiri dan melewatkan peluang yang seharusnya bisa kamu raih.
            </p>
            <p class="mt-3 text-base leading-7 text-slate-600">
                Dengan mengetahui skill gap secara spesifik, kamu bisa mengalokasikan waktu belajar dengan lebih efisien, memprioritaskan sertifikasi atau kursus yang benar-benar dibutuhkan recruiter, dan menyesuaikan konten CV agar lebih relevan dengan posisi yang dituju. Hasilnya, peluang kamu untuk dipanggil interview akan jauh lebih besar karena profil kamu memang sesuai dengan apa yang dicari perusahaan.
            </p>

            <h2 class="font-[family-name:var(--font-display)] text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950 mt-10">Pertanyaan Umum</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <h3 class="font-bold text-slate-800">Apakah Skill Gap Analyzer ini gratis?</h3>
                    <p class="mt-2 text-base leading-7 text-slate-600">Ya, Skill Gap Analyzer Lamaraja sepenuhnya gratis digunakan. Kamu bisa menganalisis skill gap sebanyak yang kamu butuhkan tanpa perlu mendaftar atau berlangganan.</p>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Seberapa akurat hasil analisisnya?</h3>
                    <p class="mt-2 text-base leading-7 text-slate-600">Akurasi analisis bergantung pada kelengkapan CV yang kamu upload. Semakin detail CV kamu mencantumkan pengalaman, skill, dan pencapaian, semakin akurat dan relevan hasil rekomendasinya.</p>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Skill apa saja yang bisa dideteksi?</h3>
                    <p class="mt-2 text-base leading-7 text-slate-600">AI kami bisa mendeteksi hard skills (kemampuan teknis seperti Python, Excel, atau desain grafis), soft skills (komunikasi, kepemimpinan), sertifikasi yang relevan, serta pengalaman industri yang dibutuhkan untuk target role kamu.</p>
                </div>
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
