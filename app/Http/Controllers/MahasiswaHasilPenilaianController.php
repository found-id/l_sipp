<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssessmentResult;
use App\Models\AssessmentResponse;
use App\Models\AssessmentResponseItem;
use App\Services\AssessmentService;
use Illuminate\Support\Facades\Auth;

class MahasiswaHasilPenilaianController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'mahasiswa') {
            abort(403, 'Unauthorized');
        }
        
        // Get assessment results for this student
        $results = AssessmentResult::where('mahasiswa_user_id', $user->id)
            ->with(['decidedBy'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get all assessment responses (including drafts)
        $responses = AssessmentResponse::where('mahasiswa_user_id', $user->id)
            ->with(['dosen', 'responseItems'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get hardcoded assessment form
        $form = AssessmentService::getAssessmentForm();
        
        return view('mahasiswa.hasil-penilaian', compact('results', 'responses', 'form'));
    }
}
