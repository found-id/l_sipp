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

        // Get detailed kelayakan data
        $kelayakanData = $this->getDetailedKelayakanData($user);

        $stats = [
            'progress_berkas' => $completed . '/' . $total,
            'progress_percentage' => $percentage,
            'kelayakan_status' => $kelayakanData['status'],
            'is_eligible' => $kelayakanData['is_eligible'],
            'kelayakan_data' => $kelayakanData,
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
        $total = 4; // 4 tabs: Kelayakan, Dokumen Pendukung, Instansi Mitra, Pemberkasan Akhir
        $completed = 0;

        // 1. Tab Pemberkasan Kelayakan - KHS transkrip 5 semester + KHS file 5 file
        $khsTranskrip = \App\Models\KhsManualTranskrip::where('mahasiswa_id', $mahasiswa->id)->count();
        $khsFileCount = $mahasiswa->khs()->whereBetween('semester', [1, 5])->distinct()->count('semester');
        if ($khsTranskrip >= 5 && $khsFileCount >= 5) {
            $completed++;
        }

        // 2. Tab Pemberkasan Dokumen Pendukung - PKKMB + E-Course filled
        $profil = $mahasiswa->profilMahasiswa;
        if ($profil && $profil->gdrive_pkkmb && $profil->gdrive_ecourse) {
            $completed++;
        }

        // 3. Tab Pemberkasan Instansi Mitra - Surat Pengantar uploaded = lengkap
        $hasSuratPengantar = \App\Models\SuratPengantar::where('mahasiswa_id', $mahasiswa->id)->exists();
        if ($hasSuratPengantar) {
            $completed++;
        }

        // 4. Tab Pemberkasan Akhir - Laporan PKL uploaded = lengkap
        if ($mahasiswa->laporanPkl()->exists()) {
            $completed++;
        }

        return [
            'completed' => $completed,
            'total' => $total,
            'percentage' => round(($completed / $total) * 100)
        ];
    }

    private function getDetailedKelayakanData($mahasiswa)
    {
        $profil = $mahasiswa->profilMahasiswa;

        // Check if all required semesters (1-4) have KHS transkrip uploaded
        $khsTranskrip = \App\Models\KhsManualTranskrip::where('mahasiswa_id', $mahasiswa->id)->get();
        $totalSemesters = $khsTranskrip->count();

        // Check KHS file uploads (4 files required)
        $khsFileCount = $mahasiswa->khs()
            ->whereBetween('semester', [1, 4])
            ->distinct()
            ->count('semester');

        // Calculate IPK and check eligibility criteria
        // Only count semesters that have IPS value (not null/empty)
        $semestersWithIps = $khsTranskrip->filter(function($khs) {
            return !empty($khs->ips) && $khs->ips > 0;
        });

        $totalIps = $semestersWithIps->sum('ips');
        $countSemestersWithIps = $semestersWithIps->count();
        $totalSksD = $khsTranskrip->sum('total_sks_d');
        $totalE = $khsTranskrip->where('has_e', true)->count();

        // Calculate IPK from semesters that have IPS values only
        $finalIpk = $countSemestersWithIps > 0 ? $totalIps / $countSemestersWithIps : 0;

        // Check Google Drive links (only PKKMB and E-Course are required, Semasa is optional)
        $hasPkkmb = !empty($profil->gdrive_pkkmb ?? '');
        $hasEcourse = !empty($profil->gdrive_ecourse ?? '');
        $hasDokumenPendukung = $hasPkkmb && $hasEcourse;

        // Check eligibility criteria - SINKRONISASI dengan logika di halaman documents
        // Syarat: IPK ≥ 2.5, SKS D ≤ 6, tidak ada nilai E
        $isTranscriptComplete = $totalSemesters >= 4;
        $isKhsComplete = $khsFileCount >= 4;
        $isEligible = $isTranscriptComplete && $isKhsComplete && $finalIpk >= 2.5 && $totalSksD <= 6 && $totalE == 0 && $hasDokumenPendukung;

        // DEBUG: Log untuk troubleshooting
        \Log::info('Dashboard Kelayakan Check', [
            'user_id' => $mahasiswa->id,
            'user_name' => $mahasiswa->name,
            'total_semesters' => $totalSemesters,
            'khs_file_count' => $khsFileCount,
            'final_ipk' => round($finalIpk, 2),
            'total_sks_d' => $totalSksD,
            'total_e' => $totalE,
            'has_pkkmb' => $hasPkkmb,
            'has_ecourse' => $hasEcourse,
            'is_transcript_complete' => $isTranscriptComplete,
            'is_khs_complete' => $isKhsComplete,
            'is_eligible' => $isEligible,
        ]);

        // Determine status
        if (!$profil) {
            $status = 'belum_lengkap';
        } elseif (!$isTranscriptComplete || !$isKhsComplete) {
            $status = 'belum_lengkap';
        } elseif ($isEligible) {
            $status = 'layak';
        } else {
            $status = 'tidak_layak';
        }

        return [
            'status' => $status,
            'is_eligible' => $isEligible,
            'total_semesters' => $totalSemesters,
            'khs_file_count' => $khsFileCount,
            'final_ipk' => $finalIpk,
            'total_sks_d' => $totalSksD,
            'total_e' => $totalE,
            'has_pkkmb' => $hasPkkmb,
            'has_ecourse' => $hasEcourse,
            'has_dokumen_pendukung' => $hasDokumenPendukung,
        ];
    }

    private function getKelayakanStatus($mahasiswa)
    {
        $data = $this->getDetailedKelayakanData($mahasiswa);
        return $data['status'];
    }

    private function getDokumenPendukungStatus($mahasiswa)
    {
        $profil = $mahasiswa->profilMahasiswa;
        if (!$profil) return 'belum_lengkap';

        // Check if required Google Drive links are filled (PKKMB and E-Course are required)
        if ($profil->gdrive_pkkmb && $profil->gdrive_ecourse) {
            return 'lengkap';
        }

        // Check if partially filled
        if ($profil->gdrive_pkkmb || $profil->gdrive_ecourse) {
            return 'sebagian';
        }

        return 'belum_lengkap';
    }

    private function getInstansiMitraStatus($mahasiswa)
    {
        $profil = $mahasiswa->profilMahasiswa;
        if (!$profil || !$profil->mitra_selected) return 'belum_pilih';

        // Check if surat balasan uploaded - if exists, it's complete
        $surat = $mahasiswa->suratBalasan()->latest()->first();
        if (!$surat) return 'belum_upload';

        // If surat balasan exists, mark as lengkap
        return 'lengkap';
    }

    private function getPemberkasanAkhirStatus($mahasiswa)
    {
        $laporan = $mahasiswa->laporanPkl()->latest()->first();
        if (!$laporan) return 'belum_upload';

        // If laporan PKL exists, mark as lengkap
        return 'lengkap';
    }
}
