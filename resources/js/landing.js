/**
 * Ngoffe ID — interaksi landing page.
 *
 * Prinsip:
 * - Tanpa dependency tambahan.
 * - Animasi hanya transform/opacity.
 * - Semua efek dihentikan saat prefers-reduced-motion aktif.
 */

const doc = document;
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

/* --------------------------------------------------------------------------
   Header: transparan di atas hero, solid setelah scroll
   -------------------------------------------------------------------------- */
const header = doc.querySelector('[data-site-header]');

function syncHeader() {
    header?.classList.toggle('is-scrolled', window.scrollY > 16);
}

syncHeader();
window.addEventListener('scroll', syncHeader, { passive: true });
window.addEventListener('pageshow', syncHeader);

/* --------------------------------------------------------------------------
   Menu mobile
   -------------------------------------------------------------------------- */
const navToggle = doc.querySelector('[data-nav-toggle]');
const primaryNav = doc.getElementById('nav-menu');

function setMenu(open, restoreFocus = false) {
    if (!navToggle || !primaryNav) {
        return;
    }

    navToggle.setAttribute('aria-expanded', String(open));
    navToggle.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
    primaryNav.classList.toggle('is-open', open);
    doc.body.classList.toggle('menu-open', open);
    header?.classList.toggle('is-open', open);

    if (restoreFocus) {
        navToggle.focus();
    }
}

if (navToggle && primaryNav) {
    navToggle.addEventListener('click', () => {
        setMenu(navToggle.getAttribute('aria-expanded') !== 'true');
    });

    primaryNav.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            setMenu(false);
        }
    });

    doc.addEventListener('pointerdown', (event) => {
        if (!primaryNav.classList.contains('is-open')) {
            return;
        }

        if (primaryNav.contains(event.target) || navToggle.contains(event.target)) {
            return;
        }

        setMenu(false);
    });

    doc.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && primaryNav.classList.contains('is-open')) {
            setMenu(false, true);
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 900 && primaryNav.classList.contains('is-open')) {
            setMenu(false);
        }
    });
}

/* --------------------------------------------------------------------------
   Reveal saat elemen masuk viewport
   -------------------------------------------------------------------------- */
const revealItems = Array.from(doc.querySelectorAll('.reveal'));
const pendingReveal = new Set(revealItems);

function revealInView() {
    if (pendingReveal.size === 0) {
        return;
    }

    const limit = window.innerHeight * 0.94;

    pendingReveal.forEach((item) => {
        const rect = item.getBoundingClientRect();

        if (rect.top < limit && rect.bottom > 0) {
            item.classList.add('is-in');
            pendingReveal.delete(item);
        }
    });
}

if (reduceMotion.matches) {
    pendingReveal.forEach((item) => item.classList.add('is-in'));
    pendingReveal.clear();
} else {
    // Fallback langsung: tetap tampil walau IntersectionObserver tertunda
    // (misalnya saat tab dibuka di latar belakang).
    revealInView();
    window.addEventListener('scroll', revealInView, { passive: true });
    window.addEventListener('resize', revealInView, { passive: true });
    window.addEventListener('load', revealInView);
    window.addEventListener('pageshow', revealInView);

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-in');
                    pendingReveal.delete(entry.target);
                    revealObserver.unobserve(entry.target);
                });
            },
            {
                threshold: 0.12,
                rootMargin: '0px 0px -8% 0px',
            },
        );

        revealItems.forEach((item) => revealObserver.observe(item));
    }
}

/* --------------------------------------------------------------------------
   FAQ accordion
   -------------------------------------------------------------------------- */
const faqButtons = doc.querySelectorAll('[data-faq-button]');

faqButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const item = button.closest('.faq-item');
        const willOpen = !item.classList.contains('is-open');

        doc.querySelectorAll('.faq-item.is-open').forEach((other) => {
            if (other === item) {
                return;
            }

            other.classList.remove('is-open');
            other.querySelector('[data-faq-button]')?.setAttribute('aria-expanded', 'false');
        });

        item.classList.toggle('is-open', willOpen);
        button.setAttribute('aria-expanded', String(willOpen));
    });
});

/* --------------------------------------------------------------------------
   Parallax ringan pada ilustrasi hero
   -------------------------------------------------------------------------- */
const scene = doc.querySelector('[data-parallax-scene]');
const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)');

if (scene && finePointer.matches && !reduceMotion.matches) {
    const layers = scene.querySelectorAll('.scene-layer');
    let frame = 0;
    let pointerX = 0;
    let pointerY = 0;

    const render = () => {
        frame = 0;

        layers.forEach((layer) => {
            const depth = Number.parseFloat(layer.dataset.depth ?? '0');

            layer.style.transform = `translate3d(${(-pointerX * depth * 18).toFixed(2)}px, ${(
                -pointerY * depth * 14
            ).toFixed(2)}px, 0)`;
        });
    };

    scene.addEventListener(
        'pointermove',
        (event) => {
            const rect = scene.getBoundingClientRect();

            pointerX = (event.clientX - rect.left) / rect.width - 0.5;
            pointerY = (event.clientY - rect.top) / rect.height - 0.5;

            if (!frame) {
                frame = window.requestAnimationFrame(render);
            }
        },
        { passive: true },
    );

    scene.addEventListener('pointerleave', () => {
        if (frame) {
            window.cancelAnimationFrame(frame);
            frame = 0;
        }

        layers.forEach((layer) => {
            layer.style.transform = '';
        });
    });
}
