import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
Alpine.plugin(collapse);

Alpine.data('jobFilters', () => ({
    searchLocation: '',
    loading: false,

    async apply(form) {
        const url = this.buildUrl(form);
        this.loading = true;

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'text/html',
                },
            });

            if (!response.ok) {
                form.submit();
                return;
            }

            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            this.swap('[data-jobs-results]', doc);
            this.swap('[data-results-count]', doc);
            window.history.pushState({}, '', url);
        } catch (error) {
            form.submit();
        } finally {
            this.loading = false;
        }
    },

    buildUrl(form) {
        const params = new URLSearchParams();
        const formData = new FormData(form);

        for (const [key, value] of formData.entries()) {
            if (String(value).trim() !== '') {
                params.append(key, value);
            }
        }

        const query = params.toString();
        return `${form.action}${query ? `?${query}` : ''}`;
    },

    swap(selector, doc) {
        const current = document.querySelector(selector);
        const next = doc.querySelector(selector);

        if (current && next) {
            current.innerHTML = next.innerHTML;
        }
    },
}));

window.Alpine = Alpine;
Alpine.start();

// --- Scroll Reveal Observer ---
const revealObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.08, rootMargin: '0px 0px -60px 0px' }
);

document.addEventListener('DOMContentLoaded', () => {
    // Stagger reveals within groups
    document.querySelectorAll('[data-reveal-stagger]').forEach((container) => {
        container.querySelectorAll('.reveal').forEach((el, i) => {
            el.style.transitionDelay = `${i * 100}ms`;
        });
    });

    // Individual reveals
    document.querySelectorAll('.reveal').forEach((el) => {
        if (el.dataset.revealDelay) {
            el.style.transitionDelay = el.dataset.revealDelay;
        }
        revealObserver.observe(el);
    });
});
