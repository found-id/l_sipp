<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryAktivitas extends Model
{
    protected $table = 'history_aktivitas';
    protected $primaryKey = 'id_aktivitas';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_mahasiswa',
        'tipe',
        'pesan',
        'tanggal_dibuat'
    ];

    protected $casts = [
        'pesan' => 'array',
        'tanggal_dibuat' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->tanggal_dibuat) {
                $model->tanggal_dibuat = now();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }
}