<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "\n========================================\n";
        echo "   PrintMaster Database Seeder\n";
        echo "========================================\n\n";

        // 1. Seed Users and Shipping Addresses
        echo "1. Seeding users and addresses...\n";
        $this->call(UserSeeder::class);

        // 2. Seed Categories
        echo "\n2. Seeding categories...\n";
        $this->call(CategorySeeder::class);

        // 3. Seed Products
        echo "\n3. Seeding products...\n";
        $this->call(ProductSeeder::class);

        // 4. Seed Promos
        echo "\n4. Seeding promos...\n";
        $this->call(PromoSeeder::class);

        // 5. Seed Orders
        echo "\n5. Seeding orders...\n";
        $this->call(OrderSeeder::class);

        // 6. Seed Settings
        echo "\n6. Seeding settings...\n";
        $this->call(SettingSeeder::class);

        // 7. Seed Contact Messages
        echo "\n7. Seeding contact messages...\n";
        $this->call(ContactMessageSeeder::class);

        // 8. Seed Newsletter Subscribers
        echo "\n8. Seeding newsletter subscribers...\n";
        $this->call(NewsletterSeeder::class);

        echo "\n========================================\n";
        echo "   Database seeded successfully!\n";
        echo "========================================\n\n";

        echo "Admin Credentials:\n";
        echo "  Email: admin@printmaster.id\n";
        echo "  Password: admin123\n\n";

        echo "Staff Credentials:\n";
        echo "  Email: staff@printmaster.id\n";
        echo "  Password: staff123\n\n";

        echo "Customer Credentials:\n";
        echo "  Email: budi@email.com\n";
        echo "  Password: user123\n";
        echo "  Email: siti@email.com\n";
        echo "  Password: user123\n";
        echo "  Email: ahmad@email.com\n";
        echo "  Password: user123\n\n";

        echo "Sample Promo Codes:\n";
        echo "  WELCOME10 - 10% off (max Rp50k)\n";
        echo "  HEMAT50K - Rp50k off\n";
        echo "  AKHIRTAHUN25 - 25% off (max Rp100k)\n";
        echo "  BULK15 - 15% off (min Rp1jt)\n";
        echo "  FIRSTORDER - Rp30k off\n";
        echo "  WEEKEND20 - 20% off weekend only\n";
        echo "  BISNIS100K - Rp100k off (min Rp2jt)\n\n";

        echo "Demo Data Summary:\n";
        echo "  - 5 Users (2 Admin + 3 Customers)\n";
        echo "  - 10 Categories\n";
        echo "  - 10+ Products\n";
        echo "  - 12+ Orders (various statuses)\n";
        echo "  - 8 Promo Codes\n";
        echo "  - 8 Contact Messages\n";
        echo "  - 20 Newsletter Subscribers\n";
        echo "  - 30+ Application Settings\n\n";
    }
}
