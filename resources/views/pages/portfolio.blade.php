<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio Muhamad Eko Alfianto, junior web developer focused on PHP, JavaScript, Laravel, automation, AI workflow, SEO systems, and practical digital products.">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Muhamad Eko Alfianto - Web Developer Portfolio">
    <meta property="og:description" content="Warm editorial portfolio for web applications, automation, AI-assisted workflows, SEO systems, and selected product work.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://promo.lamaraja.web.id/">
    <meta name="twitter:card" content="summary_large_image">
    <title>Muhamad Eko Alfianto - Web Developer Portfolio</title>
    <link rel="canonical" href="https://promo.lamaraja.web.id/">
    <style>
        :root {
            --bg: #f4efe7;
            --surface: #fffaf2;
            --surface-muted: #e8ded0;
            --text: #181612;
            --text-muted: #6f675c;
            --text-soft: #9a9083;
            --line: #d6cab9;
            --accent: #d94f2b;
            --accent-deep: #9f321c;
            --success: #3f6f52;
            --font-display: Georgia, "Times New Roman", Times, serif;
            --font-body: "Trebuchet MS", Verdana, Geneva, sans-serif;
            --font-mono: "Courier New", Courier, monospace;
            --page-max: 1440px;
            --grid-margin: clamp(20px, 4vw, 72px);
            --grid-gap: clamp(16px, 2vw, 32px);
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
            --duration-fast: 180ms;
            --duration-slow: 800ms;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: var(--font-body);
            background:
                radial-gradient(circle at 18% 6%, rgba(217, 79, 43, 0.10), transparent 26%),
                radial-gradient(circle at 92% 22%, rgba(63, 111, 82, 0.10), transparent 24%),
                linear-gradient(rgba(24, 22, 18, 0.026) 1px, transparent 1px),
                linear-gradient(90deg, rgba(24, 22, 18, 0.026) 1px, transparent 1px),
                var(--bg);
            background-size: auto, auto, 48px 48px, 48px 48px, auto;
            color: var(--text);
        }
        a { color: inherit; }
        ::selection { background: var(--accent); color: var(--bg); }

        .page { max-width: var(--page-max); margin: 0 auto; padding-inline: var(--grid-margin); }
        .grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: var(--grid-gap); }
        .meta { font-family: var(--font-mono); font-size: 0.76rem; letter-spacing: 0.08em; text-transform: uppercase; color: var(--text-soft); }
        .nav {
            position: sticky; top: 0; z-index: 20;
            display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 20px;
            padding-block: 18px; border-bottom: 1px solid var(--line);
            background: color-mix(in srgb, var(--bg) 86%, transparent); backdrop-filter: blur(16px);
        }
        .brand { font-family: var(--font-mono); font-size: 0.86rem; text-decoration: none; letter-spacing: 0.08em; text-transform: uppercase; }
        .nav-status { justify-self: center; display: inline-flex; align-items: center; gap: 8px; }
        .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--success); box-shadow: 0 0 0 5px rgba(63,111,82,.12); }
        .nav-links { justify-self: end; display: flex; gap: clamp(14px, 2vw, 28px); font-family: var(--font-mono); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .nav-links a { text-decoration: none; color: var(--text-muted); transition: color var(--duration-fast) ease; }
        .nav-links a:hover { color: var(--accent-deep); }

        .hero { min-height: calc(100svh - 66px); display: grid; align-content: center; padding-block: clamp(64px, 10vw, 140px); }
        .hero-title { grid-column: 1 / span 8; font-family: var(--font-display); font-size: clamp(3.7rem, 9vw, 9.2rem); line-height: 0.88; letter-spacing: -0.065em; font-weight: 400; margin: 18px 0 0; }
        .hero-title em { color: var(--accent); font-style: italic; }
        .hero-aside { grid-column: 10 / span 3; align-self: end; border-left: 1px solid var(--line); padding-left: clamp(18px, 2vw, 32px); }
        .hero-aside p { margin: 14px 0 0; color: var(--text-muted); line-height: 1.7; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 28px; }
        .button { display: inline-flex; align-items: center; gap: 10px; min-height: 44px; padding: 0 18px; border: 1px solid var(--text); border-radius: 999px; background: transparent; color: var(--text); font-family: var(--font-mono); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none; transition: background var(--duration-fast) ease, color var(--duration-fast) ease, border-color var(--duration-fast) ease; }
        .button:hover { background: var(--text); color: var(--bg); }
        .button-accent { background: var(--accent); border-color: var(--accent); color: var(--surface); }
        .button-accent:hover { background: var(--accent-deep); border-color: var(--accent-deep); }
        .hero-foot { grid-column: 1 / -1; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--grid-gap); margin-top: clamp(52px, 9vw, 112px); border-top: 1px solid var(--line); }
        .fact { padding-top: 22px; color: var(--text-muted); line-height: 1.55; }
        .fact strong { display: block; color: var(--text); margin-bottom: 8px; }

        section { padding-block: clamp(72px, 11vw, 160px); }
        .section-kicker { grid-column: 1 / span 3; }
        .section-title { grid-column: 4 / span 7; font-family: var(--font-display); font-size: clamp(2.45rem, 5.2vw, 6.5rem); line-height: 0.95; letter-spacing: -0.05em; font-weight: 400; margin: 0; }
        .section-copy { grid-column: 9 / span 4; color: var(--text-muted); line-height: 1.7; margin: 0; }

        .featured { grid-column: 1 / -1; display: grid; grid-template-columns: 7fr 5fr; gap: var(--grid-gap); padding-top: 48px; margin-top: 32px; border-top: 1px solid var(--line); }
        .project-visual { min-height: clamp(300px, 44vw, 610px); border: 1px solid var(--line); background: linear-gradient(135deg, rgba(217,79,43,.16), transparent 42%), repeating-linear-gradient(90deg, rgba(24,22,18,.055) 0, rgba(24,22,18,.055) 1px, transparent 1px, transparent 32px), var(--surface); position: relative; overflow: hidden; }
        .project-visual::before { content: "Lamaraja"; position: absolute; left: clamp(24px, 4vw, 64px); bottom: clamp(24px, 4vw, 64px); font-family: var(--font-display); font-size: clamp(3rem, 8vw, 9rem); letter-spacing: -0.07em; color: rgba(24,22,18,.12); }
        .project-visual::after { content: "AI CV MATCHER / JOB INGEST / SEO"; position: absolute; right: 24px; top: 24px; font-family: var(--font-mono); font-size: 0.75rem; letter-spacing: 0.08em; color: var(--accent-deep); }
        .project-detail { align-self: end; padding-bottom: clamp(8px, 2vw, 36px); }
        .project-detail h3 { font-family: var(--font-display); font-size: clamp(2.4rem, 4.4vw, 5.8rem); line-height: 0.95; letter-spacing: -0.055em; margin: 14px 0 20px; font-weight: 400; }
        .project-detail p { color: var(--text-muted); line-height: 1.72; font-size: clamp(1rem, 1.4vw, 1.18rem); }
        .tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 24px; }
        .tag { display: inline-block; padding: 6px 10px; border: 1px solid var(--line); border-radius: 999px; font-family: var(--font-mono); font-size: 0.72rem; color: var(--text-muted); background: rgba(255,250,242,.52); }

        .project-index { grid-column: 1 / -1; margin-top: 38px; }
        .project-row { display: grid; grid-template-columns: 72px 1.5fr 1fr 92px; gap: 24px; align-items: baseline; padding-block: 24px; border-top: 1px solid var(--line); text-decoration: none; transition: background var(--duration-fast) ease, padding-inline var(--duration-fast) ease; }
        .project-row:last-child { border-bottom: 1px solid var(--line); }
        .project-row:hover { background: rgba(255,250,242,.68); padding-inline: 16px; }
        .project-row:hover .project-number { color: var(--accent); }
        .project-name { font-family: var(--font-display); font-size: clamp(1.55rem, 2.4vw, 2.8rem); letter-spacing: -0.04em; }
        .project-type, .project-year { color: var(--text-muted); }

        .about-title { grid-column: 1 / span 5; font-family: var(--font-display); font-size: clamp(2.8rem, 6vw, 7.5rem); line-height: 0.9; letter-spacing: -0.06em; margin: 0; font-weight: 400; }
        .about-copy { grid-column: 7 / span 5; color: var(--text-muted); line-height: 1.75; font-size: clamp(1rem, 1.35vw, 1.16rem); }
        .about-copy p { margin: 0 0 18px; }
        .principles { grid-column: 1 / -1; display: grid; grid-template-columns: repeat(3, 1fr); border-top: 1px solid var(--line); margin-top: 64px; }
        .principle { padding: 28px; border-right: 1px solid var(--line); background: rgba(255,250,242,.36); }
        .principle:last-child { border-right: none; }
        .principle h3 { margin: 16px 0 10px; font-size: 1.18rem; }
        .principle p { margin: 0; color: var(--text-muted); line-height: 1.65; }

        .capability-card { grid-column: span 3; min-height: 210px; display: flex; flex-direction: column; justify-content: space-between; padding: 26px; border: 1px solid var(--line); background: var(--surface); }
        .capability-card h3 { margin: 20px 0 10px; font-size: 1.28rem; }
        .capability-card p { margin: 0; color: var(--text-muted); line-height: 1.65; }

        .contact { padding-bottom: 52px; }
        .contact-title { grid-column: 1 / span 9; font-family: var(--font-display); font-size: clamp(3.4rem, 10vw, 12rem); line-height: 0.84; letter-spacing: -0.075em; margin: 0; font-weight: 400; }
        .contact-panel { grid-column: 9 / span 4; align-self: end; border-top: 1px solid var(--line); padding-top: 24px; }
        .contact-panel p { color: var(--text-muted); line-height: 1.75; }
        .contact-links { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 22px; }
        .footer { display: grid; grid-template-columns: 1fr auto; gap: 24px; padding-block: 28px; border-top: 1px solid var(--line); font-family: var(--font-mono); font-size: 0.78rem; color: var(--text-soft); }

        .reveal { animation: riseFade var(--duration-slow) var(--ease-out) both; }
        .delay-1 { animation-delay: 120ms; }
        .delay-2 { animation-delay: 240ms; }
        .delay-3 { animation-delay: 360ms; }
        @keyframes riseFade { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 1023px) {
            .grid { grid-template-columns: repeat(6, 1fr); }
            .hero-title, .hero-aside, .section-kicker, .section-title, .section-copy, .featured, .about-title, .about-copy, .contact-title, .contact-panel { grid-column: 1 / -1; }
            .hero-aside { border-left: 0; border-top: 1px solid var(--line); padding: 22px 0 0; }
            .featured { grid-template-columns: 1fr; }
            .capability-card { grid-column: span 3; }
            .contact-panel { max-width: 560px; }
        }
        @media (max-width: 767px) {
            .nav { grid-template-columns: 1fr auto; }
            .nav-status { display: none; }
            .nav-links { gap: 12px; font-size: 0.72rem; }
            .nav-links a:nth-child(2) { display: none; }
            .hero { min-height: auto; padding-top: 70px; }
            .hero-title { font-size: clamp(3.2rem, 16vw, 5.2rem); }
            .hero-foot { grid-template-columns: 1fr; }
            section { padding-block: 72px; }
            .section-title { font-size: clamp(2.65rem, 12vw, 4.4rem); }
            .project-row { grid-template-columns: 1fr; gap: 8px; }
            .principles { grid-template-columns: 1fr; }
            .principle { border-right: none; border-bottom: 1px solid var(--line); }
            .capability-card { grid-column: 1 / -1; min-height: 170px; }
            .footer { grid-template-columns: 1fr; }
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
        <nav class="nav" aria-label="Main navigation">
            <a class="brand" href="#top">Eko / Portfolio</a>
            <div class="nav-status meta"><span class="dot"></span> Open for selected work</div>
            <div class="nav-links">
                <a href="#work">Work</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>
        </nav>

        <main id="top">
            <section class="hero grid" aria-label="Portfolio introduction">
                <div class="hero-title reveal">
                    I build <em>quietly useful</em> web systems.
                </div>
                <aside class="hero-aside reveal delay-1">
                    <div class="meta">Personal index / 2026</div>
                    <p>Muhamad Eko Alfianto is a web developer focused on practical digital products, PHP and JavaScript projects, automation, AI-assisted workflows, and discoverable web experiences.</p>
                    <div class="hero-actions">
                        <a class="button button-accent" href="#work">View work</a>
                        <a class="button" href="https://www.linkedin.com/in/muhamad-eko-alfianto-5805201a1/">LinkedIn</a>
                    </div>
                </aside>
                <div class="hero-foot reveal delay-2">
                    <div class="fact"><strong>Current focus</strong>Laravel products, landing pages, data workflows, and lightweight AI features that solve real tasks.</div>
                    <div class="fact"><strong>Public footprint</strong>GitHub projects show PHP, JavaScript, EJS, CMS experiments, and decision support work using Fuzzy Tsukamoto.</div>
                    <div class="fact"><strong>Selected build</strong>Lamaraja: an Indonesian job platform with CV Matcher, trusted job ingest, SEO pages, and operational automation.</div>
                </div>
            </section>

            <section id="work" class="grid" aria-label="Selected work">
                <div class="section-kicker meta">01 / Selected work</div>
                <h1 class="section-title">Projects shaped around clarity, utility, and shipping.</h1>
                <p class="section-copy">The portfolio starts with verified and in-progress work: real product systems, public code signals, and honest space for future case studies.</p>

                <article class="featured">
                    <div class="project-visual" aria-hidden="true"></div>
                    <div class="project-detail">
                        <div class="meta">Featured product</div>
                        <h3>Lamaraja</h3>
                        <p>AI-powered job portal for Indonesia with CV matching, trusted-source job ingestion, logo quality gates, SEO improvements, sitemap coverage, and performance work for mobile users.</p>
                        <div class="tags">
                            <span class="tag">Laravel</span>
                            <span class="tag">AI workflow</span>
                            <span class="tag">Job ingest</span>
                            <span class="tag">Technical SEO</span>
                        </div>
                    </div>
                </article>

                <div class="project-index" aria-label="Project index">
                    <a class="project-row" href="https://lamaraja.web.id/" target="_blank" rel="noopener">
                        <span class="project-number meta">02</span>
                        <span class="project-name">Lamaraja public site</span>
                        <span class="project-type">Job portal / CV Matcher</span>
                        <span class="project-year">Live</span>
                    </a>
                    <a class="project-row" href="https://github.com/ekoalfianto" target="_blank" rel="noopener">
                        <span class="project-number meta">03</span>
                        <span class="project-name">SPK Fuzzy Tsukamoto</span>
                        <span class="project-type">Decision support / JavaScript</span>
                        <span class="project-year">GitHub</span>
                    </a>
                    <a class="project-row" href="https://github.com/ekoalfianto" target="_blank" rel="noopener">
                        <span class="project-number meta">04</span>
                        <span class="project-name">CMS and web experiments</span>
                        <span class="project-type">PHP / EJS / learning archive</span>
                        <span class="project-year">Public</span>
                    </a>
                    <a class="project-row" href="#contact">
                        <span class="project-number meta">05</span>
                        <span class="project-name">Freelance delivery</span>
                        <span class="project-type">Practical web builds / client work</span>
                        <span class="project-year">On request</span>
                    </a>
                </div>
            </section>

            <section id="about" class="grid" aria-label="About Muhamad Eko Alfianto">
                <h2 class="about-title">About the way I work.</h2>
                <div class="about-copy">
                    <p>I work between web development, product clarity, and operational problem solving. The goal is simple: turn rough ideas into digital surfaces that people can understand, use, and improve.</p>
                    <p>LinkedIn public data is limited without login, so this page avoids unsupported claims. It uses verified public signals from GitHub, known Lamaraja work, and clear positioning that can grow as more projects are documented.</p>
                    <p>Former freelance marketplace experience is treated as delivery background, not the whole story. The focus here is the next version: stronger proof, better case studies, and cleaner communication.</p>
                </div>
                <div class="principles">
                    <div class="principle">
                        <div class="meta">01</div>
                        <h3>Reduce before adding</h3>
                        <p>Start with the message, remove noise, then add interface details that make the work easier to trust.</p>
                    </div>
                    <div class="principle">
                        <div class="meta">02</div>
                        <h3>Ship practical systems</h3>
                        <p>Favor useful flows: upload, match, ingest, validate, publish, measure, and improve.</p>
                    </div>
                    <div class="principle">
                        <div class="meta">03</div>
                        <h3>Make progress visible</h3>
                        <p>Document changes, validations, and decisions so collaborators can see what moved forward.</p>
                    </div>
                </div>
            </section>

            <section id="capabilities" class="grid" aria-label="Capabilities">
                <div class="section-kicker meta">02 / Capabilities</div>
                <h2 class="section-title">Clear support for digital products and presentation.</h2>
                <p class="section-copy">Capabilities are intentionally framed around what can be supported by current work and public traces.</p>
                <article class="capability-card">
                    <div class="meta">PHP / Laravel</div>
                    <div><h3>Web application builds</h3><p>Product pages, dashboards, forms, data views, and internal tools with pragmatic implementation.</p></div>
                </article>
                <article class="capability-card">
                    <div class="meta">Automation</div>
                    <div><h3>Data and workflow systems</h3><p>Scheduled refreshes, quality gates, scraping-aware ingestion, and repeatable operational checks.</p></div>
                </article>
                <article class="capability-card">
                    <div class="meta">AI workflow</div>
                    <div><h3>AI-assisted features</h3><p>Matching, summaries, recommendations, and decision support flows where AI has a focused role.</p></div>
                </article>
                <article class="capability-card">
                    <div class="meta">SEO / Content</div>
                    <div><h3>Discoverable pages</h3><p>Metadata, structured data, sitemap coverage, performance basics, and landing page clarity.</p></div>
                </article>
            </section>

            <section id="contact" class="contact grid" aria-label="Contact">
                <h2 class="contact-title">Have something complex that needs to feel simple?</h2>
                <div class="contact-panel">
                    <div class="meta">Contact / Collaboration</div>
                    <p>Open to selected opportunities, collaborations, and practical web projects. Reach out through LinkedIn while this temporary portfolio lives on promo.lamaraja.web.id.</p>
                    <div class="contact-links">
                        <a class="button button-accent" href="https://www.linkedin.com/in/muhamad-eko-alfianto-5805201a1/">Message on LinkedIn</a>
                        <a class="button" href="https://github.com/ekoalfianto">View GitHub</a>
                        <a class="button" href="https://lamaraja.web.id/">See Lamaraja</a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="footer">
            <span>&copy; 2026 Muhamad Eko Alfianto. Portfolio in progress.</span>
            <span>Built lightweight / GMT+7</span>
        </footer>
    </div>
</body>
</html>
