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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('order_number')->unique();
            $table->uuid('user_id');

            // Shipping info
            $table->uuid('shipping_address_id')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('shipping_provider')->nullable();
            $table->string('tracking_number')->nullable();

            // Payment info
            $table->string('payment_method')->nullable();
            $table->string('payment_type')->nullable(); // bank_transfer, ewallet, etc

            // Amounts
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);

            // Status
            $table->enum('status', [
                'pending_payment',
                'payment_verified',
                'file_verification',
                'file_rejected',
                'in_production',
                'finishing',
                'shipped',
                'delivered',
                'cancelled'
            ])->default('pending_payment');

            $table->enum('payment_status', ['pending', 'paid', 'expired', 'refunded'])->default('pending');
            $table->timestamp('payment_deadline')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
