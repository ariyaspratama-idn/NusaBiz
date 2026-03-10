<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Logika sederhana: Ambil tenant_id dari user yang sedang login
        // Di masa depan bisa dikembangkan berdasarkan subdomain atau domain
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->tenant_id) {
                session(['tenant_id' => $user->tenant_id]);
            }
        }

        // Jika guest dan belum ada tenant_id di session, coba cari tenant default
        if (!session()->has('tenant_id')) {
            $defaultTenant = \App\Models\Tenant::where('status', 'active')->first();
            if ($defaultTenant) {
                session(['tenant_id' => $defaultTenant->id]);
            }
        }

        return $next($request);
    }
}
