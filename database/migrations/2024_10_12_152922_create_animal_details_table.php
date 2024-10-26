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
        Schema::create('animal_details', function (Blueprint $table) {
            $table->id();

            
            $table->foreignId('animal_type_id');
            $table->date('animal_birthdate');
            $table->string('animal_name');
            $table->string('ear_tag');
            $table->string('sire_id')->nullable();
            $table->string('dam_id')->nullable();
            $table->foreignId('breed_id');
            $table->string('color');
            $table->string('weight_at_birth');
            $table->string('age_at_first_service');
            $table->string('weight_at_first_service');

            $table->timestamps();

            //this line animal_type_id is the foreign_key of the Animal_details table
            $table->foreign('animal_type_id')->references('id')->on('animal_types')->onDelete('cascade');

            //this line represent the breed_id is the foreign_key
            $table->foreign('breed_id')->references('id')->on('breeds')->onDelete('cascade');



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_details');
    }
};
