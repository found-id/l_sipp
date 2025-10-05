<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/complete-profile', [AuthController::class, 'showCompleteProfile'])->name('complete-profile');
Route::post('/complete-profile', [AuthController::class, 'completeProfile']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth routes - Try custom controller first
Route::get('/auth/google', [\App\Http\Controllers\CustomGoogleOAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\CustomGoogleOAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Fallback Google OAuth routes (if custom doesn't work)
// Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
// Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Document routes
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::post('/khs', [DocumentController::class, 'uploadKhs'])->name('khs.upload');
        Route::post('/surat-balasan', [DocumentController::class, 'uploadSuratBalasan'])->name('surat.upload');
        Route::post('/laporan', [DocumentController::class, 'uploadLaporan'])->name('laporan.upload');
        Route::put('/khs/{id}', [DocumentController::class, 'updateKhs'])->name('khs.update');
        Route::put('/surat-balasan/{id}', [DocumentController::class, 'updateSuratBalasan'])->name('surat.update');
        Route::put('/laporan/{id}', [DocumentController::class, 'updateLaporan'])->name('laporan.update');
    });
    
    // Activity routes
    Route::get('/activity', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activity');
    
    // Mitra routes
    Route::get('/mitra', [\App\Http\Controllers\MitraController::class, 'index'])->name('mitra');
    
    // Jadwal Seminar routes
    Route::get('/jadwal-seminar', [\App\Http\Controllers\JadwalSeminarController::class, 'index'])->name('jadwal-seminar');
    
    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });
    
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/kelola-data', [\App\Http\Controllers\AdminController::class, 'kelolaData'])->name('kelola-data');
        Route::get('/validation', [\App\Http\Controllers\AdminController::class, 'validation'])->name('validation');
        Route::post('/validation/khs/{id}', [\App\Http\Controllers\AdminController::class, 'validateKhs'])->name('validation.khs');
        Route::post('/validation/surat-balasan/{id}', [\App\Http\Controllers\AdminController::class, 'validateSuratBalasan'])->name('validation.surat-balasan');
        Route::post('/validation/laporan/{id}', [\App\Http\Controllers\AdminController::class, 'validateLaporan'])->name('validation.laporan');
        Route::get('/kelola-akun', [\App\Http\Controllers\AdminController::class, 'kelolaAkun'])->name('kelola-akun');
        Route::post('/kelola-akun', [\App\Http\Controllers\AdminController::class, 'createUser'])->name('create-user');
        Route::put('/kelola-akun/{id}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('update-user');
        Route::delete('/kelola-akun/{id}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('delete-user');
        Route::get('/kelola-mitra', [\App\Http\Controllers\AdminController::class, 'kelolaMitra'])->name('kelola-mitra');
        Route::post('/kelola-mitra', [\App\Http\Controllers\AdminController::class, 'createMitra'])->name('create-mitra');
        Route::put('/kelola-mitra/{id}', [\App\Http\Controllers\AdminController::class, 'updateMitra'])->name('update-mitra');
        Route::delete('/kelola-mitra/{id}', [\App\Http\Controllers\AdminController::class, 'deleteMitra'])->name('delete-mitra');
        Route::get('/validation', [\App\Http\Controllers\ValidationController::class, 'index'])->name('validation');
        Route::put('/validation/khs/{id}', [\App\Http\Controllers\ValidationController::class, 'validateKhs'])->name('validation.khs');
        Route::put('/validation/surat-balasan/{id}', [\App\Http\Controllers\ValidationController::class, 'validateSuratBalasan'])->name('validation.surat');
        Route::put('/validation/laporan/{id}', [\App\Http\Controllers\ValidationController::class, 'validateLaporan'])->name('validation.laporan');
        Route::post('/validation/bulk', [\App\Http\Controllers\ValidationController::class, 'bulkValidate'])->name('validation.bulk');
    });
    
    // Dosen Pembimbing routes
    Route::middleware(['role:dospem'])->prefix('dospem')->name('dospem.')->group(function () {
        Route::get('/validation', [\App\Http\Controllers\ValidationController::class, 'index'])->name('validation');
        Route::put('/validation/khs/{id}', [\App\Http\Controllers\ValidationController::class, 'validateKhs'])->name('validation.khs');
        Route::put('/validation/surat-balasan/{id}', [\App\Http\Controllers\ValidationController::class, 'validateSuratBalasan'])->name('validation.surat');
        Route::put('/validation/laporan/{id}', [\App\Http\Controllers\ValidationController::class, 'validateLaporan'])->name('validation.laporan');
        Route::post('/validation/bulk', [\App\Http\Controllers\ValidationController::class, 'bulkValidate'])->name('validation.bulk');
        Route::get('/biodata/{id}', [\App\Http\Controllers\ValidationController::class, 'getBiodata'])->name('biodata');
    });
    
    // Mahasiswa routes
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        // Add more mahasiswa routes here
    });
});
