<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HistoryAktivitas;

class ActivityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Admin can see all activities
            $activities = HistoryAktivitas::with(['user', 'mahasiswa'])
                ->orderBy('tanggal_dibuat', 'desc')
                ->paginate(20);
        } elseif ($user->role === 'dospem') {
            // Dosen can see activities of their students
            $mahasiswaIds = $user->mahasiswaBimbingan()->pluck('id_mahasiswa');
            $activities = HistoryAktivitas::whereIn('id_mahasiswa', $mahasiswaIds)
                ->orWhere('id_user', $user->id) // Include dosen's own activities
                ->with(['user', 'mahasiswa'])
                ->orderBy('tanggal_dibuat', 'desc')
                ->paginate(20);
        } else {
            // Mahasiswa can see their own activities
            $activities = HistoryAktivitas::where('id_mahasiswa', $user->id)
                ->orWhere('id_user', $user->id) // Include their own activities
                ->with(['user', 'mahasiswa'])
                ->orderBy('tanggal_dibuat', 'desc')
                ->paginate(20);
        }
        
        return view('activity.index', compact('activities'));
    }
}