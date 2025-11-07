<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPengantianMitra extends Model
{
    protected $table = 'riwayat_penggantian_mitra';

    protected $fillable = [
        'mahasiswa_id',
        'mitra_lama_id',
        'mitra_baru_id',
        'jenis_alasan',
        'alasan_lengkap',
    ];

    // Relationships
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function mitraLama()
    {
        return $this->belongsTo(Mitra::class, 'mitra_lama_id');
    }

    public function mitraBaru()
    {
        return $this->belongsTo(Mitra::class, 'mitra_baru_id');
    }

    // Accessor untuk label jenis alasan
    public function getJenisAlasanLabelAttribute()
    {
        $labels = [
            'ditolak' => 'Saya tidak diterima dari instansi tersebut',
            'alasan_tertentu' => 'Karena suatu alasan',
            'pilihan_pribadi' => 'Atas pilihan pribadi',
        ];

        return $labels[$this->jenis_alasan] ?? '-';
    }
}
