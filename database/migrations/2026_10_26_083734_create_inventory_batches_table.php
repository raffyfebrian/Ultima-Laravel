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
    Schema::create('inventory_batches', function (Blueprint $table) {
        $table->id();

        $table->foreignId('ingredient_id')
            ->constrained('ingredients')
            ->restrictOnDelete();

        $table->string('batch_number', 100)->nullable();
        $table->decimal('initial_quantity', 14, 3);
        $table->decimal('remaining_quantity', 14, 3);
        $table->date('received_at');
        $table->date('expires_at')->nullable();

        $table->timestamps();

        $table->index(['ingredient_id', 'expires_at']);
    });
}

public function down(): void
{
    Schema::dropIfExists('inventory_batches');
}
};