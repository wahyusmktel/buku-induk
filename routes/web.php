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

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Data Pokok Siswa
    Route::get('/siswas', [SiswaController::class, 'index'])->name('siswas.index');
    Route::get('/siswas/{siswa}', [SiswaController::class, 'show'])->name('siswas.show');
    
    // Restricted Actions (Import & Edit)
    Route::middleware(['role:Super Admin|Operator|Tata Usaha'])->group(function () {
        Route::post('/siswas/import', [SiswaController::class, 'import'])->name('siswas.import');
        Route::get('/siswas/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswas.edit');
        Route::put('/siswas/{siswa}', [SiswaController::class, 'update'])->name('siswas.update');
        
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
    });

    // Super Admin Only
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::delete('/siswas/{siswa}', [SiswaController::class, 'destroy'])->name('siswas.destroy');
    });
});

