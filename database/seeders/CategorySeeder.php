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
                'nama' => 'Brosur & Flyer',
                'slug' => 'brosur-flyer',
                'ikon' => '📄',
                'deskripsi' => 'Cetak brosur dan flyer berkualitas tinggi untuk promosi bisnis, event, dan marketing. Tersedia berbagai ukuran dan pilihan kertas premium.',
                'gambar' => 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=600',
            ],
            [
                'nama' => 'Kartu Nama',
                'slug' => 'kartu-nama',
                'ikon' => '💳',
                'deskripsi' => 'Kartu nama professional dengan berbagai pilihan material premium. Tampilkan identitas bisnis Anda dengan elegan.',
                'gambar' => 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=600',
            ],
            [
                'nama' => 'Banner & Spanduk',
                'slug' => 'banner-spanduk',
                'ikon' => '🎪',
                'deskripsi' => 'X-Banner, roll banner, backdrop, dan spanduk outdoor untuk pameran, event, dan promosi toko.',
                'gambar' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600',
            ],
            [
                'nama' => 'Poster & Foto',
                'slug' => 'poster-foto',
                'ikon' => '🖼️',
                'deskripsi' => 'Cetak poster dan foto dengan resolusi tinggi. Cocok untuk dekorasi, pameran seni, dan kenangan spesial.',
                'gambar' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=600',
            ],
            [
                'nama' => 'Undangan',
                'slug' => 'undangan',
                'ikon' => '💌',
                'deskripsi' => 'Undangan pernikahan, ulang tahun, dan acara spesial. Desain eksklusif dengan finishing mewah.',
                'gambar' => 'https://images.unsplash.com/photo-1607190074257-dd4b7af0309f?w=600',
            ],
            [
                'nama' => 'Stiker & Label',
                'slug' => 'stiker-label',
                'ikon' => '🏷️',
                'deskripsi' => 'Stiker custom, label produk, dan vinyl berkualitas. Tahan air dan UV untuk berbagai kebutuhan.',
                'gambar' => 'https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?w=600',
            ],
            [
                'nama' => 'Kalender',
                'slug' => 'kalender',
                'ikon' => '📅',
                'deskripsi' => 'Kalender meja, dinding, dan custom. Hadiah promosi yang bermanfaat sepanjang tahun.',
                'gambar' => 'https://images.unsplash.com/photo-1506784365847-bbad939e9335?w=600',
            ],
            [
                'nama' => 'Kemasan & Box',
                'slug' => 'kemasan-box',
                'ikon' => '📦',
                'deskripsi' => 'Kemasan produk, box custom, dan packaging premium. Tingkatkan brand value produk Anda.',
                'gambar' => 'https://images.unsplash.com/photo-1607827448387-a67db1383b59?w=600',
            ],
            [
                'nama' => 'Buku & Majalah',
                'slug' => 'buku-majalah',
                'ikon' => '📕',
                'deskripsi' => 'Cetak buku, majalah, company profile, dan booklet dengan berbagai jenis binding.',
                'gambar' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=600',
            ],
            [
                'nama' => 'ATK & Perlengkapan',
                'slug' => 'atk-perlengkapan',
                'ikon' => '✏️',
                'deskripsi' => 'Alat tulis kantor branded, kop surat, amplop, dan perlengkapan kantor custom.',
                'gambar' => 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?w=600',
            ],
            [
                'nama' => 'Konveksi Kaos',
                'slug' => 'konveksi-kaos',
                'ikon' => '👕',
                'deskripsi' => 'Cetak kaos, polo shirt, dan produk konveksi berkualitas. Tersedia berbagai pilihan bahan dan teknik sablon atau DTF printing.',
                'gambar' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=600',
            ],
        ];

        foreach ($categories as $index => $cat) {
            Category::create(array_merge($cat, [
                'urutan' => $index,
                'aktif' => true,
            ]));
        }

        echo "Categories seeded!\n";
    }
}
