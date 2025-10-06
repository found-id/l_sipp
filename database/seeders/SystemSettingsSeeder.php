<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default system settings
        SystemSetting::setValue(
            'laporan_pkl_enabled',
            '1', // Default enabled
            'Toggle untuk mengaktifkan/menonaktifkan fitur upload Laporan PKL untuk mahasiswa'
        );
        
        SystemSetting::setValue(
            'penilaian_enabled',
            '1', // Default enabled
            'Toggle untuk mengaktifkan/menonaktifkan fitur Penilaian untuk dosen pembimbing'
        );
        
        SystemSetting::setValue(
            'jadwal_seminar_enabled',
            '1', // Default enabled
            'Toggle untuk mengaktifkan/menonaktifkan fitur Jadwal Seminar'
        );
        
        SystemSetting::setValue(
            'instansi_mitra_enabled',
            '1', // Default enabled
            'Toggle untuk mengaktifkan/menonaktifkan fitur Instansi Mitra'
        );
        
        SystemSetting::setValue(
            'dokumen_pemberkasan_enabled',
            '1', // Default enabled
            'Toggle untuk mengaktifkan/menonaktifkan fitur upload dokumen pemberkasan (KHS, Surat Balasan)'
        );
        
        SystemSetting::setValue(
            'registration_enabled',
            '1', // Default enabled
            'Toggle untuk mengaktifkan/menonaktifkan pendaftaran akun baru (termasuk Google OAuth)'
        );
    }
}