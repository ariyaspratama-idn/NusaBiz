<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Penggajian;
use App\Traits\OptimasiGambar;
use App\Services\TelegramService;
use Illuminate\Support\Facades\DB;

class HRController extends Controller
{
    use OptimasiGambar;

    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Dashboard HR & Daftar Karyawan.
     */
    public function index()
    {
        $karyawans = Karyawan::with('user')->paginate(10);
        return view('admin.hr.index', compact('karyawans'));
    }

    /**
     * Simpan data karyawan baru.
     */
    public function storeKaryawan(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:karyawans',
            'nama_lengkap' => 'required',
            'no_hp' => 'nullable',
            'gaji_pokok' => 'required|numeric',
        ]);

        Karyawan::create($validated);

        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Proses Absen (API/Web).
     */
    public function absen(Request $request)
    {
        $karyawan = Karyawan::where('user_id', auth()->id())->first();
        if (!$karyawan) return response()->json(['message' => 'Data karyawan tidak ditemukan.'], 404);

        $tanggal = now()->toDateString();
        $jamSekarang = now()->toTimeString();
        
        // Cari apakah sudah ada absen hari ini
        $absensi = Absensi::where('karyawan_id', $karyawan->id)
            ->where('tanggal', $tanggal)
            ->first();

        $foto = null;
        if ($request->has('foto') && $request->foto) {
            $foto = $this->saveAndOptimizeBase64($request->foto, 'absensi');
        }

        if (!$absensi) {
            // Absen Masuk
            $absensi = Absensi::create([
                'karyawan_id' => $karyawan->id,
                'tanggal' => $tanggal,
                'jam_masuk' => $jamSekarang,
                'lat_masuk' => $request->lat,
                'lon_masuk' => $request->lon,
                'foto_masuk' => $foto,
                'status' => 'hadir',
                'tenant_id' => $karyawan->tenant_id,
                'branch_id' => $karyawan->branch_id,
            ]);
            $tipe = "Masuk";
        } else {
            // Absen Pulang
            $absensi->update([
                'jam_pulang' => $jamSekarang,
                'lat_pulang' => $request->lat,
                'lon_pulang' => $request->lon,
                'foto_pulang' => $foto,
            ]);
            $tipe = "Pulang";
        }

        // Notifikasi ke Telegram Karyawan jika sudah terkoneksi
        if ($karyawan->telegram_chat_id) {
            $message = "✅ <b>Konfirmasi Absensi</b>\n\n";
            $message .= "Halo <b>{$karyawan->nama_lengkap}</b>,\n";
            $message .= "Anda berhasil melakukan absen <b>{$tipe}</b> pada:\n";
            $message .= "📅 Tanggal: " . now()->translatedFormat('d F Y') . "\n";
            $message .= "⏰ Jam: " . now()->format('H:i') . " WIB\n\n";
            $message .= "<i>Tetap semangat bekerja!</i>";
            
            $this->telegram->sendMessage($karyawan->telegram_chat_id, $message);
        }

        return response()->json(['message' => "Absensi {$tipe} berhasil dicatat.", 'data' => $absensi]);
    }

    /**
     * Daftar Pengajuan Izin.
     */
    public function daftarIzin()
    {
        $izins = Izin::with('karyawan')->latest()->paginate(10);
        return view('admin.hr.izin', compact('izins'));
    }

    /**
     * Hitung Gaji Bulanan.
     */
    public function hitungGaji($bulan)
    {
        $karyawans = Karyawan::where('status', 'aktif')->get();
        
        foreach ($karyawans as $k) {
            $totalLembur = Absensi::where('karyawan_id', $k->id)
                ->whereMonth('tanggal', substr($bulan, 5, 2))
                ->sum('menit_lembur');

            // Logika sederhana: 10rb per menit lembur (contoh)
            $lembur = $totalLembur * 10000;

            Penggajian::updateOrCreate(
                ['karyawan_id' => $k->id, 'periode_bulan' => $bulan],
                [
                    'gaji_pokok' => $k->gaji_pokok,
                    'lembur' => $lembur,
                    'total_gaji' => $k->gaji_pokok + $lembur,
                    'status_pembayaran' => 'pending'
                ]
            );
        }

        return redirect()->back()->with('success', "Gaji periode $bulan berhasil dihitung.");
    }
}
