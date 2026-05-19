<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $brosur = Category::where('slug', 'brosur-flyer')->first();
        $kartuNama = Category::where('slug', 'kartu-nama')->first();
        $banner = Category::where('slug', 'banner-spanduk')->first();
        $poster = Category::where('slug', 'poster-foto')->first();
        $undangan = Category::where('slug', 'undangan')->first();
        $stiker = Category::where('slug', 'stiker-label')->first();
        $kalender = Category::where('slug', 'kalender')->first();
        $kemasan = Category::where('slug', 'kemasan-box')->first();
        $buku = Category::where('slug', 'buku-majalah')->first();
        $atk = Category::where('slug', 'atk-perlengkapan')->first();

        // ==================== BROSUR & FLYER ====================
        if ($brosur) {
            Product::create([
                'kategori_id' => $brosur->id,
                'nama' => 'Brosur A5 Premium',
                'slug' => 'brosur-a5-premium',
                'deskripsi' => 'Brosur A5 dengan finishing premium untuk promosi bisnis Anda. Tersedia berbagai pilihan kertas mulai dari Art Paper hingga Art Carton dengan gramasi beragam. Cocok untuk materi promosi, menu restoran, dan company profile ringkas.',
                'deskripsi_singkat' => 'Brosur A5 dengan finishing premium',
                'gambar' => ['https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=600'],
                'harga_dasar' => 500,
                'ukuran' => [
                    ['id' => 'size-a5', 'name' => 'A5', 'dimensions' => '148 x 210 mm', 'priceMultiplier' => 1],
                    ['id' => 'size-a4', 'name' => 'A4', 'dimensions' => '210 x 297 mm', 'priceMultiplier' => 1.5],
                    ['id' => 'size-a3', 'name' => 'A3', 'dimensions' => '297 x 420 mm', 'priceMultiplier' => 2.5],
                    ['id' => 'size-custom', 'name' => 'Custom', 'dimensions' => 'Sesuai pesanan', 'priceMultiplier' => 1],
                ],
                'bahan' => [
                    ['id' => 'mat-art-paper-120', 'name' => 'Art Paper', 'weight' => '120gr', 'pricePerUnit' => 200, 'description' => 'Kertas glossy standar'],
                    ['id' => 'mat-art-paper-150', 'name' => 'Art Paper', 'weight' => '150gr', 'pricePerUnit' => 280, 'description' => 'Kertas glossy lebih tebal'],
                    ['id' => 'mat-art-carton-190', 'name' => 'Art Carton', 'weight' => '190gr', 'pricePerUnit' => 350, 'description' => 'Kertas tebal semi-glossy'],
                    ['id' => 'mat-art-carton-260', 'name' => 'Art Carton', 'weight' => '260gr', 'pricePerUnit' => 450, 'description' => 'Kertas tebal premium'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1.8],
                ],
                'finishing' => [
                    ['id' => 'fin-lam-doff', 'name' => 'Laminasi Doff', 'type' => 'laminating', 'price' => 150, 'description' => 'Finishing matte elegan'],
                    ['id' => 'fin-lam-glossy', 'name' => 'Laminasi Glossy', 'type' => 'laminating', 'price' => 150, 'description' => 'Finishing mengkilap'],
                    ['id' => 'fin-uv-spot', 'name' => 'UV Spot', 'type' => 'other', 'price' => 300, 'description' => 'Efek timbul mengkilap'],
                ],
                'tier_jumlah' => [
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 700],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 600],
                    ['minQty' => 500, 'maxQty' => 999, 'pricePerUnit' => 500],
                    ['minQty' => 1000, 'maxQty' => 99999, 'pricePerUnit' => 400],
                ],
                'terlaris' => true,
                'promo' => true,
                'persen_promo' => 15,
                'min_pesan' => 100,
                'estimasi_hari' => 3,
                'berat_per_pcs' => 5,
                'tipe_file_diperbolehkan' => ['pdf', 'jpg', 'jpeg', 'png', 'ai', 'psd', 'cdr'],
                'ukuran_file_maks' => 50,
                'aktif' => true,
            ]);

            Product::create([
                'kategori_id' => $brosur->id,
                'nama' => 'Flyer Lipat 3',
                'slug' => 'flyer-lipat-3',
                'deskripsi' => 'Flyer dengan lipatan 3 (tri-fold) ideal untuk brosur produk, menu restoran, atau panduan informasi. Tampilan profesional dengan space yang luas untuk konten.',
                'deskripsi_singkat' => 'Flyer lipat 3 profesional',
                'gambar' => ['https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=600'],
                'harga_dasar' => 800,
                'ukuran' => [
                    ['id' => 'size-a4', 'name' => 'A4', 'dimensions' => '210 x 297 mm (lipat)', 'priceMultiplier' => 1],
                    ['id' => 'size-dl', 'name' => 'DL', 'dimensions' => '99 x 210 mm (lipat)', 'priceMultiplier' => 0.8],
                ],
                'bahan' => [
                    ['id' => 'mat-art-carton-210', 'name' => 'Art Carton', 'weight' => '210gr', 'pricePerUnit' => 400, 'description' => 'Rekomendasi untuk lipat'],
                    ['id' => 'mat-art-carton-260', 'name' => 'Art Carton', 'weight' => '260gr', 'pricePerUnit' => 500, 'description' => 'Premium tebal'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1],
                ],
                'finishing' => [
                    ['id' => 'fin-lam-doff', 'name' => 'Laminasi Doff', 'type' => 'laminating', 'price' => 200, 'description' => 'Finishing matte'],
                    ['id' => 'fin-lam-glossy', 'name' => 'Laminasi Glossy', 'type' => 'laminating', 'price' => 200, 'description' => 'Finishing glossy'],
                    ['id' => 'fin-lipat', 'name' => 'Lipat', 'type' => 'folding', 'price' => 100, 'description' => 'Lipat sesuai desain'],
                ],
                'tier_jumlah' => [
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 1200],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 1000],
                    ['minQty' => 500, 'maxQty' => 999, 'pricePerUnit' => 850],
                    ['minQty' => 1000, 'maxQty' => 99999, 'pricePerUnit' => 700],
                ],
                'terlaris' => true,
                'min_pesan' => 100,
                'estimasi_hari' => 4,
                'berat_per_pcs' => 10,
                'tipe_file_diperbolehkan' => ['pdf', 'ai', 'psd', 'cdr'],
                'ukuran_file_maks' => 50,
                'aktif' => true,
            ]);
        }

        // ==================== KARTU NAMA ====================
        if ($kartuNama) {
            Product::create([
                'kategori_id' => $kartuNama->id,
                'nama' => 'Kartu Nama Standar',
                'slug' => 'kartu-nama-standar',
                'deskripsi' => 'Kartu nama ukuran standar (9x5.5 cm) dengan berbagai pilihan kertas. Tampilkan profesionalitas bisnis Anda dengan kartu nama berkualitas.',
                'deskripsi_singkat' => 'Kartu nama profesional ukuran standar',
                'gambar' => ['https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=600'],
                'harga_dasar' => 100,
                'ukuran' => [
                    ['id' => 'size-standar', 'name' => 'Standar', 'dimensions' => '90 x 55 mm', 'priceMultiplier' => 1],
                    ['id' => 'size-square', 'name' => 'Square', 'dimensions' => '55 x 55 mm', 'priceMultiplier' => 0.9],
                ],
                'bahan' => [
                    ['id' => 'mat-art-carton-260', 'name' => 'Art Carton', 'weight' => '260gr', 'pricePerUnit' => 50, 'description' => 'Standar profesional'],
                    ['id' => 'mat-art-carton-310', 'name' => 'Art Carton', 'weight' => '310gr', 'pricePerUnit' => 80, 'description' => 'Extra tebal'],
                    ['id' => 'mat-linen', 'name' => 'Linen', 'weight' => '280gr', 'pricePerUnit' => 120, 'description' => 'Tekstur premium'],
                    ['id' => 'mat-ivory', 'name' => 'Ivory', 'weight' => '260gr', 'pricePerUnit' => 100, 'description' => 'Warna cream elegan'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1.6],
                ],
                'finishing' => [
                    ['id' => 'fin-lam-doff', 'name' => 'Laminasi Doff', 'type' => 'laminating', 'price' => 30, 'description' => 'Matte elegan'],
                    ['id' => 'fin-lam-glossy', 'name' => 'Laminasi Glossy', 'type' => 'laminating', 'price' => 30, 'description' => 'Mengkilap'],
                    ['id' => 'fin-emboss', 'name' => 'Emboss', 'type' => 'other', 'price' => 100, 'description' => 'Efek timbul'],
                    ['id' => 'fin-spot-uv', 'name' => 'Spot UV', 'type' => 'other', 'price' => 80, 'description' => 'Highlight mengkilap'],
                ],
                'tier_jumlah' => [
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 300],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 250],
                    ['minQty' => 500, 'maxQty' => 999, 'pricePerUnit' => 200],
                    ['minQty' => 1000, 'maxQty' => 99999, 'pricePerUnit' => 150],
                ],
                'terlaris' => true,
                'min_pesan' => 100,
                'estimasi_hari' => 2,
                'berat_per_pcs' => 2,
                'tipe_file_diperbolehkan' => ['pdf', 'ai', 'psd', 'cdr', 'jpg', 'png'],
                'ukuran_file_maks' => 20,
                'aktif' => true,
            ]);

            Product::create([
                'kategori_id' => $kartuNama->id,
                'nama' => 'Kartu Nama Premium',
                'slug' => 'kartu-nama-premium',
                'deskripsi' => 'Kartu nama dengan material dan finishing premium. Pilihan kertas eksklusif seperti Kraft, Linen, atau Soft Touch dengan berbagai opsi finishing mewah.',
                'deskripsi_singkat' => 'Kartu nama eksklusif dengan finishing premium',
                'gambar' => ['https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=600'],
                'harga_dasar' => 200,
                'ukuran' => [
                    ['id' => 'size-standar', 'name' => 'Standar', 'dimensions' => '90 x 55 mm', 'priceMultiplier' => 1],
                ],
                'bahan' => [
                    ['id' => 'mat-kraft', 'name' => 'Kraft', 'weight' => '300gr', 'pricePerUnit' => 150, 'description' => 'Tampilan natural'],
                    ['id' => 'mat-soft-touch', 'name' => 'Soft Touch', 'weight' => '350gr', 'pricePerUnit' => 250, 'description' => 'Tekstur lembut'],
                    ['id' => 'mat-metallic', 'name' => 'Metallic', 'weight' => '300gr', 'pricePerUnit' => 300, 'description' => 'Efek metalik'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1],
                ],
                'finishing' => [
                    ['id' => 'fin-emboss', 'name' => 'Emboss', 'type' => 'other', 'price' => 150, 'description' => 'Efek timbul premium'],
                    ['id' => 'fin-foil-gold', 'name' => 'Hot Foil Gold', 'type' => 'other', 'price' => 200, 'description' => 'Foil emas'],
                    ['id' => 'fin-foil-silver', 'name' => 'Hot Foil Silver', 'type' => 'other', 'price' => 200, 'description' => 'Foil silver'],
                    ['id' => 'fin-edge-color', 'name' => 'Edge Coloring', 'type' => 'other', 'price' => 300, 'description' => 'Warna pada sisi kartu'],
                ],
                'tier_jumlah' => [
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 600],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 500],
                    ['minQty' => 500, 'maxQty' => 999, 'pricePerUnit' => 400],
                    ['minQty' => 1000, 'maxQty' => 99999, 'pricePerUnit' => 350],
                ],
                'promo' => true,
                'persen_promo' => 10,
                'min_pesan' => 100,
                'estimasi_hari' => 5,
                'berat_per_pcs' => 3,
                'tipe_file_diperbolehkan' => ['pdf', 'ai', 'psd', 'cdr'],
                'ukuran_file_maks' => 20,
                'aktif' => true,
            ]);
        }

        // ==================== BANNER & SPANDUK ====================
        if ($banner) {
            Product::create([
                'kategori_id' => $banner->id,
                'nama' => 'X-Banner 60x160',
                'slug' => 'x-banner-60x160',
                'deskripsi' => 'X-Banner ukuran 60x160 cm dengan tiang aluminium. Mudah dipasang dan dibawa kemana saja. Ideal untuk pameran, toko, dan promosi indoor.',
                'deskripsi_singkat' => 'X-Banner portable dengan tiang',
                'gambar' => ['https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600'],
                'harga_dasar' => 50000,
                'ukuran' => [
                    ['id' => 'size-60x160', 'name' => '60 x 160 cm', 'dimensions' => '60 x 160 cm', 'priceMultiplier' => 1],
                    ['id' => 'size-80x180', 'name' => '80 x 180 cm', 'dimensions' => '80 x 180 cm', 'priceMultiplier' => 1.4],
                ],
                'bahan' => [
                    ['id' => 'mat-albatros', 'name' => 'Albatros', 'weight' => '280gsm', 'pricePerUnit' => 0, 'description' => 'Material standar indoor'],
                    ['id' => 'mat-flexi-korea', 'name' => 'Flexi Korea', 'weight' => '340gsm', 'pricePerUnit' => 15000, 'description' => 'Lebih tebal dan tahan lama'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                ],
                'finishing' => [],
                'tier_jumlah' => [
                    ['minQty' => 1, 'maxQty' => 4, 'pricePerUnit' => 85000],
                    ['minQty' => 5, 'maxQty' => 9, 'pricePerUnit' => 75000],
                    ['minQty' => 10, 'maxQty' => 99999, 'pricePerUnit' => 65000],
                ],
                'terlaris' => true,
                'min_pesan' => 1,
                'estimasi_hari' => 2,
                'berat_per_pcs' => 1200,
                'tipe_file_diperbolehkan' => ['pdf', 'ai', 'psd', 'cdr', 'jpg', 'png'],
                'ukuran_file_maks' => 100,
                'aktif' => true,
            ]);

            Product::create([
                'kategori_id' => $banner->id,
                'nama' => 'Roll Up Banner',
                'slug' => 'roll-up-banner',
                'deskripsi' => 'Roll Up Banner dengan sistem gulung otomatis. Profesional, mudah dibawa, dan tahan lama. Pilihan tepat untuk presentasi dan pameran.',
                'deskripsi_singkat' => 'Banner gulung otomatis premium',
                'gambar' => ['https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600'],
                'harga_dasar' => 150000,
                'ukuran' => [
                    ['id' => 'size-80x200', 'name' => '80 x 200 cm', 'dimensions' => '80 x 200 cm', 'priceMultiplier' => 1],
                    ['id' => 'size-85x200', 'name' => '85 x 200 cm', 'dimensions' => '85 x 200 cm', 'priceMultiplier' => 1.1],
                    ['id' => 'size-100x200', 'name' => '100 x 200 cm', 'dimensions' => '100 x 200 cm', 'priceMultiplier' => 1.3],
                ],
                'bahan' => [
                    ['id' => 'mat-flexi-korea', 'name' => 'Flexi Korea', 'weight' => '340gsm', 'pricePerUnit' => 0, 'description' => 'Standar roll up'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                ],
                'finishing' => [],
                'tier_jumlah' => [
                    ['minQty' => 1, 'maxQty' => 4, 'pricePerUnit' => 350000],
                    ['minQty' => 5, 'maxQty' => 9, 'pricePerUnit' => 300000],
                    ['minQty' => 10, 'maxQty' => 99999, 'pricePerUnit' => 250000],
                ],
                'min_pesan' => 1,
                'estimasi_hari' => 2,
                'berat_per_pcs' => 3000,
                'tipe_file_diperbolehkan' => ['pdf', 'ai', 'psd', 'cdr', 'jpg', 'png'],
                'ukuran_file_maks' => 100,
                'aktif' => true,
            ]);

            Product::create([
                'kategori_id' => $banner->id,
                'nama' => 'Spanduk Outdoor',
                'slug' => 'spanduk-outdoor',
                'deskripsi' => 'Spanduk untuk promosi outdoor dengan bahan flexi tahan cuaca. Dilengkapi mata ayam dan tali. Tahan sinar UV dan hujan.',
                'deskripsi_singkat' => 'Spanduk tahan cuaca dengan mata ayam',
                'gambar' => ['https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600'],
                'harga_dasar' => 25000,
                'ukuran' => [
                    ['id' => 'size-per-m2', 'name' => 'Per m²', 'dimensions' => 'Harga per meter persegi', 'priceMultiplier' => 1],
                ],
                'bahan' => [
                    ['id' => 'mat-flexi-280', 'name' => 'Flexi China', 'weight' => '280gsm', 'pricePerUnit' => 0, 'description' => 'Ekonomis'],
                    ['id' => 'mat-flexi-340', 'name' => 'Flexi Korea', 'weight' => '340gsm', 'pricePerUnit' => 15000, 'description' => 'Lebih awet'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1.8],
                ],
                'finishing' => [
                    ['id' => 'fin-mata-ayam', 'name' => 'Mata Ayam', 'type' => 'other', 'price' => 5000, 'description' => 'Per mata ayam'],
                    ['id' => 'fin-jahit-pinggir', 'name' => 'Jahit Pinggir', 'type' => 'other', 'price' => 10000, 'description' => 'Per meter'],
                ],
                'tier_jumlah' => [
                    ['minQty' => 1, 'maxQty' => 9, 'pricePerUnit' => 55000],
                    ['minQty' => 10, 'maxQty' => 49, 'pricePerUnit' => 45000],
                    ['minQty' => 50, 'maxQty' => 99999, 'pricePerUnit' => 38000],
                ],
                'min_pesan' => 1,
                'estimasi_hari' => 2,
                'berat_per_pcs' => 500,
                'tipe_file_diperbolehkan' => ['pdf', 'ai', 'psd', 'cdr', 'jpg', 'png'],
                'ukuran_file_maks' => 100,
                'aktif' => true,
            ]);
        }

            Product::create([
                'kategori_id' => $banner->id,
                'nama' => 'Spanduk Indoor',
                'slug' => 'spanduk-indoor',
                'deskripsi' => 'Spanduk indoor dengan bahan premium untuk tampilan di dalam ruangan. Warna cetak tajam dan tahan lama, cocok untuk dekorasi toko, pameran dalam ruangan, dan backdrop acara.',
                'deskripsi_singkat' => 'Spanduk indoor berkualitas untuk dekorasi dalam ruangan',
                'gambar' => ['https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600'],
                'harga_dasar' => 20000,
                'ukuran' => [
                    ['id' => 'size-per-m2', 'name' => 'Per m²', 'dimensions' => 'Harga per meter persegi', 'priceMultiplier' => 1],
                ],
                'bahan' => [
                    ['id' => 'mat-albatros', 'name' => 'Albatros', 'weight' => '280gsm', 'pricePerUnit' => 0, 'description' => 'Material standar indoor'],
                    ['id' => 'mat-canvas', 'name' => 'Canvas', 'weight' => '380gsm', 'pricePerUnit' => 20000, 'description' => 'Tampilan premium'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                ],
                'finishing' => [
                    ['id' => 'fin-jahit-pinggir', 'name' => 'Jahit Pinggir', 'type' => 'other', 'price' => 8000, 'description' => 'Per meter'],
                ],
                'tier_jumlah' => [
                    ['minQty' => 1, 'maxQty' => 9, 'pricePerUnit' => 45000],
                    ['minQty' => 10, 'maxQty' => 49, 'pricePerUnit' => 38000],
                    ['minQty' => 50, 'maxQty' => 99999, 'pricePerUnit' => 30000],
                ],
                'terlaris' => true,
                'min_pesan' => 1,
                'estimasi_hari' => 2,
                'berat_per_pcs' => 400,
                'tipe_file_diperbolehkan' => ['pdf', 'ai', 'psd', 'cdr', 'jpg', 'png'],
                'ukuran_file_maks' => 100,
                'aktif' => true,
            ]);
        }

        // ==================== STIKER ====================
        if ($stiker) {
            Product::create([
                'kategori_id' => $stiker->id,
                'nama' => 'Stiker Vinyl',
                'slug' => 'stiker-vinyl',
                'deskripsi' => 'Stiker vinyl berkualitas tinggi, tahan air dan tahan UV. Cocok untuk label produk outdoor, stiker kendaraan, dan branding.',
                'deskripsi_singkat' => 'Stiker tahan air & UV',
                'gambar' => ['https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?w=600'],
                'harga_dasar' => 500,
                'ukuran' => [
                    ['id' => 'size-a5', 'name' => 'A5', 'dimensions' => '148 x 210 mm', 'priceMultiplier' => 1],
                    ['id' => 'size-a4', 'name' => 'A4', 'dimensions' => '210 x 297 mm', 'priceMultiplier' => 1.8],
                    ['id' => 'size-a3', 'name' => 'A3', 'dimensions' => '297 x 420 mm', 'priceMultiplier' => 3],
                    ['id' => 'size-custom', 'name' => 'Custom', 'dimensions' => 'Sesuai kebutuhan', 'priceMultiplier' => 1],
                ],
                'bahan' => [
                    ['id' => 'mat-vinyl-white', 'name' => 'Vinyl White', 'weight' => '', 'pricePerUnit' => 0, 'description' => 'Putih doff'],
                    ['id' => 'mat-vinyl-trans', 'name' => 'Vinyl Transparan', 'weight' => '', 'pricePerUnit' => 200, 'description' => 'Bening'],
                    ['id' => 'mat-vinyl-chrome', 'name' => 'Vinyl Chrome', 'weight' => '', 'pricePerUnit' => 500, 'description' => 'Efek chrome'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                ],
                'finishing' => [
                    ['id' => 'fin-die-cut', 'name' => 'Die Cut', 'type' => 'cutting', 'price' => 200, 'description' => 'Potong sesuai bentuk'],
                    ['id' => 'fin-kiss-cut', 'name' => 'Kiss Cut', 'type' => 'cutting', 'price' => 150, 'description' => 'Potong stiker saja'],
                ],
                'tier_jumlah' => [
                    ['minQty' => 50, 'maxQty' => 99, 'pricePerUnit' => 1500],
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 1200],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 1000],
                    ['minQty' => 500, 'maxQty' => 99999, 'pricePerUnit' => 800],
                ],
                'terlaris' => true,
                'min_pesan' => 50,
                'estimasi_hari' => 3,
                'berat_per_pcs' => 5,
                'tipe_file_diperbolehkan' => ['pdf', 'ai', 'psd', 'cdr', 'png'],
                'ukuran_file_maks' => 50,
                'aktif' => true,
            ]);
        }

        // ==================== UNDANGAN ====================
        if ($undangan) {
            Product::create([
                'kategori_id' => $undangan->id,
                'nama' => 'Undangan Pernikahan Eksklusif',
                'slug' => 'undangan-pernikahan-eksklusif',
                'deskripsi' => 'Undangan pernikahan eksklusif dengan kertas premium dan finishing mewah. Tersedia berbagai pilihan desain romantis dan elegan.',
                'deskripsi_singkat' => 'Undangan pernikahan premium',
                'gambar' => ['https://images.unsplash.com/photo-1607190074257-dd4b7af0309f?w=600'],
                'harga_dasar' => 3000,
                'ukuran' => [
                    ['id' => 'size-single', 'name' => 'Single Card', 'dimensions' => '15 x 10 cm', 'priceMultiplier' => 1],
                    ['id' => 'size-pocket', 'name' => 'Pocket', 'dimensions' => '15 x 20 cm', 'priceMultiplier' => 1.5],
                ],
                'bahan' => [
                    ['id' => 'mat-linen-280', 'name' => 'Linen', 'weight' => '280gr', 'pricePerUnit' => 500, 'description' => 'Tekstur premium'],
                    ['id' => 'mat-jasmine-300', 'name' => 'Jasmine', 'weight' => '300gr', 'pricePerUnit' => 700, 'description' => 'Dengan glitter'],
                    ['id' => 'mat-duplex', 'name' => 'Duplex', 'weight' => '310gr', 'pricePerUnit' => 600, 'description' => 'Dua warna'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1],
                ],
                'finishing' => [
                    ['id' => 'fin-foil-gold', 'name' => 'Hot Foil Gold', 'type' => 'other', 'price' => 500, 'description' => 'Foil emas'],
                    ['id' => 'fin-foil-rose', 'name' => 'Hot Foil Rose Gold', 'type' => 'other', 'price' => 600, 'description' => 'Foil rose gold'],
                    ['id' => 'fin-emboss', 'name' => 'Emboss', 'type' => 'other', 'price' => 400, 'description' => 'Efek timbul'],
                    ['id' => 'fin-pita', 'name' => 'Pita Satin', 'type' => 'other', 'price' => 1000, 'description' => 'Pita dekorasi'],
                ],
                'tier_jumlah' => [
                    ['minQty' => 100, 'maxQty' => 199, 'pricePerUnit' => 8000],
                    ['minQty' => 200, 'maxQty' => 499, 'pricePerUnit' => 6500],
                    ['minQty' => 500, 'maxQty' => 999, 'pricePerUnit' => 5500],
                    ['minQty' => 1000, 'maxQty' => 99999, 'pricePerUnit' => 4500],
                ],
                'promo' => true,
                'persen_promo' => 20,
                'min_pesan' => 100,
                'estimasi_hari' => 7,
                'berat_per_pcs' => 20,
                'tipe_file_diperbolehkan' => ['pdf', 'ai', 'psd', 'cdr'],
                'ukuran_file_maks' => 50,
                'aktif' => true,
            ]);
        }

        // ==================== KALENDER ====================
        if ($kalender) {
            Product::create([
                'kategori_id' => $kalender->id,
                'nama' => 'Kalender Meja Custom',
                'slug' => 'kalender-meja-custom',
                'deskripsi' => 'Kalender meja custom dengan desain sendiri. 13 lembar (cover + 12 bulan). Cocok untuk hadiah perusahaan dan promosi brand.',
                'deskripsi_singkat' => 'Kalender meja 13 lembar custom',
                'gambar' => ['https://images.unsplash.com/photo-1506784365847-bbad939e9335?w=600'],
                'harga_dasar' => 10000,
                'ukuran' => [
                    ['id' => 'size-a5', 'name' => 'A5', 'dimensions' => '148 x 210 mm', 'priceMultiplier' => 1],
                    ['id' => 'size-a4', 'name' => 'A4', 'dimensions' => '210 x 297 mm', 'priceMultiplier' => 1.5],
                ],
                'bahan' => [
                    ['id' => 'mat-art-carton-260', 'name' => 'Art Carton', 'weight' => '260gr', 'pricePerUnit' => 0, 'description' => 'Standar'],
                    ['id' => 'mat-art-carton-310', 'name' => 'Art Carton', 'weight' => '310gr', 'pricePerUnit' => 3000, 'description' => 'Premium'],
                ],
                'sisi_cetak' => [
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1],
                ],
                'finishing' => [
                    ['id' => 'fin-lam-doff', 'name' => 'Laminasi Doff', 'type' => 'laminating', 'price' => 2000, 'description' => 'Cover doff'],
                    ['id' => 'fin-ring', 'name' => 'Ring Standar', 'type' => 'binding', 'price' => 5000, 'description' => 'Ring plastik'],
                    ['id' => 'fin-stand', 'name' => 'Stand/Dudukan', 'type' => 'other', 'price' => 3000, 'description' => 'Karton dudukan'],
                ],
                'tier_jumlah' => [
                    ['minQty' => 50, 'maxQty' => 99, 'pricePerUnit' => 35000],
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 28000],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 23000],
                    ['minQty' => 500, 'maxQty' => 99999, 'pricePerUnit' => 18000],
                ],
                'terlaris' => true,
                'min_pesan' => 50,
                'estimasi_hari' => 7,
                'berat_per_pcs' => 150,
                'tipe_file_diperbolehkan' => ['pdf', 'ai', 'psd', 'cdr'],
                'ukuran_file_maks' => 100,
                'aktif' => true,
            ]);
        }

        echo "Products seeded!\n";
    }
}
