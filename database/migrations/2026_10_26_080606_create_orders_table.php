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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_number', 30)->unique();

        $table->foreignId('room_id')
            ->constrained()
            ->restrictOnDelete();

        $table->string('order_status', 30)->default('menunggu_pembayaran');
        $table->string('payment_status', 30)->default('belum_dibayar');
        $table->string('payment_method', 30)->nullable();

        $table->decimal('subtotal', 12, 2)->default(0);
        $table->decimal('total', 12, 2)->default(0);

        $table->timestamp('placed_at')->nullable();
        $table->timestamp('payment_confirmed_at')->nullable();
        $table->timestamps();

        $table->index(['order_status', 'created_at']);
        $table->index(['payment_status', 'created_at']);
    });
}

public function down(): void
{
    Schema::dropIfExists('orders');
}
};
