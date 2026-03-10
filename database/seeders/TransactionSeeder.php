<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Branch;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $revenueAccount = Account::where('code', '4100')->first();
        $salaryAccount = Account::where('code', '5100')->first();
        $rentAccount = Account::where('code', '5200')->first();
        $hppAccount = Account::where('code', '5300')->first();
        
        $divisions = ['Service', 'Spareparts', 'Admin', 'F&B'];

        foreach ($branches as $branch) {
            // Seed Revenue & Expenses over last 60 days
            for ($i = 0; $i < 40; $i++) {
                $isRevenue = rand(0, 1);
                $date = Carbon::now()->subDays(rand(0, 60));
                $division = $divisions[array_rand($divisions)];
                
                if ($isRevenue) {
                    Transaction::create([
                        'transaction_no' => 'REV-' . $branch->id . '-' . rand(1000, 9999),
                        'transaction_date' => $date,
                        'branch_id' => $branch->id,
                        'account_id' => $revenueAccount->id,
                        'amount' => rand(5000000, 15000000),
                        'description' => 'Sales Revenue (' . $division . ') - ' . $branch->name,
                        'type' => 'INCOME',
                        'division' => $division
                    ]);
                } else {
                    $acc = rand(0, 2);
                    $accountId = [$salaryAccount->id, $rentAccount->id, $hppAccount->id][$acc];
                    $desc = ['Monthly Staff Salary', 'Monthly Rent', 'Cost of Goods Sold'][$acc];
                    
                    Transaction::create([
                        'transaction_no' => 'EXP-' . $branch->id . '-' . rand(1000, 9999),
                        'transaction_date' => $date,
                        'branch_id' => $branch->id,
                        'account_id' => $accountId,
                        'amount' => rand(2000000, 8000000),
                        'description' => $desc . ' (' . $division . ') - ' . $branch->name,
                        'type' => 'EXPENSE',
                        'division' => $division
                    ]);
                }
            }
        }
    }
}
