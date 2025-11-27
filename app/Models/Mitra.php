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
        'max_mahasiswa',
    ];

    // Relationships
    public function suratBalasan()
    {
        return $this->hasMany(SuratBalasan::class, 'mitra_id');
    }

    public function mahasiswaTerpilih()
    {
        return $this->hasMany(ProfilMahasiswa::class, 'mitra_selected', 'id');
    }

    /**
     * Get label mapping for criteria values (1-5)
     */
    public static function getCriteriaLabel($value)
    {
        $labels = [
            1 => 'Biasa saja',
            2 => 'Baik',
            3 => 'Bagus',
            4 => 'Sangat Bagus',
            5 => 'Luar Biasa',
        ];

        return $labels[$value] ?? 'Tidak Diketahui';
    }

    /**
     * Get all criteria labels as array
     */
    public static function getCriteriaLabels()
    {
        return [
            1 => 'Biasa saja',
            2 => 'Baik',
            3 => 'Bagus',
            4 => 'Sangat Bagus',
            5 => 'Luar Biasa',
        ];
    }

    /**
     * Get honor label
     */
    public function getHonorLabelAttribute()
    {
        // Honor hanya memiliki 2 nilai: Tidak Ada (1) atau Ada (5)
        return $this->honor >= 5 ? 'Ada' : 'Tidak Ada';
    }

    /**
     * Get fasilitas label
     */
    public function getFasilitasLabelAttribute()
    {
        return self::getCriteriaLabel($this->fasilitas);
    }

    /**
     * Get kesesuaian jurusan label
     */
    public function getKesesuaianJurusanLabelAttribute()
    {
        return self::getCriteriaLabel($this->kesesuaian_jurusan);
    }

    /**
     * Get tingkat kebersihan label
     */
    public function getTingkatKebersihanLabelAttribute()
    {
        return self::getCriteriaLabel($this->tingkat_kebersihan);
    }

    /**
     * Get jumlah mahasiswa yang sudah memilih mitra ini
     */
    public function getJumlahMahasiswaTerpilihAttribute()
    {
        return $this->mahasiswaTerpilih()->count();
    }

    /**
     * Cek apakah kuota mitra sudah penuh
     */
    public function isKuotaPenuh()
    {
        return $this->mahasiswaTerpilih()->count() >= $this->max_mahasiswa;
    }

    /**
     * Get sisa kuota mahasiswa
     */
    public function getSisaKuotaAttribute()
    {
        $sisa = $this->max_mahasiswa - $this->mahasiswaTerpilih()->count();
        return max(0, $sisa);
    }
}
