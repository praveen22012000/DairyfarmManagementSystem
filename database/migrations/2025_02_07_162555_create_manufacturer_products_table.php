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
        Schema::create('manufacturer_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manufacturer_id');
            $table->foreignId('product_id');

            $table->decimal('quantity');
            $table->date('manufacture_date');
            $table->date('expire_date');

            $table->foreignId('employee_id');//employee_id is equal to the user_id in users table


            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('milk_products')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturer_products');
    }
};
