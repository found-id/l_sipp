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

        $sort = $request->query('sort', 'ranking'); // Default to ranking

        if ($sort === 'ranking' || $sort === null) {
            // Load with count
            $allMitras = $query->withCount(['mahasiswaTerpilih as mahasiswa_count'])->get();
            
            // Separate into rankable (has criteria) and unrankable (no criteria/user added without criteria)
            $rankableMitras = $allMitras->filter(function ($m) {
                return !is_null($m->honor) && !is_null($m->fasilitas) && 
                       !is_null($m->kesesuaian_jurusan) && !is_null($m->tingkat_kebersihan);
            });
            
            $unrankableMitras = $allMitras->reject(function ($m) {
                return !is_null($m->honor) && !is_null($m->fasilitas) && 
                       !is_null($m->kesesuaian_jurusan) && !is_null($m->tingkat_kebersihan);
            });

            if ($rankableMitras->isNotEmpty()) {
                $saw = new SawCalculationService($rankableMitras);
                $rankedMitras = $saw->calculate(); // Returns sorted collection

                // Add rank number
                $rank = 1;
                foreach ($rankedMitras as $m) {
                    $m->rank = $rank++;
                }
                
                // Merge: Ranked first, then Unranked
                $mitra = $rankedMitras->merge($unrankableMitras);
            } else {
                $mitra = $unrankableMitras;
            }
            $isRankingSort = true;
        } else {
            $query->withCount(['mahasiswaTerpilih as mahasiswa_count']);
            
            // For standard DB sorts, we want "Has Criteria" first, then "No Criteria" (which usually means user added)
            // MySQL: boolean expression (column IS NOT NULL) is 1 if true, 0 if false. DESC puts 1 first.
            $query->orderByRaw('(honor IS NOT NULL AND fasilitas IS NOT NULL) DESC');

            switch ($sort) {
                case 'terbaru':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'abjad':
                    $query->orderBy('nama', 'asc');
                    break;
                case 'jarak':
                    // Push nulls to bottom for ascending sort
                    $query->orderByRaw('CASE WHEN jarak IS NULL THEN 1 ELSE 0 END, jarak ASC');
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
        // $creatorName = $user->name; // No longer used for created_by

        Mitra::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat ?? '-',
            'kontak' => $request->kontak ?? '-',
            'jarak' => $request->jarak ?? null,
            'honor' => $request->honor ?? null,
            'fasilitas' => $request->fasilitas ?? null,
            'kesesuaian_jurusan' => $request->kesesuaian_jurusan ?? null,
            'tingkat_kebersihan' => $request->tingkat_kebersihan ?? null,
            'max_mahasiswa' => $request->max_mahasiswa ?? 4,
            'created_by' => $user->id,
        ]);

        return redirect()->route('mitra')->with('success', 'Instansi Mitra berhasil ditambahkan!');
    }
}