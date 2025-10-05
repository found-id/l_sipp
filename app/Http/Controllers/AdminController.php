<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ProfilMahasiswa;
use App\Models\Mitra;

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
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if ($sortBy === 'role') {
            $query->orderBy('role', $sortOrder);
        } elseif ($sortBy === 'created_at') {
            $query->orderBy('created_at', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $users = $query->paginate(15);
        $dospems = User::where('role', 'dospem')->get();
        
        return view('admin.kelola-akun', compact('users', 'dospems'));
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
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }

    public function kelolaMitra()
    {
        $mitra = Mitra::paginate(15);
        return view('admin.kelola-mitra', compact('mitra'));
    }

    public function createMitra(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:100',
        ]);

        Mitra::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
        ]);

        return redirect()->back()->with('success', 'Mitra berhasil ditambahkan!');
    }

    public function updateMitra(Request $request, $id)
    {
        $mitra = Mitra::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:150',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:100',
        ]);

        $mitra->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
        ]);

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

    public function validation()
    {
        $khs = \App\Models\Khs::with(['mahasiswa.profilMahasiswa.dosenPembimbing'])->get();
        $suratBalasan = \App\Models\SuratBalasan::with(['mahasiswa.profilMahasiswa.dosenPembimbing'])->get();
        $laporanPkl = \App\Models\LaporanPkl::with(['mahasiswa.profilMahasiswa.dosenPembimbing'])->get();
        
        return view('admin.validation', compact('khs', 'suratBalasan', 'laporanPkl'));
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

    private function getPendingValidationCount()
    {
        return \App\Models\Khs::menunggu()->count() + 
               \App\Models\SuratBalasan::menunggu()->count() + 
               \App\Models\LaporanPkl::menunggu()->count();
    }
}