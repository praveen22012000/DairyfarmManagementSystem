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
        Schema::create('monthly_salary_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id');
            $table->date('month');
            $table->decimal('amount_paid');
            $table->date('payment_date');
            $table->enum('payment_status',['Paid','Unpaid']);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_salary_payments');
    }
};
