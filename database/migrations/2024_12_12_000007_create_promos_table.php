<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode')->unique();
            $table->string('deskripsi')->nullable();
            $table->enum('tipe', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('diskon', 10, 2);
            $table->decimal('min_beli', 15, 2)->default(0);
            $table->decimal('maks_diskon', 15, 2)->nullable();
            $table->integer('batas_penggunaan')->nullable();
            $table->integer('jumlah_penggunaan')->default(0);
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_berakhir')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
