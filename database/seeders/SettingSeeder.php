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
