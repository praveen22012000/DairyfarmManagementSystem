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
        Schema::create('retailor_order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->foreignId('product_id');
            $table->integer('ordered_quantity');
            $table->decimal('unit_price', 8, 2); // price per unit at time of order

            $table->foreign('order_id')->references('id')->on('retailor_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('milk_products')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailor_order_items');
    }
};
