<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Currency::updateOrCreate(['code' => 'IDR'], [
            'name' => 'Indonesian Rupiah',
            'symbol' => 'Rp',
            'exchange_rate' => 1.0000,
            'is_default' => true,
        ]);

        \App\Models\Currency::updateOrCreate(['code' => 'USD'], [
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 15600.0000,
            'is_default' => false,
        ]);

        \App\Models\Currency::updateOrCreate(['code' => 'SGD'], [
            'name' => 'Singapore Dollar',
            'symbol' => 'S$',
            'exchange_rate' => 11600.0000,
            'is_default' => false,
        ]);
    }
}
