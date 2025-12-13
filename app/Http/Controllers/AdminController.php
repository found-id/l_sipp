<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\ProfilMahasiswa;
use App\Models\Mitra;
use App\Models\Dospem;
use App\Services\SawCalculationService;

class AdminController extends Controller
{
    public function index()
    {
        // Basic stats
        $totalMahasiswa = User::mahasiswa()->count();
        $totalDosen = User::dosenPembimbing()->count();
        $totalAdmin = User::admin()->count();
        $totalMitra = Mitra::count();
        $totalUsers = User::count();
        
        // Pending validation stats
        $berkasPending = $this->getPendingValidationCount();
        $berkasKhsPending = \App\Models\Khs::menunggu()->count();
        $berkasSuratBalasanPending = \App\Models\SuratBalasan::menunggu()->count();
        $berkasLaporanPending = \App\Models\LaporanPkl::menunggu()->count();
        $berkasSuratPengantarPending = \App\Models\SuratPengantar::menunggu()->count();
        
        // Total berkas per jenis
        $totalKhs = \App\Models\Khs::count();
        $totalSuratBalasan = \App\Models\SuratBalasan::count();
        $totalLaporan = \App\Models\LaporanPkl::count();
        $totalSuratPengantar = \App\Models\SuratPengantar::count();
        
        // Validated stats
        $berkasKhsTervalidasi = \App\Models\Khs::where('status_validasi', 'tervalidasi')->count();
        $berkasSuratBalasanTervalidasi = \App\Models\SuratBalasan::where('status_validasi', 'tervalidasi')->count();
        $berkasLaporanTervalidasi = \App\Models\LaporanPkl::where('status_validasi', 'tervalidasi')->count();
        $berkasSuratPengantarTervalidasi = \App\Models\SuratPengantar::where('status_validasi', 'tervalidasi')->count();
        $berkasTervalidasi = $berkasKhsTervalidasi + $berkasSuratBalasanTervalidasi + $berkasLaporanTervalidasi + $berkasSuratPengantarTervalidasi;
        
        // Belum valid (revisi/ditolak)
        $berkasKhsRevisi = \App\Models\Khs::where('status_validasi', 'revisi')->count();
        $berkasSuratBalasanRevisi = \App\Models\SuratBalasan::where('status_validasi', 'revisi')->count();
        $berkasLaporanRevisi = \App\Models\LaporanPkl::where('status_validasi', 'revisi')->count();
        $berkasBelumValid = $berkasKhsRevisi + $berkasSuratBalasanRevisi + $berkasLaporanRevisi;
        
        // Mahasiswa stats
        $mahasiswaDenganDospem = ProfilMahasiswa::whereNotNull('id_dospem')->count();
        $mahasiswaTanpaDospem = ProfilMahasiswa::whereNull('id_dospem')->count();
        $mahasiswaDenganMitra = ProfilMahasiswa::whereNotNull('mitra_selected')->count();
        $mahasiswaTanpaMitra = ProfilMahasiswa::whereNull('mitra_selected')->count();
        
        // Kelayakan PKL stats
        $mahasiswaLayak = ProfilMahasiswa::where('cek_min_semester', true)
                          ->where('cek_ipk_nilaisks', true)
                          ->where('cek_valid_biodata', true)
                          ->count();
        $mahasiswaBelumLayak = $totalMahasiswa - $mahasiswaLayak;
        
        // Rata-rata IPK mahasiswa
        $avgIpk = ProfilMahasiswa::whereNotNull('ipk')->avg('ipk');
        $minIpk = ProfilMahasiswa::whereNotNull('ipk')->min('ipk');
        $maxIpk = ProfilMahasiswa::whereNotNull('ipk')->max('ipk');
        
        // Statistik jenis kelamin
        $mahasiswaLakiLaki = ProfilMahasiswa::where('jenis_kelamin', 'L')->count();
        $mahasiswaPerempuan = ProfilMahasiswa::where('jenis_kelamin', 'P')->count();
        
        // Mitra stats
        $mitraAktif = Mitra::whereHas('mahasiswaTerpilih')->count();
        $allMitra = Mitra::withCount('mahasiswaTerpilih')->get();
        $mitraPenuh = $allMitra->filter(function($m) { return $m->mahasiswa_terpilih_count >= $m->max_mahasiswa; })->count();
        $mitraTersedia = $totalMitra - $mitraPenuh;
        $totalKuotaMitra = Mitra::sum('max_mahasiswa');
        $kuotaTerisi = ProfilMahasiswa::whereNotNull('mitra_selected')->count();
        $kuotaTersisa = $totalKuotaMitra - $kuotaTerisi;
        
        // Top 5 mitra populer
        $mitraPopuler = Mitra::withCount('mahasiswaTerpilih')
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
        $mahasiswaPerProdi = ProfilMahasiswa::groupBy('prodi')
                            ->selectRaw('prodi, count(*) as total')
                            ->get();
        
        // Statistik per semester
        $mahasiswaPerSemester = ProfilMahasiswa::groupBy('semester')
                               ->selectRaw('semester, count(*) as total')
                               ->orderBy('semester')
                               ->get();
        
        // Aktivitas terbaru
        $recentActivities = \App\Models\HistoryAktivitas::with(['user', 'mahasiswa'])
                           ->orderBy('tanggal_dibuat', 'desc')
                           ->take(10)
                           ->get();
        
        // Statistik aktivitas per hari (7 hari terakhir)
        $aktivitasPerHari = \App\Models\HistoryAktivitas::selectRaw('DATE(tanggal_dibuat) as tanggal, count(*) as total')
                           ->where('tanggal_dibuat', '>=', now()->subDays(7))
                           ->groupBy('tanggal')
                           ->orderBy('tanggal')
                           ->get();
        
        // Mahasiswa terbaru yang mendaftar
        $mahasiswaTerbaru = User::mahasiswa()
                           ->with('profilMahasiswa')
                           ->orderBy('created_at', 'desc')
                           ->take(5)
                           ->get();
        
        // Registrasi per bulan (6 bulan terakhir)
        $registrasiPerBulan = User::where('role', 'mahasiswa')
                             ->selectRaw('MONTH(created_at) as bulan, YEAR(created_at) as tahun, count(*) as total')
                             ->where('created_at', '>=', now()->subMonths(6))
                             ->groupBy('tahun', 'bulan')
                             ->orderBy('tahun')
                             ->orderBy('bulan')
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
            'total_users' => $totalUsers,
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
            'aktivitas_per_hari' => $aktivitasPerHari,
            'mahasiswa_terbaru' => $mahasiswaTerbaru,
            'registrasi_per_bulan' => $registrasiPerBulan,
            'login_hari_ini' => $loginHariIni,
            'aktivitas_hari_ini' => $aktivitasHariIni,
            'upload_hari_ini' => $uploadHariIni,
            'validasi_hari_ini' => $validasiHariIni,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function kelolaAkun(Request $request)
    {
        $query = User::with(['profilMahasiswa.dosenPembimbing']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('google_email', 'like', '%' . $request->search . '%');
            });
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'role') {
            $query->orderBy('role', $sortOrder);
        } elseif ($sortBy === 'created_at') {
            $query->orderBy('created_at', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination with per_page option
        $perPage = $request->get('per_page', 15);
        $showAll = ($perPage === 'all');

        if ($showAll) {
            $users = $query->get();
        } else {
            $perPage = in_array($perPage, [15, 30, 50]) ? $perPage : 15;
            $users = $query->paginate($perPage)->withQueryString();
        }

        $dospems = User::where('role', 'dospem')->get();

        // Get orphaned profil mahasiswa (profil yang tidak memiliki relasi dengan users)
        $orphanedProfils = ProfilMahasiswa::whereNotIn('id_mahasiswa', function($query) {
            $query->select('id')->from('users');
        })->with('dosenPembimbing')->get();

        return view('admin.kelola-akun', compact('users', 'dospems', 'showAll', 'orphanedProfils'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:190|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:mahasiswa,dospem,admin',
            'nip' => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // If mahasiswa, create profil
        if ($request->role === 'mahasiswa') {
            ProfilMahasiswa::create([
                'id_mahasiswa' => $user->id,
                'nim' => $request->nim ?? null,
                'prodi' => $request->prodi ?? 'Teknologi Informasi',
                'semester' => $request->semester ?? 5,
                'no_whatsapp' => $request->no_whatsapp ?? null,
                'jenis_kelamin' => $request->jenis_kelamin ?? null,
                'ipk' => $request->ipk ?? null,
                'cek_min_semester' => false,
                'cek_ipk_nilaisks' => false,
                'cek_valid_biodata' => false,
                'id_dospem' => $request->id_dospem ?? null,
            ]);
        }
        
        // If dospem, create dospem profile with NIP
        if ($request->role === 'dospem' && $request->nip) {
            Dospem::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
            ]);
        }

        return redirect()->back()->with('success', 'User berhasil dibuat!');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:190|unique:users,email,' . $id,
            'role' => 'required|in:mahasiswa,dospem,admin',
            'nim' => 'nullable|string|max:50',
            'prodi' => 'nullable|string|max:100',
            'semester' => 'nullable|integer|min:1|max:14',
            'dospem_id' => 'nullable|exists:users,id',
            'nip' => 'nullable|string|max:50',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Handle mahasiswa profile
        if ($request->role === 'mahasiswa') {
            $profilMahasiswa = $user->profilMahasiswa ?? new ProfilMahasiswa(['id_mahasiswa' => $user->id]);
            $profilMahasiswa->fill([
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'semester' => $request->semester,
                'id_dospem' => $request->dospem_id,
            ]);
            $user->profilMahasiswa()->save($profilMahasiswa);
            
            // Delete dospem profile if exists
            if ($user->dospem) {
                $user->dospem->delete();
            }
        } elseif ($request->role === 'dospem') {
            // Handle dospem profile
            $dospemProfile = $user->dospem ?? new Dospem(['user_id' => $user->id]);
            $dospemProfile->fill([
                'nip' => $request->nip,
            ]);
            $user->dospem()->save($dospemProfile);
            
            // Delete mahasiswa profile if exists
            if ($user->profilMahasiswa) {
                $user->profilMahasiswa->delete();
            }
        } else {
            // Delete both profiles if role is admin
            if ($user->profilMahasiswa) {
                $user->profilMahasiswa->delete();
            }
            if ($user->dospem) {
                $user->dospem->delete();
            }
        }

        return redirect()->back()->with('success', 'User berhasil diupdate!');
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // If user is mahasiswa, delete related data first
            if ($user->role === 'mahasiswa') {
                // Delete profil mahasiswa
                if ($user->profilMahasiswa) {
                    $user->profilMahasiswa->delete();
                }
                
                // Delete related documents
                $user->khs()->delete();
                $user->suratBalasan()->delete();
                $user->laporanPkl()->delete();
                
                // Delete assessment results if any
                if (class_exists('\App\Models\AssessmentResult')) {
                    \App\Models\AssessmentResult::where('mahasiswa_user_id', $user->id)->delete();
                }
                
                // Delete assessment responses if any
                if (class_exists('\App\Models\AssessmentResponse')) {
                    \App\Models\AssessmentResponse::where('mahasiswa_user_id', $user->id)->delete();
                }
            }
            
            // If user is dospem, update mahasiswa bimbingan
            if ($user->role === 'dospem') {
                // Remove dospem assignment from mahasiswa
                ProfilMahasiswa::where('id_dospem', $user->id)->update(['id_dospem' => null]);
                
                // Delete assessment responses where this dospem was the evaluator
                if (class_exists('\App\Models\AssessmentResponse')) {
                    \App\Models\AssessmentResponse::where('dosen_user_id', $user->id)->delete();
                }
                
                // Delete assessment results where this dospem was the decider
                if (class_exists('\App\Models\AssessmentResult')) {
                    \App\Models\AssessmentResult::where('decided_by', $user->id)->delete();
                }
            }
            
            // Delete user activity history
            $user->historyAktivitas()->delete();
            
            // Finally delete the user
            $user->delete();

            return redirect()->back()->with('success', 'User dan semua data terkait berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function deleteOrphanedProfil($id)
    {
        try {
            // Find by primary key (id_mahasiswa)
            $profil = ProfilMahasiswa::where('id_mahasiswa', $id)->firstOrFail();

            // Verify that this profil is actually orphaned
            $userExists = User::where('id', $profil->id_mahasiswa)->exists();
            if ($userExists) {
                return redirect()->back()->with('error', 'Profil ini masih terkait dengan user yang aktif!');
            }

            // Delete related KHS if any
            \App\Models\KhsManualTranskrip::where('id_mahasiswa', $profil->id_mahasiswa)->delete();

            // Delete the orphaned profil
            $profil->delete();

            return redirect()->back()->with('success', 'Profil mahasiswa orphaned berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting orphaned profil: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus profil: ' . $e->getMessage());
        }
    }

    public function bulkDeleteOrphanedProfils(Request $request)
    {
        try {
            $orphanedMahasiswaIds = ProfilMahasiswa::whereNotIn('id_mahasiswa', function($query) {
                $query->select('id')->from('users');
            })->pluck('id_mahasiswa');

            if ($orphanedMahasiswaIds->isEmpty()) {
                return redirect()->back()->with('info', 'Tidak ada profil orphaned yang ditemukan.');
            }

            // Delete related KHS for all orphaned profils
            \App\Models\KhsManualTranskrip::whereIn('id_mahasiswa', $orphanedMahasiswaIds)->delete();

            // Delete all orphaned profils
            $count = ProfilMahasiswa::whereIn('id_mahasiswa', $orphanedMahasiswaIds)->delete();

            return redirect()->back()->with('success', "Berhasil menghapus {$count} profil mahasiswa orphaned!");
        } catch (\Exception $e) {
            Log::error('Error bulk deleting orphaned profils: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus profil: ' . $e->getMessage());
        }
    }

    public function assignDospem(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'dospem_id' => 'required|exists:users,id',
        ]);

        try {
            $student = User::findOrFail($request->student_id);
            $dospem = User::findOrFail($request->dospem_id);

            if ($student->role !== 'mahasiswa') {
                return response()->json(['success' => false, 'message' => 'User bukan mahasiswa']);
            }

            if ($dospem->role !== 'dospem') {
                return response()->json(['success' => false, 'message' => 'User bukan dosen pembimbing']);
            }

            // Update or create profil mahasiswa
            $profil = $student->profilMahasiswa;
            if ($profil) {
                $profil->update(['id_dospem' => $dospem->id]);
            } else {
                ProfilMahasiswa::create([
                    'id_mahasiswa' => $student->id,
                    'id_dospem' => $dospem->id,
                    'nim' => 'TEMP_' . $student->id,
                    'prodi' => 'Teknologi Informasi',
                    'semester' => 1,
                    'jenis_kelamin' => 'L',
                    'no_whatsapp' => '081234567890',
                    'ipk' => 3.0,
                    'cek_min_semester' => false,
                    'cek_ipk_nilaisks' => false,
                    'cek_valid_biodata' => false,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Dospem berhasil ditetapkan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function kelolaMitra(Request $request)
    {
        $query = Mitra::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('kontak', 'like', "%{$search}%");
            });
        }
        
        // Sort functionality - default to rekomendasi
        $sortBy = $request->get('sort_by', 'rekomendasi');
        $sortOrder = $request->get('sort_order', 'desc');
        $isRankingSort = false;

        if ($sortBy === 'rekomendasi') {
            $mitrasToRank = $query->withCount(['mahasiswaTerpilih as mahasiswa_count'])->get();
            if ($mitrasToRank->isNotEmpty()) {
                $saw = new SawCalculationService($mitrasToRank);
                $mitra = $saw->calculate();
                $rank = 1;
                foreach ($mitra as $m) {
                    $m->rank = $rank++;
                }
            } else {
                $mitra = $mitrasToRank;
            }
            $isRankingSort = true;
        } else {
            // Handle different sort options
            switch ($sortBy) {
                case 'terbaru':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'nama':
                    $query->orderBy('nama', $sortOrder);
                    break;
                case 'jarak':
                    $query->orderBy('jarak', $sortOrder);
                    break;
                case 'honor':
                    $query->orderBy('honor', $sortOrder);
                    break;
                case 'fasilitas':
                    $query->orderBy('fasilitas', $sortOrder);
                    break;
                case 'kesesuaian':
                    $query->orderBy('kesesuaian_jurusan', $sortOrder);
                    break;
                case 'kebersihan':
                    $query->orderBy('tingkat_kebersihan', $sortOrder);
                    break;
                case 'kuota':
                    $query->orderBy('max_mahasiswa', $sortOrder);
                    break;
                default:
                    $query->orderBy('nama', 'asc');
                    break;
            }
            $mitra = $query->paginate(15)->withQueryString();
        }
        
        return view('admin.kelola-mitra', compact('mitra', 'isRankingSort'));
    }

    public function createMitra(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:100',
            'jarak' => 'required|numeric|min:0',
            'honor' => 'required|integer|in:1,5', // Hanya Tidak Ada (1) atau Ada (5)
            'fasilitas' => 'required|integer|in:1,2,3,4,5',
            'kesesuaian_jurusan' => 'required|integer|in:1,2,3,4,5',
            'tingkat_kebersihan' => 'required|integer|in:1,2,3,4,5',
            'max_mahasiswa' => 'required|integer|min:1|max:20',
        ]);

        Mitra::create($request->all());

        return redirect()->back()->with('success', 'Mitra berhasil ditambahkan!');
    }

    public function updateMitra(Request $request, $id)
    {
        $mitra = Mitra::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:150',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:100',
            'jarak' => 'required|numeric|min:0',
            'honor' => 'required|integer|in:1,5', // Hanya Tidak Ada (1) atau Ada (5)
            'fasilitas' => 'required|integer|in:1,2,3,4,5',
            'kesesuaian_jurusan' => 'required|integer|in:1,2,3,4,5',
            'tingkat_kebersihan' => 'required|integer|in:1,2,3,4,5',
            'max_mahasiswa' => 'required|integer|min:1|max:20',
        ]);

