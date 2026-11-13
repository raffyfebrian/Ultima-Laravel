@extends('layouts.guest')

@section('title', 'Pesanan Berhasil — Ngoffe ID')
@section('description', 'Pesanan kamar berhasil dikirim ke kasir dan dapur Ngoffe ID.')

@section('content')
    <header class="guest-topbar">
        <div class="guest-topbar-inner">
            <a class="guest-brand" href="{{ route('home') }}" aria-label="Ngoffe ID — Beranda">
                Ngoffe<span>.</span>
            </a>

            <a class="btn-ghost" href="{{ route('guest.order.show', ['token' => session('guest_room_token')]) }}">
                Kembali ke Menu
            </a>
        </div>
    </header>

    <main class="success-wrap" id="konten-pelanggan">
        <section class="success-card" aria-labelledby="success-title">
            <div class="confetti" aria-hidden="true">
                <span></span><span></span><span></span>
                <span></span><span></span><span></span>
            </div>

            <div class="success-check" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m5 12.5 4.5 4.5L19 7"/>
                </svg>
            </div>

            <p class="success-kicker">Pesanan diterima</p>
            <h1 class="success-title" id="success-title">Pesanan berhasil dibuat</h1>
            <p class="success-lead">
                Pesananmu sudah masuk ke kasir dan dapur. Simpan nomor pesanan ini untuk
                membantu proses konfirmasi.
            </p>

            <div class="order-ticket">
                <p class="order-ticket-label">Nomor pesanan</p>
                <p class="order-number">{{ $order->order_number }}</p>
                <p class="order-room">Kamar {{ $order->room->room_number }}</p>
                <span class="status-pill" data-tone="warn">
                    <i aria-hidden="true"></i>
                    {{ $order->order_status_label }}
                </span>
            </div>

            @php
                $statusStepCount = count($order->status_steps);
                $statusStepIndex = $order->status_step_index;
                $statusProgress = $statusStepIndex >= 0 && $statusStepCount > 1
                    ? round(($statusStepIndex / ($statusStepCount - 1)) * 100)
                    : 0;
            @endphp

            <ol
                class="status-track {{ $order->order_status === 'cancelled' ? 'is-cancelled' : '' }}"
                style="--status-progress: {{ $statusProgress }}%"
                aria-label="Tahapan pesanan"
            >
                <li class="status-rail" aria-hidden="true">
                    <span class="status-rail-fill"></span>
                </li>

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

                @if ($order->payment_method === 'qris')
                    <p class="order-pay-note">
                        Pembayaran QRIS belum dikonfirmasi. Ikuti instruksi pembayaran dari kasir
                        setelah tersedia.
                    </p>
                @else
                    <p class="order-pay-note">
                        Silakan lakukan pembayaran tunai kepada kasir. Pesanan menunggu validasi
                        pembayaran.
                    </p>
                @endif
            </div>

            <div class="success-actions">
                <a class="btn-primary" href="{{ route('guest.order.status', $order->public_token) }}">
                    Pantau Status Pesanan
                </a>
                <a class="btn-ghost" href="{{ route('guest.order.show', ['token' => session('guest_room_token')]) }}">
                    Tambah Pesanan Lain
                </a>
            </div>
        </section>
    </main>
@endsection
