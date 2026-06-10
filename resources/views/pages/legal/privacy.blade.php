<x-layout
    title="Privacy Policy - Lamaraja"
    description="Pelajari cara Lamaraja mengumpulkan, memakai, dan melindungi data pengguna saat mencari lowongan, memakai CV Matcher, dan fitur AI."
    canonical="{{ route('legal.privacy') }}"
>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-teal-50/60 to-white">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-24 right-[-8rem] h-80 w-80 rounded-full bg-emerald-200/60 blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 h-64 w-64 rounded-full bg-teal-200/40 blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid gap-10 lg:grid-cols-[1fr_1fr] lg:items-center">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                        Legal Center
                    </span>
                    <h1 class="mt-5 font-[family-name:var(--font-display)] text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-950">
                        Privacy <span class="text-emerald-600">Policy</span>
                    </h1>
                    <p class="mt-5 max-w-xl text-lg leading-8 text-slate-600">
                        Kami merancang Lamaraja supaya proses cari kerja terasa ringan, termasuk urusan data. Halaman ini menjelaskan data apa yang diproses dan bagaimana kami menjaganya.
                    </p>
                    <div class="mt-6">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1 text-sm text-slate-500 ring-1 ring-slate-200 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                            Terakhir diperbarui: {{ now()->format('d M Y') }}
                        </span>
                    </div>
                </div>

                {{-- Data summary card --}}
                <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-xl shadow-emerald-900/10">
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-600 mb-4">Ringkasan data yang diproses</p>
                    <div class="grid gap-3 sm:grid-cols-3">
                        @foreach([
                            ['label' => 'CV files',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>', 'text' => 'Diproses terbatas untuk analisis kecocokan.'],
                            ['label' => 'Job data',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>', 'text' => 'Dipakai untuk ringkasan dan rekomendasi.'],
                            ['label' => 'Kontak',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>', 'text' => 'Hanya untuk membalas permintaan bantuan.'],
                        ] as $item)
                            <div class="rounded-2xl border border-emerald-50 bg-emerald-50 p-4">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white mb-3">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $item['icon'] !!}</svg>
                                </div>
                                <p class="text-sm font-bold text-slate-900">{{ $item['label'] }}</p>
                                <p class="mt-1 text-xs leading-5 text-slate-500">{{ $item['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section class="bg-gradient-to-b from-white to-emerald-50/40 py-14 lg:py-20">
        <div class="max-w-7xl mx-auto grid gap-8 px-4 sm:px-6 lg:grid-cols-[260px_1fr] lg:px-8">

            {{-- Sidebar nav --}}
            <aside class="h-fit rounded-3xl border border-emerald-100 bg-white p-5 shadow-sm lg:sticky lg:top-24">
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-600">Navigasi</p>
                <nav class="mt-4 space-y-1 text-sm font-semibold text-slate-700">
                    @foreach([
                        ['href' => '#data',    'label' => 'Data yang diproses'],
                        ['href' => '#cv',      'label' => 'CV dan AI'],
                        ['href' => '#sharing', 'label' => 'Berbagi data'],
                        ['href' => '#rights',  'label' => 'Hak pengguna'],
                    ] as $nav)
                        <a class="flex items-center gap-2 rounded-xl px-3 py-2 hover:bg-emerald-50 hover:text-emerald-700 transition" href="{{ $nav['href'] }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            {{ $nav['label'] }}
                        </a>
                    @endforeach
                </nav>
                <div class="mt-6 rounded-2xl bg-emerald-50 p-4">
                    <p class="text-xs font-bold text-emerald-700">Butuh bantuan?</p>
                    <a href="mailto:hello@lamaraja.web.id" class="mt-2 block text-xs text-slate-600 hover:text-emerald-700 transition break-all">hello@lamaraja.web.id</a>
                </div>
            </aside>

            {{-- Articles --}}
            <div class="space-y-5">
                @foreach([
                    [
                        'id'    => 'data',
                        'title' => 'Data yang kami proses',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>',
                        'body'  => 'Lamaraja dapat memproses data yang kamu masukkan, seperti kata kunci pencarian, lokasi, file CV yang diunggah ke CV Matcher, informasi teknis dasar, dan pesan yang kamu kirim melalui email. Data ini digunakan untuk menampilkan lowongan, menjalankan fitur AI, menjaga keamanan, dan memperbaiki kualitas produk.',
                    ],
                    [
                        'id'    => 'cv',
                        'title' => 'CV Matcher dan fitur AI',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>',
                        'body'  => 'Jika kamu mengunggah CV, file tersebut digunakan untuk membaca skill, pengalaman, dan kecocokan terhadap lowongan. Hasil AI bersifat bantuan keputusan, bukan jaminan diterima kerja. Kami tidak menjual isi CV. File dan hasil analisis dapat dibersihkan secara berkala sesuai kebutuhan operasional.',
                    ],
                    [
                        'id'    => 'sharing',
                        'title' => 'Berbagi data dengan pihak ketiga',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>',
                        'body'  => 'Kami dapat memakai penyedia infrastruktur, penyimpanan, analitik, atau model AI untuk menjalankan layanan. Data hanya dibagikan sejauh diperlukan untuk fungsi tersebut. Saat kamu menekan tombol Lamar, kamu akan diarahkan ke situs sumber lowongan dan kebijakan privasi situs tersebut berlaku.',
                    ],
                    [
                        'id'    => 'rights',
                        'title' => 'Hak dan pilihan kamu',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                        'body'  => 'Kamu dapat meminta penghapusan data yang kamu kirim, menanyakan pemrosesan data, atau meminta koreksi melalui hello@lamaraja.web.id. Kami akan menanggapi permintaan yang wajar sesuai kemampuan teknis dan kewajiban hukum yang berlaku.',
                    ],
                ] as $section)
                    <article id="{{ $section['id'] }}" class="group rounded-3xl border border-slate-100 bg-white p-6 shadow-sm hover:border-emerald-100 hover:shadow-md transition sm:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $section['icon'] !!}</svg>
                            </div>
                            <h2 class="font-[family-name:var(--font-display)] text-xl font-extrabold text-slate-950">{{ $section['title'] }}</h2>
                        </div>
                        <p class="text-base leading-8 text-slate-600">{{ $section['body'] }}</p>
                    </article>
                @endforeach

                {{-- CTA --}}
                <div class="rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-600 to-teal-600 p-8 text-white shadow-xl shadow-emerald-900/15">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                        <div>
                            <h2 class="text-2xl font-extrabold">Butuh klarifikasi?</h2>
                            <p class="mt-2 text-emerald-100">Kirim pertanyaan privacy ke kami, kami siap membantu.</p>
                        </div>
                        <a href="mailto:hello@lamaraja.web.id"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-bold text-emerald-700 shadow-sm hover:bg-emerald-50 transition shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            hello@lamaraja.web.id
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
