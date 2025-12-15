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
        // Basic stats
        $totalMahasiswa = User::mahasiswa()->count();
        $totalDosen = User::dosenPembimbing()->count();
        $totalAdmin = User::admin()->count();
        $totalMitra = \App\Models\Mitra::count();
        
        // Pending validation stats
        $berkasPending = Khs::menunggu()->count() + SuratBalasan::menunggu()->count() + LaporanPkl::menunggu()->count();
        $berkasKhsPending = Khs::menunggu()->count();
        $berkasSuratBalasanPending = SuratBalasan::menunggu()->count();
        $berkasLaporanPending = LaporanPkl::menunggu()->count();
        $berkasSuratPengantarPending = \App\Models\SuratPengantar::where('status_validasi', 'menunggu')->count();
        
        // Total berkas per jenis
        $totalKhs = Khs::count();
        $totalSuratBalasan = SuratBalasan::count();
        $totalLaporan = LaporanPkl::count();
        $totalSuratPengantar = \App\Models\SuratPengantar::count();
        
        // Validated stats
        $berkasKhsTervalidasi = Khs::tervalidasi()->count();
        $berkasSuratBalasanTervalidasi = SuratBalasan::tervalidasi()->count();
        $berkasLaporanTervalidasi = LaporanPkl::tervalidasi()->count();
        $berkasSuratPengantarTervalidasi = \App\Models\SuratPengantar::where('status_validasi', 'tervalidasi')->count();
        $berkasTervalidasi = $berkasKhsTervalidasi + $berkasSuratBalasanTervalidasi + $berkasLaporanTervalidasi + $berkasSuratPengantarTervalidasi;
        
        // Belum valid (revisi/ditolak)
        $berkasKhsRevisi = Khs::belumValid()->count();
        $berkasSuratBalasanRevisi = SuratBalasan::belumValid()->count();
        $berkasLaporanRevisi = LaporanPkl::belumValid()->count();
        $berkasBelumValid = $berkasKhsRevisi + $berkasSuratBalasanRevisi + $berkasLaporanRevisi;
        
        // Mahasiswa stats
        $mahasiswaDenganDospem = \App\Models\ProfilMahasiswa::whereNotNull('id_dospem')->count();
        $mahasiswaTanpaDospem = \App\Models\ProfilMahasiswa::whereNull('id_dospem')->count();
        $mahasiswaDenganMitra = \App\Models\ProfilMahasiswa::whereNotNull('mitra_selected')->count();
        $mahasiswaTanpaMitra = \App\Models\ProfilMahasiswa::whereNull('mitra_selected')->count();
        
        // Kelayakan PKL stats
        $mahasiswaLayak = \App\Models\ProfilMahasiswa::where('cek_min_semester', true)
                          ->where('cek_ipk_nilaisks', true)
                          ->where('cek_valid_biodata', true)
                          ->count();
        $mahasiswaBelumLayak = $totalMahasiswa - $mahasiswaLayak;
        
        // Rata-rata IPK mahasiswa
        $avgIpk = \App\Models\ProfilMahasiswa::whereNotNull('ipk')->avg('ipk') ?? 0;
        $minIpk = \App\Models\ProfilMahasiswa::whereNotNull('ipk')->min('ipk') ?? 0;
        $maxIpk = \App\Models\ProfilMahasiswa::whereNotNull('ipk')->max('ipk') ?? 0;
        
        // Statistik jenis kelamin
        $mahasiswaLakiLaki = \App\Models\ProfilMahasiswa::where('jenis_kelamin', 'L')->count();
        $mahasiswaPerempuan = \App\Models\ProfilMahasiswa::where('jenis_kelamin', 'P')->count();
        
        // Mitra stats
        $mitraAktif = \App\Models\Mitra::whereHas('mahasiswaTerpilih')->count();
        $allMitra = \App\Models\Mitra::withCount('mahasiswaTerpilih')->get();
        $mitraPenuh = $allMitra->filter(function($m) { return $m->mahasiswa_terpilih_count >= $m->max_mahasiswa; })->count();
        $mitraTersedia = $totalMitra - $mitraPenuh;
        $totalKuotaMitra = \App\Models\Mitra::sum('max_mahasiswa');
        $kuotaTerisi = \App\Models\ProfilMahasiswa::whereNotNull('mitra_selected')->count();
        $kuotaTersisa = $totalKuotaMitra - $kuotaTerisi;
        
        // Top 5 mitra populer
        $mitraPopuler = \App\Models\Mitra::withCount('mahasiswaTerpilih')
                        ->orderBy('mahasiswa_terpilih_count', 'desc')
                        ->take(5)
                        ->get();
        
        // Dospem dengan mahasiswa terbanyak
        $dospemPopuler = User::where('role', 'dospem')
                        ->withCount(['mahasiswaBimbingan'])
                        ->orderBy('mahasiswa_bimbingan_count', 'desc')
                        ->take(5)
                        ->get();
        
        // Statistik per prodi
        $mahasiswaPerProdi = \App\Models\ProfilMahasiswa::groupBy('prodi')
                            ->selectRaw('prodi, count(*) as total')
                            ->get();
        
        // Statistik per semester
        $mahasiswaPerSemester = \App\Models\ProfilMahasiswa::groupBy('semester')
                               ->selectRaw('semester, count(*) as total')
                               ->orderBy('semester')
                               ->get();
        
        // Aktivitas terbaru
        $recentActivities = \App\Models\HistoryAktivitas::with(['user', 'mahasiswa'])
                           ->orderBy('tanggal_dibuat', 'desc')
                           ->take(10)
                           ->get();
        
        // Mahasiswa terbaru yang mendaftar
        $mahasiswaTerbaru = User::mahasiswa()
                           ->with('profilMahasiswa')
                           ->orderBy('created_at', 'desc')
                           ->take(5)
                           ->get();
        
        // Statistik login hari ini
        $loginHariIni = \App\Models\HistoryAktivitas::where('tipe', 'login')
                       ->whereDate('tanggal_dibuat', today())
                       ->count();
        
        // Total aktivitas hari ini
        $aktivitasHariIni = \App\Models\HistoryAktivitas::whereDate('tanggal_dibuat', today())->count();
        
        // Upload dokumen hari ini
        $uploadHariIni = \App\Models\HistoryAktivitas::where('tipe', 'upload_dokumen')
                        ->whereDate('tanggal_dibuat', today())
                        ->count();
        
        // Validasi hari ini
        $validasiHariIni = \App\Models\HistoryAktivitas::where('tipe', 'validasi_dokumen')
                          ->whereDate('tanggal_dibuat', today())
                          ->count();

        $stats = [
            'total_mahasiswa' => $totalMahasiswa,
            'total_dosen' => $totalDosen,
            'total_admin' => $totalAdmin,
            'total_mitra' => $totalMitra,
            'berkas_pending' => $berkasPending,
            'berkas_khs_pending' => $berkasKhsPending,
            'berkas_surat_balasan_pending' => $berkasSuratBalasanPending,
            'berkas_laporan_pending' => $berkasLaporanPending,
            'berkas_surat_pengantar_pending' => $berkasSuratPengantarPending,
            'total_khs' => $totalKhs,
            'total_surat_balasan' => $totalSuratBalasan,
            'total_laporan' => $totalLaporan,
            'total_surat_pengantar' => $totalSuratPengantar,
            'berkas_tervalidasi' => $berkasTervalidasi,
            'berkas_khs_tervalidasi' => $berkasKhsTervalidasi,
            'berkas_surat_balasan_tervalidasi' => $berkasSuratBalasanTervalidasi,
            'berkas_laporan_tervalidasi' => $berkasLaporanTervalidasi,
            'berkas_surat_pengantar_tervalidasi' => $berkasSuratPengantarTervalidasi,
            'berkas_khs_revisi' => $berkasKhsRevisi,
            'berkas_surat_balasan_revisi' => $berkasSuratBalasanRevisi,
            'berkas_laporan_revisi' => $berkasLaporanRevisi,
            'berkas_belum_valid' => $berkasBelumValid,
            'mahasiswa_dengan_dospem' => $mahasiswaDenganDospem,
            'mahasiswa_tanpa_dospem' => $mahasiswaTanpaDospem,
            'mahasiswa_dengan_mitra' => $mahasiswaDenganMitra,
            'mahasiswa_tanpa_mitra' => $mahasiswaTanpaMitra,
            'mahasiswa_layak' => $mahasiswaLayak,
            'mahasiswa_belum_layak' => $mahasiswaBelumLayak,
            'avg_ipk' => $avgIpk,
            'min_ipk' => $minIpk,
            'max_ipk' => $maxIpk,
            'mahasiswa_laki_laki' => $mahasiswaLakiLaki,
            'mahasiswa_perempuan' => $mahasiswaPerempuan,
            'mitra_aktif' => $mitraAktif,
            'mitra_penuh' => $mitraPenuh,
            'mitra_tersedia' => $mitraTersedia,
            'total_kuota_mitra' => $totalKuotaMitra,
            'kuota_terisi' => $kuotaTerisi,
            'kuota_tersisa' => $kuotaTersisa,
            'mitra_populer' => $mitraPopuler,
            'dospem_populer' => $dospemPopuler,
            'mahasiswa_per_prodi' => $mahasiswaPerProdi,
            'mahasiswa_per_semester' => $mahasiswaPerSemester,
            'recent_activities' => $recentActivities,
            'mahasiswa_terbaru' => $mahasiswaTerbaru,
            'login_hari_ini' => $loginHariIni,
            'aktivitas_hari_ini' => $aktivitasHariIni,
            'upload_hari_ini' => $uploadHariIni,
            'validasi_hari_ini' => $validasiHariIni,
            'unassigned_students' => User::where('role', 'mahasiswa')
                ->whereDoesntHave('profilMahasiswa', function($query) {
                    $query->whereNotNull('id_dospem');
                })
                ->with('profilMahasiswa')
                ->get(),
        ];

        return view('dashboard.index', compact('stats'));
    }

    public function dospem()
    {
        $user = Auth::user();
        $mahasiswaIds = $user->mahasiswaBimbingan()->pluck('id_mahasiswa');
        
        // Get mahasiswa list with eager loading for IPK transkrip
        $mahasiswaBimbinganList = $user->mahasiswaBimbingan()
            ->with(['user', 'user.khsManualTranskrip'])
            ->get();
        
        // Calculate IPK from transkrip for each mahasiswa
        foreach ($mahasiswaBimbinganList as $profil) {
            $profil->ipk_transkrip = $this->calculateIpkFromTranskrip($profil->user);
        }
        
        $stats = [
            'mahasiswa_bimbingan' => $user->mahasiswaBimbingan()->count(),
            'berkas_perlu_validasi' => $this->getBerkasPerluValidasi($user),
            'berkas_tervalidasi' => $this->getBerkasTervalidasi($user),
            'total_mitra' => \App\Models\Mitra::count(),
            'mahasiswa_bimbingan_list' => $mahasiswaBimbinganList,
        ];

        return view('dashboard.index', compact('stats'));
    }

    /**
     * Calculate IPK from transkrip data using weighted average
     */
    private function calculateIpkFromTranskrip($user)
    {
        if (!$user || !$user->khsManualTranskrip) {
            return 0;
        }

        $totalCreditPoints = 0;
        $totalSksAll = 0;

        foreach ($user->khsManualTranskrip as $transkrip) {
            // Use stored IPS and total_sks from the transkrip record
            $ips = floatval($transkrip->ips ?? 0);
            $sks = intval($transkrip->total_sks ?? 0);

            if ($ips > 0 && $sks > 0) {
                $totalCreditPoints += ($ips * $sks);
                $totalSksAll += $sks;
            }
        }

        return $totalSksAll > 0 ? round($totalCreditPoints / $totalSksAll, 2) : 0;
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
            'pkl_status' => $user->profilMahasiswa->status_pkl ?? 'belum_siap',
            'missing_steps' => $progressBerkas['missing_steps'] ?? [],
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
        $missingSteps = [];

        // 1. Kelayakan PKL
        $kelayakan = $this->getDetailedKelayakanData($mahasiswa);
        if ($kelayakan['is_eligible']) {
            $completed++;
        } else {
            $missingSteps[] = 'Kelayakan PKL';
        }

        // 2. Dokumen Pendukung
        if ($this->getDokumenPendukungStatus($mahasiswa) === 'lengkap') {
            $completed++;
        } else {
            $missingSteps[] = 'Dokumen Pendukung';
        }

        // 3. Instansi Mitra
        if ($this->getInstansiMitraStatus($mahasiswa) === 'lengkap') {
            $completed++;
        } else {
            $missingSteps[] = 'Instansi Mitra';
        }

        // 4. Pemberkasan Akhir
        if ($this->getPemberkasanAkhirStatus($mahasiswa) === 'lengkap') {
            $completed++;
        } else {
            $missingSteps[] = 'Pemberkasan Akhir';
        }

        return [
            'completed' => $completed,
            'total' => $total,
            'percentage' => round(($completed / $total) * 100),
            'missing_steps' => $missingSteps
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
            ->whereBetween('semester', [1, 5])
            ->distinct()
            ->count('semester');

        // Calculate IPK and check eligibility criteria using Weighted Average
        // SINKRONISASI dengan resources/views/documents/index.blade.php
        $totalSksAll = 0;
        $totalQualityPoints = 0;
        $totalSksD = 0;
        $totalE = 0;
        $countSemestersWithIps = 0;

        foreach ($khsTranskrip as $transcript) {
            // Use stored values directly (already calculated by JavaScript when saving)
            $ips = floatval($transcript->ips ?? 0);
            $sks = intval($transcript->total_sks ?? 0);
            
            // Calculate weighted contribution: IPS × SKS
            if ($ips > 0 && $sks > 0) {
                $totalQualityPoints += ($ips * $sks);
                $totalSksAll += $sks;
                $countSemestersWithIps++;
            }
            
            // Sum up SKS D and E count
            $totalSksD += intval($transcript->total_sks_d ?? 0);
            if ($transcript->has_e) {
                $totalE++;
            }
        }

        // Calculate final IPK using Weighted Average
        $finalIpk = $totalSksAll > 0 ? $totalQualityPoints / $totalSksAll : 0;

        // Check Google Drive links (only PKKMB and E-Course are required, Semasa is optional)
        $hasPkkmb = !empty($profil->gdrive_pkkmb ?? '');
        $hasEcourse = !empty($profil->gdrive_ecourse ?? '');
        $hasDokumenPendukung = $hasPkkmb && $hasEcourse;

        // Check eligibility criteria - SINKRONISASI dengan logika di halaman documents
        // Syarat: IPK ≥ 2.5, SKS D ≤ 6, tidak ada nilai E
        // User Request: Dokumen pendukung tidak masuk dalam hitungan kelayakan di dashboard
        $isTranscriptComplete = $totalSemesters >= 4;
        $isKhsComplete = $khsFileCount >= 4;
        $isEligible = $isTranscriptComplete && $isKhsComplete && $finalIpk >= 2.5 && $totalSksD <= 6 && $totalE == 0;

        // Collect missing requirements
        $missingRequirements = [];
        if (!$isTranscriptComplete) $missingRequirements[] = "Transkrip belum lengkap (min 4 semester)";
        if (!$isKhsComplete) $missingRequirements[] = "File KHS belum lengkap (min 4 file)";
        if ($finalIpk < 2.5) $missingRequirements[] = "IPK kurang dari 2.50";
        if ($totalSksD > 6) $missingRequirements[] = "Total SKS D lebih dari 6";
        if ($totalE > 0) $missingRequirements[] = "Terdapat nilai E";

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
            'missing' => $missingRequirements
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
            'missing_requirements' => $missingRequirements,
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
