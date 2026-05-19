<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telepon')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('peran', ['customer', 'admin', 'super_admin'])->default('customer');
            $table->boolean('aktif')->default(true);
            $table->timestamp('email_diverifikasi_pada')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
