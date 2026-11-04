<?php

namespace App\Services\Inventory;

use App\Models\Ingredient;
use App\Models\InventoryBatch;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryStockService
{
    
public function adjust(
    int $batchId,
    float $newQuantity,
    ?int $userId = null,
    ?string $notes = null,
): InventoryBatch {
    if (! is_finite($newQuantity) || $newQuantity < 0) {
        throw ValidationException::withMessages([
            'quantity' => 'Stok baru tidak boleh negatif.',
        ]);
    }

    return DB::transaction(function () use (
        $batchId,
        $newQuantity,
        $userId,
        $notes,
    ) {
        $batch = InventoryBatch::query()
            ->lockForUpdate()
            ->findOrFail($batchId);

        $before = (float) $batch->remaining_quantity;
        $difference = round($newQuantity - $before, 3);

        if ($difference == 0.0) {
            throw ValidationException::withMessages([
                'quantity' => 'Stok baru sama dengan stok saat ini.',
            ]);
        }

        $batch->remaining_quantity = $newQuantity;
        $batch->save();

        InventoryMovement::query()->create([
            'inventory_batch_id' => $batch->id,
            'ingredient_id' => $batch->ingredient_id,
            'type' => 'adjustment',
            'quantity' => abs($difference),
            'stock_before' => $before,
            'stock_after' => $newQuantity,
            'notes' => $notes,
            'created_by' => $userId,
        ]);

        return $batch;
    });
}

    public function receive(
        int $ingredientId,
        float $quantity,
        ?string $batchNumber = null,
        ?string $expiresAt = null,
        ?int $userId = null,
        ?string $notes = null,
    ): InventoryBatch {
        $this->validateQuantity($quantity);

        return DB::transaction(function () use (
            $ingredientId,
            $quantity,
            $batchNumber,
            $expiresAt,
            $userId,
            $notes,
        ) {
            $ingredient = Ingredient::query()
                ->whereKey($ingredientId)
                ->where('is_active', true)
                ->lockForUpdate()
                ->firstOrFail();

            $batch = InventoryBatch::query()->create([
                'ingredient_id' => $ingredient->id,
                'batch_number' => $batchNumber,
                'initial_quantity' => $quantity,
                'remaining_quantity' => $quantity,
                'received_at' => now()->toDateString(),
                'expires_at' => $expiresAt,
            ]);

            InventoryMovement::query()->create([
                'inventory_batch_id' => $batch->id,
                'ingredient_id' => $ingredient->id,
                'type' => 'in',
                'quantity' => $quantity,
                'stock_before' => 0,
                'stock_after' => $quantity,
                'reference_number' => $batchNumber,
                'notes' => $notes,
                'created_by' => $userId,
            ]);

            return $batch;
        });
    }

    public function issue(
        int $batchId,
        float $quantity,
        ?int $userId = null,
        ?string $notes = null,
    ): InventoryBatch {
        $this->validateQuantity($quantity);

        return DB::transaction(function () use (
            $batchId,
            $quantity,
            $userId,
            $notes,
        ) {
            $batch = InventoryBatch::query()
                ->lockForUpdate()
                ->findOrFail($batchId);

            $before = (float) $batch->remaining_quantity;

            if ($quantity > $before) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok tidak mencukupi.',
                ]);
            }

            $batch->remaining_quantity = round($before - $quantity, 3);
            $batch->save();

            InventoryMovement::query()->create([
                'inventory_batch_id' => $batch->id,
                'ingredient_id' => $batch->ingredient_id,
                'type' => 'out',
                'quantity' => $quantity,
                'stock_before' => $before,
                'stock_after' => (float) $batch->remaining_quantity,
                'notes' => $notes,
                'created_by' => $userId,
            ]);

            return $batch;
        });
    }

    private function validateQuantity(float $quantity): void
    {
        if (! is_finite($quantity) || $quantity <= 0) {
            throw ValidationException::withMessages([
                'quantity' => 'Jumlah harus lebih besar dari nol.',
            ]);
        }
    }
}
