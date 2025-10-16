<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentForm;
use App\Models\AssessmentFormItem;
use App\Models\GradeScaleStep;

class DefaultRubrikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Find or create the active assessment form
        $form = AssessmentForm::firstOrCreate(
            ['is_active' => true],
            [
                'name' => 'Rubrik Penilaian Seminar PKL',
                'description' => 'Rubrik penilaian default untuk seminar PKL.',
            ]
        );

        // Deactivate other forms just in case
        AssessmentForm::where('id', '!=', $form->id)->update(['is_active' => false]);

        // Delete old items
        $form->items()->delete();

        // Create new items
        $items = [
            [
                'label' => 'Penyajian presentasi',
                'type' => 'numeric',
                'weight' => 10,
                'sort_order' => 1,
            ],
            [
                'label' => 'Pemahaman materi',
                'type' => 'numeric',
                'weight' => 15,
                'sort_order' => 2,
            ],
            [
                'label' => 'Hasil yang dicapai',
                'type' => 'numeric',
                'weight' => 40,
                'sort_order' => 3,
            ],
            [
                'label' => 'Objektifitas menanggapi pertanyaan',
                'type' => 'numeric',
                'weight' => 20,
                'sort_order' => 4,
            ],
            [
                'label' => 'Penulisan laporan',
                'type' => 'numeric',
                'weight' => 15,
                'sort_order' => 5,
            ],
            [
                'label' => 'Komentar atau Catatan',
                'type' => 'text',
                'weight' => 0,
                'sort_order' => 6,
                'required' => false,
            ],
        ];

        foreach ($items as $itemData) {
            AssessmentFormItem::create(array_merge($itemData, ['form_id' => $form->id]));
        }

        // Create grade scale steps
        GradeScaleStep::where('scale_id', 1)->delete(); // Clear old steps for this scale
        $gradeSteps = [
            ['letter' => 'A', 'gpa_point' => 4.00, 'min_score' => 85, 'max_score' => 100, 'sort_order' => 1],
            ['letter' => 'B', 'gpa_point' => 3.00, 'min_score' => 75, 'max_score' => 84.99, 'sort_order' => 2],
            ['letter' => 'C', 'gpa_point' => 2.00, 'min_score' => 65, 'max_score' => 74.99, 'sort_order' => 3],
            ['letter' => 'D', 'gpa_point' => 1.00, 'min_score' => 55, 'max_score' => 64.99, 'sort_order' => 4],
            ['letter' => 'E', 'gpa_point' => 0.00, 'min_score' => 0, 'max_score' => 54.99, 'sort_order' => 5],
        ];

        foreach ($gradeSteps as $step) {
            GradeScaleStep::create(array_merge($step, ['scale_id' => 1]));
        }
    }
}
