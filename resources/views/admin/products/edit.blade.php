<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-5xl space-y-8">

            <header class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-600">Katalog</p>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Produk</h1>
                    <p class="mt-1 text-sm text-slate-500">Perbarui informasi {{ $product->name }}.</p>
                </div>
                <a href="{{ route('admin.products.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    &larr; Kembali
                </a>
            </header>

            @include('admin.products.form', ['product' => $product, 'categories' => $categories])

        </div>
    </main>
</x-layouts::app>
