<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalSeminar extends Model
{
    protected $table = 'jadwal_seminar';
    
    protected $fillable = [
        'judul',
        'subjudul',
        'jenis',
        'lokasi_file',
        'url_eksternal',
        'tingkat_akses',
        'status_aktif',
        'tanggal_publikasi',
        'dibuat_oleh',
        'tanggal_dibuat',
        'tanggal_diperbaharui',
    ];
    
    protected $casts = [
        'tanggal_publikasi' => 'datetime',
        'tanggal_dibuat' => 'datetime',
        'tanggal_diperbaharui' => 'datetime',
        'status_aktif' => 'boolean',
    ];
    
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}