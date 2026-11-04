<x-layouts::app>
    <main class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 px-6 py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Page Header with 3D card --}}
            <header class="mb-4 relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-teal-400 to-emerald-500 rounded-2xl blur opacity-20"></div>
                <div class="relative rounded-2xl border border-white/80 bg-white/70 backdrop-blur-md p-6 shadow-2xl shadow-teal-900/10">
                    <div class="flex items-end justify-between gap-4 mb-3">
                        <div>
                            <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase">
                                Kasir
                            </p>
                            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
                                Kasir & Pembayaran
                            </h1>
                            <p class="mt-1 text-sm text-slate-500">
                                Kelola transaksi dan konfirmasi pembayaran pesanan.
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl shadow-lg hover:bg-teal-50 hover:border-teal-200 hover:-translate-y-0.5 hover:shadow-xl transition-all duration-200">
                                ← Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Summary Cards with 3D effects --}}
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Card 1: Total Sales --}}
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-40 transition-opacity duration-300"></div>
                    <div class="relative rounded-2xl border border-white/90 bg-white/80 backdrop-blur-sm p-6 shadow-2xl shadow-emerald-500/20 hover:shadow-emerald-500/40 transition-all duration-300">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center group-hover:bg-ember/100 transition-colors">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Penjualan</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-900 mb-1">
                            Rp {{ number_format((float) $totalSales, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-slate-400">Pembayaran yang telah dikonfirmasi</p>
                    </div>
                </div>

                {{-- Card 2: Pending Payments --}}
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-amber-400 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-40 transition-opacity duration-300"></div>
                    <div class="relative rounded-2xl border border-white/90 bg-white/80 backdrop-blur-sm p-6 shadow-2xl shadow-amber-500/20 hover:shadow-amber-500/40 transition-all duration-300">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center group-hover:bg-amber/100 transition-colors">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Menunggu Pembayaran</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-900 mb-1">
                            {{ $pendingPayments }}
                        </p>
                        <p class="text-xs text-slate-400">Pesanan dengan status unpaid</p>
                    </div>
                </div>

                {{-- Card 3: Completed Today --}}
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-40 transition-opacity duration-300"></div>
                    <div class="relative rounded-2xl border border-white/90 bg-white/80 backdrop-blur-sm p-6 shadow-2xl shadow-blue-500/20 hover:shadow-blue-500/40 transition-all duration-300">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center group-hover:bg-blue/100 transition-colors">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Selesai Hari Ini</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-900 mb-1">
                            {{ $completedToday }}
                        </p>
                        <p class="text-xs text-slate-400">Pesanan lunas hari ini</p>
                    </div>
                </div>

                {{-- Card 4: Total Orders --}}
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-purple-400 to-pink-500 rounded-2xl blur opacity-30 group-hover:opacity-40 transition-opacity duration-300"></div>
                    <div class="relative rounded-2xl border border-white/90 bg-white/80 backdrop-blur-sm p-6 shadow-2xl shadow-purple-500/20 hover:shadow-purple-500/40 transition-all duration-300">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center group-hover:bg-purple/100 transition-colors">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Total Pesanan</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-900 mb-1">
                            {{ $orders->total() }}
                        </p>
                        <p class="text-xs text-slate-400">Semua pesanan tercatat</p>
                    </div>
                </div>
            </div>

            {{-- Filters Section --}}
            <section class="relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-2xl blur opacity-20"></div>
                <div class="relative rounded-2xl border border-white/90 bg-white/80 backdrop-blur-md p-6 shadow-2xl shadow-indigo-900/10">
                    <form method="GET" class="grid gap-4 sm:grid-cols-3 items-end">
                        <div>
                            <label for="payment_status" class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Status Pembayaran
                            </label>
                            <select
                                id="payment_status"
                                name="payment_status"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all"
                            >
                                <option value="">Semua status</option>
                                <option value="paid" @selected(request('payment_status') === 'paid')>Lunas</option>
                                <option value="unpaid" @selected(request('payment_status') === 'unpaid')>Belum Lunas</option>
                                <option value="refunded" @selected(request('payment_status') === 'refunded')>Dikembalikan</option>
                            </select>
                        </div>

                        <div>
                            <label for="payment_method" class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Metode Pembayaran
                            </label>
                            <select
                                id="payment_method"
                                name="payment_method"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all"
                            >
                                <option value="">Semua metode</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method }}" @selected(request('payment_method') === $method)>
                                        {{ ucfirst($method) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:from-teal-600 hover:to-emerald-700 hover:-translate-y-0.5 hover:shadow-xl shadow-lg shadow-teal-900/20 transition-all duration-200">
                                Filter
                            </button>
                            <a href="{{ route('admin.cashier.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </section>

            {{-- Transactions Table with 3D cards per row --}}
            <section class="relative overflow-hidden rounded-3xl border border-white/90 bg-white/80 backdrop-blur-md shadow-2xl shadow-slate-900/5">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 to-emerald-500"></div>
                <div class="border-b border-white/80 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-bold text-slate-900">Daftar Transaksi</h2>
                            <p class="mt-1 text-sm text-slate-500">Riwayat pembayaran pesanan tamu.</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-50 text-teal-600 text-xs font-semibold border border-teal-100">
                            {{ $orders->total() }} Transaksi
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50 backdrop-blur-sm">
                            <tr>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">No. Pesanan</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Kamar</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right font-medium text-slate-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-4 text-right font-medium text-slate-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100/50">
                            @forelse ($orders as $order)
                                <tr class="group hover:bg-slate-50/50 transition-all duration-200 relative">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-slate-900">{{ $order->order_number }}</span>
                                            <span class="text-xs text-slate-400 font-mono mt-0.5">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $order->room?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2zm7-2a2 2 0 10-4 0 2 2 0 004 0z"></path></svg>
                                            {{ ucfirst($order->payment_method ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border
                                                {{ $order->payment_status === 'paid' ? 'border-teal-200 bg-teal-50 text-teal-700 shadow-sm shadow-teal-200/50' : 'border-orange-200 bg-orange-50 text-orange-700 shadow-sm shadow-orange-200/50' }}"
                                        >
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-900 font-medium">
                                        Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-500">
                                        {{ $order->placed_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex gap-1">
                                            <a
                                                href="{{ route('admin.orders.show', $order) }}"
                                                class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 hover:bg-teal-100 hover:text-teal-700 transition-all shadow-sm shadow-teal-200/50"
                                                title="Lihat Detail"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto shadow-sm shadow-slate-200/50">
                                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
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
                    <div class="border-t border-white/80 px-6 py-4 flex items-center justify-between bg-white/50">
                        <p class="text-sm text-slate-500">
                            Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }} data
                        </p>
                        <div class="flex gap-1">
                            {{ $orders->links() }}
                        </div>
                    </div>
                @endif
            </section>

        </div>
    </main>
</x-layouts::app>
