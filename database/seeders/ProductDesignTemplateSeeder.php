<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductDesignTemplate;
use Illuminate\Database\Seeder;

class ProductDesignTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (ProductDesignTemplate::exists()) {
            echo "Design templates already seeded, skipping.\n";
            return;
        }

        // Reuses the same static banner images ProductSeeder already ships
        // with (percetakan-alya-react/public/gambar/banner/*.jpeg) so no new
        // image assets are needed just to demo the feature.
        $templatesBySlug = [
            'spanduk-outdoor' => [
                ['nama' => 'Dirgahayu Kemerdekaan RI', 'gambar' => '/gambar/banner/3.jpeg'],
                ['nama' => 'Desain Korporat Biru', 'gambar' => '/gambar/banner/1.jpeg'],
                ['nama' => 'Desain Promo Merah', 'gambar' => '/gambar/banner/2.jpeg'],
            ],
            'spanduk-indoor' => [
                ['nama' => 'Desain Elegan Ungu', 'gambar' => '/gambar/banner/4.jpeg'],
                ['nama' => 'Desain Korporat Biru', 'gambar' => '/gambar/banner/1.jpeg'],
                ['nama' => 'Desain Promo Merah', 'gambar' => '/gambar/banner/2.jpeg'],
            ],
            'x-banner-60x160' => [
                ['nama' => 'Desain Pameran', 'gambar' => '/gambar/banner/1.jpeg'],
                ['nama' => 'Desain Promo', 'gambar' => '/gambar/banner/2.jpeg'],
            ],
            'roll-up-banner' => [
                ['nama' => 'Desain Korporat', 'gambar' => '/gambar/banner/2.jpeg'],
                ['nama' => 'Desain Event', 'gambar' => '/gambar/banner/4.jpeg'],
            ],
        ];

        foreach ($templatesBySlug as $slug => $options) {
            $product = Product::where('slug', $slug)->first();

            if (!$product) {
                continue;
            }

            foreach ($options as $index => $option) {
                ProductDesignTemplate::create([
                    'produk_id' => $product->id,
                    'nama' => $option['nama'],
                    'gambar' => $option['gambar'],
                    'urutan' => $index,
                ]);
            }
        }
    }
}
