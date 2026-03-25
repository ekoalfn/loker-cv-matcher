import './bootstrap';

import Alpine from 'alpinejs';
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
