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

        $isRankingSort = $request->query('sort') === 'ranking';

        if ($isRankingSort) {
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
        } else {
            $mitra = $query->withCount(['mahasiswaTerpilih as mahasiswa_count'])
                          ->orderBy('nama')
                          ->get();
        }

        // Get current user's profil mahasiswa untuk cek mitra yang sudah dipilih
        $user = \Illuminate\Support\Facades\Auth::user();
        $profilMahasiswa = $user->profilMahasiswa ?? null;

        return view('mitra.index', compact('mitra', 'profilMahasiswa', 'isRankingSort'));
    }
}