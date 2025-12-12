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
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('product_id');

            // Configuration
            $table->string('size_id')->nullable();
            $table->string('size_name')->nullable();
            $table->string('material_id')->nullable();
            $table->string('material_name')->nullable();
            $table->string('print_side_id')->nullable();
            $table->string('print_side_name')->nullable();
            $table->json('finishing_ids')->nullable();
            $table->json('finishing_names')->nullable();

            // Custom dimensions
            $table->integer('custom_width')->nullable();
            $table->integer('custom_height')->nullable();

            // Quantity and pricing
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);

            // Uploaded file
            $table->string('uploaded_file_name')->nullable();
            $table->string('uploaded_file_url')->nullable();
            $table->string('uploaded_file_status')->nullable(); // pending, approved, rejected

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

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
