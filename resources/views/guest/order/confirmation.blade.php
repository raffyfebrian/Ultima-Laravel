
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan | Ngoffe ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-cream text-charcoal antialiased">
    <main class="mx-auto max-w-2xl px-4 py-10 sm:py-16">
        <div class="rounded-3xl border border-black/5 bg-white p-6 shadow-sm sm:p-10">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-teal/10 text-3xl text-teal">
                ✓
            </div>

            <p class="mt-5 text-center text-xs font-semibold uppercase tracking-[0.2em] text-teal">
                Ngoffe ID
            </p>

            <h1 class="mt-2 text-center font-display text-3xl font-semibold">
                Pesanan berhasil dibuat
            </h1>

            <p class="mt-3 text-center text-sm leading-6 text-gray-500">
                Simpan nomor pesanan ini untuk membantu proses konfirmasi oleh kasir.
            </p>

            <div class="mt-8 rounded-2xl bg-cream p-5 text-center">
                <p class="text-sm text-gray-500">Nomor pesanan</p>
                <p class="mt-2 break-all text-xl font-bold tracking-wide text-teal">
                    {{ $order->order_number }}
                </p>
                <p class="mt-3 text-sm text-gray-600">
                    Kamar {{ $order->room->room_number }}
                </p>
            </div>

            <div class="mt-8">
                <h2 class="font-display text-xl font-semibold">
                    Rincian pesanan
                </h2>

                <div class="mt-4 divide-y divide-black/5">
                    @foreach ($order->items as $item)
                        <div
                            wire:key="confirmation-item-{{ $item->id }}"
                            class="flex items-start justify-between gap-4 py-4"
                        >
                            <div>
                                <p class="font-medium">{{ $item->product_name }}</p>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $item->quantity }} ×
                                    Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                                </p>
                            </div>

                            <p class="shrink-0 text-sm font-semibold">
                                Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-2 flex items-center justify-between border-t border-black/10 pt-5">
                    <span class="font-semibold">Total</span>
                    <span class="text-xl font-bold text-teal">
                        Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="mt-8 rounded-2xl border border-black/5 p-5">
                <p class="text-sm text-gray-500">Metode pembayaran</p>
                <p class="mt-1 font-semibold">
                    {{ $order->payment_method === 'qris' ? 'QRIS' : 'Tunai' }}
                </p>

                <p class="mt-4 text-sm text-gray-500">Status pembayaran</p>
                <p class="mt-1 font-semibold text-amber-700">
                    {{ $order->payment_status_label }}
                </p>

                <p class="mt-4 text-sm text-gray-500">Status pesanan</p>
                <p class="mt-1 font-semibold">
                    {{ $order->order_status_label }}
                </p>

                @if ($order->payment_method === 'qris')
                    <p class="mt-4 text-sm leading-6 text-gray-500">
                        Pembayaran QRIS belum dikonfirmasi. Ikuti instruksi pembayaran dari kasir setelah tersedia.
                    </p>
                @else
                    <p class="mt-4 text-sm leading-6 text-gray-500">
                        Silakan lakukan pembayaran tunai kepada kasir. Pesanan menunggu validasi.
                    </p>
                @endif
            </div>

            <a
                href="{{ route('guest.order.show', ['token' => session('guest_room_token')]) }}"
                class="mt-8 block w-full rounded-xl bg-teal px-5 py-3 text-center font-semibold text-white transition hover:bg-teal/90"
            >
                Kembali ke menu
            </a>
        </div>
    </main>

    @livewireScripts
</body>
</html>
