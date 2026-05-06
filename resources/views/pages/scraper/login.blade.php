<x-layout
    title="Scraper Admin - Login"
    robots="noindex, nofollow"
>

    <div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-sm animate-fade-up">

            {{-- Lock Icon --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center"
                     style="background: linear-gradient(135deg, #0d9488, #06b6d4); box-shadow: 0 4px 20px rgba(13, 148, 136, 0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="font-[family-name:var(--font-display)] text-2xl font-extrabold text-slate-800 tracking-tight">
                    Scraper Admin
                </h1>
                <p class="text-sm text-stone-500 mt-1.5">Masukkan password untuk mengakses dashboard</p>
            </div>

            {{-- Login Form --}}
            <form action="{{ route('scraper.authenticate') }}" method="POST"
                  class="surface rounded-2xl p-6 space-y-5">
                @csrf

                <div>
                    <label for="scraper-password" class="block text-sm font-medium text-stone-600 mb-1.5">Password</label>
                    <input
                        type="password"
                        id="scraper-password"
                        name="password"
                        placeholder="Masukkan password..."
                        required
                        autofocus
                        class="w-full min-h-[2.75rem] px-4 py-2.5 rounded-xl input-glass text-sm focus:outline-none interactive-focus @error('password') animate-shake @enderror"
                        style="@error('password') border-color: #ef4444; @enderror"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full min-h-[2.75rem] px-5 py-2.5 rounded-xl font-semibold text-sm text-white btn-primary btn-press interactive-focus flex items-center justify-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Masuk
                </button>
            </form>

            <p class="text-center text-xs text-stone-400 mt-4">
                Halaman ini hanya untuk administrator.
            </p>
        </div>
    </div>

</x-layout>
