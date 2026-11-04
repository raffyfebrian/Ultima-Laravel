<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryBatch extends Model
{
    protected $fillable = [
        'ingredient_id',
        'batch_number',
        'initial_quantity',
        'remaining_quantity',
        'received_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'initial_quantity' => 'decimal:3',
            'remaining_quantity' => 'decimal:3',
            'received_at' => 'date',
            'expires_at' => 'date',
        ];
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
