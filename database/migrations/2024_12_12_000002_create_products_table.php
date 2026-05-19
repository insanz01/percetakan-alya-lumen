<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kategori_id');
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('deskripsi_singkat')->nullable();
            $table->json('gambar')->nullable();

            $table->json('ukuran')->nullable();
            $table->json('bahan')->nullable();
            $table->json('sisi_cetak')->nullable();
            $table->json('finishing')->nullable();
            $table->json('tier_jumlah')->nullable();

            $table->boolean('terlaris')->default(false);
            $table->boolean('promo')->default(false);
            $table->integer('persen_promo')->nullable();
            $table->integer('min_pesan')->default(1);
            $table->integer('estimasi_hari')->default(3);
            $table->integer('berat_per_pcs')->default(0);

            $table->boolean('produk_retail')->default(false);
            $table->boolean('butuh_file_desain')->default(true);

            $table->json('tipe_file_diperbolehkan')->nullable();
            $table->integer('ukuran_file_maks')->default(50);

            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
