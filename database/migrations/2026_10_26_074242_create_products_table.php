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
    Schema::create('products', function (Blueprint $table) {
        $table->id();

        $table->foreignId('category_id')
            ->constrained()
            ->restrictOnDelete();

        $table->string('name', 150);
        $table->text('description')->nullable();
        $table->decimal('price', 12, 2);
        $table->string('image_path')->nullable();
        $table->boolean('is_available')->default(true);
        $table->unsignedInteger('sort_order')->default(0);

        $table->timestamps();

        $table->index(['category_id', 'is_available']);
    });
}

public function down(): void
{
    Schema::dropIfExists('products');
}
};
