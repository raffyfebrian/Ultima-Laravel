<?php

use App\Models\Product;
use App\Models\Room;
use App\Services\Orders\CreateOrderService;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public array $items = [];

    public bool $isOpen = false;

    public string $paymentMethod = 'cash';

    public bool $isCheckingOut = false;

    public ?string $idempotencyKey = null;

    #[On('add-to-cart')]
    public function addToCart(int $productId, int $quantity = 1, string $notes = ''): void
    {
        $this->add($productId, $quantity, $notes);
    }

    #[On('open-cart')]
    public function openCart(): void
    {
        $this->isOpen = true;
    }

    public function add(int $productId, int $quantity = 1, string $notes = ''): void
    {
        $product = Product::query()
            ->where('is_available', true)
            ->find($productId);

        if (! $product) {
            $this->addError('cart', 'Menu ini sedang tidak tersedia.');

            return;
        }

        $key = (string) $product->id;
        $quantity = max(1, min(99, $quantity));
        $notes = trim($notes);

        if (mb_strlen($notes) > 160) {
            $notes = mb_substr($notes, 0, 160);
        }

        if (isset($this->items[$key])) {
            $this->items[$key]['quantity'] = min(
                99,
                (int) $this->items[$key]['quantity'] + $quantity
            );

            if ($notes !== '') {
                $this->items[$key]['notes'] = $notes;
            }
        } else {
            $this->items[$key] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'notes' => $notes,
            ];
        }

        $this->resetErrorBag('cart');
        $this->dispatch('cart-updated', count: $this->totalQuantity);
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
            $this->dispatch('cart-updated', count: $this->totalQuantity);

            return;
        }

        $this->items[$key]['quantity'] = min(
            99,
            max(1, (int) $this->items[$key]['quantity']) + 1
        );

        $this->dispatch('cart-updated', count: $this->totalQuantity);
    }

    public function decrement(int $productId): void
    {
        $key = (string) $productId;

        if (! isset($this->items[$key])) {
            return;
        }

        $quantity = max(1, (int) $this->items[$key]['quantity']);

        if ($quantity <= 1) {
            unset($this->items[$key]);
        } else {
            $this->items[$key]['quantity'] = $quantity - 1;
        }

        $this->dispatch('cart-updated', count: $this->totalQuantity);
    }

    /**
     * Menetapkan jumlah final setelah tamu menekan +/−. UI melakukan optimistic
     * update; method ini tetap memvalidasi ketersediaan di server dan memberi
     * sinyal "cart-rejected" bila perubahan harus dibatalkan.
     */
    public function setQuantity(int $productId, int $quantity): void
    {
        $key = (string) $productId;

        if (! isset($this->items[$key])) {
            return;
        }

        if ($quantity < 1) {
            unset($this->items[$key]);
            $this->resetErrorBag('cart');
            $this->dispatch('cart-updated', count: $this->totalQuantity);

            return;
        }

        $product = Product::query()
            ->where('is_available', true)
            ->find($productId);

        if (! $product) {
            $this->addError('cart', 'Menu ini sedang tidak tersedia.');
            $this->dispatch('cart-rejected', productId: $productId, reason: 'unavailable');

            return;
        }

        $this->items[$key]['quantity'] = max(1, min(99, $quantity));

        $this->resetErrorBag('cart');
        $this->dispatch('cart-updated', count: $this->totalQuantity);
    }

    public function remove(int $productId): void
    {
        unset($this->items[(string) $productId]);

        $this->dispatch('cart-updated', count: $this->totalQuantity);
    }

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
            $this->addError(
                'checkout',
                'Sesi kamar tidak ditemukan. Silakan scan ulang QR kamar.'
            );

            return;
        }

        $cartItems = collect($this->items)
            ->map(fn ($item) => [
                'product_id' => (int) ($item['product_id'] ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 0),
                'notes' => (string) ($item['notes'] ?? ''),
            ])
            ->values()
            ->all();

        if (empty($cartItems)) {
            $this->addError('checkout', 'Keranjang masih kosong.');

            return;
        }

        // Kunci idempotensi dibuat sekali per percobaan checkout. Jika request
        // terkirim dua kali (double click/retry), backend mengembalikan pesanan
        // yang sama alih-alih membuat pesanan baru.
        $this->idempotencyKey ??= (string) Str::uuid();

        $this->isCheckingOut = true;

        try {
            $order = $service->handle(
                (int) $roomId,
                $cartItems,
                $this->paymentMethod,
                $this->idempotencyKey,
            );

            session()->put('guest_order_id', $order->id);
            session()->put('guest_room_id', $order->room_id);

            $this->items = [];
            $this->idempotencyKey = null;
            $this->isOpen = false;
            $this->dispatch('cart-updated', count: 0);

            $this->redirectRoute(
                'guest.order.confirmation',
                ['orderNumber' => $order->order_number]
            );
        } catch (ValidationException $exception) {
            throw $exception;
        } finally {
            $this->isCheckingOut = false;
        }
    }

    public function getCartItemsProperty(): array
    {
        $products = Product::query()
            ->whereIn('id', collect($this->items)->pluck('product_id')->all())
            ->get()
            ->keyBy(fn (Product $product) => (string) $product->id);

        $result = [];

        foreach ($this->items as $key => $item) {
            $product = $products->get($key);

            if (! $product) {
                continue;
            }

            $quantity = min(99, max(1, (int) $item['quantity']));

            $result[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'notes' => trim((string) ($item['notes'] ?? '')),
                'available' => (bool) $product->is_available,
                'subtotal' => (float) $product->price * $quantity,
            ];
        }

        return $result;
    }

    public function getTotalQuantityProperty(): int
    {
        return collect($this->cartItems)->sum('quantity');
    }

    public function getTotalPriceProperty(): float
    {
        return collect($this->cartItems)->sum('subtotal');
    }

    public function getRoomProperty(): ?Room
    {
        $roomId = session('guest_room_id');

        return $roomId ? Room::query()->find($roomId) : null;
    }
};
?>

