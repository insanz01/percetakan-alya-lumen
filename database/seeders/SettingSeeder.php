<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'kunci' => 'site_name',
                'nilai' => 'PrintMaster',
                'tipe' => 'string',
                'grup' => 'general',
            ],
            [
                'kunci' => 'site_tagline',
                'nilai' => 'Solusi Cetak Berkualitas untuk Bisnis Anda',
                'tipe' => 'string',
                'grup' => 'general',
            ],
            [
                'kunci' => 'site_description',
                'nilai' => 'PrintMaster adalah percetakan online terpercaya yang menyediakan berbagai layanan cetak berkualitas tinggi dengan harga terjangkau. Melayani cetak brosur, kartu nama, banner, undangan, dan berbagai kebutuhan percetakan lainnya.',
                'tipe' => 'text',
                'grup' => 'general',
            ],
            [
                'kunci' => 'site_logo',
                'nilai' => '/images/logo.png',
                'tipe' => 'string',
                'grup' => 'general',
            ],
            [
                'kunci' => 'site_favicon',
                'nilai' => '/images/favicon.ico',
                'tipe' => 'string',
                'grup' => 'general',
            ],

            // Contact Settings
            [
                'kunci' => 'contact_email',
                'nilai' => 'info@printmaster.id',
                'tipe' => 'string',
                'grup' => 'contact',
            ],
            [
                'kunci' => 'contact_phone',
                'nilai' => '021-12345678',
                'tipe' => 'string',
                'grup' => 'contact',
            ],
            [
                'kunci' => 'contact_whatsapp',
                'nilai' => '6281234567890',
                'tipe' => 'string',
                'grup' => 'contact',
            ],
            [
                'kunci' => 'contact_address',
                'nilai' => 'Jl. Percetakan Raya No. 123, Kelurahan Cetak Indah, Kecamatan Print Jaya, Jakarta Pusat 10110',
                'tipe' => 'text',
                'grup' => 'contact',
            ],
            [
                'kunci' => 'contact_maps_embed',
                'nilai' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613!3d-6.2087634!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMzEuNSJTIDEwNsKwNDknMTAuNCJF!5e0!3m2!1sen!2sid!4v1234567890',
                'tipe' => 'text',
                'grup' => 'contact',
            ],

            // Social Media Settings
            [
                'kunci' => 'social_facebook',
                'nilai' => 'https://facebook.com/printmaster.id',
                'tipe' => 'string',
                'grup' => 'social',
            ],
            [
                'kunci' => 'social_instagram',
                'nilai' => 'https://instagram.com/printmaster.id',
                'tipe' => 'string',
                'grup' => 'social',
            ],
            [
                'kunci' => 'social_twitter',
                'nilai' => 'https://twitter.com/printmaster_id',
                'tipe' => 'string',
                'grup' => 'social',
            ],
            [
                'kunci' => 'social_youtube',
                'nilai' => 'https://youtube.com/@printmasterid',
                'tipe' => 'string',
                'grup' => 'social',
            ],
            [
                'kunci' => 'social_tiktok',
                'nilai' => 'https://tiktok.com/@printmaster.id',
                'tipe' => 'string',
                'grup' => 'social',
            ],

            // Business Hours
            [
                'kunci' => 'business_hours',
                'nilai' => json_encode([
                    'monday' => ['open' => '08:00', 'close' => '17:00'],
                    'tuesday' => ['open' => '08:00', 'close' => '17:00'],
                    'wednesday' => ['open' => '08:00', 'close' => '17:00'],
                    'thursday' => ['open' => '08:00', 'close' => '17:00'],
                    'friday' => ['open' => '08:00', 'close' => '16:30'],
                    'saturday' => ['open' => '09:00', 'close' => '14:00'],
                    'sunday' => null,
                ]),
                'tipe' => 'json',
                'grup' => 'business',
            ],

            // Payment Settings
            [
                'kunci' => 'payment_bank_accounts',
                'nilai' => json_encode([
                    [
                        'bank' => 'BCA',
                        'account_number' => '1234567890',
                        'account_name' => 'PT PrintMaster Indonesia',
                    ],
                    [
                        'bank' => 'Mandiri',
                        'account_number' => '0987654321',
                        'account_name' => 'PT PrintMaster Indonesia',
                    ],
                    [
                        'bank' => 'BNI',
                        'account_number' => '5678901234',
                        'account_name' => 'PT PrintMaster Indonesia',
                    ],
                ]),
                'tipe' => 'json',
                'grup' => 'payment',
            ],
            [
                'kunci' => 'payment_deadline_hours',
                'nilai' => '24',
                'tipe' => 'number',
                'grup' => 'payment',
            ],

            // Shipping Settings
            [
                'kunci' => 'shipping_origin_city',
                'nilai' => 'Jakarta Pusat',
                'tipe' => 'string',
                'grup' => 'shipping',
            ],
            [
                'kunci' => 'shipping_origin_province',
                'nilai' => 'DKI Jakarta',
                'tipe' => 'string',
                'grup' => 'shipping',
            ],
            [
                'kunci' => 'shipping_free_minimum',
                'nilai' => '500000',
                'tipe' => 'number',
                'grup' => 'shipping',
            ],
            [
                'kunci' => 'shipping_providers',
                'nilai' => json_encode(['JNE', 'J&T', 'SiCepat', 'Anteraja', 'Pos Indonesia']),
                'tipe' => 'json',
                'grup' => 'shipping',
            ],

            // Order Settings
            [
                'kunci' => 'order_prefix',
                'nilai' => 'PM',
                'tipe' => 'string',
                'grup' => 'order',
            ],
            [
                'kunci' => 'order_min_amount',
                'nilai' => '50000',
                'tipe' => 'number',
                'grup' => 'order',
            ],

            // SEO Settings
            [
                'kunci' => 'seo_meta_title',
                'nilai' => 'PrintMaster - Percetakan Online Terpercaya | Cetak Berkualitas, Harga Terjangkau',
                'tipe' => 'string',
                'grup' => 'seo',
            ],
            [
                'kunci' => 'seo_meta_description',
                'nilai' => 'PrintMaster adalah percetakan online terpercaya di Indonesia. Cetak brosur, kartu nama, banner, undangan, dan berbagai kebutuhan percetakan dengan kualitas terbaik dan harga bersaing.',
                'tipe' => 'text',
                'grup' => 'seo',
            ],
            [
                'kunci' => 'seo_meta_keywords',
                'nilai' => 'percetakan online, cetak brosur, kartu nama, banner, undangan, stiker, percetakan jakarta, cetak murah',
                'tipe' => 'text',
                'grup' => 'seo',
            ],

            // Feature Toggles
            [
                'kunci' => 'feature_newsletter',
                'nilai' => 'true',
                'tipe' => 'boolean',
                'grup' => 'features',
            ],
            [
                'kunci' => 'feature_promo_banner',
                'nilai' => 'true',
                'tipe' => 'boolean',
                'grup' => 'features',
            ],
            [
                'kunci' => 'feature_live_chat',
                'nilai' => 'true',
                'tipe' => 'boolean',
                'grup' => 'features',
            ],
            [
                'kunci' => 'feature_testimonials',
                'nilai' => 'true',
                'tipe' => 'boolean',
                'grup' => 'features',
            ],

            // Notification Settings
            [
                'kunci' => 'notification_order_email',
                'nilai' => 'orders@printmaster.id',
                'tipe' => 'string',
                'grup' => 'notification',
            ],
            [
                'kunci' => 'notification_contact_email',
                'nilai' => 'info@printmaster.id',
                'tipe' => 'string',
                'grup' => 'notification',
            ],

            // ==================== Content Management (grup: content) ====================
            // Konten publik website yang dikelola Super Admin di panel /admin/content.
            // Semua disimpan sebagai JSON agar bisa berupa list/objek.
            [
                'kunci' => 'content_hero_banners',
                'nilai' => json_encode([
                    ['title' => 'Cetak Berkualitas, Harga Terjangkau', 'subtitle' => 'Solusi percetakan terpercaya untuk bisnis dan personal Anda', 'ctaText' => 'Mulai Pesan', 'ctaLink' => '/kategori', 'image' => '/gambar/banner/1.jpeg'],
                    ['title' => 'Kualitas Terjamin', 'subtitle' => 'Garansi cetak ulang jika hasil tidak sesuai ekspektasi Anda', 'ctaText' => 'Lihat Produk', 'ctaLink' => '/kategori', 'image' => '/gambar/banner/2.jpeg'],
                    ['title' => 'Express Printing', 'subtitle' => 'Pengerjaan cepat 1-5 hari kerja untuk kebutuhan urgent', 'ctaText' => 'Lihat Produk', 'ctaLink' => '/kategori', 'image' => '/gambar/banner/3.jpeg'],
                ]),
                'tipe' => 'json',
                'grup' => 'content',
            ],
            [
                'kunci' => 'content_features',
                'nilai' => json_encode([
                    ['title' => 'Pengiriman Cepat', 'description' => 'Gratis Ongkir untuk pekerjaan tertentu'],
                    ['title' => 'Kualitas Terjamin', 'description' => 'Garansi cetak ulang jika tidak sesuai'],
                    ['title' => 'Proses Cepat', 'description' => 'Proses estimasi 1-5 hari kerja'],
                    ['title' => 'Support 24/7', 'description' => 'Tim support siap membantu'],
                ]),
                'tipe' => 'json',
                'grup' => 'content',
            ],
            [
                'kunci' => 'content_about',
                'nilai' => json_encode([
                    'title' => 'Mitra Percetakan Terpercaya untuk Bisnis Anda',
                    'body' => 'Sejak 2014, Semanggi Print telah menjadi solusi percetakan online terlengkap dengan menggabungkan teknologi modern dan keahlian tradisional untuk menghasilkan produk cetak berkualitas tinggi.',
                    'stats' => [
                        ['value' => '10+', 'label' => 'Tahun Pengalaman'],
                        ['value' => '50K+', 'label' => 'Pelanggan Puas'],
                        ['value' => '500K+', 'label' => 'Pesanan Selesai'],
                        ['value' => '99%', 'label' => 'Tingkat Kepuasan'],
                    ],
                    'story' => [
                        'badge' => 'Cerita Kami',
                        'title' => 'Dari Garasi ke Ribuan Pelanggan',
                        'image' => 'https://images.unsplash.com/photo-1562654501-a0ccc0fc3fb1?w=600',
                        'paragraphs' => [
                            'Semanggi Print didirikan dengan semangat untuk memberikan layanan percetakan berkualitas yang mudah dijangkau oleh semua kalangan. Berawal dari usaha kecil, kami kini melayani ribuan pelanggan dari seluruh Indonesia.',
                            'Perjalanan kami tidak selalu mulus, namun dengan komitmen pada kualitas dan kepuasan pelanggan, kami terus berkembang. Saat ini, Semanggi Print memiliki fasilitas produksi modern dengan berbagai mesin cetak offset dan digital untuk memenuhi kebutuhan percetakan Anda.',
                            'Kami percaya bahwa setiap bisnis, besar maupun kecil, berhak mendapatkan hasil cetakan berkualitas dengan harga yang terjangkau dan transparan.',
                        ],
                    ],
                    'vision' => 'Menjadi platform percetakan online nomor satu di Indonesia yang dikenal dengan kualitas premium, inovasi teknologi, dan layanan pelanggan terbaik.',
                    'mission' => [
                        'Menyediakan produk cetak berkualitas tinggi dengan harga kompetitif',
                        'Menghadirkan pengalaman pemesanan yang mudah dan transparan',
                        'Memberikan layanan pelanggan yang responsif dan solutif',
                        'Mendukung pertumbuhan bisnis UMKM Indonesia',
                    ],
                    'values' => [
                        ['title' => 'Kualitas Premium', 'description' => 'Kami menggunakan mesin cetak terbaru dan bahan berkualitas tinggi untuk menghasilkan produk terbaik.'],
                        ['title' => 'Tepat Waktu', 'description' => 'Komitmen kami adalah menyelesaikan pesanan sesuai estimasi yang dijanjikan.'],
                        ['title' => 'Garansi Kepuasan', 'description' => 'Jika hasil tidak sesuai, kami akan cetak ulang tanpa biaya tambahan.'],
                        ['title' => 'Harga Transparan', 'description' => 'Tidak ada biaya tersembunyi. Harga yang Anda lihat adalah harga yang Anda bayar.'],
                    ],
                ]),
                'tipe' => 'json',
                'grup' => 'content',
            ],
            [
                'kunci' => 'content_faq',
                'nilai' => json_encode([
                    ['category' => 'Pemesanan', 'question' => 'Bagaimana cara memesan produk di Semanggi Print?', 'answer' => 'Untuk memesan produk, pilih produk yang diinginkan, tentukan spesifikasi (ukuran, bahan, jumlah), upload file desain Anda, lalu lanjutkan ke checkout. Setelah pembayaran dikonfirmasi, pesanan akan segera diproses.'],
                    ['category' => 'Pemesanan', 'question' => 'Berapa minimum order untuk setiap produk?', 'answer' => 'Minimum order bervariasi tergantung jenis produk. Untuk brosur dan flyer umumnya mulai dari 100 lembar, kartu nama mulai dari 100 pcs, dan banner mulai dari 1 pcs. Detail minimum order dapat dilihat di halaman masing-masing produk.'],
                    ['category' => 'Pemesanan', 'question' => 'Apakah bisa pesan dalam jumlah besar?', 'answer' => 'Tentu! Kami menerima pesanan dalam jumlah besar dengan harga khusus. Semakin banyak jumlah pesanan, semakin hemat harga per unitnya. Untuk pesanan khusus dalam jumlah sangat besar, silahkan hubungi tim sales kami.'],
                    ['category' => 'Pembayaran', 'question' => 'Metode pembayaran apa saja yang tersedia?', 'answer' => 'Kami menerima pembayaran melalui Transfer Bank (BCA, Mandiri, BNI, BRI), Virtual Account, E-Wallet (OVO, GoPay, DANA, ShopeePay), dan QRIS. Semua metode pembayaran aman dan terverifikasi.'],
                    ['category' => 'Pembayaran', 'question' => 'Berapa lama batas waktu pembayaran?', 'answer' => 'Batas waktu pembayaran adalah 24 jam setelah pesanan dibuat. Jika pembayaran tidak diterima dalam waktu tersebut, pesanan akan otomatis dibatalkan. Anda dapat membuat pesanan baru jika hal ini terjadi.'],
                    ['category' => 'File Desain', 'question' => 'Format file apa yang diterima?', 'answer' => 'Kami menerima file dalam format PDF, AI, PSD, JPG, dan PNG. Untuk hasil terbaik, kami merekomendasikan menggunakan format PDF dengan resolusi minimal 300 DPI dan mode warna CMYK.'],
                    ['category' => 'File Desain', 'question' => 'Bagaimana jika saya tidak punya desain?', 'answer' => 'Tidak perlu khawatir! Kami menyediakan layanan desain dengan biaya tambahan. Tim desainer profesional kami siap membantu mewujudkan ide Anda. Silahkan hubungi customer service untuk informasi lebih lanjut.'],
                    ['category' => 'File Desain', 'question' => 'Apakah file desain saya akan diperiksa sebelum cetak?', 'answer' => 'Ya, tim kami akan memeriksa file desain Anda untuk memastikan kualitas cetak optimal. Jika ada masalah dengan file (resolusi rendah, warna tidak sesuai, dll), kami akan menghubungi Anda sebelum melanjutkan produksi.'],
                    ['category' => 'Produksi & Pengiriman', 'question' => 'Berapa lama proses produksi?', 'answer' => 'Waktu produksi bervariasi tergantung jenis produk dan jumlah pesanan. Umumnya 3-7 hari kerja setelah file desain disetujui. Untuk pesanan urgent, kami menyediakan layanan express dengan biaya tambahan.'],
                    ['category' => 'Produksi & Pengiriman', 'question' => 'Ekspedisi apa yang digunakan?', 'answer' => 'Kami bekerja sama dengan berbagai ekspedisi terpercaya seperti JNE, J&T, SiCepat, Anteraja, dan Pos Indonesia. Anda dapat memilih ekspedisi sesuai kebutuhan saat checkout.'],
                    ['category' => 'Produksi & Pengiriman', 'question' => 'Apakah bisa COD?', 'answer' => 'Saat ini kami belum menyediakan layanan COD. Semua pesanan harus dibayar terlebih dahulu sebelum proses produksi dimulai. Hal ini untuk memastikan kelancaran produksi dan pengiriman.'],
                    ['category' => 'Garansi & Retur', 'question' => 'Apakah ada garansi untuk produk cetak?', 'answer' => 'Ya, kami memberikan garansi 100% jika terjadi kesalahan cetak dari pihak kami atau produk tidak sesuai spesifikasi yang dipesan. Klaim garansi dapat diajukan maksimal 3 hari setelah produk diterima.'],
                    ['category' => 'Garansi & Retur', 'question' => 'Bagaimana jika produk rusak saat pengiriman?', 'answer' => 'Jika produk rusak saat pengiriman, segera foto kondisi paket dan produk, lalu ajukan klaim melalui halaman akun atau hubungi customer service kami. Kami akan memproses pengiriman ulang tanpa biaya tambahan.'],
                ]),
                'tipe' => 'json',
                'grup' => 'content',
            ],
            [
                'kunci' => 'content_contact',
                'nilai' => json_encode([
                    'address' => 'Jl. A.Yani No.39, Palangka Raya, Kalteng',
                    'phone' => '0813-1115-2071',
                    'email' => 'rudygrafika@gmail.com',
                    'whatsapp' => '6281311152071',
                    'hours' => 'Senin - Sabtu: 08.00 - 17.00',
                    'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.5!2d113.9213!3d-2.2072!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMsKwMTInMjUuOSJTIDExM8KwNTUnMTYuNyJF!5e0!3m2!1sid!2sid!4v1234567890!5m2!1sid!2sid&q=Jl.+A.Yani+No.39+Palangka+Raya+Kalimantan+Tengah',
                    'addressTitle' => 'Kantor & Workshop',
                    'addressLines' => [
                        'Jl. A.Yani No.39',
                        'Kelurahan Langkai, Kec. Pahandut',
                        'Kota Palangka Raya, Kalimantan Tengah 73111',
                    ],
                    'mapsLink' => 'https://www.google.com/maps/search/Jl.+A.Yani+No.39+Palangka+Raya+Kalimantan+Tengah',
                ]),
                'tipe' => 'json',
                'grup' => 'content',
            ],
            [
                'kunci' => 'content_footer',
                'nilai' => json_encode([
                    'description' => 'Platform percetakan online terpercaya dengan kualitas premium dan harga transparan. Melayani kebutuhan cetak individu hingga corporate.',
                    'instagram' => '',
                    'facebook' => '',
                    'tiktok' => '',
                    'whatsapp' => '6281311152071',
                ]),
                'tipe' => 'json',
                'grup' => 'content',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['kunci' => $setting['kunci']],
                $setting
            );
        }

        echo "Settings seeded!\n";
    }
}
