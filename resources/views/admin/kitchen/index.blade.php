<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Page Header --}}
            <header class="mb-4">
                <div class="flex items-end justify-between gap-4 mb-3">
                    <div>
                        <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase">
                            Dapur
                        </p>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                            Manajemen Dapur
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Pantau pesanan yang sedang diproses dan siap disajikan.
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:border-slate-300 transition">
                            ← Kembali
                        </a>
                    </div>
                </div>
            </header>

            {{-- Kitchen Summary Cards --}}
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Antrian Diproses</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ $preparingCount }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Pesanan dengan status preparing</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Siap Disajikan</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ $readyCount }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Pesanan dengan status ready</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Antrian</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ $orders->total() }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Pending, confirmed, preparing</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Items Masuk</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ $orders->sum(fn ($o) => $o->items->count()) }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Total item dalam antrian</p>
                </div>
            </div>

            {{-- Kitchen Orders Table --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="font-bold text-slate-900">Daftar Pesanan Dapur</h2>
                    <p class="mt-1 text-sm text-slate-500">Urutkan berdasarkan waktu pesanan masuk.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">No. Pesanan</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Kamar</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-4 text-right font-medium text-slate-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-right font-medium text-slate-500 uppercase tracking-wider">Waktu</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $order->room?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                {{ $order->order_status === 'preparing' ? 'bg-amber-100 text-amber-700 border border-amber-200' : ( $order->order_status === 'ready' ? 'bg-teal-100 text-teal-700 border border-teal-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}"
                                        >
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 tabular-nums text-slate-900 font-medium">
                                        {{ $order->items->count() }}
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-900 font-medium">
                                        Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums text-slate-500">
                                        {{ $order->placed_at?->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            <p class="font-medium text-slate-900">Belum ada antrian dapur</p>
                                            <p class="text-sm text-slate-500">Data pesanan dapur akan tampil di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="border-t border-slate-100 px-6 py-4 flex items-center justify-between">
                        <p class="text-sm text-slate-500">
                            Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }} data
                        </p>
                        {{ $orders->links() }}
                    </div>
                @endif
            </section>

        </div>
    </main>
</x-layouts::app>
