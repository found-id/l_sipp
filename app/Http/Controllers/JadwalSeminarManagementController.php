<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalSeminarManagement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class JadwalSeminarManagementController extends Controller
{
    public function index()
    {
        $jadwal = JadwalSeminarManagement::with('pembuat')
            ->where('status_aktif', true)
            ->orderBy('tanggal_publikasi', 'desc')
            ->paginate(10);
            
        return view('jadwal-seminar.index', compact('jadwal'));
    }
    
    public function manage()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $jadwal = JadwalSeminarManagement::with('pembuat')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.jadwal-seminar.manage', compact('jadwal'));
    }
    
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('admin.jadwal-seminar.create');
    }
    
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'jenis' => 'required|in:file,link',
            'file' => 'required_if:jenis,file|file|mimes:pdf,xls,xlsx,jpg,jpeg,png|max:10240',
            'url_eksternal' => 'required_if:jenis,link|nullable|url',
        ]);
        
        $data = [
            'judul' => $request->judul,
            'subjudul' => $request->subjudul,
            'jenis' => $request->jenis,
            'status_aktif' => true,
            'tanggal_publikasi' => now(),
            'dibuat_oleh' => Auth::id(),
        ];
        
        if ($request->jenis === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'jadwal_' . date('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jadwal', $filename, 'public');
            $data['lokasi_file'] = $path;
        } elseif ($request->jenis === 'link') {
            $data['url_eksternal'] = $request->url_eksternal;
        }
        
        JadwalSeminarManagement::create($data);
        
        return redirect()->route('admin.jadwal-seminar.manage')
            ->with('success', 'Jadwal seminar berhasil dipublikasikan!');
    }
    
    public function toggle($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $jadwal = JadwalSeminarManagement::findOrFail($id);
        $jadwal->update(['status_aktif' => !$jadwal->status_aktif]);
        
        return redirect()->back()
            ->with('success', 'Status jadwal berhasil diubah!');
    }
    
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $jadwal = JadwalSeminarManagement::findOrFail($id);
        
        if ($jadwal->lokasi_file && Storage::disk('public')->exists($jadwal->lokasi_file)) {
            Storage::disk('public')->delete($jadwal->lokasi_file);
        }
        
        $jadwal->delete();
        
        return redirect()->back()
            ->with('success', 'Jadwal seminar berhasil dihapus!');
    }
}
