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
        Schema::create('dispose_feed_items', function (Blueprint $table) {
            $table->id();
 
            $table->foreignId('purchase_feed_item_id');
            $table->foreignId('dispose_feed_detail_id');

         
            $table->integer('dispose_quantity');

            $table->string('reason_for_dispose');

            $table->foreign('purchase_feed_item_id')->references('id')->on('purchase_feed_items')->onDelete('cascade');
            $table->foreign('dispose_feed_detail_id')->references('id')->on('dispose_feed_detaills')->onDelete('cascade');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispose_feed_items');
    }
};
