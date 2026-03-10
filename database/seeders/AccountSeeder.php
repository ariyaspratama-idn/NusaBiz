<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // 1. Assets
            ['code' => '1000', 'name' => 'ASET', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_header' => true],
            ['code' => '1100', 'name' => 'Aset Lancar', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_header' => true, 'parent_code' => '1000'],
            ['code' => '1101', 'name' => 'Kas Pusat', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_header' => false, 'parent_code' => '1100'],
            ['code' => '1102', 'name' => 'Bank BCA', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_header' => false, 'parent_code' => '1100'],
            ['code' => '1103', 'name' => 'Piutang Usaha', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_header' => false, 'parent_code' => '1100'],
            ['code' => '1104', 'name' => 'Persediaan Barang', 'type' => 'ASSET', 'normal_balance' => 'DEBIT', 'is_header' => false, 'parent_code' => '1100'],
            
            // 2. Liabilities
            ['code' => '2000', 'name' => 'KEWAJIBAN', 'type' => 'LIABILITY', 'normal_balance' => 'KREDIT', 'is_header' => true],
            ['code' => '2100', 'name' => 'Hutang Usaha', 'type' => 'LIABILITY', 'normal_balance' => 'KREDIT', 'is_header' => false, 'parent_code' => '2000'],
            
            // 3. Equity
            ['code' => '3000', 'name' => 'EKUITAS', 'type' => 'EQUITY', 'normal_balance' => 'KREDIT', 'is_header' => true],
            ['code' => '3100', 'name' => 'Modal Disetor', 'type' => 'EQUITY', 'normal_balance' => 'KREDIT', 'is_header' => false, 'parent_code' => '3000'],
            ['code' => '3200', 'name' => 'Laba Ditahan', 'type' => 'EQUITY', 'normal_balance' => 'KREDIT', 'is_header' => false, 'parent_code' => '3000'],
            
            // 4. Revenue
            ['code' => '4000', 'name' => 'PENDAPATAN', 'type' => 'REVENUE', 'normal_balance' => 'KREDIT', 'is_header' => true],
            ['code' => '4100', 'name' => 'Pendapatan Penjualan', 'type' => 'REVENUE', 'normal_balance' => 'KREDIT', 'is_header' => false, 'parent_code' => '4000'],
            
            // 5. Expense
            ['code' => '5000', 'name' => 'BEBAN', 'type' => 'EXPENSE', 'normal_balance' => 'DEBIT', 'is_header' => true],
            ['code' => '5100', 'name' => 'Beban Gaji', 'type' => 'EXPENSE', 'normal_balance' => 'DEBIT', 'is_header' => false, 'parent_code' => '5000'],
            ['code' => '5200', 'name' => 'Beban Sewa', 'type' => 'EXPENSE', 'normal_balance' => 'DEBIT', 'is_header' => false, 'parent_code' => '5000'],
            ['code' => '5300', 'name' => 'Harga Pokok Penjualan (HPP)', 'type' => 'EXPENSE', 'normal_balance' => 'DEBIT', 'is_header' => false, 'parent_code' => '5000'],
        ];

        foreach ($accounts as $accountData) {
            $parentId = null;
            if (isset($accountData['parent_code'])) {
                $parentId = \App\Models\Account::where('code', $accountData['parent_code'])->first()?->id;
            }

            \App\Models\Account::create([
                'code' => $accountData['code'],
                'name' => $accountData['name'],
                'type' => $accountData['type'],
                'normal_balance' => $accountData['normal_balance'],
                'is_header' => $accountData['is_header'],
                'parent_id' => $parentId,
            ]);
        }
    }
}
