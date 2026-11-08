@php
    $editing = isset($category);
@endphp

<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-6xl space-y-8">

            {{-- Header --}}
            <header class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-600">Katalog</p>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Kategori Produk</h1>
                    <p class="mt-1 text-sm text-slate-500">Kelompokkan menu agar mudah dikelola dan dicari.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.products.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                        Produk
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                        &larr; Kembali
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

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800">
                    <p class="font-semibold">Periksa kembali data berikut:</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid gap-5 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Kategori</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $totalCategories }}</p>
                    <p class="mt-1 text-xs text-slate-500">Seluruh kategori terdaftar</p>
                </div>
                <div class="rounded-2xl border border-teal-200 bg-teal-50/60 p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-700">Kategori Aktif</p>
                    <p class="mt-2 text-2xl font-bold text-teal-900">{{ $activeCategories }}</p>
                    <p class="mt-1 text-xs text-teal-700">Tampil di halaman tamu</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Produk</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $totalProducts }}</p>
                    <p class="mt-1 text-xs text-slate-500">Tersebar di semua kategori</p>
                </div>
            </div>

            {{-- Form --}}
            <section class="rounded-2xl border {{ $editing ? 'border-teal-200 bg-teal-50/40' : 'border-slate-200 bg-white' }} p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-slate-900">
                            {{ $editing ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $editing ? 'Perbarui nama, deskripsi, atau status kategori.' : 'Buat kelompok menu baru untuk produk kafe.' }}
                        </p>
                    </div>
                    @if ($editing)
                        <a href="{{ route('admin.categories.index') }}"
                           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            Batal Edit
                        </a>
                    @endif
                </div>

                <form method="POST"
                      action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
                      class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @csrf
                    @if ($editing)
                        @method('PATCH')
                    @endif

                    <div class="lg:col-span-2">
                        <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Nama Kategori</label>
                        <input type="text" id="name" name="name" required maxlength="100"
                               value="{{ old('name', $category->name ?? '') }}"
                               placeholder="Contoh: Kopi Signature"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div class="lg:col-span-2">
                        <label for="description" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Deskripsi <span class="font-normal normal-case text-slate-400">(opsional)</span></label>
                        <input type="text" id="description" name="description" maxlength="500"
                               value="{{ old('description', $category->description ?? '') }}"
                               placeholder="Deskripsi singkat kategori"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label for="sort_order" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Urutan</label>
                        <input type="number" id="sort_order" name="sort_order" min="0" max="9999"
                               value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div class="flex items-end">
                        <label class="inline-flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3">
                            <input type="checkbox" name="is_active" value="1"
                                   @checked(old('is_active', $category->is_active ?? true))
                                   class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                            <span class="text-sm font-medium text-slate-700">Kategori aktif</span>
                        </label>
                    </div>

                    <div class="flex items-end sm:col-span-2">
                        <button type="submit"
                                class="rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition hover:-translate-y-0.5 hover:from-teal-600 hover:to-emerald-700 hover:shadow-xl">
                            {{ $editing ? 'Simpan Perubahan' : 'Simpan Kategori' }}
                        </button>
                    </div>
                </form>
            </section>

            {{-- Table --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 class="font-bold text-slate-900">Daftar Kategori</h2>
                        <p class="mt-1 text-sm text-slate-500">Urutan tampil mengikuti kolom urutan.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">
                        {{ $categories->count() }} Kategori
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Nama</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Deskripsi</th>
                                <th class="px-6 py-4 text-center font-medium uppercase tracking-wider text-slate-500">Produk</th>
                                <th class="px-6 py-4 text-center font-medium uppercase tracking-wider text-slate-500">Urutan</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($categories as $item)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-6 py-4 font-medium text-slate-900">{{ $item->name }}</td>
                                    <td class="max-w-xs px-6 py-4 text-slate-600">
                                        {{ $item->description ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center tabular-nums text-slate-600">{{ $item->products_count }}</td>
                                    <td class="px-6 py-4 text-center tabular-nums text-slate-600">{{ $item->sort_order }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold
                                            {{ $item->is_active ? 'border-teal-200 bg-teal-50 text-teal-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.categories.toggle', $item) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700">
                                                    {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>

                                            <a href="{{ route('admin.categories.edit', $item) }}"
                                               class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('admin.categories.destroy', $item) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100"
                                                        onclick="return confirm('Hapus kategori {{ $item->name }}?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <p class="font-medium text-slate-900">Belum ada kategori</p>
                                        <p class="mt-1 text-sm text-slate-500">Tambahkan kategori pertama lewat form di atas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>
</x-layouts::app>
