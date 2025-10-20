<?php
// File: routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarkahController;
use App\Http\Controllers\PeminjamanController;


Route::get('/', function () {
    return view('welcome');
});
// Import Eksport Warkah Routes (tanpa middleware)
Route::get('/warkah/export', [WarkahController::class, 'export'])->name('warkah.export');
Route::post('/warkah/import', [WarkahController::class, 'import'])->name('warkah.import');

// Master Data Warkah Routes (tanpa middleware)
Route::get('/warkah', [WarkahController::class, 'index'])->name('warkah.index');
Route::get('/warkah/create', [WarkahController::class, 'create'])->name('warkah.create');
Route::post('/warkah', [WarkahController::class, 'store'])->name('warkah.store');
Route::get('/warkah/{warkah}', [WarkahController::class, 'show'])->name('warkah.show');
Route::get('/warkah/{warkah}/edit', [WarkahController::class, 'edit'])->name('warkah.edit');
Route::put('/warkah/{warkah}', [WarkahController::class, 'update'])->name('warkah.update');
Route::delete('/warkah/{warkah}', [WarkahController::class, 'destroy'])->name('warkah.destroy');
Route::put('/warkah/{id}/restore', [WarkahController::class, 'restore'])->name('warkah.restore');

Route::get('/', function () {
    return redirect()->route('peminjaman.index');
});

Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
    Route::get('/', [PeminjamanController::class, 'index'])->name('index');
    Route::get('/{id}', [PeminjamanController::class, 'show'])->name('show');
    Route::post('/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('kembalikan');
    // Route untuk create akan ditambahkan nanti
});
Route::post('/', [PeminjamanController::class, 'store'])->name('store');
Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
    Route::get('/', [PeminjamanController::class, 'index'])->name('index');
    Route::post('/', [PeminjamanController::class, 'store'])->name('store');
    Route::get('/{id}', [PeminjamanController::class, 'show'])->name('show');
    Route::post('/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('kembalikan');
});

Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
    Route::get('/', [PeminjamanController::class, 'index'])->name('index');
    Route::post('/', [PeminjamanController::class, 'store'])->name('store');

    Route::get('/{id}', [PeminjamanController::class, 'show'])->name('show');
    Route::post('/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('kembalikan');
});

// ROUTE API - LETAKKAN DI ATAS Route::resource
// ============================================
Route::get('peminjaman/api/available-warkah', [PeminjamanController::class, 'getAvailableWarkah'])
    ->name('peminjaman.api.available-warkah');

// Route existing peminjaman
Route::resource('peminjaman', PeminjamanController::class);
Route::post('peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])
    ->name('peminjaman.kembalikan');

    

    
