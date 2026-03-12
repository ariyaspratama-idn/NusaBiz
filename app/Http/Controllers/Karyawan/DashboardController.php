<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Penggajian;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilan Dashboard Karyawan
     */
    public function index()
    {
        $user = auth()->user();
        $karyawan = Karyawan::where('user_id', $user->id)->first();

        if (!$karyawan) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Data profil karyawan Anda tidak ditemukan. Hubungi Admin.']);
        }

        $today = Carbon::today()->toDateString();
        $absensi = Absensi::where('karyawan_id', $karyawan->id)
            ->where('tanggal', $today)
            ->first();

        $latestAbsensi = Absensi::where('karyawan_id', $karyawan->id)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        $currentPayroll = Penggajian::where('karyawan_id', $karyawan->id)
            ->where('periode_bulan', Carbon::now()->format('Y-m'))
            ->first();

        return view('karyawan.dashboard', compact('karyawan', 'absensi', 'latestAbsensi', 'currentPayroll'));
    }

    /**
     * Daftar Riwayat Gaji Karyawan
     */
    public function payroll()
    {
        $user = auth()->user();
        $karyawan = Karyawan::where('user_id', $user->id)->first();
        
        $payrolls = Penggajian::where('karyawan_id', $karyawan->id)
            ->orderBy('periode_bulan', 'desc')
            ->paginate(12);

        return view('karyawan.payroll', compact('karyawan', 'payrolls'));
    }
}
