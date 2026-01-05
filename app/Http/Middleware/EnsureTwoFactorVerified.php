<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        $twoFaEnabled = $user && $user->two_factor_secret && $user->two_factor_confirmed_at;
        if (!$twoFaEnabled) {
            return $next($request);
        }

        // Kalau belum lolos OTP di session
        if (!$request->session()->get('2fa_passed', false)) {
            // allow akses ke route verifikasi
            if ($request->routeIs('2fa.challenge', '2fa.verify', 'logout', '2fa.enable', '2fa.confirm', '2fa.disable')) {
                return $next($request);
            }
            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }
}
