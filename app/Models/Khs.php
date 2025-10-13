<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khs extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'file_path',
        'semester',
        'status_validasi',
        'transcript_data',
        'ips',
        'total_sks_d',
        'has_e',
        'eligible',
        'total_sks',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'ips' => 'decimal:2',
            'total_sks_d' => 'integer',
            'has_e' => 'boolean',
            'eligible' => 'boolean',
        ];
    }

    // Relationships
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    // Scopes
    public function scopeMenunggu($query)
    {
        return $query->where('status_validasi', 'menunggu');
    }

    public function scopeBelumValid($query)
    {
        return $query->where('status_validasi', 'belum_valid');
    }

    public function scopeTervalidasi($query)
    {
        return $query->where('status_validasi', 'tervalidasi');
    }
}
