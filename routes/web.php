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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunPelajaranController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\BukuIndukController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\EkstrakurikulerController;
use App\Http\Controllers\EkskulPrestasiController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SiswaPromotionController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Audit Log / Riwayat Aktivitas
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/export', [ActivityController::class, 'export'])->name('activities.export');

    // Promosi / Naik Kelas
    Route::get('/siswas/promote', [SiswaPromotionController::class, 'index'])->name('siswas.promote.index');
    Route::post('/siswas/promote', [SiswaPromotionController::class, 'store'])->name('siswas.promote.store');
    Route::get('/api/rombels/{tahunId}', [SiswaPromotionController::class, 'getRombelsByYear']);

    // Master Import
    Route::post('/siswas/master-import', [SiswaController::class, 'masterImport'])->name('siswas.master-import');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/captcha', [ProfileController::class, 'captchaImage'])->name('captcha');

    // Data Pokok Siswa
    Route::get('/siswas', [SiswaController::class, 'index'])->name('siswas.index');
    Route::get('/siswas/{siswa}', [SiswaController::class, 'show'])->name('siswas.show');

    // Buku Induk (accessible to all authenticated users, editable by admin roles)
    Route::get('/buku-induk', [BukuIndukController::class, 'index'])->name('buku-induk.index');
    Route::get('/buku-induk/{nisn}', [BukuIndukController::class, 'show'])->name('buku-induk.show');
    Route::get('/buku-induk/{nisn}/print', [BukuIndukController::class, 'print'])->name('buku-induk.print');
    Route::get('/buku-induk/{nisn}/print-prestasi', [BukuIndukController::class, 'printPrestasi'])->name('buku-induk.print-prestasi');
    
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
        Route::get('/buku-induk/{nisn}/prestasi/template', [PrestasiController::class, 'downloadTemplate'])->name('prestasi.template');
        Route::post('/buku-induk/{nisn}/prestasi/import', [PrestasiController::class, 'import'])->name('prestasi.import');

        // Ekstrakurikuler Prestasi (Nilai Per-Semester)
        Route::post('/buku-induk/{nisn}/ekskul', [EkskulPrestasiController::class, 'store'])->name('ekskul.store');
        Route::get('/buku-induk/{nisn}/ekskul/template', [EkskulPrestasiController::class, 'downloadTemplate'])->name('ekskul.template');
        Route::post('/buku-induk/{nisn}/ekskul/import', [EkskulPrestasiController::class, 'import'])->name('ekskul.import');

        // Export Massal ZIP
        Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
        Route::post('/exports', [ExportController::class, 'store'])->name('exports.store');
        Route::get('/exports/progress/{id}', [ExportController::class, 'progress'])->name('exports.progress');
        Route::get('/exports/{id}/download', [ExportController::class, 'download'])->name('exports.download');
        Route::delete('/exports/{id}', [ExportController::class, 'destroy'])->name('exports.destroy');
        
        // Rombel Management
        Route::get('/rombels', [RombelController::class, 'index'])->name('rombels.index');
        Route::post('/rombels', [RombelController::class, 'store'])->name('rombels.store');
        Route::get('/rombels/{id}', [RombelController::class, 'show'])->name('rombels.show');
        Route::get('/api/rombels/{id}/unassigned-siswas', [RombelController::class, 'getUnassignedSiswas']);
        Route::post('/rombels/{id}/assign-siswas', [RombelController::class, 'assignSiswas'])->name('rombels.assign');
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
        Route::resource('ekstrakurikuler', EkstrakurikulerController::class)->except(['create', 'show', 'edit']);

        // Settings (Konfigurasi Sistem)
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Super Admin Only
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::delete('/siswas/{siswa}', [SiswaController::class, 'destroy'])->name('siswas.destroy');
    });
});
