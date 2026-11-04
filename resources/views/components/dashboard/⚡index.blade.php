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
                'value' => Order::whereIn('order_status', ['pending', 'confirmed', 'preparing'])->count(),
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

            <section class="p-6 md:p-10">
                <div class="mb-8">
                    <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase">
                        Selamat datang kembali,
                    </p>
                    <h1 class="mt-1 text-3xl font-bold text-slate-900 tracking-tight">
                        {{ $this->userName }}.
                    </h1>
                    <p class="mt-2 text-sm text-slate-600 max-w-2xl leading-7">
                        Kelola operasional Ngoffe ID dari satu tempat. Pilih modul di bawah atau gunakan sidebar.
                    </p>
                </div>

                {{-- Quick Access Stats Cards --}}
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4 mb-8">
                    @forelse ($this->stats as $stat)
                        <a
                            href="{{ route($stat['route']) }}"
                            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:border-teal-500/50 hover:bg-slate-50 transition-all duration-200"
                        >
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider group-hover:text-teal-600 transition">
                                        {{ $stat['label'] }}
                                    </p>
                                    <p class="mt-0.5 text-xs text-slate-400 group-hover:text-slate-600 transition">
                                        {{ $stat['description'] }}
                                    </p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center group-hover:bg-teal-100 transition-colors">
                                    <span class="text-teal-600 text-xl group-hover:text-teal-700 transition-colors">
                                        ●
                                    </span>
                                </div>
                            </div>
                            <p class="text-2xl font-bold text-slate-900 group-hover:text-teal-600 transition-colors">
                                {{ $stat['value'] }}
                            </p>
                        </a>
                    @empty
                        <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                            </svg>
                            <p class="text-sm font-medium text-slate-900">Tidak ada modul tersedia</p>
                            <p class="text-sm text-slate-500 mt-1">Tidak ada modul yang tersedia untuk role Anda.</p>
                        </div>
                    @endforelse
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <h3 class="text-lg font-bold text-slate-900">Langkah Berikutnya</h3>
                    <p class="mt-2 leading-7 text-slate-600">
                        Dashboard awal sudah siap. Kartu di atas adalah pintas masuk ke manajemen pengguna,
                        pesanan, kasir &amp; pembayaran, dan dapur sesuai role Anda.
                    </p>
                </div>
            </section>
        </main>
    </div>
</div>
