<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $table = 'mitra';
    
    protected $fillable = [
        'nama',
        'alamat',
        'kontak',
        'jarak',
        'honor',
        'fasilitas',
        'kesesuaian_jurusan',
        'tingkat_kebersihan',
    ];

    // Relationships
    public function suratBalasan()
    {
        return $this->hasMany(SuratBalasan::class, 'mitra_id');
    }
}
