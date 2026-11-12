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
        string $paymentMethod,
        ?string $idempotencyKey = null
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

        $idempotencyKey = $idempotencyKey !== null && trim($idempotencyKey) !== ''
            ? substr(trim($idempotencyKey), 0, 64)
            : null;

        if ($idempotencyKey !== null) {
            $existing = Order::query()
                ->with('items')
                ->where('idempotency_key', $idempotencyKey)
                ->where('room_id', $roomId)
                ->first();

            if ($existing) {
                return $existing;
            }
        }

        return DB::transaction(function () use (
            $roomId,
            $cartItems,
            $paymentMethod,
            $idempotencyKey
        ) {
            $room = Room::query()
                ->whereKey($roomId)
                ->where('is_active', true)
                ->firstOrFail();

            $lines = [];

            foreach ($cartItems as $item) {
                $productId = (int) ($item['product_id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);
                $notes = trim((string) ($item['notes'] ?? ''));

                if ($productId < 1 || $quantity < 1 || $quantity > 99) {
                    throw ValidationException::withMessages([
                        'cart' => 'Item keranjang tidak valid.',
                    ]);
                }

                if (mb_strlen($notes) > 160) {
                    throw ValidationException::withMessages([
                        'cart' => 'Catatan pesanan terlalu panjang.',
                    ]);
                }

                $key = $productId.'|'.$notes;
                $newQuantity = ($lines[$key]['quantity'] ?? 0) + $quantity;

                if ($newQuantity > 99) {
                    throw ValidationException::withMessages([
                        'cart' => 'Jumlah item melebihi batas 99.',
                    ]);
                }

                $lines[$key] = [
                    'product_id' => $productId,
                    'quantity' => $newQuantity,
                    'notes' => $notes,
                ];
            }

            $products = Product::query()
                ->whereIn('id', array_column($lines, 'product_id'))
                ->where('is_available', true)
                ->get()
                ->keyBy('id');

            if ($products->count() !== count(array_unique(array_column($lines, 'product_id')))) {
                throw ValidationException::withMessages([
                    'cart' => 'Ada produk yang sudah tidak tersedia.',
                ]);
            }

            $subtotal = 0;

            foreach ($lines as $line) {
                $subtotal += (float) $products[$line['product_id']]->price * $line['quantity'];
            }

            $attributes = [
                'order_number' => 'NGF-'.now()->format('Ymd').'-'
                    .Str::upper(Str::random(8)),
                'public_token' => Str::random(64),
                'idempotency_key' => $idempotencyKey,
                'room_id' => $room->id,
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'placed_at' => now(),
            ];

            $order = $idempotencyKey !== null
                ? Order::query()->firstOrCreate(
                    [
                        'idempotency_key' => $idempotencyKey,
                        'room_id' => $room->id,
                    ],
                    $attributes
                )
                : Order::query()->create($attributes);

            if (! $order->wasRecentlyCreated) {
                return $order->load('items');
            }

            foreach ($lines as $line) {
                $product = $products[$line['product_id']];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $line['quantity'],
                    'notes' => $line['notes'] !== '' ? $line['notes'] : null,
                    'subtotal' => (float) $product->price * $line['quantity'],
                ]);
            }

            return $order->load('items');
        });
    }
}
