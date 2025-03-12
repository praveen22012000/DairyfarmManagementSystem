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

            $table->enum('status', ['alive', 'deceased'])->default('alive');
            $table->date('death_date')->nullable();

            $table->foreignId('sire_id')->nullable()->constrained('animal_details')->nullOnDelete();
            $table->foreignId('dam_id')->nullable()->constrained('animal_details')->nullOnDelete();


       
            
            $table->string('color');
            $table->string('weight_at_birth');
            $table->string('age_at_first_service');
            $table->string('weight_at_first_service');

            $table->timestamps();

            //this line animal_type_id is the foreign_key of the Animal_details table
            $table->foreign('animal_type_id')->references('id')->on('animal_types')->onDelete('cascade');

         



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
