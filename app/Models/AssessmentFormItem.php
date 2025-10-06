<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentFormItem extends Model
{
    protected $fillable = [
        'form_id',
        'label',
        'type',
        'weight',
        'required',
        'sort_order'
    ];
    
    protected $casts = [
        'weight' => 'decimal:2',
        'required' => 'boolean',
    ];
    
    public function form()
    {
        return $this->belongsTo(AssessmentForm::class, 'form_id');
    }
    
    public function responseItems()
    {
        return $this->hasMany(AssessmentResponseItem::class, 'item_id');
    }
}
