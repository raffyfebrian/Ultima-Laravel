<?php

use App\Models\Product;
use Livewire\Component;
use App\Services\Orders\CreateOrderService;
use Illuminate\Validation\ValidationException;

new class extends Component {
    #[\Livewire\Attributes\On('add-to-cart')]
    public function addToCart(int $productId): void
    {
    $this->add($productId);
    }
    public array $items = [];

    public bool $isOpen = false;
    public string $paymentMethod = 'cash';

    public bool $isCheckingOut = false;

    public ?string $orderNumber = null;

public function checkout(CreateOrderService $service): void
{
    if ($this->isCheckingOut) {
        return;
    }

    $this->validate([
        'paymentMethod' => ['required', 'in:cash,qris'],
    ]);

    $roomId = session('guest_room_id');

    if (! $roomId) {
        throw ValidationException::withMessages([
            'checkout' => 'Sesi kamar tidak ditemukan. Silakan scan ulang QR kamar.',
        ]);
    }

    $cartItems = collect($this->items)
        ->map(fn ($item) => [
            'product_id' => (int) ($item['product_id'] ?? 0),
            'quantity' => (int) ($item['quantity'] ?? 0),
        ])
        ->values()
        ->all();

    $this->isCheckingOut = true;

    try {
        $order = $service->handle(
            (int) $roomId,
            $cartItems,
            $this->paymentMethod,
        );

       session()->put('guest_order_id', $order->id);
session()->put('guest_room_id', $order->room_id);

$this->items = [];

$this->redirectRoute(
    'guest.order.confirmation',
    ['orderNumber' => $order->order_number]
);
    } finally {
        $this->isCheckingOut = false;
    }
}


public function add(int $productId): void
{
    $product = Product::query()
        ->where('is_available', true)
        ->findOrFail($productId);

    $key = (string) $product->id;

    if (isset($this->items[$key])) {
        $this->increment($productId);

        return;
    }

    $this->items[$key] = [
        'product_id' => $product->id,
        'quantity' => 1,
    ];

    $this->isOpen = true;
}

public function increment(int $productId): void
{
    $key = (string) $productId;

    if (! isset($this->items[$key])) {
        return;
    }

    $product = Product::query()
        ->where('is_available', true)
        ->find($productId);

    if (! $product) {
        unset($this->items[$key]);

        return;
    }

    $quantity = (int) $this->items[$key]['quantity'];

    if ($quantity >= 99) {
        return;
    }

    $this->items[$key]['quantity'] = max(1, $quantity) + 1;
}

public function decrement(int $productId): void
{
    $key = (string) $productId;

    if (! isset($this->items[$key])) {
        return;
    }

    $quantity = (int) $this->items[$key]['quantity'];

    if ($quantity <= 1) {
        unset($this->items[$key]);

        return;
    }

    $this->items[$key]['quantity'] = $quantity - 1;
}


    public function remove(int $productId): void
    {
        unset($this->items[(string) $productId]);
    }

    public function getCartItemsProperty(): array
    {
        $products = Product::query()
            ->whereIn(
                'id',
                collect($this->items)->pluck('product_id')->all()
            )
            ->where('is_available', true)
            ->get()
            ->keyBy(fn (Product $product) => (string) $product->id);

        $result = [];

        foreach ($this->items as $key => $item) {
            $product = $products->get($key);

            if (! $product) {
                continue;
            }

            $result[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => min(99, max(1, (int) $item['quantity'])),
                'subtotal' => (float) $product->price
                * min(99, max(1, (int) $item['quantity'])),
            ];
        }

        return $result;
    }

    public function getTotalQuantityProperty(): int
    {
        return collect($this->cartItems)
            ->sum('quantity');
    }

    public function getTotalPriceProperty(): float
    {
        return collect($this->cartItems)
            ->sum('subtotal');
    }
};
?>

<div>
    @if ($orderNumber)
    <div class="fixed inset-x-4 top-4 z-[60] mx-auto max-w-lg rounded-2xl border border-teal/20 bg-white p-5 shadow-lg">
        <p class="font-display text-xl font-semibold text-charcoal">
            Pesanan berhasil dibuat!
        </p>
        <p class="mt-2 text-sm text-gray-500">
            Nomor pesanan:
            <span class="font-semibold text-teal">{{ $orderNumber }}</span>
        </p>
        <button
            type="button"
            wire:click="$set('orderNumber', null)"
            class="mt-3 text-sm font-semibold text-teal hover:underline"
        >
            Tutup
        </button>
    </div>
