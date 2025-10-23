<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarkahController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PermintaanController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard / Main Page (setelah login)
|--------------------------------------------------------------------------
*/
// Route::get('/dashboard', function () {
//     return redirect()->route('peminjaman.index');
// })->name('dashboard');


/*
|--------------------------------------------------------------------------
| Redirect Default Route
|--------------------------------------------------------------------------
*/
// Route::get('/', function () {
//     return redirect()->route('peminjaman.index');
// });

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

Route::post('/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');


    Route::get('/api/available-warkah', [PeminjamanController::class, 'getAvailableWarkah'])->name('api.available-warkah');
});

/*
|--------------------------------------------------------------------------
|/*
|--------------------------------------------------------------------------
| Permintaan Salinan Warkah
|--------------------------------------------------------------------------
*/


Route::prefix('permintaan')->group(function () {
    Route::get('/', [PermintaanController::class, 'index'])->name('permintaan.index');
    Route::get('/create', [PermintaanController::class, 'create'])->name('permintaan.create');
    Route::post('/', [PermintaanController::class, 'store'])->name('permintaan.store');

    // ⚠️ Rute khusus file — HARUS di atas /{id}
    Route::get('/{id}/file/{type}', [PermintaanController::class, 'lihatFile'])->name('permintaan.lihatFile');
    Route::get('/{id}/download/{type}', [PermintaanController::class, 'downloadFile'])->name('permintaan.downloadFile');


  
    Route::post('/{id}/dokumen', [PermintaanController::class, 'updateDokumen'])->name('permintaan.updateDokumen');
    Route::get('/{id}/cetak', [PermintaanController::class, 'cetakPDF'])->name('permintaan.cetak');
    Route::patch('/{id}/update-status', [PermintaanController::class, 'updateStatus'])->name('permintaan.updateStatus');


    // 👇 show dan delete di bagian bawah supaya tidak konflik dengan rute file
    Route::get('/{id}', [PermintaanController::class, 'show'])->name('permintaan.show');
    Route::delete('/{id}', [PermintaanController::class, 'destroy'])->name('permintaan.destroy');
});


// Route khusus untuk cetak PDF (letakkan SEBELUM resource route atau gunakan nama berbeda)
Route::get('/permintaan/{id}/cetak', [PermintaanController::class, 'cetakPDF'])->name('permintaan.cetak');
/*
|--------------------------------------------------------------------------
| API (Warkah)
|--------------------------------------------------------------------------
*/
Route::get('/api/warkah/search', [PermintaanController::class, 'searchWarkah'])->name('warkah.search');
Route::get('/warkah/{id}', [PermintaanController::class, 'getWarkahDetail'])->name('warkah.detail');
