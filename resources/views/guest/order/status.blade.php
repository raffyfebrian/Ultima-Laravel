@extends('layouts.guest')

@section('title', 'Status Pesanan '.$order->order_number.' — Ngoffe ID')
@section('description', 'Pantau status pesanan kamar Ngoffe ID secara aman melalui tautan pesanan.')

@section('content')
    <header class="guest-topbar">
        <div class="guest-topbar-inner">
            <a class="guest-brand" href="{{ route('home') }}" aria-label="Ngoffe ID — Beranda">
                Ngoffe<span>.</span>
            </a>

            <a class="btn-ghost" href="{{ route('home') }}">Beranda</a>
        </div>
    </header>

    <main class="status-wrap" id="konten-pelanggan">
        <section
            class="status-card"
            aria-labelledby="status-title"
            data-order-status
            data-status-url="{{ route('guest.order.status-data', $order->public_token) }}"
        >
            <p class="success-kicker">Status pesanan</p>
            <h1 class="success-title" id="status-title">{{ $order->order_status_label }}</h1>
            <p class="success-lead">
                Pesanan untuk Kamar {{ $order->room->room_number }}. Halaman ini hanya dapat
                dibuka melalui tautan pesanan yang Anda terima.
            </p>

            <div class="order-ticket">
                <p class="order-ticket-label">Nomor pesanan</p>
                <p class="order-number">{{ $order->order_number }}</p>
                <p class="order-room">Kamar {{ $order->room->room_number }}</p>

                <span
                    class="status-pill"
                    data-status-pill
                    data-tone="{{ $order->order_status === 'cancelled' ? 'danger' : ($order->order_status === 'completed' ? 'done' : ($order->payment_status === 'unpaid' ? 'warn' : 'done')) }}"
                >
                    <i aria-hidden="true"></i>
                    <span data-status-label aria-live="polite">{{ $order->order_status_label }}</span>
                </span>
            </div>

            <ol class="status-track" aria-label="Tahapan pesanan">
                @foreach ($order->status_steps as $index => $step)
                    <li class="status-step" data-step-index="{{ $index }}" data-state="{{ $step['state'] }}">
                        <span class="status-dot" aria-hidden="true">
                            @if ($step['state'] === 'done')
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m5 12.5 4.5 4.5L19 7"/>
                                </svg>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </span>
                        <b>{{ $step['label'] }}</b>
                        <small>{{ $step['hint'] }}</small>
                    </li>
                @endforeach
            </ol>

            <p class="status-meta">
                <span class="status-live-dot" aria-hidden="true"></span>
                <span data-status-updated>Diperbarui otomatis setiap 20 detik</span>
            </p>

            @if ($order->order_status === 'cancelled')
                <p class="cart-error" role="alert" style="margin-top: 18px;">
                    Pesanan ini dibatalkan. Silakan hubungi kasir jika Anda membutuhkan bantuan.
                </p>
            @endif

            <div class="status-section">
                <h2>Rincian pesanan</h2>
                <ul class="order-lines">
                    @foreach ($order->items as $item)
                        <li class="order-line">
                            <div>
                                <p>{{ $item->product_name }}</p>
                                <small>
                                    {{ $item->quantity }} ×
                                    Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                                </small>
                                @if ($item->notes)
                                    <p class="order-line-note">Catatan: {{ $item->notes }}</p>
                                @endif
                            </div>
                            <b>Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</b>
                        </li>
                    @endforeach
                </ul>

                <div class="order-total">
                    <span>Total pesanan</span>
                    <b>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</b>
                </div>
            </div>

            <div class="order-pay-card">
                <div class="order-pay-item">
                    <span>Metode pembayaran</span>
                    <b>{{ $order->payment_method_label }}</b>
                </div>
                <div class="order-pay-item">
                    <span>Status pembayaran</span>
                    <b>{{ $order->payment_status_label }}</b>
                </div>

                @if ($order->payment_status !== 'paid')
                    <p class="order-pay-note">
                        Pembayaran belum diverifikasi. Status akan diperbarui setelah kasir
                        mengonfirmasi pembayaran.
                    </p>
                @endif
            </div>

            <div class="success-actions">
                @if (session('guest_room_token'))
                    <a class="btn-primary" href="{{ route('guest.order.show', ['token' => session('guest_room_token')]) }}">
                        Pesan Menu Lain
                    </a>
                @else
                    <a class="btn-primary" href="{{ route('guest.order.entry') }}">
                        Pesan Menu Lain
                    </a>
                @endif
                <a class="btn-ghost" href="{{ route('home') }}">Kembali ke Beranda</a>
            </div>
        </section>
    </main>
@endsection
