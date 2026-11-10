<x-layouts::landing
    title="Ngoffe ID — Sistem Pesanan & Operasional Kafe"
    description="Ngoffe ID menyatukan pesanan QR dari meja dan kamar, antrean dapur, pembayaran kasir, stok bahan, dan laporan penjualan dalam satu alur kerja."
>
    @php
        $dashboardUrl = auth()->check() ? route('dashboard') : route('login');
        $loginUrl = route('login');
    @endphp

    <header class="site-header" data-site-header>
        <div class="shell nav-shell">
            <a class="brand" href="#beranda" aria-label="Ngoffe ID — kembali ke beranda">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 8h11v5a4 4 0 0 1-4 4H9a4 4 0 0 1-4-4V8Z"/>
                        <path d="M16 9h1.5a2.5 2.5 0 0 1 0 5H16"/>
                        <path d="M4 21h13"/>
                    </svg>
                </span>
                <span class="brand-name">Ngoffe<span class="brand-dot">.</span></span>
            </a>

            <nav class="primary-nav" id="nav-menu" aria-label="Navigasi utama">
                <a href="#beranda">Beranda</a>
                <a href="#fitur">Fitur</a>
                <a href="#tentang">Tentang</a>
                <a href="#cara-kerja">Cara Kerja</a>
                <a href="#faq">FAQ</a>
                <a class="nav-panel-login" href="{{ $dashboardUrl }}">
                    {{ auth()->check() ? 'Dashboard' : 'Masuk' }}
                </a>
            </nav>

            <div class="nav-actions">
                <a class="btn btn-primary btn-sm nav-cta" href="{{ $dashboardUrl }}">
                    Mulai Sekarang
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>

                <button
                    type="button"
                    class="nav-toggle"
                    data-nav-toggle
                    aria-controls="nav-menu"
                    aria-expanded="false"
                    aria-label="Buka menu navigasi"
                >
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <main id="konten">
        {{-- Hero --}}
        <section class="hero" id="beranda" data-parallax-scene>
            <div class="hero-backdrop" aria-hidden="true">
                <span class="hero-glow hero-glow--one"></span>
                <span class="hero-glow hero-glow--two"></span>
                <span class="hero-mesh"></span>
                <span class="hero-noise"></span>
            </div>

            <div class="shell hero-grid">
                <div class="hero-copy">
                    <p class="eyebrow eyebrow--light reveal">
                        <span class="eyebrow-dot" aria-hidden="true"></span>
                        Sistem operasional kafe &amp; resto
                    </p>

                    <h1 class="hero-title reveal" style="--reveal-delay: 70ms">
                        Pesanan rapi.<br>
                        Dapur tenang.<br>
                        <span class="hero-accent">Layanan lebih cepat.</span>
                    </h1>

                    <p class="hero-lead reveal" style="--reveal-delay: 140ms">
                        Ngoffe ID menyatukan pesanan QR dari meja dan kamar, antrean dapur,
                        pembayaran kasir, stok bahan, sampai laporan penjualan — dalam satu
                        alur yang mudah diikuti.
                    </p>

                    <div class="hero-actions reveal" style="--reveal-delay: 210ms">
                        <a class="btn btn-lime" href="{{ $dashboardUrl }}">
                            Masuk ke Dashboard
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                            </svg>
                        </a>
                        <a class="btn btn-ghost" href="#fitur">Lihat Fitur</a>
                    </div>

                    <ul class="hero-points reveal" style="--reveal-delay: 280ms">
                        <li>
                            <svg class="icon icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            Tanpa unduh aplikasi
                        </li>
                        <li>
                            <svg class="icon icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            Ramah ponsel &amp; tablet
                        </li>
                        <li>
                            <svg class="icon icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            Akses sesuai peran staf
                        </li>
                    </ul>
                </div>

                {{-- Ilustrasi 3D (dekoratif, bukan tangkapan layar) --}}
                <div class="hero-visual" aria-hidden="true">
                    <div class="scene">
                        <div class="scene-aura"></div>
                        <div class="scene-ring"></div>

                        <div class="scene-layer scene-layer--orb" data-depth="0.55">
                            <div class="orb float-orb"></div>
                        </div>

                        <div class="scene-layer scene-layer--phone" data-depth="0.3">
                            <div class="phone float-phone">
                                <div class="phone-shell">
                                    <div class="phone-screen">
                                        <div class="phone-bar">
                                            <span class="phone-brand">Ngoffe<span>.</span></span>
                                            <span class="phone-live"><i></i>Live</span>
                                        </div>
                                        <p class="phone-kicker">Kamar 101</p>
                                        <p class="phone-heading">Menu malam ini</p>

                                        <div class="phone-item">
                                            <span class="phone-thumb phone-thumb--one"></span>
                                            <span class="phone-lines"><i></i><i></i></span>
                                            <b>38k</b>
                                        </div>
                                        <div class="phone-item">
                                            <span class="phone-thumb phone-thumb--two"></span>
                                            <span class="phone-lines"><i></i><i></i></span>
                                            <b>42k</b>
                                        </div>
                                        <div class="phone-item">
                                            <span class="phone-thumb phone-thumb--three"></span>
                                            <span class="phone-lines"><i></i><i></i></span>
                                            <b>24k</b>
                                        </div>

                                        <div class="phone-total">
                                            <span>Total</span>
                                            <b>Rp 104.000</b>
                                        </div>
                                        <div class="phone-cta">Kirim pesanan</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="scene-layer scene-layer--order" data-depth="1.05">
                            <div class="chip chip--order float-chip-a">
                                <span class="chip-icon chip-icon--green">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v8"/><path d="M8 12h8"/><circle cx="12" cy="12" r="9"/></svg>
                                </span>
                                <span class="chip-text"><small>Pesanan baru</small><b>Kamar 101</b></span>
                            </div>
                        </div>

                        <div class="scene-layer scene-layer--ready" data-depth="1.45">
                            <div class="chip chip--ready float-chip-b">
                                <span class="chip-icon chip-icon--lime">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 13 4 4L19 7"/></svg>
                                </span>
                                <span class="chip-text"><small>Status dapur</small><b>Siap disajikan</b></span>
                            </div>
                        </div>

                        <div class="scene-layer scene-layer--note" data-depth="0.85">
                            <div class="chip chip--note float-chip-c">
                                <span class="chip-icon chip-icon--mint">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V9"/><path d="M10 19V5"/><path d="M16 19v-7"/><path d="M22 19H2"/></svg>
                                </span>
                                <span class="chip-text"><small>Hari ini</small><b>Penjualan tercatat</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Trust bar --}}
        <section class="trust" aria-label="Keunggulan singkat">
            <div class="shell">
                <ul class="trust-list reveal">
                    <li>
                        <span class="trust-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20h1"/></svg>
                        </span>
                        <span><b>Pesan dari meja &amp; kamar</b><small>Tamu cukup memindai QR</small></span>
                    </li>
                    <li>
                        <span class="trust-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4"/><path d="M5.6 5.6 8.4 8.4"/><path d="M3 12h4"/><path d="M5.6 18.4 8.4 15.6"/><path d="M12 21v-4"/><path d="M18.4 18.4 15.6 15.6"/><path d="M21 12h-4"/><path d="M18.4 5.6 15.6 8.4"/><circle cx="12" cy="12" r="3"/></svg>
                        </span>
                        <span><b>Status jelas di dapur</b><small>Antrean yang sama untuk tim</small></span>
                    </li>
                    <li>
                        <span class="trust-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="6" width="19" height="12" rx="2.5"/><path d="M2.5 10h19"/><path d="M6 14.5h3"/></svg>
                        </span>
                        <span><b>Kasir tahu status bayar</b><small>Lunas, belum, atau refund</small></span>
                    </li>
                    <li>
                        <span class="trust-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V9"/><path d="M10 19V5"/><path d="M16 19v-7"/><path d="M22 19H2"/></svg>
                        </span>
                        <span><b>Stok &amp; penjualan tercatat</b><small>Siap ditinjau kapan saja</small></span>
                    </li>
                </ul>
            </div>
        </section>

        {{-- Fitur --}}
        <section class="section" id="fitur">
            <div class="shell">
                <header class="section-head reveal">
                    <p class="eyebrow eyebrow--dark"><span class="eyebrow-dot" aria-hidden="true"></span>Fitur</p>
                    <h2>Satu sistem untuk seluruh alur layanan</h2>
                    <p class="section-lead">
                        Dari tamu memilih menu sampai laporan penjualan dibuat, setiap peran
                        bekerja dari data yang sama.
                    </p>
                </header>

                <div class="feature-grid">
                    <article class="feature-card reveal">
                        <span class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20h1"/></svg>
                        </span>
                        <h3>Pesanan QR per meja &amp; kamar</h3>
                        <p>Tamu memindai QR, memilih menu, lalu pesanan langsung masuk ke sistem tanpa perlu mencatat ulang.</p>
                    </article>

                    <article class="feature-card reveal">
                        <span class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4"/><path d="M5.6 5.6 8.4 8.4"/><path d="M3 12h4"/><path d="M5.6 18.4 8.4 15.6"/><path d="M12 21v-4"/><path d="M18.4 18.4 15.6 15.6"/><path d="M21 12h-4"/><path d="M18.4 5.6 15.6 8.4"/><circle cx="12" cy="12" r="3"/></svg>
                        </span>
                        <h3>Antrean dapur</h3>
                        <p>Pesanan bergerak dari menunggu, diproses, siap disajikan, hingga selesai. Tim dapur melihat antrean yang sama.</p>
                    </article>

                    <article class="feature-card reveal">
                        <span class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="6" width="19" height="12" rx="2.5"/><path d="M2.5 10h19"/><path d="M6 14.5h3"/></svg>
                        </span>
                        <h3>Kasir &amp; pembayaran</h3>
                        <p>Konfirmasi pembayaran tunai, QRIS, transfer, atau kartu. Status lunas dan refund tercatat rapi.</p>
                    </article>

                    <article class="feature-card reveal">
                        <span class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15.5a2.5 2.5 0 0 1-2.5 2.5H6.5A2.5 2.5 0 0 1 4 18.5v-13Z"/><path d="M4 18.5A2.5 2.5 0 0 1 6.5 16H20"/><path d="M8 7h8"/></svg>
                        </span>
                        <h3>Menu &amp; kategori</h3>
                        <p>Atur produk, harga, kategori, dan ketersediaan menu tanpa perlu mengubah kode aplikasi.</p>
                    </article>

                    <article class="feature-card reveal">
                        <span class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z"/><path d="m4 7.5 8 4.5 8-4.5"/><path d="M12 12v9"/></svg>
                        </span>
                        <h3>Inventori bahan</h3>
                        <p>Catat stok masuk, batch, dan pergerakan bahan agar pemakaian harian lebih terkendali.</p>
                    </article>

                    <article class="feature-card reveal">
                        <span class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V9"/><path d="M10 19V5"/><path d="M16 19v-7"/><path d="M22 19H2"/></svg>
                        </span>
                        <h3>Laporan penjualan</h3>
                        <p>Rekap pendapatan, jumlah transaksi, dan menu terjual per periode, siap diekspor ke CSV.</p>
                    </article>
                </div>
            </div>
        </section>

        {{-- Tentang --}}
        <section class="section section--about" id="tentang">
            <div class="shell about-grid">
                <div class="about-copy reveal">
                    <p class="eyebrow eyebrow--dark"><span class="eyebrow-dot" aria-hidden="true"></span>Tentang Ngoffe ID</p>
                    <h2>Satu alur kerja, bukan tumpukan catatan terpisah.</h2>
                    <p>
                        Saat pesanan dicatat di kertas, chat, dan aplikasi berbeda, dapur dan
                        kasir bekerja dengan informasi yang tidak sama. Ngoffe ID menyatukan
                        semuanya: pesanan tamu masuk ke satu tempat, statusnya terlihat oleh
                        tim yang berhak, dan hasilnya bisa ditinjau di laporan.
                    </p>

                    <ul class="about-list">
                        <li>
                            <svg class="icon icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            Pesanan tamu langsung tercatat
                        </li>
                        <li>
                            <svg class="icon icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            Status dapur dan kasir sinkron
                        </li>
                        <li>
                            <svg class="icon icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            Riwayat transaksi tersimpan
                        </li>
                        <li>
                            <svg class="icon icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            Akses dibatasi sesuai peran staf
                        </li>
                    </ul>
                </div>

                <div class="flow-visual reveal" role="group" aria-label="Alur pesanan Ngoffe ID">
                    <div class="flow-card">
                        <div class="flow-top">
                            <span class="flow-label">Alur pesanan</span>
                            <span class="flow-badge"><i></i>Tersinkron</span>
                        </div>

                        <ol class="flow-list">
                            <li>
                                <span class="flow-step">01</span>
                                <span class="flow-body"><b>Tamu memindai QR</b><small>Menu terbuka di browser</small></span>
                                <span class="flow-status">Masuk</span>
                            </li>
                            <li>
                                <span class="flow-step">02</span>
                                <span class="flow-body"><b>Dapur memproses</b><small>Status diperbarui sesuai progres</small></span>
                                <span class="flow-status flow-status--amber">Diproses</span>
                            </li>
                            <li>
                                <span class="flow-step">03</span>
                                <span class="flow-body"><b>Kasir menyelesaikan</b><small>Pembayaran dikonfirmasi</small></span>
                                <span class="flow-status flow-status--green">Lunas</span>
                            </li>
                        </ol>

                        <div class="flow-foot">
                            <span class="flow-foot-dot"></span>
                            Pesanan, dapur, dan kasir membaca data yang sama
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Cara kerja --}}
        <section class="section section--steps" id="cara-kerja">
            <div class="shell">
                <header class="section-head section-head--center reveal">
                    <p class="eyebrow eyebrow--dark"><span class="eyebrow-dot" aria-hidden="true"></span>Cara kerja</p>
                    <h2>Empat langkah, satu alur</h2>
                    <p class="section-lead">Tidak ada proses baru yang rumit — hanya alur layanan yang dibuat jelas.</p>
                </header>

                <ol class="steps">
                    <li class="step reveal">
                        <span class="step-number">01</span>
                        <h3>Pindai QR</h3>
                        <p>Tamu membuka menu dari QR di meja atau kamar. Tidak perlu memasang aplikasi.</p>
                    </li>
                    <li class="step reveal">
                        <span class="step-number">02</span>
                        <h3>Pesanan masuk</h3>
                        <p>Pilihan menu dan total langsung tercatat di dashboard operasional.</p>
                    </li>
                    <li class="step reveal">
                        <span class="step-number">03</span>
                        <h3>Dapur memproses</h3>
                        <p>Tim dapur memperbarui status pesanan sesuai progres sebenarnya.</p>
                    </li>
                    <li class="step reveal">
                        <span class="step-number">04</span>
                        <h3>Bayar &amp; selesai</h3>
                        <p>Kasir mengonfirmasi pembayaran, lalu transaksi masuk ke laporan.</p>
                    </li>
                </ol>
            </div>
        </section>

        {{-- Pratinjau produk --}}
        <section class="section section--preview" id="preview">
            <div class="shell">
                <header class="section-head reveal">
                    <p class="eyebrow eyebrow--dark"><span class="eyebrow-dot" aria-hidden="true"></span>Pratinjau</p>
                    <h2>Tampilan yang benar-benar dipakai tim</h2>
                    <p class="section-lead">
                        Tangkapan layar diambil langsung dari aplikasi Ngoffe ID yang berjalan —
                        bukan ilustrasi fitur yang belum tersedia.
                    </p>
                </header>

                <div class="preview-grid">
                    <figure class="preview-frame preview-frame--main reveal">
                        <div class="frame-bar" aria-hidden="true">
                            <span class="frame-dot"></span>
                            <span class="frame-dot"></span>
                            <span class="frame-dot"></span>
                            <span class="frame-title">Ngoffe ID · Manajemen Dapur</span>
                        </div>
                        <img
                            src="{{ asset('images/landing/kitchen.webp') }}"
                            alt="Tampilan Manajemen Dapur Ngoffe ID dengan ringkasan pesanan baru, sedang diproses, dan siap disajikan."
                            width="1600"
                            height="1264"
                            loading="lazy"
                            decoding="async"
                        >
                        <figcaption>Antrean dapur dengan status pesanan yang jelas.</figcaption>
                    </figure>

                    <figure class="preview-frame preview-frame--side reveal">
                        <div class="frame-bar" aria-hidden="true">
                            <span class="frame-dot"></span>
                            <span class="frame-dot"></span>
                            <span class="frame-dot"></span>
                            <span class="frame-title">Ngoffe ID · Menu tamu</span>
                        </div>
                        <img
                            src="{{ asset('images/landing/guest-menu.webp') }}"
                            alt="Halaman menu tamu Ngoffe ID untuk Kamar 101 berisi daftar menu yang dapat dipesan."
                            width="1600"
                            height="1264"
                            loading="lazy"
                            decoding="async"
                        >
                        <figcaption>Menu tamu yang dibuka dari QR meja atau kamar.</figcaption>
                    </figure>
                </div>
            </div>
        </section>

        {{-- Use case (pengganti testimoni karena belum ada data asli) --}}
        <section class="section section--usecase" id="use-case">
            <div class="shell">
                <header class="section-head section-head--center reveal">
                    <p class="eyebrow eyebrow--dark"><span class="eyebrow-dot" aria-hidden="true"></span>Cocok untuk</p>
                    <h2>Dirancang untuk layanan yang sibuk</h2>
                    <p class="section-lead">
                        Alur pesanan per meja dan per kamar dapat disesuaikan dengan jenis usaha Anda.
                    </p>
                </header>

                <div class="usecase-grid">
                    <article class="usecase-card reveal">
                        <span class="usecase-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9h12v6a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V9Z"/><path d="M16 10h2a2.5 2.5 0 0 1 0 5h-2"/><path d="M7 3v3M11 3v3M15 3v3"/></svg>
                        </span>
                        <h3>Kafe &amp; coffee shop</h3>
                        <p>Pesanan meja yang cepat dengan menu dan stok yang sering berubah.</p>
                    </article>

                    <article class="usecase-card reveal">
                        <span class="usecase-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 3v8a3 3 0 0 0 6 0V3"/><path d="M8 11v10"/><path d="M17 3c-1.5 1.5-2 3.5-2 6 0 2 .8 3 2 3s2-1 2-3c0-2.5-.5-4.5-2-6Z"/><path d="M17 12v9"/></svg>
                        </span>
                        <h3>Restoran &amp; rumah makan</h3>
                        <p>Alur dapur dan kasir yang tetap sinkron saat jam ramai.</p>
                    </article>

                    <article class="usecase-card reveal">
                        <span class="usecase-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 3l9 7"/><path d="M5 9v11h14V9"/><path d="M9 20v-6h6v6"/></svg>
                        </span>
                        <h3>Kedai dengan meja bernomor</h3>
                        <p>Satu QR untuk satu meja, sehingga pesanan tidak tertukar.</p>
                    </article>

                    <article class="usecase-card reveal">
                        <span class="usecase-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6"/><path d="M3 18h18"/><path d="M6 10V7a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v3"/><path d="M8 14h2"/></svg>
                        </span>
                        <h3>Penginapan dengan layanan kamar</h3>
                        <p>Tamu memesan dari kamar tanpa perlu menelepon resepsionis.</p>
                    </article>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="section section--faq" id="faq">
            <div class="shell faq-grid">
                <header class="section-head reveal">
                    <p class="eyebrow eyebrow--dark"><span class="eyebrow-dot" aria-hidden="true"></span>FAQ</p>
                    <h2>Pertanyaan yang sering muncul</h2>
                    <p class="section-lead">
                        Belum menemukan jawaban? Hubungi pengelola Ngoffe ID untuk informasi
                        akun dan akses.
                    </p>
                </header>

                @php
                    $faqs = [
                        [
                            'question' => 'Apa itu Ngoffe ID?',
                            'answer' => 'Ngoffe ID adalah sistem operasional berbasis web untuk mengelola pesanan, dapur, kasir, stok bahan, dan laporan penjualan dalam satu tempat.',
                        ],
                        [
                            'question' => 'Bagaimana tamu memesan?',
                            'answer' => 'Tamu memindai QR yang tertempel di meja atau kamar, lalu memilih menu dari halaman pesanan. Tidak perlu memasang aplikasi tambahan.',
                        ],
                        [
                            'question' => 'Apakah butuh perangkat khusus?',
                            'answer' => 'Tidak. Aplikasi berjalan di browser modern pada ponsel, tablet, maupun komputer.',
                        ],
                        [
                            'question' => 'Siapa saja yang bisa mengakses dashboard?',
                            'answer' => 'Akses dibagi per peran: superadmin, admin, kasir, dan dapur. Setiap peran hanya melihat menu yang relevan dengan tugasnya.',
                        ],
                        [
                            'question' => 'Bagaimana data pesanan disimpan?',
                            'answer' => 'Pesanan, pembayaran, dan pergerakan stok tersimpan di database aplikasi. Setiap peran staf hanya dapat membuka menu yang sesuai dengan tugasnya.',
                        ],
                        [
                            'question' => 'Apakah laporan bisa diunduh?',
                            'answer' => 'Bisa. Laporan penjualan dapat diekspor dalam format CSV untuk diolah lebih lanjut.',
                        ],
                        [
                            'question' => 'Bagaimana cara mulai memakai?',
                            'answer' => 'Masuk ke dashboard dengan akun staf yang diberikan pengelola, lalu atur produk, kategori, serta meja atau kamar yang digunakan.',
                        ],
                        [
                            'question' => 'Bagaimana cara menghubungi pengelola?',
                            'answer' => 'Kontak resmi pengelola akan dicantumkan pada bagian footer halaman ini. Untuk sementara, gunakan kanal komunikasi yang sudah Anda miliki dengan pengelola Ngoffe ID.',
                        ],
                    ];
                @endphp

                <div class="faq-list">
                    @foreach ($faqs as $index => $faq)
                        @php($number = $index + 1)
                        <div class="faq-item reveal">
                            <h3>
                                <button
                                    type="button"
                                    class="faq-button"
                                    id="faq-button-{{ $number }}"
                                    data-faq-button
                                    aria-expanded="false"
                                    aria-controls="faq-panel-{{ $number }}"
                                >
                                    {{ $faq['question'] }}
                                    <span class="faq-icon" aria-hidden="true"></span>
                                </button>
                            </h3>
                            <div class="faq-panel" id="faq-panel-{{ $number }}" role="region" aria-labelledby="faq-button-{{ $number }}">
                                <div class="faq-panel-inner">
                                    <p>{{ $faq['answer'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA penutup --}}
        <section class="final-cta">
            <div class="final-cta-bg" aria-hidden="true">
                <span class="final-glow final-glow--one"></span>
                <span class="final-glow final-glow--two"></span>
            </div>

            <div class="shell final-cta-inner reveal">
                <p class="eyebrow eyebrow--light"><span class="eyebrow-dot" aria-hidden="true"></span>Siap dipakai</p>
                <h2>Siap merapikan alur layanan?</h2>
                <p>
                    Masuk ke dashboard dan lihat bagaimana pesanan, dapur, dan kasir
                    bekerja dalam satu sistem.
                </p>

                <div class="hero-actions">
                    <a class="btn btn-lime" href="{{ $dashboardUrl }}">
                        Masuk ke Dashboard
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                        </svg>
                    </a>
                    <a class="btn btn-ghost" href="#cara-kerja">Lihat cara kerja</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="shell footer-grid">
            <div class="footer-brand">
                <a class="brand brand--footer" href="#beranda" aria-label="Ngoffe ID — kembali ke beranda">
                    <span class="brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 8h11v5a4 4 0 0 1-4 4H9a4 4 0 0 1-4-4V8Z"/>
                            <path d="M16 9h1.5a2.5 2.5 0 0 1 0 5H16"/>
                            <path d="M4 21h13"/>
                        </svg>
                    </span>
                    <span class="brand-name">Ngoffe<span class="brand-dot">.</span></span>
                </a>
                <p>Sistem pesanan dan operasional untuk kafe, restoran, dan penginapan.</p>
            </div>

            <nav class="footer-links" aria-label="Navigasi footer">
                <p class="footer-title">Navigasi</p>
                <a href="#beranda">Beranda</a>
                <a href="#fitur">Fitur</a>
                <a href="#tentang">Tentang</a>
                <a href="#cara-kerja">Cara Kerja</a>
                <a href="#faq">FAQ</a>
            </nav>

            <div class="footer-links">
                <p class="footer-title">Akses</p>
                <a href="{{ $loginUrl }}">{{ auth()->check() ? 'Dashboard' : 'Masuk staf' }}</a>
                <a href="{{ $dashboardUrl }}">Dashboard</a>
                <a href="https://github.com/raffyfebrian/Ultima-Laravel" target="_blank" rel="noopener noreferrer">Kode sumber</a>
            </div>

            <div class="footer-contact">
                <p class="footer-title">Kontak</p>
                <p>Hubungi pengelola Ngoffe ID untuk akun staf, akses, dan pertanyaan operasional.</p>
                {{-- TODO: tambahkan email atau WhatsApp resmi di sini. --}}
            </div>
        </div>

        <div class="shell footer-bottom">
            <p>&copy; {{ date('Y') }} Ngoffe ID. Seluruh hak dilindungi.</p>
            <p>Dibangun dengan Laravel.</p>
        </div>
    </footer>
</x-layouts::landing>
