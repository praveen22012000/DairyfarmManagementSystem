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
        Schema::create('feed_consume_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('animal_id');
            $table->date('date');
            $table->time('time');

            $table->foreignId('user_id');

            $table->foreign('animal_id')->references('id')->on('animal_details')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_consume_details');
    }
};
