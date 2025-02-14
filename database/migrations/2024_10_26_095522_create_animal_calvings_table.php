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
        Schema::create('animal_calvings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('calf_id');
            $table->foreignId('veterinarian_id');
            $table->foreignId('pregnancy_id');

            $table->date('calving_date');
            $table->string('calving_notes');


            $table->foreign('calf_id')->references('id')->on('animal_details')->onDelete('cascade');
             $table->foreign('veterinarian_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pregnancy_id')->references('id')->on('pregnancies')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_calvings');
    }
};
