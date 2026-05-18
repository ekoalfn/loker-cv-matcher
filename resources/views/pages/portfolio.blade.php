<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio Muhamad Eko Alfianto - builder produk digital, automation, AI workflow, dan web application.">
    <meta name="robots" content="index, follow">
    <title>Muhamad Eko Alfianto - Portfolio</title>
    <link rel="canonical" href="https://promo.lamaraja.web.id/">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet"></noscript>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --ink:#111827; --muted:#667085; --line:#e6e8ec; --paper:#fbfaf7; --lime:#d9f99d; --green:#14532d; --blue:#1d4ed8; }
        body { font-family: Outfit, ui-sans-serif, system-ui, sans-serif; background: var(--paper); color: var(--ink); }
        .font-editorial { font-family: 'Instrument Serif', Georgia, serif; }
        .mesh { background: radial-gradient(circle at 15% 12%, rgba(217,249,157,.85), transparent 28%), radial-gradient(circle at 85% 8%, rgba(191,219,254,.8), transparent 28%), linear-gradient(180deg,#fffef9 0%,#f6f5ef 100%); }
        .grain::after { content:""; position:absolute; inset:0; pointer-events:none; opacity:.35; background-image: radial-gradient(#111827 0.5px, transparent 0.5px); background-size:18px 18px; mix-blend-mode:soft-light; }
        .card { background: rgba(255,255,255,.76); border:1px solid rgba(17,24,39,.08); box-shadow:0 20px 60px rgba(17,24,39,.08); backdrop-filter: blur(16px); }
    </style>
</head>
<body>
    <main class="relative overflow-hidden mesh grain min-h-screen">
        <nav class="relative z-10 max-w-6xl mx-auto px-5 py-6 flex items-center justify-between">
            <a href="/" class="font-bold tracking-tight text-lg">Eko<span class="text-emerald-700">.</span></a>
            <div class="hidden sm:flex items-center gap-6 text-sm text-slate-600">
                <a href="#work" class="hover:text-slate-950">Work</a>
                <a href="#services" class="hover:text-slate-950">Services</a>
                <a href="#contact" class="hover:text-slate-950">Contact</a>
            </div>
            <a href="https://www.linkedin.com/in/muhamad-eko-alfianto-5805201a1/" class="rounded-full bg-slate-950 text-white px-4 py-2 text-sm font-semibold hover:bg-emerald-800 transition">LinkedIn</a>
        </nav>

        <section class="relative z-10 max-w-6xl mx-auto px-5 pt-10 pb-20 lg:pt-20">
            <div class="grid lg:grid-cols-[1.1fr_.9fr] gap-10 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/70 px-3 py-1.5 text-sm text-slate-600 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Available for product, automation, and AI web projects
                    </div>
                    <h1 class="mt-7 text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-[-0.055em] leading-[.94]">
                        Muhamad Eko Alfianto builds practical digital products that ship.
                    </h1>
                    <p class="mt-7 max-w-2xl text-lg text-slate-600 leading-8">
                        Product-minded builder focused on web applications, automation, AI-assisted workflows, and conversion-ready landing pages. Currently building Lamaraja, an AI-powered job platform for Indonesian job seekers.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="#contact" class="rounded-2xl bg-emerald-700 text-white px-6 py-4 font-bold shadow-xl shadow-emerald-900/15 hover:bg-emerald-800 transition text-center">Start a project</a>
                        <a href="#work" class="rounded-2xl bg-white/80 border border-slate-200 px-6 py-4 font-bold hover:bg-white transition text-center">See selected work</a>
                    </div>
                </div>

                <div class="card rounded-[2rem] p-6 lg:p-8 rotate-1">
                    <div class="rounded-[1.5rem] bg-slate-950 text-white p-6 -rotate-1">
                        <p class="text-sm text-emerald-300 font-semibold">Profile Snapshot</p>
                        <h2 class="mt-4 font-editorial text-4xl leading-none">Builder, operator, problem solver.</h2>
                        <div class="mt-8 grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-white/10 p-4"><p class="text-3xl font-extrabold">AI</p><p class="text-sm text-slate-300">CV matching, summaries, workflows</p></div>
                            <div class="rounded-2xl bg-white/10 p-4"><p class="text-3xl font-extrabold">Web</p><p class="text-sm text-slate-300">Laravel, product pages, dashboards</p></div>
                            <div class="rounded-2xl bg-white/10 p-4"><p class="text-3xl font-extrabold">Ops</p><p class="text-sm text-slate-300">Scraping, ingest, automation</p></div>
                            <div class="rounded-2xl bg-white/10 p-4"><p class="text-3xl font-extrabold">SEO</p><p class="text-sm text-slate-300">Technical SEO, content systems</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="work" class="relative z-10 max-w-6xl mx-auto px-5 py-16">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm uppercase tracking-[.24em] text-emerald-700 font-bold">Selected Work</p>
                    <h2 class="mt-3 text-4xl md:text-5xl font-extrabold tracking-tight">Projects with business intent.</h2>
                </div>
                <p class="max-w-md text-slate-600">A portfolio designed around outcomes: launch faster, automate repetitive work, and make products easier to discover.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-5">
                <article class="card rounded-3xl p-6">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Product</span>
                    <h3 class="mt-4 text-2xl font-bold">Lamaraja</h3>
                    <p class="mt-3 text-slate-600 leading-7">AI-powered job portal for Indonesia with job aggregation, CV Matcher, SEO landing pages, and daily job refresh automation.</p>
                </article>
                <article class="card rounded-3xl p-6">
                    <span class="text-xs font-bold text-blue-700 uppercase tracking-widest">Automation</span>
                    <h3 class="mt-4 text-2xl font-bold">Job Data Pipeline</h3>
                    <p class="mt-3 text-slate-600 leading-7">Trusted-source ingestion, logo validation, quality gates, and scheduled refresh to keep listings complete and fresh.</p>
                </article>
                <article class="card rounded-3xl p-6">
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-widest">Freelance</span>
                    <h3 class="mt-4 text-2xl font-bold">Client Delivery</h3>
                    <p class="mt-3 text-slate-600 leading-7">Previously served freelance clients through marketplace work, focusing on practical web builds and reliable execution.</p>
                </article>
            </div>
        </section>

        <section id="services" class="relative z-10 max-w-6xl mx-auto px-5 py-16">
            <div class="rounded-[2rem] bg-slate-950 text-white p-6 md:p-10">
                <div class="grid lg:grid-cols-[.85fr_1.15fr] gap-10">
                    <div>
                        <p class="text-sm uppercase tracking-[.24em] text-lime-200 font-bold">Services</p>
                        <h2 class="mt-4 font-editorial text-5xl leading-none">What I can help with.</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach([
                            ['AI & automation', 'Turn manual processes into repeatable workflows with scraping, scheduled jobs, and AI summaries.'],
                            ['Web application', 'Build Laravel-based products, dashboards, landing pages, and internal tools.'],
                            ['SEO systems', 'Technical SEO fixes, structured data, performance, content pages, and indexing readiness.'],
                            ['Growth pages', 'Clean modern websites for portfolios, promos, products, and service offers.'],
                        ] as [$title, $copy])
                            <div class="rounded-3xl bg-white/10 border border-white/10 p-5">
                                <h3 class="font-bold text-xl">{{ $title }}</h3>
                                <p class="mt-3 text-slate-300 leading-7">{{ $copy }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="relative z-10 max-w-6xl mx-auto px-5 py-16 pb-24">
            <div class="card rounded-[2rem] p-6 md:p-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div>
                    <p class="text-sm uppercase tracking-[.24em] text-emerald-700 font-bold">Contact</p>
                    <h2 class="mt-3 text-4xl md:text-5xl font-extrabold tracking-tight">Need a product-minded builder?</h2>
                    <p class="mt-4 text-slate-600 max-w-2xl">Reach out through LinkedIn while this temporary portfolio is hosted on promo.lamaraja.web.id.</p>
                </div>
                <a href="https://www.linkedin.com/in/muhamad-eko-alfianto-5805201a1/" class="rounded-2xl bg-slate-950 text-white px-7 py-4 font-bold hover:bg-emerald-800 transition text-center">Message on LinkedIn</a>
            </div>
        </section>
    </main>
</body>
</html>
