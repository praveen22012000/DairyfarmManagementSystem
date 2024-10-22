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
        Schema::create('milk_records', function (Blueprint $table) {
            $table->id();
            
            $table->date('milking_date');
            $table->string('cow_name');
            $table->string('cow_id');
            $table->string('morning_milk_quantity');
            $table->string('afternoon_milk_quantity');
            $table->string('evening_milk_quantity');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_records');
    }
};
