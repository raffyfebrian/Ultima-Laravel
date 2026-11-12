<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="theme-color" content="#F6F9F6">

        <title>@yield('title', 'Pesan Menu — Ngoffe ID')</title>
        <meta name="description" content="@yield('description', 'Pilih makanan dan minuman favoritmu, lalu kirim pesanan langsung dari kamar. Tanpa daftar, tanpa login.')">
        <meta name="robots" content="@yield('robots', 'noindex, nofollow')">

        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/guest.css', 'resources/js/guest.js'])

        @livewireStyles
    </head>
    <body class="guest-body">
        <a class="guest-skip" href="#konten-pelanggan">Lewati ke konten utama</a>

        @yield('content')

        @livewireScripts
        @stack('scripts')
    </body>
</html>
