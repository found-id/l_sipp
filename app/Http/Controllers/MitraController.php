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

        if ($request->query('sort') === 'ranking') {
            $mitrasToRank = $query->get();
            if ($mitrasToRank->isNotEmpty()) {
                $saw = new SawCalculationService($mitrasToRank);
                $mitra = $saw->calculate(); // This is a sorted collection
                // Manually load the count of applications for the ranked collection
                $mitra->loadCount('suratBalasan as total_applications');
            } else {
                $mitra = $mitrasToRank; // Empty collection
            }
        } else {
            $mitra = $query->withCount(['suratBalasan as total_applications'])
                          ->orderBy('nama')
                          ->get();
        }
        
        return view('mitra.index', compact('mitra'));
    }
}