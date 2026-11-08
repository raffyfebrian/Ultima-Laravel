@php
    $editing = isset($product);
    $currentImage = $editing && $product->image_path ? Storage::url($product->image_path) : null;
@endphp

<form method="POST"
      action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}"
      enctype="multipart/form-data"
      class="space-y-8">
    @csrf
    @if ($editing)
        @method('PATCH')
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

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main fields --}}
        <section class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
            <div>
                <h2 class="font-bold text-slate-900">Informasi Produk</h2>
                <p class="mt-1 text-sm text-slate-500">Data utama yang tampil di halaman pemesanan tamu.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Nama Produk</label>
                    <input type="text" id="name" name="name" required maxlength="150"
                           value="{{ old('name', $product->name ?? '') }}"
                           placeholder="Contoh: Es Kopi Susu Gula Aren"
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label for="category_id" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Kategori</label>
                    <select id="category_id" name="category_id" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="price" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Harga (Rp)</label>
                    <input type="number" id="price" name="price" required min="0" max="999999999" step="1"
                           value="{{ old('price', $product->price ?? '') }}"
                           placeholder="25000"
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Deskripsi <span class="font-normal normal-case text-slate-400">(opsional)</span></label>
                    <textarea id="description" name="description" rows="4" maxlength="1000"
                              placeholder="Bahan, rasa, atau catatan penyajian..."
                              class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                <div>
                    <label for="sort_order" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Urutan Tampil</label>
                    <input type="number" id="sort_order" name="sort_order" min="0" max="9999"
                           value="{{ old('sort_order', $product->sort_order ?? 0) }}"
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="flex items-end">
                    <label class="inline-flex w-full cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3">
                        <input type="checkbox" name="is_available" value="1"
                               @checked(old('is_available', $product->is_available ?? true))
                               class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        <span class="text-sm font-medium text-slate-700">Produk tersedia</span>
                    </label>
                </div>
            </div>
        </section>

        {{-- Image --}}
        <section class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <h2 class="font-bold text-slate-900">Foto Produk</h2>
                <p class="mt-1 text-sm text-slate-500">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
            </div>

            <div class="overflow-hidden rounded-xl border border-dashed border-slate-300 bg-slate-50">
                <div id="image-placeholder" class="flex aspect-square items-center justify-center {{ $currentImage ? 'hidden' : '' }}">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-xs text-slate-400">Belum ada foto</p>
                    </div>
                </div>
                <img id="image-preview"
                     src="{{ $currentImage ?? '' }}"
                     alt="Pratinjau foto produk"
                     class="aspect-square w-full object-cover {{ $currentImage ? '' : 'hidden' }}">
            </div>

            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
                   class="block w-full cursor-pointer rounded-xl border border-slate-200 bg-white text-sm text-slate-500 file:mr-4 file:rounded-l-xl file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">

            @if ($editing && $product->image_path)
                <p class="text-xs text-slate-500">Biarkan kosong jika tidak ingin mengganti foto.</p>
            @endif
        </section>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
        <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-slate-600 transition hover:text-slate-900">
            &larr; Kembali ke daftar produk
        </a>
        <button type="submit"
                class="rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition hover:-translate-y-0.5 hover:from-teal-600 hover:to-emerald-700 hover:shadow-xl">
            {{ $editing ? 'Simpan Perubahan' : 'Simpan Produk' }}
        </button>
    </div>
</form>

<script>
    (function () {
        const input = document.getElementById('image');
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('image-placeholder');

        if (!input || !preview) return;

        input.addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (loadEvent) {
                preview.src = loadEvent.target.result;
                preview.classList.remove('hidden');
                placeholder?.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        });
    })();
</script>
