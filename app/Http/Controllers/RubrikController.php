<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AssessmentResponse;
use App\Models\AssessmentResult;
use App\Models\AssessmentResponseItem;
use App\Services\AssessmentService;

class RubrikController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $form = AssessmentService::getAssessmentForm();

        if (!$form) {
            return view('admin.penilaian.index', [
                'form' => null,
                'students' => collect(),
                'lecturers' => collect(),
                'selectedStudent' => null,
                'selectedDospem' => null,
            ]);
        }

        $sort = $request->get('sort', 'status');
        $selectedDospemId = $request->get('dospem_id');
        $lecturers = collect();
        $selectedDospem = null;
        $students = collect();

        if ($sort === 'dospem_status' && !$selectedDospemId) {
            // View: List of Lecturers
            $lecturers = User::where('role', 'dospem')
                ->whereHas('mahasiswaBimbingan')
                ->withCount([
                    'mahasiswaBimbingan as total_mahasiswa',
                    'mahasiswaBimbingan as dinilai_count' => function ($query) {
                        $query->whereHas('user.assessmentResults');
                    }
                ])
                ->get();
        } else {
            // View: List of Students
            $query = User::where('role', 'mahasiswa')
                ->whereHas('profilMahasiswa');

            if ($sort === 'dospem_status' && $selectedDospemId) {
                $query->whereHas('profilMahasiswa', function ($q) use ($selectedDospemId) {
                    $q->where('id_dospem', $selectedDospemId);
                });
                $selectedDospem = User::find($selectedDospemId);
            }

            $students = $query->with(['profilMahasiswa', 'assessmentResults'])->get();
        }

        $selectedStudentId = $request->get('student_id');
        $responses = collect();
        $results = null;
        $selectedStudent = null;

        // Get all results for display
        $allResults = AssessmentResult::with('mahasiswa')
            ->get()
            ->keyBy('mahasiswa_user_id');

        if ($selectedStudentId && $students->contains('id', $selectedStudentId)) {
            $selectedStudent = $students->firstWhere('id', $selectedStudentId);
            
            $response = AssessmentResponse::where('mahasiswa_user_id', $selectedStudentId)
                ->with('responseItems')
                ->first();
            
            if ($response) {
                $responses = $response->responseItems->keyBy('item_id');
            }
            
            $results = $allResults->get($selectedStudentId);
        }
        
        return view('admin.penilaian.index', compact(
            'form', 'students', 'selectedStudent', 'responses', 'results', 'allResults', 'lecturers', 'selectedDospem'
        ));
    }
    
    // Form management methods removed - form is now hardcoded in AssessmentService
    // Grade scale management methods removed - grade scale is now hardcoded in AssessmentService
}