<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SuratBalasanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PemberkasanController;
use App\Http\Controllers\HomeController;

    Route::get('/', [HomeController::class, 'index'])->name('welcome');
    Route::get('faq', [FAQController::class, 'index'])->name('faq');

    // Auth Routes
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout'])->name('logout')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('complete-profile', [AuthController::class, 'showCompleteProfile'])->name('complete-profile');
    Route::post('complete-profile', [AuthController::class, 'completeProfile']);

    // Google OAuth Routes
    Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::post('cancel-registration', [AuthController::class, 'cancelRegistration'])->name('cancel-registration');

    Route::middleware(['auth'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('mitra', [MitraController::class, 'index'])->name('mitra');
        Route::get('activity', [ActivityController::class, 'index'])->name('activity');
        Route::delete('activity/clear', [ActivityController::class, 'clearAllActivities'])->middleware('role:admin')->name('activity.clear');

        // Document Management Routes
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/', [DocumentController::class, 'index'])->name('index');
            Route::post('/khs/upload', [DocumentController::class, 'uploadKhs'])->name('khs.upload');
            Route::post('/khs/upload/multiple', [DocumentController::class, 'uploadKhsMultiple'])->name('khs.upload.multiple');
            Route::post('/surat-pengantar/upload', [DocumentController::class, 'uploadSuratPengantar'])->name('surat-pengantar.upload');
            Route::delete('/surat-pengantar/{id}', [DocumentController::class, 'deleteSuratPengantar'])->name('surat-pengantar.delete');
            Route::post('/select-mitra', [DocumentController::class, 'selectMitra'])->name('select-mitra');
            Route::post('/surat/upload', [DocumentController::class, 'uploadSuratBalasan'])->name('surat.upload');
            Route::post('/laporan/upload', [DocumentController::class, 'uploadLaporan'])->name('laporan.upload');
            Route::get('/preview/{type}/{filename}', [DocumentController::class, 'previewFile'])->name('preview');
            Route::get('/download/{type}/{filename}', [DocumentController::class, 'downloadFile'])->name('download');
            Route::delete('/khs/{id}', [DocumentController::class, 'deleteKhs'])->name('khs.delete');
            Route::delete('/surat-balasan/{id}', [DocumentController::class, 'deleteSuratBalasan'])->name('surat-balasan.delete');
            Route::delete('/laporan/{id}', [DocumentController::class, 'deleteLaporan'])->name('laporan.delete');
            Route::post('/save-semester-data', [DocumentController::class, 'saveSemesterData'])->name('save-semester-data');
            Route::post('/delete-semester-data', [DocumentController::class, 'deleteSemesterData'])->name('delete-semester-data');
            Route::post('/save-gdrive-links', [DocumentController::class, 'saveGdriveLinks'])->name('save-gdrive-links');
            Route::get('/load-gdrive-links', [DocumentController::class, 'loadGdriveLinks'])->name('load-gdrive-links');
            Route::post('/activate-pkl-status', [DocumentController::class, 'activatePklStatus'])->name('activate-pkl-status');
            Route::post('/deactivate-pkl-status', [DocumentController::class, 'deactivatePklStatus'])->name('deactivate-pkl-status');
            Route::post('/complete-pkl-status', [DocumentController::class, 'completePklStatus'])->name('complete-pkl-status');
            Route::post('/revert-pkl-status', [DocumentController::class, 'revertPklStatus'])->name('revert-pkl-status');
        });
    });
    
// Jadwal Seminar routes
Route::get('/jadwal-seminar', [\App\Http\Controllers\AdminJadwalSeminarController::class, 'index'])->name('jadwal-seminar');

