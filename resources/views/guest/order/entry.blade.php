@extends('layouts.guest')

@section('title', 'Pesan dari Kamar — Ngoffe ID')
@section('description', 'Buka menu kamar Ngoffe ID untuk memilih makanan dan minuman, lalu kirim pesanan tanpa akun atau login staf.')

@section('content')
    <header class="guest-topbar">
        <div class="guest-topbar-inner">
            <a class="guest-brand" href="{{ route('home') }}" aria-label="Ngoffe ID — Beranda">
                Ngoffe<span>.</span>
            </a>

            <a class="btn-ghost" href="{{ route('home') }}">Kembali ke Beranda</a>
        </div>
    </header>

    <main class="entry-wrap" id="konten-pelanggan">
        <section class="entry-card" aria-labelledby="entry-title">
            <p class="guest-eyebrow">Pesan dari kamar</p>
            <h1 class="entry-title" id="entry-title">
                Langsung ke menu,<br>tanpa akun staf.
            </h1>
            <p class="entry-lead">
                Pindai QR yang terpasang di kamar untuk langsung membuka menu kamar Anda.
                Jika kamera tidak bisa memindai, masukkan nomor kamar di bawah ini.
            </p>

            <form method="POST" action="{{ route('guest.order.open') }}" class="entry-form">
                @csrf

                <label class="entry-label" for="room_number">Nomor kamar</label>
                <div class="entry-field">
                    <span class="entry-prefix" aria-hidden="true">#</span>
                    <input
                        type="text"
                        id="room_number"
                        name="room_number"
                        value="{{ old('room_number') }}"
                        inputmode="numeric"
                        autocomplete="off"
                        placeholder="Contoh: 101"
                        required
                        aria-describedby="room-number-help"
                        @error('room_number') aria-invalid="true" @enderror
                    >
                </div>

                <p class="entry-help" id="room-number-help">
                    Nomor kamar ada pada kartu kunci atau ditempel di pintu kamar.
                </p>

                @error('room_number')
                    <p class="entry-error" role="alert">{{ $message }}</p>
                @enderror

                <button type="submit" class="entry-submit">
                    Buka Menu Kamar
                    <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                    </svg>
                </button>
            </form>

            <div class="entry-divider"><span>atau</span></div>

            <div class="entry-scan">
                <span class="entry-scan-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                        <path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20h1"/>
                    </svg>
                </span>
                <div>
                    <b>Pindai QR di kamar</b>
                    <p>QR kamar langsung membuka menu dengan nomor kamar yang benar—tanpa perlu mengetik.</p>
                </div>
            </div>

            <p class="entry-note">
                Halaman ini untuk tamu yang memesan dari kamar. Tidak ada login, daftar akun,
                atau akses ke dashboard staf.
            </p>
        </section>
    </main>
@endsection
