<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\SuratBalasan;

use App\Services\SawCalculationService;

class MitraController extends Controller
{
    public function index(Request $request)
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

        $sort = $request->query('sort');

        if ($sort === 'ranking') {
            // Load with count before SAW calculation
            $mitrasToRank = $query->withCount(['mahasiswaTerpilih as mahasiswa_count'])->get();
            if ($mitrasToRank->isNotEmpty()) {
                $saw = new SawCalculationService($mitrasToRank);
                $mitra = $saw->calculate(); // This is a sorted collection with count already loaded

                // Add rank number to each mitra
                $rank = 1;
                foreach ($mitra as $m) {
                    $m->rank = $rank++;
                }
            } else {
                $mitra = $mitrasToRank; // Empty collection
            }
            $isRankingSort = true;
        } else {
            $query->withCount(['mahasiswaTerpilih as mahasiswa_count']);
            
            switch ($sort) {
                case 'jarak':
                    $query->orderBy('jarak', 'asc');
                    break;
                case 'honor':
                    $query->orderBy('honor', 'desc');
                    break;
                case 'fasilitas':
                    $query->orderBy('fasilitas', 'desc');
                    break;
                case 'kesesuaian':
                    $query->orderBy('kesesuaian_jurusan', 'desc');
                    break;
                case 'kebersihan':
                    $query->orderBy('tingkat_kebersihan', 'desc');
                    break;
                default:
                    $query->orderBy('nama', 'asc');
                    break;
            }
            
            $mitra = $query->get();
            $isRankingSort = false;
        }

        // Get current user's profil mahasiswa untuk cek mitra yang sudah dipilih
        $user = \Illuminate\Support\Facades\Auth::user();
        $profilMahasiswa = $user->profilMahasiswa ?? null;

        return view('mitra.index', compact('mitra', 'profilMahasiswa', 'isRankingSort'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:255',
            'jarak' => 'nullable|numeric|min:0',
            'honor' => 'nullable|numeric|min:0',
            'fasilitas' => 'nullable|integer|min:1|max:5',
            'kesesuaian_jurusan' => 'nullable|integer|min:1|max:5',
            'tingkat_kebersihan' => 'nullable|integer|min:1|max:5',
            'max_mahasiswa' => 'nullable|integer|min:1',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        $creatorName = $user->name;

        Mitra::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat ?? '-',
            'kontak' => $request->kontak ?? '-',
            'jarak' => $request->jarak ?? 0,
            'honor' => $request->honor ?? 3,
            'fasilitas' => $request->fasilitas ?? 3,
            'kesesuaian_jurusan' => $request->kesesuaian_jurusan ?? 3,
            'tingkat_kebersihan' => $request->tingkat_kebersihan ?? 3,
            'max_mahasiswa' => $request->max_mahasiswa ?? 10,
            'created_by' => $creatorName,
        ]);

        return redirect()->route('mitra')->with('success', 'Instansi Mitra berhasil ditambahkan!');
    }
}