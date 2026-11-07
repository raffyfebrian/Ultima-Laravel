<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateOrderService
{
    public function handle(
        int $roomId,
        array $cartItems,
        string $paymentMethod
    ): Order {
        if (! in_array($paymentMethod, ['cash', 'qris'], true)) {
            throw ValidationException::withMessages([
                'paymentMethod' => 'Metode pembayaran tidak valid.',
            ]);
        }

        if (empty($cartItems)) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang masih kosong.',
            ]);
        }

        return DB::transaction(function () use (
            $roomId,
            $cartItems,
            $paymentMethod
        ) {
            $room = Room::query()
                ->whereKey($roomId)
                ->where('is_active', true)
                ->firstOrFail();

            $quantities = [];

            foreach ($cartItems as $item) {
                $productId = (int) ($item['product_id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);

                if ($productId < 1 || $quantity < 1 || $quantity > 99) {
                    throw ValidationException::withMessages([
                        'cart' => 'Item keranjang tidak valid.',
                    ]);
                }

                $quantities[$productId] = ($quantities[$productId] ?? 0)
                    + $quantity;
            }

            $products = Product::query()
                ->whereIn('id', array_keys($quantities))
                ->where('is_available', true)
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($quantities)) {
                throw ValidationException::withMessages([
                    'cart' => 'Ada produk yang sudah tidak tersedia.',
                ]);
            }

            $subtotal = 0;

            foreach ($quantities as $productId => $quantity) {
                $subtotal += (float) $products[$productId]->price * $quantity;
            }

            $order = Order::query()->create([
                'order_number' => 'NGF-' . now()->format('Ymd') . '-'
                    . Str::upper(Str::random(8)),
                'room_id' => $room->id,
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'placed_at' => now(),
            ]);

            foreach ($quantities as $productId => $quantity) {
                $product = $products[$productId];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => (float) $product->price * $quantity,
                ]);
            }

            return $order;
        });
    }
}
