import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const initializeCountUp = () => {
    const counterElements = document.querySelectorAll('[data-count-up]');

    if (!counterElements.length) {
        return;
    }

    const numberFormatter = new Intl.NumberFormat('id-ID');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const renderFinalValue = (element) => {
        const target = Number(element.dataset.target ?? 0);

        element.textContent = numberFormatter.format(target);
    };

    if (reduceMotion || !('IntersectionObserver' in window)) {
        counterElements.forEach((element) => {
            renderFinalValue(element);
        });

        return;
    }

    const animateCount = (element) => {
        const target = Number(element.dataset.target ?? 0);

        if (!Number.isFinite(target) || target < 0) {
            element.textContent = target.toString();

            return;
        }

        const duration = 1200;
        const animationStart = performance.now();

        const tick = (currentTime) => {
            const progress = Math.min((currentTime - animationStart) / duration, 1);
            const currentValue = Math.round(target * progress);

            element.textContent = numberFormatter.format(currentValue);

            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        };

        requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver(
        (entries, currentObserver) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                animateCount(entry.target);
                currentObserver.unobserve(entry.target);
            });
        },
        {
            threshold: 0.35,
        },
    );

    counterElements.forEach((element) => {
        observer.observe(element);
    });
};

document.addEventListener('DOMContentLoaded', initializeCountUp);