// Serve jadwal seminar files
Route::get('/jadwal/{filename}', function ($filename) {
    $path = storage_path('app/public/jadwal/' . $filename);
    
    \Log::info('Trying to serve file: ' . $path);
    \Log::info('File exists: ' . (file_exists($path) ? 'yes' : 'no'));
    
    if (!file_exists($path)) {
        \Log::error('File not found: ' . $path);
        abort(404);
    }
    
    return response()->file($path);
})->name('jadwal.file');
    
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
        Route::get('/jadwal-seminar', [\App\Http\Controllers\AdminJadwalSeminarController::class, 'manage'])->name('jadwal-seminar.manage');
        Route::get('/jadwal-seminar/create', [\App\Http\Controllers\AdminJadwalSeminarController::class, 'create'])->name('jadwal-seminar.create');
        Route::post('/jadwal-seminar', [\App\Http\Controllers\AdminJadwalSeminarController::class, 'store'])->name('jadwal-seminar.store');
        Route::post('/jadwal-seminar/{id}/toggle', [\App\Http\Controllers\AdminJadwalSeminarController::class, 'toggle'])->name('jadwal-seminar.toggle');
        Route::delete('/jadwal-seminar/{id}', [\App\Http\Controllers\AdminJadwalSeminarController::class, 'destroy'])->name('jadwal-seminar.destroy');
        
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
        Route::post('penilaian/store', [\App\Http\Controllers\RubrikController::class, 'store'])->name('penilaian.store');
        Route::get('/validation', [\App\Http\Controllers\AdminController::class, 'validation'])->name('validation');
        Route::post('/validation/khs/{id}', [\App\Http\Controllers\AdminController::class, 'validateKhs'])->name('validation.khs');
        Route::post('/validation/surat-balasan/{id}', [\App\Http\Controllers\AdminController::class, 'validateSuratBalasan'])->name('validation.surat-balasan');
        Route::post('/validation/laporan/{id}', [\App\Http\Controllers\AdminController::class, 'validateLaporan'])->name('validation.laporan');
        Route::post('/validation/surat-pengantar/{id}', [\App\Http\Controllers\AdminController::class, 'validateSuratPengantar'])->name('validation.surat-pengantar');
        Route::get('/mahasiswa/{id}/detail', [\App\Http\Controllers\ValidationController::class, 'mahasiswaDetail'])->name('mahasiswa.detail');
        
        // Penilaian dan Nilai Akhir
        Route::get('/penilaian-dosen', [\App\Http\Controllers\AdminController::class, 'penilaianDosen'])->name('penilaian-dosen');
        Route::get('/nilai-akhir', [\App\Http\Controllers\AdminController::class, 'nilaiAkhir'])->name('nilai-akhir');
        Route::get('/kelola-akun', [\App\Http\Controllers\AdminController::class, 'kelolaAkun'])->name('kelola-akun');
        Route::post('/kelola-akun', [\App\Http\Controllers\AdminController::class, 'createUser'])->name('create-user');
        Route::put('/kelola-akun/{id}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('update-user');
        Route::delete('/kelola-akun/{id}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('delete-user');
        Route::post('/kelola-akun/bulk-delete', [\App\Http\Controllers\AdminController::class, 'bulkDeleteUsers'])->name('bulk-delete-users');
        Route::post('/kelola-akun/bulk-edit-dospem', [\App\Http\Controllers\AdminController::class, 'bulkEditDospem'])->name('bulk-edit-dospem');
        Route::post('/kelola-akun/bulk-reset-documents', [\App\Http\Controllers\AdminController::class, 'bulkResetDocuments'])->name('bulk-reset-documents');
        Route::delete('/kelola-akun/orphaned/{id}', [\App\Http\Controllers\AdminController::class, 'deleteOrphanedProfil'])->name('delete-orphaned-profil');
        Route::post('/kelola-akun/bulk-delete-orphaned', [\App\Http\Controllers\AdminController::class, 'bulkDeleteOrphanedProfils'])->name('bulk-delete-orphaned-profils');
        Route::get('/kelola-mitra', [\App\Http\Controllers\AdminController::class, 'kelolaMitra'])->name('kelola-mitra');
        Route::post('/kelola-mitra', [\App\Http\Controllers\AdminController::class, 'createMitra'])->name('create-mitra');
        Route::put('/kelola-mitra/{id}', [\App\Http\Controllers\AdminController::class, 'updateMitra'])->name('update-mitra');
        Route::delete('/kelola-mitra/{id}', [\App\Http\Controllers\AdminController::class, 'deleteMitra'])->name('delete-mitra');
        
        // System settings routes
        Route::get('/system-settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])->name('system-settings');
        Route::put('/system-settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'update'])->name('system-settings.update');
        Route::post('/system-settings/upload-login-bg', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'uploadLoginBackground'])->name('system-settings.upload-login-bg');

        // (catatan: kamu punya dua set /validation di admin sebelumnya — aku biarkan yang di atas saja agar tidak ganda)
    });
    
    // Dosen Pembimbing routes
    Route::middleware(['role:dospem'])->prefix('dospem')->name('dospem.')->group(function () {
        // Main validation page (mahasiswa list)
        Route::get('/validation', [\App\Http\Controllers\ValidationController::class, 'mahasiswaList'])->name('validation');
        Route::get('/mahasiswa/{id}/detail', [\App\Http\Controllers\ValidationController::class, 'mahasiswaDetail'])->name('mahasiswa.detail');
        Route::get('/mahasiswa/{mahasiswaId}/preview/{type}/{filename}', [\App\Http\Controllers\ValidationController::class, 'previewMahasiswaFile'])->name('mahasiswa.preview');

        // New validation methods for 4 categories
        Route::put('/validate/{mahasiswaId}/kelayakan', [\App\Http\Controllers\ValidationController::class, 'validateKelayakan'])->name('validate.kelayakan');
        Route::put('/validate/{mahasiswaId}/dokumen-pendukung', [\App\Http\Controllers\ValidationController::class, 'validateDokumenPendukung'])->name('validate.dokumen_pendukung');
        Route::put('/validate/{mahasiswaId}/instansi-mitra', [\App\Http\Controllers\ValidationController::class, 'validateInstansiMitra'])->name('validate.instansi_mitra');
        Route::put('/validate/{mahasiswaId}/akhir', [\App\Http\Controllers\ValidationController::class, 'validateAkhir'])->name('validate.akhir');

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
    
