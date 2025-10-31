<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarkahController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Tidak perlu login)
|--------------------------------------------------------------------------
*/

// Redirect root ke welcome blade
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route /home (alias untuk root)
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('warkah.index');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Register (jika sudah ada controller register)
    // Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    // Route::post('/register', [RegisterController::class, 'register']);
    
    // Password Reset (jika sudah ada)
    // Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    // Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

// Logout (perlu login)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Protected Routes (Perlu Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | Dashboard (optional)
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        return redirect()->route('warkah.index');
    })->name('dashboard');

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
        Route::post('/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('kembalikan');
        Route::get('/api/available-warkah', [PeminjamanController::class, 'getAvailableWarkah'])->name('api.available-warkah');
    });

    /*
    |--------------------------------------------------------------------------
    | Permintaan Salinan Warkah
    |--------------------------------------------------------------------------
    */
    Route::prefix('permintaan')->name('permintaan.')->group(function () {
        Route::get('/', [PermintaanController::class, 'index'])->name('index');
        Route::get('/create', [PermintaanController::class, 'create'])->name('create');
        Route::post('/', [PermintaanController::class, 'store'])->name('store');

        // File routes
        Route::get('/{id}/file/{type}', [PermintaanController::class, 'lihatFile'])->name('lihatFile');
        Route::get('/{id}/download/{type}', [PermintaanController::class, 'downloadFile'])->name('downloadFile');
        Route::get('/{id}/file-content/{type}', function ($id, $type) {
            $permintaan = \App\Models\Permintaan::findOrFail($id);

            if ($type === 'nota') {
                $filePath = $permintaan->nota_dinas_masuk_file;
            } elseif ($type === 'disposisi') {
                $filePath = $permintaan->file_disposisi;
            } else {
                abort(404, 'Tipe file tidak valid');
            }

            if (empty($filePath)) {
                abort(404, 'File tidak ditemukan');
            }

            $path = storage_path('app/public/' . $filePath);

            if (!file_exists($path)) {
                abort(404, 'File tidak ditemukan di server');
            }

            return response()->file($path, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]);
        })->name('getFileContent');

        // Document routes
        Route::post('/{id}/dokumen', [PermintaanController::class, 'updateDokumen'])->name('updateDokumen');
        Route::get('/{id}/cetak', [PermintaanController::class, 'cetakPDF'])->name('cetak');
        Route::patch('/{id}/update-status', [PermintaanController::class, 'updateStatus'])->name('updateStatus');

        // Show dan delete di bagian bawah supaya tidak konflik dengan rute file
        Route::get('/{id}', [PermintaanController::class, 'show'])->name('show');
        Route::delete('/{id}', [PermintaanController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | API Routes (untuk AJAX)
    |--------------------------------------------------------------------------
    */
    Route::get('/api/warkah/search', [PermintaanController::class, 'searchWarkah'])->name('warkah.search');
    Route::get('/warkah/{id}', [PermintaanController::class, 'getWarkahDetail'])->name('warkah.detail');
    

});