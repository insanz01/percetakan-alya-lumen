<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_pesanan')->unique();
            $table->uuid('pengguna_id');

            $table->uuid('alamat_pengiriman_id')->nullable();
            $table->string('metode_pengiriman')->nullable();
            $table->string('kurir')->nullable();
            $table->string('nomor_resi')->nullable();

            $table->string('metode_pembayaran')->nullable();
            $table->string('tipe_pembayaran')->nullable();

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('biaya_kirim', 15, 2)->default(0);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

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

            $table->enum('status_bayar', ['pending', 'paid', 'expired', 'refunded'])->default('pending');
            $table->timestamp('batas_bayar')->nullable();
            $table->timestamp('dibayar_pada')->nullable();

            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('pengguna_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
