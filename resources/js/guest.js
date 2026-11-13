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
   Gambar produk: skeleton + blur-up
   -------------------------------------------------------------------------- */

function markProductImageReady(img) {
    const media = img.closest('.product-media');

    if (!media) {
        return;
    }

    media.classList.remove('is-loading');

    if (img.naturalWidth === 0) {
        media.classList.add('is-broken');
    }
}

function initProductImages() {
    document.querySelectorAll('[data-product-image]').forEach((img) => {
        if (img.dataset.imageReady === 'true') {
            return;
        }

        img.dataset.imageReady = 'true';

        const media = img.closest('.product-media');

        if (!img.complete) {
            media?.classList.add('is-loading');
            img.addEventListener('load', () => markProductImageReady(img), { once: true });
            img.addEventListener('error', () => markProductImageReady(img), { once: true });

            return;
        }

        markProductImageReady(img);
    });
}

/* --------------------------------------------------------------------------
   Fly-to-cart: umpan balik visual saat item ditambahkan
   -------------------------------------------------------------------------- */

function initFlyToCart() {
    if (reduceMotion.matches || typeof Element.prototype.animate !== 'function') {
        return;
    }

    if (document.documentElement.dataset.flyToCartReady === 'true') {
        return;
    }

    document.documentElement.dataset.flyToCartReady = 'true';

    document.addEventListener(
        'click',
        (event) => {
            if (reduceMotion.matches) {
                return;
            }

            const button = event.target.closest('.product-add');

            if (!button || button.disabled || button.getAttribute('aria-disabled') === 'true') {
                return;
            }

            const card = button.closest('[data-product-card]');
            const target = document.querySelector('.guest-cart-button');

            if (!card || !target) {
                return;
            }

            const media = card.querySelector('.product-media');
            const image = media?.querySelector('img');
            const sourceRect = (media ?? card).getBoundingClientRect();
            const targetRect = target.getBoundingClientRect();
            const size = 46;

            if (sourceRect.width === 0 || targetRect.width === 0) {
                return;
            }

            const fly = document.createElement('span');
            fly.className = 'fly-to-cart';
            fly.setAttribute('aria-hidden', 'true');
            fly.style.width = `${size}px`;
            fly.style.height = `${size}px`;
            fly.style.left = `${sourceRect.left + sourceRect.width / 2 - size / 2}px`;
            fly.style.top = `${sourceRect.top + sourceRect.height / 2 - size / 2}px`;

            if (image && image.complete && image.naturalWidth > 0) {
                fly.style.backgroundImage = `url("${image.currentSrc || image.src}")`;
            } else {
                fly.textContent = (card.dataset.name ?? 'N').trim().charAt(0).toUpperCase();
            }

            document.body.appendChild(fly);

            const dx =
                targetRect.left + targetRect.width / 2 - (sourceRect.left + sourceRect.width / 2);
            const dy =
                targetRect.top + targetRect.height / 2 - (sourceRect.top + sourceRect.height / 2);
            const midX = dx * 0.5;
            const midY = dy * 0.5 - Math.min(120, Math.abs(dx) * 0.25 + 40);

            const animation = fly.animate(
                [
                    { transform: 'translate(0, 0) scale(1)', opacity: 1 },
                    {
                        transform: `translate(${midX}px, ${midY}px) scale(0.76)`,
                        opacity: 0.95,
                        offset: 0.55,
                    },
                    { transform: `translate(${dx}px, ${dy}px) scale(0.18)`, opacity: 0.2 },
                ],
                {
                    duration: 520,
                    easing: 'cubic-bezier(0.22, 0.61, 0.36, 1)',
                },
            );

            const cleanup = () => {
                fly.remove();
                target.classList.remove('is-receiving');
            };

            animation.addEventListener('finish', cleanup, { once: true });
            animation.addEventListener('cancel', cleanup, { once: true });

            target.classList.remove('is-receiving');
            void target.offsetWidth;
            target.classList.add('is-receiving');
        },
        { passive: true },
    );
}

/* --------------------------------------------------------------------------
   Kuantitas keranjang: optimistic update + rollback
   -------------------------------------------------------------------------- */

const cartQuantityState = new Map();
const cartRollbackTimers = new WeakMap();
let cartErrorTimer = null;

function formatRupiah(value) {
    return `Rp ${Math.round(value).toLocaleString('id-ID')}`;
}

