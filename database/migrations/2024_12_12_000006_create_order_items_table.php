<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pesanan_id');
            $table->uuid('produk_id');

            $table->string('ukuran_id')->nullable();
            $table->string('nama_ukuran')->nullable();
            $table->string('bahan_id')->nullable();
            $table->string('nama_bahan')->nullable();
            $table->string('sisi_cetak_id')->nullable();
            $table->string('nama_sisi_cetak')->nullable();
            $table->json('finishing_ids')->nullable();
            $table->json('nama_finishing')->nullable();

            $table->integer('lebar_kustom')->nullable();
            $table->integer('tinggi_kustom')->nullable();

            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('harga_total', 15, 2);

            $table->string('nama_file_diunggah')->nullable();
            $table->string('url_file_diunggah')->nullable();
            $table->string('status_file_diunggah')->nullable();

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

            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('pesanan_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('produk_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
