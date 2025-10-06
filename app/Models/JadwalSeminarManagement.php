<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalSeminarManagement extends Model
{
    protected $table = 'jadwal_seminar_management';
    
    protected $fillable = [
        'judul',
        'subjudul',
        'jenis',
        'lokasi_file',
        'url_eksternal',
        'status_aktif',
        'tanggal_publikasi',
        'dibuat_oleh'
    ];
    
    protected $casts = [
        'status_aktif' => 'boolean',
        'tanggal_publikasi' => 'datetime',
    ];
    
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
