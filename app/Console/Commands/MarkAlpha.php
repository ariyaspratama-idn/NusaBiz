<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Console\Command;

class MarkAlpha extends Command
{
    protected $signature = 'attendance:mark-alpha';
    protected $description = 'Otomatis menandai Alpha bagi karyawan yang tidak absen masuk hingga pukul 23:59';

    public function handle()
    {
        $today = now()->toDateString();
        
        // Cari user yang belum absen hari ini
        $users = User::whereDoesntHave('attendances', function($query) use ($today) {
            $query->whereDate('check_in', $today);
        })->get();

        foreach ($users as $user) {
            Attendance::create([
                'user_id' => $user->id,
                'tenant_id' => $user->tenant_id,
                'branch_id' => $user->branch_id,
                'status' => 'alpha',
                'check_in' => now(), // Placeholder time for alpha record
            ]);
        }

        $this->info('Alpha marking completed.');
    }
}
