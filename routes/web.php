<?php
// File: routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarkahController;

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