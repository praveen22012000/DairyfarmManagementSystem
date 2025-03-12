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
        Schema::create('production_supply_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('production_milk_id');
            $table->foreignId('production_supply_id');
            $table->foreignId('product_id');

            $table->decimal('consumed_quantity');

            $table->foreign('production_milk_id')->references('id')->on('production_milks')->onDelete('cascade');
            $table->foreign('production_supply_id')->references('id')->on('production_supplies')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('milk_products')->onDelete('cascade');
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_supply_details');
    }
};
