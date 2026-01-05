<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function enable(Request $request)
    {
        $user = $request->user();

        // Generate secret
        $google2fa = app('pragmarx.google2fa');
        $secret = $google2fa->generateSecretKey();

        $user->two_factor_secret = encrypt($secret);
        $user->two_factor_confirmed_at = null;
        $user->save();

        // QR
        $qr = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return back()->with('2fa_qr', $qr)->with('success', 'Scan QR lalu masukkan kode OTP untuk konfirmasi.');
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'otp' => ['required','digits:6'],
        ]);

        $user = $request->user();
        if (!$user->two_factor_secret) {
            return back()->with('error', '2FA belum di-generate.');
        }

        $google2fa = app('pragmarx.google2fa');
        $secret = decrypt($user->two_factor_secret);

        $valid = $google2fa->verifyKey($secret, $request->otp);

        if (!$valid) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        $user->two_factor_confirmed_at = now();
        $user->save();

        return back()->with('success', '2FA berhasil diaktifkan.');
    }

    public function disable(Request $request)
    {
        $user = $request->user();

        $user->two_factor_secret = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return back()->with('success', '2FA dinonaktifkan.');
    }
}
