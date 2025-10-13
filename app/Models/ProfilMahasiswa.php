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
        'gdrive_pkkmb',
        'gdrive_ecourse',
        'gdrive_more',
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
}
