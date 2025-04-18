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
        Schema::create('vaccine_consume_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('animal_id');
            $table->foreignId('vaccine_id');
            $table->foreignId('vaccination_item_id');
            $table->foreignId('vaccine_consume_detail_id');

            $table->decimal('consumed_quantity');
           

            $table->foreign('animal_id')->references('id')->on('animal_details')->onDelete('cascade');
            $table->foreign('vaccine_id')->references('id')->on('vaccines')->onDelete('cascade');
            $table->foreign('vaccination_item_id')->references('id')->on('purchase_vaccine_items')->onDelete('cascade');
            $table->foreign('vaccine_consume_detail_id')->references('id')->on('vaccine_consume_details')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccine_consume_items');
    }
};
