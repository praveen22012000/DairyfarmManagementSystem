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
        Schema::create('veterinarians', function (Blueprint $table) {
            $table->id();

            $table->string('specialization');
            $table->date('hire_date');
            $table->date('birth_date');
            $table->string('license_number');
            $table->string('gender');
            $table->string('salary');
          
            $table->foreignId('veterinarian_id');

            $table->unique('veterinarian_id'); 

            $table->foreign('veterinarian_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veterinarians');
    }
};
