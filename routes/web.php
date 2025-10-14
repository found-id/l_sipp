<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SuratBalasanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\PemberkasanController;

// Public routes
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('welcome');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/complete-profile', [AuthController::class, 'showCompleteProfile'])->name('complete-profile');
Route::post('/complete-profile', [AuthController::class, 'completeProfile']);
Route::post('/cancel-registration', [AuthController::class, 'cancelRegistration'])->name('cancel-registration');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// FAQ route
Route::get('/faq', [FAQController::class, 'index'])->name('faq');

// Google OAuth routes - Try custom controller first
Route::get('/auth/google', [\App\Http\Controllers\CustomGoogleOAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\CustomGoogleOAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

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
        Route::get('/preview/{type}/{filename}', [DocumentController::class, 'previewFile'])->name('preview');
Route::get('/download/{type}/{filename}', [DocumentController::class, 'downloadFile'])->name('download');
Route::post('/save-semester-data', [DocumentController::class, 'saveSemesterData'])->name('save-semester-data');
Route::post('/delete-semester-data', [DocumentController::class, 'deleteSemesterData'])->name('delete-semester-data');
Route::delete('/khs/{id}', [DocumentController::class, 'deleteKhs'])->name('khs.delete');
Route::get('/load-gdrive-links', [DocumentController::class, 'loadGdriveLinks'])->name('load-gdrive-links');
Route::post('/save-gdrive-links', [DocumentController::class, 'saveGdriveLinks'])->name('save-gdrive-links');
        Route::delete('/surat-balasan/{id}', [DocumentController::class, 'deleteSuratBalasan'])->name('surat-balasan.delete');
        Route::delete('/laporan/{id}', [DocumentController::class, 'deleteLaporan'])->name('laporan.delete');
    });
    
    // Activity routes
    Route::get('/activity', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activity');
    
    // Mitra routes
    Route::get('/mitra', [\App\Http\Controllers\MitraController::class, 'index'])->name('mitra');
    
    // Jadwal Seminar routes
    Route::get('/jadwal-seminar', [\App\Http\Controllers\JadwalSeminarManagementController::class, 'index'])->name('jadwal-seminar');
    
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
        Route::post('/assign-dospem', [\App\Http\Controllers\AdminController::class, 'assignDospem'])->name('assign-dospem');
        
        // Jadwal Seminar Management
        Route::get('/jadwal-seminar', [\App\Http\Controllers\JadwalSeminarManagementController::class, 'manage'])->name('jadwal-seminar.manage');
        Route::get('/jadwal-seminar/create', [\App\Http\Controllers\JadwalSeminarManagementController::class, 'create'])->name('jadwal-seminar.create');
        Route::post('/jadwal-seminar', [\App\Http\Controllers\JadwalSeminarManagementController::class, 'store'])->name('jadwal-seminar.store');
        Route::post('/jadwal-seminar/{id}/toggle', [\App\Http\Controllers\JadwalSeminarManagementController::class, 'toggle'])->name('jadwal-seminar.toggle');
        Route::delete('/jadwal-seminar/{id}', [\App\Http\Controllers\JadwalSeminarManagementController::class, 'destroy'])->name('jadwal-seminar.destroy');
        
        // Rubrik Management
        Route::get('/rubrik', [\App\Http\Controllers\RubrikController::class, 'index'])->name('rubrik.index');
        Route::post('/rubrik/create-form', [\App\Http\Controllers\RubrikController::class, 'createForm'])->name('rubrik.create-form');
        Route::get('/rubrik/{id}/edit', [\App\Http\Controllers\RubrikController::class, 'edit'])->name('rubrik.edit');
        Route::post('/rubrik/{formId}/add-item', [\App\Http\Controllers\RubrikController::class, 'addItem'])->name('rubrik.add-item');
        Route::post('/rubrik/{id}/toggle', [\App\Http\Controllers\RubrikController::class, 'toggleForm'])->name('rubrik.toggle');
        Route::delete('/rubrik/{id}', [\App\Http\Controllers\RubrikController::class, 'deleteForm'])->name('rubrik.delete');
        Route::put('/rubrik/item/{id}', [\App\Http\Controllers\RubrikController::class, 'updateItem'])->name('rubrik.update-item');
        Route::post('/rubrik/update-order', [\App\Http\Controllers\RubrikController::class, 'updateOrder'])->name('rubrik.update-order');
        Route::delete('/rubrik/item/{id}', [\App\Http\Controllers\RubrikController::class, 'deleteItem'])->name('rubrik.delete-item');
        Route::get('/validation', [\App\Http\Controllers\AdminController::class, 'validation'])->name('validation');
        Route::post('/validation/khs/{id}', [\App\Http\Controllers\AdminController::class, 'validateKhs'])->name('validation.khs');
        Route::post('/validation/surat-balasan/{id}', [\App\Http\Controllers\AdminController::class, 'validateSuratBalasan'])->name('validation.surat-balasan');
        Route::post('/validation/laporan/{id}', [\App\Http\Controllers\AdminController::class, 'validateLaporan'])->name('validation.laporan');
        
        // Penilaian dan Nilai Akhir
        Route::get('/penilaian-dosen', [\App\Http\Controllers\AdminController::class, 'penilaianDosen'])->name('penilaian-dosen');
        Route::get('/nilai-akhir', [\App\Http\Controllers\AdminController::class, 'nilaiAkhir'])->name('nilai-akhir');
        Route::get('/kelola-akun', [\App\Http\Controllers\AdminController::class, 'kelolaAkun'])->name('kelola-akun');
        Route::post('/kelola-akun', [\App\Http\Controllers\AdminController::class, 'createUser'])->name('create-user');
        Route::put('/kelola-akun/{id}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('update-user');
        Route::delete('/kelola-akun/{id}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('delete-user');
        Route::get('/kelola-mitra', [\App\Http\Controllers\AdminController::class, 'kelolaMitra'])->name('kelola-mitra');
        Route::post('/kelola-mitra', [\App\Http\Controllers\AdminController::class, 'createMitra'])->name('create-mitra');
        Route::put('/kelola-mitra/{id}', [\App\Http\Controllers\AdminController::class, 'updateMitra'])->name('update-mitra');
        Route::delete('/kelola-mitra/{id}', [\App\Http\Controllers\AdminController::class, 'deleteMitra'])->name('delete-mitra');
        
        // System settings routes
        Route::get('/system-settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])->name('system-settings');
        Route::put('/system-settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'update'])->name('system-settings.update');

        // (catatan: kamu punya dua set /validation di admin sebelumnya — aku biarkan yang di atas saja agar tidak ganda)
    });
    
    // Dosen Pembimbing routes
    Route::middleware(['role:dospem'])->prefix('dospem')->name('dospem.')->group(function () {
        Route::get('/validation', [\App\Http\Controllers\ValidationController::class, 'index'])->name('validation');
        Route::put('/validation/khs/{id}', [\App\Http\Controllers\ValidationController::class, 'validateKhs'])->name('validation.khs');
        Route::put('/validation/surat-balasan/{id}', [\App\Http\Controllers\ValidationController::class, 'validateSuratBalasan'])->name('validation.surat');
        Route::put('/validation/laporan/{id}', [\App\Http\Controllers\ValidationController::class, 'validateLaporan'])->name('validation.laporan');
        Route::post('/validation/bulk', [\App\Http\Controllers\ValidationController::class, 'bulkValidate'])->name('validation.bulk');
        Route::get('/biodata/{id}', [\App\Http\Controllers\ValidationController::class, 'getBiodata'])->name('biodata');
        
        // Penilaian routes
        Route::get('/penilaian', [\App\Http\Controllers\DospemPenilaianController::class, 'index'])->name('penilaian');
        Route::post('/penilaian', [\App\Http\Controllers\DospemPenilaianController::class, 'store'])->name('penilaian.store');
    });
    
    // Mahasiswa routes
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/hasil-penilaian', [\App\Http\Controllers\MahasiswaHasilPenilaianController::class, 'index'])->name('hasil-penilaian');
    });

    // ===========================
    // Pemberkasan (STEP 1 UI + Upload KHS per semester)
    // ===========================
    Route::prefix('pemberkasan')->name('pemberkasan.')->group(function () {
        Route::get('/', fn() => redirect()->route('pemberkasan.cek'))->name('index');
        Route::get('/cek-kelayakan', [PemberkasanController::class, 'cekKelayakan'])->name('cek');

        // Upload KHS per semester (1..4) untuk cek kelayakan
        Route::post('/cek-kelayakan/khs/{semester}', [PemberkasanController::class, 'uploadKhsSemester'])
            ->whereNumber('semester')
            ->name('khs.semester');

        // (opsional) kalau masih ada form lama yang submit ke 'khs.multi', kamu bisa aktifkan alias ini:
        // Route::post('/khs-multi', [DocumentController::class, 'uploadKhs'])->name('khs.multi');
    });
});
