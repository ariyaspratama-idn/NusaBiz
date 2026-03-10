<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin', 'description' => 'Full access to all modules and branches'],
            ['name' => 'admin_pusat', 'description' => 'Manage central operations and view reports'],
            ['name' => 'admin_cabang', 'description' => 'Manage branch operations'],
            ['name' => 'finance_manager', 'description' => 'Approve transactions and view reports'],
            ['name' => 'finance_staff', 'description' => 'Input daily transactions'],
            ['name' => 'auditor', 'description' => 'View only access'],
            ['name' => 'owner', 'description' => 'Business owner with full financial overview'],
            ['name' => 'investor', 'description' => 'Shareholder with report viewing access'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }

        $permissions = [
            // General
            ['name' => 'view_dashboard', 'module' => 'General'],
            // Accounting
            ['name' => 'view_accounts', 'module' => 'Accounting'],
            ['name' => 'create_accounts', 'module' => 'Accounting'],
            ['name' => 'view_journals', 'module' => 'Accounting'],
            ['name' => 'create_journals', 'module' => 'Accounting'],
            ['name' => 'post_journals', 'module' => 'Accounting'],
            // inventory
            ['name' => 'view_products', 'module' => 'Inventory'],
            ['name' => 'manage_stock', 'module' => 'Inventory'],
            // Transactions
            ['name' => 'create_transactions', 'module' => 'Transactions'],
            ['name' => 'view_reports', 'module' => 'Reports'],
        ];

        foreach ($permissions as $permission) {
            \App\Models\Permission::create($permission);
        }

        // Link Super Admin to all permissions
        $superAdmin = \App\Models\Role::where('name', 'super_admin')->first();
        $allPermissionIds = \App\Models\Permission::pluck('id')->toArray();
        $superAdmin->permissions()->attach($allPermissionIds);

        // Link Finance Manager to some permissions
        $financeManager = \App\Models\Role::where('name', 'finance_manager')->first();
        $financePermissions = \App\Models\Permission::whereIn('module', ['General', 'Accounting', 'Transactions', 'Reports'])->pluck('id')->toArray();
        $financeManager->permissions()->attach($financePermissions);
    }
}
