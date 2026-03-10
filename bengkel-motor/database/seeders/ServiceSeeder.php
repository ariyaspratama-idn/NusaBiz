<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // Oil Change Services
            [
                'name' => 'Ganti Oli Matic',
                'category' => 'Oil Change',
                'price' => 75000,
                'duration_minutes' => 30,
                'description' => 'Penggantian oli untuk motor matic',
            ],
            [
                'name' => 'Ganti Oli Sport',
                'category' => 'Oil Change',
                'price' => 100000,
                'duration_minutes' => 30,
                'description' => 'Penggantian oli untuk motor sport',
            ],
            [
                'name' => 'Ganti Oli Bebek',
                'category' => 'Oil Change',
                'price' => 65000,
                'duration_minutes' => 25,
                'description' => 'Penggantian oli untuk motor bebek',
            ],

            // Maintenance Services
            [
                'name' => 'Service Berkala',
                'category' => 'Maintenance',
                'price' => 150000,
                'duration_minutes' => 60,
                'description' => 'Service rutin berkala termasuk pengecekan menyeluruh',
            ],
            [
                'name' => 'Tune Up',
                'category' => 'Maintenance',
                'price' => 200000,
                'duration_minutes' => 90,
                'description' => 'Tune up lengkap untuk performa optimal',
            ],
            [
                'name' => 'Ganti Ban',
                'category' => 'Tire',
                'price' => 50000,
                'duration_minutes' => 20,
                'description' => 'Jasa ganti ban motor (harga ban terpisah)',
            ],
            [
                'name' => 'Tambal Ban',
                'category' => 'Tire',
                'price' => 15000,
                'duration_minutes' => 15,
                'description' => 'Tambal ban bocor',
            ],

            // Brake Services
            [
                'name' => 'Ganti Kampas Rem',
                'category' => 'Brake',
                'price' => 75000,
                'duration_minutes' => 30,
                'description' => 'Penggantian kampas rem depan/belakang',
            ],
            [
                'name' => 'Service Rem Cakram',
                'category' => 'Brake',
                'price' => 100000,
                'duration_minutes' => 45,
                'description' => 'Service dan pembersihan sistem rem cakram',
            ],

            // Engine Services
            [
                'name' => 'Ganti Busi',
                'category' => 'Engine',
                'price' => 25000,
                'duration_minutes' => 15,
                'description' => 'Penggantian busi motor',
            ],
            [
                'name' => 'Setel Klep',
                'category' => 'Engine',
                'price' => 125000,
                'duration_minutes' => 60,
                'description' => 'Penyetelan klep motor',
            ],
            [
                'name' => 'Ganti Rantai',
                'category' => 'Engine',
                'price' => 150000,
                'duration_minutes' => 75,
                'description' => 'Penggantian rantai dan gear set',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
