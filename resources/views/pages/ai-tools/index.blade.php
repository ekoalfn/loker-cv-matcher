<x-layout
    title="AI Career Tools Gratis - CV, Cover Letter, Interview | Lamaraja"
    description="Kumpulan AI career tools gratis: generator surat lamaran, CV rewrite ATS, skill gap analyzer, career path, dan interview question generator untuk pencari kerja Indonesia."
    robots="index, follow"
    canonical="{{ route('ai-tools.index') }}"
>
    @php
        $faqLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'Apa saja AI Career Tools yang tersedia di Lamaraja?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Lamaraja menyediakan 6 AI career tools gratis: CV Matcher (ATS Checker), Latihan Interview AI, Generator Surat Lamaran, CV Rewrite & ATS Optimizer, Skill Gap Analyzer, Career Path Recommender, dan Interview Question Generator.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Apakah semua AI tools di Lamaraja gratis?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Ya, semua AI career tools di Lamaraja gratis digunakan tanpa perlu daftar atau login.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Bagaimana cara memulai menggunakan AI tools Lamaraja?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Pilih tool yang sesuai kebutuhan, upload CV PDF atau masukkan informasi yang diminta, lalu AI akan langsung memproses dan menghasilkan rekomendasi atau output dalam hitungan detik.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Apakah data CV saya aman di Lamaraja?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'CV yang diupload hanya digunakan untuk proses analisis AI dan tidak disimpan secara permanen. File dihapus setelah proses selesai.',
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $itemListLd = json_encode([
            chr(64) . 'context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'AI Career Tools Lamaraja',
            'description' => 'Kumpulan tool AI gratis untuk pencari kerja Indonesia',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'CV Matcher (ATS Checker)',
                    'url' => route('cv-matcher.index'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Generator Surat Lamaran',
                    'url' => route('ai-tools.cover-letter'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => 'CV Rewrite & ATS Optimizer',
                    'url' => route('ai-tools.cv-rewrite'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 4,
                    'name' => 'Skill Gap Analyzer',
                    'url' => route('ai-tools.skill-gap'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 5,
                    'name' => 'AI Career Path',
                    'url' => route('ai-tools.career-path'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 6,
                    'name' => 'Latihan Interview AI',
                    'url' => route('mock-interview.landing'),
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp

    <script type="application/ld+json">{!! $faqLd !!}</script>
    <script type="application/ld+json">{!! $itemListLd !!}</script>

    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-teal-50/60 to-white">
        <div class="absolute -top-24 -right-24 h-80 w-80 rounded-full bg-emerald-200/50 blur-3xl" aria-hidden="true"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20 text-center">
            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100 shadow-sm">AI Career Tools</span>
            <h1 class="mt-5 font-[family-name:var(--font-display)] text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-950">Toolkit AI untuk pencari kerja</h1>
            <p class="mt-4 max-w-2xl mx-auto text-lg leading-8 text-slate-600">Perbaiki CV, buat surat lamaran, analisis skill gap, dan latihan interview. Semua gratis untuk dicoba dan CV kamu langsung dihapus setelah diproses.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @php
                $tools = [
                    ['route' => route('cv-matcher.index'), 'title' => 'CV Matcher (ATS Checker)', 'desc' => 'Cek kecocokan CV dengan lowongan: skor, skill cocok, gap, dan saran perbaikan.'],
                    ['route' => route('ai-tools.cover-letter'), 'title' => 'Generator Surat Lamaran', 'desc' => 'Buat cover letter personal dari CV dan deskripsi lowongan dalam hitungan detik.'],
                    ['route' => route('ai-tools.cv-rewrite'), 'title' => 'CV Rewrite / ATS Optimizer', 'desc' => 'Ubah pengalaman kerja jadi bullet point ATS-friendly berbasis pencapaian.'],
                    ['route' => route('ai-tools.skill-gap'), 'title' => 'Skill Gap Analyzer', 'desc' => 'Dari CV + target role, lihat skill yang kurang beserta rencana belajarnya.'],
                    ['route' => route('ai-tools.career-path'), 'title' => 'AI Career Path', 'desc' => 'Rekomendasi role berikutnya yang realistis berdasarkan profil CV-mu.'],
                    ['route' => route('mock-interview.landing'), 'title' => 'Latihan Interview AI', 'desc' => 'Simulasi interview dengan pertanyaan relevan dan feedback otomatis.'],
                ];
            @endphp
            @foreach($tools as $tool)
                <a href="{{ $tool['route'] }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h2 class="mt-4 text-lg font-bold text-slate-900 group-hover:text-emerald-700">{{ $tool['title'] }}</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $tool['desc'] }}</p>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-emerald-600">Coba sekarang
                        <svg class="w-4 h-4 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </section>
</x-layout>
