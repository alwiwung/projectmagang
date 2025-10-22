<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarkahController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PermintaanController;

/*
|--------------------------------------------------------------------------
| Redirect Default Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('peminjaman.index');
});

/*
|--------------------------------------------------------------------------
| Warkah (Master Data)
|--------------------------------------------------------------------------
*/
Route::prefix('warkah')->name('warkah.')->group(function () {
    Route::get('/', [WarkahController::class, 'index'])->name('index');
    Route::get('/create', [WarkahController::class, 'create'])->name('create');
    Route::post('/', [WarkahController::class, 'store'])->name('store');
    Route::get('/export', [WarkahController::class, 'export'])->name('export');
    Route::post('/import', [WarkahController::class, 'import'])->name('import');

    Route::get('/{warkah}', [WarkahController::class, 'show'])->name('show');
    Route::get('/{warkah}/edit', [WarkahController::class, 'edit'])->name('edit');
    Route::put('/{warkah}', [WarkahController::class, 'update'])->name('update');
    Route::delete('/{warkah}', [WarkahController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [WarkahController::class, 'restore'])->name('restore');
});

/*
|--------------------------------------------------------------------------
| Peminjaman
|--------------------------------------------------------------------------
*/
Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
    Route::get('/', [PeminjamanController::class, 'index'])->name('index');
    Route::post('/', [PeminjamanController::class, 'store'])->name('store');
    Route::get('/{id}', [PeminjamanController::class, 'show'])->name('show');
    Route::post('/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('kembalikan');
    Route::get('/api/available-warkah', [PeminjamanController::class, 'getAvailableWarkah'])->name('api.available-warkah');
});

/*
|--------------------------------------------------------------------------
| Permintaan Salinan
|--------------------------------------------------------------------------
*/
Route::prefix('permintaan')->name('permintaan.')->group(function () {
    Route::get('/', [PermintaanController::class, 'index'])->name('index');
    Route::get('/create', [PermintaanController::class, 'create'])->name('create');
    Route::post('/', [PermintaanController::class, 'store'])->name('store');
    Route::get('/{id}', [PermintaanController::class, 'show'])->name('show');
    Route::get('/{id}/barcode', [PermintaanController::class, 'showBarcode'])->name('barcode');
    Route::get('/{id}/cetak', [PermintaanController::class, 'cetakPDF'])->name('cetak');
    Route::post('/{id}/status', [PermintaanController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/export/excel', [PermintaanController::class, 'exportExcel'])->name('exportExcel');
});

/*
|--------------------------------------------------------------------------
| API (Warkah)
|--------------------------------------------------------------------------
*/
Route::get('/api/warkah/search', [PermintaanController::class, 'searchWarkah'])->name('warkah.search');
Route::get('/warkah/{id}', [PermintaanController::class, 'getWarkahDetail'])->name('warkah.detail');
