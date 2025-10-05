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
    }
}