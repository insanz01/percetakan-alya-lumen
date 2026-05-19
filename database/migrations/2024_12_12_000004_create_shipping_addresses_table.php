<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pengguna_id');
            $table->string('label')->nullable();
            $table->string('nama_penerima');
            $table->string('telepon');
            $table->text('alamat');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kode_pos');
            $table->boolean('utama')->default(false);
            $table->timestamps();

            $table->foreign('pengguna_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
