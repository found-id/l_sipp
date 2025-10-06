<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $laporanPklEnabled = SystemSetting::isEnabled('laporan_pkl_enabled');
        $penilaianEnabled = SystemSetting::isEnabled('penilaian_enabled');
        $jadwalSeminarEnabled = SystemSetting::isEnabled('jadwal_seminar_enabled');
        $instansiMitraEnabled = SystemSetting::isEnabled('instansi_mitra_enabled');
        $dokumenPemberkasanEnabled = SystemSetting::isEnabled('dokumen_pemberkasan_enabled');
        $registrationEnabled = SystemSetting::isEnabled('registration_enabled');
        
        return view('admin.system-settings', compact('laporanPklEnabled', 'penilaianEnabled', 'jadwalSeminarEnabled', 'instansiMitraEnabled', 'dokumenPemberkasanEnabled', 'registrationEnabled'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'laporan_pkl_enabled' => 'boolean',
            'penilaian_enabled' => 'boolean',
            'jadwal_seminar_enabled' => 'boolean',
            'instansi_mitra_enabled' => 'boolean',
            'dokumen_pemberkasan_enabled' => 'boolean',
            'registration_enabled' => 'boolean',
        ]);

        try {
            // Update all settings
            SystemSetting::setEnabled(
                'laporan_pkl_enabled', 
                $request->boolean('laporan_pkl_enabled'),
                'Toggle untuk mengaktifkan/menonaktifkan fitur upload Laporan PKL untuk mahasiswa'
            );
            
            SystemSetting::setEnabled(
                'penilaian_enabled', 
                $request->boolean('penilaian_enabled'),
                'Toggle untuk mengaktifkan/menonaktifkan fitur Penilaian untuk dosen pembimbing'
            );
            
            SystemSetting::setEnabled(
                'jadwal_seminar_enabled', 
                $request->boolean('jadwal_seminar_enabled'),
                'Toggle untuk mengaktifkan/menonaktifkan fitur Jadwal Seminar'
            );
            
            SystemSetting::setEnabled(
                'instansi_mitra_enabled', 
                $request->boolean('instansi_mitra_enabled'),
                'Toggle untuk mengaktifkan/menonaktifkan fitur Instansi Mitra'
            );
            
            SystemSetting::setEnabled(
                'dokumen_pemberkasan_enabled', 
                $request->boolean('dokumen_pemberkasan_enabled'),
                'Toggle untuk mengaktifkan/menonaktifkan fitur upload dokumen pemberkasan (KHS, Surat Balasan)'
            );
            
            SystemSetting::setEnabled(
                'registration_enabled', 
                $request->boolean('registration_enabled'),
                'Toggle untuk mengaktifkan/menonaktifkan pendaftaran akun baru (termasuk Google OAuth)'
            );

            Log::info('System settings updated', [
                'laporan_pkl_enabled' => $request->boolean('laporan_pkl_enabled'),
                'penilaian_enabled' => $request->boolean('penilaian_enabled'),
                'jadwal_seminar_enabled' => $request->boolean('jadwal_seminar_enabled'),
                'instansi_mitra_enabled' => $request->boolean('instansi_mitra_enabled'),
                'dokumen_pemberkasan_enabled' => $request->boolean('dokumen_pemberkasan_enabled'),
                'registration_enabled' => $request->boolean('registration_enabled'),
                'updated_by' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Failed to update system settings', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui pengaturan sistem: ' . $e->getMessage()]);
        }
    }
}