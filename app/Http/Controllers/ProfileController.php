<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\FonnteService;
use App\Models\User;
use App\Models\ProfilMahasiswa;

class ProfileController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user()->load('dospem');
        $profil = $user->profilMahasiswa;

        // Get dosen pembimbing for this mahasiswa
        $dosenPembimbing = null;
        if ($user->role === 'mahasiswa' && $profil && $profil->id_dospem) {
            $dosenPembimbing = User::find($profil->id_dospem);
        }

        // Tampilkan halaman profile
        return view('profile.index', compact('user', 'profil', 'dosenPembimbing'));
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

        // Convert comma to dot in IPK if present (support Indonesian format)
        if ($request->has('ipk')) {
            $request->merge([
                'ipk' => str_replace(',', '.', $request->ipk)
            ]);
        }

        try {
            // Base rules for all roles
            $rules = [
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:190|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6|confirmed',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
            ];

            if ($user->role === 'dospem') {
                // Only validate NIP for dospem
                $ignoreId = optional($user->dospem)->id;
                $rules['nip'] = 'nullable|string|max:50|unique:dospem,nip,' . ($ignoreId ?? 'NULL') . ',id';
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

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->photo && !filter_var($user->photo, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Store new photo
            $file = $request->file('profile_photo');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');

            // Update user photo
            $user->photo = $path;
            // Set google_linked to false since user uploaded custom photo
            $user->google_linked = false;

            Log::info('Profile photo uploaded', [
                'user_id' => $user->id,
                'filename' => $filename
            ]);
        }

        // Update user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        if (isset($user->photo)) {
            $userData['photo'] = $user->photo;
        }

        if (isset($user->google_linked)) {
            $userData['google_linked'] = $user->google_linked;
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
                $message .= "Profil Anda telah berhasil diperbarui di *SIP PKL*.\n\n";
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

    /**
     * Upload profile photo
     */
    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
        ]);

        $user = Auth::user();

        // Delete old profile photo if exists
        if ($user->photo && !filter_var($user->photo, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Store new photo
        $file = $request->file('profile_photo');
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile_photos', $filename, 'public');

        // Update user photo and set google_linked to false
        $user->update([
            'photo' => $path,
            'google_linked' => false
        ]);

        Log::info('Profile photo uploaded', [
            'user_id' => $user->id,
            'filename' => $filename
        ]);

        return back()->with('success', 'Foto profil berhasil diupload!');
    }

    /**
     * Delete profile photo
     */
    public function deleteProfilePhoto()
    {
        $user = Auth::user();

        if (!$user->photo) {
            return back()->withErrors(['error' => 'Tidak ada foto profil yang akan dihapus.']);
        }

        // Delete file from storage (only if it's not a URL)
        if (!filter_var($user->photo, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Clear photo field
        $user->update(['photo' => null]);

        Log::info('Profile photo deleted', ['user_id' => $user->id]);

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }
}
