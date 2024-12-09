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
        Schema::create('pregnancies', function (Blueprint $table) {
            
            $table->id();

            $table->foreignId('breeding_id');
            $table->foreignId('female_cow_id');
            $table->foreignId('veterinarian_id');

            $table->string('pregnancy_status');
            $table->date('estimated_calving_date');
            $table->date('confirmation_date');

            $table->foreign('breeding_id')->references('id')->on('breeding_events')->onDelete('cascade');
            $table->foreign('female_cow_id')->references('id')->on('animal_details')->onDelete('cascade');
            $table->foreign('veterinarian_id')->references('id')->on('users')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregnancies');
    }
};
