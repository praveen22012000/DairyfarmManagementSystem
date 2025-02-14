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
        Schema::create('breeding_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('female_cow_id');
            $table->foreignId('male_cow_id');

            $table->foreignId('veterinarian_id');

            $table->date('breeding_date');
            $table->string('insemination_type');
            $table->string('notes');

            $table->timestamps();

            $table->foreign('female_cow_id')->references('id')->on('animal_details')->onDelete('cascade');
            $table->foreign('male_cow_id')->references('id')->on('animal_details')->onDelete('cascade');

            $table->foreign('veterinarian_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breeding_events');
    }
};
