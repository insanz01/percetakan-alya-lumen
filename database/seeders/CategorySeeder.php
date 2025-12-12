<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Brosur & Flyer',
                'slug' => 'brosur-flyer',
                'icon' => '📄',
                'description' => 'Cetak brosur dan flyer berkualitas tinggi untuk promosi bisnis, event, dan marketing. Tersedia berbagai ukuran dan pilihan kertas premium.',
                'image' => 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=600',
            ],
            [
                'name' => 'Kartu Nama',
                'slug' => 'kartu-nama',
                'icon' => '💳',
                'description' => 'Kartu nama professional dengan berbagai pilihan material premium. Tampilkan identitas bisnis Anda dengan elegan.',
                'image' => 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=600',
            ],
            [
                'name' => 'Banner & Spanduk',
                'slug' => 'banner-spanduk',
                'icon' => '🎪',
                'description' => 'X-Banner, roll banner, backdrop, dan spanduk outdoor untuk pameran, event, dan promosi toko.',
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600',
            ],
            [
                'name' => 'Poster & Foto',
                'slug' => 'poster-foto',
                'icon' => '🖼️',
                'description' => 'Cetak poster dan foto dengan resolusi tinggi. Cocok untuk dekorasi, pameran seni, dan kenangan spesial.',
                'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=600',
            ],
            [
                'name' => 'Undangan',
                'slug' => 'undangan',
                'icon' => '💌',
                'description' => 'Undangan pernikahan, ulang tahun, dan acara spesial. Desain eksklusif dengan finishing mewah.',
                'image' => 'https://images.unsplash.com/photo-1607190074257-dd4b7af0309f?w=600',
            ],
            [
                'name' => 'Stiker & Label',
                'slug' => 'stiker-label',
                'icon' => '🏷️',
                'description' => 'Stiker custom, label produk, dan vinyl berkualitas. Tahan air dan UV untuk berbagai kebutuhan.',
                'image' => 'https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?w=600',
            ],
            [
                'name' => 'Kalender',
                'slug' => 'kalender',
                'icon' => '📅',
                'description' => 'Kalender meja, dinding, dan custom. Hadiah promosi yang bermanfaat sepanjang tahun.',
                'image' => 'https://images.unsplash.com/photo-1506784365847-bbad939e9335?w=600',
            ],
            [
                'name' => 'Kemasan & Box',
                'slug' => 'kemasan-box',
                'icon' => '📦',
                'description' => 'Kemasan produk, box custom, dan packaging premium. Tingkatkan brand value produk Anda.',
                'image' => 'https://images.unsplash.com/photo-1607827448387-a67db1383b59?w=600',
            ],
            [
                'name' => 'Buku & Majalah',
                'slug' => 'buku-majalah',
                'icon' => '📕',
                'description' => 'Cetak buku, majalah, company profile, dan booklet dengan berbagai jenis binding.',
                'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=600',
            ],
            [
                'name' => 'ATK & Perlengkapan',
                'slug' => 'atk-perlengkapan',
                'icon' => '✏️',
                'description' => 'Alat tulis kantor branded, kop surat, amplop, dan perlengkapan kantor custom.',
                'image' => 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?w=600',
            ],
        ];

        foreach ($categories as $index => $cat) {
            Category::create(array_merge($cat, [
                'sort_order' => $index,
                'is_active' => true,
            ]));
        }

        echo "Categories seeded!\n";
    }
}
