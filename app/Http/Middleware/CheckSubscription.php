<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $requiredPackage
     */
    public function handle(Request $request, Closure $next, ?string $requiredPackage = null): Response
    {
        $user = Auth::user();
        
        // Skip for owner (central level) or if not logged in
        if (!$user || $user->role === 'owner') {
            return $next($request);
        }

        $pesantren = $request->get('pesantren'); // Assuming pesantren is injected by tenant resolver or available in request
        
        // If pesantren data not in request, try to get from user
        if (!$pesantren && $user->pesantren_id) {
            $pesantren = \App\Models\Pesantren::find($user->pesantren_id);
        }

        if (!$pesantren) {
            return $next($request);
        }

        // 1. Allow access to billing routes always (so they can pay)
        if ($request->is('billing*') || $request->routeIs('billing.*')) {
            return $next($request);
        }

        // 2. Real-time Expiration Check
        $isExpired = !$pesantren->expired_at || Carbon::parse($pesantren->expired_at)->isPast();
        
        if ($isExpired) {
            return redirect()->route('billing.index')->with('warning', 'Masa aktif langganan Anda telah habis. Silakan lakukan perpanjangan.');
        }

        // 3. Package Gating
        if ($requiredPackage === 'advance' && $pesantren->package !== 'advance') {
            return redirect()->route('billing.plans')->with('error', 'Fitur ini hanya tersedia pada paket ADVANCE. Silakan upgrade paket Anda.');
        }

        return $next($request);
    }
}
