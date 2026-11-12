<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'public_token',
        'idempotency_key',
        'room_id',
        'order_status',
        'payment_status',
        'payment_method',
        'subtotal',
        'total',
        'placed_at',
        'payment_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'placed_at' => 'datetime',
            'payment_confirmed_at' => 'datetime',
        ];
    }

    public function getOrderStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'preparing' => 'Diproses',
            'ready' => 'Siap Disajikan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst((string) $this->order_status),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'Belum Dibayar',
            'paid' => 'Lunas',
            'refunded' => 'Dikembalikan',
            default => ucfirst((string) $this->payment_status),
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'transfer' => 'Transfer',
            'card' => 'Kartu',
            default => $this->payment_method ? ucfirst($this->payment_method) : '-',
        };
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Tahapan status publik. Urutan mengikuti alur dapur yang sudah ada.
     */
    public function getStatusStepsAttribute(): array
    {
        $steps = [
            ['key' => 'received', 'label' => 'Diterima', 'hint' => 'Pesanan masuk ke kasir'],
            ['key' => 'processing', 'label' => 'Diproses', 'hint' => 'Dapur menyiapkan pesanan'],
            ['key' => 'ready', 'label' => 'Siap', 'hint' => 'Pesanan siap diantar'],
            ['key' => 'completed', 'label' => 'Selesai', 'hint' => 'Pesanan selesai'],
        ];

        $current = match ($this->order_status) {
            'pending', 'confirmed' => 0,
            'preparing' => 1,
            'ready' => 2,
            'completed' => 3,
            default => -1,
        };

        return array_map(function (array $step, int $index) use ($current) {
            $step['state'] = $current === -1
                ? 'cancelled'
                : ($index < $current ? 'done' : ($index === $current ? 'current' : 'upcoming'));

            return $step;
        }, $steps, array_keys($steps));
    }

    public function getStatusStepIndexAttribute(): int
    {
        return match ($this->order_status) {
            'pending', 'confirmed' => 0,
            'preparing' => 1,
            'ready' => 2,
            'completed' => 3,
            default => -1,
        };
    }
}
