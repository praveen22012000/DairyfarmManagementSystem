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
        Schema::create('general_managers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('general_manager_id');

            $table->string('qualification');
          
            $table->date('general_manager_hire_date');
          

            $table->foreign('general_manager_id')->references('id')->on('users')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_managers');
    }
};
