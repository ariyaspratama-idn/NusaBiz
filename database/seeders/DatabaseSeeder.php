<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Branch;
use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder dasar hanya jika belum ada data kunci
        if (Role::count() === 0) {
            $this->call([RoleSeeder::class]);
        }
        if (Branch::count() === 0) {
            $this->call([BranchSeeder::class]);
        }
        if (Account::count() === 0) {
            $this->call([AccountSeeder::class]);
        }

        $superAdminRole = Role::where('name', 'super_admin')->first();
        $firstBranch = Branch::first();

        // Super Admin
        User::updateOrCreate(
            ['email' => 'admin@nusabiz.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role' => User::ROLE_SUPER_ADMIN,
                'role_id' => $superAdminRole?->id,
                'branch_id' => $firstBranch?->id,
                'is_active' => true,
            ]
        );

        // Akun Cabang (Bandung)
        $branchBandung = Branch::where('code', 'CB1')->first();
        $roleCashier = Role::where('name', 'cashier')->first();
        
        User::updateOrCreate(
            ['email' => 'bandung@example.com'],
            [
                'name' => 'Kasir Bandung',
                'password' => bcrypt('password'),
                'role' => User::ROLE_CASHIER,
                'role_id' => $roleCashier?->id ?? 2,
                'branch_id' => $branchBandung?->id,
                'is_active' => true,
            ]
        );
    }
}
