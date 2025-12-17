<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dospem extends Model
{
    protected $table = 'dospem';

    protected $fillable = [
        'user_id',
        'nip',
        'no_telepon',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
