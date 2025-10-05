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
        
        return view('admin.system-settings', compact('laporanPklEnabled'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'laporan_pkl_enabled' => 'boolean',
        ]);

        try {
            // Update Laporan PKL setting
            SystemSetting::setEnabled(
                'laporan_pkl_enabled', 
                $request->boolean('laporan_pkl_enabled'),
                'Toggle untuk mengaktifkan/menonaktifkan fitur upload Laporan PKL untuk mahasiswa'
            );

            Log::info('System settings updated', [
                'laporan_pkl_enabled' => $request->boolean('laporan_pkl_enabled'),
                'updated_by' => auth()->user()->id
            ]);

            return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Failed to update system settings', [
                'error' => $e->getMessage(),
                'user_id' => auth()->user()->id
            ]);
            
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui pengaturan sistem: ' . $e->getMessage()]);
        }
    }
}