<div
    x-data
    @keydown.escape.window="$wire.set('isOpen', false)"
>
    <div class="cart-dock" aria-live="polite">
        <button type="button" class="cart-dock-info" wire:click="$set('isOpen', true)">
            <small><span data-dock-count>{{ $this->totalQuantity }}</span> item dalam keranjang</small>
            <b data-dock-total>Rp {{ number_format($this->totalPrice, 0, ',', '.') }}</b>
        </button>

        <button
            type="button"
            class="cart-dock-button"
            wire:click="$set('isOpen', true)"
            @disabled($this->totalQuantity === 0)
        >
            Lihat Keranjang
        </button>
    </div>

    @if ($isOpen)
        <div
            class="cart-overlay"
            role="dialog"
            aria-modal="true"
            aria-labelledby="cart-title"
            wire:keydown.escape="$set('isOpen', false)"
        >
            <button
                type="button"
                class="absolute inset-0 h-full w-full cursor-default border-0 bg-transparent"
                wire:click="$set('isOpen', false)"
                aria-label="Tutup keranjang"
                tabindex="-1"
            ></button>

            <section
                class="cart-panel"
                x-data
                x-effect="$wire.isOpen && $nextTick(() => $refs.cartClose?.focus())"
            >
                <div class="cart-panel-head">
                    <div>
                        <p class="cart-panel-kicker">Ngoffe ID</p>
                        <h2 class="cart-panel-title" id="cart-title">Keranjangmu</h2>
                    </div>

                    <button
                        type="button"
                        class="cart-close"
                        x-ref="cartClose"
                        wire:click="$set('isOpen', false)"
                        aria-label="Tutup keranjang"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <path d="M6 6l12 12M18 6 6 18"/>
                        </svg>
                    </button>
                </div>

                @if ($this->room)
                    <div class="cart-room">
                        <span>Pesanan untuk</span>
                        <b>Kamar {{ $this->room->room_number }}</b>
                    </div>
                @endif

                <p class="visually-hidden" role="status" aria-live="polite" data-cart-announcer></p>

                <div class="cart-items">
                    @forelse ($this->cartItems as $item)
                        <article
                            class="cart-item"
                            wire:key="cart-item-{{ $item['id'] }}"
                            data-cart-item
                            data-product-id="{{ $item['id'] }}"
                            data-confirmed-qty="{{ $item['quantity'] }}"
                            data-display-qty="{{ $item['quantity'] }}"
                            data-unit-price="{{ $item['price'] }}"
                        >
                            <div class="cart-item-inner">
                                <div class="cart-item-top">
                                    <div>
                                        <h3 class="cart-item-name">{{ $item['name'] }}</h3>
                                        <p class="cart-item-price">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            @unless ($item['available'])
                                                · <span style="color: #a33b3b;">tidak tersedia</span>
                                            @endunless
                                        </p>
                                    </div>

                                    <button
                                        type="button"
                                        class="cart-remove"
                                        wire:click="remove({{ $item['id'] }})"
                                        aria-label="Hapus {{ $item['name'] }} dari keranjang"
                                    >
                                        Hapus
                                    </button>
                                </div>

                                @if ($item['notes'] !== '')
                                    <p class="cart-item-note">
                                        <span class="visually-hidden">Catatan: </span>{{ $item['notes'] }}
                                    </p>
                                @endif

                                <div class="cart-item-bottom">
                                    <div class="qty-control">
                                        <button
                                            type="button"
                                            data-qty-action="decrement"
                                            aria-label="Kurangi {{ $item['name'] }}"
                                        >
                                            −
                                        </button>
                                        <span class="qty-value" data-qty-value>{{ $item['quantity'] }}</span>
                                        <button
                                            type="button"
                                            data-qty-action="increment"
                                            aria-label="Tambah {{ $item['name'] }}"
                                        >
                                            +
                                        </button>
                                    </div>

                                    <p class="cart-item-subtotal" data-item-subtotal>
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="cart-empty">
                            <b>Keranjang masih kosong</b>
                            <span>Tambahkan menu favoritmu untuk memulai.</span>
                        </div>
                    @endforelse
                </div>

                <div class="cart-summary">
                    <div class="cart-total-row">
                        <span>Total pesanan</span>
                        <b data-cart-total>Rp {{ number_format($this->totalPrice, 0, ',', '.') }}</b>
                    </div>

                    <p class="cart-pay-title">Pilih metode pembayaran</p>

                    <div class="cart-pay-options">
                        <label class="cart-pay">
                            <input type="radio" wire:model="paymentMethod" value="cash">
                            <span>
                                <b>Tunai</b>
                                <small>Bayar kepada kasir saat pesanan diantar</small>
                            </span>
                        </label>

                        <label class="cart-pay">
                            <input type="radio" wire:model="paymentMethod" value="qris">
                            <span>
                                <b>QRIS</b>
                                <small>Menunggu instruksi pembayaran dari kasir</small>
                            </span>
                        </label>
                    </div>

                    @error('checkout')
                        <p class="cart-error">{{ $message }}</p>
                    @enderror

                    @error('cart')
                        <p class="cart-error">{{ $message }}</p>
                    @enderror

                    <p class="cart-error" data-cart-client-error hidden></p>

                    @error('paymentMethod')
                        <p class="cart-error">{{ $message }}</p>
                    @enderror

                    <button
                        type="button"
                        class="cart-submit"
                        wire:click="checkout"
                        wire:loading.attr="disabled"
                        wire:target="checkout"
                        @disabled($this->totalQuantity === 0 || $this->isCheckingOut)
                    >
                        <span wire:loading.remove wire:target="checkout">Kirim Pesanan</span>
                        <span wire:loading wire:target="checkout">Mengirim pesanan…</span>
                    </button>

                    <button
                        type="button"
                        class="cart-continue"
                        wire:click="$set('isOpen', false)"
                    >
                        Lanjut Pilih Menu
                    </button>
                </div>
            </section>
        </div>
    @endif
</div>
