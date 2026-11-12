import './bootstrap';

/**
 * Animasi dashboard Ngofee.
 *
 * Prinsip:
 * - Hanya memakai transform/opacity untuk animasi.
 * - Count-up berjalan sekali saat data pertama kali tampil, lalu bertransisi
 *   dari nilai lama ke nilai baru (tidak mengulang dari nol).
 * - Menghormati prefers-reduced-motion.
 */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const numberFormatter = new Intl.NumberFormat('id-ID');

const countupCache = new Map();
const orderRowState = new Map();

let orderRowsReady = false;
let scanScheduled = false;
let writingCountup = false;

function setCountupText(element, text) {
    writingCountup = true;
    element.textContent = text;

    // MutationObserver berjalan sebagai microtask sebelum reset ini,
    // sehingga tulisan hasil animasi sendiri tidak memicu scan ulang.
    queueMicrotask(() => {
        writingCountup = false;
    });
}

function formatCountup(value, format) {
    const rounded = Math.round(value);
    const formatted = numberFormatter.format(rounded);

    return format === 'currency' ? `Rp ${formatted}` : formatted;
}

function countupKeyFor(element) {
    if (element.dataset.countupKey) {
        return element.dataset.countupKey;
    }

    if (!element.dataset.generatedCountupKey) {
        element.dataset.generatedCountupKey = `countup-${countupCache.size}-${Math.random()
            .toString(36)
            .slice(2, 8)}`;
    }

    return element.dataset.generatedCountupKey;
}

function animateCountup(element) {
    const target = Number(element.dataset.countup ?? 0);
    const format = element.dataset.countupFormat ?? 'number';
    const key = countupKeyFor(element);
    const start = countupCache.has(key) ? countupCache.get(key) : 0;

    countupCache.set(key, target);

    if (reduceMotion.matches || start === target) {
        setCountupText(element, formatCountup(target, format));
        return;
    }

    const duration = Number(element.dataset.countupDuration ?? 700);
    const startedAt = performance.now();
    const easeOut = (progress) => 1 - Math.pow(1 - progress, 3);

    const frame = (now) => {
        const progress = Math.min(1, (now - startedAt) / duration);
        const value = start + (target - start) * easeOut(progress);

        setCountupText(element, formatCountup(value, format));

        if (progress < 1) {
            requestAnimationFrame(frame);
        }
    };

    requestAnimationFrame(frame);
}

function scanCountups(root = document) {
    root.querySelectorAll('[data-countup]').forEach((element) => {
        animateCountup(element);
    });
}

function orderSignature(row) {
    return [
        row.dataset.orderStatus ?? '',
        row.dataset.orderPayment ?? '',
        row.dataset.orderTotal ?? '',
    ].join('|');
}

function flashOrderRow(row) {
    row.classList.remove('order-updated');

    // Memaksa reflow agar animasi bisa dijalankan ulang pada baris yang sama.
    void row.offsetWidth;
    row.classList.add('order-updated');

    window.clearTimeout(row._ngofeeHighlightTimer);
    row._ngofeeHighlightTimer = window.setTimeout(() => {
        row.classList.remove('order-updated');
    }, 1700);
}

function scanOrderRows() {
    const rows = document.querySelectorAll('[data-order-row]');

    if (!rows.length) {
        return;
    }

    const current = new Map();

    rows.forEach((row) => {
        const key = row.dataset.orderRow;
        const signature = orderSignature(row);
        const previous = orderRowState.get(key);

        current.set(key, signature);

        if (orderRowsReady && previous !== signature) {
            flashOrderRow(row);
        }
    });

    orderRowState.clear();
    current.forEach((signature, key) => orderRowState.set(key, signature));

    orderRowsReady = true;
}

const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)');

function initWorkspaceParallax(root = document) {
    if (!finePointer.matches || reduceMotion.matches) {
        return;
    }

    root.querySelectorAll('[data-workspace-scene]').forEach((scene) => {
        if (scene.dataset.parallaxReady === 'true') {
            return;
        }

        const layers = scene.querySelectorAll('[data-depth]');

        if (!layers.length) {
            return;
        }

        scene.dataset.parallaxReady = 'true';

        scene.addEventListener(
            'pointermove',
            (event) => {
                const rect = scene.getBoundingClientRect();
                const pointerX = (event.clientX - rect.left) / rect.width - 0.5;
                const pointerY = (event.clientY - rect.top) / rect.height - 0.5;

                layers.forEach((layer) => {
                    const depth = Number.parseFloat(layer.dataset.depth ?? '0');
                    const shiftX = (-pointerX * depth * 10).toFixed(2);
                    const shiftY = (-pointerY * depth * 8).toFixed(2);
                    const tiltX = (-pointerY * depth * 1.6).toFixed(2);
                    const tiltY = (pointerX * depth * 2).toFixed(2);

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
    });
}

function scheduleScan() {
    if (scanScheduled) {
        return;
    }

    scanScheduled = true;

    requestAnimationFrame(() => {
        scanScheduled = false;
        scanCountups();
        scanOrderRows();
        initWorkspaceParallax();
    });
}

function startObserver() {
    const observer = new MutationObserver(() => {
        // Abaikan tulisan hasil animasi count-up sendiri.
        if (writingCountup) {
            return;
        }

        scheduleScan();
    });

    observer.observe(document.body, {
        childList: true,
        characterData: true,
        subtree: true,
    });
}

function refreshMotion() {
    scanCountups();
    scanOrderRows();
    initWorkspaceParallax();
}

function boot() {
    refreshMotion();
    startObserver();

    document.addEventListener('livewire:navigated', () => {
        orderRowsReady = false;
        orderRowState.clear();
        refreshMotion();
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
    boot();
}

window.NgofeeMotion = { refresh: refreshMotion };
