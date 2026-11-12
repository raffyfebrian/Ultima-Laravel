@extends('layouts.guest')

@section('title', 'Menu Kamar '.$room->room_number.' — Ngoffe ID')
@section('description', 'Pilih makanan dan minuman favoritmu untuk Kamar '.$room->room_number.'. Kirim pesanan langsung tanpa daftar atau login.')

@section('content')
    <header class="guest-topbar">
        <div class="guest-topbar-inner">
            <a class="guest-brand" href="{{ route('home') }}" aria-label="Ngoffe ID — Beranda">
                Ngoffe<span>.</span>
            </a>

            <div class="guest-topbar-meta">
                <span class="guest-room-chip">
                    <i aria-hidden="true"></i>
                    Kamar {{ $room->room_number }}
                    <small>· aktif</small>
                </span>

                <button
                    type="button"
                    class="guest-cart-button"
                    x-data
                    @click="$dispatch('open-cart')"
                    aria-label="Buka keranjang pesanan"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 8H6"/>
                        <circle cx="10" cy="20" r="1.4"/>
                        <circle cx="18" cy="20" r="1.4"/>
                    </svg>
                    <span class="guest-cart-badge" data-cart-badge aria-hidden="true">0</span>
                </button>
            </div>
        </div>
    </header>

    <main
        class="guest-main"
        id="konten-pelanggan"
        x-data="{
            sheetOpen: false,
            sheet: {},
            qty: 1,
            notes: '',
            lastFocus: null,
            openSheet(el) {
                const data = el.dataset;
                this.sheet = {
                    id: Number(data.id),
                    name: data.name,
                    description: data.description,
                    price: Number(data.price),
                    priceLabel: data.priceLabel,
                    category: data.categoryName,
                    image: data.image,
                    soldout: data.available !== '1',
                };
                this.qty = 1;
                this.notes = '';
                this.lastFocus = document.activeElement;
                this.sheetOpen = true;
                this.$nextTick(() => this.$refs.sheetClose?.focus());
            },
            closeSheet() {
                this.sheetOpen = false;
                this.$nextTick(() => this.lastFocus?.focus());
            },
            increment() {
                this.qty = Math.min(99, this.qty + 1);
            },
            decrement() {
                this.qty = Math.max(1, this.qty - 1);
            },
            get totalLabel() {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(this.sheet.price * this.qty);
            },
            addFromSheet() {
                if (this.sheet.soldout) {
                    return;
                }

                this.$dispatch('add-to-cart', {
                    productId: this.sheet.id,
                    quantity: this.qty,
                    notes: this.notes.trim(),
                });

                this.closeSheet();
            },
        }"
        @keydown.escape.window="sheetOpen && closeSheet()"
    >
        <section class="guest-hero" aria-labelledby="guest-title">
            <div>
                <p class="guest-eyebrow">Pesan untuk Kamar {{ $room->room_number }}</p>
                <h1 class="guest-title" id="guest-title">
                    Mau pesan <em>apa hari ini?</em>
                </h1>
                <p class="guest-lead">
                    Pilih menu favoritmu, kami siapkan pesananmu. Pesanan langsung masuk
                    ke kasir dan dapur—tanpa daftar atau login.
                </p>
                <p class="guest-hero-note">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3 4 7v5c0 4.4 3.4 7.6 8 9 4.6-1.4 8-4.6 8-9V7l-8-4Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    Kamar terverifikasi dari QR · tanpa akun pelanggan
                </p>
            </div>

            <div class="guest-hero-art" aria-hidden="true">
                <span class="guest-art-glow"></span>
                <div class="guest-art-cup">
                    <span class="cup-saucer"></span>
                    <span class="cup-steam cup-steam--one"></span>
                    <span class="cup-steam cup-steam--two"></span>
                    <span class="cup-steam cup-steam--three"></span>
                    <span class="cup-body"></span>
                    <span class="cup-handle"></span>
                </div>
                <div class="guest-art-ticket">
                    <small>Pesanan kamar</small>
                    <b>Kamar {{ $room->room_number }}</b>
                    <ul>
                        <li><span>2 × Kopi Susu</span><b>Rp 76.000</b></li>
                        <li><span>1 × Croissant</span><b>Rp 28.000</b></li>
                    </ul>
                    <div class="ticket-total">
                        <span>Total</span>
                        <span>Rp 104.000</span>
                    </div>
                </div>
            </div>
        </section>

        <section aria-labelledby="menu-heading">
            <div class="guest-toolbar" data-catalog>
                <div class="guest-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-3.2-3.2"/>
                    </svg>
                    <label class="visually-hidden" for="catalog-search">Cari menu</label>
                    <input
                        type="search"
                        id="catalog-search"
                        data-catalog-search
                        placeholder="Cari kopi, makanan, atau camilan…"
                        autocomplete="off"
                    >
                </div>

                <div class="guest-filters" role="group" aria-label="Filter kategori menu">
                    <button type="button" class="guest-filter is-active" data-category-filter="all" aria-pressed="true">
                        Semua
                    </button>
                    @foreach ($categories as $category)
                        <button
                            type="button"
                            class="guest-filter"
                            data-category-filter="{{ $category->id }}"
                            aria-pressed="false"
                        >
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>

                <p class="guest-result-count">
                    Menampilkan <b data-catalog-count>{{ $categories->sum(fn ($category) => $category->products->count()) }}</b> menu
                </p>

                <div class="guest-section-head">
                    <div>
                        <h2 class="guest-section-title" id="menu-heading">Menu hari ini</h2>
                        <p class="guest-section-sub">Ketuk foto menu untuk melihat detail dan menambah catatan.</p>
                    </div>
                </div>

                <div class="guest-grid">
                    @foreach ($categories as $category)
                        @foreach ($category->products as $product)
                            <article
                                class="product-card guest-reveal {{ $product->is_available ? '' : 'is-soldout' }}"
                                data-product-card
                                data-category="{{ $category->id }}"
                                data-category-name="{{ $category->name }}"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-description="{{ $product->description }}"
                                data-price="{{ (float) $product->price }}"
                                data-price-label="Rp {{ number_format((float) $product->price, 0, ',', '.') }}"
                                data-image="{{ $product->image_path ? \Illuminate\Support\Facades\Storage::url($product->image_path) : '' }}"
                                data-available="{{ $product->is_available ? '1' : '0' }}"
                                data-search="{{ \Illuminate\Support\Str::lower($product->name.' '.($product->description ?? '').' '.$category->name) }}"
                            >
                                <button
                                    type="button"
                                    class="product-media product-open"
                                    @click="openSheet($el.closest('[data-product-card]'))"
                                    aria-label="Lihat detail {{ $product->name }}"
                                >
                                    @if ($product->image_path)
                                        <img
                                            src="{{ \Illuminate\Support\Facades\Storage::url($product->image_path) }}"
                                            alt="{{ $product->name }}"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    @else
                                        <div class="product-placeholder" aria-hidden="true">Ngoffe.</div>
                                    @endif

                                    @unless ($product->is_available)
                                        <span class="product-badge product-badge--soldout">Habis</span>
                                    @endunless
                                </button>

                                <div class="product-body">
                                    <p class="product-category">{{ $category->name }}</p>
                                    <h3 class="product-name">{{ $product->name }}</h3>

                                    @if ($product->description)
                                        <p class="product-desc">{{ $product->description }}</p>
                                    @endif

                                    <div class="product-foot">
                                        <span class="product-price">
                                            Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                                        </span>

                                        @if ($product->is_available)
                                            <button
                                                type="button"
                                                class="product-add"
                                                x-data="{ added: false }"
                                                @click="
                                                    $dispatch('add-to-cart', { productId: {{ $product->id }}, quantity: 1, notes: '' });
                                                    added = true;
                                                    setTimeout(() => added = false, 1200);
                                                "
                                                aria-label="Tambah {{ $product->name }} ke keranjang"
                                            >
                                                <svg x-show="!added" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                                    <path d="M12 5v14M5 12h14"/>
                                                </svg>
                                                <svg x-show="added" x-cloak viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="m5 12 5 5L20 7"/>
                                                </svg>
                                                <span x-text="added ? 'Ditambahkan' : 'Tambah'">Tambah</span>
                                            </button>
                                        @else
                                            <button type="button" class="product-add" disabled>
                                                Habis
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    @endforeach
                </div>

                <div class="guest-empty" data-catalog-empty role="status">
                    <h3>Menu tidak ditemukan</h3>
                    <p>Coba kata kunci lain atau pilih kategori “Semua”.</p>
                </div>
            </div>
        </section>

        <footer class="guest-footer">
            <span>© {{ date('Y') }} Ngoffe ID · Pesanan kamar tanpa akun.</span>
            <a href="{{ route('home') }}">Kembali ke Beranda</a>
        </footer>

        {{-- Bottom sheet detail menu --}}
        <div
            x-show="sheetOpen"
            x-cloak
            class="cart-overlay"
            role="dialog"
            aria-modal="true"
            aria-labelledby="sheet-title"
            x-transition.opacity.duration.200ms
        >
            <button
                type="button"
                class="absolute inset-0 h-full w-full cursor-default border-0 bg-transparent"
                @click="closeSheet()"
                aria-label="Tutup detail menu"
                tabindex="-1"
            ></button>

            <section class="cart-panel" x-ref="sheetPanel">
                <div class="cart-panel-head">
                    <div>
                        <p class="cart-panel-kicker" x-text="sheet.category">Menu</p>
                        <h2 class="cart-panel-title" id="sheet-title" x-text="sheet.name">Detail menu</h2>
                    </div>
                    <button type="button" class="cart-close" x-ref="sheetClose" @click="closeSheet()" aria-label="Tutup detail menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <path d="M6 6l12 12M18 6 6 18"/>
                        </svg>
                    </button>
                </div>

                <div class="product-media" style="margin-top: 16px; border-radius: 16px;">
                    <template x-if="sheet.image">
                        <img :src="sheet.image" :alt="sheet.name" loading="lazy">
                    </template>
                    <template x-if="!sheet.image">
                        <div class="product-placeholder" aria-hidden="true">Ngoffe.</div>
                    </template>
                </div>

                <p class="product-desc" style="margin-top: 14px;" x-text="sheet.description"></p>
                <p class="product-price" style="margin-top: 8px; font-size: 1.1rem;" x-text="sheet.priceLabel"></p>

                <div class="cart-summary">
                    <p class="cart-pay-title">Jumlah</p>
                    <div class="qty-control" style="width: fit-content;">
                        <button type="button" @click="decrement()" aria-label="Kurangi jumlah">−</button>
                        <span x-text="qty">1</span>
                        <button type="button" @click="increment()" aria-label="Tambah jumlah">+</button>
                    </div>

                    <label class="cart-pay-title" for="sheet-notes" style="display: block; margin-top: 16px;">
                        Catatan khusus <span style="font-weight: 500; color: var(--ng-muted);">(opsional)</span>
                    </label>
                    <textarea
                        id="sheet-notes"
                        x-model="notes"
                        maxlength="160"
                        rows="2"
                        placeholder="Contoh: tanpa gula, extra shot, tidak pedas"
                        style="width: 100%; margin-top: 6px; padding: 12px 14px; border: 1px solid var(--ng-border); border-radius: 14px; background: var(--ng-surface); resize: vertical;"
                    ></textarea>

                    <p class="guest-result-count" style="margin-top: 10px;">
                        <span x-text="qty">1</span> item · <b x-text="totalLabel">Rp 0</b>
                    </p>

                    <button
                        type="button"
                        class="cart-submit"
                        @click="addFromSheet()"
                        :disabled="sheet.soldout"
                        x-text="sheet.soldout ? 'Menu sedang habis' : 'Tambahkan ke keranjang'"
                    >
                        Tambahkan ke keranjang
                    </button>
                </div>
            </section>
        </div>
    </main>

    <livewire:guest.cart />
@endsection
