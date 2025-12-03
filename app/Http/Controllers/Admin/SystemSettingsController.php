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
        $whatsappNotificationEnabled = SystemSetting::isEnabled('whatsapp_notification_enabled');
        $systemFont = SystemSetting::getValue('system_font', 'default');
        
        return view('admin.system-settings', compact('laporanPklEnabled', 'penilaianEnabled', 'jadwalSeminarEnabled', 'instansiMitraEnabled', 'dokumenPemberkasanEnabled', 'registrationEnabled', 'whatsappNotificationEnabled', 'systemFont'));
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
            'whatsapp_notification_enabled' => 'boolean',
            'system_font' => 'required|string|in:default,poppins,inter,ibm_plex_sans,archivo,space_grotesk,bricolage_grotesque',
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

            SystemSetting::setEnabled(
                'whatsapp_notification_enabled',
                $request->boolean('whatsapp_notification_enabled'),
                'Toggle untuk mengaktifkan/menonaktifkan notifikasi WhatsApp via Fonnte'
            );

            SystemSetting::setValue(
                'system_font',
                $request->input('system_font'),
                'Font sistem yang digunakan pada seluruh aplikasi'
            );

            Log::info('System settings updated', [
                'laporan_pkl_enabled' => $request->boolean('laporan_pkl_enabled'),
                'penilaian_enabled' => $request->boolean('penilaian_enabled'),
                'jadwal_seminar_enabled' => $request->boolean('jadwal_seminar_enabled'),
                'instansi_mitra_enabled' => $request->boolean('instansi_mitra_enabled'),
                'dokumen_pemberkasan_enabled' => $request->boolean('dokumen_pemberkasan_enabled'),
                'registration_enabled' => $request->boolean('registration_enabled'),
                'whatsapp_notification_enabled' => $request->boolean('whatsapp_notification_enabled'),
                'system_font' => $request->input('system_font'),
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

    public function uploadLoginBackground(Request $request)
    {
        $request->validate([
            'login_bg_image' => 'required|image|mimes:jpeg,jpg,png|max:5120', // max 5MB
        ], [
            'login_bg_image.required' => 'Gambar harus dipilih',
            'login_bg_image.image' => 'File harus berupa gambar',
            'login_bg_image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'login_bg_image.max' => 'Ukuran gambar maksimal 5MB',
        ]);

        try {
            $image = $request->file('login_bg_image');
            $destinationPath = public_path('images/auth');

            // Create directory if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Delete old image if exists
            $oldImagePath = $destinationPath . '/bg_login.jpg';
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Save new image with fixed name
            $image->move($destinationPath, 'bg_login.jpg');

            Log::info('Login background image updated', [
                'updated_by' => auth()->id(),
                'file_size' => $image->getSize(),
            ]);

            return redirect()->back()->with('success', 'Gambar background login berhasil diupload!');
        } catch (\Exception $e) {
            Log::error('Failed to upload login background image', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->withErrors(['error' => 'Gagal mengupload gambar: ' . $e->getMessage()]);
        }
    }
}