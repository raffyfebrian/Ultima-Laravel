<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
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
}
