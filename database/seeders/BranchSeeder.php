<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'code' => 'PST',
                'name' => 'Kantor Pusat',
                'type' => 'PUSAT',
                'address' => 'Jl. Sudirman No. 1, Jakarta',
                'phone' => '021-12345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CB1',
                'name' => 'Cabang Bandung',
                'type' => 'CABANG',
                'address' => 'Jl. Asia Afrika No. 10, Bandung',
                'phone' => '022-12345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CB2',
                'name' => 'Cabang Surabaya',
                'type' => 'CABANG',
                'address' => 'Jl. Tunjungan No. 20, Surabaya',
                'phone' => '031-12345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CB3',
                'name' => 'Cabang Yogyakarta',
                'type' => 'CABANG',
                'address' => 'Jl. Malioboro No. 30, Yogyakarta',
                'phone' => '0274-12345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CB4',
                'name' => 'Cabang Semarang',
                'type' => 'CABANG',
                'address' => 'Jl. Pemuda No. 40, Semarang',
                'phone' => '024-12345678',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('branches')->insert($branches);
    }
}
