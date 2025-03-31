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
        Schema::create('purchase_feed_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_id');
            $table->foreignId('feed_id');

            $table->decimal('unit_price');

            $table->integer('purchase_quantity')->default(0);
            $table->integer('initial_quantity')->default(0);

            $table->integer('stock_quantity')->default(0);

            $table->date('manufacture_date');
            $table->date('expire_date');

            $table->foreign('purchase_id')->references('id')->on('purchase_feeds')->onDelete('cascade');
            $table->foreign('feed_id')->references('id')->on('feeds')->onDelete('cascade');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_feed_items');
    }
};
