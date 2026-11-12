@php
    $user = Auth::user();

    $menus = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'roles' => ['superadmin', 'admin', 'cashier', 'kitchen'],
        ],
        [
            'label' => 'Manajemen Pengguna',
            'route' => 'admin.users.index',
            'roles' => ['superadmin'],
        ],
        [
            'label' => 'Pesanan',
            'route' => 'admin.orders.index',
            'match' => 'admin.orders.*',
            'roles' => ['superadmin', 'admin', 'cashier', 'kitchen'],
        ],
        [
            'label' => 'Kasir & Pembayaran',
            'route' => 'admin.cashier.index',
            'match' => 'admin.cashier.*',
            'roles' => ['superadmin', 'admin', 'cashier'],
        ],
        [
            'label' => 'Dapur',
            'route' => 'admin.kitchen.index',
            'match' => 'admin.kitchen.*',
            'roles' => ['superadmin', 'admin', 'kitchen'],
        ],
        [
            'label' => 'Produk & Kategori',
            'route' => 'admin.products.index',
            'match' => ['admin.products.*', 'admin.categories.*'],
            'roles' => ['superadmin', 'admin'],
        ],
        [
            'label' => 'Laporan Penjualan',
            'route' => 'admin.reports.index',
            'match' => 'admin.reports.*',
            'roles' => ['superadmin', 'admin'],
        ],
        [
            'label' => 'Inventori',
            'route' => 'admin.inventory.index',
            'match' => 'admin.inventory.*',
            'roles' => ['superadmin', 'admin'],
        ],
        [
            'label' => 'Pengaturan',
            'route' => 'admin.settings.index',
            'match' => ['admin.settings.*', 'admin.rooms.*'],
            'roles' => ['superadmin', 'admin'],
        ],
    ];

    $role = $user?->getRoleNames()->first();
@endphp

<div
    x-data="{
        open: false,
        show() {
            this.open = true;
            document.body.classList.add('overflow-hidden');
        },
        close() {
            this.open = false;
            document.body.classList.remove('overflow-hidden');
        },
        toggle() {
            this.open ? this.close() : this.show();
        },
    }"
    @toggle-sidebar.window="toggle()"
    @keydown.escape.window="close()"
    @resize.window="if (window.innerWidth >= 768) close()"
>
    {{-- Overlay untuk layar kecil --}}
    <div
        x-cloak
        x-show="open"
        x-transition.opacity.duration.220ms
        class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm md:hidden"
        @click="close()"
        aria-hidden="true"
    ></div>

    <aside
        :class="{ 'is-open': open }"
        class="mobile-drawer fixed inset-y-0 left-0 z-50 flex w-72 shrink-0 flex-col overflow-y-auto border-r border-black/10 bg-white p-6 shadow-2xl shadow-slate-900/10 md:z-auto md:w-64 md:shadow-none"
        aria-label="Menu navigasi utama"
    >
        <div class="mb-10 flex items-center justify-between gap-3">
            <a href="{{ route('dashboard') }}"
               class="font-display text-2xl font-semibold tracking-tight text-teal">
                Ngoffe<span class="text-charcoal">.</span>
            </a>

            <button
                type="button"
                class="pressable flex h-9 w-9 items-center justify-center rounded-lg border border-black/10 text-gray-500 hover:bg-gray-50 md:hidden"
                @click="close()"
                aria-label="Tutup menu"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-gray-400">
            Workspace
        </p>

        <nav class="space-y-2">
            @foreach ($menus as $menu)
                @if ($role && in_array($role, $menu['roles'], true))
                    @php
                        $isActive = $menu['route'] && request()->routeIs($menu['match'] ?? $menu['route']);
                    @endphp

                    <a
                        href="{{ $menu['route'] ? route($menu['route']) : '#' }}"
                        @class([
                            'sidebar-link flex items-center rounded-xl px-4 py-3 text-sm font-semibold',
                            'active' => $isActive,
                            'text-gray-600' => !$isActive,
                            'cursor-not-allowed opacity-60' => !$menu['route'],
                        ])
                        @if ($isActive) aria-current="page" @endif
                        @if (!$menu['route']) aria-disabled="true" title="Halaman belum tersedia" @endif
                    >
                        {{ $menu['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="mt-auto border-t border-black/10 pt-5">
            <p class="truncate font-semibold text-charcoal">
                {{ $user?->name ?? 'User' }}
            </p>

            <p class="mt-1 text-sm capitalize text-gray-500">
                {{ $role ?? 'No Role' }}
            </p>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf

                <button type="submit"
                        class="pressable w-full rounded-xl border border-black/10 px-4 py-2 text-left text-sm hover:bg-gray-50">
                    Keluar
                </button>
            </form>
        </div>
    </aside>
</div>
