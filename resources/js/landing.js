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
   Header, progres scroll, section aktif, dan tombol kembali ke atas
   -------------------------------------------------------------------------- */
const header = doc.querySelector('[data-site-header]');
const progressBar = doc.querySelector('[data-scroll-progress]');
const toTop = doc.querySelector('[data-to-top]');
const navLinks = Array.from(doc.querySelectorAll('.primary-nav a[href^="#"]'));

const navSections = navLinks
    .map((link) => {
        const id = link.getAttribute('href')?.slice(1);
        const section = id ? doc.getElementById(id) : null;

        return section ? { link, section } : null;
    })
    .filter(Boolean);

function clamp(value, min, max) {
    return Math.min(Math.max(value, min), max);
}

function syncScrollUi() {
    const scrollTop = window.scrollY || doc.documentElement.scrollTop;
    const maxScroll = Math.max(doc.documentElement.scrollHeight - window.innerHeight, 1);

    header?.classList.toggle('is-scrolled', scrollTop > 16);
    toTop?.classList.toggle('is-visible', scrollTop > 640);

    if (progressBar) {
        progressBar.style.transform = `scaleX(${clamp(scrollTop / maxScroll, 0, 1).toFixed(4)})`;
    }
}

function syncActiveNav() {
    if (navSections.length === 0) {
        return;
    }

    const offset = (header?.offsetHeight ?? 76) + 56;
    const probe = (window.scrollY || doc.documentElement.scrollTop) + offset;
    let current = navSections[0].link;

    navSections.forEach(({ link, section }) => {
        if (section.offsetTop <= probe) {
            current = link;
        }
    });

    navLinks.forEach((link) => {
        const isActive = link === current;
        link.classList.toggle('is-active', isActive);

        if (isActive) {
            link.setAttribute('aria-current', 'true');
        } else {
            link.removeAttribute('aria-current');
        }
    });
}

function onScroll() {
    syncScrollUi();
    syncActiveNav();
}

syncScrollUi();
syncActiveNav();
window.addEventListener('scroll', onScroll, { passive: true });
window.addEventListener('resize', onScroll, { passive: true });
window.addEventListener('pageshow', onScroll);
window.addEventListener('load', onScroll);

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
        if (window.innerWidth > 960 && primaryNav.classList.contains('is-open')) {
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
   Transisi gambar saat selesai dimuat
   -------------------------------------------------------------------------- */
const previewImages = doc.querySelectorAll('.preview-frame img');

previewImages.forEach((image) => {
    const markLoaded = () => image.classList.add('is-loaded');

    if (image.complete && image.naturalWidth > 0) {
        markLoaded();
    } else {
        image.addEventListener('load', markLoaded, { once: true });
        image.addEventListener('error', markLoaded, { once: true });
    }
});

/* --------------------------------------------------------------------------
   Pratinjau produk: tab interaktif
   -------------------------------------------------------------------------- */
const previewTabs = Array.from(doc.querySelectorAll('[data-preview-tab]'));

function activatePreviewTab(nextTab, moveFocus = false) {
    previewTabs.forEach((tab) => {
        const isNext = tab === nextTab;
        const panel = doc.getElementById(tab.getAttribute('aria-controls') ?? '');

        tab.setAttribute('aria-selected', String(isNext));
        tab.tabIndex = isNext ? 0 : -1;

        if (panel) {
            panel.hidden = !isNext;
        }
    });

    if (moveFocus) {
        nextTab.focus();
    }
}

if (previewTabs.length > 0) {
    previewTabs.forEach((tab, index) => {
        tab.addEventListener('click', () => activatePreviewTab(tab));

        tab.addEventListener('keydown', (event) => {
            let nextIndex = null;

            if (event.key === 'ArrowDown' || event.key === 'ArrowRight') {
                nextIndex = (index + 1) % previewTabs.length;
            } else if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') {
                nextIndex = (index - 1 + previewTabs.length) % previewTabs.length;
            } else if (event.key === 'Home') {
                nextIndex = 0;
            } else if (event.key === 'End') {
                nextIndex = previewTabs.length - 1;
            }

            if (nextIndex === null) {
                return;
            }

            event.preventDefault();
            activatePreviewTab(previewTabs[nextIndex], true);
        });
    });
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
   Parallax ringan + tilt pada ilustrasi hero
   -------------------------------------------------------------------------- */
const scene = doc.querySelector('[data-parallax-scene]');
const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)');

if (scene && finePointer.matches && !reduceMotion.matches) {
    const layers = scene.querySelectorAll('.scene-layer');

    scene.addEventListener(
        'pointermove',
        (event) => {
            const rect = scene.getBoundingClientRect();
            const pointerX = (event.clientX - rect.left) / rect.width - 0.5;
            const pointerY = (event.clientY - rect.top) / rect.height - 0.5;

            layers.forEach((layer) => {
                const depth = Number.parseFloat(layer.dataset.depth ?? '0');
                const shiftX = (-pointerX * depth * 18).toFixed(2);
                const shiftY = (-pointerY * depth * 14).toFixed(2);
                const tiltX = (-pointerY * depth * 3).toFixed(2);
                const tiltY = (pointerX * depth * 4).toFixed(2);

                layer.style.transform = `translate3d(${shiftX}px, ${shiftY}px, 0) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
            });
        },
        { passive: true },
    );

    scene.addEventListener('pointerleave', () => {
        layers.forEach((layer) => {
            layer.style.transform = '';
        });
    });
}
