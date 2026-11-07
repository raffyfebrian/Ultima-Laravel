<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Page Header --}}
            <header class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-600">Administration</p>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Pesanan</h1>
                    <p class="mt-1 text-sm text-slate-500">Pantau semua pesanan dari tamu kamar.</p>
                </div>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    &larr; Kembali
                </a>
            </header>

            @if (session('success'))
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white">&check;</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pesanan</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $totalOrders }}</p>
                    <p class="mt-1 text-xs text-slate-500">Seluruh pesanan tercatat</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pesanan Hari Ini</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $todayOrders }}</p>
                    <p class="mt-1 text-xs text-slate-500">Masuk sejak 00:00</p>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Sedang Berjalan</p>
                    <p class="mt-2 text-2xl font-bold text-amber-900">{{ $activeOrders }}</p>
                    <p class="mt-1 text-xs text-amber-700">Belum selesai / dibatalkan</p>
                </div>
                <div class="rounded-2xl border border-teal-200 bg-teal-50/60 p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-700">Total Pendapatan</p>
                    <p class="mt-2 text-2xl font-bold text-teal-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-teal-700">Dari pesanan lunas</p>
                </div>
            </div>

            {{-- Filters --}}
            @php
                $statusLabels = [
                    'pending' => 'Menunggu',
                    'confirmed' => 'Dikonfirmasi',
                    'preparing' => 'Diproses',
                    'ready' => 'Siap Disajikan',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ];
                $paymentLabels = [
                    'unpaid' => 'Belum Dibayar',
                    'paid' => 'Lunas',
                    'refunded' => 'Dikembalikan',
                ];
            @endphp
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="GET" class="grid items-end gap-4 md:grid-cols-4">
                    <div>
                        <label for="q" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Cari No. Pesanan</label>
                        <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="NG-2026..."
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label for="status" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Status Pesanan</label>
                        <select id="status" name="status"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                            <option value="">Semua status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>
                                    {{ $statusLabels[$status] ?? ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="payment_status" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Status Pembayaran</label>
                        <select id="payment_status" name="payment_status"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                            <option value="">Semua pembayaran</option>
                            @foreach ($paymentStatuses as $ps)
                                <option value="{{ $ps }}" @selected(request('payment_status') === $ps)>
                                    {{ $paymentLabels[$ps] ?? ucfirst($ps) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                            Filter
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Reset
                        </a>
                    </div>
                </form>
            </section>

            {{-- Orders Table --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="font-bold text-slate-900">Daftar Pesanan</h2>
                    <p class="mt-1 text-sm text-slate-500">Total {{ $orders->total() }} pesanan.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">No. Pesanan</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Kamar</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Pembayaran</th>
                                <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Total</th>
                                <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Waktu</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($orders as $order)
                                @php
                                    $orderBadge = match ($order->order_status) {
                                        'completed' => 'bg-teal-100 text-teal-700 border-teal-200',
                                        'preparing' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'ready' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'confirmed' => 'bg-sky-100 text-sky-700 border-sky-200',
                                        'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                    $paymentBadge = match ($order->payment_status) {
                                        'paid' => 'bg-teal-100 text-teal-700 border-teal-200',
                                        'refunded' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        default => 'bg-orange-100 text-orange-700 border-orange-200',
                                    };
                                @endphp
                                <tr class="group transition hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="block">
                                            <p class="font-medium text-slate-900 group-hover:text-teal-600">{{ $order->order_number }}</p>
                                            <p class="font-mono text-xs text-slate-400">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $order->room?->room_number ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $orderBadge }}">
                                            {{ $order->order_status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $paymentBadge }}">
                                            {{ $order->payment_status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium tabular-nums text-slate-900">
                                        Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-500">
                                        {{ $order->placed_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="inline-flex items-center gap-1.5 rounded-lg border border-teal-100 bg-teal-50 px-3 py-2 text-xs font-semibold text-teal-600 transition hover:bg-teal-100">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                            </svg>
                                            <p class="font-medium text-slate-900">Belum ada pesanan</p>
                                            <p class="text-sm text-slate-500">Data pesanan akan tampil di sini setelah ada pesanan masuk.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4">
                        <p class="text-sm text-slate-500">
                            Menampilkan {{ $orders->firstItem() }}&ndash;{{ $orders->lastItem() }} dari {{ $orders->total() }} data
                        </p>
                        {{ $orders->links() }}
                    </div>
                @endif
            </section>

        </div>
    </main>
</x-layouts::app>
