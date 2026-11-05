<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-3xl space-y-8">

            {{-- Page Header --}}
            <header class="mb-4">
                <div class="flex items-center justify-between gap-4 mb-3">
                    <div>
                        <a
                            href="{{ route('admin.orders.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-medium text-teal-600 hover:text-teal-700 transition"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7v18"></path></svg>
                            Kembali ke Daftar Pesanan
                        </a>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                        Detail Pesanan
                    </h1>
                </div>
                <p class="text-sm text-slate-500">
                    Nomor: <span class="font-mono font-medium text-slate-900">{{ $order->order_number }}</span>
                </p>
            </header>

            {{-- Order Info Cards --}}
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kamar</p>
                    <p class="mt-1 text-lg font-medium text-slate-900">{{ $order->room?->name ?? '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Pesan</p>
                    <p class="mt-1 text-lg font-medium text-slate-900">{{ $order->placed_at?->format('d M Y, H:i') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Pesanan</p>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border
                            {{ $order->order_status === 'completed' ? 'border-teal-200 bg-teal-50 text-teal-700' : ( $order->order_status === 'preparing' ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-slate-200 bg-slate-50 text-slate-700') }}"
                    >
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pembayaran</p>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border
                            {{ $order->payment_status === 'paid' ? 'border-teal-200 bg-teal-50 text-teal-700' : 'border-orange-200 bg-orange-50 text-orange-700' }}"
                    >
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            {{-- Status Update Form --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-bold text-slate-900 mb-4">Ubah Status Pesanan</h2>
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <select
                        name="order_status"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                    >
                        @foreach ($statuses as $status)
                            <option
                                value="{{ $status }}"
                                @selected($order->order_status === $status)
                            >
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white hover:bg-teal-700 transition"
                    >
                        Simpan Perubahan
                    </button>
                </form>
            </section>

            {{-- Order Items --}}
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="font-bold text-slate-900">Item Pesanan</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $order->items->count() }} item dalam pesanan ini.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider text-right">Qty</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider text-right">Harga Satuan</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider text-right">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($order->items as $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        {{ $item->product_name }}
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-600">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-600">
                                        Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-900 font-medium">
                                        Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                    </td>
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
                                <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-900">
                                    Total
                                </td>
                                <td class="px-6 py-4 text-right tabular-nums text-slate-900 font-bold">
                                    Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>

        </div>
    </main>
</x-layouts::app>
