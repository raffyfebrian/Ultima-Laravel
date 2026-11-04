<x-layouts::app>
    <main class="min-h-screen bg-cream px-6 py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Page Header --}}
            <header>
                <p class="text-sm font-medium text-teal">
                    ADMINISTRATION
                </p>

                <h1 class="mt-2 font-display text-3xl font-semibold text-charcoal">
                    Inventori Bahan Baku
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Pantau jumlah stok bahan baku yang tersedia.
                </p>
            </header>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="rounded-xl border border-teal/20 bg-white px-4 py-3 text-sm text-teal">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="mb-2 font-semibold">
                        Periksa kembali data yang dimasukkan:
                    </p>

                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Add Ingredient --}}
            <section class="rounded-2xl border border-black/5 bg-white p-6 shadow-sm">
                <h2 class="font-display text-xl font-semibold text-charcoal">
                    Tambah Bahan Baku
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Daftarkan bahan baku beserta satuan pengukurannya.
                </p>

                <form
                    action="{{ route('admin.inventory.store') }}"
                    method="POST"
                    class="mt-5 grid gap-4 md:grid-cols-2"
                >
                    @csrf

                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-charcoal">
                            Nama Bahan
                        </label>

                        <input
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            maxlength="150"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm"
                            placeholder="Contoh: Susu UHT"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit" class="mb-2 block text-sm font-medium text-charcoal">
                            Satuan
                        </label>

                        <select
                            id="unit"
                            name="unit"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm"
                        >
                            <option value="">Pilih satuan</option>

                            @foreach (['gram', 'kg', 'ml', 'liter', 'pcs'] as $unit)
                                <option
                                    value="{{ $unit }}"
                                    @selected(old('unit') === $unit)
                                >
                                    {{ $unit }}
                                </option>
                            @endforeach
                        </select>

                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="mb-2 block text-sm font-medium text-charcoal">
                            Deskripsi (Opsional)
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            maxlength="1000"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm"
                            placeholder="Keterangan bahan baku..."
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <button
                            type="submit"
                            class="rounded-xl bg-teal px-5 py-3 text-sm font-semibold text-white hover:bg-teal/90"
                        >
                            Simpan Bahan
                        </button>
                    </div>
                </form>
            </section>

            {{-- Receive Stock --}}
            <section class="rounded-2xl border border-black/5 bg-white p-6 shadow-sm">
                <h2 class="font-display text-xl font-semibold text-charcoal">
                    Stok Masuk
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Catat stok baru yang diterima. Jumlah akan ditambahkan
                    ke total stok bahan baku secara otomatis.
                </p>

                <form
                    action="{{ route('admin.inventory.stock-in') }}"
                    method="POST"
                    class="mt-5 grid gap-4 md:grid-cols-2"
                >
                    @csrf

                    <div>
                        <label for="ingredient_id" class="mb-2 block text-sm font-medium text-charcoal">
                            Bahan Baku
                        </label>

                        <select
                            id="ingredient_id"
                            name="ingredient_id"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm"
                        >
                            <option value="">Pilih bahan baku</option>

                            @foreach ($ingredients as $ingredient)
                                <option
                                    value="{{ $ingredient->id }}"
                                    @selected(old('ingredient_id') == $ingredient->id)
                                >
                                    {{ $ingredient->name }} ({{ $ingredient->unit }})
                                </option>
                            @endforeach
                        </select>

                        @error('ingredient_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quantity" class="mb-2 block text-sm font-medium text-charcoal">
                            Jumlah Stok Masuk
                        </label>

                        <input
                            id="quantity"
                            name="quantity"
                            type="number"
                            min="0.001"
                            step="0.001"
                            value="{{ old('quantity') }}"
                            required
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm"
                            placeholder="Contoh: 1000"
                        >

                        <p class="mt-1 text-xs text-gray-500">
                            Masukkan jumlah sesuai satuan bahan yang dipilih.
                        </p>

                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="batch_number" class="mb-2 block text-sm font-medium text-charcoal">
                            Nomor Batch (Opsional)
                        </label>

                        <input
                            id="batch_number"
                            name="batch_number"
                            type="text"
                            maxlength="100"
                            value="{{ old('batch_number') }}"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm"
                            placeholder="Contoh: BATCH-2026-001"
                        >

                        @error('batch_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expires_at" class="mb-2 block text-sm font-medium text-charcoal">
                            Tanggal Kedaluwarsa (Opsional)
                        </label>

                        <input
                            id="expires_at"
                            name="expires_at"
                            type="date"
                            min="{{ now()->toDateString() }}"
                            value="{{ old('expires_at') }}"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm"
                        >

                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="mb-2 block text-sm font-medium text-charcoal">
                            Catatan (Opsional)
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="2"
                            maxlength="1000"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm"
                            placeholder="Contoh: Pembelian dari supplier..."
                        >{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <button
                            type="submit"
                            class="rounded-xl bg-teal px-5 py-3 text-sm font-semibold text-white hover:bg-teal/90"
                        >
                            Simpan Stok Masuk
                        </button>
                    </div>
                </form>
            </section>

            {{-- Ingredient Inventory Table --}}
            <section class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
                <div class="border-b border-black/5 px-6 py-5">
                    <h2 class="font-semibold text-charcoal">
                        Daftar Bahan Baku
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Total stok dihitung dari seluruh batch setiap bahan.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-cream/70 text-charcoal">
                            <tr>
                                <th class="px-6 py-4 font-semibold">
                                    Bahan Baku
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Satuan
                                </th>

                                <th class="px-6 py-4 text-right font-semibold">
                                    Total Stok
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($ingredients as $ingredient)
                                <tr class="transition hover:bg-cream/30">
                                    <td class="px-6 py-4 font-medium text-charcoal">
                                        {{ $ingredient->name }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $ingredient->unit }}
                                    </td>

                                    <td class="px-6 py-4 text-right tabular-nums text-charcoal">
                                       {{ rtrim(rtrim(number_format((float) ($ingredient->total_stock ?? 0), 3, ',', '.'), '0'), ',') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <p class="font-medium text-charcoal">
                                            Belum ada bahan baku
                                        </p>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Data bahan baku akan tampil di sini setelah ditambahkan.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($ingredients->hasPages())
                    <div class="border-t border-black/5 px-6 py-4">
                        {{ $ingredients->links() }}
                    </div>
                @endif
            </section>

        </div>
    </main>
</x-layouts::app>