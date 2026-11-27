<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KandangController;
use App\Http\Controllers\KondisiFisikController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SI-MBEK (Sistem Informasi Manajemen & Peternakan Kambing)
| Modul 1: Manajemen Pengguna & Kandang
| Terintegrasi dengan Modul 3: Kesehatan & Pencatatan Penyakit
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard or login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Routes (Requires login and active account)
Route::middleware(['auth', 'role:pengelola,pegawai'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Kandang - View Access for both Pengelola and Pegawai
    Route::get('/kandang', [KandangController::class, 'index'])->name('kandang.index');
    Route::get('/kandang/{kandang}', [KandangController::class, 'show'])->name('kandang.show')->whereNumber('kandang');

    // Modul 3 Kesehatan & Pencatatan Penyakit (Terintegrasi)
    Route::get('kondisi-fisik/terakhir/{kambing}', [KondisiFisikController::class, 'terakhir'])
        ->name('kondisi-fisik.terakhir');
    Route::resource('kondisi-fisik', KondisiFisikController::class)
        ->parameters(['kondisi-fisik' => 'kondisi']);

    Route::patch('penyakit/{penyakit}/sembuh', [PenyakitController::class, 'sembuh'])
        ->name('penyakit.sembuh');
    Route::resource('penyakit', PenyakitController::class)
        ->parameters(['penyakit' => 'penyakit']);

    // Placeholders for Modul 2 & Perkawinan
    Route::get('/kambing', function () {
        return view('placeholders.module', [
            'title' => 'Data Kambing',
            'moduleName' => 'Modul 2: Data Kambing',
            'description' => 'Halaman ini merupakan cakupan Modul 2 untuk mengelola data identitas utama kambing, ras, status ternak, dan penempatan kandang.'
        ]);
    })->name('kambing.index');

    Route::get('/perkawinan', function () {
        return view('placeholders.module', [
            'title' => 'Data Perkawinan',
            'moduleName' => 'Modul 3: Perkawinan & Kelahiran',
            'description' => 'Halaman ini merupakan cakupan Modul Perkawinan & Kelahiran untuk mencatat riwayat perkawinan, penjodohan, dan tanggal melahirkan.'
        ]);
    })->name('perkawinan.index');

    // Restricted Routes for Pengelola / Pemilik Only
    Route::middleware('role:pengelola')->group(function () {
        // Kandang Write Operations
        Route::get('/kandang/create', [KandangController::class, 'create'])->name('kandang.create');
        Route::post('/kandang', [KandangController::class, 'store'])->name('kandang.store');
        Route::get('/kandang/{kandang}/edit', [KandangController::class, 'edit'])->name('kandang.edit')->whereNumber('kandang');
        Route::put('/kandang/{kandang}', [KandangController::class, 'update'])->name('kandang.update')->whereNumber('kandang');
        Route::delete('/kandang/{kandang}', [KandangController::class, 'destroy'])->name('kandang.destroy')->whereNumber('kandang');
        Route::patch('/kandang/{kandang}/toggle-status', [KandangController::class, 'toggleStatus'])->name('kandang.toggle-status')->whereNumber('kandang');

        // User Management (Kelola Akun Pengelola & Pegawai)
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
});
