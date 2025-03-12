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
        Schema::create('production_milks', function (Blueprint $table) {
            $table->id();

       

            $table->foreignId('animal_id');
            $table->foreignId('user_id');

            $table->date('production_date');

            $table->decimal('Quantity_Liters',8,2)->default(0);
            $table->decimal('initial_quantity_liters', 8, 2)->default(0);
         
            
            $table->string('shift');
        

            $table->string('fat_percentage');
            $table->string('protein_percentage');
            $table->string('lactose_percentage');
            $table->string('somatic_cell_count');

            $table->decimal('stock_quantity', 8, 2)->default(0); // Stock quantity (hidden field)

            $table->foreign('animal_id')->references('id')->on('animal_details')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_milks');
    }
};
