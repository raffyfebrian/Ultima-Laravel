@extends('layouts.guest')

@section('title', $heading.' — Ngoffe ID')
@section('description', 'Tautan QR kamar Ngoffe ID tidak valid atau sudah diganti.')

@section('content')
    <header class="guest-topbar">
        <div class="guest-topbar-inner">
            <a class="guest-brand" href="{{ route('home') }}" aria-label="Ngoffe ID — Beranda">
                Ngoffe<span>.</span>
            </a>

            <a class="btn-ghost" href="{{ route('home') }}">Beranda</a>
        </div>
    </header>

    <main class="invalid-wrap" id="konten-pelanggan">
        <section class="invalid-card" aria-labelledby="invalid-title">
            <div class="invalid-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    <path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20h1"/>
                    <path d="m4 20 16-16" stroke-width="2.2"/>
                </svg>
            </div>

            <h1 id="invalid-title">{{ $heading }}</h1>
            <p>{{ $message }}</p>

            <div class="invalid-actions">
                <a class="btn-primary" href="{{ route('home') }}">{{ $backLabel }}</a>
                <a class="btn-ghost" href="{{ route('guest.order.entry') }}">Buka Menu dengan Nomor Kamar</a>
            </div>

            <p class="invalid-hint">
                Pesanan tamu tidak memerlukan akun atau login staf. Jika QR di kamar bermasalah,
                resepsionis dapat membantu Anda membuka menu.
            </p>
        </section>
    </main>
@endsection
