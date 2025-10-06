<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentForm;
use App\Models\AssessmentFormItem;
use App\Models\GradeScaleStep;

class RubrikSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create assessment form based on old database
        $form = AssessmentForm::create([
            'name' => 'Penilaian Seminar PKL 2025',
            'description' => 'Form terpadu untuk semua aspek seminar',
            'is_active' => true,
        ]);

        // Create form items based on old database
        $items = [
            [
                'label' => 'Topik/Tema, Tujuan, Manfaat',
                'type' => 'numeric',
                'weight' => 15.00,
                'required' => true,
                'sort_order' => 1,
            ],
            [
                'label' => 'Hasil dan Pembahasan',
                'type' => 'numeric',
                'weight' => 25.00,
                'required' => true,
                'sort_order' => 2,
            ],
            [
                'label' => 'Teknik Presentasi, Penampilan dan Komunikasi',
                'type' => 'numeric',
                'weight' => 20.00,
                'required' => true,
                'sort_order' => 3,
            ],
            [
                'label' => 'Kemampuan Menjawab dan Menjelaskan',
                'type' => 'numeric',
                'weight' => 20.00,
                'required' => true,
                'sort_order' => 4,
            ],
            [
                'label' => 'Laporan PKL',
                'type' => 'numeric',
                'weight' => 20.00,
                'required' => true,
                'sort_order' => 5,
            ],
            [
                'label' => 'Kehadiran',
                'type' => 'boolean',
                'weight' => 0.00,
                'required' => false,
                'sort_order' => 6,
            ],
            [
                'label' => 'Catatan Dosen',
                'type' => 'text',
                'weight' => 0.00,
                'required' => false,
                'sort_order' => 7,
            ],
        ];

        foreach ($items as $item) {
            AssessmentFormItem::create([
                'form_id' => $form->id,
                'label' => $item['label'],
                'type' => $item['type'],
                'weight' => $item['weight'],
                'required' => $item['required'],
                'sort_order' => $item['sort_order'],
            ]);
        }

        // Create grade scale steps
        $gradeSteps = [
            ['letter' => 'A', 'gpa_point' => 4.00, 'min_score' => 85, 'max_score' => 100, 'sort_order' => 1],
            ['letter' => 'A-', 'gpa_point' => 3.70, 'min_score' => 80, 'max_score' => 84.99, 'sort_order' => 2],
            ['letter' => 'B+', 'gpa_point' => 3.30, 'min_score' => 75, 'max_score' => 79.99, 'sort_order' => 3],
            ['letter' => 'B', 'gpa_point' => 3.00, 'min_score' => 70, 'max_score' => 74.99, 'sort_order' => 4],
            ['letter' => 'B-', 'gpa_point' => 2.70, 'min_score' => 65, 'max_score' => 69.99, 'sort_order' => 5],
            ['letter' => 'C+', 'gpa_point' => 2.30, 'min_score' => 60, 'max_score' => 64.99, 'sort_order' => 6],
            ['letter' => 'C', 'gpa_point' => 2.00, 'min_score' => 55, 'max_score' => 59.99, 'sort_order' => 7],
            ['letter' => 'C-', 'gpa_point' => 1.70, 'min_score' => 50, 'max_score' => 54.99, 'sort_order' => 8],
            ['letter' => 'D', 'gpa_point' => 1.00, 'min_score' => 40, 'max_score' => 49.99, 'sort_order' => 9],
            ['letter' => 'F', 'gpa_point' => 0.00, 'min_score' => 0, 'max_score' => 39.99, 'sort_order' => 10],
        ];

        foreach ($gradeSteps as $step) {
            GradeScaleStep::create([
                'scale_id' => 1,
                'letter' => $step['letter'],
                'gpa_point' => $step['gpa_point'],
                'min_score' => $step['min_score'],
                'max_score' => $step['max_score'],
                'sort_order' => $step['sort_order'],
            ]);
        }

        $this->command->info('Sample rubrik penilaian berhasil dibuat!');
    }
}