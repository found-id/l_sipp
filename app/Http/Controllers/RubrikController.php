<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\AssessmentResponse;
use App\Models\AssessmentResult;
use App\Models\AssessmentResponseItem;
use App\Services\AssessmentService;
use App\Services\FonnteService;

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

            $students = $query->with(['profilMahasiswa.dosenPembimbing', 'assessmentResults'])->get();
        }

        $selectedStudentId = $request->get('m') ?? $request->get('student_id');
        $responses = collect();
        $results = null;
        $selectedStudent = null;

        // Get all results for display - force fresh data from database
        $allResults = AssessmentResult::with('mahasiswa')
            ->whereIn('mahasiswa_user_id', $students->pluck('id'))
            ->orderBy('updated_at', 'desc')
            ->get()
            ->fresh()
            ->keyBy('mahasiswa_user_id');

        if ($selectedStudentId && $students->contains('id', $selectedStudentId)) {
            $selectedStudent = $students->firstWhere('id', $selectedStudentId);

            // Get latest response from database
            $response = AssessmentResponse::where('mahasiswa_user_id', $selectedStudentId)
                ->with('responseItems')
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($response) {
                $responses = $response->responseItems->keyBy('item_id');
            }

            // Get latest result for selected student
            $results = $allResults->get($selectedStudentId);
        }
        
        return view('admin.penilaian.index', compact(
            'form', 'students', 'selectedStudent', 'responses', 'results', 'allResults', 'lecturers', 'selectedDospem'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'items' => 'required|array',
        ]);

        $form = AssessmentService::getAssessmentForm();
        $mahasiswaId = $request->mahasiswa_id;

        // Admin can assess any student, no need to check dospem relationship

        // Create or update response (use admin's ID as dosen_user_id)
        $response = AssessmentResponse::updateOrCreate(
            [
                'mahasiswa_user_id' => $mahasiswaId,
                'dosen_user_id' => $user->id, // Admin acts as assessor
            ],
            [
                'is_final' => true,
                'submitted_at' => now(),
            ]
        );

        // Save response items
        foreach ($request->items as $itemId => $value) {
            $item = AssessmentService::getAssessmentFormItem($itemId);

            $responseItem = [
                'response_id' => $response->id,
                'item_id' => $itemId,
            ];

            if ($item['type'] === 'numeric') {
                $responseItem['value_numeric'] = $value;
            } elseif ($item['type'] === 'boolean') {
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

        // Send WhatsApp notifications to mahasiswa and dospem
        $this->sendAdminAssessmentNotification($mahasiswaId, $totalScore, $grade);

        // Redirect back to the same student with success message
        return redirect()->route('admin.rubrik.index', array_merge(
            $request->query(),
            ['m' => $mahasiswaId]
        ))
        ->with('success', 'Penilaian berhasil disimpan!')
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }
    
    /**
     * Send WhatsApp notification to mahasiswa and dospem when admin submits assessment
     */
    private function sendAdminAssessmentNotification($mahasiswaId, $totalScore, $grade)
    {
        try {
            $mahasiswa = User::with(['profilMahasiswa.dosenPembimbing.dospem'])->find($mahasiswaId);
            
            if (!$mahasiswa || !$mahasiswa->profilMahasiswa) {
                Log::info('Admin assessment notification skipped - no profil', ['mahasiswa_id' => $mahasiswaId]);
                return;
            }
            
            $fonnte = new FonnteService();
            
            // 1. Send notification to Mahasiswa
            $mahasiswaWhatsapp = $mahasiswa->profilMahasiswa->no_whatsapp;
            if ($mahasiswaWhatsapp) {
                $message = "🎓 *Notifikasi Penilaian PKL*\n\n";
                $message .= "Halo *{$mahasiswa->name}*,\n\n";
                $message .= "Penilaian PKL Anda telah dilakukan oleh Admin.\n\n";
                $message .= "📊 *Hasil Penilaian:*\n";
                $message .= "• Nilai: *{$totalScore}*\n";
                $message .= "• Grade: *{$grade['letter']}*\n";
                $message .= "• Huruf Mutu: *{$grade['gpa']}*\n\n";
                $message .= "Silakan cek hasil lengkap di dashboard Anda.\n\n";
                $message .= "Terima kasih atas kerja keras Anda! 🙏";
                
                $fonnte->sendMessage($mahasiswaWhatsapp, $message);
                
                Log::info('Admin assessment notification sent to mahasiswa', [
                    'mahasiswa_id' => $mahasiswaId,
                    'phone' => $mahasiswaWhatsapp,
                    'score' => $totalScore,
                    'grade' => $grade['letter']
                ]);
            }
            
            // 2. Send notification to Dospem
            $dospem = $mahasiswa->profilMahasiswa->dosenPembimbing;
            if ($dospem && $dospem->dospem && $dospem->dospem->no_telepon) {
                $dospemWhatsapp = $dospem->dospem->no_telepon;
                
                $message = "📋 *Notifikasi Penilaian Mahasiswa Bimbingan*\n\n";
                $message .= "Halo *{$dospem->name}*,\n\n";
                $message .= "Mahasiswa bimbingan Anda telah dinilai oleh Admin:\n\n";
                $message .= "👤 *Mahasiswa:* {$mahasiswa->name}\n";
                $message .= "📝 *NIM:* {$mahasiswa->profilMahasiswa->nim}\n\n";
                $message .= "📊 *Hasil Penilaian:*\n";
                $message .= "• Nilai: *{$totalScore}*\n";
                $message .= "• Grade: *{$grade['letter']}*\n";
                $message .= "• Huruf Mutu: *{$grade['gpa']}*\n\n";
                $message .= "Terima kasih! 🙏";
                
                $fonnte->sendMessage($dospemWhatsapp, $message);
                
                Log::info('Admin assessment notification sent to dospem', [
                    'dospem_id' => $dospem->id,
                    'phone' => $dospemWhatsapp,
                    'mahasiswa_name' => $mahasiswa->name,
                    'score' => $totalScore
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to send admin assessment WhatsApp notification', [
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

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Cast to integer for proper comparison
        $mahasiswaId = (int) $mahasiswaId;

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

        // Build redirect URL with current query params
        $queryParams = request()->query->all();
        $queryParams['m'] = $mahasiswaId;
        $queryParams['_t'] = time();
        $queryParams['_r'] = rand(1000, 9999);

        return redirect()->route('admin.rubrik.index', $queryParams)
            ->with('success', 'Penilaian berhasil dihapus/reset!')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // Form management methods removed - form is now hardcoded in AssessmentService
    // Grade scale management methods removed - grade scale is now hardcoded in AssessmentService
}