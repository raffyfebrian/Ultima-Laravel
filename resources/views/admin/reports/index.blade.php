<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <header class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-600">Analitik</p>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Laporan Penjualan</h1>
                    <p class="mt-1 text-sm text-slate-500">Rekap transaksi lunas berdasarkan rentang tanggal.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.reports.export', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition hover:-translate-y-0.5 hover:from-teal-600 hover:to-emerald-700 hover:shadow-xl">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export CSV
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                        &larr; Kembali
                    </a>
                </div>
            </header>

            {{-- Filter --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="GET" class="grid items-end gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="from" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Dari Tanggal</label>
                        <input type="date" id="from" name="from" value="{{ $from->format('Y-m-d') }}"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label for="to" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Sampai Tanggal</label>
                        <input type="date" id="to" name="to" value="{{ $to->format('Y-m-d') }}"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="flex gap-2 lg:col-span-2">
                        <button type="submit" class="flex-1 rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                            Tampilkan Laporan
                        </button>
                        <a href="{{ route('admin.reports.index') }}"
                           class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Bulan Ini
                        </a>
                    </div>
                </form>

                <p class="mt-4 text-xs text-slate-500">
                    Periode: <span class="font-semibold text-slate-700">{{ $from->format('d M Y') }}</span>
                    &ndash;
                    <span class="font-semibold text-slate-700">{{ $to->format('d M Y') }}</span>
                    &middot; hanya transaksi dengan status <span class="font-semibold text-teal-700">Lunas</span>.
                </p>
            </section>

            {{-- Summary --}}
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-teal-200 bg-teal-50/60 p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-700">Total Pendapatan</p>
                    <p class="mt-2 text-2xl font-bold text-teal-900">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-teal-700">Dari {{ $orderCount }} transaksi lunas</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Jumlah Transaksi</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $orderCount }}</p>
                    <p class="mt-1 text-xs text-slate-500">Pembayaran terkonfirmasi</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Item Terjual</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $itemsSold }}</p>
                    <p class="mt-1 text-xs text-slate-500">Total kuantitas produk</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Rata-rata / Transaksi</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">Rp {{ number_format($average, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-slate-500">Nilai belanja rata-rata</p>
                </div>
            </div>

            @if ($orderCount === 0)
                <section class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-20 text-center shadow-sm">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-50">
                        <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="mt-4 font-medium text-slate-900">Belum ada transaksi lunas</p>
                    <p class="mt-1 text-sm text-slate-500">Coba ubah rentang tanggal atau konfirmasi pembayaran di kasir.</p>
                </section>
            @else
                <div class="grid gap-6 lg:grid-cols-5">
                    {{-- Daily breakdown --}}
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-3">
                        <div class="border-b border-slate-100 px-6 py-5">
                            <h2 class="font-bold text-slate-900">Rincian Harian</h2>
                            <p class="mt-1 text-sm text-slate-500">Pendapatan per tanggal pembayaran.</p>
                        </div>

                        @php
                            $maxDailyRevenue = max(1, (float) $daily->max('revenue'));
                        @endphp

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Tanggal</th>
                                        <th class="px-6 py-4 text-center font-medium uppercase tracking-wider text-slate-500">Transaksi</th>
                                        <th class="px-6 py-4 text-center font-medium uppercase tracking-wider text-slate-500">Item</th>
                                        <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($daily as $day)
                                        <tr class="transition hover:bg-slate-50">
                                            <td class="px-6 py-4">
                                                <p class="font-medium text-slate-900">{{ $day['date']->format('d M Y') }}</p>
                                                <p class="text-xs text-slate-400">{{ $day['date']->translatedFormat('l') }}</p>
                                                <div class="mt-2 h-1.5 w-full max-w-40 overflow-hidden rounded-full bg-slate-100">
                                                    <div class="h-full rounded-full bg-gradient-to-r from-teal-400 to-emerald-500"
                                                         style="width: {{ max(4, ($day['revenue'] / $maxDailyRevenue) * 100) }}%"></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center tabular-nums text-slate-600">{{ $day['orders'] }}</td>
                                            <td class="px-6 py-4 text-center tabular-nums text-slate-600">{{ $day['items'] }}</td>
                                            <td class="px-6 py-4 text-right font-medium tabular-nums text-slate-900">
                                                Rp {{ number_format($day['revenue'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-slate-50">
                                    <tr>
                                        <td class="px-6 py-4 font-bold text-slate-900">Total</td>
                                        <td class="px-6 py-4 text-center font-bold tabular-nums text-slate-900">{{ $orderCount }}</td>
                                        <td class="px-6 py-4 text-center font-bold tabular-nums text-slate-900">{{ $itemsSold }}</td>
                                        <td class="px-6 py-4 text-right font-bold tabular-nums text-slate-900">Rp {{ number_format($revenue, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </section>

                    {{-- Payment methods --}}
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                        <h2 class="font-bold text-slate-900">Metode Pembayaran</h2>
                        <p class="mt-1 text-sm text-slate-500">Kontribusi tiap metode terhadap pendapatan.</p>

                        <div class="mt-5 space-y-4">
                            @foreach ($paymentBreakdown as $payment)
                                @php
                                    $share = $revenue > 0 ? ($payment['revenue'] / $revenue) * 100 : 0;
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-medium text-slate-700">{{ $payment['label'] }}</span>
                                        <span class="font-semibold text-slate-900">{{ number_format($share, 1, ',', '.') }}%</span>
                                    </div>
                                    <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-gradient-to-r from-teal-400 to-emerald-500"
                                             style="width: {{ max(3, $share) }}%"></div>
                                    </div>
                                    <div class="mt-1 flex items-center justify-between text-xs text-slate-500">
                                        <span>{{ $payment['orders'] }} transaksi</span>
                                        <span>Rp {{ number_format($payment['revenue'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>

                {{-- Top products --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-5">
                        <div>
                            <h2 class="font-bold text-slate-900">Produk Terlaris</h2>
                            <p class="mt-1 text-sm text-slate-500">Diurutkan berdasarkan jumlah item terjual.</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">
                            Top {{ $topProducts->count() }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">#</th>
                                    <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Produk</th>
                                    <th class="px-6 py-4 text-center font-medium uppercase tracking-wider text-slate-500">Qty Terjual</th>
                                    <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($topProducts as $index => $product)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold
                                                {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-slate-200 text-slate-700' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-slate-100 text-slate-500')) }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-slate-900">{{ $product['name'] }}</td>
                                        <td class="px-6 py-4 text-center tabular-nums text-slate-600">{{ $product['quantity'] }}</td>
                                        <td class="px-6 py-4 text-right font-medium tabular-nums text-slate-900">
                                            Rp {{ number_format($product['revenue'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif

        </div>
    </main>
</x-layouts::app>
