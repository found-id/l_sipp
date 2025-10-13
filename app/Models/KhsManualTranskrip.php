<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhsManualTranskrip extends Model
{
    protected $table = 'khs_manual_transkrip';
    
    protected $fillable = [
        'mahasiswa_id',
        'semester',
        'transcript_data',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // Relationship dengan User
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
