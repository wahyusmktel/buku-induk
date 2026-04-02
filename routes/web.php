<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tentang', function () {
    return view('tentang');
});

Route::get('/kontak', function () {
    return view('kontak');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunPelajaranController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\BukuIndukController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\MataPelajaranController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Data Pokok Siswa
    Route::get('/siswas', [SiswaController::class, 'index'])->name('siswas.index');
    Route::get('/siswas/{siswa}', [SiswaController::class, 'show'])->name('siswas.show');

    // Buku Induk (accessible to all authenticated users, editable by admin roles)
    Route::get('/buku-induk', [BukuIndukController::class, 'index'])->name('buku-induk.index');
    Route::get('/buku-induk/{nisn}', [BukuIndukController::class, 'show'])->name('buku-induk.show');
    Route::get('/buku-induk/{nisn}/print', [BukuIndukController::class, 'print'])->name('buku-induk.print');
    
    // Restricted Actions (Import & Edit)
    Route::middleware(['role:Super Admin|Operator|Tata Usaha'])->group(function () {
        Route::post('/siswas/import', [SiswaController::class, 'import'])->name('siswas.import');
        Route::get('/siswas/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswas.edit');
        Route::put('/siswas/{siswa}', [SiswaController::class, 'update'])->name('siswas.update');

        // Buku Induk Edit
        Route::get('/buku-induk/{nisn}/edit', [BukuIndukController::class, 'edit'])->name('buku-induk.edit');
        Route::put('/buku-induk/{nisn}', [BukuIndukController::class, 'update'])->name('buku-induk.update');

        // Prestasi Belajar
        Route::post('/buku-induk/{nisn}/prestasi', [PrestasiController::class, 'store'])->name('prestasi.store');
        Route::delete('/buku-induk/{nisn}/prestasi/{prestasi}', [PrestasiController::class, 'destroy'])->name('prestasi.destroy');
        Route::get('/prestasi/template', [PrestasiController::class, 'downloadTemplate'])->name('prestasi.template');
        Route::post('/buku-induk/{nisn}/prestasi/import', [PrestasiController::class, 'import'])->name('prestasi.import');

        // Rombel Management
        Route::get('/rombels', [RombelController::class, 'index'])->name('rombels.index');
        Route::get('/rombels/{id}', [RombelController::class, 'show'])->name('rombels.show');
    });

    // Tahun Pelajaran Management
    Route::middleware(['role:Super Admin|Operator'])->group(function () {
        Route::get('/tahun-pelajaran', [TahunPelajaranController::class, 'index'])->name('tahun-pelajaran.index');
        Route::post('/tahun-pelajaran', [TahunPelajaranController::class, 'store'])->name('tahun-pelajaran.store');
        Route::post('/tahun-pelajaran/{id}/copy-data', [TahunPelajaranController::class, 'copyData'])->name('tahun-pelajaran.copy-data');
        Route::patch('/tahun-pelajaran/{id}/activate', [TahunPelajaranController::class, 'activate'])->name('tahun-pelajaran.activate');
        Route::delete('/tahun-pelajaran/{id}', [TahunPelajaranController::class, 'destroy'])->name('tahun-pelajaran.destroy');
        
        // Mata Pelajaran Management
        Route::resource('mata-pelajaran', MataPelajaranController::class)->except(['create', 'show', 'edit']);
    });

    // Super Admin Only
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::delete('/siswas/{siswa}', [SiswaController::class, 'destroy'])->name('siswas.destroy');
    });
});

