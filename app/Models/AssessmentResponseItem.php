<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentResponseItem extends Model
{
    use HasFactory;

    protected $table = 'assessment_response_items';
    
    protected $fillable = [
        'response_id',
        'item_id',
        'value_numeric',
        'value_bool',
        'value_text'
    ];

    protected $casts = [
        'value_numeric' => 'decimal:2',
        'value_bool' => 'boolean'
    ];

    /**
     * Get the response
     */
    public function response()
    {
        return $this->belongsTo(AssessmentResponse::class, 'response_id');
    }

    /**
     * Get the form item (hardcoded)
     */
    public function getFormItemAttribute()
    {
        return \App\Services\AssessmentService::getAssessmentFormItem($this->item_id);
    }
}