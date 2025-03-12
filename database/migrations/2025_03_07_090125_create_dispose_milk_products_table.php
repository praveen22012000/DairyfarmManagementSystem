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
        Schema::create('dispose_milk_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manufacturer_product_id');
            $table->foreignId('user_id');

            $table->date('date');
            $table->string('reason_for_dispose');
            $table->decimal('dispose_quantity');

            $table->foreign('manufacturer_product_id')->references('id')->on('manufacturer_products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->on('users')->onDelete('cascade');
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispose_milk_products');
    }
};
