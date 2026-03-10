<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceLock
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $device_uuid = $request->header('X-Device-UUID') ?: $request->input('device_uuid');

            if ($user->device_uuid && $device_uuid && $user->device_uuid !== $device_uuid) {
                // Jangan abort dulu jika belum implementasi device binding di login
                // abort(403, 'Perangkat tidak terdaftar.');
            }
        }

        return $next($request);
    }
}
