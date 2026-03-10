<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/complaint', [\App\Http\Controllers\WebhookController::class, 'handleComplaint']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
