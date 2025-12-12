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
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, text, boolean, json
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Seed default settings
        DB::table('settings')->insert([
            ['key' => 'store_name', 'value' => 'PrintMaster Indonesia', 'type' => 'string', 'group' => 'general', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['key' => 'store_tagline', 'value' => 'Platform Percetakan Online Terpercaya', 'type' => 'string', 'group' => 'general', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['key' => 'store_description', 'value' => 'PrintMaster adalah platform percetakan online yang menyediakan layanan cetak berkualitas tinggi dengan harga transparan.', 'type' => 'text', 'group' => 'general', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['key' => 'store_email', 'value' => 'info@printmaster.id', 'type' => 'string', 'group' => 'contact', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['key' => 'store_phone', 'value' => '021-12345678', 'type' => 'string', 'group' => 'contact', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['key' => 'store_whatsapp', 'value' => '+62 812-3456-7890', 'type' => 'string', 'group' => 'contact', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['key' => 'store_address', 'value' => 'Jl. Percetakan No. 123, Jakarta Pusat, 10110', 'type' => 'text', 'group' => 'contact', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['key' => 'notification_new_order', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['key' => 'notification_payment', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['key' => 'notification_shipping', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
