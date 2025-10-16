<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AssessmentForm;
use App\Models\AssessmentFormItem;
use App\Models\AssessmentResponse;
use App\Models\AssessmentResponseItem;
use App\Models\AssessmentResult;
use App\Models\GradeScaleStep;

class DospemPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'dospem') {
            abort(403, 'Unauthorized');
        }
        
        // Get active assessment form
        $form = AssessmentForm::where('is_active', true)->with('items')->first();
        
        if (!$form) {
            return view('dospem.penilaian.index', [
                'form' => null,
                'students' => collect(),
                'selectedStudent' => null,
                'items' => collect(),
                'responses' => collect(),
                'results' => collect()
            ]);
        }
        
        // Get mahasiswa bimbingan
        $mahasiswaIds = \App\Models\ProfilMahasiswa::where('id_dospem', $user->id)
            ->pluck('id_mahasiswa')
            ->toArray();
        
        $query = \App\Models\User::whereIn('users.id', $mahasiswaIds)->with('profilMahasiswa');
            
        // Default sort by assessment status (unevaluated first), then by name
        $query->leftJoin('assessment_results', function ($join) use ($form) {
            $join->on('users.id', '=', 'assessment_results.mahasiswa_user_id')
                 ->where('assessment_results.form_id', $form->id);
        })
        ->orderByRaw('assessment_results.id IS NULL DESC')
        ->orderBy('users.name', 'asc')
        ->select('users.*');
        
        $students = $query->get();
        
        // Get selected student
        $selectedStudentId = $request->get('m');
        $selectedStudent = null;
        $responses = collect();
        $results = collect();
        
        // Get all assessment results for all students
        $allResults = AssessmentResult::where('form_id', $form->id)
            ->whereIn('mahasiswa_user_id', $students->pluck('id'))
            ->get()
            ->keyBy('mahasiswa_user_id');
        
        if ($selectedStudentId && $students->contains('id', $selectedStudentId)) {
            $selectedStudent = $students->firstWhere('id', $selectedStudentId);
            
            // Get existing response
            $response = AssessmentResponse::where('form_id', $form->id)
                ->where('mahasiswa_user_id', $selectedStudentId)
                ->where('dosen_user_id', $user->id)
                ->with('responseItems')
                ->first();
            
            if ($response) {
                $responses = $response->responseItems->keyBy('item_id');
            }
            
            // Get existing results for selected student
            $results = $allResults->get($selectedStudentId);
        }
        
        return view('dospem.penilaian.index', compact(
            'form', 'students', 'selectedStudent', 'responses', 'results', 'allResults'
        ));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'dospem') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'form_id' => 'required|exists:assessment_forms,id',
            'items' => 'required|array',
        ]);
        
        $form = AssessmentForm::findOrFail($request->form_id);
        $mahasiswaId = $request->mahasiswa_id;
        
        // Check if mahasiswa is under this dospem
        $mahasiswaIds = \App\Models\ProfilMahasiswa::where('id_dospem', $user->id)
            ->pluck('id_mahasiswa')
            ->toArray();
        if (!in_array($mahasiswaId, $mahasiswaIds)) {
            abort(403, 'Unauthorized');
        }
        
        // Create or update response
        $response = AssessmentResponse::updateOrCreate(
            [
                'form_id' => $form->id,
                'mahasiswa_user_id' => $mahasiswaId,
                'dosen_user_id' => $user->id,
            ],
            [
                'is_final' => true,
                'submitted_at' => now(),
            ]
        );
        
        // Save response items
        foreach ($request->items as $itemId => $value) {
            $item = AssessmentFormItem::findOrFail($itemId);
            
            $responseItem = [
                'response_id' => $response->id,
                'item_id' => $itemId,
            ];
            
            if ($item->type === 'numeric') {
                $responseItem['value_numeric'] = $value;
            } elseif ($item->type === 'boolean') {
                $responseItem['value_bool'] = (bool) $value;
            } else {
                $responseItem['value_text'] = $value;
            }
            
            AssessmentResponseItem::updateOrCreate(
                [
                    'response_id' => $response->id,
                    'item_id' => $itemId,
                ],
                $responseItem
            );
        }
        
        // Calculate total score
        $totalScore = $this->calculateTotalScore($response);
        
        // Get grade from scale
        $grade = $this->getGradeFromScore($totalScore);
        
        // Save result
        AssessmentResult::updateOrCreate(
            [
                'form_id' => $form->id,
                'mahasiswa_user_id' => $mahasiswaId,
            ],
            [
                'total_percent' => $totalScore,
                'letter_grade' => $grade['letter'],
                'gpa_point' => $grade['gpa'],
                'decided_at' => now(),
                'decided_by' => $user->id,
            ]
        );
        
        return redirect()->back()->with('success', 'Penilaian berhasil disimpan!');
    }
    
    private function calculateTotalScore($response)
    {
        $totalScore = 0;
        
        foreach ($response->responseItems as $item) {
            $formItem = $item->item;
            $weight = $formItem->weight;
            
            if ($formItem->type === 'numeric') {
                $value = $item->value_numeric ?? 0;
                $totalScore += ($value / 100) * $weight;
            } elseif ($formItem->type === 'boolean') {
                $value = $item->value_bool ? 100 : 0;
                $totalScore += ($value / 100) * $weight;
            }
        }
        
        return round($totalScore, 2);
    }
    
    private function getGradeFromScore($score)
    {
        $gradeStep = GradeScaleStep::where('scale_id', 1)
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->orderBy('sort_order')
            ->first();
        
        if ($gradeStep) {
            return [
                'letter' => $gradeStep->letter,
                'gpa' => $gradeStep->gpa_point,
            ];
        }
        
        return [
            'letter' => 'F',
            'gpa' => 0,
        ];
    }
}
