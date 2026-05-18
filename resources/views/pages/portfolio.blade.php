<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio Muhamad Eko Alfianto, Senior Full Stack Developer dengan pengalaman Laravel, Next.js, WordPress, Restful API, dan 200+ project delivery.">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#2563eb">
    <meta property="og:title" content="Muhamad Eko Alfianto - Web Developer Portfolio">
    <meta property="og:description" content="Clean modern portfolio inspired by Lamaraja, featuring 200+ projects, Laravel, Next.js, WordPress, API development, and selected client work.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://promo.lamaraja.web.id/">
    <meta name="twitter:card" content="summary_large_image">
    <title>Muhamad Eko Alfianto - Web Developer Portfolio</title>
    <link rel="canonical" href="https://promo.lamaraja.web.id/">
    <style>
        :root {
            --blue-950: #0f172a;
            --blue-900: #172554;
            --blue-800: #1e40af;
            --blue-700: #1d4ed8;
            --blue-600: #2563eb;
            --blue-500: #3b82f6;
            --blue-100: #dbeafe;
            --blue-50: #eff6ff;
            --cyan-50: #ecfeff;
            --slate-900: #0f172a;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --white: #ffffff;
            --radius-xl: 1.5rem;
            --radius-2xl: 2rem;
            --shadow-soft: 0 24px 80px rgba(37, 99, 235, 0.12);
            --font-display: "Plus Jakarta Sans", "Trebuchet MS", Verdana, sans-serif;
            --font-body: Inter, "Trebuchet MS", Verdana, sans-serif;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: var(--font-body);
            color: var(--slate-900);
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.16), transparent 28rem),
                radial-gradient(circle at 88% 8%, rgba(14, 165, 233, 0.14), transparent 26rem),
                linear-gradient(180deg, #f8fbff 0%, #ffffff 40%, #f8fbff 100%);
        }
        a { color: inherit; }
        .page { min-height: 100vh; overflow: hidden; }
        .container { width: min(1120px, calc(100% - 40px)); margin: 0 auto; }
        .nav {
            position: sticky; top: 0; z-index: 40;
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
            background: rgba(255, 255, 255, 0.84);
            backdrop-filter: blur(18px);
        }
        .nav-inner { height: 72px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .brand-mark { width: 44px; height: 44px; border-radius: 16px; display: grid; place-items: center; color: white; font-weight: 900; background: linear-gradient(135deg, var(--blue-700), #06b6d4); box-shadow: 0 12px 32px rgba(37, 99, 235, 0.28); }
        .brand-text strong { display: block; font-family: var(--font-display); font-size: 1rem; letter-spacing: -0.02em; }
        .brand-text span { display: block; margin-top: 2px; color: var(--blue-600); font-size: 0.76rem; font-weight: 700; }
        .nav-links { display: flex; align-items: center; gap: 28px; color: var(--slate-600); font-size: 0.92rem; font-weight: 700; }
        .nav-links a { text-decoration: none; transition: color 180ms ease; }
        .nav-links a:hover { color: var(--blue-700); }
        .nav-cta { display: inline-flex; align-items: center; justify-content: center; min-height: 42px; padding: 0 16px; border-radius: 999px; background: var(--blue-600); color: white; text-decoration: none; font-weight: 800; box-shadow: 0 12px 30px rgba(37, 99, 235, 0.24); }

        .hero { position: relative; padding: 86px 0 72px; }
        .hero-grid { display: grid; grid-template-columns: 1.04fr 0.96fr; gap: 56px; align-items: center; }
        .badge { display: inline-flex; align-items: center; gap: 9px; padding: 9px 12px; border: 1px solid #bfdbfe; border-radius: 999px; background: rgba(255, 255, 255, 0.78); color: var(--blue-800); font-size: 0.9rem; font-weight: 800; box-shadow: 0 12px 36px rgba(15, 23, 42, 0.06); }
        .badge-dot { width: 8px; height: 8px; border-radius: 999px; background: #22c55e; box-shadow: 0 0 0 5px rgba(34, 197, 94, 0.14); }
        h1 { margin: 22px 0 0; font-family: var(--font-display); font-size: clamp(3.2rem, 7vw, 6.8rem); line-height: 0.94; letter-spacing: -0.07em; color: var(--blue-950); }
        .hero-copy { margin: 24px 0 0; max-width: 680px; color: var(--slate-600); font-size: 1.12rem; line-height: 1.8; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 32px; }
        .button { display: inline-flex; align-items: center; justify-content: center; min-height: 50px; padding: 0 20px; border-radius: 14px; border: 1px solid var(--slate-200); background: white; color: var(--slate-900); text-decoration: none; font-weight: 900; transition: transform 180ms ease, box-shadow 180ms ease, background 180ms ease; }
        .button:hover { transform: translateY(-2px); box-shadow: 0 16px 36px rgba(15, 23, 42, 0.1); }
        .button-primary { border-color: var(--blue-600); background: var(--blue-600); color: white; box-shadow: 0 16px 38px rgba(37, 99, 235, 0.24); }
        .button-primary:hover { background: var(--blue-700); }
        .hero-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 34px; }
        .stat { padding: 18px; border: 1px solid var(--slate-200); border-radius: 22px; background: rgba(255,255,255,0.76); box-shadow: 0 16px 40px rgba(15, 23, 42, 0.05); }
        .stat strong { display: block; color: var(--blue-700); font-size: 1.4rem; font-family: var(--font-display); }
        .stat span { display: block; margin-top: 6px; color: var(--slate-500); font-size: 0.86rem; line-height: 1.5; }

        .profile-card { position: relative; border: 1px solid #bfdbfe; border-radius: 34px; background: rgba(255, 255, 255, 0.82); box-shadow: var(--shadow-soft); padding: 18px; }
        .profile-card::before { content: ""; position: absolute; inset: -42px -48px auto auto; width: 160px; height: 160px; border-radius: 999px; background: rgba(59, 130, 246, 0.16); filter: blur(8px); z-index: -1; }
        .profile-screen { border-radius: 26px; overflow: hidden; background: var(--blue-950); color: white; }
        .screen-top { display: flex; gap: 7px; padding: 16px 18px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .screen-dot { width: 10px; height: 10px; border-radius: 999px; background: #60a5fa; opacity: 0.9; }
        .screen-body { padding: 28px; }
        .screen-label { color: #93c5fd; font-weight: 900; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.14em; }
        .screen-title { margin: 12px 0 0; font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3.4rem); line-height: 1; letter-spacing: -0.05em; }
        .skill-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-top: 28px; }
        .skill { min-height: 112px; border: 1px solid rgba(147, 197, 253, 0.2); border-radius: 22px; background: rgba(255,255,255,0.08); padding: 16px; }
        .skill strong { display: block; font-size: 1.7rem; letter-spacing: -0.04em; }
        .skill span { display: block; margin-top: 8px; color: #cbd5e1; font-size: 0.88rem; line-height: 1.5; }
        .floating-card { position: absolute; left: -28px; bottom: 34px; max-width: 260px; border: 1px solid var(--slate-200); border-radius: 22px; background: white; padding: 16px; box-shadow: 0 18px 54px rgba(15, 23, 42, 0.12); }
        .floating-card strong { display: block; color: var(--slate-900); }
        .floating-card span { display: block; margin-top: 6px; color: var(--slate-500); font-size: 0.86rem; line-height: 1.5; }

        section { padding: 72px 0; }
        .section-head { display: flex; align-items: end; justify-content: space-between; gap: 28px; margin-bottom: 30px; }
        .kicker { color: var(--blue-700); font-size: 0.78rem; font-weight: 900; letter-spacing: 0.16em; text-transform: uppercase; }
        h2 { margin: 10px 0 0; font-family: var(--font-display); color: var(--blue-950); font-size: clamp(2.2rem, 4.4vw, 4.4rem); line-height: 1; letter-spacing: -0.055em; }
        .section-lead { max-width: 460px; color: var(--slate-600); line-height: 1.7; }
        .work-feature { display: grid; grid-template-columns: 1.04fr 0.96fr; gap: 28px; align-items: stretch; }
        .work-visual { min-height: 420px; border-radius: var(--radius-2xl); border: 1px solid #bfdbfe; background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 44%, #ffffff 100%); box-shadow: var(--shadow-soft); padding: 28px; position: relative; overflow: hidden; }
        .work-visual::after { content: ""; position: absolute; right: -70px; bottom: -70px; width: 260px; height: 260px; border-radius: 999px; background: rgba(37,99,235,0.14); }
        .mock-window { position: relative; z-index: 1; height: 100%; border-radius: 24px; background: white; border: 1px solid var(--slate-200); padding: 22px; box-shadow: 0 18px 48px rgba(37,99,235,0.12); }
        .mock-row { height: 12px; border-radius: 999px; background: var(--slate-100); margin-bottom: 12px; }
        .mock-row.blue { background: linear-gradient(90deg, var(--blue-600), #06b6d4); }
        .match-card { margin-top: 24px; border-radius: 22px; border: 1px solid #bfdbfe; background: #f8fbff; padding: 18px; }
        .match-card strong { display: block; font-size: 1.05rem; }
        .bar { height: 9px; border-radius: 999px; background: var(--blue-100); margin-top: 12px; overflow: hidden; }
        .bar span { display: block; height: 100%; width: 78%; background: linear-gradient(90deg, var(--blue-600), #06b6d4); border-radius: inherit; }
        .work-copy { border-radius: var(--radius-2xl); border: 1px solid var(--slate-200); background: white; padding: 34px; box-shadow: 0 18px 50px rgba(15, 23, 42, 0.06); }
        .work-copy h3 { margin: 12px 0 0; font-family: var(--font-display); font-size: 2.4rem; letter-spacing: -0.05em; }
        .work-copy p { color: var(--slate-600); line-height: 1.75; }
        .tags { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px; }
        .tag { display: inline-flex; align-items: center; min-height: 34px; padding: 0 12px; border-radius: 999px; background: var(--blue-50); color: var(--blue-800); font-size: 0.82rem; font-weight: 800; }
        .project-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-top: 18px; }
        .project-card { border: 1px solid var(--slate-200); border-radius: 26px; background: white; padding: 24px; text-decoration: none; transition: transform 180ms ease, border-color 180ms ease, box-shadow 180ms ease; }
        .project-card:hover { transform: translateY(-4px); border-color: #93c5fd; box-shadow: 0 18px 52px rgba(37,99,235,0.12); }
        .project-card .num { color: var(--blue-600); font-weight: 900; }
        .project-card h3 { margin: 16px 0 10px; font-size: 1.22rem; }
        .project-card p { margin: 0; color: var(--slate-600); line-height: 1.65; font-size: 0.94rem; }

        .capabilities { background: linear-gradient(180deg, transparent, var(--blue-50), transparent); }
        .cap-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; border: 1px solid var(--slate-200); border-radius: var(--radius-2xl); overflow: hidden; background: white; box-shadow: 0 18px 50px rgba(15,23,42,0.05); }
        .cap { padding: 28px; border-right: 1px solid var(--slate-200); }
        .cap:last-child { border-right: 0; }
        .icon { width: 48px; height: 48px; border-radius: 16px; display: grid; place-items: center; background: var(--blue-50); color: var(--blue-700); font-weight: 900; }
        .cap h3 { margin: 20px 0 10px; }
        .cap p { margin: 0; color: var(--slate-600); line-height: 1.65; font-size: 0.94rem; }

        .about-card { display: grid; grid-template-columns: 0.9fr 1.1fr; gap: 36px; align-items: center; border: 1px solid var(--slate-200); border-radius: 34px; background: white; padding: clamp(24px, 4vw, 44px); box-shadow: 0 18px 58px rgba(15,23,42,0.06); }
        .about-card p { color: var(--slate-600); line-height: 1.8; }
        .principles { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 24px; }
        .principle { border-radius: 20px; background: var(--blue-50); padding: 18px; }
        .principle strong { display: block; color: var(--blue-800); }
        .principle span { display: block; margin-top: 8px; color: var(--slate-600); font-size: 0.88rem; line-height: 1.55; }

        .contact-card { position: relative; overflow: hidden; border-radius: 36px; background: linear-gradient(135deg, var(--blue-950), var(--blue-800)); color: white; padding: clamp(28px, 5vw, 56px); box-shadow: 0 24px 80px rgba(29,78,216,0.26); }
        .contact-card::after { content: ""; position: absolute; right: -90px; top: -90px; width: 300px; height: 300px; border-radius: 999px; background: rgba(255,255,255,0.12); }
        .contact-card h2 { color: white; max-width: 760px; }
        .contact-card p { max-width: 680px; color: #dbeafe; line-height: 1.75; }
        .contact-links { position: relative; z-index: 1; display: flex; flex-wrap: wrap; gap: 12px; margin-top: 26px; }
        .contact-links .button { border-color: rgba(255,255,255,0.26); background: white; color: var(--blue-900); }
        .contact-links .button.secondary { background: transparent; color: white; }
        .footer { padding: 28px 0 40px; color: var(--slate-500); font-size: 0.9rem; }
        .footer-inner { display: flex; justify-content: space-between; gap: 18px; border-top: 1px solid var(--slate-200); padding-top: 24px; }

        .reveal { animation: riseFade 720ms cubic-bezier(0.16, 1, 0.3, 1) both; }
        .delay-1 { animation-delay: 120ms; }
        .delay-2 { animation-delay: 240ms; }
        @keyframes riseFade { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 960px) {
            .hero-grid, .work-feature, .about-card { grid-template-columns: 1fr; }
            .floating-card { position: static; margin: -18px 18px 0; max-width: none; }
            .project-grid { grid-template-columns: 1fr; }
            .cap-grid { grid-template-columns: repeat(2, 1fr); }
            .cap:nth-child(2) { border-right: 0; }
            .cap { border-bottom: 1px solid var(--slate-200); }
            .cap:nth-child(n+3) { border-bottom: 0; }
            .section-head { align-items: start; flex-direction: column; }
        }
        @media (max-width: 640px) {
            .container { width: min(100% - 32px, 1120px); }
            .nav-inner { height: 64px; }
            .brand-text span, .nav-links a:nth-child(2) { display: none; }
            .nav-links { gap: 14px; font-size: 0.82rem; }
            .nav-cta { display: none; }
            .hero { padding-top: 56px; }
            .hero-stats { grid-template-columns: 1fr; }
            .skill-grid, .cap-grid, .principles { grid-template-columns: 1fr; }
            .cap, .cap:nth-child(2), .cap:nth-child(n+3) { border-right: 0; border-bottom: 1px solid var(--slate-200); }
            .cap:last-child { border-bottom: 0; }
            .work-visual { min-height: 320px; padding: 18px; }
            .footer-inner { flex-direction: column; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; transition-duration: 0.01ms !important; }
        }
    </style>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "Muhamad Eko Alfianto",
        "url": "https://promo.lamaraja.web.id/",
        "sameAs": [
            "https://www.linkedin.com/in/muhamad-eko-alfianto-5805201a1/",
            "https://github.com/ekoalfianto"
        ],
        "knowsAbout": ["PHP", "JavaScript", "Laravel", "Web applications", "Automation", "AI-assisted workflows", "SEO systems"]
    }
    </script>
</head>
<body>
    <div class="page">
        <header class="nav">
            <div class="container nav-inner">
                <a class="brand" href="#top" aria-label="Muhamad Eko Alfianto portfolio">
                    <span class="brand-mark">EA</span>
                    <span class="brand-text"><strong>Muhamad Eko Alfianto</strong><span>Web Developer Portfolio</span></span>
                </a>
                <nav class="nav-links" aria-label="Main navigation">
                    <a href="#work">Work</a>
                    <a href="#about">About</a>
                    <a href="#contact">Contact</a>
                </nav>
                <a class="nav-cta" href="https://www.linkedin.com/in/muhamad-eko-alfianto-5805201a1/">LinkedIn</a>
            </div>
        </header>

        <main id="top">
            <section class="hero">
                <div class="container hero-grid">
                    <div class="reveal">
                        <div class="badge"><span class="badge-dot"></span> Senior Full Stack Developer / 200+ projects delivered</div>
                        <h1>Build clean products, scalable apps, and reliable APIs.</h1>
                        <p class="hero-copy">Portfolio Muhamad Eko Alfianto, Senior Full Stack Developer dengan 7+ tahun pengalaman dan 200+ project delivery. Fokus pada Laravel, Next.js, WordPress, Restful API, automation, dan web application yang scalable.</p>
                        <div class="hero-actions">
                            <a class="button button-primary" href="#work">See selected work</a>
                            <a class="button" href="https://github.com/ekoalfianto">View GitHub</a>
                        </div>
                        <div class="hero-stats">
                            <div class="stat"><strong>200+</strong><span>projects done independently and with teams.</span></div>
                            <div class="stat"><strong>7+</strong><span>years building Laravel, web apps, and APIs.</span></div>
                            <div class="stat"><strong>5+</strong><span>countries served through development teams and clients.</span></div>
                        </div>
                    </div>

                    <div class="profile-card reveal delay-1">
                        <div class="profile-screen">
                            <div class="screen-top"><span class="screen-dot"></span><span class="screen-dot"></span><span class="screen-dot"></span></div>
                            <div class="screen-body">
                                <div class="screen-label">Profile Snapshot</div>
                                <div class="screen-title">Senior developer for web, mobile-ready products, and API systems.</div>
                                <div class="skill-grid">
                                    <div class="skill"><strong>Laravel</strong><span>Responsive web apps, dashboards, and backend systems.</span></div>
                                    <div class="skill"><strong>Next.js</strong><span>React, Node.js, Express.js, and modern frontend stacks.</span></div>
                                    <div class="skill"><strong>WordPress</strong><span>Elementor, custom templates, conversion, and plugins.</span></div>
                                    <div class="skill"><strong>API</strong><span>Restful APIs, third-party integrations, and custom apps.</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="floating-card">
                            <strong>From old portfolio data</strong>
                            <span>Core details were migrated from ekoalfianto.my.id and refreshed into a cleaner Lamaraja-inspired interface.</span>
                        </div>
                    </div>
                </div>
            </section>

            <section id="work">
                <div class="container">
                    <div class="section-head">
                        <div>
                            <div class="kicker">Selected Work</div>
                            <h2>Projects with product intent.</h2>
                        </div>
                        <p class="section-lead">Fokus pada karya yang bisa diverifikasi atau dijelaskan secara jujur: Lamaraja, public GitHub projects, dan delivery background.</p>
                    </div>

                    <div class="work-feature">
                        <div class="work-visual" aria-hidden="true">
                            <div class="mock-window">
                                <div class="mock-row blue" style="width: 58%"></div>
                                <div class="mock-row" style="width: 92%"></div>
                                <div class="mock-row" style="width: 76%"></div>
                                <div class="match-card">
                                    <strong>Lamaraja CV Matcher</strong>
                                    <div class="bar"><span></span></div>
                                </div>
                                <div class="match-card">
                                    <strong>Trusted Job Refresh</strong>
                                    <div class="bar"><span style="width: 88%"></span></div>
                                </div>
                            </div>
                        </div>
                        <article class="work-copy">
                            <div class="kicker">Featured Product</div>
                            <h3>Lamaraja</h3>
                            <p>AI-powered job portal for Indonesia dengan CV Matcher, job aggregation, trusted-source ingest, logo quality gates, SEO content, sitemap coverage, dan performance improvement untuk mobile users.</p>
                            <div class="tags">
                                <span class="tag">Laravel</span>
                                <span class="tag">AI Workflow</span>
                                <span class="tag">Automation</span>
                                <span class="tag">Technical SEO</span>
                            </div>
                            <div class="hero-actions">
                                <a class="button button-primary" href="https://lamaraja.web.id/">Open Lamaraja</a>
                                <a class="button" href="#contact">Discuss Project</a>
                            </div>
                        </article>
                    </div>

                    <div class="project-grid">
                        <a class="project-card" href="https://github.com/ekoalfianto" target="_blank" rel="noopener">
                            <span class="num">01</span>
                            <h3>Simtaka Apps</h3>
                            <p>Laravel and Vue.js application listed in the previous portfolio as selected work.</p>
                        </a>
                        <a class="project-card" href="#contact">
                            <span class="num">02</span>
                            <h3>Inilah News Portal</h3>
                            <p>Laravel, Next.js, and Restful API work from the legacy portfolio project list.</p>
                        </a>
                        <a class="project-card" href="#contact">
                            <span class="num">03</span>
                            <h3>Javabica Online Shop</h3>
                            <p>Laravel, Nuxt, and Restful API online shop implementation from earlier selected work.</p>
                        </a>
                    </div>
                    <div class="project-grid">
                        <a class="project-card" href="#contact">
                            <span class="num">04</span>
                            <h3>Nifty Educate Blockchain</h3>
                            <p>Laravel, Web3, and React.js blockchain application from the old portfolio archive.</p>
                        </a>
                        <a class="project-card" href="#contact">
                            <span class="num">05</span>
                            <h3>E-BPN</h3>
                            <p>PHP, CodeIgniter, and Bootstrap project listed as selected work.</p>
                        </a>
                        <a class="project-card" href="#contact">
                            <span class="num">06</span>
                            <h3>Sidokoe</h3>
                            <p>Laravel, Tailwind, and PHP project from the previous portfolio.</p>
                        </a>
                    </div>
                </div>
            </section>

            <section class="capabilities" id="capabilities">
                <div class="container">
                    <div class="section-head">
                        <div>
                            <div class="kicker">Capabilities</div>
                            <h2>What I can help with.</h2>
                        </div>
                        <p class="section-lead">Dibuat seperti Lamaraja: clear sections, benefit-first copy, cards yang ringan, dan CTA yang langsung.</p>
                    </div>
                    <div class="cap-grid">
                        <article class="cap"><div class="icon">01</div><h3>Laravel Development</h3><p>7+ years building responsive and interactive web applications with Laravel technologies.</p></article>
                        <article class="cap"><div class="icon">02</div><h3>Next.js Development</h3><p>Modern development with Next.js, Express.js, React, and Node.js-based stacks.</p></article>
                        <article class="cap"><div class="icon">03</div><h3>WordPress Development</h3><p>Elementor, custom templates, HTML to WordPress conversion, and plugin development.</p></article>
                        <article class="cap"><div class="icon">04</div><h3>Restful API Development</h3><p>Custom applications, third-party integrations, and seamless API functionality.</p></article>
                    </div>
                </div>
            </section>

            <section id="experience">
                <div class="container">
                    <div class="section-head">
                        <div>
                            <div class="kicker">Experience</div>
                            <h2>Companies and teams from the old portfolio.</h2>
                        </div>
                        <p class="section-lead">Experience data migrated from ekoalfianto.my.id and presented in a cleaner Lamaraja-style timeline.</p>
                    </div>
                    <div class="project-grid">
                        <article class="project-card">
                            <span class="num">Oct 2021 - Sep 2024</span>
                            <h3>Senior Full Stack Developer | Nine Dragon Labs</h3>
                            <p>Nine Dragon Labs serves clients across more than 5 countries with teams in front-end, back-end, UI/UX, technology consulting, and blockchain smart contract development.</p>
                        </article>
                        <article class="project-card">
                            <span class="num">Jan 2017 - Aug 2021</span>
                            <h3>Full Stack Developer | Inowtech</h3>
                            <p>Inowtech is a digital technology company focused on development, research, digitization, agencies, careers, and MSME business needs.</p>
                        </article>
                        <article class="project-card">
                            <span class="num">Freelance</span>
                            <h3>Independent and marketplace delivery</h3>
                            <p>Delivered practical client work independently and with teams, with emphasis on communication, planning, and reliable execution.</p>
                        </article>
                    </div>
                </div>
            </section>

            <section id="testimonials" class="capabilities">
                <div class="container">
                    <div class="section-head">
                        <div>
                            <div class="kicker">Testimonials</div>
                            <h2>Client words from previous portfolio.</h2>
                        </div>
                        <p class="section-lead">Selected feedback highlights organization, communication, patience, quality delivery, and backend/devops strength.</p>
                    </div>
                    <div class="project-grid">
                        <article class="project-card"><span class="num">Michael Kravc</span><h3>Road To Virtuosity</h3><p>Professional and well organized, with step-by-step planning and quick completion.</p></article>
                        <article class="project-card"><span class="num">Ahmed Samah</span><h3>Jobsicle</h3><p>Talented developer building applications with latest technology and strong client communication.</p></article>
                        <article class="project-card"><span class="num">Michael Thomp</span><h3>United Kingdom</h3><p>Strong back-end and dev-ops skills, high quality assurance, and detail-oriented work.</p></article>
                    </div>
                </div>
            </section>

            <section id="about">
                <div class="container about-card">
                    <div>
                        <div class="kicker">About</div>
                        <h2>Clean work, clear progress.</h2>
                    </div>
                    <div>
                        <p>Muhamad Eko Alfianto is a Senior Full Stack Developer with experience delivering web products independently and as part of development teams. The old portfolio positions him around Laravel, Next.js, WordPress, Restful API, web development, security awareness, and scalable client solutions.</p>
                        <p>This refreshed version keeps the proven content from ekoalfianto.my.id, then repackages it into a clean blue interface inspired by Lamaraja: clearer sections, sharper CTAs, better project hierarchy, and stronger presentation.</p>
                        <div class="principles">
                            <div class="principle"><strong>01. Clear first</strong><span>Make the message easy to understand before adding complexity.</span></div>
                            <div class="principle"><strong>02. Ship useful</strong><span>Build flows that solve actual work: upload, match, ingest, validate, publish.</span></div>
                            <div class="principle"><strong>03. Improve openly</strong><span>Report changes, validation, and decisions so progress is visible.</span></div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="contact">
                <div class="container contact-card">
                    <div class="kicker" style="color:#bfdbfe">Contact</div>
                    <h2>Need a clean web product or automation flow?</h2>
                    <p>Open to selected opportunities, collaborations, and practical web projects. Reach out through LinkedIn while this temporary portfolio is hosted on promo.lamaraja.web.id.</p>
                    <div class="contact-links">
                        <a class="button" href="https://www.linkedin.com/in/muhamad-eko-alfianto-5805201a1/">Message on LinkedIn</a>
                        <a class="button secondary" href="https://github.com/ekoalfianto">View GitHub</a>
                        <a class="button secondary" href="https://lamaraja.web.id/">See Lamaraja</a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="footer">
            <div class="container footer-inner">
                <span>&copy; 2026 Muhamad Eko Alfianto. Portfolio in progress.</span>
                <span>Clean modern blue UI inspired by Lamaraja.</span>
            </div>
        </footer>
    </div>
</body>
</html>
