<x-layout
    title="Privacy Policy - Lamaraja"
    description="Pelajari cara Lamaraja mengumpulkan, memakai, dan melindungi data pengguna saat mencari lowongan, memakai CV Matcher, dan fitur AI."
    canonical="{{ route('legal.privacy') }}"
>
    <section class="relative overflow-hidden bg-[#f6f1e8]">
        <div class="absolute inset-0 opacity-60" style="background-image: radial-gradient(circle at 18% 20%, rgba(13,148,136,.18), transparent 28%), radial-gradient(circle at 78% 8%, rgba(245,158,11,.16), transparent 26%), linear-gradient(135deg, rgba(15,23,42,.04) 0 25%, transparent 25% 100%);"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-end">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.28em] text-teal-700">Legal Center</p>
                    <h1 class="mt-4 font-[family-name:var(--font-display)] text-4xl font-extrabold tracking-tight text-slate-950 sm:text-6xl">Privacy Policy</h1>
                    <p class="mt-5 max-w-xl text-lg leading-8 text-slate-700">Kami merancang Lamaraja supaya proses cari kerja terasa ringan, termasuk urusan data. Halaman ini menjelaskan data apa yang diproses dan bagaimana kami menjaganya.</p>
                </div>
                <div class="rounded-[2rem] border border-slate-900/10 bg-white/80 p-6 shadow-2xl shadow-slate-900/10 backdrop-blur">
                    <div class="grid gap-3 sm:grid-cols-3">
                        @foreach([
                            ['label' => 'CV files', 'text' => 'Diproses terbatas untuk analisis kecocokan.'],
                            ['label' => 'Job data', 'text' => 'Dipakai untuk ringkasan dan rekomendasi.'],
                            ['label' => 'Kontak', 'text' => 'Hanya untuk membalas permintaan bantuan.'],
                        ] as $item)
                            <div class="rounded-2xl bg-slate-950 p-4 text-white">
                                <p class="text-sm font-bold text-amber-200">{{ $item['label'] }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-300">{{ $item['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-5 text-sm text-slate-500">Terakhir diperbarui: {{ now()->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-14 lg:py-20">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 sm:px-6 lg:grid-cols-[260px_1fr] lg:px-8">
            <aside class="h-fit rounded-3xl border border-slate-200 bg-slate-50 p-5 lg:sticky lg:top-24">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Ringkasan</p>
                <nav class="mt-4 space-y-2 text-sm font-semibold text-slate-700">
                    <a class="block rounded-xl px-3 py-2 hover:bg-white" href="#data">Data yang diproses</a>
                    <a class="block rounded-xl px-3 py-2 hover:bg-white" href="#cv">CV dan AI</a>
                    <a class="block rounded-xl px-3 py-2 hover:bg-white" href="#sharing">Berbagi data</a>
                    <a class="block rounded-xl px-3 py-2 hover:bg-white" href="#rights">Hak pengguna</a>
                </nav>
            </aside>
            <div class="space-y-6">
                @foreach([
                    ['id' => 'data', 'title' => 'Data yang kami proses', 'body' => 'Lamaraja dapat memproses data yang kamu masukkan, seperti kata kunci pencarian, lokasi, file CV yang diunggah ke CV Matcher, informasi teknis dasar, dan pesan yang kamu kirim melalui email. Data ini digunakan untuk menampilkan lowongan, menjalankan fitur AI, menjaga keamanan, dan memperbaiki kualitas produk.'],
                    ['id' => 'cv', 'title' => 'CV Matcher dan fitur AI', 'body' => 'Jika kamu mengunggah CV, file tersebut digunakan untuk membaca skill, pengalaman, dan kecocokan terhadap lowongan. Hasil AI bersifat bantuan keputusan, bukan jaminan diterima kerja. Kami tidak menjual isi CV. File dan hasil analisis dapat dibersihkan secara berkala sesuai kebutuhan operasional.'],
                    ['id' => 'sharing', 'title' => 'Berbagi data dengan pihak ketiga', 'body' => 'Kami dapat memakai penyedia infrastruktur, penyimpanan, analitik, atau model AI untuk menjalankan layanan. Data hanya dibagikan sejauh diperlukan untuk fungsi tersebut. Saat kamu menekan tombol Lamar, kamu akan diarahkan ke situs sumber lowongan dan kebijakan privasi situs tersebut berlaku.'],
                    ['id' => 'rights', 'title' => 'Hak dan pilihan kamu', 'body' => 'Kamu dapat meminta penghapusan data yang kamu kirim, menanyakan pemrosesan data, atau meminta koreksi melalui hello@lamaraja.web.id. Kami akan menanggapi permintaan yang wajar sesuai kemampuan teknis dan kewajiban hukum yang berlaku.'],
                ] as $section)
                    <article id="{{ $section['id'] }}" class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="font-[family-name:var(--font-display)] text-2xl font-extrabold text-slate-950">{{ $section['title'] }}</h2>
                        <p class="mt-4 text-base leading-8 text-slate-600">{{ $section['body'] }}</p>
                    </article>
                @endforeach
                <div class="rounded-[2rem] bg-teal-950 p-6 text-white sm:p-8">
                    <h2 class="text-2xl font-extrabold">Butuh klarifikasi?</h2>
                    <p class="mt-3 text-teal-100">Kirim pertanyaan privacy ke <a class="font-bold text-amber-200 underline" href="mailto:hello@lamaraja.web.id">hello@lamaraja.web.id</a>.</p>
                </div>
            </div>
        </div>
    </section>
</x-layout>
