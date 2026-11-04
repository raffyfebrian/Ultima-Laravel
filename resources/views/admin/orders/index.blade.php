<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Page Header --}}
            <header class="mb-4">
                <div class="flex items-end justify-between gap-4 mb-3">
                    <div>
                        <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase">
                            Administration
                        </p>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                            Manajemen Pesanan
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Pantau semua pesanan dari tamu kamar.
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

            {{-- Filters --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="GET" class="grid gap-4 md:grid-cols-4 items-end">
                    <div>
                        <label for="status" class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Status Pesanan
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                        >
                            <option value="">Semua status</option>
                            @foreach ($statuses as $status)
                                <option
                                    value="{{ $status }}"
                                    @selected(request('status') === $status)
                                >
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="payment_status" class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Status Pembayaran
                        </label>
                        <select
                            id="payment_status"
                            name="payment_status"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                        >
                            <option value="">Semua pembayaran</option>
                            @foreach ($paymentStatuses as $ps)
                                <option
                                    value="{{ $ps }}"
                                    @selected(request('payment_status') === $ps)
                                >
                                    {{ ucfirst($ps) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="flex-1 rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white hover:bg-teal-700 transition">
                            Filter
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
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
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">No. Pesanan</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Kamar</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Pembayaran</th>
                                <th class="px-6 py-4 text-right font-medium text-slate-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-right font-medium text-slate-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-teal-600 hover:underline">
                                            <p class="font-medium text-slate-900">{{ $order->order_number }}</p>
                                            <p class="text-xs text-slate-400 font-mono">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $order->room?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                {{ $order->order_status === 'completed' ? 'bg-teal-100 text-teal-700 border border-teal-200' : ( $order->order_status === 'preparing' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}"
                                        >
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                {{ $order->payment_status === 'paid' ? 'bg-teal-100 text-teal-700 border border-teal-200' : 'bg-orange-100 text-orange-700 border border-orange-200' }}"
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
                                        <a
                                            href="{{ route('admin.orders.show', $order) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-teal-600 bg-teal-50 rounded-lg hover:bg-teal-100 transition border border-teal-100"
                                        >
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
