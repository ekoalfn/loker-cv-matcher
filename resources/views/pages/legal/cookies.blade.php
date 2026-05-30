<x-layout
    title="Cookie Policy - Lamaraja"
    description="Informasi penggunaan cookie dan teknologi serupa di Lamaraja untuk keamanan, preferensi, analitik, dan peningkatan layanan."
    canonical="{{ route('legal.cookies') }}"
>
    <section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-white to-teal-50">
        <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-amber-200/50 blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 h-72 w-72 rounded-full bg-teal-200/50 blur-3xl"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="grid gap-10 lg:grid-cols-[1fr_380px] lg:items-center">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.28em] text-teal-700">Legal Center</p>
                    <h1 class="mt-4 font-[family-name:var(--font-display)] text-4xl font-extrabold tracking-tight text-slate-950 sm:text-6xl">Cookie Policy</h1>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-700">Cookie membantu Lamaraja mengingat sesi, menjaga keamanan, memahami performa halaman, dan membuat pengalaman cari kerja lebih mulus.</p>
                </div>
                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-amber-100 text-amber-700">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v.01M8 12v.01M16 12v.01M12 16v.01M21 12a9 9 0 11-9-9 3 3 0 003 3 3 3 0 003 3 3 3 0 003 3z" /></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-950">Transparan dan seperlunya</p>
                            <p class="mt-1 text-sm text-slate-500">Terakhir diperbarui: {{ now()->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-14 lg:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-2">
                @foreach([
                    ['title' => 'Cookie esensial', 'body' => 'Digunakan untuk sesi, keamanan CSRF, autentikasi area admin/testing, dan fungsi dasar website. Cookie ini diperlukan agar layanan berjalan.'],
                    ['title' => 'Preferensi', 'body' => 'Dapat digunakan untuk mengingat pilihan pengguna seperti menu, state form, atau preferensi antarmuka supaya pengalaman lebih nyaman.'],
                    ['title' => 'Analitik', 'body' => 'Kami dapat memakai analitik agregat untuk memahami halaman yang sering dikunjungi, performa fitur, dan area yang perlu diperbaiki.'],
                    ['title' => 'Pihak ketiga', 'body' => 'Tautan apply, font, infrastruktur, atau layanan analitik tertentu dapat memakai teknologi serupa sesuai kebijakan masing-masing penyedia.'],
                ] as $section)
                    <article class="rounded-[2rem] border border-slate-200 bg-slate-50 p-6 sm:p-8">
                        <h2 class="font-[family-name:var(--font-display)] text-2xl font-extrabold text-slate-950">{{ $section['title'] }}</h2>
                        <p class="mt-4 text-base leading-8 text-slate-600">{{ $section['body'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-8 rounded-[2rem] bg-slate-950 p-6 text-white sm:p-8">
                <h2 class="text-2xl font-extrabold">Mengelola cookie</h2>
                <p class="mt-3 max-w-3xl text-slate-300">Kamu bisa menghapus atau memblokir cookie lewat pengaturan browser. Beberapa fitur seperti sesi login, upload, atau proses form mungkin tidak berjalan optimal jika cookie esensial diblokir.</p>
                <a href="mailto:hello@lamaraja.web.id" class="mt-6 inline-flex rounded-2xl bg-teal-500 px-5 py-3 text-sm font-bold text-white hover:bg-teal-600">Tanya soal cookie</a>
            </div>
        </div>
    </section>
</x-layout>
