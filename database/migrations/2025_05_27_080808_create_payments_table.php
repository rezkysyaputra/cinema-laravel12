<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('midtrans_transaction_id')->unique()->nullable();
            $table->string('order_id')->unique();
            $table->decimal('gross_amount', 10, 2);
            $table->string('transaction_status')->default('pending');
            $table->dateTime('transaction_time');
            $table->string('payment_type')->nullable();
            $table->string('redirect_url')->nullable();
            $table->text('response_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
