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
                'category_id' => $brosur->id,
                'name' => 'Brosur A5 Premium',
                'slug' => 'brosur-a5-premium',
                'description' => 'Brosur A5 dengan finishing premium untuk promosi bisnis Anda. Tersedia berbagai pilihan kertas mulai dari Art Paper hingga Art Carton dengan gramasi beragam. Cocok untuk materi promosi, menu restoran, dan company profile ringkas.',
                'short_description' => 'Brosur A5 dengan finishing premium',
                'images' => ['https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=600'],
                'base_price' => 500,
                'sizes' => [
                    ['id' => 'size-a5', 'name' => 'A5', 'dimensions' => '148 x 210 mm', 'priceMultiplier' => 1],
                    ['id' => 'size-a4', 'name' => 'A4', 'dimensions' => '210 x 297 mm', 'priceMultiplier' => 1.5],
                    ['id' => 'size-a3', 'name' => 'A3', 'dimensions' => '297 x 420 mm', 'priceMultiplier' => 2.5],
                    ['id' => 'size-custom', 'name' => 'Custom', 'dimensions' => 'Sesuai pesanan', 'priceMultiplier' => 1],
                ],
                'materials' => [
                    ['id' => 'mat-art-paper-120', 'name' => 'Art Paper', 'weight' => '120gr', 'pricePerUnit' => 200, 'description' => 'Kertas glossy standar'],
                    ['id' => 'mat-art-paper-150', 'name' => 'Art Paper', 'weight' => '150gr', 'pricePerUnit' => 280, 'description' => 'Kertas glossy lebih tebal'],
                    ['id' => 'mat-art-carton-190', 'name' => 'Art Carton', 'weight' => '190gr', 'pricePerUnit' => 350, 'description' => 'Kertas tebal semi-glossy'],
                    ['id' => 'mat-art-carton-260', 'name' => 'Art Carton', 'weight' => '260gr', 'pricePerUnit' => 450, 'description' => 'Kertas tebal premium'],
                ],
                'print_sides' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1.8],
                ],
                'finishings' => [
                    ['id' => 'fin-lam-doff', 'name' => 'Laminasi Doff', 'type' => 'laminating', 'price' => 150, 'description' => 'Finishing matte elegan'],
                    ['id' => 'fin-lam-glossy', 'name' => 'Laminasi Glossy', 'type' => 'laminating', 'price' => 150, 'description' => 'Finishing mengkilap'],
                    ['id' => 'fin-uv-spot', 'name' => 'UV Spot', 'type' => 'other', 'price' => 300, 'description' => 'Efek timbul mengkilap'],
                ],
                'quantity_tiers' => [
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 700],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 600],
                    ['minQty' => 500, 'maxQty' => 999, 'pricePerUnit' => 500],
                    ['minQty' => 1000, 'maxQty' => 99999, 'pricePerUnit' => 400],
                ],
                'is_best_seller' => true,
                'is_promo' => true,
                'promo_percentage' => 15,
                'min_order_qty' => 100,
                'estimated_days' => 3,
                'weight_per_piece' => 5,
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png', 'ai', 'psd', 'cdr'],
                'max_file_size' => 50,
                'is_active' => true,
            ]);

            Product::create([
                'category_id' => $brosur->id,
                'name' => 'Flyer Lipat 3',
                'slug' => 'flyer-lipat-3',
                'description' => 'Flyer dengan lipatan 3 (tri-fold) ideal untuk brosur produk, menu restoran, atau panduan informasi. Tampilan profesional dengan space yang luas untuk konten.',
                'short_description' => 'Flyer lipat 3 profesional',
                'images' => ['https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=600'],
                'base_price' => 800,
                'sizes' => [
                    ['id' => 'size-a4', 'name' => 'A4', 'dimensions' => '210 x 297 mm (lipat)', 'priceMultiplier' => 1],
                    ['id' => 'size-dl', 'name' => 'DL', 'dimensions' => '99 x 210 mm (lipat)', 'priceMultiplier' => 0.8],
                ],
                'materials' => [
                    ['id' => 'mat-art-carton-210', 'name' => 'Art Carton', 'weight' => '210gr', 'pricePerUnit' => 400, 'description' => 'Rekomendasi untuk lipat'],
                    ['id' => 'mat-art-carton-260', 'name' => 'Art Carton', 'weight' => '260gr', 'pricePerUnit' => 500, 'description' => 'Premium tebal'],
                ],
                'print_sides' => [
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1],
                ],
                'finishings' => [
                    ['id' => 'fin-lam-doff', 'name' => 'Laminasi Doff', 'type' => 'laminating', 'price' => 200, 'description' => 'Finishing matte'],
                    ['id' => 'fin-lam-glossy', 'name' => 'Laminasi Glossy', 'type' => 'laminating', 'price' => 200, 'description' => 'Finishing glossy'],
                    ['id' => 'fin-lipat', 'name' => 'Lipat', 'type' => 'folding', 'price' => 100, 'description' => 'Lipat sesuai desain'],
                ],
                'quantity_tiers' => [
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 1200],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 1000],
                    ['minQty' => 500, 'maxQty' => 999, 'pricePerUnit' => 850],
                    ['minQty' => 1000, 'maxQty' => 99999, 'pricePerUnit' => 700],
                ],
                'is_best_seller' => true,
                'min_order_qty' => 100,
                'estimated_days' => 4,
                'weight_per_piece' => 10,
                'allowed_file_types' => ['pdf', 'ai', 'psd', 'cdr'],
                'max_file_size' => 50,
                'is_active' => true,
            ]);
        }

        // ==================== KARTU NAMA ====================
        if ($kartuNama) {
            Product::create([
                'category_id' => $kartuNama->id,
                'name' => 'Kartu Nama Standar',
                'slug' => 'kartu-nama-standar',
                'description' => 'Kartu nama ukuran standar (9x5.5 cm) dengan berbagai pilihan kertas. Tampilkan profesionalitas bisnis Anda dengan kartu nama berkualitas.',
                'short_description' => 'Kartu nama profesional ukuran standar',
                'images' => ['https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=600'],
                'base_price' => 100,
                'sizes' => [
                    ['id' => 'size-standar', 'name' => 'Standar', 'dimensions' => '90 x 55 mm', 'priceMultiplier' => 1],
                    ['id' => 'size-square', 'name' => 'Square', 'dimensions' => '55 x 55 mm', 'priceMultiplier' => 0.9],
                ],
                'materials' => [
                    ['id' => 'mat-art-carton-260', 'name' => 'Art Carton', 'weight' => '260gr', 'pricePerUnit' => 50, 'description' => 'Standar profesional'],
                    ['id' => 'mat-art-carton-310', 'name' => 'Art Carton', 'weight' => '310gr', 'pricePerUnit' => 80, 'description' => 'Extra tebal'],
                    ['id' => 'mat-linen', 'name' => 'Linen', 'weight' => '280gr', 'pricePerUnit' => 120, 'description' => 'Tekstur premium'],
                    ['id' => 'mat-ivory', 'name' => 'Ivory', 'weight' => '260gr', 'pricePerUnit' => 100, 'description' => 'Warna cream elegan'],
                ],
                'print_sides' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1.6],
                ],
                'finishings' => [
                    ['id' => 'fin-lam-doff', 'name' => 'Laminasi Doff', 'type' => 'laminating', 'price' => 30, 'description' => 'Matte elegan'],
                    ['id' => 'fin-lam-glossy', 'name' => 'Laminasi Glossy', 'type' => 'laminating', 'price' => 30, 'description' => 'Mengkilap'],
                    ['id' => 'fin-emboss', 'name' => 'Emboss', 'type' => 'other', 'price' => 100, 'description' => 'Efek timbul'],
                    ['id' => 'fin-spot-uv', 'name' => 'Spot UV', 'type' => 'other', 'price' => 80, 'description' => 'Highlight mengkilap'],
                ],
                'quantity_tiers' => [
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 300],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 250],
                    ['minQty' => 500, 'maxQty' => 999, 'pricePerUnit' => 200],
                    ['minQty' => 1000, 'maxQty' => 99999, 'pricePerUnit' => 150],
                ],
                'is_best_seller' => true,
                'min_order_qty' => 100,
                'estimated_days' => 2,
                'weight_per_piece' => 2,
                'allowed_file_types' => ['pdf', 'ai', 'psd', 'cdr', 'jpg', 'png'],
                'max_file_size' => 20,
                'is_active' => true,
            ]);

            Product::create([
                'category_id' => $kartuNama->id,
                'name' => 'Kartu Nama Premium',
                'slug' => 'kartu-nama-premium',
                'description' => 'Kartu nama dengan material dan finishing premium. Pilihan kertas eksklusif seperti Kraft, Linen, atau Soft Touch dengan berbagai opsi finishing mewah.',
                'short_description' => 'Kartu nama eksklusif dengan finishing premium',
                'images' => ['https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=600'],
                'base_price' => 200,
                'sizes' => [
                    ['id' => 'size-standar', 'name' => 'Standar', 'dimensions' => '90 x 55 mm', 'priceMultiplier' => 1],
                ],
                'materials' => [
                    ['id' => 'mat-kraft', 'name' => 'Kraft', 'weight' => '300gr', 'pricePerUnit' => 150, 'description' => 'Tampilan natural'],
                    ['id' => 'mat-soft-touch', 'name' => 'Soft Touch', 'weight' => '350gr', 'pricePerUnit' => 250, 'description' => 'Tekstur lembut'],
                    ['id' => 'mat-metallic', 'name' => 'Metallic', 'weight' => '300gr', 'pricePerUnit' => 300, 'description' => 'Efek metalik'],
                ],
                'print_sides' => [
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1],
                ],
                'finishings' => [
                    ['id' => 'fin-emboss', 'name' => 'Emboss', 'type' => 'other', 'price' => 150, 'description' => 'Efek timbul premium'],
                    ['id' => 'fin-foil-gold', 'name' => 'Hot Foil Gold', 'type' => 'other', 'price' => 200, 'description' => 'Foil emas'],
                    ['id' => 'fin-foil-silver', 'name' => 'Hot Foil Silver', 'type' => 'other', 'price' => 200, 'description' => 'Foil silver'],
                    ['id' => 'fin-edge-color', 'name' => 'Edge Coloring', 'type' => 'other', 'price' => 300, 'description' => 'Warna pada sisi kartu'],
                ],
                'quantity_tiers' => [
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 600],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 500],
                    ['minQty' => 500, 'maxQty' => 999, 'pricePerUnit' => 400],
                    ['minQty' => 1000, 'maxQty' => 99999, 'pricePerUnit' => 350],
                ],
                'is_promo' => true,
                'promo_percentage' => 10,
                'min_order_qty' => 100,
                'estimated_days' => 5,
                'weight_per_piece' => 3,
                'allowed_file_types' => ['pdf', 'ai', 'psd', 'cdr'],
                'max_file_size' => 20,
                'is_active' => true,
            ]);
        }

        // ==================== BANNER & SPANDUK ====================
        if ($banner) {
            Product::create([
                'category_id' => $banner->id,
                'name' => 'X-Banner 60x160',
                'slug' => 'x-banner-60x160',
                'description' => 'X-Banner ukuran 60x160 cm dengan tiang aluminium. Mudah dipasang dan dibawa kemana saja. Ideal untuk pameran, toko, dan promosi indoor.',
                'short_description' => 'X-Banner portable dengan tiang',
                'images' => ['https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600'],
                'base_price' => 50000,
                'sizes' => [
                    ['id' => 'size-60x160', 'name' => '60 x 160 cm', 'dimensions' => '60 x 160 cm', 'priceMultiplier' => 1],
                    ['id' => 'size-80x180', 'name' => '80 x 180 cm', 'dimensions' => '80 x 180 cm', 'priceMultiplier' => 1.4],
                ],
                'materials' => [
                    ['id' => 'mat-albatros', 'name' => 'Albatros', 'weight' => '280gsm', 'pricePerUnit' => 0, 'description' => 'Material standar indoor'],
                    ['id' => 'mat-flexi-korea', 'name' => 'Flexi Korea', 'weight' => '340gsm', 'pricePerUnit' => 15000, 'description' => 'Lebih tebal dan tahan lama'],
                ],
                'print_sides' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                ],
                'finishings' => [],
                'quantity_tiers' => [
                    ['minQty' => 1, 'maxQty' => 4, 'pricePerUnit' => 85000],
                    ['minQty' => 5, 'maxQty' => 9, 'pricePerUnit' => 75000],
                    ['minQty' => 10, 'maxQty' => 99999, 'pricePerUnit' => 65000],
                ],
                'is_best_seller' => true,
                'min_order_qty' => 1,
                'estimated_days' => 2,
                'weight_per_piece' => 1200,
                'allowed_file_types' => ['pdf', 'ai', 'psd', 'cdr', 'jpg', 'png'],
                'max_file_size' => 100,
                'is_active' => true,
            ]);

            Product::create([
                'category_id' => $banner->id,
                'name' => 'Roll Up Banner',
                'slug' => 'roll-up-banner',
                'description' => 'Roll Up Banner dengan sistem gulung otomatis. Profesional, mudah dibawa, dan tahan lama. Pilihan tepat untuk presentasi dan pameran.',
                'short_description' => 'Banner gulung otomatis premium',
                'images' => ['https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600'],
                'base_price' => 150000,
                'sizes' => [
                    ['id' => 'size-80x200', 'name' => '80 x 200 cm', 'dimensions' => '80 x 200 cm', 'priceMultiplier' => 1],
                    ['id' => 'size-85x200', 'name' => '85 x 200 cm', 'dimensions' => '85 x 200 cm', 'priceMultiplier' => 1.1],
                    ['id' => 'size-100x200', 'name' => '100 x 200 cm', 'dimensions' => '100 x 200 cm', 'priceMultiplier' => 1.3],
                ],
                'materials' => [
                    ['id' => 'mat-flexi-korea', 'name' => 'Flexi Korea', 'weight' => '340gsm', 'pricePerUnit' => 0, 'description' => 'Standar roll up'],
                ],
                'print_sides' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                ],
                'finishings' => [],
                'quantity_tiers' => [
                    ['minQty' => 1, 'maxQty' => 4, 'pricePerUnit' => 350000],
                    ['minQty' => 5, 'maxQty' => 9, 'pricePerUnit' => 300000],
                    ['minQty' => 10, 'maxQty' => 99999, 'pricePerUnit' => 250000],
                ],
                'min_order_qty' => 1,
                'estimated_days' => 2,
                'weight_per_piece' => 3000,
                'allowed_file_types' => ['pdf', 'ai', 'psd', 'cdr', 'jpg', 'png'],
                'max_file_size' => 100,
                'is_active' => true,
            ]);

            Product::create([
                'category_id' => $banner->id,
                'name' => 'Spanduk Outdoor',
                'slug' => 'spanduk-outdoor',
                'description' => 'Spanduk untuk promosi outdoor dengan bahan flexi tahan cuaca. Dilengkapi mata ayam dan tali. Tahan sinar UV dan hujan.',
                'short_description' => 'Spanduk tahan cuaca dengan mata ayam',
                'images' => ['https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600'],
                'base_price' => 25000,
                'sizes' => [
                    ['id' => 'size-per-m2', 'name' => 'Per m²', 'dimensions' => 'Harga per meter persegi', 'priceMultiplier' => 1],
                ],
                'materials' => [
                    ['id' => 'mat-flexi-280', 'name' => 'Flexi China', 'weight' => '280gsm', 'pricePerUnit' => 0, 'description' => 'Ekonomis'],
                    ['id' => 'mat-flexi-340', 'name' => 'Flexi Korea', 'weight' => '340gsm', 'pricePerUnit' => 15000, 'description' => 'Lebih awet'],
                ],
                'print_sides' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1.8],
                ],
                'finishings' => [
                    ['id' => 'fin-mata-ayam', 'name' => 'Mata Ayam', 'type' => 'other', 'price' => 5000, 'description' => 'Per mata ayam'],
                    ['id' => 'fin-jahit-pinggir', 'name' => 'Jahit Pinggir', 'type' => 'other', 'price' => 10000, 'description' => 'Per meter'],
                ],
                'quantity_tiers' => [
                    ['minQty' => 1, 'maxQty' => 9, 'pricePerUnit' => 55000],
                    ['minQty' => 10, 'maxQty' => 49, 'pricePerUnit' => 45000],
                    ['minQty' => 50, 'maxQty' => 99999, 'pricePerUnit' => 38000],
                ],
                'min_order_qty' => 1,
                'estimated_days' => 2,
                'weight_per_piece' => 500,
                'allowed_file_types' => ['pdf', 'ai', 'psd', 'cdr', 'jpg', 'png'],
                'max_file_size' => 100,
                'is_active' => true,
            ]);
        }

        // ==================== STIKER ====================
        if ($stiker) {
            Product::create([
                'category_id' => $stiker->id,
                'name' => 'Stiker Vinyl',
                'slug' => 'stiker-vinyl',
                'description' => 'Stiker vinyl berkualitas tinggi, tahan air dan tahan UV. Cocok untuk label produk outdoor, stiker kendaraan, dan branding.',
                'short_description' => 'Stiker tahan air & UV',
                'images' => ['https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?w=600'],
                'base_price' => 500,
                'sizes' => [
                    ['id' => 'size-a5', 'name' => 'A5', 'dimensions' => '148 x 210 mm', 'priceMultiplier' => 1],
                    ['id' => 'size-a4', 'name' => 'A4', 'dimensions' => '210 x 297 mm', 'priceMultiplier' => 1.8],
                    ['id' => 'size-a3', 'name' => 'A3', 'dimensions' => '297 x 420 mm', 'priceMultiplier' => 3],
                    ['id' => 'size-custom', 'name' => 'Custom', 'dimensions' => 'Sesuai kebutuhan', 'priceMultiplier' => 1],
                ],
                'materials' => [
                    ['id' => 'mat-vinyl-white', 'name' => 'Vinyl White', 'weight' => '', 'pricePerUnit' => 0, 'description' => 'Putih doff'],
                    ['id' => 'mat-vinyl-trans', 'name' => 'Vinyl Transparan', 'weight' => '', 'pricePerUnit' => 200, 'description' => 'Bening'],
                    ['id' => 'mat-vinyl-chrome', 'name' => 'Vinyl Chrome', 'weight' => '', 'pricePerUnit' => 500, 'description' => 'Efek chrome'],
                ],
                'print_sides' => [
                    ['id' => 'side-1', 'name' => '1 Sisi', 'code' => '4/0', 'priceMultiplier' => 1],
                ],
                'finishings' => [
                    ['id' => 'fin-die-cut', 'name' => 'Die Cut', 'type' => 'cutting', 'price' => 200, 'description' => 'Potong sesuai bentuk'],
                    ['id' => 'fin-kiss-cut', 'name' => 'Kiss Cut', 'type' => 'cutting', 'price' => 150, 'description' => 'Potong stiker saja'],
                ],
                'quantity_tiers' => [
                    ['minQty' => 50, 'maxQty' => 99, 'pricePerUnit' => 1500],
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 1200],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 1000],
                    ['minQty' => 500, 'maxQty' => 99999, 'pricePerUnit' => 800],
                ],
                'is_best_seller' => true,
                'min_order_qty' => 50,
                'estimated_days' => 3,
                'weight_per_piece' => 5,
                'allowed_file_types' => ['pdf', 'ai', 'psd', 'cdr', 'png'],
                'max_file_size' => 50,
                'is_active' => true,
            ]);
        }

        // ==================== UNDANGAN ====================
        if ($undangan) {
            Product::create([
                'category_id' => $undangan->id,
                'name' => 'Undangan Pernikahan Eksklusif',
                'slug' => 'undangan-pernikahan-eksklusif',
                'description' => 'Undangan pernikahan eksklusif dengan kertas premium dan finishing mewah. Tersedia berbagai pilihan desain romantis dan elegan.',
                'short_description' => 'Undangan pernikahan premium',
                'images' => ['https://images.unsplash.com/photo-1607190074257-dd4b7af0309f?w=600'],
                'base_price' => 3000,
                'sizes' => [
                    ['id' => 'size-single', 'name' => 'Single Card', 'dimensions' => '15 x 10 cm', 'priceMultiplier' => 1],
                    ['id' => 'size-pocket', 'name' => 'Pocket', 'dimensions' => '15 x 20 cm', 'priceMultiplier' => 1.5],
                ],
                'materials' => [
                    ['id' => 'mat-linen-280', 'name' => 'Linen', 'weight' => '280gr', 'pricePerUnit' => 500, 'description' => 'Tekstur premium'],
                    ['id' => 'mat-jasmine-300', 'name' => 'Jasmine', 'weight' => '300gr', 'pricePerUnit' => 700, 'description' => 'Dengan glitter'],
                    ['id' => 'mat-duplex', 'name' => 'Duplex', 'weight' => '310gr', 'pricePerUnit' => 600, 'description' => 'Dua warna'],
                ],
                'print_sides' => [
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1],
                ],
                'finishings' => [
                    ['id' => 'fin-foil-gold', 'name' => 'Hot Foil Gold', 'type' => 'other', 'price' => 500, 'description' => 'Foil emas'],
                    ['id' => 'fin-foil-rose', 'name' => 'Hot Foil Rose Gold', 'type' => 'other', 'price' => 600, 'description' => 'Foil rose gold'],
                    ['id' => 'fin-emboss', 'name' => 'Emboss', 'type' => 'other', 'price' => 400, 'description' => 'Efek timbul'],
                    ['id' => 'fin-pita', 'name' => 'Pita Satin', 'type' => 'other', 'price' => 1000, 'description' => 'Pita dekorasi'],
                ],
                'quantity_tiers' => [
                    ['minQty' => 100, 'maxQty' => 199, 'pricePerUnit' => 8000],
                    ['minQty' => 200, 'maxQty' => 499, 'pricePerUnit' => 6500],
                    ['minQty' => 500, 'maxQty' => 999, 'pricePerUnit' => 5500],
                    ['minQty' => 1000, 'maxQty' => 99999, 'pricePerUnit' => 4500],
                ],
                'is_promo' => true,
                'promo_percentage' => 20,
                'min_order_qty' => 100,
                'estimated_days' => 7,
                'weight_per_piece' => 20,
                'allowed_file_types' => ['pdf', 'ai', 'psd', 'cdr'],
                'max_file_size' => 50,
                'is_active' => true,
            ]);
        }

        // ==================== KALENDER ====================
        if ($kalender) {
            Product::create([
                'category_id' => $kalender->id,
                'name' => 'Kalender Meja Custom',
                'slug' => 'kalender-meja-custom',
                'description' => 'Kalender meja custom dengan desain sendiri. 13 lembar (cover + 12 bulan). Cocok untuk hadiah perusahaan dan promosi brand.',
                'short_description' => 'Kalender meja 13 lembar custom',
                'images' => ['https://images.unsplash.com/photo-1506784365847-bbad939e9335?w=600'],
                'base_price' => 10000,
                'sizes' => [
                    ['id' => 'size-a5', 'name' => 'A5', 'dimensions' => '148 x 210 mm', 'priceMultiplier' => 1],
                    ['id' => 'size-a4', 'name' => 'A4', 'dimensions' => '210 x 297 mm', 'priceMultiplier' => 1.5],
                ],
                'materials' => [
                    ['id' => 'mat-art-carton-260', 'name' => 'Art Carton', 'weight' => '260gr', 'pricePerUnit' => 0, 'description' => 'Standar'],
                    ['id' => 'mat-art-carton-310', 'name' => 'Art Carton', 'weight' => '310gr', 'pricePerUnit' => 3000, 'description' => 'Premium'],
                ],
                'print_sides' => [
                    ['id' => 'side-2', 'name' => '2 Sisi', 'code' => '4/4', 'priceMultiplier' => 1],
                ],
                'finishings' => [
                    ['id' => 'fin-lam-doff', 'name' => 'Laminasi Doff', 'type' => 'laminating', 'price' => 2000, 'description' => 'Cover doff'],
                    ['id' => 'fin-ring', 'name' => 'Ring Standar', 'type' => 'binding', 'price' => 5000, 'description' => 'Ring plastik'],
                    ['id' => 'fin-stand', 'name' => 'Stand/Dudukan', 'type' => 'other', 'price' => 3000, 'description' => 'Karton dudukan'],
                ],
                'quantity_tiers' => [
                    ['minQty' => 50, 'maxQty' => 99, 'pricePerUnit' => 35000],
                    ['minQty' => 100, 'maxQty' => 249, 'pricePerUnit' => 28000],
                    ['minQty' => 250, 'maxQty' => 499, 'pricePerUnit' => 23000],
                    ['minQty' => 500, 'maxQty' => 99999, 'pricePerUnit' => 18000],
                ],
                'is_best_seller' => true,
                'min_order_qty' => 50,
                'estimated_days' => 7,
                'weight_per_piece' => 150,
                'allowed_file_types' => ['pdf', 'ai', 'psd', 'cdr'],
                'max_file_size' => 100,
                'is_active' => true,
            ]);
        }

        echo "Products seeded!\n";
    }
}
