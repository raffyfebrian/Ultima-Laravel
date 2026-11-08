
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
            'match' => 'admin.products.*|admin.categories.*',
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
    ];

    $role = $user?->getRoleNames()->first();
@endphp

<aside class="hidden w-64 shrink-0 border-r border-black/10 bg-white p-6 md:flex md:flex-col">
    <a href="{{ route('dashboard') }}"
       class="mb-10 font-display text-2xl font-semibold tracking-tight text-teal">
        Ngoffe<span class="text-charcoal">.</span>
    </a>

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
                        'flex items-center rounded-xl px-4 py-3 text-sm font-semibold transition',
                        'bg-teal/10 text-teal' => $isActive,
                        'text-gray-600 hover:bg-gray-100' => !$isActive,
                        'cursor-not-allowed opacity-60' => !$menu['route'],
                    ])
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
                    class="w-full rounded-xl border border-black/10 px-4 py-2 text-left text-sm transition hover:bg-gray-50">
                Keluar
            </button>
        </form>
    </div>
</aside>
