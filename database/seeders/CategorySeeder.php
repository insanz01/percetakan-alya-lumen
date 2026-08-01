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
                'gambar' => '/gambar/brosur/1.jpeg',
            ],
            [
                'nama' => 'Kartu Nama',
                'slug' => 'kartu-nama',
                'ikon' => '💳',
                'deskripsi' => 'Kartu nama professional dengan berbagai pilihan material premium. Tampilkan identitas bisnis Anda dengan elegan.',
                'gambar' => '/gambar/kartu_nama/1.jpeg',
            ],
            [
                'nama' => 'Banner & Spanduk',
                'slug' => 'banner-spanduk',
                'ikon' => '🎪',
                'deskripsi' => 'X-Banner, roll banner, backdrop, dan spanduk outdoor untuk pameran, event, dan promosi toko.',
                'gambar' => '/gambar/banner/1.jpeg',
            ],
            [
                'nama' => 'Poster & Foto',
                'slug' => 'poster-foto',
                'ikon' => '🖼️',
                'deskripsi' => 'Cetak poster dan foto dengan resolusi tinggi. Cocok untuk dekorasi, pameran seni, dan kenangan spesial.',
                'gambar' => '/gambar/poster_landscape/1.jpeg',
            ],
            [
                'nama' => 'Undangan',
                'slug' => 'undangan',
                'ikon' => '💌',
                'deskripsi' => 'Undangan pernikahan, ulang tahun, dan acara spesial. Desain eksklusif dengan finishing mewah.',
                'gambar' => '/gambar/undangan/1.jpeg',
            ],
            [
                'nama' => 'Stiker & Label',
                'slug' => 'stiker-label',
                'ikon' => '🏷️',
                'deskripsi' => 'Stiker custom, label produk, dan vinyl berkualitas. Tahan air dan UV untuk berbagai kebutuhan.',
                'gambar' => '/gambar/stiker/1.jpeg',
            ],
            [
                'nama' => 'Kalender',
                'slug' => 'kalender',
                'ikon' => '📅',
                'deskripsi' => 'Kalender meja, dinding, dan custom. Hadiah promosi yang bermanfaat sepanjang tahun.',
                'gambar' => '/gambar/kalender/1.jpeg',
            ],
            [
                'nama' => 'Kemasan & Box',
                'slug' => 'kemasan-box',
                'ikon' => '📦',
                'deskripsi' => 'Kemasan produk, box custom, dan packaging premium. Tingkatkan brand value produk Anda.',
                'gambar' => '/gambar/kemasan_box/1.jpeg',
            ],
            [
                'nama' => 'Buku & Majalah',
                'slug' => 'buku-majalah',
                'ikon' => '📕',
                'deskripsi' => 'Cetak buku, majalah, company profile, dan booklet dengan berbagai jenis binding.',
                'gambar' => '/gambar/buku_majalah/1.jpeg',
            ],
            [
                'nama' => 'ATK & Perlengkapan',
                'slug' => 'atk-perlengkapan',
                'ikon' => '✏️',
                'deskripsi' => 'Alat tulis kantor branded, kop surat, amplop, dan perlengkapan kantor custom.',
                'gambar' => '/gambar/atk/1.jpeg',
            ],
            [
                'nama' => 'Konveksi Kaos',
                'slug' => 'konveksi-kaos',
                'ikon' => '👕',
                'deskripsi' => 'Cetak kaos, polo shirt, dan produk konveksi berkualitas. Tersedia berbagai pilihan bahan dan teknik sablon atau DTF printing.',
                'gambar' => '/gambar/konveksi/1.jpeg',
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
