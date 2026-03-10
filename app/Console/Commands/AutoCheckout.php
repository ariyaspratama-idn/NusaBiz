<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;

class AutoCheckout extends Command
{
    protected $signature = 'attendance:auto-checkout';
    protected $description = 'Otomatis checkout karyawan yang lupa pada pukul 17:00';

    public function handle()
    {
        $attendances = Attendance::whereNull('check_out')
            ->whereNotNull('check_in')
            ->whereDate('check_in', now()->toDateString())
            ->get();

        foreach ($attendances as $attendance) {
            $attendance->update([
                'check_out' => now()->setTime(17, 0, 0),
                'status' => 'auto-checkout'
            ]);
        }

        $this->info('Auto checkout completed.');
    }
}
