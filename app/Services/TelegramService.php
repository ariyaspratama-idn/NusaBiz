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
     * Kirim pesan ke chat id tertentu.
     */
    public function sendMessage($chatId, $message, $replyMarkup = null)
    {
        if (!$this->token || !$chatId) return false;

        try {
            $data = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ];

            if ($replyMarkup) {
                $data['reply_markup'] = json_encode($replyMarkup);
            }

            $response = Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", $data);

            if ($response->successful()) return true;

            Log::error('Telegram API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Telegram Service Exception: ' . $e->getMessage());
            return false;
        }
    }
}
