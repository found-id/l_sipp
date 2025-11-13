<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPengantar extends Model
{
    use HasFactory;

    protected $table = 'surat_pengantar';

    protected $fillable = [
        'mahasiswa_id',
        'file_path',
        'status_validasi',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
