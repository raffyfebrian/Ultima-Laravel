<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('public_token', 64)->nullable()->unique()->after('order_number');
            $table->string('idempotency_key', 64)->nullable()->unique()->after('public_token');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('notes', 160)->nullable()->after('quantity');
        });

        // Beri token status untuk pesanan lama agar halaman status tetap konsisten.
        DB::table('orders')
            ->whereNull('public_token')
            ->orderBy('id')
            ->each(function ($order) {
                DB::table('orders')
                    ->where('id', $order->id)
                    ->update(['public_token' => Str::random(64)]);
            });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['public_token']);
            $table->dropUnique(['idempotency_key']);
            $table->dropColumn(['public_token', 'idempotency_key']);
        });
    }
};
