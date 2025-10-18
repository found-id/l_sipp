<?php

namespace App\Services;

class AssessmentService
{
    /**
     * Get the hardcoded assessment form
     */
    public static function getAssessmentForm()
    {
        return [
            'id' => 1,
            'name' => 'Penilaian Seminar PKL 2025',
            'description' => 'Form terpadu untuk semua aspek seminar',
            'is_active' => true,
            'items' => self::getAssessmentFormItems()
        ];
    }

    /**
     * Get the hardcoded assessment form items
     */
    public static function getAssessmentFormItems()
    {
        return [
            [
                'id' => 27,
                'form_id' => 1,
                'label' => 'Penyajian presentasi',
                'type' => 'numeric',
                'weight' => 10.00,
                'required' => true,
                'sort_order' => 1
            ],
            [
                'id' => 28,
                'form_id' => 1,
                'label' => 'Pemahaman materi',
                'type' => 'numeric',
                'weight' => 15.00,
                'required' => true,
                'sort_order' => 2
            ],
            [
                'id' => 29,
                'form_id' => 1,
                'label' => 'Hasil yang dicapai',
                'type' => 'numeric',
                'weight' => 40.00,
                'required' => true,
                'sort_order' => 3
            ],
            [
                'id' => 30,
                'form_id' => 1,
                'label' => 'Objektifitas menanggapi pertanyaan',
                'type' => 'numeric',
                'weight' => 20.00,
                'required' => true,
                'sort_order' => 4
            ],
            [
                'id' => 31,
                'form_id' => 1,
                'label' => 'Penulisan laporan',
                'type' => 'numeric',
                'weight' => 15.00,
                'required' => true,
                'sort_order' => 5
            ],
            [
                'id' => 32,
                'form_id' => 1,
                'label' => 'Komentar atau Catatan',
                'type' => 'text',
                'weight' => 0.00,
                'required' => false,
                'sort_order' => 6
            ]
        ];
    }

    /**
     * Get the hardcoded grade scale steps
     */
    public static function getGradeScaleSteps()
    {
        return [
            [
                'id' => 11,
                'scale_id' => 1,
                'letter' => 'A',
                'gpa_point' => 4.00,
                'min_score' => 85.00,
                'max_score' => 100.00,
                'sort_order' => 1
            ],
            [
                'id' => 12,
                'scale_id' => 1,
                'letter' => 'B',
                'gpa_point' => 3.00,
                'min_score' => 75.00,
                'max_score' => 84.99,
                'sort_order' => 2
            ],
            [
                'id' => 13,
                'scale_id' => 1,
                'letter' => 'C',
                'gpa_point' => 2.00,
                'min_score' => 65.00,
                'max_score' => 74.99,
                'sort_order' => 3
            ],
            [
                'id' => 14,
                'scale_id' => 1,
                'letter' => 'D',
                'gpa_point' => 1.00,
                'min_score' => 55.00,
                'max_score' => 64.99,
                'sort_order' => 4
            ],
            [
                'id' => 15,
                'scale_id' => 1,
                'letter' => 'E',
                'gpa_point' => 0.00,
                'min_score' => 0.00,
                'max_score' => 54.99,
                'sort_order' => 5
            ]
        ];
    }

    /**
     * Calculate grade based on total percentage
     */
    public static function calculateGrade($totalPercent)
    {
        $gradeSteps = self::getGradeScaleSteps();
        
        foreach ($gradeSteps as $step) {
            if ($totalPercent >= $step['min_score'] && $totalPercent <= $step['max_score']) {
                return [
                    'letter' => $step['letter'],
                    'gpa_point' => $step['gpa_point']
                ];
            }
        }
        
        return [
            'letter' => 'E',
            'gpa_point' => 0.00
        ];
    }

    /**
     * Get assessment form item by ID
     */
    public static function getAssessmentFormItem($itemId)
    {
        $items = self::getAssessmentFormItems();
        
        foreach ($items as $item) {
            if ($item['id'] == $itemId) {
                return $item;
            }
        }
        
        return null;
    }
}
