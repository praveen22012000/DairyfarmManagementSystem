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
        Schema::create('purchase_vaccine_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_id');
            $table->foreignId('payment_receiver');

            $table->decimal('payment_amount');
            $table->string('reference_number');
            $table->date('payment_date');

            $table->foreign('purchase_id')->references('id')->on('purchase_vaccines')->onDelete('cascade');
            $table->foreign('payment_receiver')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_vaccine_payments');
    }
};
