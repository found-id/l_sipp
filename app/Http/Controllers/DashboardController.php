<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Khs;
use App\Models\SuratBalasan;
use App\Models\LaporanPkl;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect to role-specific dashboard
        switch ($user->role) {
            case 'admin':
                return $this->admin();
            case 'dospem':
                return $this->dospem();
            case 'mahasiswa':
                return $this->mahasiswa();
            default:
                return view('dashboard.index');
        }
    }

    public function admin()
    {
        $stats = [
            'total_mahasiswa' => User::mahasiswa()->count(),
            'total_dosen' => User::dosenPembimbing()->count(),
            'total_admin' => User::admin()->count(),
            'total_mitra' => \App\Models\Mitra::count(),
            'berkas_pending' => Khs::menunggu()->count() + 
                              SuratBalasan::menunggu()->count() + 
                              LaporanPkl::menunggu()->count(),
            'berkas_tervalidasi' => Khs::tervalidasi()->count() + 
                                  SuratBalasan::tervalidasi()->count() + 
                                  LaporanPkl::tervalidasi()->count(),
            'berkas_belum_valid' => Khs::belumValid()->count() + 
                                  SuratBalasan::belumValid()->count() + 
                                  LaporanPkl::belumValid()->count(),
            'unassigned_students' => User::where('role', 'mahasiswa')
                ->whereDoesntHave('profilMahasiswa', function($query) {
                    $query->whereNotNull('id_dospem');
                })
                ->with('profilMahasiswa')
                ->get(),
            'recent_activities' => \App\Models\HistoryAktivitas::with(['user', 'mahasiswa'])
                ->orderBy('tanggal_dibuat', 'desc')
                ->limit(3)
                ->get(),
        ];

        return view('dashboard.index', compact('stats'));
    }

    public function dospem()
    {
        $user = Auth::user();
        $mahasiswaIds = $user->mahasiswaBimbingan()->pluck('id_mahasiswa');
        
        $stats = [
            'mahasiswa_bimbingan' => $user->mahasiswaBimbingan()->count(),
            'berkas_perlu_validasi' => $this->getBerkasPerluValidasi($user),
            'berkas_tervalidasi' => $this->getBerkasTervalidasi($user),
            'total_mitra' => \App\Models\Mitra::count(),
            'mahasiswa_bimbingan_list' => $user->mahasiswaBimbingan()->with('user')->get(),
        ];

        return view('dashboard.index', compact('stats'));
    }

    public function mahasiswa()
    {
        $user = Auth::user();

        $progressBerkas = $this->getProgressBerkas($user);
        $completed = $progressBerkas['completed'];
        $total = $progressBerkas['total'];
        $percentage = $progressBerkas['percentage'];

        $stats = [
            'progress_berkas' => $completed . '/' . $total,
            'progress_percentage' => $percentage,
            'kelayakan_status' => $this->getKelayakanStatus($user),
            'dokumen_pendukung_status' => $this->getDokumenPendukungStatus($user),
            'instansi_mitra_status' => $this->getInstansiMitraStatus($user),
            'pemberkasan_akhir_status' => $this->getPemberkasanAkhirStatus($user),
            'dosen_pembimbing' => $user->profilMahasiswa->dosenPembimbing ?? null,
        ];

        return view('dashboard.index', compact('stats'));
    }

    private function getBerkasPerluValidasi($dosen)
    {
        $mahasiswaIds = $dosen->mahasiswaBimbingan()->pluck('id_mahasiswa');
        
        return Khs::whereIn('mahasiswa_id', $mahasiswaIds)->menunggu()->count() +
               SuratBalasan::whereIn('mahasiswa_id', $mahasiswaIds)->menunggu()->count() +
               LaporanPkl::whereIn('mahasiswa_id', $mahasiswaIds)->menunggu()->count();
    }

    private function getBerkasTervalidasi($dosen)
    {
        $mahasiswaIds = $dosen->mahasiswaBimbingan()->pluck('id_mahasiswa');
        
        return Khs::whereIn('mahasiswa_id', $mahasiswaIds)->tervalidasi()->count() +
               SuratBalasan::whereIn('mahasiswa_id', $mahasiswaIds)->tervalidasi()->count() +
               LaporanPkl::whereIn('mahasiswa_id', $mahasiswaIds)->tervalidasi()->count();
    }

    private function getProgressBerkas($mahasiswa)
    {
        $total = 4; // Kelayakan, Dokumen Pendukung, Instansi Mitra, Pemberkasan Akhir
        $completed = 0;

        // 1. Kelayakan - Check if eligible based on KHS
        if ($this->getKelayakanStatus($mahasiswa) === 'layak') $completed++;

        // 2. Dokumen Pendukung - Check if Google Drive links are filled
        if ($this->getDokumenPendukungStatus($mahasiswa) === 'lengkap') $completed++;

        // 3. Instansi Mitra - Check if mitra selected and surat balasan uploaded & validated
        if ($this->getInstansiMitraStatus($mahasiswa) === 'tervalidasi') $completed++;

        // 4. Pemberkasan Akhir - Check if laporan uploaded & validated
        if ($this->getPemberkasanAkhirStatus($mahasiswa) === 'tervalidasi') $completed++;

        return [
            'completed' => $completed,
            'total' => $total,
            'percentage' => round(($completed / $total) * 100)
        ];
    }

    private function getKelayakanStatus($mahasiswa)
    {
        $profil = $mahasiswa->profilMahasiswa;
        if (!$profil) return 'belum_lengkap';

        // Check if all required semesters (1-4) have KHS uploaded
        $khsCount = \App\Models\KhsManualTranskrip::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('semester', [1, 2, 3, 4])
            ->count();

        if ($khsCount < 4) return 'belum_lengkap';

        // Check eligibility based on profil checks
        if ($profil->cek_min_semester && $profil->cek_ipk_nilaisks && $profil->cek_valid_biodata) {
            return 'layak';
        }

        return 'tidak_layak';
    }

    private function getDokumenPendukungStatus($mahasiswa)
    {
        $profil = $mahasiswa->profilMahasiswa;
        if (!$profil) return 'belum_lengkap';

        // Check if all required Google Drive links are filled
        if ($profil->gdrive_pkkmb && $profil->gdrive_ecourse) {
            return 'lengkap';
        }

        return 'belum_lengkap';
    }

    private function getInstansiMitraStatus($mahasiswa)
    {
        $profil = $mahasiswa->profilMahasiswa;
        if (!$profil || !$profil->mitra_selected) return 'belum_pilih';

        // Check if surat balasan uploaded and validated
        $surat = $mahasiswa->suratBalasan()->latest()->first();
        if (!$surat) return 'belum_upload';

        return $surat->status_validasi;
    }

    private function getPemberkasanAkhirStatus($mahasiswa)
    {
        $laporan = $mahasiswa->laporanPkl()->latest()->first();
        if (!$laporan) return 'belum_upload';

        return $laporan->status_validasi;
    }
}