function readQuantity(row) {
    const value = Number.parseInt(
        row.dataset.displayQty ?? row.dataset.confirmedQty ?? '0',
        10,
    );

    return Number.isFinite(value) ? Math.max(0, value) : 0;
}

function updateCartTotals() {
    let count = 0;
    let total = 0;

    document.querySelectorAll('[data-cart-item]').forEach((row) => {
        const quantity = readQuantity(row);
        const price = Number(row.dataset.unitPrice ?? 0) || 0;

        count += quantity;
        total += quantity * price;
    });

    const dockCount = document.querySelector('[data-dock-count]');
    const dockTotal = document.querySelector('[data-dock-total]');
    const cartTotal = document.querySelector('[data-cart-total]');

    if (dockCount) {
        dockCount.textContent = String(count);
    }

    if (dockTotal) {
        dockTotal.textContent = formatRupiah(total);
    }

    if (cartTotal) {
        cartTotal.textContent = formatRupiah(total);
    }

    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count } }));
}

function animateClass(element, className) {
    if (!element) {
        return;
    }

    element.classList.remove(className);
    void element.offsetWidth;
    element.classList.add(className);
}

function paintCartRow(row, quantity, direction) {
    const value = row.querySelector('[data-qty-value]');
    const subtotal = row.querySelector('[data-item-subtotal]');
    const price = Number(row.dataset.unitPrice ?? 0) || 0;
    const safeQuantity = Math.max(0, Math.min(99, quantity));

    row.dataset.displayQty = String(safeQuantity);
    row.classList.toggle('is-removing', safeQuantity === 0);

    if (value) {
        value.textContent = String(safeQuantity);
        animateClass(value, direction === 'up' ? 'is-rolling-up' : 'is-rolling-down');
    }

    if (subtotal) {
        subtotal.textContent = formatRupiah(price * safeQuantity);
        animateClass(subtotal, 'is-bumping');
    }

    if (safeQuantity === 0) {
        row.classList.remove('is-increasing', 'is-decreasing');
    } else {
        animateClass(row, direction === 'up' ? 'is-increasing' : 'is-decreasing');
    }

    const announcer = document.querySelector('[data-cart-announcer]');

    if (announcer) {
        const name = row.querySelector('.cart-item-name')?.textContent?.trim() ?? 'Item';

        announcer.textContent =
            safeQuantity === 0
                ? `${name} dihapus dari keranjang`
                : `${name}: ${safeQuantity} item`;
    }

    updateCartTotals();
}

function showCartClientError(message) {
    const error = document.querySelector('[data-cart-client-error]');

    if (!error) {
        return;
    }

    error.textContent = message;
    error.hidden = false;

    window.clearTimeout(cartErrorTimer);
    cartErrorTimer = window.setTimeout(() => {
        error.hidden = true;
        error.textContent = '';
    }, 4000);
}

function clearCartClientError() {
    const error = document.querySelector('[data-cart-client-error]');

    if (!error) {
        return;
    }

    window.clearTimeout(cartErrorTimer);
    error.hidden = true;
    error.textContent = '';
}

function findCartRow(productId) {
    return document.querySelector(`[data-cart-item][data-product-id="${productId}"]`);
}

function getCartWire(element) {
    const root = element.closest('[wire\\:id]');

    if (!root || !window.Livewire?.find) {
        return null;
    }

    return window.Livewire.find(root.getAttribute('wire:id'));
}

function rollbackCartQuantity(productId, message, showMessage = true) {
    const state = cartQuantityState.get(productId);
    const row = findCartRow(productId);

    if (state) {
        window.clearTimeout(state.timer);
        state.timer = null;
        state.inFlight = false;
    }

    if (!row) {
        return;
    }

    const confirmed = Math.max(
        0,
        Number.parseInt(row.dataset.confirmedQty ?? '1', 10) || 0,
    );
    const current = readQuantity(row);

    paintCartRow(row, confirmed, current > confirmed ? 'down' : 'up');
    row.classList.remove('is-removing');

    window.clearTimeout(cartRollbackTimers.get(row));
    row.classList.remove('is-rolling-back');
    void row.offsetWidth;
    row.classList.add('is-rolling-back');
    cartRollbackTimers.set(
        row,
        window.setTimeout(() => row.classList.remove('is-rolling-back'), 480),
    );

    if (showMessage) {
        showCartClientError(message);
    }

    if (state) {
        state.target = confirmed;
    }
}

