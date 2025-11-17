<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilMahasiswa extends Model
{
    protected $table = 'profil_mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    
    protected $fillable = [
        'id_mahasiswa',
        'nim',
        'prodi',
        'semester',
        'no_whatsapp',
        'jenis_kelamin',
        'ipk',
        'cek_min_semester',
        'cek_ipk_nilaisks',
        'cek_valid_biodata',
        'id_dospem',
        'mitra_selected',
        'gdrive_pkkmb',
        'gdrive_ecourse',
        'gdrive_more',
        'status_dokumen_pendukung',
        'status_pkl',
    ];

    protected function casts(): array
    {
        return [
            'cek_min_semester' => 'boolean',
            'cek_ipk_nilaisks' => 'boolean',
            'cek_valid_biodata' => 'boolean',
            'ipk' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa', 'id');
    }

    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'id_dospem', 'id');
    }

    public function mitraSelected()
    {
        return $this->belongsTo(Mitra::class, 'mitra_selected', 'id');
    }

    public function riwayatPengantianMitra()
    {
        return $this->hasMany(RiwayatPengantianMitra::class, 'mahasiswa_id', 'id_mahasiswa');
    }
}
