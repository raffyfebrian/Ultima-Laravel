<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <header class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-600">Katalog</p>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Produk &amp; Menu</h1>
                    <p class="mt-1 text-sm text-slate-500">Kelola menu, harga, foto, dan ketersediaan produk.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.categories.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                        Kategori
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                        &larr; Kembali
                    </a>
                    <a href="{{ route('admin.products.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition hover:-translate-y-0.5 hover:from-teal-600 hover:to-emerald-700 hover:shadow-xl">
                        + Tambah Produk
                    </a>
                </div>
            </header>

            @if (session('success'))
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white">&check;</span>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-rose-500 text-white">!</span>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Produk</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $totalProducts }}</p>
                    <p class="mt-1 text-xs text-slate-500">Seluruh menu terdaftar</p>
                </div>
                <div class="rounded-2xl border border-teal-200 bg-teal-50/60 p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-700">Tersedia</p>
                    <p class="mt-2 text-2xl font-bold text-teal-900">{{ $availableProducts }}</p>
                    <p class="mt-1 text-xs text-teal-700">Bisa dipesan tamu</p>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Tidak Tersedia</p>
                    <p class="mt-2 text-2xl font-bold text-amber-900">{{ $unavailableProducts }}</p>
                    <p class="mt-1 text-xs text-amber-700">Disembunyikan dari tamu</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kategori</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $totalCategories }}</p>
                    <p class="mt-1 text-xs text-slate-500">Kelompok menu aktif</p>
                </div>
            </div>

            {{-- Filters --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="GET" class="grid items-end gap-4 md:grid-cols-4">
                    <div>
                        <label for="q" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Cari Produk</label>
                        <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Nama produk..."
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label for="category" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Kategori</label>
                        <select id="category" name="category"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                            <option value="">Semua kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="availability" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Ketersediaan</label>
                        <select id="availability" name="availability"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                            <option value="">Semua status</option>
                            <option value="available" @selected(request('availability') === 'available')>Tersedia</option>
                            <option value="unavailable" @selected(request('availability') === 'unavailable')>Tidak Tersedia</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                            Filter
                        </button>
                        <a href="{{ route('admin.products.index') }}"
                           class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Reset
                        </a>
                    </div>
                </form>
            </section>

            {{-- Product Grid --}}
            @if ($products->isEmpty())
                <section class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-20 text-center shadow-sm">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-50">
                        <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <p class="mt-4 font-medium text-slate-900">Belum ada produk yang cocok</p>
                    <p class="mt-1 text-sm text-slate-500">Ubah filter atau tambahkan produk baru.</p>
                    <a href="{{ route('admin.products.create') }}"
                       class="mt-5 inline-flex rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700">
                        Tambah Produk
                    </a>
                </section>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                            <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                                @if ($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}"
                                         alt="{{ $product->name }}"
                                         class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                        <svg class="h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif

                                <span class="absolute left-3 top-3 inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold shadow-sm
                                    {{ $product->is_available ? 'border-teal-200 bg-teal-50/95 text-teal-700' : 'border-rose-200 bg-rose-50/95 text-rose-700' }}">
                                    {{ $product->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>

                            <div class="flex flex-1 flex-col p-5">
                                <p class="text-xs font-semibold uppercase tracking-wider text-teal-600">
                                    {{ $product->category?->name ?? 'Tanpa Kategori' }}
                                </p>
                                <h3 class="mt-1 font-bold text-slate-900">{{ $product->name }}</h3>
                                <p class="mt-1 line-clamp-2 text-sm text-slate-500">
                                    {{ $product->description ?: 'Belum ada deskripsi.' }}
                                </p>

                                <div class="mt-auto flex items-end justify-between pt-4">
                                    <p class="text-lg font-bold text-slate-900">
                                        Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                                    </p>
                                    <span class="text-xs text-slate-400">Urutan {{ $product->sort_order }}</span>
                                </div>

                                <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700">
                                            {{ $product->is_available ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="ml-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100"
                                                onclick="return confirm('Hapus produk {{ $product->name }}?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($products->hasPages())
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
                        <p class="text-sm text-slate-500">
                            Menampilkan {{ $products->firstItem() }}&ndash;{{ $products->lastItem() }} dari {{ $products->total() }} produk
                        </p>
                        {{ $products->links() }}
                    </div>
                @endif
            @endif

        </div>
    </main>
</x-layouts::app>
