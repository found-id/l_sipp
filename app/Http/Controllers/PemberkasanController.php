<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\Khs;

class PemberkasanController extends Controller
{
    public function cekKelayakan()
    {
        // UI cek kelayakan dirender via documents.index (tab "Cek Kelayakan" sudah include partial)
        return view('documents.index');
    }

    // Upload KHS per semester (1..4), langsung trigger perhitungan kelayakan otomatis
    public function uploadKhsSemester(Request $request, int $semester)
    {
        abort_unless(in_array($semester, [1,2,3,4]), 404);

        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $user = Auth::user();
        $file = $request->file('file');

        $nim  = optional($user->profilMahasiswa)->nim ?? $user->id;
        $nama = preg_replace('/[^A-Za-z0-9_]/', '_', str_replace(' ', '_', $user->name));
        $filename = "S{$semester}_KHS_{$nama}_{$nim}_" . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("documents/khs/{$user->id}", $filename, 'public');

        $data = [
            'mahasiswa_id'    => $user->id,
            'file_path'       => $path,
            'status_validasi' => 'terunggah', // kita tidak pakai validasi manual untuk cek kelayakan
        ];

        // kalau tabel KHS punya kolom 'semester', isi
        if (in_array('semester', Schema::getColumnListing((new Khs)->getTable()))) {
            $data['semester'] = $semester;
        }

        Khs::create($data);

        // hitung kelayakan otomatis
        $this->recalculateEligibility($user->id);

        return back()->with('success', "KHS Semester {$semester} berhasil diupload & diproses.");
    }

    protected function recalculateEligibility(int $mahasiswaId): void
    {
        // TODO: Implement parsing PDF dan hitung IPK / total SKS D / cek nilai E
        // Contoh alur:
        // 1) Ambil 4 file S1..S4 dari storage
        // 2) Ekstrak nilai -> hitung IPK, total D (SKS), dan ada/tidak nilai E
        // 3) Simpan ke table status/registrasi (eligible true/false)
        //
        // $files = Storage::disk('public')->files("documents/khs/{$mahasiswaId}");
        // $eligible = ($ipk >= 2.50) && ($totalD <= 6) && (!$adaE);
        // \App\Models\RegistrasiPkl::updateOrCreate([...], ['eligible'=>$eligible, ...]);
    }
}
