<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;

new class extends Component
{
    public function getUserNameProperty(): string
    {
        return Auth::user()->name ?? 'User';
    }

    public function getUserRoleProperty(): string
    {
        return Auth::user()?->getRoleNames()->first() ?? 'No Role';
    }

    public function getStatsProperty(): array
    {
        $role = $this->userRole;

        $stats = [];

        if (in_array($role, ['superadmin', 'admin', 'cashier', 'kitchen'], true)) {
            $stats[] = [
                'label' => 'Pesanan Hari Ini',
                'value' => Order::whereDate('placed_at', today())->count(),
                'description' => 'Total pesanan masuk hari ini',
                'route' => 'admin.orders.index',
            ];
        }

        if (in_array($role, ['superadmin', 'admin', 'cashier'], true)) {
            $stats[] = [
                'label' => 'Menunggu Pembayaran',
                'value' => Order::where('payment_status', 'unpaid')->count(),
                'description' => 'Pesanan yang belum lunas',
                'route' => 'admin.cashier.index',
            ];
        }

        if (in_array($role, ['superadmin', 'admin', 'kitchen'], true)) {
            $stats[] = [
                'label' => 'Antrian Dapur',
                'value' => Order::whereIn('order_status', ['pending', 'confirmed', 'preparing', 'ready'])->count(),
                'description' => 'Pesanan yang sedang diproses',
                'route' => 'admin.kitchen.index',
            ];
        }

        if (in_array($role, ['superadmin'], true)) {
            $stats[] = [
                'label' => 'Total Pengguna',
                'value' => User::count(),
                'description' => 'Akun staf terdaftar',
                'route' => 'admin.users.index',
            ];
        }

        return $stats;
    }

    public function getRevenueTodayProperty(): float
    {
        return (float) Order::where('payment_status', 'paid')
            ->whereDate('payment_confirmed_at', today())
            ->sum('total');
    }

    public function getRevenueTotalProperty(): float
    {
        return (float) Order::where('payment_status', 'paid')->sum('total');
    }

    public function getActiveOrdersProperty(): int
    {
        return Order::whereIn('order_status', ['pending', 'confirmed', 'preparing', 'ready'])->count();
    }

    public function getRecentOrdersProperty()
    {
        return Order::with(['room', 'items'])
            ->latest('placed_at')
            ->take(6)
            ->get();
    }

    public function getWeeklySeriesProperty(): array
    {
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $series = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);

            $series[] = [
                'label' => $dayNames[$date->dayOfWeek],
                'date' => $date->format('d/m'),
                'count' => Order::whereDate('placed_at', $date)->count(),
                'total' => (float) Order::whereDate('placed_at', $date)
                    ->where('payment_status', 'paid')
                    ->sum('total'),
            ];
        }

        return $series;
    }

    public function getStatusBreakdownProperty(): array
    {
        return [
            ['label' => 'Menunggu', 'value' => Order::where('order_status', 'pending')->count(), 'color' => 'bg-slate-400'],
            ['label' => 'Dikonfirmasi', 'value' => Order::where('order_status', 'confirmed')->count(), 'color' => 'bg-sky-500'],
            ['label' => 'Diproses', 'value' => Order::where('order_status', 'preparing')->count(), 'color' => 'bg-amber-500'],
            ['label' => 'Siap Disajikan', 'value' => Order::where('order_status', 'ready')->count(), 'color' => 'bg-teal-500'],
            ['label' => 'Selesai', 'value' => Order::where('order_status', 'completed')->count(), 'color' => 'bg-emerald-600'],
            ['label' => 'Dibatalkan', 'value' => Order::where('order_status', 'cancelled')->count(), 'color' => 'bg-rose-500'],
        ];
    }
}
?>

