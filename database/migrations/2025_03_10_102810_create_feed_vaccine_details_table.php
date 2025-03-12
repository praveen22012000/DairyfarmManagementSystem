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
        Schema::create('feed_vaccine_details', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('manufacturer');
            $table->enum('type',['feed','vaccine']);

            $table->enum('unit_type',['g','mg','kg','ml']);

            $table->decimal('unit_price',8,2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_vaccine_details');
    }
};
