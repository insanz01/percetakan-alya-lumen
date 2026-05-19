<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            [
                'nama' => 'Andi Pratama',
                'email' => 'andi.pratama@gmail.com',
                'telepon' => '081234567891',
                'subjek' => 'Pertanyaan Harga Cetak Buku',
                'pesan' => 'Selamat siang, saya ingin menanyakan harga cetak buku untuk tugas akhir saya. Jumlah halaman sekitar 150 halaman, ukuran A5, kertas HVS 80gr. Mohon info harga untuk 10 eksemplar. Terima kasih.',
                'status' => 'new',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'nama' => 'Maya Sari',
                'email' => 'maya.sari@email.com',
                'telepon' => '082345678901',
                'subjek' => 'Request Desain Undangan Custom',
                'pesan' => 'Halo PrintMaster, saya tertarik dengan undangan pernikahan eksklusif. Apakah tersedia layanan desain custom? Saya ingin tema rustic dengan warna sage green. Mohon info lebih lanjut mengenai harga dan proses pengerjaannya.',
                'status' => 'read',
                'catatan_admin' => 'Customer tertarik desain custom - forward ke tim desain',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'nama' => 'PT. Maju Terus',
                'email' => 'procurement@majuterus.co.id',
                'telepon' => '021-5551234',
                'subjek' => 'Penawaran Kerjasama Corporate',
                'pesan' => 'Dengan hormat, kami dari PT. Maju Terus ingin menjalin kerjasama dalam pengadaan kebutuhan percetakan perusahaan kami. Kami membutuhkan: kartu nama untuk 50 karyawan, brosur promosi 1000 lembar, dan roll banner 10 unit. Mohon kirimkan penawaran harga ke email kami. Terima kasih.',
                'status' => 'replied',
                'catatan_admin' => 'Corporate client - sudah dikirim penawaran harga via email',
                'dibalas_pada' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'nama' => 'Dewi Lestari',
                'email' => 'dewi.l@gmail.com',
                'telepon' => '085678901234',
                'subjek' => 'Komplain Pesanan #PM20261225ABC',
                'pesan' => 'Saya ingin menyampaikan komplain mengenai pesanan saya dengan nomor PM20261225ABC. Warna cetakan brosur tidak sesuai dengan preview yang saya lihat di website. Warna terlihat lebih pucat dari yang diharapkan. Mohon dapat ditindaklanjuti.',
                'status' => 'replied',
                'catatan_admin' => 'Sudah dikonfirmasi - perbedaan di monitor customer. Dijelaskan mengenai perbedaan warna CMYK vs RGB. Customer sudah mengerti.',
                'dibalas_pada' => Carbon::now()->subHours(12),
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'nama' => 'Roni Setiawan',
                'email' => 'roni.setiawan@yahoo.com',
                'telepon' => '087890123456',
                'subjek' => 'Pertanyaan Waktu Pengerjaan',
                'pesan' => 'Selamat pagi, saya ingin bertanya berapa lama waktu pengerjaan untuk mencetak 500 kartu nama dan 200 stiker vinyl? Apakah ada opsi express? Saya butuh dalam 2 hari. Terima kasih.',
                'status' => 'new',
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'nama' => 'Lisa Permata',
                'email' => 'lisa.permata@email.com',
                'telepon' => '089012345678',
                'subjek' => 'Saran Penambahan Fitur Website',
                'pesan' => 'Saran untuk website PrintMaster: alangkah baiknya jika ada fitur preview 3D untuk produk-produk seperti undangan dan kalender. Ini akan membantu customer melihat hasil akhir sebelum order. Terima kasih, sukses selalu!',
                'status' => 'archived',
                'catatan_admin' => 'Saran bagus - forward ke tim development',
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'nama' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@email.com',
                'telepon' => '081122334455',
                'subjek' => 'Tanya Spesifikasi File untuk X-Banner',
                'pesan' => 'Mau tanya dong, untuk cetak X-Banner 60x160 cm spesifikasi file yang dibutuhkan seperti apa ya? Resolusi minimum berapa dpi? Format file apa saja yang diterima? Makasih.',
                'status' => 'read',
                'catatan_admin' => 'FAQ umum - bisa diarahkan ke halaman FAQ',
                'created_at' => Carbon::now()->subHours(8),
            ],
            [
                'nama' => 'Sari Wulandari',
                'email' => 'sari.w@gmail.com',
                'telepon' => '082233445566',
                'subjek' => 'Request Sample Kertas',
                'pesan' => 'Halo, apakah bisa minta sample kertas untuk kartu nama? Saya tertarik dengan kertas Linen dan Soft Touch tapi ingin lihat dan rasakan dulu sebelum order. Lokasi saya di Jakarta Selatan. Thanks.',
                'status' => 'new',
                'created_at' => Carbon::now()->subHours(2),
            ],
        ];

        foreach ($messages as $message) {
            ContactMessage::create($message);
        }

        echo "Contact messages seeded!\n";
    }
}
