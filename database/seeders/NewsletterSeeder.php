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
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subMonths(3),
                'created_at' => Carbon::now()->subMonths(3),
            ],
            [
                'email' => 'maria.garcia@email.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subMonths(2)->subDays(15),
                'created_at' => Carbon::now()->subMonths(2)->subDays(15),
            ],
            [
                'email' => 'budi.santoso@gmail.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subMonths(2),
                'created_at' => Carbon::now()->subMonths(2),
            ],
            [
                'email' => 'siti.aminah@yahoo.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subMonths(1)->subDays(20),
                'created_at' => Carbon::now()->subMonths(1)->subDays(20),
            ],
            [
                'email' => 'ahmad.wijaya@email.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subMonths(1)->subDays(10),
                'created_at' => Carbon::now()->subMonths(1)->subDays(10),
            ],
            [
                'email' => 'lisa.permata@gmail.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subMonth(),
                'created_at' => Carbon::now()->subMonth(),
            ],
            [
                'email' => 'roni.setiawan@hotmail.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subDays(25),
                'created_at' => Carbon::now()->subDays(25),
            ],
            [
                'email' => 'dewi.lestari@email.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'email' => 'eko.prasetyo@gmail.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'email' => 'anisa.putri@email.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'email' => 'indra.kusuma@yahoo.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'email' => 'rina.maharani@gmail.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'email' => 'doni.satria@email.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'email' => 'ratna.sari@gmail.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'email' => 'bayu.pratama@email.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subDay(),
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'email' => 'nina.anggraini@yahoo.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subHours(12),
                'created_at' => Carbon::now()->subHours(12),
            ],
            [
                'email' => 'hendra.wijaya@gmail.com',
                'aktif' => true,
                'berlangganan_pada' => Carbon::now()->subHours(6),
                'created_at' => Carbon::now()->subHours(6),
            ],
            // Some unsubscribed users
            [
                'email' => 'unsubscribed1@email.com',
                'aktif' => false,
                'berlangganan_pada' => Carbon::now()->subMonths(2),
                'berhenti_langganan_pada' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subMonths(2),
            ],
            [
                'email' => 'unsubscribed2@email.com',
                'aktif' => false,
                'berlangganan_pada' => Carbon::now()->subMonths(1),
                'berhenti_langganan_pada' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now()->subMonths(1),
            ],
            [
                'email' => 'unsubscribed3@gmail.com',
                'aktif' => false,
                'berlangganan_pada' => Carbon::now()->subDays(30),
                'berhenti_langganan_pada' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(30),
            ],
        ];

        foreach ($subscribers as $subscriber) {
            $subscriber['token_berhenti'] = Str::random(32);
            NewsletterSubscriber::create($subscriber);
        }

        echo "Newsletter subscribers seeded!\n";
    }
}
