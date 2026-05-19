<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pengguna_id')->nullable();
            $table->string('nama_asli');
            $table->string('nama_disimpan');
            $table->string('jalur');
            $table->string('disk')->default('local');
            $table->string('tipe_mime');
            $table->bigInteger('ukuran');
            $table->string('tipe')->default('design');
            $table->uuid('terkait_id')->nullable();
            $table->string('terkait_tipe')->nullable();
            $table->timestamps();

            $table->foreign('pengguna_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['terkait_id', 'terkait_tipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploaded_files');
    }
};
