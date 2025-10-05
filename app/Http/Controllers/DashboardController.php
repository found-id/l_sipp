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
            'khs_status' => $this->getKhsStatus($user),
            'surat_status' => $this->getSuratStatus($user),
            'laporan_status' => $this->getLaporanStatus($user),
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
        $total = 3; // KHS, Surat Balasan, Laporan
        $completed = 0;
        
        if ($mahasiswa->khs()->tervalidasi()->exists()) $completed++;
        if ($mahasiswa->suratBalasan()->tervalidasi()->exists()) $completed++;
        if ($mahasiswa->laporanPkl()->tervalidasi()->exists()) $completed++;
        
        return [
            'completed' => $completed,
            'total' => $total,
            'percentage' => round(($completed / $total) * 100)
        ];
    }

    private function getKhsStatus($mahasiswa)
    {
        $khs = $mahasiswa->khs()->latest()->first();
        return $khs ? $khs->status_validasi : 'belum_upload';
    }

    private function getSuratStatus($mahasiswa)
    {
        $surat = $mahasiswa->suratBalasan()->latest()->first();
        return $surat ? $surat->status_validasi : 'belum_upload';
    }

    private function getLaporanStatus($mahasiswa)
    {
        $laporan = $mahasiswa->laporanPkl()->latest()->first();
        return $laporan ? $laporan->status_validasi : 'belum_upload';
    }
}
