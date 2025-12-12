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
            'name' => 'Super Admin',
            'email' => 'admin@printmaster.id',
            'password' => Hash::make('admin123'),
            'phone' => '081200000001',
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        // Staff Admin
        User::create([
            'name' => 'Staff Admin',
            'email' => 'staff@printmaster.id',
            'password' => Hash::make('staff123'),
            'phone' => '081200000002',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        // Sample Customers
        $customer1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'password' => Hash::make('user123'),
            'phone' => '081234567890',
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        // Create shipping address for customer 1
        ShippingAddress::create([
            'user_id' => $customer1->id,
            'label' => 'Rumah',
            'recipient_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'address' => 'Jl. Kebon Jeruk No. 10, RT 05/RW 02',
            'city' => 'Jakarta Barat',
            'province' => 'DKI Jakarta',
            'postal_code' => '11530',
            'is_default' => true,
        ]);

        ShippingAddress::create([
            'user_id' => $customer1->id,
            'label' => 'Kantor',
            'recipient_name' => 'Budi Santoso',
            'phone' => '081234567891',
            'address' => 'Gedung Graha Niaga Lt. 5, Jl. Jend. Sudirman Kav. 58',
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'postal_code' => '12190',
            'is_default' => false,
        ]);

        $customer2 = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@email.com',
            'password' => Hash::make('user123'),
            'phone' => '081345678901',
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        ShippingAddress::create([
            'user_id' => $customer2->id,
            'label' => 'Rumah',
            'recipient_name' => 'Siti Rahayu',
            'phone' => '081345678901',
            'address' => 'Jl. Cempaka Putih Raya No. 25',
            'city' => 'Jakarta Pusat',
            'province' => 'DKI Jakarta',
            'postal_code' => '10520',
            'is_default' => true,
        ]);

        $customer3 = User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'ahmad@email.com',
            'password' => Hash::make('user123'),
            'phone' => '082112345678',
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        ShippingAddress::create([
            'user_id' => $customer3->id,
            'label' => 'Toko',
            'recipient_name' => 'Ahmad Wijaya',
            'phone' => '082112345678',
            'address' => 'Ruko Golden Boulevard Blok C No. 15',
            'city' => 'Tangerang',
            'province' => 'Banten',
            'postal_code' => '15143',
            'is_default' => true,
        ]);

        echo "Users and addresses seeded!\n";
    }
}
