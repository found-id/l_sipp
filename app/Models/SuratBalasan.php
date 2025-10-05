<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratBalasan extends Model
{
    protected $table = 'surat_balasan';
    
    protected $fillable = [
        'mahasiswa_id',
        'mitra_id',
        'mitra_nama_custom',
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

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
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
