<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Khs;
use App\Models\KhsManualTranskrip;
use App\Models\SuratBalasan;
use App\Models\SuratPengantar;
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
            $khsFiles = $user->khs()->get();
            $khs = $khsFiles->sortByDesc('created_at')->first();
            // Hitung jumlah KHS per semester secara distinct (1..5) agar tidak melebihi 5
            $khsFileCount = $user->khs()
                ->whereBetween('semester', [1, 5])
                ->distinct()
                ->count('semester');
            $khsManualTranskrip = $user->khsManualTranskrip()->get();

            $suratPengantar = \App\Models\SuratPengantar::where('mahasiswa_id', $user->id)->latest()->first();
            $suratBalasan = $user->suratBalasan()->latest()->first();
            $laporanPkl = $user->laporanPkl()->latest()->first();
            $mitra = Mitra::all();

            // Get profil mahasiswa for status_pkl
            $profilMahasiswa = ProfilMahasiswa::where('id_mahasiswa', $user->id)->first();
            $statusPkl = $profilMahasiswa ? $profilMahasiswa->status_pkl : 'siap';
            $hasMitraSelected = $profilMahasiswa && $profilMahasiswa->mitra_selected ? true : false;

            // Check if all requirements are checked
            $requirementsChecked = $profilMahasiswa &&
                $profilMahasiswa->cek_min_semester &&
                $profilMahasiswa->cek_ipk_nilaisks &&
                $profilMahasiswa->cek_valid_biodata;

            return view('documents.index', compact(
                'user',
                'khs',
                'suratPengantar',
                'suratBalasan',
                'laporanPkl',
                'mitra',
                'khsFiles',
                'khsFileCount',
                'khsManualTranskrip',
                'statusPkl',
                'hasMitraSelected',
                'requirementsChecked'
            ));
        }

        // dospem & admin
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
        $semester = (int) $request->input('semester');

        // Hapus KHS lama untuk semester yang sama (jika ada), jangan ganggu semester lain
        $oldKhs = $user->khs()->where('semester', $semester)->first();
        if ($oldKhs) {
            if (!empty($oldKhs->file_path)) {
                Storage::disk('public')->delete($oldKhs->file_path);
            }
            $oldKhs->delete();
        }

        // Simpan file baru ke folder user_id agar rapi: storage/app/public/documents/khs/{user_id}
        $file = $request->file('file');
        $nim  = optional($user->profilMahasiswa)->nim ?? $user->id;
        $nama = preg_replace('/[^A-Za-z0-9_]/', '_', str_replace(' ', '_', $user->name));
        $filename = 'S' . $semester . '_KHS_' . $nama . '_' . $nim . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/khs/' . $user->id, $filename, 'public');

        // Buat record KHS untuk semester ini
        Khs::create([
            'mahasiswa_id'    => $user->id,
            'file_path'       => $path,
            'semester'        => $semester,
            'status_validasi' => 'menunggu',
        ]);

        // Log aktivitas
        \App\Models\HistoryAktivitas::create([
            'id_user'      => $user->id,
            'id_mahasiswa' => $user->id,
            'tipe'         => 'upload_dokumen',
            'pesan'        => [
                'action'        => 'upload_dokumen',
                'document_type' => 'KHS',
                'semester'      => $semester,
                'mahasiswa'     => $user->name,
                'file_name'     => $filename,
            ],
        ]);

        // Setelah upload, halaman index akan menghitung ulang $khsFileCount dan menampilkan x/5
        return redirect()->route('documents.index')->with('success', "KHS Semester {$semester} berhasil diupload!");
    }

    public function uploadKhsMultiple(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:1|max:5',
            'files.*' => 'required|file|mimes:pdf|max:10240', // 10MB max per file
            'semesters' => 'required|array|min:1|max:5',
            'semesters.*' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        $uploadedFiles = [];
        $errors = [];

        foreach ($request->file('files') as $index => $file) {
            try {
                $semester = $request->input('semesters')[$index] ?? ($index + 1);
                
                // Check if KHS already exists for this semester
                $existingKhs = $user->khs()->where('semester', $semester)->first();
                if ($existingKhs) {
                    // Delete old file
                    if (!empty($existingKhs->file_path)) {
                        Storage::disk('public')->delete($existingKhs->file_path);
                    }
                    $existingKhs->delete();
                }

                // Save new file
                $nim = optional($user->profilMahasiswa)->nim ?? $user->id;
                $nama = preg_replace('/[^A-Za-z0-9_]/', '_', str_replace(' ', '_', $user->name));
                $newFilename = 'S' . $semester . '_KHS_' . $nama . '_' . $nim . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('documents/khs/' . $user->id, $newFilename, 'public');

                // Create KHS record
                Khs::create([
                    'mahasiswa_id' => $user->id,
                    'file_path' => $path,
                    'semester' => $semester,
                    'status_validasi' => 'menunggu',
                ]);

                $uploadedFiles[] = [
                    'semester' => $semester,
                    'filename' => $newFilename,
                    'original_name' => $file->getClientOriginalName()
                ];

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
                        'file_name' => $newFilename,
                        'upload_type' => 'multiple'
                    ],
                ]);

            } catch (\Exception $e) {
                $errors[] = "File " . ($index + 1) . ": " . $e->getMessage();
                Log::error('KHS Multiple Upload Error', [
                    'user_id' => $user->id,
                    'file_index' => $index,
                    'error' => $e->getMessage()
                ]);
            }
        }

        if (count($uploadedFiles) > 0) {
            $message = 'Berhasil mengupload ' . count($uploadedFiles) . ' file KHS!';
            if (count($errors) > 0) {
                $message .= ' Beberapa file gagal: ' . implode(', ', $errors);
            }
            return response()->json([
                'success' => true,
                'message' => $message,
                'uploaded_count' => count($uploadedFiles),
                'errors' => $errors
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload file. ' . implode(', ', $errors)
            ], 400);
        }
    }

    public function uploadSuratPengantar(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $user = Auth::user();

        // Delete old surat pengantar if exists
        $oldSurat = \App\Models\SuratPengantar::where('mahasiswa_id', $user->id)->first();
        if ($oldSurat) {
            Storage::disk('public')->delete($oldSurat->file_path);
            $oldSurat->delete();
        }

        $file = $request->file('file');
        $nim = optional($user->profilMahasiswa)->nim ?? $user->id;
        $nama = str_replace(' ', '_', $user->name);
        $filename = 'Surat_Pengantar_' . $nama . '_' . $nim . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/surat_pengantar', $filename, 'public');

        \App\Models\SuratPengantar::create([
            'mahasiswa_id' => $user->id,
            'file_path' => $path,
            'status_validasi' => 'menunggu',
        ]);

        \App\Models\HistoryAktivitas::create([
            'id_user' => $user->id,
            'id_mahasiswa' => $user->id,
            'tipe' => 'upload_dokumen',
            'pesan' => json_encode([
                'action' => 'upload_surat_pengantar',
                'user' => $user->name,
                'mahasiswa' => optional($user->profilMahasiswa)->nim ?? $user->id,
                'filename' => $filename,
                'document_type' => 'surat_pengantar'
            ])
        ]);

        return redirect()->back()->with('success', 'Surat Pengantar berhasil diupload!');
    }

    public function selectMitra(Request $request)
    {
        $request->validate([
            'mitra_id' => 'nullable|exists:mitra,id',
            'jenis_alasan' => 'nullable|in:ditolak,alasan_tertentu,pilihan_pribadi',
            'alasan_lengkap' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();

        // Get profil mahasiswa
        $profilMahasiswa = $user->profilMahasiswa;
        $mitraLamaId = null;

        if ($profilMahasiswa && $profilMahasiswa->mitra_selected) {
            $mitraLamaId = $profilMahasiswa->mitra_selected;
        }

        // Validasi kuota mitra
        if ($request->mitra_id) {
            $mitraBaru = Mitra::find($request->mitra_id);

            // Cek kuota hanya jika mahasiswa memilih mitra baru atau mengganti ke mitra yang berbeda
            if ($mitraLamaId != $request->mitra_id) {
                if ($mitraBaru->isKuotaPenuh()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maaf, kuota mahasiswa untuk mitra ' . $mitraBaru->nama . ' sudah penuh (maksimal ' . $mitraBaru->max_mahasiswa . ' mahasiswa). Silakan pilih mitra lain.'
                    ], 422);
                }
            }

            // Jika ada penggantian mitra (dari mitra lama ke mitra baru)
            if ($mitraLamaId && $mitraLamaId != $request->mitra_id) {
                // Simpan riwayat penggantian
                \App\Models\RiwayatPengantianMitra::create([
                    'mahasiswa_id' => $user->id,
                    'mitra_lama_id' => $mitraLamaId,
                    'mitra_baru_id' => $request->mitra_id,
                    'jenis_alasan' => $request->jenis_alasan ?? 'pilihan_pribadi',
                    'alasan_lengkap' => $request->alasan_lengkap
                ]);
            }
        }

        // Update or create profil mahasiswa with selected mitra
        if ($profilMahasiswa) {
            $profilMahasiswa->update(['mitra_selected' => $request->mitra_id]);
        } else {
            // If no profil mahasiswa exists, create one
            \App\Models\ProfilMahasiswa::create([
                'id_mahasiswa' => $user->id,
                'nim' => $user->id, // fallback
                'mitra_selected' => $request->mitra_id
            ]);
        }

        $message = $request->mitra_id ? 'Instansi mitra berhasil dipilih' : 'Instansi mitra berhasil dihapus';

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function uploadSuratBalasan(Request $request)
    {
        $request->validate([
            'file'              => 'required|file|mimes:pdf|max:10240',
            'mitra_id'          => 'nullable|exists:mitra,id',
            'mitra_nama_custom' => 'nullable|string|max:150',
        ]);

        $user = Auth::user();

        $oldSurat = optional($user->suratBalasan())->latest()->first();
        if ($oldSurat) {
            Storage::disk('public')->delete($oldSurat->file_path);
            $oldSurat->delete();
        }

        $file = $request->file('file');
        $nim = $user->profilMahasiswa->nim ?? $user->id;
        $nama = str_replace(' ', '_', $user->name);
        $filename = 'Surat_Balasan_' . $nama . '_' . $nim . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/surat_balasan', $filename, 'public');

        SuratBalasan::create([
            'mahasiswa_id'     => $user->id,
            'mitra_id'         => $request->mitra_id,
            'mitra_nama_custom'=> $request->mitra_nama_custom,
            'file_path'        => $path,
            'status_validasi'  => 'menunggu',
        ]);

        \App\Models\HistoryAktivitas::create([
            'id_user'      => $user->id,
            'id_mahasiswa' => $user->id,
            'tipe'         => 'upload_dokumen',
            'pesan'        => [
                'action'        => 'upload_dokumen',
                'document_type' => 'Surat Balasan',
                'mahasiswa'     => $user->name,
                'file_name'     => $filename,
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

        $oldLaporan = optional($user->laporanPkl())->latest()->first();
        if ($oldLaporan) {
            Storage::disk('public')->delete($oldLaporan->file_path);
            $oldLaporan->delete();
        }

        $file = $request->file('file');
        $nim = $user->profilMahasiswa->nim ?? $user->id;
        $nama = str_replace(' ', '_', $user->name);
        $filename = 'Laporan_PKL_' . $nama . '_' . $nim . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/laporan_pkl', $filename, 'public');

        LaporanPkl::create([
            'mahasiswa_id'    => $user->id,
            'file_path'       => $path,
            'status_validasi' => 'menunggu',
        ]);

        \App\Models\HistoryAktivitas::create([
            'id_user'      => $user->id,
            'id_mahasiswa' => $user->id,
            'tipe'         => 'upload_dokumen',
            'pesan'        => [
                'action'        => 'upload_dokumen',
                'document_type' => 'Laporan PKL',
                'mahasiswa'     => $user->name,
                'file_name'     => $filename,
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
        $khs->update(['status_validasi' => $request->status_validasi]);

        return redirect()->back()->with('success', 'Status KHS berhasil diupdate!');
    }

    public function updateSuratBalasan(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:menunggu,belum_valid,tervalidasi,revisi',
        ]);

        $surat = SuratBalasan::findOrFail($id);
        $surat->update(['status_validasi' => $request->status_validasi]);

        return redirect()->back()->with('success', 'Status Surat Balasan berhasil diupdate!');
    }

    public function updateLaporan(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:menunggu,belum_valid,tervalidasi,revisi',
        ]);

        $laporan = LaporanPkl::findOrFail($id);
        $laporan->update(['status_validasi' => $request->status_validasi]);

        return redirect()->back()->with('success', 'Status Laporan PKL berhasil diupdate!');
    }

    private function getDocumentsForSupervisor($user)
    {
        if ($user->role === 'dospem') {
            $mahasiswaIds = $user->mahasiswaBimbingan()->pluck('id_mahasiswa');
        } else {
            $mahasiswaIds = \App\Models\User::mahasiswa()->pluck('id');
        }

        return [
            'khs'           => Khs::whereIn('mahasiswa_id', $mahasiswaIds)->with('mahasiswa')->get(),
            'surat_balasan' => SuratBalasan::whereIn('mahasiswa_id', $mahasiswaIds)->with(['mahasiswa', 'mitra'])->get(),
            'laporan_pkl'   => LaporanPkl::whereIn('mahasiswa_id', $mahasiswaIds)->with('mahasiswa')->get(),
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
                'user_id' => $user->id,
                'user_role' => $user->role
            ]);
            
            // Validate type
            if (!in_array($type, ['khs', 'surat-pengantar', 'surat-balasan', 'laporan'])) {
                Log::error('Invalid file type', ['type' => $type]);
                abort(404);
            }

            // Find the file based on type and user
            $file = null;
            if ($user->role === 'admin') {
                // Admin can view any file
                switch ($type) {
                    case 'khs':
                        $file = \App\Models\Khs::where('file_path', 'LIKE', '%' . $filename)->first();
                        break;
                    case 'surat-pengantar':
                        $file = \App\Models\SuratPengantar::where('file_path', 'LIKE', '%' . $filename)->first();
                        break;
                    case 'surat-balasan':
                        $file = \App\Models\SuratBalasan::where('file_path', 'LIKE', '%' . $filename)->first();
                        break;
                    case 'laporan':
                        $file = \App\Models\LaporanPkl::where('file_path', 'LIKE', '%' . $filename)->first();
                        break;
                }
            } else {
                // Mahasiswa can only view their own files
                switch ($type) {
                    case 'khs':
                        $file = $user->khs()->where('file_path', 'LIKE', '%' . $filename)->first();
                        break;
                    case 'surat-pengantar':
                        $file = \App\Models\SuratPengantar::where('mahasiswa_id', $user->id)
                            ->where('file_path', 'LIKE', '%' . $filename)
                            ->first();
                        break;
                    case 'surat-balasan':
                        $file = $user->suratBalasan()->where('file_path', 'LIKE', '%' . $filename)->first();
                        break;
                    case 'laporan':
                        $file = $user->laporanPkl()->where('file_path', 'LIKE', '%' . $filename)->first();
                        break;
                }
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
            if (!in_array($type, ['khs', 'surat-pengantar', 'surat-balasan', 'laporan'])) {
                Log::error('Invalid file type', ['type' => $type]);
                abort(404);
            }

            // Find the file based on type and user
            $file = null;
            switch ($type) {
                case 'khs':
                    $file = $user->khs()->where('file_path', 'LIKE', '%' . $filename)->first();
                    break;
                case 'surat-pengantar':
                    $file = \App\Models\SuratPengantar::where('mahasiswa_id', $user->id)
                        ->where('file_path', 'LIKE', '%' . $filename)
                        ->first();
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

    public function deleteSuratPengantar($id)
    {
        try {
            $user = Auth::user();
            $suratPengantar = \App\Models\SuratPengantar::where('id', $id)->where('mahasiswa_id', $user->id)->first();

            if (!$suratPengantar) {
                return response()->json(['success' => false, 'message' => 'File tidak ditemukan'], 404);
            }

            // Delete file from storage
            if ($suratPengantar->file_path) {
                $fullPath = storage_path('app/public/' . $suratPengantar->file_path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            // Delete record
            $suratPengantar->delete();

            return response()->json(['success' => true, 'message' => 'Surat Pengantar berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error deleting Surat Pengantar: ' . $e->getMessage());
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
                'ips' => 'nullable|numeric|min:0|max:4',
                'total_sks' => 'nullable|integer|min:0',
                'total_sks_d' => 'nullable|integer|min:0',
                'has_e' => 'nullable|boolean',
                'eligible' => 'nullable|boolean',
            ]);

            // Cari atau buat KHS Manual Transkrip record untuk semester ini
            $khsManualTranskrip = KhsManualTranskrip::updateOrCreate(
                [
                    'mahasiswa_id' => $user->id,
                    'semester' => $request->semester,
                ],
                [
                    'transcript_data' => $request->transcript_data,
                    'ips' => $request->ips,
                    'total_sks' => $request->total_sks,
                    'total_sks_d' => $request->total_sks_d ?? 0,
                    'has_e' => $request->has_e ?? false,
                    'eligible' => $request->eligible ?? false,
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

    public function activatePklStatus(Request $request)
    {
        try {
            $user = Auth::user();

            // Verify that all requirements are met
            $hasSuratPengantar = SuratPengantar::where('mahasiswa_id', $user->id)->exists();
            $hasMitraSelected = ProfilMahasiswa::where('id_mahasiswa', $user->id)
                ->whereNotNull('mitra_selected')
                ->exists();
            $hasSuratBalasan = SuratBalasan::where('mahasiswa_id', $user->id)->exists();

            if (!$hasSuratPengantar || !$hasMitraSelected || !$hasSuratBalasan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua dokumen (Surat Pengantar, Instansi Mitra, dan Surat Balasan) harus lengkap terlebih dahulu.'
                ], 400);
            }

            // Update status PKL to aktif
            $profile = ProfilMahasiswa::where('id_mahasiswa', $user->id)->first();
            if ($profile) {
                $profile->status_pkl = 'aktif';
                $profile->save();
            }

            // Log activity
            \App\Models\HistoryAktivitas::create([
                'id_user' => $user->id,
                'id_mahasiswa' => $user->id,
                'tipe' => 'activate_pkl_status',
                'pesan' => [
                    'action' => 'activate_pkl_status',
                    'mahasiswa' => $user->name,
                    'new_status' => 'aktif',
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status PKL berhasil diaktifkan!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error activating PKL status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan status PKL: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deactivatePklStatus(Request $request)
    {
        try {
            $user = Auth::user();

            // Update status PKL back to siap
            $profile = ProfilMahasiswa::where('id_mahasiswa', $user->id)->first();
            if ($profile) {
                $profile->status_pkl = 'siap';
                $profile->save();
            }

            // Log activity
            \App\Models\HistoryAktivitas::create([
                'id_user' => $user->id,
                'id_mahasiswa' => $user->id,
                'tipe' => 'deactivate_pkl_status',
                'pesan' => [
                    'action' => 'deactivate_pkl_status',
                    'mahasiswa' => $user->name,
                    'new_status' => 'siap',
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status PKL berhasil dihentikan!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deactivating PKL status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghentikan status PKL: ' . $e->getMessage()
            ], 500);
        }
    }

    public function completePklStatus(Request $request)
    {
        try {
            $user = Auth::user();

            // Update status PKL to selesai
            $profile = ProfilMahasiswa::where('id_mahasiswa', $user->id)->first();
            if ($profile) {
                $profile->status_pkl = 'selesai';
                $profile->save();
            }

            // Log activity
            \App\Models\HistoryAktivitas::create([
                'id_user' => $user->id,
                'id_mahasiswa' => $user->id,
                'tipe' => 'complete_pkl_status',
                'pesan' => [
                    'action' => 'complete_pkl_status',
                    'mahasiswa' => $user->name,
                    'new_status' => 'selesai',
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Selamat! Anda telah menyelesaikan PKL.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error completing PKL status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan PKL: ' . $e->getMessage()
            ], 500);
        }
    }

    public function revertPklStatus(Request $request)
    {
        try {
            $user = Auth::user();

            // Update status PKL back to aktif
            $profile = ProfilMahasiswa::where('id_mahasiswa', $user->id)->first();
            if ($profile) {
                $profile->status_pkl = 'aktif';
                $profile->save();
            }

            // Log activity
            \App\Models\HistoryAktivitas::create([
                'id_user' => $user->id,
                'id_mahasiswa' => $user->id,
                'tipe' => 'revert_pkl_status',
                'pesan' => [
                    'action' => 'revert_pkl_status',
                    'mahasiswa' => $user->name,
                    'new_status' => 'aktif',
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status PKL berhasil dikembalikan ke Aktif PKL!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error reverting PKL status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status PKL: ' . $e->getMessage()
            ], 500);
        }
    }

}
