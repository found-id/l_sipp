<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentForm extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    public function items()
    {
        return $this->hasMany(AssessmentFormItem::class, 'form_id');
    }
    
    public function responses()
    {
        return $this->hasMany(AssessmentResponse::class, 'form_id');
    }
    
    public function results()
    {
        return $this->hasMany(AssessmentResult::class, 'form_id');
    }
}
