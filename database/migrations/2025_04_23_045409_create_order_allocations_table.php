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
        Schema::create('order_allocations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->foreignId('product_id');
            $table->foreignId('stock_id');

            $table->integer('allocated_quantity');
            $table->integer('delivered_quantity');
            $table->boolean('is_delivered');

            $table->foreign('order_id')->references('id')->on('retailor_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('milk_products')->onDelete('cascade');
            $table->foreign('stock_id')->references('id')->on('manufacturer_products')->onDelete('cascade');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_allocations');
    }
};
