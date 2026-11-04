
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#FFF9ED">
    <title>Menu Ngoffe ID — Kamar {{ $room->room_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream text-charcoal">
    <main class="mx-auto max-w-4xl px-5 py-8 md:py-12">
        <header class="mb-10">
            <p class="font-display text-2xl font-semibold text-teal">
                Ngoffe<span class="text-charcoal">.</span>
            </p>

            <div class="mt-8 rounded-2xl border border-black/5 bg-white p-6 shadow-sm md:p-8">
                <p class="text-sm text-gray-500">Selamat datang di Ngoffe ID</p>

                <h1 class="mt-2 font-display text-3xl font-semibold">
                    Kamar {{ $room->room_number }}
                </h1>

                <p class="mt-3 max-w-xl leading-7 text-gray-600">
                    Temukan menu favoritmu dan nikmati pesanan langsung dari kamar.
                </p>
            </div>
        </header>

        <section aria-labelledby="menu-heading">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal">
                    Our Menu
                </p>

                <h2 id="menu-heading" class="mt-2 font-display text-2xl font-semibold">
                    Menu Ngoffe ID
                </h2>

                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Pilih menu favoritmu dari daftar berikut.
                </p>
            </div>

            @forelse ($categories as $category)
                <section class="mb-8" aria-labelledby="category-{{ $category->id }}">
                    <h3
                        id="category-{{ $category->id }}"
                        class="mb-4 font-display text-xl font-semibold"
                    >
                        {{ $category->name }}
                    </h3>

                    @if ($category->description)
                        <p class="mb-4 text-sm leading-6 text-gray-500">
                            {{ $category->description }}
                        </p>
                    @endif

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach ($category->products as $product)
                            <article class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                                @if ($product->image_path)
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::url($product->image_path) }}"
                                        alt="{{ $product->name }}"
                                        loading="lazy"
                                        class="h-48 w-full object-cover"
                                    >
                                @else
                                    <div class="flex h-32 items-center justify-center bg-teal/5">
                                        <span class="font-display text-3xl font-semibold text-teal/50">
                                            Ngoffe.
                                        </span>
                                    </div>
                                @endif

                                <div class="p-5">
                                    <h4 class="font-semibold">
                                        {{ $product->name }}
                                    </h4>

                                    @if ($product->description)
                                        <p class="mt-2 text-sm leading-6 text-gray-500">
                                            {{ $product->description }}
                                        </p>
                                    @endif

                                    <p class="mt-4 font-semibold text-teal">
                                        Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                                    </p>
                                    

                                <button
                                    type="button"
                                    x-data
                                    @click="$dispatch('add-to-cart', { productId: {{ $product->id }} })"
                                    class="mt-4 w-full rounded-xl bg-teal px-4 py-3 text-sm font-semibold text-white transition hover:bg-teal/90"
                                >
                                    + Tambah ke Keranjang
                                </button>


                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-2xl border border-dashed border-black/10 bg-white p-8 text-center">
                    <h3 class="font-display text-xl font-semibold">
                        Menu sedang disiapkan
                    </h3>

                    <p class="mt-3 text-sm leading-6 text-gray-500">
                        Belum ada menu yang tersedia saat ini. Silakan coba kembali nanti.
                    </p>
                </div>
            @endforelse
        </section>

        <footer class="mt-12 border-t border-black/5 pt-6 text-center text-xs text-gray-400">
            © {{ date('Y') }} Ngoffe ID
        </footer>
    </main>
        <livewire:guest.cart />
</body>
</html>
