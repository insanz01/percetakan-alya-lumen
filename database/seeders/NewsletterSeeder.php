<?php

namespace Database\Seeders;

use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsletterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscribers = [
            [
                'email' => 'john.doe@gmail.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subMonths(3),
                'created_at' => Carbon::now()->subMonths(3),
            ],
            [
                'email' => 'maria.garcia@email.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subMonths(2)->subDays(15),
                'created_at' => Carbon::now()->subMonths(2)->subDays(15),
            ],
            [
                'email' => 'budi.santoso@gmail.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subMonths(2),
                'created_at' => Carbon::now()->subMonths(2),
            ],
            [
                'email' => 'siti.aminah@yahoo.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subMonths(1)->subDays(20),
                'created_at' => Carbon::now()->subMonths(1)->subDays(20),
            ],
            [
                'email' => 'ahmad.wijaya@email.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subMonths(1)->subDays(10),
                'created_at' => Carbon::now()->subMonths(1)->subDays(10),
            ],
            [
                'email' => 'lisa.permata@gmail.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subMonth(),
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'email' => 'roni.setiawan@hotmail.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subDays(25),
                'created_at' => Carbon::now()->subDays(25),
            ],
            [
                'email' => 'dewi.lestari@email.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'email' => 'eko.prasetyo@gmail.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'email' => 'anisa.putri@email.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'email' => 'indra.kusuma@yahoo.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'email' => 'rina.maharani@gmail.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'email' => 'doni.satria@email.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'email' => 'ratna.sari@gmail.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'email' => 'bayu.pratama@email.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subDay(),
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'email' => 'nina.anggraini@yahoo.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subHours(12),
                'created_at' => Carbon::now()->subHours(12),
            ],
            [
                'email' => 'hendra.wijaya@gmail.com',
                'is_active' => true,
                'subscribed_at' => Carbon::now()->subHours(6),
                'created_at' => Carbon::now()->subHours(6),
            ],
            // Some unsubscribed users
            [
                'email' => 'unsubscribed1@email.com',
                'is_active' => false,
                'subscribed_at' => Carbon::now()->subMonths(2),
                'unsubscribed_at' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subMonths(2),
            ],
            [
                'email' => 'unsubscribed2@email.com',
                'is_active' => false,
                'subscribed_at' => Carbon::now()->subMonths(1),
                'unsubscribed_at' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now()->subMonths(1),
            ],
            [
                'email' => 'unsubscribed3@gmail.com',
                'is_active' => false,
                'subscribed_at' => Carbon::now()->subDays(30),
                'unsubscribed_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(30),
            ],
        ];

        foreach ($subscribers as $subscriber) {
            $subscriber['unsubscribe_token'] = Str::random(32);
            NewsletterSubscriber::create($subscriber);
        }

        echo "Newsletter subscribers seeded!\n";
    }
}
