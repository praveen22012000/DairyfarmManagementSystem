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
        Schema::create('retailor_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('retailor_id'); // foreign key to users table
            $table->decimal('total_amount', 10, 2)->default(0);

            $table->date('ordered_date');

            $table->string('delivery_address');
            $table->enum('status', ['Pending', 'Approved','Rejected','canceled', 'Paid', 'Ready for Delivery','Assigned','Out for Delivery', 'Delivered', 'Completed'])->default('Pending');
            $table->enum('payment_status', ['Unpaid','Under Review', 'Paid','Rejected'])->default('Unpaid');

            $table->boolean('is_delivered')->default(false);
            $table->boolean('refund_requested')->default(false);

            // In a migration file
            $table->time('delivered_at')->nullable();


            $table->integer('payment_attempts')->default(0);

            $table->foreignId('delivery_person_id')->nullable();
        
            $table->foreign('retailor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('delivery_person_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailor_orders');
    }
};
