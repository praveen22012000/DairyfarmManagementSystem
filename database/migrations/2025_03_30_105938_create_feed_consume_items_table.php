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
        Schema::create('feed_consume_items', function (Blueprint $table) {
            $table->id();

         
            
            $table->decimal('consumed_quantity');
            $table->string('notes');

            $table->foreignId('feed_id');
         
            $table->foreignId('purchase_feed_item_id');
            $table->foreignId('feed_consume_detail_id');

            $table->foreign('feed_id')->references('id')->on('feeds')->onDelete('cascade');
      
            $table->foreign('purchase_feed_item_id')->references('id')->on('purchase_feed_items')->onDelete('cascade');
            $table->foreign('feed_consume_detail_id')->references('id')->on('feed_consume_details')->onDelete('cascade');
             

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_consume_items');
    }
};
