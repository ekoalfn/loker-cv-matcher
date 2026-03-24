import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// --- Scroll Reveal Observer ---
// Elements with class "reveal" will fade-up when entering viewport
const revealObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
);

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.reveal').forEach((el, i) => {
        // Stagger delay for grouped reveals
        if (el.dataset.revealDelay) {
            el.style.transitionDelay = el.dataset.revealDelay;
        } else if (el.closest('[data-reveal-stagger]')) {
            el.style.transitionDelay = `${i * 80}ms`;
        }
        revealObserver.observe(el);
    });
});
