<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BatchController;
use App\Http\Controllers\MikrotikTestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\WgClientBulkController;
use App\Http\Controllers\WgClientController;

Route::get('/mt-test', [MikrotikTestController::class, 'index'])->name('mt.test');

/**
 * Home: arahkan ke app utama
 */
Route::get('/', fn () => redirect()->route('batches.index'));

/**
 * Dashboard (kalau kamu masih pakai)
 * Pastikan juga pakai twofactor
 */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'twofactor'])->name('dashboard');

/**
 * Profile routes (boleh auth saja, agar user bisa setup 2FA)
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2FA setup routes (auth saja, jangan twofactor)
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/confirm', [TwoFactorController::class, 'confirm'])->name('2fa.confirm');
    Route::delete('/2fa/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable');

    // 2FA challenge (auth saja)
    Route::get('/2fa/challenge', function () {
        return view('auth.2fa-challenge');
    })->name('2fa.challenge');

    Route::post('/2fa/verify', function (Request $request) {
        $request->validate(['otp' => ['required', 'digits:6']]);

        $user = $request->user();

        $google2fa = app('pragmarx.google2fa');
        $secret = decrypt($user->two_factor_secret);

        if (!$google2fa->verifyKey($secret, $request->otp)) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        $request->session()->put('2fa_passed', true);

        return redirect()->intended(route('batches.index'))->with('success', 'Verifikasi 2FA berhasil.');
    })->name('2fa.verify');
});

/**
 * Semua fitur aplikasi harus lewat auth + twofactor
 */
Route::middleware(['auth', 'twofactor'])->group(function () {
    // Batch
    Route::get('/batches', [BatchController::class, 'index'])->name('batches.index');
    Route::get('/batches/create', [BatchController::class, 'create'])->name('batches.create');
    Route::post('/batches', [BatchController::class, 'store'])->name('batches.store');
    Route::get('/batches/{batch}', [BatchController::class, 'show'])->name('batches.show');

    // Clients in batch
    Route::post('/batches/{batch}/clients', [WgClientController::class, 'store'])->name('clients.store');

    // delete
    Route::delete('/batches/{batch}', [BatchController::class, 'destroy'])->name('batches.destroy');
    Route::delete('/clients/{client}', [WgClientController::class, 'destroy'])->name('clients.destroy');

    // Action buttons
    Route::post('/clients/{client}/provision', [WgClientController::class, 'provision'])->name('clients.provision');
    Route::get('/clients/{client}/download', [WgClientController::class, 'download'])->name('clients.download');
    Route::post('/clients/{client}/revoke', [WgClientController::class, 'revoke'])->name('clients.revoke');

    // Bulk actions
    Route::post('/batches/{batch}/clients/bulk-provision', [WgClientBulkController::class, 'provision'])
        ->name('clients.bulkProvision');

    Route::post('/batches/{batch}/clients/bulk-revoke', [WgClientBulkController::class, 'revoke'])
        ->name('clients.bulkRevoke');
});

require __DIR__ . '/auth.php';
