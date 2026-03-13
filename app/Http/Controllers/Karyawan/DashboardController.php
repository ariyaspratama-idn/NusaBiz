<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Penggajian;
use App\Models\Izin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Services\TelegramService;

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

    /**
     * Submit Pengajuan Izin / Lembur
     */
    public function storeIzin(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:izin,sakit,cuti,lembur',
            'alasan' => 'required|string',
            'bukti' => 'required|image|max:5120',
        ]);

        $user = auth()->user();
        $karyawan = Karyawan::where('user_id', $user->id)->first();

        // Cek lembur otomatis jika ada lat/lon dan jam masuk belum diset jam pulang
        $catatan = '';
        if ($request->tipe == 'lembur' && $request->filled('lat') && $request->filled('lon')) {
            $catatan = "Lokasi submit Lembur: {$request->lat}, {$request->lon}";
        }

        $path = $request->file('bukti')->store('izin_bukti', 'public');

        Izin::create([
            'karyawan_id' => $karyawan->id,
            'tenant_id' => $karyawan->tenant_id,
            'tipe' => $request->tipe,
            'tanggal_mulai' => Carbon::today(),
            'tanggal_selesai' => Carbon::today(),
            'alasan' => $request->alasan,
            'bukti_path' => $path,
            'status' => 'pending',
            'catatan_admin' => $catatan
        ]);

        // Notifikasi Telegram ke Kepala Cabang / Owner (Bisa ditangani via Observer/TelegramService)
        // Kita simulasikan pemanggilan jika layanan tersedia.
        try {
            $telegram = app(TelegramService::class);
            $pesan = "⚠️ <b>Pengajuan {$request->tipe} Baru</b>\n\nNIP: {$karyawan->nip}\nNama: {$karyawan->nama_lengkap}\nKeterangan: {$request->alasan}";
            // Kirim ke grup atau admin
            // $telegram->sendMessage(...) 
        } catch (\Exception $e) {
            // Abaikan jika tidak diset
        }

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim dan menunggu validasi.');
    }

    /**
     * Update Profil (Nomor Telepon / Telegram)
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();
        $karyawan = Karyawan::where('user_id', $user->id)->first();
        
        if ($karyawan) {
            $karyawan->update([
                'no_hp' => $request->no_hp
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
