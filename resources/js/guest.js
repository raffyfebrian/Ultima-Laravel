/**
 * Ngoffe ID — interaksi halaman pelanggan.
 *
 * Prinsip: ringan, tanpa dependency tambahan, dan menghormati
 * prefers-reduced-motion. Animasi hanya memakai transform/opacity.
 */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

function normalise(value) {
    return (value ?? '')
        .toString()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();
}

/* --------------------------------------------------------------------------
   Filter katalog (kategori + pencarian)
   -------------------------------------------------------------------------- */

function initCatalog() {
    document.querySelectorAll('[data-catalog]').forEach((catalog) => {
        if (catalog.dataset.catalogReady === 'true') {
            return;
        }

        const cards = Array.from(catalog.querySelectorAll('[data-product-card]'));
        const search = catalog.querySelector('[data-catalog-search]');
        const filters = Array.from(catalog.querySelectorAll('[data-category-filter]'));
        const empty = catalog.querySelector('[data-catalog-empty]');
        const count = catalog.querySelector('[data-catalog-count]');

        if (!cards.length) {
            return;
        }

        catalog.dataset.catalogReady = 'true';

        let activeCategory = 'all';

        const apply = () => {
            const query = normalise(search?.value ?? '');
            let visible = 0;

            cards.forEach((card) => {
                const matchesCategory =
                    activeCategory === 'all' || card.dataset.category === activeCategory;
                const matchesSearch =
                    query === '' || normalise(card.dataset.search).includes(query);
                const show = matchesCategory && matchesSearch;

                card.hidden = !show;

                if (show) {
                    visible += 1;
                }
            });

            if (count) {
                count.textContent = visible;
            }

            empty?.classList.toggle('is-visible', visible === 0);
        };

        search?.addEventListener('input', apply, { passive: true });

        filters.forEach((button) => {
            button.addEventListener('click', () => {
                activeCategory = button.dataset.categoryFilter ?? 'all';

                filters.forEach((item) => {
                    const isActive = item === button;
                    item.classList.toggle('is-active', isActive);
                    item.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });

                apply();
            });
        });

        apply();
    });
}

/* --------------------------------------------------------------------------
   Reveal saat masuk viewport (sekali per kunjungan)
   -------------------------------------------------------------------------- */

function initReveal() {
    const items = document.querySelectorAll('.guest-reveal:not(.is-visible)');

    if (!items.length) {
        return;
    }

    if (reduceMotion.matches || !('IntersectionObserver' in window)) {
        items.forEach((item) => item.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -8% 0px' },
    );

    items.forEach((item) => observer.observe(item));
}

/* --------------------------------------------------------------------------
   Badge keranjang
   -------------------------------------------------------------------------- */

function initCartBadge() {
    const badge = document.querySelector('[data-cart-badge]');

    if (!badge) {
        return;
    }

    const update = (value) => {
        const count = Math.max(0, Number.parseInt(value, 10) || 0);

        badge.textContent = count > 99 ? '99+' : String(count);
        badge.classList.toggle('is-visible', count > 0);
        badge.classList.remove('is-bumped');

        if (count > 0 && !reduceMotion.matches) {
            // Memaksa reflow agar animasi bounce bisa diputar ulang.
            void badge.offsetWidth;
            badge.classList.add('is-bumped');
        }
    };

    window.addEventListener('cart-updated', (event) => {
        const detail = event.detail;
        const count = Array.isArray(detail) ? detail[0] : detail?.count;

        update(count ?? 0);
    });

    update(badge.textContent);
}

/* --------------------------------------------------------------------------
   Polling status pesanan (maksimal sekali per 20 detik)
   -------------------------------------------------------------------------- */

function applyOrderStatus(root, data) {
    if (!data) {
        return;
    }

    const label = root.querySelector('[data-status-label]');
    const pill = root.querySelector('[data-status-pill]');
    const updated = root.querySelector('[data-status-updated]');

    if (label) {
        label.textContent = data.status_label ?? label.textContent;
    }

    if (pill) {
        const tone =
            data.order_status === 'cancelled'
                ? 'danger'
                : data.order_status === 'completed'
                  ? 'done'
                  : data.payment_status === 'unpaid'
                    ? 'warn'
                    : 'done';

        pill.dataset.tone = tone;
    }

    if (Array.isArray(data.steps)) {
        data.steps.forEach((step, index) => {
            const item = root.querySelector(`[data-step-index="${index}"]`);

            if (item) {
                item.dataset.state = step.state;
            }
        });
    }

    if (updated) {
        const time = new Date();

        updated.textContent = `Diperbarui ${time.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
        })}`;
    }
}

function initStatusPolling() {
    const root = document.querySelector('[data-order-status]');

    if (!root || root.dataset.pollReady === 'true') {
        return;
    }

    const url = root.dataset.statusUrl;

    if (!url) {
        return;
    }

    root.dataset.pollReady = 'true';

    let timer = null;
    let stopped = false;

    const schedule = () => {
        if (stopped) {
            return;
        }

        timer = window.setTimeout(poll, 20000);
    };

    const poll = async () => {
        window.clearTimeout(timer);

        if (document.hidden) {
            schedule();
            return;
        }

        try {
            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
                cache: 'no-store',
            });

            if (response.ok) {
                applyOrderStatus(root, await response.json());
            }
        } catch {
            // Koneksi terputus: biarkan status terakhir tetap tampil.
        }

        schedule();
    };

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            poll();
        }
    });

    schedule();
}

/* --------------------------------------------------------------------------
   Boot
   -------------------------------------------------------------------------- */

function bootGuest() {
    initCatalog();
    initReveal();
    initCartBadge();
    initStatusPolling();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootGuest, { once: true });
} else {
    bootGuest();
}

document.addEventListener('livewire:navigated', () => {
    initCatalog();
    initReveal();
    initStatusPolling();
});
