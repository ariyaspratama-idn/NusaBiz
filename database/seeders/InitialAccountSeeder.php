<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;

class InitialAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan Tenant Utama ada
        $tenant = Tenant::firstOrCreate(
            ['name' => 'NusaBiz Enterprise'],
            ['status' => 'active']
        );

        // 2. Pastikan Cabang Utama ada
        $branch = Branch::firstOrCreate(
            ['code' => 'PST'],
            [
                'name' => 'Kantor Pusat Jakarta',
                'tenant_id' => $tenant->id,
                'type' => 'PUSAT',
                'is_active' => true
            ]
        );

        $password = Hash::make('password123');

        $users = [
            [
                'name' => 'Administrator Pusat',
                'email' => 'admin.pusat@nusabiz.com',
                'role' => 'admin-pusat',
            ],
            [
                'name' => 'Pemilik Bisnis (Owner)',
                'email' => 'owner@nusabiz.com',
                'role' => 'owner',
            ],
            [
                'name' => 'Kepala Cabang Jakarta',
                'email' => 'kepala.cabang@nusabiz.com',
                'role' => 'kepala-cabang',
            ],
            [
                'name' => 'Wakil Kepala Cabang',
                'email' => 'wakil.cabang@nusabiz.com',
                'role' => 'wakil-kepala-cabang',
            ],
            [
                'name' => 'Kasir Utama',
                'email' => 'kasir@nusabiz.com',
                'role' => 'kasir',
            ],
            [
                'name' => 'Karyawan Staff',
                'email' => 'karyawan@nusabiz.com',
                'role' => 'karyawan',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $password,
                    'role' => $userData['role'],
                    'tenant_id' => $tenant->id,
                    'branch_id' => $branch->id,
                    'is_active' => true,
                ]
            );

            // Jika role adalah karyawan operasional, buat data di tabel karyawans
            $karyawanRoles = ['karyawan', 'kasir', 'kepala-cabang', 'wakil-kepala-cabang', 'mechanic'];
            if (in_array($userData['role'], $karyawanRoles)) {
                // Generate NIP based on role and name initials or just use email prefix
                $nip = strtoupper(str_replace(['@nusabiz.com', '.'], ['', ''], $userData['email'])) . '007';
                
                \App\Models\Karyawan::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $nip,
                        'nama_lengkap' => $user->name,
                        'tenant_id' => $tenant->id,
                        'branch_id' => $branch->id,
                        'jabatan' => ucfirst(str_replace('-', ' ', $user->role)),
                        'status' => 'aktif',
                        'gaji_pokok' => 5000000,
                    ]
                );
            }
        }

        $this->command->info('Initial roles and accounts created successfully!');
        $this->command->info('Default password for all accounts: password123');
    }
}
