<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AssessmentService;

class AssessmentResponse extends Model
{
    use HasFactory;

    protected $table = 'assessment_responses';
    
    protected $fillable = [
        'mahasiswa_user_id',
        'dosen_user_id',
        'is_final'
    ];

    protected $casts = [
        'is_final' => 'boolean'
    ];

    /**
     * Get the mahasiswa user
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_user_id');
    }

    /**
     * Get the dosen user
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_user_id');
    }

    /**
     * Get the response items
     */
    public function responseItems()
    {
        return $this->hasMany(AssessmentResponseItem::class, 'response_id');
    }

    /**
     * Get the assessment result
     */
    public function result()
    {
        return $this->hasOne(AssessmentResult::class, 'mahasiswa_user_id', 'mahasiswa_user_id');
    }

    /**
     * Get the hardcoded assessment form
     */
    public function getFormAttribute()
    {
        return AssessmentService::getAssessmentForm();
    }

    /**
     * Calculate total score
     */
    public function calculateTotalScore()
    {
        $totalScore = 0;
        $totalWeight = 0;
        
        $formItems = AssessmentService::getAssessmentFormItems();
        
        foreach ($this->responseItems as $responseItem) {
            $formItem = AssessmentService::getAssessmentFormItem($responseItem->item_id);
            
            if ($formItem && $formItem['type'] === 'numeric' && $responseItem->value_numeric !== null) {
                $totalScore += ($responseItem->value_numeric * $formItem['weight'] / 100);
                $totalWeight += $formItem['weight'];
            }
        }
        
        return $totalWeight > 0 ? ($totalScore / $totalWeight) * 100 : 0;
    }
}