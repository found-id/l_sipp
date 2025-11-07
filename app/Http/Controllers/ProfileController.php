<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\FonnteService;
use App\Models\User;
use App\Models\ProfilMahasiswa;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('dospem');
        if (!$user) {
            return redirect()->route('login');
        }
        $profil = $user->profilMahasiswa;
        $dospem = $user->dospem;

        // Get dosen pembimbing info
        $dosenPembimbing = null;
        if ($profil && $profil->dosenPembimbing) {
            $dosenPembimbing = $profil->dosenPembimbing;
        }

        return view('profile.index', compact('user', 'profil', 'dospem', 'dosenPembimbing'));
    }

    public function edit()
    {
        $user = Auth::user()->load('dospem');
        $profil = $user->profilMahasiswa;
        $dospem = $user->dospem;

        // Get all dosen pembimbing for dropdown
        $dosenPembimbingList = User::dosenPembimbing()->get();

        return view('profile.edit', compact('user', 'profil', 'dospem', 'dosenPembimbingList'));
    }

    public function update(Request $request)
    {
        Log::info('ProfileController@update method called');
        $user = Auth::user();
        
        Log::info('Profile update request received:', [
            'user_id' => $user->id,
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);
        
        try {
            // Base rules for all roles
            $rules = [
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:190|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6|confirmed',
            ];

            if ($user->role === 'dospem') {
                // Only validate NIP for dospem
                $ignoreId = optional($user->dospem)->id;
                $rules['nip'] = 'nullable|string|max:50|unique:dospems,nip,' . ($ignoreId ?? 'NULL') . ',id';
            } elseif ($user->role === 'mahasiswa') {
                // Mahasiswa-specific validations
                $rules = array_merge($rules, [
                    'nim' => 'nullable|string|max:50|unique:profil_mahasiswa,nim,' . ($user->profilMahasiswa->id_mahasiswa ?? 'NULL') . ',id_mahasiswa',
                    'prodi' => 'required|string|max:100',
                    'semester' => 'required|integer|min:1|max:14',
                    'no_whatsapp' => 'nullable|string|max:30',
                    'jenis_kelamin' => 'nullable|in:L,P',
                    'ipk' => 'nullable|numeric|min:0|max:4.0',
                    'id_dospem' => 'nullable|exists:users,id',
                    'cek_min_semester' => 'boolean',
                    'cek_ipk_nilaisks' => 'boolean',
                    'cek_valid_biodata' => 'boolean',
                ]);
            }

            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        }

        // Update user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);

        // If user is dospem, update or create dospem profile
        if ($user->role === 'dospem') {
            $user->dospem()->updateOrCreate(
                ['user_id' => $user->id],
                ['nip' => $request->nip]
            );
        }

        // Update or create profil mahasiswa only for mahasiswa role
        if ($user->role === 'mahasiswa') {
            $profilData = [
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'semester' => $request->semester,
                'no_whatsapp' => $request->no_whatsapp,
                'jenis_kelamin' => $request->jenis_kelamin,
                'ipk' => $request->ipk,
                'id_dospem' => $request->id_dospem,
                'cek_min_semester' => $request->has('cek_min_semester'),
                'cek_ipk_nilaisks' => $request->has('cek_ipk_nilaisks'),
                'cek_valid_biodata' => $request->has('cek_valid_biodata'),
            ];

            // Debug logging
            Log::info('Profile Update Debug:', [
                'user_id' => $user->id,
                'profil_exists' => $user->profilMahasiswa ? 'yes' : 'no',
                'profil_data' => $profilData,
                'request_data' => $request->all()
            ]);

            // Update profile data
            try {
                if ($user->profilMahasiswa) {
                    // Update existing profile
                    $profil = $user->profilMahasiswa;
                    $profil->nim = $profilData['nim'];
                    $profil->prodi = $profilData['prodi'];
                    $profil->semester = $profilData['semester'];
                    $profil->no_whatsapp = $profilData['no_whatsapp'];
                    $profil->jenis_kelamin = $profilData['jenis_kelamin'];
                    $profil->ipk = $profilData['ipk'];
                    $profil->id_dospem = $profilData['id_dospem'];
                    $profil->cek_min_semester = $profilData['cek_min_semester'];
                    $profil->cek_ipk_nilaisks = $profilData['cek_ipk_nilaisks'];
                    $profil->cek_valid_biodata = $profilData['cek_valid_biodata'];

                    $result = $profil->save();
                    Log::info('Profile updated successfully:', ['result' => $result, 'profil_id' => $profil->id_mahasiswa]);
                } else {
                    // Create new profile
                    $profilData['id_mahasiswa'] = $user->id;
                    $profil = ProfilMahasiswa::create($profilData);
                    Log::info('Profile created successfully:', ['profil' => $profil]);
                }
            } catch (\Exception $e) {
                Log::error('Profile update error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
            }
        }

        // Send WhatsApp notification for profile changes
        if ($user->role === 'mahasiswa' && $user->profilMahasiswa && $user->profilMahasiswa->no_whatsapp) {
            try {
                $fonnte = new FonnteService();
                $phone = '+62' . $user->profilMahasiswa->no_whatsapp;
                
                $message = "📝 *Notifikasi Perubahan Profil*\n\n";
                $message .= "Halo *{$user->name}*,\n\n";
                $message .= "Profil Anda telah berhasil diperbarui di *SIPP PKL*.\n\n";
                $message .= "🔗 *Akses Profil:*\n";
                $message .= "http://localhost:8000/profile\n\n";
                $message .= "Jika ini bukan Anda, segera hubungi admin.\n\n";
                $message .= "Terima kasih! 🙏";
                
                $fonnte->sendMessage($phone, $message);
                
                Log::info('WhatsApp profile update notification sent', [
                    'user_id' => $user->id,
                    'phone' => $phone,
                    'name' => $user->name
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send WhatsApp profile update notification', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return redirect()->route('profile.index')->with('success', 'Biodata berhasil diperbaharui!');
    }

    public function settings()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.settings')->with('success', 'Password berhasil diubah!');
    }
}
