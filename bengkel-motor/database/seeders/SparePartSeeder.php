<?php

namespace Database\Seeders;

use App\Models\SparePart;
use Illuminate\Database\Seeder;

class SparePartSeeder extends Seeder
{
    public function run(): void
    {
        $spareParts = [
            // Oil Products
            [
                'name' => 'Oli Matic 800ml',
                'category' => 'Oil',
                'sku' => 'OIL-MAT-001',
                'stock' => 50,
                'price' => 45000,
                'cost_price' => 35000,
                'supplier' => 'PT Pelumas Indonesia',
                'min_stock' => 10,
            ],
            [
                'name' => 'Oli Sport 1L',
                'category' => 'Oil',
                'sku' => 'OIL-SPT-001',
                'stock' => 40,
                'price' => 75000,
                'cost_price' => 60000,
                'supplier' => 'PT Pelumas Indonesia',
                'min_stock' => 10,
            ],

            // Brake Parts
            [
                'name' => 'Kampas Rem Depan',
                'category' => 'Brake',
                'sku' => 'BRK-PAD-F01',
                'stock' => 30,
                'price' => 50000,
                'cost_price' => 35000,
                'supplier' => 'PT Sparepart Motor',
                'min_stock' => 5,
            ],
            [
                'name' => 'Kampas Rem Belakang',
                'category' => 'Brake',
                'sku' => 'BRK-PAD-R01',
                'stock' => 30,
                'price' => 45000,
                'cost_price' => 32000,
                'supplier' => 'PT Sparepart Motor',
                'min_stock' => 5,
            ],

            // Engine Parts
            [
                'name' => 'Busi NGK',
                'category' => 'Engine',
                'sku' => 'ENG-PLG-001',
                'stock' => 100,
                'price' => 25000,
                'cost_price' => 18000,
                'supplier' => 'PT Busi Jaya',
                'min_stock' => 20,
            ],
            [
                'name' => 'Filter Oli',
                'category' => 'Engine',
                'sku' => 'ENG-FIL-001',
                'stock' => 60,
                'price' => 35000,
                'cost_price' => 25000,
                'supplier' => 'PT Filter Indonesia',
                'min_stock' => 15,
            ],
            [
                'name' => 'Rantai Motor',
                'category' => 'Engine',
                'sku' => 'ENG-CHN-001',
                'stock' => 25,
                'price' => 125000,
                'cost_price' => 95000,
                'supplier' => 'PT Rantai Kuat',
                'min_stock' => 5,
            ],

            // Tire Products
            [
                'name' => 'Ban Depan 80/90-14',
                'category' => 'Tire',
                'sku' => 'TIR-FRT-001',
                'stock' => 20,
                'price' => 250000,
                'cost_price' => 200000,
                'supplier' => 'PT Ban Motor',
                'min_stock' => 4,
            ],
            [
                'name' => 'Ban Belakang 90/90-14',
                'category' => 'Tire',
                'sku' => 'TIR-REA-001',
                'stock' => 20,
                'price' => 275000,
                'cost_price' => 220000,
                'supplier' => 'PT Ban Motor',
                'min_stock' => 4,
            ],
        ];

        foreach ($spareParts as $part) {
            SparePart::create($part);
        }
    }
}