@endif
    <div class="fixed inset-x-0 bottom-0 z-40 border-t border-black/5 bg-white/95 p-4 shadow-[0_-8px_30px_rgba(0,0,0,0.08)] backdrop-blur">
        <div class="mx-auto flex max-w-4xl items-center justify-between gap-4">
            <button
                type="button"
                wire:click="$set('isOpen', true)"
                class="min-w-0 text-left"
            >
                <span class="block text-xs text-gray-500">
                    {{ $this->totalQuantity }} item dalam keranjang
                </span>

                <span class="mt-1 block font-semibold text-teal">
                    Rp {{ number_format($this->totalPrice, 0, ',', '.') }}
                </span>
            </button>

            <button
                type="button"
                wire:click="$set('isOpen', true)"
                @disabled($this->totalQuantity === 0)
                class="rounded-xl bg-teal px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal/90 disabled:cursor-not-allowed disabled:opacity-40"
            >
                Lihat Keranjang
            </button>
        </div>
    </div>

    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-black/40 sm:items-center sm:p-4">
            <button
                type="button"
                wire:click="$set('isOpen', false)"
                aria-label="Tutup keranjang"
                class="absolute inset-0 h-full w-full cursor-default"
            ></button>

            <section class="relative z-10 max-h-[85vh] w-full max-w-lg overflow-y-auto rounded-t-3xl bg-cream p-6 sm:rounded-3xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-teal">
                            Ngoffe ID
                        </p>
                        <h2 class="mt-1 font-display text-2xl font-semibold">
                            Keranjangmu
                        </h2>
                    </div>

                    <button
                        type="button"
                        wire:click="$set('isOpen', false)"
                        class="rounded-full px-3 py-2 text-sm text-gray-500 hover:bg-black/5"
                    >
                        Tutup
                    </button>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($this->cartItems as $item)
                        <article
                            wire:key="cart-item-{{ $item['id'] }}"
                            class="rounded-2xl border border-black/5 bg-white p-4"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-semibold">
                                        {{ $item['name'] }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    wire:click="remove({{ $item['id'] }})"
                                    class="text-xs font-medium text-red-600 hover:underline"
                                >
                                    Hapus
                                </button>
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <button
                                        type="button"
                                        wire:click="decrement({{ $item['id'] }})"
                                        aria-label="Kurangi {{ $item['name'] }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-black/10 hover:bg-cream"
                                    >
                                        −
                                    </button>

                                    <span class="min-w-5 text-center font-semibold">
                                        {{ $item['quantity'] }}
                                    </span>

                                    <button
                                        type="button"
                                        wire:click="increment({{ $item['id'] }})"
                                        aria-label="Tambah {{ $item['name'] }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-black/10 hover:bg-cream"
                                    >
                                        +
                                    </button>
                                </div>

                                <p class="font-semibold text-teal">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </p>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl bg-white p-8 text-center">
                            <p class="font-display text-xl font-semibold">
                                Keranjang masih kosong
                            </p>
                            <p class="mt-2 text-sm text-gray-500">
                                Tambahkan menu favoritmu untuk memulai.
                            </p>
                        </div>
                    @endforelse
                </div>

                
<div class="mt-6 border-t border-black/10 pt-5">
    <div class="flex items-center justify-between">
        <span class="text-sm text-gray-500">Total pesanan</span>
        <span class="text-lg font-semibold text-teal">
            Rp {{ number_format($this->totalPrice, 0, ',', '.') }}
        </span>
    </div>

    <div class="mt-5 space-y-3">
        <p class="text-sm font-semibold text-charcoal">
            Pilih metode pembayaran
        </p>

        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-black/10 bg-white p-4">
            <input
                type="radio"
                wire:model="paymentMethod"
                value="cash"
                class="accent-teal"
            >
            <span>
                <span class="block text-sm font-semibold">Tunai</span>
                <span class="text-xs text-gray-500">Bayar kepada kasir</span>
            </span>
        </label>

        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-black/10 bg-white p-4">
            <input
                type="radio"
                wire:model="paymentMethod"
                value="qris"
                class="accent-teal"
            >
            <span>
                <span class="block text-sm font-semibold">QRIS</span>
                <span class="text-xs text-gray-500">Menunggu instruksi pembayaran</span>
            </span>
        </label>
    </div>

    @error('checkout')
        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @error('paymentMethod')
        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <button
        type="button"
        wire:click="checkout"
        wire:loading.attr="disabled"
        wire:target="checkout"
        @disabled($this->totalQuantity === 0)
        class="mt-5 w-full rounded-xl bg-teal px-5 py-3 font-semibold text-white transition hover:bg-teal/90 disabled:cursor-not-allowed disabled:opacity-50"
    >
        <span wire:loading.remove wire:target="checkout">Buat Pesanan</span>
        <span wire:loading wire:target="checkout">Memproses pesanan...</span>
    </button>

    <button
        type="button"
        wire:click="$set('isOpen', false)"
        class="mt-3 w-full rounded-xl border border-black/10 px-5 py-3 text-sm font-semibold text-charcoal hover:bg-white"
    >
        Lanjut Pilih Menu
    </button>
</div>

            </section>
        </div>
    @endif
</div>
