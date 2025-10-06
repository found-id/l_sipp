<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentResponseItem extends Model
{
    protected $fillable = [
        'response_id',
        'item_id',
        'value_numeric',
        'value_bool',
        'value_text'
    ];
    
    protected $casts = [
        'value_numeric' => 'decimal:2',
        'value_bool' => 'boolean',
    ];
    
    public function response()
    {
        return $this->belongsTo(AssessmentResponse::class, 'response_id');
    }
    
    public function item()
    {
        return $this->belongsTo(AssessmentFormItem::class, 'item_id');
    }
}
