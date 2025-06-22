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
        Schema::create('farm_labores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farm_labore_id');

           
            $table->date('farm_labore_hire_date');
            $table->enum('status',['Available','Busy'])->default('Available');

            $table->foreign('farm_labore_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_labores');
    }
};
