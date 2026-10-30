<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name', 150);
            $table->string('type', 20);
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('stock_before');
            $table->unsignedInteger('stock_after');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['product_id', 'created_at']);
            $table->index(['type', 'created_at']);
        });

        $now = now();
        DB::table('products')
            ->select(['id', 'name', 'stock'])
            ->orderBy('id')
            ->chunkById(500, function ($products) use ($now) {
                $baselineMovements = $products->map(fn (object $product) => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'type' => 'initial',
                    'quantity' => $product->stock,
                    'stock_before' => 0,
                    'stock_after' => $product->stock,
                    'notes' => 'Saldo inicial registrado al habilitar el historial de movimientos.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

                if ($baselineMovements !== []) {
                    DB::table('stock_movements')->insert($baselineMovements);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
