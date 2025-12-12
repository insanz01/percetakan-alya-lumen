<?php

namespace Database\Seeders;

use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Welcome discount for new customers
        Promo::create([
            'code' => 'WELCOME10',
            'description' => 'Diskon 10% untuk pelanggan baru. Maksimal potongan Rp 50.000.',
            'type' => 'percentage',
            'discount' => 10,
            'min_purchase' => 100000,
            'max_discount' => 50000,
            'usage_limit' => 1000,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(6),
            'is_active' => true,
        ]);

        // Fixed discount
        Promo::create([
            'code' => 'HEMAT50K',
            'description' => 'Potongan langsung Rp 50.000 untuk pembelian minimal Rp 500.000.',
            'type' => 'fixed',
            'discount' => 50000,
            'min_purchase' => 500000,
            'usage_limit' => 100,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
            'is_active' => true,
        ]);

        // Seasonal promo
        Promo::create([
            'code' => 'AKHIRTAHUN25',
            'description' => 'Promo akhir tahun! Diskon 25% untuk semua produk. Maksimal Rp 100.000.',
            'type' => 'percentage',
            'discount' => 25,
            'min_purchase' => 200000,
            'max_discount' => 100000,
            'usage_limit' => 500,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::create(null, 12, 31, 23, 59, 59),
            'is_active' => true,
        ]);

        // Bulk order discount
        Promo::create([
            'code' => 'BULK15',
            'description' => 'Diskon 15% untuk pesanan minimal Rp 1.000.000. Maksimal Rp 200.000.',
            'type' => 'percentage',
            'discount' => 15,
            'min_purchase' => 1000000,
            'max_discount' => 200000,
            'usage_limit' => 200,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(3),
            'is_active' => true,
        ]);

        // First order promo
        Promo::create([
            'code' => 'FIRSTORDER',
            'description' => 'Gratis ongkir untuk pesanan pertama (potongan Rp 30.000).',
            'type' => 'fixed',
            'discount' => 30000,
            'min_purchase' => 150000,
            'usage_limit' => 500,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(12),
            'is_active' => true,
        ]);

        // Weekend promo
        Promo::create([
            'code' => 'WEEKEND20',
            'description' => 'Diskon akhir pekan 20% untuk semua produk. Berlaku Sabtu-Minggu.',
            'type' => 'percentage',
            'discount' => 20,
            'min_purchase' => 100000,
            'max_discount' => 75000,
            'usage_limit' => 300,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(2),
            'is_active' => true,
        ]);

        // Business promo
        Promo::create([
            'code' => 'BISNIS100K',
            'description' => 'Potongan Rp 100.000 untuk pelanggan bisnis dengan pembelian minimal Rp 2.000.000.',
            'type' => 'fixed',
            'discount' => 100000,
            'min_purchase' => 2000000,
            'usage_limit' => 50,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(3),
            'is_active' => true,
        ]);

        // Inactive promo for testing
        Promo::create([
            'code' => 'EXPIRED',
            'description' => 'Promo ini sudah berakhir.',
            'type' => 'percentage',
            'discount' => 50,
            'min_purchase' => 50000,
            'start_date' => Carbon::now()->subMonths(2),
            'end_date' => Carbon::now()->subMonth(),
            'is_active' => false,
        ]);

        echo "Promos seeded!\n";
    }
}
