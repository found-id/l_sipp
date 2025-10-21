<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AssessmentService;

class AssessmentResult extends Model
{
    use HasFactory;

    protected $table = 'assessment_results';
    
    protected $fillable = [
        'mahasiswa_user_id',
        'total_percent',
        'letter_grade',
        'gpa_point',
        'decided_by'
    ];

    protected $casts = [
        'total_percent' => 'decimal:2',
        'gpa_point' => 'decimal:2',
        'decided_at' => 'datetime'
    ];

    /**
     * Get the mahasiswa user
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_user_id');
    }

    /**
     * Get the user who decided the result
     */
    public function decidedBy()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }


    /**
     * Get the hardcoded assessment form
     */
    public function getFormAttribute()
    {
        return AssessmentService::getAssessmentForm();
    }

    /**
     * Calculate and set grade based on total percentage
     */
    public function calculateGrade()
    {
        $grade = AssessmentService::calculateGrade($this->total_percent);
        $this->letter_grade = $grade['letter'];
        $this->gpa_point = $grade['gpa_point'];
        return $this;
    }
}