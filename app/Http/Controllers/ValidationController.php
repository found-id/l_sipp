<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Khs;
use App\Models\SuratBalasan;
use App\Models\LaporanPkl;
use App\Models\HistoryAktivitas;
use App\Services\FonnteService;

class ValidationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if user is admin or dosen pembimbing
        if ($user->role === 'admin') {
            // Admin can see all mahasiswa documents
            $documents = [
                'khs' => Khs::with(['mahasiswa.profilMahasiswa.dosenPembimbing'])
                            ->orderBy('created_at', 'desc')
                            ->get(),
                'surat_balasan' => SuratBalasan::with(['mahasiswa.profilMahasiswa.dosenPembimbing', 'mitra'])
                                              ->orderBy('created_at', 'desc')
                                              ->get(),
                'laporan_pkl' => LaporanPkl::with(['mahasiswa.profilMahasiswa.dosenPembimbing'])
                                          ->orderBy('created_at', 'desc')
                                          ->get(),
            ];
        } else {
            // Dosen pembimbing can only see their bimbingan
            $mahasiswaIds = \App\Models\ProfilMahasiswa::where('id_dospem', $user->id)
                ->pluck('id_mahasiswa')
                ->toArray();

            $documents = [
                'khs' => Khs::whereIn('mahasiswa_id', $mahasiswaIds)
                            ->with('mahasiswa.profilMahasiswa')
                            ->orderBy('created_at', 'desc')
                            ->get(),
                'surat_balasan' => SuratBalasan::whereIn('mahasiswa_id', $mahasiswaIds)
                                              ->with(['mahasiswa.profilMahasiswa', 'mitra'])
                                              ->orderBy('created_at', 'desc')
                                              ->get(),
                'laporan_pkl' => LaporanPkl::whereIn('mahasiswa_id', $mahasiswaIds)
                                          ->with('mahasiswa.profilMahasiswa')
                                          ->orderBy('created_at', 'desc')
                                          ->get(),
            ];
        }

        // Statistics
        $stats = [
            'total_documents' => $documents['khs']->count()
                + $documents['surat_balasan']->count()
                + $documents['laporan_pkl']->count(),
            'pending_validation' => $documents['khs']->where('status_validasi', 'menunggu')->count()
                + $documents['surat_balasan']->where('status_validasi', 'menunggu')->count()
                + $documents['laporan_pkl']->where('status_validasi', 'menunggu')->count(),
            'validated' => $documents['khs']->where('status_validasi', 'tervalidasi')->count()
                + $documents['surat_balasan']->where('status_validasi', 'tervalidasi')->count()
                + $documents['laporan_pkl']->where('status_validasi', 'tervalidasi')->count(),
            'need_revision' => $documents['khs']->where('status_validasi', 'revisi')->count()
                + $documents['surat_balasan']->where('status_validasi', 'revisi')->count()
                + $documents['laporan_pkl']->where('status_validasi', 'revisi')->count(),
        ];

        return view('validation.index', compact('documents', 'stats'));
    }

    public function validateKhs(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:tervalidasi,belum_valid,revisi',
            'catatan' => 'nullable|string|max:500',
        ]);

        $khs = Khs::with('mahasiswa.profilMahasiswa')->findOrFail($id);
        $oldStatus = $khs->status_validasi;

        $khs->update([
            'status_validasi' => $request->status_validasi,
        ]);

        // Log activity
        $this->logValidationActivity('khs', $khs->mahasiswa_id, $oldStatus, $request->status_validasi, $request->catatan);

        // Send WhatsApp notification
        Log::info('About to send validation notification for KHS', [
            'khs_id' => $khs->id,
            'mahasiswa_id' => $khs->mahasiswa_id,
            'mahasiswa_name' => $khs->mahasiswa->name ?? 'N/A',
            'whatsapp_number' => optional($khs->mahasiswa->profilMahasiswa)->no_whatsapp ?? 'N/A',
            'status' => $request->status_validasi
        ]);

        $this->sendValidationNotification($khs->mahasiswa, 'KHS', $request->status_validasi, $request->catatan);

        return redirect()->back()->with('success', 'Status KHS berhasil diupdate!');
    }

    public function validateSuratBalasan(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:tervalidasi,belum_valid,revisi',
            'catatan' => 'nullable|string|max:500',
        ]);

        $surat = SuratBalasan::with('mahasiswa.profilMahasiswa')->findOrFail($id);
        $oldStatus = $surat->status_validasi;

        $surat->update([
            'status_validasi' => $request->status_validasi,
        ]);

        // Log activity
        $this->logValidationActivity('surat_balasan', $surat->mahasiswa_id, $oldStatus, $request->status_validasi, $request->catatan);

        // Send WhatsApp notification
        Log::info('About to send validation notification for Surat Balasan', [
            'surat_id' => $surat->id,
            'mahasiswa_id' => $surat->mahasiswa_id,
            'mahasiswa_name' => $surat->mahasiswa->name ?? 'N/A',
            'whatsapp_number' => optional($surat->mahasiswa->profilMahasiswa)->no_whatsapp ?? 'N/A',
            'status' => $request->status_validasi
        ]);

        $this->sendValidationNotification($surat->mahasiswa, 'Surat Balasan', $request->status_validasi, $request->catatan);

        return redirect()->back()->with('success', 'Status Surat Balasan berhasil diupdate!');
    }

    public function validateLaporan(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:tervalidasi,belum_valid,revisi',
            'catatan' => 'nullable|string|max:500',
        ]);

        $laporan = LaporanPkl::with('mahasiswa.profilMahasiswa')->findOrFail($id);
        $oldStatus = $laporan->status_validasi;

        $laporan->update([
            'status_validasi' => $request->status_validasi,
        ]);

        // Log activity
        $this->logValidationActivity('laporan_pkl', $laporan->mahasiswa_id, $oldStatus, $request->status_validasi, $request->catatan);

        // Send WhatsApp notification
        Log::info('About to send validation notification for Laporan PKL', [
            'laporan_id' => $laporan->id,
            'mahasiswa_id' => $laporan->mahasiswa_id,
            'mahasiswa_name' => $laporan->mahasiswa->name ?? 'N/A',
            'whatsapp_number' => optional($laporan->mahasiswa->profilMahasiswa)->no_whatsapp ?? 'N/A',
            'status' => $request->status_validasi
        ]);

        $this->sendValidationNotification($laporan->mahasiswa, 'Laporan PKL', $request->status_validasi, $request->catatan);

        return redirect()->back()->with('success', 'Status Laporan PKL berhasil diupdate!');
    }

    public function bulkValidate(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:khs,surat_balasan,laporan_pkl',
            'document_ids' => 'required|array',
            'document_ids.*' => 'integer',
            'status_validasi' => 'required|in:tervalidasi,belum_valid,revisi',
            'catatan' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $mahasiswaIds = \App\Models\ProfilMahasiswa::where('id_dospem', $user->id)
            ->pluck('id_mahasiswa')
            ->toArray();

        $updated = 0;

        foreach ($request->document_ids as $documentId) {
            if ($request->document_type === 'khs') {
                $document = Khs::whereIn('mahasiswa_id', $mahasiswaIds)->find($documentId);
            } elseif ($request->document_type === 'surat_balasan') {
                $document = SuratBalasan::whereIn('mahasiswa_id', $mahasiswaIds)->find($documentId);
            } else {
                $document = LaporanPkl::whereIn('mahasiswa_id', $mahasiswaIds)->find($documentId);
            }

            if ($document) {
                $oldStatus = $document->status_validasi;
                $document->update(['status_validasi' => $request->status_validasi]);

                // Log activity
                $this->logValidationActivity($request->document_type, $document->mahasiswa_id, $oldStatus, $request->status_validasi, $request->catatan);
                $updated++;
            }
        }

        return redirect()->back()->with('success', "Berhasil mengupdate {$updated} dokumen!");
    }

    private function logValidationActivity($documentType, $mahasiswaId, $oldStatus, $newStatus, $catatan = null)
    {
        $user = Auth::user();
        $mahasiswa = User::find($mahasiswaId);

        $pesan = [
            'action' => 'validasi_dokumen',
            'document_type' => $documentType,
            'mahasiswa' => $mahasiswa->name,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'catatan' => $catatan,
        ];

        HistoryAktivitas::create([
            'id_user' => $user->id,
            'id_mahasiswa' => $mahasiswaId,
            'tipe' => 'validasi_dokumen',
            'pesan' => $pesan,
        ]);
    }

    public function getBiodata($id)
    {
        $mahasiswa = User::with(['profilMahasiswa.dosenPembimbing'])->findOrFail($id);

        $biodata = [
            'name' => $mahasiswa->name,
            'nim' => optional($mahasiswa->profilMahasiswa)->nim ?? 'N/A',
            'prodi' => optional($mahasiswa->profilMahasiswa)->prodi ?? 'N/A',
            'semester' => optional($mahasiswa->profilMahasiswa)->semester ?? 'N/A',
            'dospem' => optional(optional($mahasiswa->profilMahasiswa)->dosenPembimbing)->name ?? 'Belum ditentukan'
        ];

        return response()->json(['success' => true, 'biodata' => $biodata]);
    }

    private function sendValidationNotification($mahasiswa, $documentType, $status, $notes = null)
    {
        try {
            Log::info('sendValidationNotification called', [
                'mahasiswa_id' => $mahasiswa->id,
                'mahasiswa_name' => $mahasiswa->name,
                'document_type' => $documentType,
                'status' => $status,
                'notes' => $notes
            ]);

            // Ambil nomor WA dari profil mahasiswa
            $whatsappNumber = optional($mahasiswa->profilMahasiswa)->no_whatsapp;

            Log::info('WhatsApp number check', [
                'mahasiswa_id' => $mahasiswa->id,
                'whatsapp_number' => $whatsappNumber,
                'has_profil' => $mahasiswa->profilMahasiswa ? 'yes' : 'no'
            ]);

            if ($whatsappNumber) {
                $fonnte = new FonnteService();

                // Buat pesan
                $statusText = $status === 'tervalidasi'
                    ? '✅ Tervalidasi'
                    : ($status === 'belum_valid' ? '❌ Belum Valid' : '⚠️ Perlu Revisi');

                $message  = "📄 *Notifikasi Validasi Dokumen*\n\n";
                $message .= "Halo *{$mahasiswa->name}*,\n\n";
                $message .= "Dokumen *{$documentType}* Anda telah divalidasi dengan status: *{$statusText}*\n\n";

                if ($notes) {
                    $message .= "📝 *Catatan:*\n{$notes}\n\n";
                }

                $message .= "Silakan cek dashboard untuk detail lebih lanjut.\n\nTerima kasih! 🙏";

                // Kirim (biarkan FonnteService yang normalisasi nomor ke +62)
                $result = $fonnte->sendMessage($whatsappNumber, $message);

                Log::info('WhatsApp validation notification sent', [
                    'mahasiswa_id' => $mahasiswa->id,
                    'phone' => $whatsappNumber,
                    'document_type' => $documentType,
                    'status' => $status,
                    'result' => $result
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp validation notification', [
                'mahasiswa_id' => $mahasiswa->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display list of mahasiswa for dospem to select
     */
    public function mahasiswaList(Request $request)
    {
        $user = Auth::user();

        $query = \App\Models\ProfilMahasiswa::with([
            'user',
            'user.khs',
            'user.suratBalasan',
            'user.laporanPkl',
            'dosenPembimbing'
        ]);

        if ($user->role === 'admin') {
            // Admin can see all mahasiswa
        } else {
            // Dospem can only see their bimbingan
            $query->where('id_dospem', $user->id);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('nim', 'like', '%' . $search . '%')
                ->orWhere('prodi', 'like', '%' . $search . '%');
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        switch($sortBy) {
            case 'name':
                $query->join('users', 'profil_mahasiswa.id_mahasiswa', '=', 'users.id')
                      ->orderBy('users.name', $sortOrder)
                      ->select('profil_mahasiswa.*');
                break;
            case 'prodi':
                $query->orderBy('prodi', $sortOrder);
                break;
            case 'semester':
                $query->orderBy('semester', $sortOrder);
                break;
            case 'ipk':
                // IPK: jika asc, tampilkan dari tertinggi (desc), jika desc tampilkan dari terendah (asc)
                $ipkOrder = $sortOrder === 'asc' ? 'desc' : 'asc';
                $query->orderBy('ipk', $ipkOrder);
                break;
            case 'nim':
                // NIM: tetap gunakan order normal (asc = dari terkecil, desc = dari terbesar)
                $query->orderBy('nim', $sortOrder);
                break;
            default:
                $query->join('users', 'profil_mahasiswa.id_mahasiswa', '=', 'users.id')
                      ->orderBy('users.name', $sortOrder)
                      ->select('profil_mahasiswa.*');
        }

        $mahasiswa = $query->get();

        return view('validation.mahasiswa-list', compact('mahasiswa'));
    }

    /**
     * Display detailed pemberkasan for a specific mahasiswa
     */
    public function mahasiswaDetail($id)
    {
        $user = Auth::user();

        // Find the mahasiswa
        $mahasiswa = User::with([
            'profilMahasiswa.dosenPembimbing',
            'profilMahasiswa.mitraSelected',
            'profilMahasiswa.riwayatPengantianMitra.mitraLama',
            'profilMahasiswa.riwayatPengantianMitra.mitraBaru',
            'khs' => function($q) { $q->orderBy('semester'); },
            'khsManualTranskrip' => function($q) { $q->orderBy('semester'); },
            'suratBalasan.mitra',
            'laporanPkl'
        ])->findOrFail($id);

        // Load surat pengantar
        $suratPengantar = \App\Models\SuratPengantar::where('mahasiswa_id', $id)->first();

        // Check authorization
        if ($user->role === 'dospem') {
            // Verify this mahasiswa is under this dospem
            if (!$mahasiswa->profilMahasiswa || $mahasiswa->profilMahasiswa->id_dospem !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
            }
        }

        // Calculate academic summary
        $khsManual = $mahasiswa->khsManualTranskrip;
        $totalSemesters = $khsManual->count();

        // Calculate IPK from transcript_data
        $totalIps = 0;
        $semesterCount = 0;
        $totalSksDCount = 0;
        $totalSksECount = 0;

        foreach ($khsManual as $transkrip) {
            $data = is_string($transkrip->transcript_data)
                ? json_decode($transkrip->transcript_data, true)
                : $transkrip->transcript_data;

            if (!empty($data)) {
                $semesterIps = 0;
                $semesterSks = 0;
                $hasDGrade = false;
                $hasEGrade = false;

                foreach ($data as $matkul) {
                    $nilai = strtoupper(trim($matkul['nilai'] ?? ''));
                    $sks = floatval($matkul['sks'] ?? 0);

                    // Convert nilai to angka mutu
                    $angkaMutu = 0;
                    switch ($nilai) {
                        case 'A': $angkaMutu = 4.0; break;
                        case 'AB': $angkaMutu = 3.5; break;
                        case 'B': $angkaMutu = 3.0; break;
                        case 'BC': $angkaMutu = 2.5; break;
                        case 'C': $angkaMutu = 2.0; break;
                        case 'D':
                            $angkaMutu = 1.0;
                            $hasDGrade = true;
                            $totalSksDCount += $sks;
                            break;
                        case 'E':
                            $angkaMutu = 0.0;
                            $hasEGrade = true;
                            break;
                    }

                    $semesterIps += ($angkaMutu * $sks);
                    $semesterSks += $sks;
                }

                if ($semesterSks > 0) {
                    $totalIps += ($semesterIps / $semesterSks);
                    $semesterCount++;
                }

                if ($hasEGrade) {
                    $totalSksECount++;
                }
            }
        }

        $ipkFromTranskrip = $semesterCount > 0 ? round($totalIps / $semesterCount, 2) : 0;
        $ipkFromProfile = $mahasiswa->profilMahasiswa->ipk ?? 0;
        $allEligible = true; // Default karena tidak ada kolom eligible lagi

        // Count rejected from mitra (ditolak)
        $jumlahDitolak = \App\Models\RiwayatPengantianMitra::where('mahasiswa_id', $id)
            ->where('jenis_alasan', 'ditolak')
            ->count();

        // KHS files count
        $khsFilesCount = $mahasiswa->khs->count();

        // Get Google Drive links from profil
        $gdrive = [
            'pkkmb' => $mahasiswa->profilMahasiswa->gdrive_pkkmb ?? null,
            'ecourse' => $mahasiswa->profilMahasiswa->gdrive_ecourse ?? null,
            'more' => $mahasiswa->profilMahasiswa->gdrive_more ?? null,
        ];

        // Get validation status for each category
        $validationStatus = [
            'kelayakan' => $this->getKelayakanStatus($mahasiswa),
            'dokumen_pendukung' => $this->getDokumenPendukungStatus($mahasiswa, $gdrive),
            'instansi_mitra' => $this->getInstansiMitraStatus($mahasiswa),
            'akhir' => $this->getAkhirStatus($mahasiswa),
        ];

        // Status PKL (based on all documents completion)
        $statusPKL = $this->calculateStatusPKL($mahasiswa, $gdrive);

        return view('validation.mahasiswa-detail', compact(
            'mahasiswa',
            'ipkFromTranskrip',
            'ipkFromProfile',
            'totalSksDCount',
            'totalSksECount',
            'allEligible',
            'totalSemesters',
            'khsFilesCount',
            'gdrive',
            'validationStatus',
            'statusPKL',
            'jumlahDitolak',
            'suratPengantar'
        ));
    }

    /**
     * Preview file mahasiswa untuk dospem
     */
    public function previewMahasiswaFile($mahasiswaId, $type, $filename)
    {
        try {
            $user = Auth::user();

            // Verify authorization
            if ($user->role === 'dospem') {
                $mahasiswa = User::whereHas('profilMahasiswa', function($q) use ($user) {
                    $q->where('id_dospem', $user->id);
                })->findOrFail($mahasiswaId);
            } elseif ($user->role === 'admin') {
                $mahasiswa = User::findOrFail($mahasiswaId);
            } else {
                abort(403);
            }

            // Validate type
            if (!in_array($type, ['khs', 'surat-pengantar', 'surat-balasan', 'laporan'])) {
                abort(404);
            }

            // Find the file
            $file = null;
            switch ($type) {
                case 'khs':
                    $file = $mahasiswa->khs()->where('file_path', 'LIKE', '%' . $filename)->first();
                    break;
                case 'surat-pengantar':
                    $file = \App\Models\SuratPengantar::where('mahasiswa_id', $mahasiswaId)
                        ->where('file_path', 'LIKE', '%' . $filename)
                        ->first();
                    break;
                case 'surat-balasan':
                    $file = $mahasiswa->suratBalasan()->where('file_path', 'LIKE', '%' . $filename)->first();
                    break;
                case 'laporan':
                    $file = $mahasiswa->laporanPkl()->where('file_path', 'LIKE', '%' . $filename)->first();
                    break;
            }

            if (!$file) {
                abort(404, 'File not found');
            }

            $filePath = storage_path('app/public/' . $file->file_path);

            if (!file_exists($filePath)) {
                abort(404, 'File not found on disk');
            }

            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);
        } catch (\Exception $e) {
            \Log::error('Preview mahasiswa file error', [
                'error' => $e->getMessage(),
                'mahasiswa_id' => $mahasiswaId,
                'type' => $type,
                'filename' => $filename
            ]);
            abort(500, 'Error previewing file');
        }
    }

    /**
     * Get validation status for Pemberkasan Kelayakan
     */
    private function getKelayakanStatus($mahasiswa)
    {
        $khsFiles = $mahasiswa->khs;

        if ($khsFiles->isEmpty()) {
            return ['status' => 'belum_upload', 'label' => 'Belum Upload', 'color' => 'gray'];
        }

        $tervalidasi = $khsFiles->where('status_validasi', 'tervalidasi')->count();
        $total = $khsFiles->count();

        if ($tervalidasi === $total) {
            return ['status' => 'tervalidasi', 'label' => 'Tervalidasi', 'color' => 'green'];
        } elseif ($khsFiles->where('status_validasi', 'revisi')->count() > 0) {
            return ['status' => 'revisi', 'label' => 'Perlu Revisi', 'color' => 'orange'];
        } elseif ($khsFiles->where('status_validasi', 'belum_valid')->count() > 0) {
            return ['status' => 'belum_valid', 'label' => 'Belum Valid', 'color' => 'red'];
        } else {
            return ['status' => 'menunggu', 'label' => 'Menunggu Validasi', 'color' => 'yellow'];
        }
    }

    /**
     * Get validation status for Pemberkasan Dokumen Pendukung
     */
    private function getDokumenPendukungStatus($mahasiswa, $gdrive)
    {
        $hasAll = $gdrive['pkkmb'] && $gdrive['ecourse'] && $gdrive['more'];
        $hasAny = $gdrive['pkkmb'] || $gdrive['ecourse'] || $gdrive['more'];

        if (!$hasAny) {
            return ['status' => 'belum_upload', 'label' => 'Belum Upload', 'color' => 'gray'];
        }

        // Check validation status from database
        $statusValidasi = $mahasiswa->profilMahasiswa->status_dokumen_pendukung ?? 'menunggu';

        if ($statusValidasi === 'tervalidasi') {
            return ['status' => 'tervalidasi', 'label' => 'Tervalidasi', 'color' => 'green'];
        } elseif ($statusValidasi === 'revisi') {
            return ['status' => 'revisi', 'label' => 'Perlu Revisi', 'color' => 'orange'];
        } elseif ($statusValidasi === 'belum_valid') {
            return ['status' => 'belum_valid', 'label' => 'Belum Valid', 'color' => 'red'];
        } elseif ($hasAll) {
            return ['status' => 'menunggu', 'label' => 'Menunggu Validasi', 'color' => 'yellow'];
        } else {
            return ['status' => 'sebagian', 'label' => 'Sebagian', 'color' => 'yellow'];
        }
    }

    /**
     * Get validation status for Pemberkasan Instansi Mitra
     */
    private function getInstansiMitraStatus($mahasiswa)
    {
        $suratBalasan = $mahasiswa->suratBalasan;

        if ($suratBalasan->isEmpty()) {
            return ['status' => 'belum_upload', 'label' => 'Belum Upload', 'color' => 'gray'];
        }

        $surat = $suratBalasan->first();

        if ($surat->status_validasi === 'tervalidasi') {
            return ['status' => 'tervalidasi', 'label' => 'Tervalidasi', 'color' => 'green'];
        } elseif ($surat->status_validasi === 'revisi') {
            return ['status' => 'revisi', 'label' => 'Perlu Revisi', 'color' => 'orange'];
        } elseif ($surat->status_validasi === 'belum_valid') {
            return ['status' => 'belum_valid', 'label' => 'Belum Valid', 'color' => 'red'];
        } else {
            return ['status' => 'menunggu', 'label' => 'Menunggu Validasi', 'color' => 'yellow'];
        }
    }

    /**
     * Get validation status for Pemberkasan Akhir
     */
    private function getAkhirStatus($mahasiswa)
    {
        $laporanPkl = $mahasiswa->laporanPkl;

        if ($laporanPkl->isEmpty()) {
            return ['status' => 'belum_upload', 'label' => 'Belum Upload', 'color' => 'gray'];
        }

        $laporan = $laporanPkl->first();

        if ($laporan->status_validasi === 'tervalidasi') {
            return ['status' => 'tervalidasi', 'label' => 'Tervalidasi', 'color' => 'green'];
        } elseif ($laporan->status_validasi === 'revisi') {
            return ['status' => 'revisi', 'label' => 'Perlu Revisi', 'color' => 'orange'];
        } elseif ($laporan->status_validasi === 'belum_valid') {
            return ['status' => 'belum_valid', 'label' => 'Belum Valid', 'color' => 'red'];
        } else {
            return ['status' => 'menunggu', 'label' => 'Menunggu Validasi', 'color' => 'yellow'];
        }
    }

    /**
     * Calculate overall PKL status
     */
    private function calculateStatusPKL($mahasiswa, $gdrive)
    {
        $khsComplete = $mahasiswa->khs->count() >= 5 &&
                      $mahasiswa->khs->where('status_validasi', 'tervalidasi')->count() >= 5;
        $gdriveComplete = $gdrive['pkkmb'] && $gdrive['ecourse'] && $gdrive['more'];
        $suratComplete = $mahasiswa->suratBalasan->isNotEmpty() &&
                        $mahasiswa->suratBalasan->first()->status_validasi === 'tervalidasi';
        $laporanComplete = $mahasiswa->laporanPkl->isNotEmpty() &&
                          $mahasiswa->laporanPkl->first()->status_validasi === 'tervalidasi';

        if ($khsComplete && $gdriveComplete && $suratComplete && $laporanComplete) {
            return 'Lengkap';
        } else {
            return 'Belum Lengkap';
        }
    }

    /**
     * Validate Pemberkasan Kelayakan (KHS Files + Manual Transkrip)
     */
    public function validateKelayakan(Request $request, $mahasiswaId)
    {
        $request->validate([
            'status_validasi' => 'required|in:tervalidasi,belum_valid,revisi',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $mahasiswa = User::findOrFail($mahasiswaId);

        // Check authorization
        if ($user->role === 'dospem') {
            if (!$mahasiswa->profilMahasiswa || $mahasiswa->profilMahasiswa->id_dospem !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
            }
        }

        // Update all KHS files status
        \App\Models\Khs::where('mahasiswa_id', $mahasiswaId)->update([
            'status_validasi' => $request->status_validasi,
        ]);

        // Log activity
        $this->logValidationActivity(
            'pemberkasan_kelayakan',
            $mahasiswaId,
            'menunggu',
            $request->status_validasi,
            $request->catatan
        );

        // Send WhatsApp notification
        $this->sendValidationNotification(
            $mahasiswa,
            'Pemberkasan Kelayakan (KHS)',
            $request->status_validasi,
            $request->catatan
        );

        return redirect()->back()->with('success', 'Pemberkasan Kelayakan berhasil divalidasi!');
    }

    /**
     * Validate Pemberkasan Dokumen Pendukung (Google Drive Links)
     */
    public function validateDokumenPendukung(Request $request, $mahasiswaId)
    {
        $request->validate([
            'status_validasi' => 'required|in:tervalidasi,belum_valid,revisi',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $mahasiswa = User::findOrFail($mahasiswaId);

        // Check authorization
        if ($user->role === 'dospem') {
            if (!$mahasiswa->profilMahasiswa || $mahasiswa->profilMahasiswa->id_dospem !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
            }
        }

        // Update profil mahasiswa with validation status
        if ($mahasiswa->profilMahasiswa) {
            $mahasiswa->profilMahasiswa->status_dokumen_pendukung = $request->status_validasi;
            $mahasiswa->profilMahasiswa->save();
        }

        // Log activity
        $this->logValidationActivity(
            'pemberkasan_dokumen_pendukung',
            $mahasiswaId,
            'menunggu',
            $request->status_validasi,
            $request->catatan
        );

        // Send WhatsApp notification
        $this->sendValidationNotification(
            $mahasiswa,
            'Pemberkasan Dokumen Pendukung (Sertifikat)',
            $request->status_validasi,
            $request->catatan
        );

        return redirect()->back()->with('success', 'Pemberkasan Dokumen Pendukung berhasil divalidasi!');
    }

    /**
     * Validate Pemberkasan Instansi Mitra (Surat Balasan)
     */
    public function validateInstansiMitra(Request $request, $mahasiswaId)
    {
        $request->validate([
            'status_validasi' => 'required|in:tervalidasi,belum_valid,revisi',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $mahasiswa = User::findOrFail($mahasiswaId);

        // Check authorization
        if ($user->role === 'dospem') {
            if (!$mahasiswa->profilMahasiswa || $mahasiswa->profilMahasiswa->id_dospem !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
            }
        }

        // Update surat balasan status
        \App\Models\SuratBalasan::where('mahasiswa_id', $mahasiswaId)->update([
            'status_validasi' => $request->status_validasi,
        ]);

        // Log activity
        $this->logValidationActivity(
            'pemberkasan_instansi_mitra',
            $mahasiswaId,
            'menunggu',
            $request->status_validasi,
            $request->catatan
        );

        // Send WhatsApp notification
        $this->sendValidationNotification(
            $mahasiswa,
            'Pemberkasan Instansi Mitra (Surat Balasan)',
            $request->status_validasi,
            $request->catatan
        );

        return redirect()->back()->with('success', 'Pemberkasan Instansi Mitra berhasil divalidasi!');
    }

    /**
     * Validate Pemberkasan Akhir (Laporan PKL)
     */
    public function validateAkhir(Request $request, $mahasiswaId)
    {
        $request->validate([
            'status_validasi' => 'required|in:tervalidasi,belum_valid,revisi',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $mahasiswa = User::findOrFail($mahasiswaId);

        // Check authorization
        if ($user->role === 'dospem') {
            if (!$mahasiswa->profilMahasiswa || $mahasiswa->profilMahasiswa->id_dospem !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
            }
        }

        // Update laporan PKL status
        \App\Models\LaporanPkl::where('mahasiswa_id', $mahasiswaId)->update([
            'status_validasi' => $request->status_validasi,
        ]);

        // Log activity
        $this->logValidationActivity(
            'pemberkasan_akhir',
            $mahasiswaId,
            'menunggu',
            $request->status_validasi,
            $request->catatan
        );

        // Send WhatsApp notification
        $this->sendValidationNotification(
            $mahasiswa,
            'Pemberkasan Akhir (Laporan PKL)',
            $request->status_validasi,
            $request->catatan
        );

        return redirect()->back()->with('success', 'Pemberkasan Akhir berhasil divalidasi!');
    }
}
