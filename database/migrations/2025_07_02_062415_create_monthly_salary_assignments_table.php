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
        Schema::create('monthly_salary_assignments', function (Blueprint $table) {
            $table->id();

             $table->foreignId('user_id');
                  

            $table->decimal('amount_paid', 10, 2);

            // Store the month the salary is for (use first day of the month as date)
            $table->date('salary_month');

            $table->date('paid_at');

          //  $table->unique(['user_id', 'salary_month']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_salary_assignments');
    }
};
