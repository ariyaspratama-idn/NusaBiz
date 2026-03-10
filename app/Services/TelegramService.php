<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $token;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token');
    }

    /**
     * Kirim notifikasi real-time ke Telegram Bot.
     */
    public function sendMessage($chatId, $message)
    {
        if (!$this->token || !$chatId) {
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Telegram Notification Error: " . $e->getMessage());
            return false;
        }
    }

    public function broadcastToAdmins($message, $tenantId = null)
    {
        // Logika untuk mengirim ke semua admin di tenant tertentu
    }
}