        $mitra->update($request->all());

        return redirect()->back()->with('success', 'Mitra berhasil diupdate!');
    }

    public function deleteMitra($id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->delete();

        return redirect()->back()->with('success', 'Mitra berhasil dihapus!');
    }

    public function kelolaData()
    {
        return view('admin.kelola-data');
    }

    public function validation(Request $request)
    {
        // Get all mahasiswa with their related data
        $query = ProfilMahasiswa::with(['user', 'dosenPembimbing']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%');
            })->orWhere('nim', 'like', '%' . $searchTerm . '%');
        }

        // Sort functionality
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        if ($sortBy === 'name') {
            $query->join('users', 'profil_mahasiswa.id_mahasiswa', '=', 'users.id')
                  ->orderBy('users.name', $sortOrder)
                  ->select('profil_mahasiswa.*');
        } elseif ($sortBy === 'nim') {
            $query->orderBy('nim', $sortOrder);
        } elseif ($sortBy === 'semester') {
            $query->orderBy('semester', $sortOrder);
        } elseif ($sortBy === 'ipk') {
            $query->orderBy('ipk', $sortOrder);
        }

        $mahasiswa = $query->get();

        // Legacy data for backward compatibility (if needed)
        $khs = \App\Models\Khs::with(['mahasiswa.profilMahasiswa.dosenPembimbing'])->get();
        $suratBalasan = \App\Models\SuratBalasan::with(['mahasiswa.profilMahasiswa.dosenPembimbing'])->get();
        $laporanPkl = \App\Models\LaporanPkl::with(['mahasiswa.profilMahasiswa.dosenPembimbing'])->get();
        $suratPengantar = \App\Models\SuratPengantar::with(['mahasiswa.profilMahasiswa.dosenPembimbing'])->get();

        return view('admin.validation', compact('mahasiswa', 'khs', 'suratBalasan', 'laporanPkl', 'suratPengantar'));
    }

    public function validateKhs(Request $request, $id)
    {
        $khs = \App\Models\Khs::findOrFail($id);
        $khs->status_validasi = $request->status;
        $khs->save();

        return response()->json(['success' => true]);
    }

    public function validateSuratBalasan(Request $request, $id)
    {
        $suratBalasan = \App\Models\SuratBalasan::findOrFail($id);
        $suratBalasan->status_validasi = $request->status;
        $suratBalasan->save();

        return response()->json(['success' => true]);
    }

    public function validateLaporan(Request $request, $id)
    {
        $laporanPkl = \App\Models\LaporanPkl::findOrFail($id);
        $laporanPkl->status_validasi = $request->status;
        $laporanPkl->save();

        return response()->json(['success' => true]);
    }

    public function validateSuratPengantar(Request $request, $id)
    {
        $suratPengantar = \App\Models\SuratPengantar::findOrFail($id);
        $suratPengantar->status_validasi = $request->status;
        $suratPengantar->save();

        return response()->json(['success' => true]);
    }

    private function getPendingValidationCount()
    {
        return \App\Models\Khs::menunggu()->count() + 
               \App\Models\SuratBalasan::menunggu()->count() + 
               \App\Models\LaporanPkl::menunggu()->count();
    }

    public function penilaianDosen()
    {
        // Get all assessment results with dospem info
        $results = \App\Models\AssessmentResult::with([
            'mahasiswa.profilMahasiswa.dosenPembimbing'
        ])->orderBy('created_at', 'desc')->get();

        return view('admin.penilaian-dosen', compact('results'));
    }

    public function nilaiAkhir()
    {
        // Get all assessment results for all students
        $results = \App\Models\AssessmentResult::with([
            'mahasiswa.profilMahasiswa.dosenPembimbing'
        ])->orderBy('created_at', 'desc')->get();

        return view('admin.nilai-akhir', compact('results'));
    }

    public function bulkDeleteUsers(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            $deletedCount = 0;
            foreach ($request->user_ids as $userId) {
                $user = User::find($userId);
                if ($user) {
                    // Delete related data first
                    if ($user->role === 'mahasiswa') {
                        if ($user->profilMahasiswa) {
                            $user->profilMahasiswa->delete();
                        }
                        $user->khs()->delete();
                        $user->suratBalasan()->delete();
                        $user->laporanPkl()->delete();

                        if (class_exists('\App\Models\AssessmentResult')) {
                            \App\Models\AssessmentResult::where('mahasiswa_user_id', $user->id)->delete();
                        }
                        if (class_exists('\App\Models\AssessmentResponse')) {
                            \App\Models\AssessmentResponse::where('mahasiswa_user_id', $user->id)->delete();
                        }
                    }

                    if ($user->role === 'dospem') {
                        ProfilMahasiswa::where('id_dospem', $user->id)->update(['id_dospem' => null]);
                        if (class_exists('\App\Models\AssessmentResponse')) {
                            \App\Models\AssessmentResponse::where('dosen_user_id', $user->id)->delete();
                        }
                        if (class_exists('\App\Models\AssessmentResult')) {
                            \App\Models\AssessmentResult::where('decided_by', $user->id)->delete();
                        }
                    }

                    $user->historyAktivitas()->delete();
                    $user->delete();
                    $deletedCount++;
                }
            }

            return redirect()->back()->with('success', "Berhasil menghapus {$deletedCount} user!");
        } catch (\Exception $e) {
            Log::error('Error bulk deleting users: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function bulkEditDospem(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'dospem_id' => 'required|exists:users,id',
        ]);

        try {
            $dospem = User::findOrFail($request->dospem_id);
            if ($dospem->role !== 'dospem') {
                return redirect()->back()->with('error', 'User yang dipilih bukan dosen pembimbing!');
            }

            $updatedCount = 0;
            foreach ($request->user_ids as $userId) {
                $user = User::find($userId);
                if ($user && $user->role === 'mahasiswa') {
                    $profil = $user->profilMahasiswa;
                    if ($profil) {
                        $profil->update(['id_dospem' => $request->dospem_id]);
                        $updatedCount++;
                    } else {
                        // Create profile if not exists
                        ProfilMahasiswa::create([
                            'id_mahasiswa' => $user->id,
                            'id_dospem' => $request->dospem_id,
                            'nim' => 'TEMP_' . $user->id,
                            'prodi' => 'Teknologi Informasi',
                            'semester' => 1,
                            'jenis_kelamin' => 'L',
                            'no_whatsapp' => '081234567890',
                            'ipk' => 3.0,
                            'cek_min_semester' => false,
                            'cek_ipk_nilaisks' => false,
                            'cek_valid_biodata' => false,
                        ]);
                        $updatedCount++;
                    }
                }
            }

            return redirect()->back()->with('success', "Berhasil mengubah dosen pembimbing untuk {$updatedCount} mahasiswa!");
        } catch (\Exception $e) {
            Log::error('Error bulk editing dospem: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah dosen pembimbing: ' . $e->getMessage());
        }
    }

    public function bulkResetDocuments(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            $resetCount = 0;
            foreach ($request->user_ids as $userId) {
                $user = User::find($userId);
                if ($user && $user->role === 'mahasiswa') {
                    // Delete all documents
                    $user->khs()->delete();
                    $user->suratBalasan()->delete();
                    $user->laporanPkl()->delete();

                    // Reset profile data
                    if ($user->profilMahasiswa) {
                        $user->profilMahasiswa->update([
                            'mitra_selected' => null,
                            'cek_min_semester' => false,
                            'cek_ipk_nilaisks' => false,
                            'cek_valid_biodata' => false,
                        ]);
                    }

                    $resetCount++;
                }
            }

            return redirect()->back()->with('success', "Berhasil mereset data pemberkasan untuk {$resetCount} mahasiswa!");
        } catch (\Exception $e) {
            Log::error('Error bulk resetting documents: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mereset data pemberkasan: ' . $e->getMessage());
        }
    }
}