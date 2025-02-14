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
        Schema::create('dispose_milks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('production_milk_id');
            $table->foreignId('user_id');

            $table->date('date');
            $table->string('reason_for_dispose');

            $table->foreign('production_milk_id')->references('id')->on('production_milks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->on('users')->onDelete('cascade');
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispose_milks');
    }
};
