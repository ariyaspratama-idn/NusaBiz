<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Bengkel',
            'email' => 'admin@bengkel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Bengkel No. 1, Jakarta',
        ]);

        // Create Mechanic Users
        User::create([
            'name' => 'Budi Mekanik',
            'email' => 'budi@bengkel.com',
            'password' => Hash::make('password'),
            'role' => 'mechanic',
            'phone' => '081234567891',
            'address' => 'Jl. Mekanik No. 1, Jakarta',
        ]);

        User::create([
            'name' => 'Andi Mekanik',
            'email' => 'andi@bengkel.com',
            'password' => Hash::make('password'),
            'role' => 'mechanic',
            'phone' => '081234567892',
            'address' => 'Jl. Mekanik No. 2, Jakarta',
        ]);

        // Create Sample Customer with Membership Barcode
        User::create([
            'name' => 'John Customer',
            'email' => 'john@customer.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567893',
            'address' => 'Jl. Customer No. 1, Jakarta',
            'membership_barcode' => 'BM-202602001',
            'barcode_printed_at' => now(),
        ]);

        // System Settings
        Setting::create([
            'key' => 'default_oil_interval_months',
            'value' => '1',
            'type' => 'integer',
            'description' => 'Default oil change interval in months (1 or 2)',
        ]);

        Setting::create([
            'key' => 'first_reminder_days',
            'value' => '7',
            'type' => 'integer',
            'description' => 'Days after due date to send first reminder',
        ]);

        Setting::create([
            'key' => 'second_reminder_days',
            'value' => '30',
            'type' => 'integer',
            'description' => 'Days after due date to send second reminder',
        ]);

        Setting::create([
            'key' => 'bengkel_name',
            'value' => 'Bengkel Motor Sejahtera',
            'type' => 'string',
            'description' => 'Workshop name',
        ]);

        Setting::create([
            'key' => 'bengkel_phone',
            'value' => '021-12345678',
            'type' => 'string',
            'description' => 'Workshop phone number',
        ]);

        Setting::create([
            'key' => 'bengkel_address',
            'value' => 'Jl. Raya Motor No. 123, Jakarta',
            'type' => 'string',
            'description' => 'Workshop address',
        ]);

        // Call other seeders
        $this->call([
            ServiceSeeder::class,
            SparePartSeeder::class,
        ]);
    }
}
