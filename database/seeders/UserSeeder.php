<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ShippingAddress;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::create([
            'nama' => 'Super Admin',
            'email' => 'admin@printmaster.id',
            'password' => Hash::make('admin123'),
            'telepon' => '081200000001',
            'peran' => 'super_admin',
            'aktif' => true,
            'email_diverifikasi_pada' => Carbon::now(),
        ]);

        // Staff Admin
        User::create([
            'nama' => 'Staff Admin',
            'email' => 'staff@printmaster.id',
            'password' => Hash::make('staff123'),
            'telepon' => '081200000002',
            'peran' => 'admin',
            'aktif' => true,
            'email_diverifikasi_pada' => Carbon::now(),
        ]);

        // Sample Customers
        $customer1 = User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'password' => Hash::make('user123'),
            'telepon' => '081234567890',
            'peran' => 'customer',
            'aktif' => true,
            'email_diverifikasi_pada' => Carbon::now(),
        ]);

        // Create shipping address for customer 1
        ShippingAddress::create([
            'pengguna_id' => $customer1->id,
            'label' => 'Rumah',
            'nama_penerima' => 'Budi Santoso',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Kebon Jeruk No. 10, RT 05/RW 02',
            'kota' => 'Jakarta Barat',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '11530',
            'utama' => true,
        ]);

        ShippingAddress::create([
            'pengguna_id' => $customer1->id,
            'label' => 'Kantor',
            'nama_penerima' => 'Budi Santoso',
            'telepon' => '081234567891',
            'alamat' => 'Gedung Graha Niaga Lt. 5, Jl. Jend. Sudirman Kav. 58',
            'kota' => 'Jakarta Selatan',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '12190',
            'utama' => false,
        ]);

        $customer2 = User::create([
            'nama' => 'Siti Rahayu',
            'email' => 'siti@email.com',
            'password' => Hash::make('user123'),
            'telepon' => '081345678901',
            'peran' => 'customer',
            'aktif' => true,
            'email_diverifikasi_pada' => Carbon::now(),
        ]);

        ShippingAddress::create([
            'pengguna_id' => $customer2->id,
            'label' => 'Rumah',
            'nama_penerima' => 'Siti Rahayu',
            'telepon' => '081345678901',
            'alamat' => 'Jl. Cempaka Putih Raya No. 25',
            'kota' => 'Jakarta Pusat',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '10520',
            'utama' => true,
        ]);

        $customer3 = User::create([
            'nama' => 'Ahmad Wijaya',
            'email' => 'ahmad@email.com',
            'password' => Hash::make('user123'),
            'telepon' => '082112345678',
            'peran' => 'customer',
            'aktif' => true,
            'email_diverifikasi_pada' => Carbon::now(),
        ]);

        ShippingAddress::create([
            'pengguna_id' => $customer3->id,
            'label' => 'Toko',
            'nama_penerima' => 'Ahmad Wijaya',
            'telepon' => '082112345678',
            'alamat' => 'Ruko Golden Boulevard Blok C No. 15',
            'kota' => 'Tangerang',
            'provinsi' => 'Banten',
            'kode_pos' => '15143',
            'utama' => true,
        ]);

        echo "Users and addresses seeded!\n";
    }
}
