<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/complaint', [\App\Http\Controllers\WebhookController::class, 'handleComplaint']);

// HR & Absensi API
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/hr/absen', [\App\Http\Controllers\Admin\HRController::class, 'absen']);
});

// External Analysis Webhooks
Route::post('/analysis/webhooks/complaint', [\App\Http\Controllers\Admin\AnalysisController::class, 'handleWebhookComplaint']);

// Telegram Bot Webhook
Route::post('/telegram/webhook', [\App\Http\Controllers\TelegramController::class, 'handle']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
