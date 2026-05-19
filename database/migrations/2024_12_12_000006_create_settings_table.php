<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('kunci')->unique();
            $table->text('nilai')->nullable();
            $table->string('tipe')->default('string');
            $table->string('grup')->default('general');
            $table->timestamps();
        });

        DB::table('settings')->insert([
            ['kunci' => 'store_name', 'nilai' => 'PrintMaster Indonesia', 'tipe' => 'string', 'grup' => 'general', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['kunci' => 'store_tagline', 'nilai' => 'Platform Percetakan Online Terpercaya', 'tipe' => 'string', 'grup' => 'general', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['kunci' => 'store_description', 'nilai' => 'PrintMaster adalah platform percetakan online yang menyediakan layanan cetak berkualitas tinggi dengan harga transparan.', 'tipe' => 'text', 'grup' => 'general', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['kunci' => 'store_email', 'nilai' => 'info@printmaster.id', 'tipe' => 'string', 'grup' => 'contact', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['kunci' => 'store_phone', 'nilai' => '021-12345678', 'tipe' => 'string', 'grup' => 'contact', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['kunci' => 'store_whatsapp', 'nilai' => '+62 812-3456-7890', 'tipe' => 'string', 'grup' => 'contact', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['kunci' => 'store_address', 'nilai' => 'Jl. Percetakan No. 123, Jakarta Pusat, 10110', 'tipe' => 'text', 'grup' => 'contact', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['kunci' => 'notification_new_order', 'nilai' => 'true', 'tipe' => 'boolean', 'grup' => 'notifications', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['kunci' => 'notification_payment', 'nilai' => 'true', 'tipe' => 'boolean', 'grup' => 'notifications', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['kunci' => 'notification_shipping', 'nilai' => 'true', 'tipe' => 'boolean', 'grup' => 'notifications', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
