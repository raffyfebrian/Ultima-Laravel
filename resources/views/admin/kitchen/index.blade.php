<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Page Header --}}
            <header class="dashboard-enter relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900 p-6 text-white shadow-2xl shadow-teal-900/20 md:p-8">
                <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-teal-400/20 blur-3xl"></div>
                <div class="absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-400/10 blur-3xl"></div>
                <div class="absolute inset-x-8 bottom-0 h-px bg-gradient-to-r from-transparent via-ngofee-mint/40 to-transparent"></div>
                <div class="relative flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-300">Kitchen Display</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight">Manajemen Dapur</h1>
                        <p class="mt-2 max-w-xl text-sm text-slate-300">
                            Pantau antrian pesanan, mulai proses, dan tandai pesanan siap disajikan.
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.kitchen.index') }}"
                           class="pressable inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur hover:bg-white/20">
                            Muat Ulang
                        </a>
                        <a href="{{ route('dashboard') }}"
                           class="pressable inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 hover:bg-teal-50">
                            &larr; Kembali
                        </a>
                    </div>
                </div>
            </header>

            @if (session('success'))
                <div class="dashboard-enter flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white">&check;</span>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="dashboard-enter flex items-center gap-3 rounded-2xl border border-sky-200 bg-sky-50 px-5 py-4 text-sm font-medium text-sky-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-500 text-white">i</span>
                    {{ session('info') }}
                </div>
            @endif

            {{-- Summary --}}
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay: 60ms">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Antrian Masuk</p>
                        <span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span>
                    </div>
                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        <span data-countup="{{ $pendingCount }}" data-countup-key="kitchen-pending">{{ number_format($pendingCount, 0, ',', '.') }}</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Pesanan baru &amp; terkonfirmasi</p>
                </div>
                <div class="reveal rounded-2xl border border-amber-200 bg-amber-50/60 p-6 shadow-sm" style="--reveal-delay: 120ms">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Sedang Diproses</p>
                        <span class="warning-pulse h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                    </div>
                    <p class="mt-2 text-3xl font-bold text-amber-900">
                        <span data-countup="{{ $preparingCount }}" data-countup-key="kitchen-preparing">{{ number_format($preparingCount, 0, ',', '.') }}</span>
                    </p>
                    <p class="mt-1 text-xs text-amber-700">Pesanan di dapur</p>
                </div>
                <div class="reveal rounded-2xl border border-teal-200 bg-teal-50/60 p-6 shadow-sm" style="--reveal-delay: 180ms">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wider text-teal-700">Siap Disajikan</p>
                        <span class="h-2.5 w-2.5 rounded-full bg-teal-500"></span>
                    </div>
                    <p class="mt-2 text-3xl font-bold text-teal-900">
                        <span data-countup="{{ $readyCount }}" data-countup-key="kitchen-ready">{{ number_format($readyCount, 0, ',', '.') }}</span>
                    </p>
                    <p class="mt-1 text-xs text-teal-700">Menunggu diambil pelayan</p>
                </div>
                <div class="reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay: 240ms">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Selesai Hari Ini</p>
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    </div>
                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        <span data-countup="{{ $completedToday }}" data-countup-key="kitchen-completed">{{ number_format($completedToday, 0, ',', '.') }}</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Pesanan tuntas hari ini</p>
                </div>
            </div>

            {{-- Filter Tabs --}}
            <div class="flex flex-wrap items-center gap-2">
                @php
                    $tabs = [
                        '' => 'Semua',
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'preparing' => 'Diproses',
                        'ready' => 'Siap Disajikan',
                    ];
                @endphp
                @foreach ($tabs as $value => $label)
                    <a href="{{ route('admin.kitchen.index', array_filter(['status' => $value])) }}"
                       class="pressable rounded-full border px-4 py-2 text-sm font-semibold
                           {{ request('status', '') === $value
                               ? 'border-teal-600 bg-teal-600 text-white shadow-lg shadow-teal-900/20'
                               : 'border-slate-200 bg-white text-slate-600 hover:border-ngofee-border hover:bg-ngofee-bg hover:text-ngofee-dark' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Order Cards --}}
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($orders as $order)
                    @php
                        $statusStyles = match ($order->order_status) {
                            'preparing' => ['border-amber-300', 'bg-amber-100 text-amber-800', 'bg-amber-500'],
                            'ready' => ['border-teal-300', 'bg-teal-100 text-teal-800', 'bg-teal-500'],
                            'confirmed' => ['border-sky-300', 'bg-sky-100 text-sky-800', 'bg-sky-500'],
                            default => ['border-slate-200', 'bg-slate-100 text-slate-700', 'bg-slate-400'],
                        };
                        $action = match ($order->order_status) {
                            'pending' => ['Konfirmasi Pesanan', 'from-sky-500 to-blue-600'],
                            'confirmed' => ['Mulai Proses', 'from-amber-500 to-orange-600'],
                            'preparing' => ['Siap Disajikan', 'from-teal-500 to-emerald-600'],
                            'ready' => ['Selesaikan', 'from-slate-700 to-slate-900'],
                            default => [null, null],
                        };
                    @endphp

                    <article class="reveal flex flex-col overflow-hidden rounded-2xl border-2 {{ $statusStyles[0] }} bg-white shadow-sm transition hover:shadow-xl {{ $order->order_status === 'ready' ? 'kitchen-pulse' : '' }} {{ $order->order_status === 'pending' ? 'kitchen-flash' : '' }}"
                             style="--reveal-delay: {{ min($loop->index, 8) * 60 }}ms">
                        <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-5 py-4">
                            <div>
                                <p class="font-mono text-sm font-bold text-slate-900">{{ $order->order_number }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    Kamar {{ $order->room?->room_number ?? '-' }}
                                    &middot; {{ $order->placed_at?->diffForHumans() }}
                                </p>
                            </div>
                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold {{ $statusStyles[1] }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $statusStyles[2] }}"></span>
                                {{ $order->order_status_label }}
                            </span>
                        </div>

                        <div class="flex-1 space-y-2 px-5 py-4">
                            @forelse ($order->items as $item)
                                <div class="flex items-start justify-between gap-3 text-sm">
                                    <div class="flex items-start gap-2">
                                        <span class="mt-0.5 inline-flex h-5 min-w-5 items-center justify-center rounded-md bg-slate-100 px-1 text-xs font-bold text-slate-700">
                                            {{ $item->quantity }}x
                                        </span>
                                        <span class="text-slate-700">
                                            {{ $item->product_name }}
                                            @if ($item->notes)
                                                <span class="mt-0.5 block text-xs font-normal text-amber-700">
                                                    Catatan: {{ $item->notes }}
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                    <span class="shrink-0 tabular-nums text-slate-500">
                                        Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-slate-400">Tidak ada item.</p>
                            @endforelse
                        </div>

                        <div class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/60 px-5 py-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400">Total</p>
                                <p class="font-bold tabular-nums text-slate-900">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                            </div>

                            @if ($action[0])
                                <form method="POST" action="{{ route('admin.kitchen.advance', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="pressable rounded-xl bg-gradient-to-r {{ $action[1] }} px-4 py-2.5 text-sm font-semibold text-white shadow-lg hover:shadow-xl">
                                        {{ $action[0] }} &rarr;
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-semibold text-slate-400">Tidak ada aksi</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-16 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-teal-50">
                            <svg class="h-10 w-10 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="mt-4 text-lg font-semibold text-slate-900">Tidak ada antrian dapur</p>
                        <p class="mt-1 text-sm text-slate-500">Semua pesanan sudah diproses. Antrian baru akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            @if ($orders->hasPages())
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-6 py-4">
                    <p class="text-sm text-slate-500">
                        Menampilkan {{ $orders->firstItem() }}&ndash;{{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
                    </p>
                    {{ $orders->links() }}
                </div>
            @endif

        </div>
    </main>
</x-layouts::app>
