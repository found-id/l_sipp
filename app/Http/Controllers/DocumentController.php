<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Khs;
use App\Models\KhsManualTranskrip;
use App\Models\SuratBalasan;
use App\Models\LaporanPkl;
use App\Models\Mitra;
use App\Models\ProfilMahasiswa;
use App\Services\PdfExtractionService;

class DocumentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'mahasiswa') {
            $khsFiles = $user->khs()->orderBy('semester')->get();
            $khsManualTranskrip = $user->khsManualTranskrip()->orderBy('semester')->get();
            $suratBalasan = $user->suratBalasan()->latest()->first();
            $laporanPkl = $user->laporanPkl()->latest()->first();
            $mitra = Mitra::all();
            
            // Count actual KHS files in storage (check physical files)
            $khsFileCount = 0;
            $khsFilesInStorage = [];
            
            // Get all KHS files for this user
            $allKhsFiles = $user->khs()->get();
            
            // Check each semester (1-5) for uploaded KHS files
            for ($semester = 1; $semester <= 5; $semester++) {
                $semesterKhsFiles = $allKhsFiles->where('semester', $semester);
                
                if ($semesterKhsFiles->count() > 0) {
                    // Check if physical file exists in storage AND is a PDF file
                    $hasPhysicalFile = false;
                    foreach ($semesterKhsFiles as $khs) {
                        if (Storage::disk('public')->exists($khs->file_path) && 
                            pathinfo($khs->file_path, PATHINFO_EXTENSION) === 'pdf') {
                            $hasPhysicalFile = true;
                            \Log::info("Found physical PDF file for semester {$semester}: {$khs->file_path}");
                            break;
                        } else {
                            \Log::info("Physical PDF file not found for semester {$semester}: {$khs->file_path}");
                        }
                    }
                    
                    if ($hasPhysicalFile) {
                        $khsFileCount++;
                        $khsFilesInStorage[] = $semester;
                    }
                }
            }
            
            // Also check if there are any orphaned files in storage directory
            $storagePath = 'documents/khs';
            if (Storage::disk('public')->exists($storagePath)) {
                $files = Storage::disk('public')->files($storagePath);
                \Log::info("Files in storage directory: " . implode(', ', $files));
            }
            
            // Debug: Log the data being passed
            \Log::info('DocumentController index - User: ' . $user->id . ' (' . $user->name . ')');
            \Log::info('KHS Manual Transkrip count: ' . $khsManualTranskrip->count());
            \Log::info('KHS Files in storage count: ' . $khsFileCount . '/5');
            \Log::info('KHS Files in storage semesters: ' . implode(', ', $khsFilesInStorage));
            foreach ($khsManualTranskrip as $khs) {
                \Log::info('KHS Manual Transkrip - ID: ' . $khs->id . ', Semester: ' . $khs->semester . ', Data length: ' . strlen($khs->transcript_data));
            }
            
