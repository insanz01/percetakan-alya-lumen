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
                'name' => 'Andi Pratama',
                'email' => 'andi.pratama@gmail.com',
                'phone' => '081234567891',
                'subject' => 'Pertanyaan Harga Cetak Buku',
                'message' => 'Selamat siang, saya ingin menanyakan harga cetak buku untuk tugas akhir saya. Jumlah halaman sekitar 150 halaman, ukuran A5, kertas HVS 80gr. Mohon info harga untuk 10 eksemplar. Terima kasih.',
                'status' => 'new',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@email.com',
                'phone' => '082345678901',
                'subject' => 'Request Desain Undangan Custom',
                'message' => 'Halo PrintMaster, saya tertarik dengan undangan pernikahan eksklusif. Apakah tersedia layanan desain custom? Saya ingin tema rustic dengan warna sage green. Mohon info lebih lanjut mengenai harga dan proses pengerjaannya.',
                'status' => 'read',
                'admin_notes' => 'Customer tertarik desain custom - forward ke tim desain',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'PT. Maju Terus',
                'email' => 'procurement@majuterus.co.id',
                'phone' => '021-5551234',
                'subject' => 'Penawaran Kerjasama Corporate',
                'message' => 'Dengan hormat, kami dari PT. Maju Terus ingin menjalin kerjasama dalam pengadaan kebutuhan percetakan perusahaan kami. Kami membutuhkan: kartu nama untuk 50 karyawan, brosur promosi 1000 lembar, dan roll banner 10 unit. Mohon kirimkan penawaran harga ke email kami. Terima kasih.',
                'status' => 'replied',
                'admin_notes' => 'Corporate client - sudah dikirim penawaran harga via email',
                'replied_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.l@gmail.com',
                'phone' => '085678901234',
                'subject' => 'Komplain Pesanan #PM20261225ABC',
                'message' => 'Saya ingin menyampaikan komplain mengenai pesanan saya dengan nomor PM20261225ABC. Warna cetakan brosur tidak sesuai dengan preview yang saya lihat di website. Warna terlihat lebih pucat dari yang diharapkan. Mohon dapat ditindaklanjuti.',
                'status' => 'replied',
                'admin_notes' => 'Sudah dikonfirmasi - perbedaan di monitor customer. Dijelaskan mengenai perbedaan warna CMYK vs RGB. Customer sudah mengerti.',
                'replied_at' => Carbon::now()->subHours(12),
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'Roni Setiawan',
                'email' => 'roni.setiawan@yahoo.com',
                'phone' => '087890123456',
                'subject' => 'Pertanyaan Waktu Pengerjaan',
                'message' => 'Selamat pagi, saya ingin bertanya berapa lama waktu pengerjaan untuk mencetak 500 kartu nama dan 200 stiker vinyl? Apakah ada opsi express? Saya butuh dalam 2 hari. Terima kasih.',
                'status' => 'new',
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'name' => 'Lisa Permata',
                'email' => 'lisa.permata@email.com',
                'phone' => '089012345678',
                'subject' => 'Saran Penambahan Fitur Website',
                'message' => 'Saran untuk website PrintMaster: alangkah baiknya jika ada fitur preview 3D untuk produk-produk seperti undangan dan kalender. Ini akan membantu customer melihat hasil akhir sebelum order. Terima kasih, sukses selalu!',
                'status' => 'archived',
                'admin_notes' => 'Saran bagus - forward ke tim development',
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@email.com',
                'phone' => '081122334455',
                'subject' => 'Tanya Spesifikasi File untuk X-Banner',
                'message' => 'Mau tanya dong, untuk cetak X-Banner 60x160 cm spesifikasi file yang dibutuhkan seperti apa ya? Resolusi minimum berapa dpi? Format file apa saja yang diterima? Makasih.',
                'status' => 'read',
                'admin_notes' => 'FAQ umum - bisa diarahkan ke halaman FAQ',
                'created_at' => Carbon::now()->subHours(8),
            ],
            [
                'name' => 'Sari Wulandari',
                'email' => 'sari.w@gmail.com',
                'phone' => '082233445566',
                'subject' => 'Request Sample Kertas',
                'message' => 'Halo, apakah bisa minta sample kertas untuk kartu nama? Saya tertarik dengan kertas Linen dan Soft Touch tapi ingin lihat dan rasakan dulu sebelum order. Lokasi saya di Jakarta Selatan. Thanks.',
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
