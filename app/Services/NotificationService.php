<?php

namespace App\Services;

use App\Models\EcOrder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Kirim notifikasi pembaruan status pesanan.
     */
    public static function sendOrderUpdate(EcOrder $order)
    {
        $statusLabel = [
            'perlu_diproses' => 'Sedang Diverifikasi',
            'diproses'       => 'Sedang Diproses',
            'dikirim'        => 'Dalam Pengiriman',
            'selesai'        => 'Selesai',
            'dibatalkan'     => 'Dibatalkan',
        ];

        $label = $statusLabel[$order->status] ?? $order->status;
        $message = "Halo *{$order->customer_name}*,\n\nStatus pesanan Anda *#{$order->order_number}* telah diperbarui menjadi: *{$label}*.\n";

        if ($order->status === 'dikirim' && $order->tracking_number) {
            $message .= "Nomor Resi: *{$order->tracking_number}*\n";
        }

        $message .= "\nTerima kasih telah berbelanja di NusaBiz!";

        // 1. Kirim via WhatsApp (Fonnte)
        self::sendWhatsApp($order->customer_phone, $message);

        // 2. Kirim via Email
        try {
            Mail::raw($message, function($mail) use ($order) {
                $mail->to($order->customer_email)
                     ->subject("Update Pesanan NusaBiz #{$order->order_number}");
            });
        } catch (\Exception $e) {
            Log::warning("Gagal mengirim Email ke {$order->customer_email}: " . $e->getMessage());
        }
    }

    /**
     * Kirim pesan WhatsApp menggunakan API Fonnte.
     */
    public static function sendWhatsApp($target, $message)
    {
        $token = env('FONNTE_TOKEN');
        if (!$token) {
            Log::warning("Fonnte Token tidak ditemukan di .env. Notifikasi WA ke {$target} dibatalkan.");
            return;
        }

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
            ]);

            if ($response->status() >= 400) {
                Log::error("Gagal mengirim WA ke {$target}: " . ($response->json()['detail'] ?? $response->status()));
            }
        } catch (\Exception $e) {
            Log::error("Error saat mengirim WA ke {$target}: " . $e->getMessage());
        }
    }
}
