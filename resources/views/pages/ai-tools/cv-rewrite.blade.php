<x-layout
    title="AI CV Rewrite & ATS Optimizer Gratis | Lamaraja"
    description="Ubah pengalaman kerja di CV jadi bullet point ATS-friendly dengan AI. Optimalkan CV agar lolos screening ATS dan lebih menonjol di mata recruiter."
    robots="index, follow"
    canonical="{{ route('ai-tools.cv-rewrite') }}"
    ogImage="{{ url('/images/og-cv-rewrite.jpg') }}"
>
    @php
        $howToLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => 'Cara Mengoptimasi CV dengan AI ATS Optimizer',
            'description' => 'Optimalkan CV kamu agar lolos ATS dan meningkatkan peluang dipanggil interview',
            'step' => [
                ['@type' => 'HowToStep', 'name' => 'Upload CV PDF', 'text' => 'Upload file CV kamu dalam format PDF'],
                ['@type' => 'HowToStep', 'name' => 'Masukkan target role', 'text' => 'Ketik posisi yang ingin kamu lamar'],
                ['@type' => 'HowToStep', 'name' => 'Klik Optimalkan CV', 'text' => 'Klik tombol dan biarkan AI menganalisis CV kamu'],
                ['@type' => 'HowToStep', 'name' => 'Copy hasil optimasi', 'text' => 'Salin bullet point ATS-friendly yang dihasilkan AI dan gunakan di CV kamu'],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $faqLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'Apa itu AI CV Rewrite Lamaraja?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'AI CV Rewrite Lamaraja adalah tool gratis yang mengubah pengalaman kerja di CV menjadi bullet point ATS-friendly berbasis pencapaian, sehingga CV lebih mudah lolos screening otomatis rekruter.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Apa itu ATS dan kenapa CV perlu dioptimasi?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'ATS (Applicant Tracking System) adalah software yang dipakai perusahaan untuk filter CV secara otomatis. CV yang tidak ATS-friendly bisa otomatis tersingkir sebelum dibaca rekruter.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Format CV apa yang didukung?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Saat ini AI CV Rewrite mendukung input teks langsung. Salin pengalaman kerja dari CV kamu ke dalam form, lalu AI akan merewritenya.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Apakah hasil rewrite bisa langsung dipakai?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Ya, hasil rewrite bisa langsung disalin ke CV kamu. Disarankan untuk review dan sesuaikan dengan gaya penulisan personalmu.',
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $softwareLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => 'AI CV Rewrite & ATS Optimizer Lamaraja',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'url' => route('ai-tools.cv-rewrite'),
            'description' => 'Tool gratis untuk mengoptimasi CV agar ATS-friendly dengan AI, mengubah pengalaman kerja jadi bullet point berbasis pencapaian.',
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

        {{-- Content Section: Informatif & SEO --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 border-t border-emerald-100">

            <h2 class="font-[family-name:var(--font-display)] text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950 mt-0">Apa itu CV Rewrite & ATS Optimizer?</h2>
            <p class="mt-4 text-base leading-7 text-slate-600">
                CV Rewrite & ATS Optimizer adalah alat berbasis kecerdasan buatan (AI) yang membantu kamu mengubah pengalaman kerja biasa di CV menjadi bullet point yang kuat, berbasis pencapaian, dan ramah terhadap Applicant Tracking System (ATS). Banyak pencari kerja tidak menyadari bahwa CV mereka gagal lolos seleksi awal bukan karena kurangnya pengalaman, melainkan karena format atau kata kunci yang tidak sesuai dengan sistem screening otomatis yang digunakan perusahaan.
            </p>
            <p class="mt-3 text-base leading-7 text-slate-600">
                Alat ini bekerja dengan cara menganalisis teks CV kamu, memahami konteks pengalaman kerjamu, lalu menyusun ulang setiap poin pengalaman menjadi kalimat yang lebih impactful, terukur, dan mengandung kata kunci yang relevan untuk industri serta posisi yang kamu tuju. Hasilnya adalah CV yang lebih mudah dibaca oleh ATS sekaligus lebih menarik di mata recruiter manusia.
            </p>
            <p class="mt-3 text-base leading-7 text-slate-600">
                CV Rewrite & ATS Optimizer Lamaraja dirancang untuk semua level pencari kerja — mulai dari fresh graduate yang ingin membuat CV pertama mereka lebih profesional, hingga profesional berpengalaman yang ingin meningkatkan tingkat respons lamaran kerja mereka.
            </p>

            <h2 class="font-[family-name:var(--font-display)] text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950 mt-10">Cara Mengoptimasi CV dengan AI</h2>
            <p class="mt-4 text-base leading-7 text-slate-600">Ikuti langkah berikut untuk mengoptimasi CV kamu agar lolos ATS dan menarik perhatian recruiter:</p>
            <ol class="mt-4 space-y-3 list-decimal list-inside text-base leading-7 text-slate-600">
                <li><span class="font-semibold text-slate-800">Upload CV PDF kamu</span> — Pilih file CV dalam format PDF. AI akan membaca dan menganalisis seluruh konten CV secara otomatis.</li>
                <li><span class="font-semibold text-slate-800">Masukkan target role (opsional)</span> — Ketik posisi yang ingin kamu lamar, misalnya "Backend Developer" atau "Digital Marketing Specialist". Informasi ini membantu AI menyesuaikan kata kunci yang relevan dengan posisi tersebut.</li>
                <li><span class="font-semibold text-slate-800">Klik tombol Optimalkan CV</span> — AI akan memproses CV kamu dan menghasilkan bullet point ATS-friendly dalam hitungan detik.</li>
                <li><span class="font-semibold text-slate-800">Salin dan gunakan hasilnya</span> — Copy bullet point hasil optimasi ke CV kamu. Kamu juga bisa melihat perbandingan Before & After untuk memahami perbedaannya, serta tips tambahan untuk meningkatkan skor ATS.</li>
            </ol>

            <h2 class="font-[family-name:var(--font-display)] text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950 mt-10">Mengapa CV Kamu Perlu Dioptimasi untuk ATS?</h2>
            <p class="mt-4 text-base leading-7 text-slate-600">
                Studi menunjukkan bahwa lebih dari 75% CV tidak pernah dibaca oleh manusia karena sudah tersingkir lebih dulu oleh sistem ATS. ATS (Applicant Tracking System) adalah perangkat lunak yang digunakan hampir semua perusahaan besar dan startup modern untuk menyaring lamaran kerja secara otomatis sebelum diserahkan ke recruiter. Sistem ini menilai CV berdasarkan kata kunci, format, dan struktur — bukan hanya pengalaman atau pendidikanmu.
            </p>
            <p class="mt-3 text-base leading-7 text-slate-600">
                CV yang tidak dioptimasi untuk ATS seringkali menggunakan format tabel yang tidak terbaca, kata kerja yang lemah tanpa angka pencapaian, atau tidak menyertakan kata kunci yang relevan dengan deskripsi pekerjaan. Dengan menggunakan CV Rewrite & ATS Optimizer Lamaraja, kamu bisa memastikan CV kamu melewati filter ATS dan sampai ke tangan recruiter — sehingga peluang kamu untuk dipanggil interview meningkat secara signifikan.
            </p>

            <h2 class="font-[family-name:var(--font-display)] text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-950 mt-10">Pertanyaan Umum</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <h3 class="font-bold text-slate-800">Apakah AI CV Rewrite ini gratis?</h3>
                    <p class="mt-2 text-base leading-7 text-slate-600">Ya, CV Rewrite & ATS Optimizer Lamaraja sepenuhnya gratis digunakan tanpa perlu mendaftar atau berlangganan apapun.</p>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Apakah hasil rewrite bisa langsung dipakai di CV?</h3>
                    <p class="mt-2 text-base leading-7 text-slate-600">Ya, hasil rewrite sudah siap disalin ke CV kamu. Namun disarankan untuk membaca ulang dan menyesuaikan dengan gaya penulisan personalmu agar tetap terasa autentik.</p>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Format CV apa yang didukung?</h3>
                    <p class="mt-2 text-base leading-7 text-slate-600">Saat ini tool kami mendukung upload file CV dalam format PDF. Pastikan PDF kamu bisa dibaca teks (bukan hasil scan gambar) agar AI bisa menganalisisnya dengan optimal.</p>
                </div>
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
