<x-layouts::app>
    <main class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 px-6 py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Page Header --}}
            <header class="relative">
                <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-teal-400 to-emerald-500 opacity-20 blur"></div>
                <div class="relative rounded-3xl border border-white/80 bg-white/70 p-6 shadow-2xl shadow-teal-900/10 backdrop-blur-md">
                    <div class="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-teal-600">Kasir</p>
                            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Kasir &amp; Pembayaran</h1>
                            <p class="mt-1 text-sm text-slate-500">Kelola transaksi dan konfirmasi pembayaran pesanan.</p>
                        </div>
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:border-teal-200 hover:bg-teal-50 hover:shadow-xl">
                            &larr; Kembali
                        </a>
                    </div>
                </div>
            </header>

            @if (session('success'))
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white">&check;</span>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="flex items-center gap-3 rounded-2xl border border-sky-200 bg-sky-50 px-5 py-4 text-sm font-medium text-sky-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-500 text-white">i</span>
                    {{ session('info') }}
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-rose-500 text-white">!</span>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="group relative">
                    <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-emerald-400 to-teal-500 opacity-30 blur transition-opacity duration-300 group-hover:opacity-50"></div>
                    <div class="relative rounded-2xl border border-white/90 bg-white/80 p-6 shadow-2xl shadow-emerald-500/20 backdrop-blur-sm transition-all duration-300 group-hover:-translate-y-0.5 group-hover:shadow-emerald-500/40">
                        <div class="mb-3 flex items-center justify-between">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50">
                                <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Total Penjualan</span>
                        </div>
                        <p class="mb-1 text-3xl font-bold text-slate-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400">Pembayaran yang telah dikonfirmasi</p>
                    </div>
                </div>

                <div class="group relative">
                    <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-blue-400 to-indigo-500 opacity-30 blur transition-opacity duration-300 group-hover:opacity-50"></div>
                    <div class="relative rounded-2xl border border-white/90 bg-white/80 p-6 shadow-2xl shadow-blue-500/20 backdrop-blur-sm transition-all duration-300 group-hover:-translate-y-0.5 group-hover:shadow-blue-500/40">
                        <div class="mb-3 flex items-center justify-between">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600">Pendapatan Hari Ini</span>
                        </div>
                        <p class="mb-1 text-3xl font-bold text-slate-900">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400">{{ $completedToday }} transaksi lunas hari ini</p>
                    </div>
                </div>

                <div class="group relative">
                    <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-amber-400 to-orange-500 opacity-30 blur transition-opacity duration-300 group-hover:opacity-50"></div>
                    <div class="relative rounded-2xl border border-white/90 bg-white/80 p-6 shadow-2xl shadow-amber-500/20 backdrop-blur-sm transition-all duration-300 group-hover:-translate-y-0.5 group-hover:shadow-amber-500/40">
                        <div class="mb-3 flex items-center justify-between">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50">
                                <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-amber-600">Menunggu Pembayaran</span>
                        </div>
                        <p class="mb-1 text-3xl font-bold text-slate-900">{{ $pendingPayments }}</p>
                        <p class="text-xs text-slate-400">Nilai tagihan Rp {{ number_format($unpaidTotal, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="group relative">
                    <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-purple-400 to-pink-500 opacity-30 blur transition-opacity duration-300 group-hover:opacity-50"></div>
                    <div class="relative rounded-2xl border border-white/90 bg-white/80 p-6 shadow-2xl shadow-purple-500/20 backdrop-blur-sm transition-all duration-300 group-hover:-translate-y-0.5 group-hover:shadow-purple-500/40">
                        <div class="mb-3 flex items-center justify-between">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50">
                                <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-purple-600">Total Pesanan</span>
                        </div>
                        <p class="mb-1 text-3xl font-bold text-slate-900">{{ $orders->total() }}</p>
                        <p class="text-xs text-slate-400">Semua pesanan tercatat</p>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <section class="relative">
                <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-indigo-400 to-purple-500 opacity-20 blur"></div>
                <div class="relative rounded-2xl border border-white/90 bg-white/80 p-6 shadow-2xl shadow-indigo-900/10 backdrop-blur-md">
                    <form method="GET" class="grid items-end gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="q" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Cari Pesanan</label>
                            <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Contoh: NG-2026..."
                                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition-all focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label for="payment_status" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Status Pembayaran</label>
                            <select id="payment_status" name="payment_status"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition-all focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                                <option value="">Semua status</option>
                                <option value="paid" @selected(request('payment_status') === 'paid')>Lunas</option>
                                <option value="unpaid" @selected(request('payment_status') === 'unpaid')>Belum Dibayar</option>
                                <option value="refunded" @selected(request('payment_status') === 'refunded')>Dikembalikan</option>
                            </select>
                        </div>
                        <div>
                            <label for="payment_method" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Metode Pembayaran</label>
                            <select id="payment_method" name="payment_method"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition-all focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                                <option value="">Semua metode</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method }}" @selected(request('payment_method') === $method)>
                                        {{ match ($method) {
                                            'qris' => 'QRIS',
                                            'cash' => 'Tunai',
                                            'transfer' => 'Transfer',
                                            'card' => 'Kartu',
                                            default => ucfirst($method),
                                        } }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:from-teal-600 hover:to-emerald-700 hover:shadow-xl">
                                Filter
                            </button>
                            <a href="{{ route('admin.cashier.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 hover:shadow-lg">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </section>

            {{-- Transactions --}}
            <section class="relative overflow-hidden rounded-3xl border border-white/90 bg-white/80 shadow-2xl shadow-slate-900/5 backdrop-blur-md">
                <div class="absolute left-0 top-0 h-1 w-full bg-gradient-to-r from-teal-500 to-emerald-500"></div>
                <div class="border-b border-white/80 px-6 py-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="font-bold text-slate-900">Daftar Transaksi</h2>
                            <p class="mt-1 text-sm text-slate-500">Riwayat pembayaran pesanan tamu.</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-teal-100 bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-600">
                            {{ $orders->total() }} Transaksi
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50 backdrop-blur-sm">
                            <tr>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">No. Pesanan</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Kamar</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Metode</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Jumlah</th>
                                <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Waktu</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100/70">
                            @forelse ($orders as $order)
                                <tr class="group transition-all duration-200 hover:bg-slate-50/70">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="flex flex-col">
                                            <span class="font-medium text-slate-900 group-hover:text-teal-600">{{ $order->order_number }}</span>
                                            <span class="mt-0.5 font-mono text-xs text-slate-400">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $order->room?->room_number ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $order->payment_method_label }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold
                                            {{ $order->payment_status === 'paid'
                                                ? 'border-teal-200 bg-teal-50 text-teal-700 shadow-sm shadow-teal-200/50'
                                                : ($order->payment_status === 'refunded'
                                                    ? 'border-slate-200 bg-slate-100 text-slate-600'
                                                    : 'border-orange-200 bg-orange-50 text-orange-700 shadow-sm shadow-orange-200/50') }}">
                                            {{ $order->payment_status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium tabular-nums text-slate-900">
                                        Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-500">
                                        {{ $order->placed_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                               class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-50 text-teal-600 shadow-sm shadow-teal-200/50 transition-all hover:bg-teal-100 hover:text-teal-700"
                                               title="Lihat Detail">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            @if ($order->payment_status === 'unpaid')
                                                <form method="POST" action="{{ route('admin.cashier.confirm', $order) }}" class="flex items-center gap-1">
                                                    @csrf
                                                    <select name="payment_method" class="rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                                                        <option value="cash" @selected($order->payment_method === 'cash')>Tunai</option>
                                                        <option value="qris" @selected($order->payment_method === 'qris')>QRIS</option>
                                                        <option value="transfer" @selected($order->payment_method === 'transfer')>Transfer</option>
                                                        <option value="card" @selected($order->payment_method === 'card')>Kartu</option>
                                                    </select>
                                                    <button type="submit"
                                                            class="rounded-lg bg-gradient-to-r from-teal-500 to-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm shadow-teal-900/20 transition hover:from-teal-600 hover:to-emerald-700"
                                                            onclick="return confirm('Konfirmasi pembayaran pesanan ini?')">
                                                        Konfirmasi
                                                    </button>
                                                </form>
                                            @elseif ($order->payment_status === 'paid')
                                                <form method="POST" action="{{ route('admin.cashier.refund', $order) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100"
                                                            onclick="return confirm('Tandai pembayaran ini sebagai dikembalikan?')">
                                                        Refund
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 shadow-sm shadow-slate-200/50">
                                                <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="font-medium text-slate-900">Belum ada transaksi</p>
                                            <p class="text-sm text-slate-500">Data pembayaran akan tampil di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="flex items-center justify-between border-t border-white/80 bg-white/50 px-6 py-4">
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