<div class="min-h-screen bg-[#f8fafc] text-[#252525]">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <x-dashboard.sidebar />

        {{-- Main Content --}}
        <main class="min-w-0 flex-1">
            <x-dashboard.page-header
                title="Dashboard"
                breadcrumb="Ngoffe ID / Workspace"
            />

            {{-- Toast notifikasi (khusus dashboard, alert inline di halaman admin tetap dipertahankan) --}}
            @if (session('success') || session('error') || session('info'))
                <div class="pointer-events-none fixed right-4 top-4 z-[60] w-[min(22rem,calc(100vw-2rem))] space-y-3" aria-live="polite" role="status">
                    @if (session('success'))
                        <div
                            x-data="{ show: true }"
                            x-init="setTimeout(() => show = false, 4500)"
                            x-show="show"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="toast-enter pointer-events-auto flex items-start gap-3 rounded-2xl border border-ngofee-border bg-ngofee-bg px-4 py-3 text-sm text-ngofee-dark shadow-lg shadow-ngofee/10"
                        >
                            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-ngofee text-white" aria-hidden="true">&check;</span>
                            <p class="flex-1 font-medium">{{ session('success') }}</p>
                            <button type="button" class="pressable -mr-1 rounded-md p-1 text-ngofee-dark/70 hover:bg-ngofee-soft" @click="show = false" aria-label="Tutup notifikasi">&times;</button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            x-data="{ show: true }"
                            x-init="setTimeout(() => show = false, 5000)"
                            x-show="show"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="toast-enter pointer-events-auto flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-lg shadow-rose-900/10"
                        >
                            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-500 text-white" aria-hidden="true">!</span>
                            <p class="flex-1 font-medium">{{ session('error') }}</p>
                            <button type="button" class="pressable -mr-1 rounded-md p-1 text-rose-700/70 hover:bg-rose-100" @click="show = false" aria-label="Tutup notifikasi">&times;</button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div
                            x-data="{ show: true }"
                            x-init="setTimeout(() => show = false, 4500)"
                            x-show="show"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="toast-enter pointer-events-auto flex items-start gap-3 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800 shadow-lg shadow-sky-900/10"
                        >
                            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-500 text-white" aria-hidden="true">i</span>
                            <p class="flex-1 font-medium">{{ session('info') }}</p>
                            <button type="button" class="pressable -mr-1 rounded-md p-1 text-sky-700/70 hover:bg-sky-100" @click="show = false" aria-label="Tutup notifikasi">&times;</button>
                        </div>
                    @endif
                </div>
            @endif

            <section class="space-y-8 p-4 md:p-10">

                {{-- Skeleton halus saat data dashboard dimuat ulang --}}
                <div wire:loading.delay class="skeleton h-1.5 w-full rounded-full" aria-hidden="true"></div>

                {{-- Hero --}}
                <div class="dashboard-enter relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900 p-6 text-white shadow-2xl shadow-teal-900/20 md:p-8">
                    <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-teal-400/20 blur-3xl"></div>
                    <div class="absolute -bottom-24 left-1/4 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl"></div>
                    <div class="absolute inset-x-8 bottom-0 h-px bg-gradient-to-r from-transparent via-ngofee-mint/40 to-transparent"></div>

                    <div class="relative flex flex-wrap items-end justify-between gap-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-300">
                                Selamat datang kembali,
                            </p>
                            <h1 class="mt-2 text-3xl font-bold tracking-tight md:text-4xl">
                                {{ $this->userName }}.
                            </h1>
                            <p class="mt-3 max-w-xl text-sm leading-7 text-slate-300">
                                Ringkasan operasional Ngoffe ID hari ini. Pantau pesanan, pembayaran,
                                dan antrian dapur dari satu tempat.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @if (in_array($this->userRole, ['superadmin', 'admin', 'cashier'], true))
                                <a href="{{ route('admin.cashier.index') }}"
                                   class="action-button rounded-xl px-5 py-2.5 text-sm font-semibold focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    Buka Kasir
                                </a>
                            @endif
                            @if (in_array($this->userRole, ['superadmin', 'admin', 'kitchen'], true))
                                <a href="{{ route('admin.kitchen.index') }}"
                                   class="pressable rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur hover:bg-white/20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    Lihat Dapur
                                </a>
                            @endif
                            <button type="button"
                                    wire:click="$refresh"
                                    wire:loading.attr="disabled"
                                    class="pressable inline-flex items-center gap-2 rounded-xl border border-white/15 px-5 py-2.5 text-sm font-semibold text-white/90 hover:border-ngofee-mint/50 hover:bg-white/10 disabled:cursor-wait disabled:opacity-70">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span wire:loading.remove>Muat Ulang</span>
                                <span wire:loading>Memuat...</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Revenue Highlight --}}
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="reveal rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-teal-50 p-6 shadow-sm" style="--reveal-delay: 60ms">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Pendapatan Hari Ini</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-900">
                            <span wire:loading.remove
                                  data-countup="{{ (int) round($this->revenueToday) }}"
                                  data-countup-key="revenue-today"
                                  data-countup-format="currency">Rp {{ number_format($this->revenueToday, 0, ',', '.') }}</span>
                            <span wire:loading class="skeleton inline-block h-8 w-40 rounded-lg align-middle" aria-hidden="true"></span>
                        </p>
                        <p class="mt-1 text-xs text-emerald-700">Pembayaran lunas hari ini</p>
                    </div>
                    <div class="reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay: 120ms">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pendapatan</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            <span wire:loading.remove
                                  data-countup="{{ (int) round($this->revenueTotal) }}"
                                  data-countup-key="revenue-total"
                                  data-countup-format="currency">Rp {{ number_format($this->revenueTotal, 0, ',', '.') }}</span>
                            <span wire:loading class="skeleton inline-block h-8 w-40 rounded-lg align-middle" aria-hidden="true"></span>
                        </p>
                        <p class="mt-1 text-xs text-slate-500">Akumulasi seluruh pembayaran lunas</p>
                    </div>
                    <div class="reveal rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50 p-6 shadow-sm" style="--reveal-delay: 180ms">
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Pesanan Aktif</p>
                        <p class="mt-2 text-3xl font-bold text-amber-900">
                            <span wire:loading.remove
                                  data-countup="{{ $this->activeOrders }}"
                                  data-countup-key="active-orders">{{ number_format($this->activeOrders, 0, ',', '.') }}</span>
                            <span wire:loading class="skeleton inline-block h-8 w-16 rounded-lg align-middle" aria-hidden="true"></span>
                        </p>
                        <p class="mt-1 text-xs text-amber-700">Belum selesai / dibatalkan</p>
                    </div>
                </div>

                {{-- Quick Access Stats --}}
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    @forelse ($this->stats as $stat)
                        <a href="{{ route($stat['route']) }}"
                           class="stat-card group reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ngofee"
                           style="--reveal-delay: {{ 220 + $loop->index * 60 }}ms">
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 transition group-hover:text-ngofee">
                                        {{ $stat['label'] }}
                                    </p>
                                    <p class="mt-0.5 text-xs text-slate-400 transition group-hover:text-slate-600">
                                        {{ $stat['description'] }}
                                    </p>
                                </div>
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-ngofee-bg transition-colors group-hover:bg-ngofee-soft">
                                    <span class="text-lg text-ngofee transition-colors group-hover:text-ngofee-dark">&rarr;</span>
                                </div>
                            </div>
                            <p class="text-2xl font-bold text-slate-900 transition-colors group-hover:text-ngofee">
                                <span wire:loading.remove
                                      data-countup="{{ (int) $stat['value'] }}"
                                      data-countup-key="stat-{{ $stat['route'] }}">{{ number_format($stat['value'], 0, ',', '.') }}</span>
                                <span wire:loading class="skeleton inline-block h-7 w-16 rounded-lg align-middle" aria-hidden="true"></span>
                            </p>
                        </a>
                    @empty
                        <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                            <p class="text-sm font-medium text-slate-900">Tidak ada modul tersedia</p>
                            <p class="mt-1 text-sm text-slate-500">Tidak ada modul yang tersedia untuk role Anda.</p>
                        </div>
                    @endforelse
                </div>

                <div class="grid gap-6 lg:grid-cols-5">
                    {{-- Weekly Chart --}}
                    <div class="reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-3" style="--reveal-delay: 360ms">
                        <div class="flex items-end justify-between gap-3">
                            <div>
                                <h3 class="font-bold text-slate-900">Pesanan 7 Hari Terakhir</h3>
                                <p class="mt-1 text-sm text-slate-500">Jumlah pesanan masuk per hari.</p>
                            </div>
                            <span class="rounded-full bg-ngofee-bg px-3 py-1 text-xs font-semibold text-ngofee">
                                {{ collect($this->weeklySeries)->sum('count') }} pesanan
                            </span>
                        </div>

                        @php $maxCount = max(1, collect($this->weeklySeries)->max('count')); @endphp
                        <div class="mt-6 flex h-48 items-end gap-3">
                            @foreach ($this->weeklySeries as $day)
                                <div class="group flex flex-1 flex-col items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500">{{ $day['count'] }}</span>
                                    <div class="flex w-full flex-1 items-end">
                                        <div class="chart-bar w-full rounded-t-lg bg-gradient-to-t from-teal-600 to-emerald-400 transition-colors duration-300 group-hover:from-teal-700 group-hover:to-emerald-300"
                                             style="--bar-delay: {{ $loop->index * 60 }}ms; height: {{ max(6, round(($day['count'] / $maxCount) * 100)) }}%"
                                             title="{{ $day['date'] }}: {{ $day['count'] }} pesanan, Rp {{ number_format($day['total'], 0, ',', '.') }}">
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400">{{ $day['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Status Breakdown --}}
                    <div class="reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2" style="--reveal-delay: 420ms">
                        <h3 class="font-bold text-slate-900">Status Pesanan</h3>
                        <p class="mt-1 text-sm text-slate-500">Distribusi seluruh pesanan.</p>

                        @php $totalStatus = max(1, collect($this->statusBreakdown)->sum('value')); @endphp
                        <div class="mt-5 space-y-4">
                            @foreach ($this->statusBreakdown as $item)
                                <div>
                                    <div class="mb-1 flex items-center justify-between text-xs">
                                        <span class="font-medium text-slate-600">{{ $item['label'] }}</span>
                                        <span class="font-semibold text-slate-900">{{ $item['value'] }}</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                        <div class="segment-bar h-full rounded-full {{ $item['color'] }}"
                                             style="--bar-delay: {{ $loop->index * 60 }}ms; width: {{ round(($item['value'] / $totalStatus) * 100) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Recent Orders --}}
                <div class="reveal overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" style="--reveal-delay: 480ms">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                        <div>
                            <h3 class="font-bold text-slate-900">Pesanan Terbaru</h3>
                            <p class="mt-1 text-sm text-slate-500">6 pesanan terakhir yang masuk.</p>
                        </div>
                        <a href="{{ route('admin.orders.index') }}"
                           class="text-sm font-semibold text-ngofee transition hover:text-ngofee-dark">
                            Lihat semua &rarr;
                        </a>
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
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($this->recentOrders as $order)
                                    <tr
                                        data-order-row="{{ $order->id }}"
                                        data-order-status="{{ $order->order_status }}"
                                        data-order-payment="{{ $order->payment_status }}"
                                        data-order-total="{{ (int) $order->total }}"
                                        class="order-row transition hover:bg-slate-50"
                                    >
                                        <td class="px-6 py-4">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-slate-900 hover:text-ngofee">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">{{ $order->room?->room_number ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold
                                                {{ $order->order_status === 'completed' ? 'border-ngofee-border bg-ngofee-bg text-ngofee-dark' : ($order->order_status === 'cancelled' ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-slate-200 bg-slate-50 text-slate-700') }}">
                                                {{ $order->order_status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold
                                                {{ $order->payment_status === 'paid' ? 'border-ngofee-border bg-ngofee-bg text-ngofee-dark' : 'border-orange-200 bg-orange-50 text-orange-700' }}">
                                                {{ $order->payment_status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium tabular-nums text-slate-900">
                                            Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                                            Belum ada pesanan. Data akan tampil setelah pesanan pertama masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </main>
    </div>
</div>