function sendCartQuantity(productId, state) {
    state.timer = null;

    if (!state.wire) {
        rollbackCartQuantity(productId, 'Koneksi tidak tersedia. Coba lagi.');

        return;
    }

    const sentTarget = state.target;
    state.inFlight = true;

    Promise.resolve(state.wire.call('setQuantity', productId, sentTarget))
        .then(() => {
            state.inFlight = false;
            clearCartClientError();

            window.requestAnimationFrame(() => {
                window.requestAnimationFrame(() => {
                    const row = findCartRow(productId);

                    if (!row) {
                        cartQuantityState.delete(productId);
                        updateCartTotals();

                        return;
                    }

                    if (state.target !== sentTarget) {
                        // Ada klik baru selama request berjalan: pertahankan angka optimistis.
                        paintCartRow(
                            row,
                            state.target,
                            state.target > sentTarget ? 'up' : 'down',
                        );

                        return;
                    }

                    const serverQuantity = Math.max(
                        0,
                        Number.parseInt(
                            row.querySelector('[data-qty-value]')?.textContent ?? sentTarget,
                            10,
                        ) || 0,
                    );

                    row.dataset.confirmedQty = String(serverQuantity);
                    row.dataset.displayQty = String(serverQuantity);
                    row.classList.toggle('is-removing', serverQuantity === 0);
                    updateCartTotals();
                });
            });
        })
        .catch(() => {
            state.inFlight = false;
            rollbackCartQuantity(
                productId,
                'Jumlah belum tersimpan. Periksa koneksi lalu coba lagi.',
            );
        });
}

function queueCartQuantity(button, row, target) {
    const productId = Number(row.dataset.productId);
    const state = cartQuantityState.get(productId) ?? {
        wire: getCartWire(button),
        target,
        timer: null,
        inFlight: false,
    };

    state.wire = state.wire ?? getCartWire(button);
    state.target = target;
    state.row = row;

    window.clearTimeout(state.timer);
    state.timer = window.setTimeout(() => sendCartQuantity(productId, state), 220);

    cartQuantityState.set(productId, state);
}

function registerCartRequestFailure() {
    if (
        !window.Livewire?.hook ||
        document.documentElement.dataset.cartCommitHook === 'true'
    ) {
        return;
    }

    document.documentElement.dataset.cartCommitHook = 'true';

    window.Livewire.hook('commit', ({ component, fail }) => {
        fail(() => {
            cartQuantityState.forEach((state, productId) => {
                if (state.inFlight && state.wire?.$id === component.id) {
                    rollbackCartQuantity(
                        productId,
                        'Koneksi terputus. Jumlah belum tersimpan.',
                    );
                }
            });
        });
    });
}

function initCartQuantity() {
    registerCartRequestFailure();

    if (document.documentElement.dataset.cartQuantityReady === 'true') {
        return;
    }

    document.documentElement.dataset.cartQuantityReady = 'true';

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-qty-action]');

        if (!button || button.disabled) {
            return;
        }

        const row = button.closest('[data-cart-item]');

        if (!row) {
            return;
        }

        const current = readQuantity(row);
        const step = button.dataset.qtyAction === 'increment' ? 1 : -1;
        const next = Math.max(0, Math.min(99, current + step));

        if (next === current) {
            return;
        }

        event.preventDefault();
        paintCartRow(row, next, step > 0 ? 'up' : 'down');
        queueCartQuantity(button, row, next);
    });

    window.addEventListener('cart-rejected', (event) => {
        const detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;
        const productId = Number(detail?.productId ?? detail?.product_id);

        if (!Number.isFinite(productId)) {
            return;
        }

        rollbackCartQuantity(productId, '', false);
    });
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

    const track = root.querySelector('.status-track');

    if (track) {
        const steps = Array.isArray(data.steps) ? data.steps : [];
        const total = Math.max(1, steps.length - 1);
        let index = Number(data.step_index);

        if (!Number.isFinite(index) || index < 0) {
            index = steps.findIndex((step) => step.state === 'current');
        }

        if (!Number.isFinite(index) || index < 0) {
            index = 0;
        }

        const percent = Math.max(0, Math.min(100, (index / total) * 100));

        track.style.setProperty('--status-progress', `${percent}%`);
        track.classList.toggle('is-cancelled', data.order_status === 'cancelled');
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
    initProductImages();
    initFlyToCart();
    initCartQuantity();
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
    initProductImages();
    initFlyToCart();
    initCartQuantity();
    initStatusPolling();
});

document.addEventListener('livewire:navigating', () => {
    cartQuantityState.forEach((state) => window.clearTimeout(state.timer));
    cartQuantityState.clear();
});
