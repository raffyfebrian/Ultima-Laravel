<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Contracts\View\View;
use App\Http\Requests\StoreIngredientRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreInventoryBatchRequest;
use App\Services\Inventory\InventoryStockService;


class InventoryController extends Controller
{
public function receive(
    StoreInventoryBatchRequest $request,
    InventoryStockService $inventoryStockService,
): RedirectResponse {
    $data = $request->validated();

    $inventoryStockService->receive(
        ingredientId: (int) $data['ingredient_id'],
        quantity: (float) $data['quantity'],
        batchNumber: $data['batch_number'] ?? null,
        expiresAt: $data['expires_at'] ?? null,
        userId: (int) $request->user()->id,
        notes: $data['notes'] ?? null,
    );

    return redirect()
        ->route('admin.inventory.index')
        ->with('success', 'Stok bahan baku berhasil ditambahkan.');
}
    
public function store(StoreIngredientRequest $request): RedirectResponse
{
    \App\Models\Ingredient::query()->create(
        $request->validated()
    );

    return redirect()
        ->route('admin.inventory.index')
        ->with('success', 'Bahan baku berhasil ditambahkan.');
}

    public function index(): View
    {
        $ingredients = Ingredient::query()
            ->withSum('batches as total_stock', 'remaining_quantity')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.inventory.index', [
            'ingredients' => $ingredients,
        ]);
    }
}
