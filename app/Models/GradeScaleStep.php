<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeScaleStep extends Model
{
    protected $fillable = [
        'scale_id',
        'letter',
        'gpa_point',
        'min_score',
        'max_score',
        'sort_order'
    ];
    
    protected $casts = [
        'gpa_point' => 'decimal:2',
        'min_score' => 'decimal:2',
        'max_score' => 'decimal:2',
    ];
}