            return view('documents.index', compact('khsFiles', 'khsManualTranskrip', 'suratBalasan', 'laporanPkl', 'mitra', 'khsFileCount'));
        }
        
        // For dospem and admin - show documents from their students
        $documents = $this->getDocumentsForSupervisor($user);
        
        return view('documents.supervisor', compact('documents'));
    }

    public function uploadKhs(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'semester' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        $semester = $request->input('semester');
        
        // Delete old KHS for this semester if exists
        $oldKhs = $user->khs()->where('semester', $semester)->first();
        if ($oldKhs) {
            Storage::delete($oldKhs->file_path);
            $oldKhs->delete();
        }

        // Store new file with semester naming convention
        $file = $request->file('file');
        $nim = $user->profilMahasiswa?->nim ?? $user->id;
        $nama = str_replace(' ', '_', $user->name);
        $filename = 'KHS_Semester-' . $semester . '_' . $nama . '_' . $nim . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/khs', $filename, 'public');

        // Create KHS record
        $khs = Khs::create([
            'mahasiswa_id' => $user->id,
            'file_path' => $path,
            'semester' => $semester,
            'status_validasi' => 'menunggu',
        ]);


        // Log activity
        \App\Models\HistoryAktivitas::create([
            'id_user' => $user->id,
            'id_mahasiswa' => $user->id,
            'tipe' => 'upload_dokumen',
            'pesan' => [
                'action' => 'upload_dokumen',
                'document_type' => 'KHS',
                'semester' => $semester,
                'mahasiswa' => $user->name,
                'file_name' => $filename,
            ],
        ]);

        return redirect()->route('documents.index')->with('success', "KHS Semester {$semester} berhasil diupload!");
    }

    public function uploadSuratBalasan(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
            'mitra_id' => 'nullable|exists:mitra,id',
            'mitra_nama_custom' => 'nullable|string|max:150',
        ]);

        $user = Auth::user();
        
        // Delete old surat balasan if exists
        $oldSurat = $user->suratBalasan()->latest()->first();
        if ($oldSurat) {
            Storage::delete($oldSurat->file_path);
            $oldSurat->delete();
        }

        // Store new file
        $file = $request->file('file');
        $nim = $user->profilMahasiswa?->nim ?? $user->id;
        $nama = str_replace(' ', '_', $user->name);
        $filename = 'Surat_Balasan_' . $nama . '_' . $nim . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/surat_balasan', $filename, 'public');

        // Create SuratBalasan record
        SuratBalasan::create([
            'mahasiswa_id' => $user->id,
            'mitra_id' => $request->mitra_id,
            'mitra_nama_custom' => $request->mitra_nama_custom,
            'file_path' => $path,
            'status_validasi' => 'menunggu',
        ]);

        // Log activity
        \App\Models\HistoryAktivitas::create([
            'id_user' => $user->id,
            'id_mahasiswa' => $user->id,
            'tipe' => 'upload_dokumen',
            'pesan' => [
                'action' => 'upload_dokumen',
                'document_type' => 'Surat Balasan',
                'mahasiswa' => $user->name,
                'file_name' => $filename,
            ],
        ]);

        return redirect()->route('documents.index')->with('success', 'Surat Balasan berhasil diupload!');
    }

    public function uploadLaporan(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $user = Auth::user();
        
        // Delete old laporan if exists
        $oldLaporan = $user->laporanPkl()->latest()->first();
        if ($oldLaporan) {
            Storage::delete($oldLaporan->file_path);
            $oldLaporan->delete();
        }

        // Store new file
        $file = $request->file('file');
        $nim = $user->profilMahasiswa?->nim ?? $user->id;
        $nama = str_replace(' ', '_', $user->name);
        $filename = 'Laporan_PKL_' . $nama . '_' . $nim . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/laporan_pkl', $filename, 'public');

        // Create LaporanPkl record
        LaporanPkl::create([
            'mahasiswa_id' => $user->id,
            'file_path' => $path,
            'status_validasi' => 'menunggu',
        ]);

        // Log activity
        \App\Models\HistoryAktivitas::create([
            'id_user' => $user->id,
            'id_mahasiswa' => $user->id,
            'tipe' => 'upload_dokumen',
            'pesan' => [
                'action' => 'upload_dokumen',
                'document_type' => 'Laporan PKL',
                'mahasiswa' => $user->name,
                'file_name' => $filename,
            ],
        ]);

        return redirect()->route('documents.index')->with('success', 'Laporan PKL berhasil diupload!');
    }

    public function updateKhs(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:menunggu,belum_valid,tervalidasi,revisi',
        ]);

        $khs = Khs::findOrFail($id);
        $khs->update([
            'status_validasi' => $request->status_validasi,
        ]);

        return redirect()->back()->with('success', 'Status KHS berhasil diupdate!');
    }

    public function updateSuratBalasan(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:menunggu,belum_valid,tervalidasi,revisi',
        ]);

        $surat = SuratBalasan::findOrFail($id);
        $surat->update([
            'status_validasi' => $request->status_validasi,
        ]);

        return redirect()->back()->with('success', 'Status Surat Balasan berhasil diupdate!');
    }

    public function updateLaporan(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:menunggu,belum_valid,tervalidasi,revisi',
        ]);

        $laporan = LaporanPkl::findOrFail($id);
        $laporan->update([
            'status_validasi' => $request->status_validasi,
        ]);

        return redirect()->back()->with('success', 'Status Laporan PKL berhasil diupdate!');
    }

    private function getDocumentsForSupervisor($user)
    {
        if ($user->role === 'dospem') {
            $mahasiswaIds = $user->mahasiswaBimbingan()->pluck('id_mahasiswa');
        } else {
            // Admin can see all documents
            $mahasiswaIds = \App\Models\User::mahasiswa()->pluck('id');
        }

        return [
            'khs' => Khs::whereIn('mahasiswa_id', $mahasiswaIds)->with('mahasiswa')->get(),
            'surat_balasan' => SuratBalasan::whereIn('mahasiswa_id', $mahasiswaIds)->with(['mahasiswa', 'mitra'])->get(),
            'laporan_pkl' => LaporanPkl::whereIn('mahasiswa_id', $mahasiswaIds)->with('mahasiswa')->get(),
        ];
    }

    /**
     * Process TPK data extraction from KHS PDF
     */





    /**
     * Preview file with proper access control
     */
    public function previewFile($type, $filename)
    {
        try {
            $user = Auth::user();
            
            Log::info('Preview file request', [
                'type' => $type,
                'filename' => $filename,
                'user_id' => $user->id
            ]);
            
            // Validate type
            if (!in_array($type, ['khs', 'surat-balasan', 'laporan'])) {
                Log::error('Invalid file type', ['type' => $type]);
                abort(404);
            }
            
            // Find the file based on type and user
            $file = null;
            switch ($type) {
                case 'khs':
                    $file = $user->khs()->where('file_path', 'LIKE', '%' . $filename)->first();
                    break;
                case 'surat-balasan':
                    $file = $user->suratBalasan()->where('file_path', 'LIKE', '%' . $filename)->first();
                    break;
                case 'laporan':
                    $file = $user->laporanPkl()->where('file_path', 'LIKE', '%' . $filename)->first();
                    break;
            }
            
            if (!$file) {
                Log::error('File not found in database', [
                    'type' => $type,
                    'filename' => $filename,
                    'user_id' => $user->id
                ]);
                abort(404, 'File not found or access denied');
            }
            
            $filePath = storage_path('app/public/' . $file->file_path);
            
            Log::info('File path constructed', [
                'file_path' => $file->file_path,
                'full_path' => $filePath,
                'exists' => file_exists($filePath)
            ]);
            
            if (!file_exists($filePath)) {
                Log::error('File not found on disk', ['file_path' => $filePath]);
                abort(404, 'File not found on disk');
            }
            
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);
        } catch (\Exception $e) {
            Log::error('Preview file error', [
                'error' => $e->getMessage(),
                'type' => $type,
                'filename' => $filename
            ]);
            abort(500, 'Error previewing file');
        }
    }

    public function downloadFile($type, $filename)
    {
        try {
            $user = Auth::user();
            
            Log::info('Download file request', [
                'type' => $type,
                'filename' => $filename,
                'user_id' => $user->id
            ]);
            
            // Validate type
            if (!in_array($type, ['khs', 'surat-balasan', 'laporan'])) {
                Log::error('Invalid file type', ['type' => $type]);
                abort(404);
            }
            
            // Find the file based on type and user
            $file = null;
            switch ($type) {
                case 'khs':
                    $file = $user->khs()->where('file_path', 'LIKE', '%' . $filename)->first();
                    break;
                case 'surat-balasan':
                    $file = $user->suratBalasan()->where('file_path', 'LIKE', '%' . $filename)->first();
                    break;
                case 'laporan':
                    $file = $user->laporanPkl()->where('file_path', 'LIKE', '%' . $filename)->first();
                    break;
            }
            
            if (!$file) {
                Log::error('File not found in database', [
                    'type' => $type,
                    'filename' => $filename,
                    'user_id' => $user->id
                ]);
                abort(404, 'File not found or access denied');
            }
            
            $filePath = storage_path('app/public/' . $file->file_path);
            
            Log::info('File path constructed', [
                'file_path' => $file->file_path,
                'full_path' => $filePath,
                'exists' => file_exists($filePath)
            ]);
            
            if (!file_exists($filePath)) {
                Log::error('File not found on disk', ['file_path' => $filePath]);
                abort(404, 'File not found on disk');
            }
            
            return response()->download($filePath, basename($filePath), [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . basename($filePath) . '"'
            ]);
        } catch (\Exception $e) {
            Log::error('Download file error', [
                'error' => $e->getMessage(),
                'type' => $type,
                'filename' => $filename
            ]);
            abort(500, 'Error downloading file');
        }
    }

    public function analyzeTranscript(Request $request)
    {
        try {
            $rows = $request->input('table');
            if (!$rows || count($rows) < 2) {
                return response()->json(['error' => 'Data transkrip tidak valid']);
            }

            $header = array_map('strtolower', $rows[0]);
            $idxSks = array_search('sks', $header);
            $idxNilai = array_search('nilai', $header);

            $totalSksD = 0;
            $hasE = false;
            $sumQuality = 0;
            $sumSks = 0;
            $map = [
                'A'  => 4.0,
                'B+' => 3.5,
                'B'  => 3.0,
                'C+' => 2.5,
                'C'  => 2.0,
                'D'  => 1.0,
                'E'  => 0.0
            ];

            for ($i = 1; $i < count($rows); $i++) {
                $r = $rows[$i];
                if (!isset($r[$idxSks]) || !isset($r[$idxNilai])) continue;

                $sks = (int)$r[$idxSks];
                $nilai = strtoupper(trim($r[$idxNilai]));

                if ($nilai === 'D') $totalSksD += $sks;
                if ($nilai === 'E') $hasE = true;

                if (isset($map[$nilai])) {
                    $sumQuality += $map[$nilai] * $sks;
                    $sumSks += $sks;
                }
            }

            $ipk = $sumSks > 0 ? round($sumQuality / $sumSks, 2) : null;
            $eligible = ($ipk !== null && $ipk >= 2.5 && $totalSksD <= 6 && !$hasE);

            return response()->json([
                'ipk' => $ipk,
                'total_sks_d' => $totalSksD,
                'has_e' => $hasE,
                'eligible' => $eligible
            ]);
        } catch (\Exception $e) {
            Log::error('Transcript analysis error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return response()->json(['error' => 'Terjadi kesalahan saat menganalisis transkrip']);
        }
    }

    public function saveTranscript(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Create or update transcript record
            $transcript = \App\Models\Transcript::updateOrCreate(
                ['nim' => $request->nim],
                [
                    'nama_mahasiswa' => $request->nama_mahasiswa,
                    'ipk' => $request->ipk,
                    'total_sks_d' => $request->total_sks_d,
                    'has_e' => $request->has_e,
                    'eligible' => $request->eligible,
                    'semester_data' => $request->semester_data ?? null,
                ]
            );

            Log::info('Transcript saved', [
                'user_id' => $user->id,
                'transcript_id' => $transcript->id,
                'nim' => $request->nim,
                'ipk' => $request->ipk,
                'eligible' => $request->eligible
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Hasil analisis transkrip berhasil disimpan.',
                'data' => $transcript
            ]);
        } catch (\Exception $e) {
            Log::error('Save transcript error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan hasil analisis transkrip: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteKhs($id)
    {
        try {
            $user = Auth::user();
            
            Log::info('Delete KHS request', [
                'id' => $id,
                'user_id' => $user->id,
                'request_method' => request()->method(),
                'request_headers' => request()->headers->all()
            ]);
            
            $khs = Khs::where('id', $id)->where('mahasiswa_id', $user->id)->first();
            
            if (!$khs) {
                Log::error('KHS not found for deletion', [
                    'id' => $id,
                    'user_id' => $user->id
                ]);
                return response()->json(['success' => false, 'message' => 'File tidak ditemukan'], 404);
            }
            
            Log::info('KHS found for deletion', [
                'khs_id' => $khs->id,
                'file_path' => $khs->file_path
            ]);
            
            // Delete file from storage
            if ($khs->file_path) {
                $fullPath = storage_path('app/public/' . $khs->file_path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    Log::info('File deleted from storage', ['file_path' => $fullPath]);
                } else {
                    Log::warning('File not found in storage', ['file_path' => $fullPath]);
                }
            }
            
            
            // Delete record
            $khs->delete();
            
            Log::info('KHS record deleted successfully', ['id' => $id]);
            
            return response()->json(['success' => true, 'message' => 'File KHS berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error deleting KHS: ' . $e->getMessage(), [
                'id' => $id,
                'user_id' => $user->id ?? 'unknown'
            ]);
            return response()->json(['success' => false, 'message' => 'Gagal menghapus file'], 500);
        }
    }

    public function deleteSuratBalasan($id)
    {
        try {
            $user = Auth::user();
            $suratBalasan = SuratBalasan::where('id', $id)->where('mahasiswa_id', $user->id)->first();
            
            if (!$suratBalasan) {
                return response()->json(['success' => false, 'message' => 'File tidak ditemukan'], 404);
            }
            
            // Delete file from storage
            if ($suratBalasan->file_path) {
                $fullPath = storage_path('app/public/' . $suratBalasan->file_path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            
            // Delete record
            $suratBalasan->delete();
            
            return response()->json(['success' => true, 'message' => 'File Surat Balasan berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error deleting Surat Balasan: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus file'], 500);
        }
    }

    public function deleteLaporan($id)
    {
        try {
            $user = Auth::user();
            $laporan = LaporanPkl::where('id', $id)->where('mahasiswa_id', $user->id)->first();
            
            if (!$laporan) {
                return response()->json(['success' => false, 'message' => 'File tidak ditemukan'], 404);
            }
            
            // Delete file from storage
            if ($laporan->file_path) {
                $fullPath = storage_path('app/public/' . $laporan->file_path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            
            // Delete record
            $laporan->delete();
            
            return response()->json(['success' => true, 'message' => 'File Laporan PKL berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error deleting Laporan PKL: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus file'], 500);
        }
    }

    public function saveSemesterData(Request $request)
    {
        try {
            $user = Auth::user();
            
            $request->validate([
                'semester' => 'required|integer|min:1|max:5',
                'transcript_data' => 'required|string',
            ]);

            // Cari atau buat KHS Manual Transkrip record untuk semester ini
            $khsManualTranskrip = KhsManualTranskrip::updateOrCreate(
                [
                    'mahasiswa_id' => $user->id,
                    'semester' => $request->semester,
                ],
                [
                    'transcript_data' => $request->transcript_data, // Hanya simpan data textfield
                ]
            );

            // Log activity
            \App\Models\HistoryAktivitas::create([
                'id_user' => $user->id,
                'id_mahasiswa' => $user->id,
                'tipe' => 'save_transcript_data',
                'pesan' => [
                    'action' => 'save_transcript_data',
                    'semester' => $request->semester,
                    'mahasiswa' => $user->name,
                    'transcript_data_length' => strlen($request->transcript_data),
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data semester ' . $request->semester . ' berhasil disimpan!',
                'khs_manual_transkrip_id' => $khsManualTranskrip->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving semester data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteSemesterData(Request $request)
    {
        try {
            $user = Auth::user();
            
            $request->validate([
                'semester' => 'required|integer|min:1|max:5',
            ]);

            // Hapus data KHS Manual Transkrip untuk semester ini
            $deleted = KhsManualTranskrip::where('mahasiswa_id', $user->id)
                ->where('semester', $request->semester)
                ->delete();

            // Log activity
            \App\Models\HistoryAktivitas::create([
                'id_user' => $user->id,
                'id_mahasiswa' => $user->id,
                'tipe' => 'delete_transcript_data',
                'pesan' => [
                    'action' => 'delete_transcript_data',
                    'semester' => $request->semester,
                    'mahasiswa' => $user->name,
                    'deleted' => $deleted > 0,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data semester ' . $request->semester . ' berhasil dihapus!',
                'deleted_count' => $deleted,
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting semester data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function loadGdriveLinks(Request $request)
    {
        try {
            $user = Auth::user();
            $profile = $user->profilMahasiswa;
            return response()->json([
                'success' => true,
                'profile' => $profile ? [
                    'gdrive_pkkmb' => $profile->gdrive_pkkmb,
                    'gdrive_ecourse' => $profile->gdrive_ecourse,
                    'gdrive_more' => $profile->gdrive_more,
                ] : null
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading Google Drive links: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat link: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveGdriveLinks(Request $request)
    {
        try {
            $user = Auth::user();
            
            $request->validate([
                'gdrive_pkkmb' => 'nullable|url',
                'gdrive_ecourse' => 'nullable|url',
                'gdrive_more' => 'nullable|url',
            ]);

            // Update or create profil mahasiswa
            $profile = ProfilMahasiswa::updateOrCreate(
                ['id_mahasiswa' => $user->id],
                [
                    'gdrive_pkkmb' => $request->gdrive_pkkmb,
                    'gdrive_ecourse' => $request->gdrive_ecourse,
                    'gdrive_more' => $request->gdrive_more,
                ]
            );

            // Log activity
            \App\Models\HistoryAktivitas::create([
                'id_user' => $user->id,
                'id_mahasiswa' => $user->id,
                'tipe' => 'save_gdrive_links',
                'pesan' => [
                    'action' => 'save_gdrive_links',
                    'mahasiswa' => $user->name,
                    'links_saved' => [
                        'pkkmb' => !empty($request->gdrive_pkkmb),
                        'ecourse' => !empty($request->gdrive_ecourse),
                        'more' => !empty($request->gdrive_more),
                    ],
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Link Google Drive berhasil disimpan!',
                'profile_id' => $profile->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving Google Drive links: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan link: ' . $e->getMessage()
            ], 500);
        }
    }

}
