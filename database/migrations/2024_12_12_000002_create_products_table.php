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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('category_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->json('images')->nullable(); // Array of image URLs
            $table->decimal('base_price', 15, 2)->default(0);

            // Options stored as JSON
            $table->json('sizes')->nullable();
            $table->json('materials')->nullable();
            $table->json('print_sides')->nullable();
            $table->json('finishings')->nullable();
            $table->json('quantity_tiers')->nullable();

            // Meta
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_promo')->default(false);
            $table->integer('promo_percentage')->nullable();
            $table->integer('min_order_qty')->default(1);
            $table->integer('estimated_days')->default(3);
            $table->integer('weight_per_piece')->default(0); // in grams

            // Product type
            $table->boolean('is_retail_product')->default(false);
            $table->boolean('requires_design_file')->default(true);

            // File requirements
            $table->json('allowed_file_types')->nullable();
            $table->integer('max_file_size')->default(50); // in MB

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
