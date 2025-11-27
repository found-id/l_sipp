<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'photo',
        'profile_photo',
        'google_linked',
        'google_email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'google_linked' => 'boolean',
        ];
    }

    // Relationships
    public function profilMahasiswa()
    {
        return $this->hasOne(ProfilMahasiswa::class, 'id_mahasiswa', 'id');
    }

    public function dospem()
    {
        return $this->hasOne(Dospem::class, 'user_id');
    }

    public function mahasiswaBimbingan()
    {
        return $this->hasMany(ProfilMahasiswa::class, 'id_dospem', 'id');
    }

    public function khs()
    {
        return $this->hasMany(Khs::class, 'mahasiswa_id');
    }

    public function khsManualTranskrip()
    {
        return $this->hasMany(KhsManualTranskrip::class, 'mahasiswa_id');
    }

    public function suratBalasan()
    {
        return $this->hasMany(SuratBalasan::class, 'mahasiswa_id');
    }

    public function laporanPkl()
    {
        return $this->hasMany(LaporanPkl::class, 'mahasiswa_id');
    }

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class, 'mahasiswa_user_id', 'id');
    }

    public function historyAktivitas()
    {
        return $this->hasMany(HistoryAktivitas::class, 'id_user');
    }

    // Scopes
    public function scopeMahasiswa($query)
    {
        return $query->where('role', 'mahasiswa');
    }

    public function scopeDosenPembimbing($query)
    {
        return $query->where('role', 'dospem');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    // Helper method to get profile photo (custom or Google fallback)
    public function getProfilePhotoUrlAttribute()
    {
        // Priority: custom photo > default avatar
        if ($this->photo) {
            // Check if it's a URL or a storage path
            if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
                return $this->photo;
            }
            return asset('storage/' . $this->photo);
        }

        // Default avatar based on role
        $defaultAvatars = [
            'mahasiswa' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3b82f6&color=fff',
            'dospem' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=10b981&color=fff',
            'admin' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=ef4444&color=fff',
        ];

        return $defaultAvatars[$this->role] ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }
}
