<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssessmentForm;
use App\Models\AssessmentFormItem;
use App\Models\GradeScaleStep;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AssessmentResponse;
use App\Models\AssessmentResult;
use App\Models\AssessmentResponseItem;

class RubrikController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $form = AssessmentForm::where('is_active', true)->with('items')->first();

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
                    'mahasiswaBimbingan as dinilai_count' => function ($query) use ($form) {
                        $query->whereHas('user.assessmentResults', function ($q) use ($form) {
                            $q->where('form_id', $form->id);
                        });
                    }
                ])
                ->orderBy('name')
                ->get();
        } else {
            // View: List of Students (default, or for a specific lecturer)
            $query = User::where('users.role', 'mahasiswa')->with('profilMahasiswa');

            if ($sort === 'dospem_status' && $selectedDospemId) {
                $query->whereHas('profilMahasiswa', function ($q) use ($selectedDospemId) {
                    $q->where('id_dospem', $selectedDospemId);
                });
                $selectedDospem = User::find($selectedDospemId);
            }

            // Apply sorting
            switch ($sort) {
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'nim_asc':
                    $query->join('profil_mahasiswa', 'users.id', '=', 'profil_mahasiswa.id_mahasiswa')
                          ->orderBy('profil_mahasiswa.nim', 'asc');
                    break;
                case 'nim_desc':
                    $query->join('profil_mahasiswa', 'users.id', '=', 'profil_mahasiswa.id_mahasiswa')
                          ->orderBy('profil_mahasiswa.nim', 'desc');
                    break;
                case 'dospem_status': // This case now only handles sorting for a selected dospem's students
                case 'status':
                default:
                    $query->leftJoin('assessment_results', function ($join) use ($form) {
                            $join->on('assessment_results.mahasiswa_user_id', '=', 'users.id')
                                 ->where('assessment_results.form_id', '=', $form->id);
                          })
                          ->orderByRaw('assessment_results.id IS NULL DESC')
                          ->orderBy('users.name', 'asc');
                    break;
            }
            
            $students = $query->select('users.*')->get();
        }

        $selectedStudentId = $request->get('m');
        $selectedStudent = null;
        $responses = collect();
        $results = collect();
        
        $allResults = AssessmentResult::where('form_id', $form->id)
            ->whereIn('mahasiswa_user_id', $students->pluck('id'))
            ->get()
            ->keyBy('mahasiswa_user_id');

        if ($selectedStudentId && $students->contains('id', $selectedStudentId)) {
            $selectedStudent = $students->firstWhere('id', $selectedStudentId);
            
            $response = AssessmentResponse::where('form_id', $form->id)
                ->where('mahasiswa_user_id', $selectedStudentId)
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
    
    public function createForm(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // Deactivate all other forms
        AssessmentForm::where('is_active', true)->update(['is_active' => false]);
        
        $form = AssessmentForm::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);
        
        return redirect()->route('admin.rubrik.edit', $form->id)
            ->with('success', 'Form penilaian berhasil dibuat!');
    }
    
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $form = AssessmentForm::with('items')->findOrFail($id);
        $items = $form->items()->orderBy('sort_order')->get();
        
        return view('admin.rubrik.edit', compact('form', 'items'));
    }
    
    public function addItem(Request $request, $formId)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:numeric,boolean,text',
            'weight' => 'required|numeric|min:0|max:100',
        ]);
        
        $form = AssessmentForm::findOrFail($formId);
        
        $nextOrder = $form->items()->max('sort_order') + 1;
        
        AssessmentFormItem::create([
            'form_id' => $formId,
            'label' => $request->label,
            'type' => $request->type,
            'weight' => $request->weight,
            'sort_order' => $nextOrder,
        ]);
        
        return redirect()->back()->with('success', 'Item berhasil ditambahkan!');
    }
    
    public function toggleForm($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $form = AssessmentForm::findOrFail($id);
        
        if ($form->is_active) {
            $form->update(['is_active' => false]);
            $message = 'Form dinonaktifkan!';
        } else {
            // Deactivate all other forms first
            AssessmentForm::where('is_active', true)->update(['is_active' => false]);
            $form->update(['is_active' => true]);
            $message = 'Form diaktifkan!';
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function deleteForm($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $form = AssessmentForm::findOrFail($id);
        $form->delete();
        
        return redirect()->route('admin.rubrik.index')
            ->with('success', 'Form berhasil dihapus!');
    }
    
    public function updateItem(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:numeric,boolean,text',
            'weight' => 'nullable|numeric|min:0|max:100',
            'required' => 'boolean'
        ]);
        
        $item = AssessmentFormItem::findOrFail($id);
        $item->update([
            'label' => $request->label,
            'type' => $request->type,
            'weight' => $request->weight ?? 0,
            'required' => $request->has('required')
        ]);
        
        return redirect()->back()->with('success', 'Item berhasil diperbarui');
    }
    
    public function updateOrder(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|integer',
            'updates.*.sort_order' => 'required|integer|min:1'
        ]);
        
        try {
            foreach ($request->updates as $update) {
                AssessmentFormItem::where('id', $update['id'])
                    ->update(['sort_order' => $update['sort_order']]);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    public function deleteItem($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        try {
            $item = AssessmentFormItem::findOrFail($id);
            $item->delete();
            
            return response()->json(['success' => true, 'message' => 'Item berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus item: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'form_id' => 'required|exists:assessment_forms,id',
            'items' => 'required|array',
        ]);
        
        $form = AssessmentForm::findOrFail($request->form_id);
        $mahasiswaId = $request->mahasiswa_id;
        
        $response = AssessmentResponse::updateOrCreate(
            [
                'form_id' => $form->id,
                'mahasiswa_user_id' => $mahasiswaId,
                'dosen_user_id' => $user->id, // Admin's ID will be stored as the assessor
            ],
            [
                'is_final' => true,
                'submitted_at' => now(),
            ]
        );
        
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
        
        $totalScore = $this->calculateTotalScore($response);
        
        $grade = $this->getGradeFromScore($totalScore);
        
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
        $gradeStep = GradeScaleStep::where('scale_id', 1) // Assuming scale_id 1 is the default
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
