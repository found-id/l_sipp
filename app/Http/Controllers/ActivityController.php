<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HistoryAktivitas;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = HistoryAktivitas::query();
        
        if ($user->role === 'admin') {
            // Admin can see all activities
            $query->with(['user', 'mahasiswa']);
        } elseif ($user->role === 'dospem') {
            // Dosen can see activities of their students
            $mahasiswaIds = \App\Models\ProfilMahasiswa::where('id_dospem', $user->id)
                ->pluck('id_mahasiswa')
                ->toArray();
            if (!empty($mahasiswaIds)) {
                $query->whereIn('id_mahasiswa', $mahasiswaIds);
            }
            $query->orWhere('id_user', $user->id) // Include dosen's own activities
                  ->with(['user', 'mahasiswa']);
        } else {
            // Mahasiswa can see their own activities
            $query->where('id_mahasiswa', $user->id)
                  ->orWhere('id_user', $user->id) // Include their own activities
                  ->with(['user', 'mahasiswa']);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('mahasiswa', function($mahasiswaQuery) use ($search) {
                    $mahasiswaQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('tipe', 'like', "%{$search}%");
            });
        }
        
        // Sort functionality
        $sortBy = $request->get('sort_by', 'tanggal_dibuat');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['tanggal_dibuat', 'tipe'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $activities = $query->paginate(20)->withQueryString();
        
        return view('activity.index', compact('activities'));
    }
}