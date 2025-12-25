<?php

use App\Http\Controllers\BatchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WgClientBulkController;
use App\Http\Controllers\WgClientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MikrotikTestController;

Route::get('/mt-test', [MikrotikTestController::class, 'index'])->name('mt.test');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', fn () => redirect('/batches'));

Route::middleware(['auth'])->group(function () {
    // Batch
    Route::get('/batches', [BatchController::class, 'index'])->name('batches.index');
    Route::get('/batches/create', [BatchController::class, 'create'])->name('batches.create');
    Route::post('/batches', [BatchController::class, 'store'])->name('batches.store');
    Route::get('/batches/{batch}', [BatchController::class, 'show'])->name('batches.show');

    // Clients in batch
    Route::post('/batches/{batch}/clients', [WgClientController::class, 'store'])->name('clients.store');

    //delete 
    Route::delete('/batches/{batch}', [BatchController::class, 'destroy'])->name('batches.destroy');
    Route::delete('/clients/{client}', [WgClientController::class, 'destroy'])->name('clients.destroy');

    // Action buttons (nanti diisi logikanya)
    Route::post('/clients/{client}/provision', [WgClientController::class, 'provision'])->name('clients.provision');
    Route::get('/clients/{client}/download', [WgClientController::class, 'download'])->name('clients.download');
    Route::post('/clients/{client}/revoke', [WgClientController::class, 'revoke'])->name('clients.revoke');

    
    Route::post('/batches/{batch}/clients/bulk-provision', [WgClientBulkController::class, 'provision'])
        ->name('clients.bulkProvision');

    Route::post('/batches/{batch}/clients/bulk-revoke', [WgClientBulkController::class, 'revoke'])
        ->name('clients.bulkRevoke');
});


require __DIR__.'/auth.php';
