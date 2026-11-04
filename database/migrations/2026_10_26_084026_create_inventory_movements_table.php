<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
public function up(): void
{
    Schema::create('inventory_movements', function (Blueprint $table) {
        $table->id();

        $table->foreignId('inventory_batch_id')
            ->nullable()
            ->constrained('inventory_batches')
            ->restrictOnDelete();

        $table->foreignId('ingredient_id')
            ->constrained('ingredients')
            ->restrictOnDelete();

        $table->string('type', 20);
        $table->decimal('quantity', 14, 3);
        $table->decimal('stock_before', 14, 3);
        $table->decimal('stock_after', 14, 3);

        $table->string('reference_number', 100)->nullable();
        $table->text('notes')->nullable();

        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamps();

        $table->index(['ingredient_id', 'created_at']);
        $table->index(['type', 'created_at']);
    });
}

public function down(): void
{
    Schema::dropIfExists('inventory_movements');
}
};