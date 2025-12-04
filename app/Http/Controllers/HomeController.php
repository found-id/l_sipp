<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemSetting;
use App\Models\Mitra;

class HomeController extends Controller
{
    /**
     * Handle the root route redirect based on authentication status
     */
    public function index()
    {
        if (Auth::check()) {
            // User is authenticated, redirect to dashboard
            return redirect()->route('dashboard');
        } else {
            // User is not authenticated, show landing page
            $fontConfig = SystemSetting::getFontConfig();
            
            // Fetch top 3 Mitra based on SAW score (assuming 'saw_score' column exists or logic needed)
            // For now, let's fetch top 3 based on 'mahasiswa_terpilih_count' or just random/latest if score not available
            // If you have a specific 'saw_score' column, use orderBy('saw_score', 'desc')
            $topMitra = Mitra::withCount('mahasiswaTerpilih')
                             ->orderBy('mahasiswa_terpilih_count', 'desc') // Fallback to popularity
                             ->take(3)
                             ->get();

            return view('welcome', compact('fontConfig', 'topMitra'));
        }
    }
}
