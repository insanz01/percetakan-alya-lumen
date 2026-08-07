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
        if (Promo::exists()) {
            echo "Promos already seeded, skipping.\n";
            return;
        }
        // Welcome discount for new customers
        Promo::create([
            'kode' => 'WELCOME10',
            'deskripsi' => 'Diskon 10% untuk pelanggan baru. Maksimal potongan Rp 50.000.',
            'tipe' => 'percentage',
            'diskon' => 10,
            'min_beli' => 100000,
            'maks_diskon' => 50000,
            'batas_penggunaan' => 1000,
            'tanggal_mulai' => Carbon::now(),
            'tanggal_berakhir' => Carbon::now()->addMonths(6),
            'aktif' => true,
        ]);

        // Fixed discount
        Promo::create([
            'kode' => 'HEMAT50K',
            'deskripsi' => 'Potongan langsung Rp 50.000 untuk pembelian minimal Rp 500.000.',
            'tipe' => 'fixed',
            'diskon' => 50000,
            'min_beli' => 500000,
            'batas_penggunaan' => 100,
            'tanggal_mulai' => Carbon::now(),
            'tanggal_berakhir' => Carbon::now()->addMonth(),
            'aktif' => true,
        ]);

        // Seasonal promo
        Promo::create([
            'kode' => 'AKHIRTAHUN25',
            'deskripsi' => 'Promo akhir tahun! Diskon 25% untuk semua produk. Maksimal Rp 100.000.',
            'tipe' => 'percentage',
            'diskon' => 25,
            'min_beli' => 200000,
            'maks_diskon' => 100000,
            'batas_penggunaan' => 500,
            'tanggal_mulai' => Carbon::now(),
            'tanggal_berakhir' => Carbon::create(null, 12, 31, 23, 59, 59),
            'aktif' => true,
        ]);

        // Bulk order discount
        Promo::create([
            'kode' => 'BULK15',
            'deskripsi' => 'Diskon 15% untuk pesanan minimal Rp 1.000.000. Maksimal Rp 200.000.',
            'tipe' => 'percentage',
            'diskon' => 15,
            'min_beli' => 1000000,
            'maks_diskon' => 200000,
            'batas_penggunaan' => 200,
            'tanggal_mulai' => Carbon::now(),
            'tanggal_berakhir' => Carbon::now()->addMonths(3),
            'aktif' => true,
        ]);

        // First order promo
        Promo::create([
            'kode' => 'FIRSTORDER',
            'deskripsi' => 'Gratis ongkir untuk pesanan pertama (potongan Rp 30.000).',
            'tipe' => 'fixed',
            'diskon' => 30000,
            'min_beli' => 150000,
            'batas_penggunaan' => 500,
            'tanggal_mulai' => Carbon::now(),
            'tanggal_berakhir' => Carbon::now()->addMonths(12),
            'aktif' => true,
        ]);

        // Weekend promo
        Promo::create([
            'kode' => 'WEEKEND20',
            'deskripsi' => 'Diskon akhir pekan 20% untuk semua produk. Berlaku Sabtu-Minggu.',
            'tipe' => 'percentage',
            'diskon' => 20,
            'min_beli' => 100000,
            'maks_diskon' => 75000,
            'batas_penggunaan' => 300,
            'tanggal_mulai' => Carbon::now(),
            'tanggal_berakhir' => Carbon::now()->addMonths(2),
            'aktif' => true,
        ]);

        // Business promo
        Promo::create([
            'kode' => 'BISNIS100K',
            'deskripsi' => 'Potongan Rp 100.000 untuk pelanggan bisnis dengan pembelian minimal Rp 2.000.000.',
            'tipe' => 'fixed',
            'diskon' => 100000,
            'min_beli' => 2000000,
            'batas_penggunaan' => 50,
            'tanggal_mulai' => Carbon::now(),
            'tanggal_berakhir' => Carbon::now()->addMonths(3),
            'aktif' => true,
        ]);

        // Inactive promo for testing
        Promo::create([
            'kode' => 'EXPIRED',
            'deskripsi' => 'Promo ini sudah berakhir.',
            'tipe' => 'percentage',
            'diskon' => 50,
            'min_beli' => 50000,
            'tanggal_mulai' => Carbon::now()->subMonths(2),
            'tanggal_berakhir' => Carbon::now()->subMonth(),
            'aktif' => false,
        ]);

        echo "Promos seeded!\n";
    }
}
