<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Handle Webhook dari Telegram.
     */
    public function handle(Request $request)
    {
        $update = $request->all();
        
        if (!isset($update['message'])) return response()->json(['status' => 'ok']);

        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        // Tangani Command
        if (str_starts_with($text, '/start')) {
            $this->telegram->sendMessage($chatId, "<b>Selamat Datang di NusaBiz Bot!</b>\n\nSilakan kirimkan nomor HP Anda yang terdaftar di sistem untuk menghubungkan akun anda.");
        } 
        elseif (str_starts_with($text, '/myid')) {
            $this->telegram->sendMessage($chatId, "Chat ID Anda: <code>$chatId</code>");
        }
        else {
            // Logika pairing via nomor HP
            $phone = preg_replace('/[^0-9]/', '', $text);
            if (strlen($phone) >= 10) {
                // Normalisasi nomor HP (hapus 0 didepan, ganti ke 62 atau sebaliknya)
                $formattedPhone = $phone;
                if (str_starts_with($phone, '0')) $formattedPhone = '62' . substr($phone, 1);
                
                $karyawan = Karyawan::where('no_hp', 'like', "%$phone%")->first();
                
                if ($karyawan) {
                    $karyawan->update(['telegram_chat_id' => $chatId]);
                    $this->telegram->sendMessage($chatId, "✅ <b>Berhasil!</b>\nAkun Anda (<b>{$karyawan->nama_lengkap}</b>) telah terhubung. Anda akan menerima notifikasi absensi di sini.");
                } else {
                    $this->telegram->sendMessage($chatId, "❌ Nomor HP tidak ditemukan dalah sistem NusaBiz. Pastikan nomor HP Anda sudah benar sesuai data karyawan.");
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
