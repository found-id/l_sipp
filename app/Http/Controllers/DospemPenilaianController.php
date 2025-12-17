<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AssessmentResponse;
use App\Models\AssessmentResponseItem;
use App\Models\AssessmentResult;
use App\Models\User;
use App\Services\AssessmentService;
use App\Services\FonnteService;

class DospemPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'dospem') {
            abort(403, 'Unauthorized');
        }
        
        // Get hardcoded assessment form
        $form = AssessmentService::getAssessmentForm();
        
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
        $query->leftJoin('assessment_results', function ($join) {
            $join->on('users.id', '=', 'assessment_results.mahasiswa_user_id');
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
        
        // Get all assessment results for all students - force fresh data from database
        $allResults = AssessmentResult::whereIn('mahasiswa_user_id', $students->pluck('id'))
            ->orderBy('updated_at', 'desc')
            ->get()
            ->fresh()
            ->keyBy('mahasiswa_user_id');
        
        if ($selectedStudentId && $students->contains('id', $selectedStudentId)) {
            $selectedStudent = $students->firstWhere('id', $selectedStudentId);

            // Clear any model cache
            AssessmentResponse::flushEventListeners();
            AssessmentResponseItem::flushEventListeners();

            // Get latest response from any assessor - force fresh from database
            $response = AssessmentResponse::where('mahasiswa_user_id', $selectedStudentId)
                ->orderBy('id', 'desc') // Use ID instead of updated_at for reliability
                ->first();

            if ($response) {
                // Force reload response items from database
                $response->load('responseItems');
                $responses = $response->responseItems->fresh()->keyBy('item_id');
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
            'items' => 'required|array',
        ]);
        
        $form = AssessmentService::getAssessmentForm();
        $mahasiswaId = $request->mahasiswa_id;
        
        // Check if mahasiswa is under this dospem
        $mahasiswaIds = \App\Models\ProfilMahasiswa::where('id_dospem', $user->id)
            ->pluck('id_mahasiswa')
            ->toArray();
        if (!in_array($mahasiswaId, $mahasiswaIds)) {
            abort(403, 'Unauthorized');
        }
        
        // Delete old responses for this student to avoid conflicts
        AssessmentResponse::where('mahasiswa_user_id', $mahasiswaId)->delete();

        // Create new response
        $response = AssessmentResponse::create([
            'mahasiswa_user_id' => $mahasiswaId,
            'dosen_user_id' => $user->id,
            'is_final' => true,
            'submitted_at' => now(),
        ]);

        // Save response items
        foreach ($request->items as $itemId => $value) {
            $item = AssessmentService::getAssessmentFormItem($itemId);

            $responseItem = [
                'response_id' => $response->id,
                'item_id' => $itemId,
            ];

            if ($item['type'] === 'numeric') {
                $responseItem['value_numeric'] = floatval($value);
            } elseif ($item['type'] === 'boolean') {
                $responseItem['value_bool'] = (bool) $value;
            } else {
                $responseItem['value_text'] = $value;
            }

            AssessmentResponseItem::create($responseItem);
        }

        // Force reload to ensure we have fresh data
        $response->refresh();
        $response->load('responseItems');
        
        // Calculate total score
        $totalScore = $this->calculateTotalScore($response);
        
        // Get grade from scale
        $grade = $this->getGradeFromScore($totalScore);
        
        // Save result
        AssessmentResult::updateOrCreate(
            [
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
        
        // Send WhatsApp notification to student
        $this->sendAssessmentNotification($mahasiswaId, $user, $totalScore, $grade);
        
        // Clear any potential cache and redirect with timestamp to force reload
        return redirect()->route('dospem.penilaian', [
                'm' => $mahasiswaId,
                '_t' => time(), // Add timestamp to force browser reload
                '_r' => rand(1000, 9999) // Additional randomness
            ])
            ->with('success', 'Penilaian berhasil disimpan!')
            ->with('saved_values', $request->items) // Pass saved values for debugging
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Clear-Site-Data', '"cache"');
    }
    
    /**
     * Send WhatsApp notification to student when assessment is submitted
     */
    private function sendAssessmentNotification($mahasiswaId, $dospem, $totalScore, $grade)
    {
        try {
            $mahasiswa = User::with('profilMahasiswa')->find($mahasiswaId);
            
            if (!$mahasiswa || !$mahasiswa->profilMahasiswa) {
                Log::info('Assessment notification skipped - no profil', ['mahasiswa_id' => $mahasiswaId]);
                return;
            }
            
            $whatsappNumber = $mahasiswa->profilMahasiswa->no_whatsapp;
            
            if (!$whatsappNumber) {
                Log::info('Assessment notification skipped - no WhatsApp number', ['mahasiswa_id' => $mahasiswaId]);
                return;
            }
            
            $fonnte = new FonnteService();
            
            $message = "🎓 *Notifikasi Penilaian PKL*\n\n";
            $message .= "Halo *{$mahasiswa->name}*,\n\n";
            $message .= "Penilaian PKL Anda telah dilakukan oleh Dosen Pembimbing.\n\n";
            $message .= "📊 *Hasil Penilaian:*\n";
            $message .= "• Nilai: *{$totalScore}*\n";
            $message .= "• Grade: *{$grade['letter']}*\n";
            $message .= "• Huruf Mutu: *{$grade['gpa']}*\n\n";
            $message .= "👨‍🏫 Dinilai oleh: *{$dospem->name}*\n\n";
            $message .= "Silakan cek hasil lengkap di dashboard Anda.\n\n";
            $message .= "Terima kasih atas kerja keras Anda! 🙏";
            
            $result = $fonnte->sendMessage($whatsappNumber, $message);
            
            Log::info('Assessment WhatsApp notification sent', [
                'mahasiswa_id' => $mahasiswaId,
                'phone' => $whatsappNumber,
                'dospem_id' => $dospem->id,
                'score' => $totalScore,
                'grade' => $grade['letter'],
                'result' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send assessment WhatsApp notification', [
                'mahasiswa_id' => $mahasiswaId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function calculateTotalScore($response)
    {
        $totalScore = 0;
        
        foreach ($response->responseItems as $item) {
            $formItem = AssessmentService::getAssessmentFormItem($item->item_id);
            $weight = $formItem['weight'];
            
            if ($formItem['type'] === 'numeric') {
                $value = $item->value_numeric ?? 0;
                $totalScore += ($value / 100) * $weight;
            } elseif ($formItem['type'] === 'boolean') {
                $value = $item->value_bool ? 100 : 0;
                $totalScore += ($value / 100) * $weight;
            }
        }
        
        return round($totalScore, 2);
    }
    
    private function getGradeFromScore($score)
    {
        $grade = AssessmentService::calculateGrade($score);
        
        return [
            'letter' => $grade['letter'],
            'gpa' => $grade['gpa_point'],
        ];
    }

    public function destroy($mahasiswaId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'dospem') {
            abort(403, 'Unauthorized');
        }

        // Cast to integer for proper comparison
        $mahasiswaId = (int) $mahasiswaId;

        // Check if mahasiswa is under this dospem
        $mahasiswaIds = \App\Models\ProfilMahasiswa::where('id_dospem', $user->id)
            ->pluck('id_mahasiswa')
            ->toArray();
        
        if (!in_array($mahasiswaId, $mahasiswaIds)) {
            abort(403, 'Unauthorized');
        }

        // Get all responses for this student first
        $responses = AssessmentResponse::where('mahasiswa_user_id', $mahasiswaId)->get();
        
        // Delete response items for each response
        foreach ($responses as $response) {
            AssessmentResponseItem::where('response_id', $response->id)->delete();
        }

        // Delete assessment responses for this student
        AssessmentResponse::where('mahasiswa_user_id', $mahasiswaId)->delete();

        // Delete assessment result for this student
        AssessmentResult::where('mahasiswa_user_id', $mahasiswaId)->delete();

        return redirect()->route('dospem.penilaian', [
                'm' => $mahasiswaId,
                '_t' => time(),
                '_r' => rand(1000, 9999)
            ])
            ->with('success', 'Penilaian berhasil dihapus/reset!')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
