<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\SuratBalasan;

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
        
        // Get mitra with count of applications
        $mitra = $query->withCount(['suratBalasan as total_applications'])
                      ->orderBy('nama')
                      ->get();
        
        return view('mitra.index', compact('mitra'));
    }
}