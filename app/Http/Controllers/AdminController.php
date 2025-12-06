<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\ProfilMahasiswa;
use App\Models\Mitra;
use App\Services\SawCalculationService;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_mahasiswa' => User::mahasiswa()->count(),
            'total_dosen' => User::dosenPembimbing()->count(),
            'total_admin' => User::admin()->count(),
            'total_mitra' => Mitra::count(),
            'pending_validation' => $this->getPendingValidationCount(),
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
        } else {
            // Delete mahasiswa profile if role is not mahasiswa
            if ($user->profilMahasiswa) {
                $user->profilMahasiswa->delete();
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
        
        // Sort functionality
        $sortBy = $request->get('sort_by', 'nama');
        $sortOrder = $request->get('sort_order', 'asc');
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
            if (in_array($sortBy, ['nama', 'alamat', 'kontak', 'created_at', 'jarak', 'honor', 'fasilitas', 'kesesuaian_jurusan', 'tingkat_kebersihan', 'max_mahasiswa'])) {
                $query->orderBy($sortBy, $sortOrder);
            } else {
                $query->orderBy('nama', 'asc');
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