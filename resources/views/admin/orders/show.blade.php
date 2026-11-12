<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-4xl space-y-8">

            {{-- Header --}}
            <header class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <a href="{{ route('admin.orders.index') }}"
                       class="inline-flex items-center gap-2 text-sm font-medium text-teal-600 transition hover:text-teal-700">
                        &larr; Kembali ke Daftar Pesanan
                    </a>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Detail Pesanan</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Nomor: <span class="font-mono font-medium text-slate-900">{{ $order->order_number }}</span>
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.orders.receipt', $order) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition hover:from-teal-600 hover:to-emerald-700">
                        Cetak Struk
                    </a>
                    <a href="{{ route('admin.cashier.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-teal-300 hover:bg-teal-50">
                        Buka Kasir
                    </a>
                </div>
            </header>

            @if (session('success'))
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white">&check;</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Info Cards --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kamar</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">{{ $order->room?->room_number ?? '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Waktu Pesan</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">{{ $order->placed_at?->format('d M Y, H:i') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status Pesanan</p>
                    <span class="mt-2 inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold
                        {{ $order->order_status === 'completed' ? 'border-teal-200 bg-teal-50 text-teal-700' : ($order->order_status === 'preparing' ? 'border-amber-200 bg-amber-50 text-amber-700' : ($order->order_status === 'cancelled' ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-slate-200 bg-slate-50 text-slate-700')) }}">
                        {{ $order->order_status_label }}
                    </span>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pembayaran</p>
                    <span class="mt-2 inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold
                        {{ $order->payment_status === 'paid' ? 'border-teal-200 bg-teal-50 text-teal-700' : ($order->payment_status === 'refunded' ? 'border-slate-200 bg-slate-100 text-slate-600' : 'border-orange-200 bg-orange-50 text-orange-700') }}">
                        {{ $order->payment_status_label }}
                    </span>
                    <p class="mt-2 text-xs text-slate-500">Metode: {{ $order->payment_method_label }}</p>
                </div>
            </div>

            {{-- Payment Action --}}
            @if ($order->payment_status === 'unpaid')
                <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-amber-200 bg-amber-50/70 p-6">
                    <div>
                        <h2 class="font-bold text-amber-900">Pembayaran belum dikonfirmasi</h2>
                        <p class="mt-1 text-sm text-amber-700">Konfirmasi setelah tamu membayar agar pesanan tercatat lunas.</p>
                    </div>
                    <form method="POST" action="{{ route('admin.cashier.confirm', $order) }}" class="flex items-center gap-2">
                        @csrf
                        <select name="payment_method" class="rounded-xl border border-amber-200 bg-white px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                            <option value="cash" @selected($order->payment_method === 'cash')>Tunai</option>
                            <option value="qris" @selected($order->payment_method === 'qris')>QRIS</option>
                            <option value="transfer" @selected($order->payment_method === 'transfer')>Transfer</option>
                            <option value="card" @selected($order->payment_method === 'card')>Kartu</option>
                        </select>
                        <button type="submit" class="rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition hover:from-teal-600 hover:to-emerald-700">
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                </section>
            @endif

            {{-- Status Update --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-bold text-slate-900">Ubah Status Pesanan</h2>
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="flex flex-wrap items-end gap-3">
                    @csrf
                    @method('PATCH')

                    <div class="min-w-52 flex-1">
                        <label for="order_status" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Status Baru</label>
                        <select id="order_status" name="order_status"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                            @php
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'confirmed' => 'Dikonfirmasi',
                                    'preparing' => 'Diproses',
                                    'ready' => 'Siap Disajikan',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ];
                            @endphp
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($order->order_status === $status)>
                                    {{ $statusLabels[$status] ?? ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="rounded-xl bg-teal-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                        Simpan Perubahan
                    </button>
                </form>
            </section>

            {{-- Items --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="font-bold text-slate-900">Item Pesanan</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $order->items->count() }} item dalam pesanan ini.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Produk</th>
                                <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Qty</th>
                                <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Harga Satuan</th>
                                <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($order->items as $item)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        {{ $item->product_name }}
                                        @if ($item->notes)
                                            <span class="mt-0.5 block text-xs font-normal text-amber-700">
                                                Catatan: {{ $item->notes }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-600">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-600">Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-medium tabular-nums text-slate-900">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <p class="text-sm text-slate-500">Tidak ada item dalam pesanan ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot class="bg-slate-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-900">Total</td>
                                <td class="px-6 py-4 text-right font-bold tabular-nums text-slate-900">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>

        </div>
    </main>
</x-layouts::app>
