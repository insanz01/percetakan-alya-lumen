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
                'key' => 'site_name',
                'value' => 'PrintMaster',
                'type' => 'string',
                'group' => 'general',
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Solusi Cetak Berkualitas untuk Bisnis Anda',
                'type' => 'string',
                'group' => 'general',
            ],
            [
                'key' => 'site_description',
                'value' => 'PrintMaster adalah percetakan online terpercaya yang menyediakan berbagai layanan cetak berkualitas tinggi dengan harga terjangkau. Melayani cetak brosur, kartu nama, banner, undangan, dan berbagai kebutuhan percetakan lainnya.',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'site_logo',
                'value' => '/images/logo.png',
                'type' => 'string',
                'group' => 'general',
            ],
            [
                'key' => 'site_favicon',
                'value' => '/images/favicon.ico',
                'type' => 'string',
                'group' => 'general',
            ],

            // Contact Settings
            [
                'key' => 'contact_email',
                'value' => 'info@printmaster.id',
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone',
                'value' => '021-12345678',
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_whatsapp',
                'value' => '6281234567890',
                'type' => 'string',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jl. Percetakan Raya No. 123, Kelurahan Cetak Indah, Kecamatan Print Jaya, Jakarta Pusat 10110',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_maps_embed',
                'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613!3d-6.2087634!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMzEuNSJTIDEwNsKwNDknMTAuNCJF!5e0!3m2!1sen!2sid!4v1234567890',
                'type' => 'text',
                'group' => 'contact',
            ],

            // Social Media Settings
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/printmaster.id',
                'type' => 'string',
                'group' => 'social',
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/printmaster.id',
                'type' => 'string',
                'group' => 'social',
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/printmaster_id',
                'type' => 'string',
                'group' => 'social',
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/@printmasterid',
                'type' => 'string',
                'group' => 'social',
            ],
            [
                'key' => 'social_tiktok',
                'value' => 'https://tiktok.com/@printmaster.id',
                'type' => 'string',
                'group' => 'social',
            ],

            // Business Hours
            [
                'key' => 'business_hours',
                'value' => json_encode([
                    'monday' => ['open' => '08:00', 'close' => '17:00'],
                    'tuesday' => ['open' => '08:00', 'close' => '17:00'],
                    'wednesday' => ['open' => '08:00', 'close' => '17:00'],
                    'thursday' => ['open' => '08:00', 'close' => '17:00'],
                    'friday' => ['open' => '08:00', 'close' => '16:30'],
                    'saturday' => ['open' => '09:00', 'close' => '14:00'],
                    'sunday' => null,
                ]),
                'type' => 'json',
                'group' => 'business',
            ],

            // Payment Settings
            [
                'key' => 'payment_bank_accounts',
                'value' => json_encode([
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
                'type' => 'json',
                'group' => 'payment',
            ],
            [
                'key' => 'payment_deadline_hours',
                'value' => '24',
                'type' => 'number',
                'group' => 'payment',
            ],

            // Shipping Settings
            [
                'key' => 'shipping_origin_city',
                'value' => 'Jakarta Pusat',
                'type' => 'string',
                'group' => 'shipping',
            ],
            [
                'key' => 'shipping_origin_province',
                'value' => 'DKI Jakarta',
                'type' => 'string',
                'group' => 'shipping',
            ],
            [
                'key' => 'shipping_free_minimum',
                'value' => '500000',
                'type' => 'number',
                'group' => 'shipping',
            ],
            [
                'key' => 'shipping_providers',
                'value' => json_encode(['JNE', 'J&T', 'SiCepat', 'Anteraja', 'Pos Indonesia']),
                'type' => 'json',
                'group' => 'shipping',
            ],

            // Order Settings
            [
                'key' => 'order_prefix',
                'value' => 'PM',
                'type' => 'string',
                'group' => 'order',
            ],
            [
                'key' => 'order_min_amount',
                'value' => '50000',
                'type' => 'number',
                'group' => 'order',
            ],

            // SEO Settings
            [
                'key' => 'seo_meta_title',
                'value' => 'PrintMaster - Percetakan Online Terpercaya | Cetak Berkualitas, Harga Terjangkau',
                'type' => 'string',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_meta_description',
                'value' => 'PrintMaster adalah percetakan online terpercaya di Indonesia. Cetak brosur, kartu nama, banner, undangan, dan berbagai kebutuhan percetakan dengan kualitas terbaik dan harga bersaing.',
                'type' => 'text',
                'group' => 'seo',
            ],
            [
                'key' => 'seo_meta_keywords',
                'value' => 'percetakan online, cetak brosur, kartu nama, banner, undangan, stiker, percetakan jakarta, cetak murah',
                'type' => 'text',
                'group' => 'seo',
            ],

            // Feature Toggles
            [
                'key' => 'feature_newsletter',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'features',
            ],
            [
                'key' => 'feature_promo_banner',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'features',
            ],
            [
                'key' => 'feature_live_chat',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'features',
            ],
            [
                'key' => 'feature_testimonials',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'features',
            ],

            // Notification Settings
            [
                'key' => 'notification_order_email',
                'value' => 'orders@printmaster.id',
                'type' => 'string',
                'group' => 'notification',
            ],
            [
                'key' => 'notification_contact_email',
                'value' => 'info@printmaster.id',
                'type' => 'string',
                'group' => 'notification',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        echo "Settings seeded!\n";
    }
}
