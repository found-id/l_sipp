<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPkl extends Model
{
    protected $table = 'laporan_pkl';
    
    protected $fillable = [
        'mahasiswa_id',
        'file_path',
        'status_validasi',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
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

    public function scopeRevisi($query)
    {
        return $query->where('status_validasi', 'revisi');
    }
}
