<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Khs;
use App\Models\SuratBalasan;
use App\Models\LaporanPkl;
use App\Models\Mitra;

class DocumentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'mahasiswa') {
            $khs = $user->khs()->latest()->first();
            $suratBalasan = $user->suratBalasan()->latest()->first();
            $laporanPkl = $user->laporanPkl()->latest()->first();
            $mitra = Mitra::all();
            
            return view('documents.index', compact('khs', 'suratBalasan', 'laporanPkl', 'mitra'));
        }
        
        // For dospem and admin - show documents from their students
        $documents = $this->getDocumentsForSupervisor($user);
        
        return view('documents.supervisor', compact('documents'));
    }

    public function uploadKhs(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        
        // Delete old KHS if exists
        $oldKhs = $user->khs()->latest()->first();
        if ($oldKhs) {
            Storage::delete($oldKhs->file_path);
            $oldKhs->delete();
        }

        // Store new file
        $file = $request->file('file');
        $nim = $user->profilMahasiswa->nim ?? $user->id;
        $nama = str_replace(' ', '_', $user->name);
        $filename = 'KHS_' . $nama . '_' . $nim . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/khs', $filename, 'public');

        // Create KHS record
        Khs::create([
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
                'document_type' => 'KHS',
                'mahasiswa' => $user->name,
                'file_name' => $filename,
            ],
        ]);

        return redirect()->route('documents.index')->with('success', 'KHS berhasil diupload!');
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
        $nim = $user->profilMahasiswa->nim ?? $user->id;
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
        $nim = $user->profilMahasiswa->nim ?? $user->id;
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
}
