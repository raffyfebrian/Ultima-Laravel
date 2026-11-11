<!DOCTYPE html>
<html lang="id" class="no-js">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? 'Ngoffe ID — Sistem Pesanan & Operasional Kafe' }}</title>
        <meta name="description" content="{{ $description ?? 'Ngoffe ID menyatukan pesanan QR dari meja dan kamar, antrean dapur, pembayaran kasir, stok bahan, dan laporan penjualan dalam satu alur kerja.' }}">
        <meta name="theme-color" content="#061F18">
        <link rel="canonical" href="{{ url()->current() }}">

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Ngoffe ID">
        <meta property="og:locale" content="id_ID">
        <meta property="og:title" content="{{ $title ?? 'Ngoffe ID — Sistem Pesanan & Operasional Kafe' }}">
        <meta property="og:description" content="{{ $description ?? 'Satu sistem untuk pesanan QR, dapur, kasir, stok, dan laporan penjualan.' }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ asset('images/landing/kitchen.webp') }}">
        <meta property="og:image:width" content="1600">
        <meta property="og:image:height" content="1264">
        <meta property="og:image:alt" content="Tampilan Manajemen Dapur pada aplikasi Ngoffe ID">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $title ?? 'Ngoffe ID — Sistem Pesanan & Operasional Kafe' }}">
        <meta name="twitter:description" content="{{ $description ?? 'Satu sistem untuk pesanan QR, dapur, kasir, stok, dan laporan penjualan.' }}">
        <meta name="twitter:image" content="{{ asset('images/landing/kitchen.webp') }}">

        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

        <script>document.documentElement.classList.replace('no-js', 'js');</script>

        @vite(['resources/css/landing.css', 'resources/js/landing.js'])

        <noscript>
            <style>
                .reveal { opacity: 1 !important; transform: none !important; }
                .nav-toggle { display: none !important; }
                @media (max-width: 960px) {
                    .primary-nav {
                        position: static !important;
                        opacity: 1 !important;
                        visibility: visible !important;
                        transform: none !important;
                        background: transparent !important;
                        box-shadow: none !important;
                        padding: 0 !important;
                    }
                }
            </style>
        </noscript>
    </head>
    <body>
        <a class="skip-link" href="#konten">Lewati ke konten utama</a>

        {{ $slot }}
    </body>
</html>
