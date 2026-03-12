<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Branch;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle incoming complaints from external sources (Google Maps, etc.)
     * Payload expected:
     * {
     *   "source": "GOOGLE_MAPS",
     *   "branch_external_id": "place_id_123",
     *   "description": "The service was slow...",
     *   "external_id": "review_id_abc",
     *   "external_url": "https://maps.google.com/..."
     * }
     */
    public function handleComplaint(Request $request)
    {
        // Simple token check for demo (in production use proper Auth/Middleware)
        if ($request->header('X-Webhook-Token') !== config('services.webhook.token', 'dev-token-123')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'source' => 'required|string',
            'branch_external_id' => 'required|string',
            'description' => 'required|string',
            'external_id' => 'nullable|string',
            'external_url' => 'nullable|url',
        ]);

        // Find branch by Google Maps ID or other external mapping
        $branch = Branch::where('google_maps_id', $validated['branch_external_id'])->first();

        if (!$branch) {
            Log::warning("Automated complaint received for unknown branch ID: " . $validated['branch_external_id']);
            return response()->json(['message' => 'Branch not found'], 404);
        }

        $complaint = Complaint::create([
            'branch_id' => $branch->id,
            'description' => "[AUTOMATED] " . $validated['description'],
            'source' => strtoupper($validated['source']),
            'external_id' => $validated['external_id'],
            'external_url' => $validated['external_url'],
            'date' => now()->toDateString(),
            'status' => 'OPEN',
            'user_id' => 1, // System/Admin user
        ]);

        // Notifikasi ke Telegram Admin
        $adminChatId = env('ADMIN_TELEGRAM_CHAT_ID');
        if ($adminChatId) {
            $telegram = app(\App\Services\TelegramService::class);
            $msg = "📢 <b>Pengaduan Otomatis Baru</b>\n\n";
            $msg .= "Sumber: <b>{$complaint->source}</b>\n";
            $msg .= "Cabang: <b>{$branch->nama_cabang}</b>\n";
            $msg .= "Pesan: <i>\"{$validated['description']}\"</i>\n";
            if ($complaint->external_url) {
                $msg .= "\n🔗 <a href='{$complaint->external_url}'>Lihat Sumber Asli</a>";
            }
            $telegram->sendMessage($adminChatId, $msg);
        }

        return response()->json([
            'success' => true,
            'message' => 'Complaint recorded automatically',
            'id' => $complaint->id
        ]);
    }
}
