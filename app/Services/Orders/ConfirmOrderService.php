<?php

namespace App\Services\Orders;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConfirmOrderService
{
    public function handle(int $orderId): Order
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::query()
                ->lockForUpdate()
                ->findOrFail($orderId);

            if ($order->order_status !== 'menunggu_validasi') {
                throw ValidationException::withMessages([
                    'order' => 'Pesanan ini tidak sedang menunggu validasi.',
                ]);
            }

            // Pengecekan stok dan alokasi bahan akan ditambahkan
            // sebelum pesanan diteruskan ke dapur.

            return $order;
        });
    }
}
