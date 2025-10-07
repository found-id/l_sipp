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

        // Get mahasiswa bimbingan
        $mahasiswaIds = \App\Models\ProfilMahasiswa::where('id_dospem', $user->id)
            ->pluck('id_mahasiswa')
            ->toArray();

        // Get documents that need validation
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
}
