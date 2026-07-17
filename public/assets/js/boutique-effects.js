/* Pure vanilla JS — no jQuery dependency, unlike main.js (which bundles
   slick/lightGallery/etc. this redesign's templates don't use). Fades
   images in as they finish loading instead of popping in, reveals
   sections/cards as the user scrolls to them, and drives the
   back-to-top button's visibility. */
(function () {
    'use strict';

    function markLoaded(img) {
        img.classList.add('is-loaded');
    }

    document.querySelectorAll('img[loading="lazy"]').forEach(function (img) {
        if (img.complete) {
            markLoaded(img);
        } else {
            img.addEventListener('load', function () { markLoaded(img); });
            img.addEventListener('error', function () { markLoaded(img); });
        }
    });

    var revealTargets = document.querySelectorAll('.reveal-on-scroll');
    if (revealTargets.length) {
        if (!('IntersectionObserver' in window)) {
            revealTargets.forEach(function (el) { el.classList.add('is-visible'); });
        } else {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });
            revealTargets.forEach(function (el) { observer.observe(el); });
        }
    }

    var backToTopBtn = document.getElementById('back-to-top');
    if (backToTopBtn) {
        var SHOW_AFTER_PX = 400;
        var ticking = false;

        function updateVisibility() {
            var isPastFold = window.scrollY > SHOW_AFTER_PX;
            backToTopBtn.style.opacity = isPastFold ? '1' : '0';
            backToTopBtn.style.pointerEvents = isPastFold ? 'auto' : 'none';
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(updateVisibility);
        }, { passive: true });

        updateVisibility();

        backToTopBtn.addEventListener('click', function () {
            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
        });
    }
})();
