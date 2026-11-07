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

            <section class="space-y-8 p-6 md:p-10">

                {{-- Hero --}}
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900 p-8 text-white shadow-2xl shadow-teal-900/20">
                    <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-teal-400/20 blur-3xl"></div>
                    <div class="absolute -bottom-24 left-1/4 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl"></div>

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
                                   class="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-teal-50">
                                    Buka Kasir
                                </a>
                            @endif
                            @if (in_array($this->userRole, ['superadmin', 'admin', 'kitchen'], true))
                                <a href="{{ route('admin.kitchen.index') }}"
                                   class="rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                                    Lihat Dapur
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Revenue Highlight --}}
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-teal-50 p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Pendapatan Hari Ini</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-900">Rp {{ number_format($this->revenueToday, 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-emerald-700">Pembayaran lunas hari ini</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pendapatan</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">Rp {{ number_format($this->revenueTotal, 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-slate-500">Akumulasi seluruh pembayaran lunas</p>
                    </div>
                    <div class="rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50 p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Pesanan Aktif</p>
                        <p class="mt-2 text-3xl font-bold text-amber-900">{{ $this->activeOrders }}</p>
                        <p class="mt-1 text-xs text-amber-700">Belum selesai / dibatalkan</p>
                    </div>
                </div>

                {{-- Quick Access Stats --}}
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    @forelse ($this->stats as $stat)
                        <a href="{{ route($stat['route']) }}"
                           class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-teal-500/50 hover:bg-slate-50 hover:shadow-lg">
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 transition group-hover:text-teal-600">
                                        {{ $stat['label'] }}
                                    </p>
                                    <p class="mt-0.5 text-xs text-slate-400 transition group-hover:text-slate-600">
                                        {{ $stat['description'] }}
                                    </p>
                                </div>
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-50 transition-colors group-hover:bg-teal-100">
                                    <span class="text-lg text-teal-600 transition-colors group-hover:text-teal-700">&rarr;</span>
                                </div>
                            </div>
                            <p class="text-2xl font-bold text-slate-900 transition-colors group-hover:text-teal-600">
                                {{ $stat['value'] }}
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
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-3">
                        <div class="flex items-end justify-between gap-3">
                            <div>
                                <h3 class="font-bold text-slate-900">Pesanan 7 Hari Terakhir</h3>
                                <p class="mt-1 text-sm text-slate-500">Jumlah pesanan masuk per hari.</p>
                            </div>
                            <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">
                                {{ collect($this->weeklySeries)->sum('count') }} pesanan
                            </span>
                        </div>

                        @php $maxCount = max(1, collect($this->weeklySeries)->max('count')); @endphp
                        <div class="mt-6 flex h-48 items-end gap-3">
                            @foreach ($this->weeklySeries as $day)
                                <div class="group flex flex-1 flex-col items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500">{{ $day['count'] }}</span>
                                    <div class="flex w-full flex-1 items-end">
                                        <div class="w-full rounded-t-lg bg-gradient-to-t from-teal-600 to-emerald-400 transition-all duration-300 group-hover:from-teal-700 group-hover:to-emerald-300"
                                             style="height: {{ max(6, round(($day['count'] / $maxCount) * 100)) }}%"
                                             title="{{ $day['date'] }}: {{ $day['count'] }} pesanan, Rp {{ number_format($day['total'], 0, ',', '.') }}">
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400">{{ $day['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Status Breakdown --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
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
                                        <div class="h-full rounded-full {{ $item['color'] }}"
                                             style="width: {{ round(($item['value'] / $totalStatus) * 100) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Recent Orders --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                        <div>
                            <h3 class="font-bold text-slate-900">Pesanan Terbaru</h3>
                            <p class="mt-1 text-sm text-slate-500">6 pesanan terakhir yang masuk.</p>
                        </div>
                        <a href="{{ route('admin.orders.index') }}"
                           class="text-sm font-semibold text-teal-600 transition hover:text-teal-700">
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
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-slate-900 hover:text-teal-600">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">{{ $order->room?->room_number ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold
                                                {{ $order->order_status === 'completed' ? 'border-teal-200 bg-teal-50 text-teal-700' : ($order->order_status === 'cancelled' ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-slate-200 bg-slate-50 text-slate-700') }}">
                                                {{ $order->order_status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold
                                                {{ $order->payment_status === 'paid' ? 'border-teal-200 bg-teal-50 text-teal-700' : 'border-orange-200 bg-orange-50 text-orange-700' }}">
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
