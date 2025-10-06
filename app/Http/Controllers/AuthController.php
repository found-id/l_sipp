<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\FonnteService;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Try to find user by email or NIM
        $user = User::where('email', $credentials['email'])
                   ->orWhereHas('profilMahasiswa', function($query) use ($credentials) {
                       $query->where('nim', $credentials['email']);
                   })
                   ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            
            // Log login activity
            \App\Models\HistoryAktivitas::create([
                'id_user' => $user->id,
                'id_mahasiswa' => $user->role === 'mahasiswa' ? $user->id : null,
                'tipe' => 'login',
                'pesan' => [
                    'action' => 'login',
                    'user' => $user->name,
                    'role' => $user->role,
                    'message' => $user->name . ' (' . ucfirst($user->role) . ') melakukan login',
                ],
            ]);
            
            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('dashboard');
                case 'dospem':
                    return redirect()->route('dashboard');
                case 'mahasiswa':
                    // Check if mahasiswa has completed profile
                    if (!$user->profilMahasiswa) {
                        return redirect()->route('profile.edit')->with('info', 'Silakan lengkapi biodata Anda terlebih dahulu.');
                    }
                    return redirect()->route('dashboard');
                default:
                    return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email/NIM atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        // Check if registration is enabled
        if (!\App\Models\SystemSetting::isEnabled('registration_enabled')) {
            return redirect()->route('login')->with('error', 'Pendaftaran sedang ditutup. Silakan hubungi administrator.');
        }
        
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:190|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:mahasiswa,dospem,admin',
            // Biodata validation for mahasiswa
            'nim' => 'required_if:role,mahasiswa|string|max:20|unique:profil_mahasiswa,nim',
            'prodi' => 'required_if:role,mahasiswa|string|max:100',
            'semester' => 'required_if:role,mahasiswa|integer|min:1|max:8',
            'jenis_kelamin' => 'required_if:role,mahasiswa|string|in:L,P',
            'no_wa' => 'required_if:role,mahasiswa|string|regex:/^8\d{8,11}$/',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create biodata for mahasiswa
        if ($request->role === 'mahasiswa') {
            try {
                $profilData = [
                    'id_mahasiswa' => $user->id,
                    'nim' => $request->nim,
                    'prodi' => $request->prodi,
                    'semester' => $request->semester,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'no_whatsapp' => $request->no_wa,
                    'cek_min_semester' => false,
                    'cek_ipk_nilaisks' => false,
                    'cek_valid_biodata' => false,
                ];
                
                \App\Models\ProfilMahasiswa::create($profilData);
            } catch (\Exception $e) {
                // Log the error
                Log::error('Error creating ProfilMahasiswa: ' . $e->getMessage());
                Log::error('Data: ' . json_encode($profilData));
                
                // Delete the user if profil creation fails
                $user->delete();
                
                return back()->withErrors([
                    'database' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'
                ])->withInput();
            }
        }

        Auth::login($user);

        // Log registration activity
        \App\Models\HistoryAktivitas::create([
            'id_user' => $user->id,
            'id_mahasiswa' => $user->role === 'mahasiswa' ? $user->id : null,
            'tipe' => 'register',
            'pesan' => [
                'action' => 'register',
                'user' => $user->name,
                'role' => $user->role,
                'message' => $user->name . ' telah melakukan registrasi sebagai ' . ucfirst($user->role),
            ],
        ]);

        // Send WhatsApp notification for mahasiswa
        if ($user->role === 'mahasiswa' && $request->no_wa) {
            try {
                $fonnte = new FonnteService();
                $phone = '+62' . $request->no_wa;
                $fonnte->sendRegistrationSuccess($phone, $user->name, 'Mahasiswa');
                Log::info('WhatsApp notification sent for registration', [
                    'user_id' => $user->id,
                    'phone' => $phone,
                    'name' => $user->name
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send WhatsApp notification', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Redirect to dashboard for all roles
        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di SIPP PKL.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log logout activity
        if ($user) {
            \App\Models\HistoryAktivitas::create([
                'id_user' => $user->id,
                'id_mahasiswa' => $user->role === 'mahasiswa' ? $user->id : null,
                'tipe' => 'logout',
                'pesan' => [
                    'action' => 'logout',
                    'user' => $user->name,
                    'role' => $user->role,
                    'message' => $user->name . ' (' . ucfirst($user->role) . ') melakukan logout',
                ],
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    public function showCompleteProfile()
    {
        if (!Auth::check() || Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('login');
        }

        if (Auth::user()->profilMahasiswa) {
            return redirect()->route('dashboard');
        }

        $dosenPembimbingList = User::where('role', 'dospem')->get();
        return view('auth.complete-profile', compact('dosenPembimbingList'));
    }

    public function completeProfile(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'mahasiswa' || $user->profilMahasiswa) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'nim' => 'required|string|max:20|unique:profil_mahasiswa,nim',
            'prodi' => 'required|string|max:100',
            'semester' => 'required|integer|min:1|max:14',
            'jenis_kelamin' => 'required|string|in:L,P',
            'no_whatsapp' => 'required|string|regex:/^8\d{8,11}$/',
            'ipk' => 'required|numeric|min:0|max:4.0',
            'id_dospem' => 'nullable|exists:users,id',
        ]);

        // Update user name if provided
        if ($request->name) {
            $user->update(['name' => $request->name]);
        }

        // Create profil mahasiswa
        \App\Models\ProfilMahasiswa::create([
            'id_mahasiswa' => $user->id,
            'nim' => $request->nim,
            'prodi' => $request->prodi,
            'semester' => $request->semester,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_whatsapp' => $request->no_whatsapp,
            'ipk' => $request->ipk,
            'id_dospem' => $request->id_dospem,
            'cek_min_semester' => false,
            'cek_ipk_nilaisks' => false,
            'cek_valid_biodata' => false,
        ]);

        // Send WhatsApp notification
        try {
            $fonnteService = new \App\Services\FonnteService();
            $phoneNumber = '+62' . $request->no_whatsapp;
            $message = "Halo {$request->name}! Biodata Anda telah berhasil dilengkapi. Selamat datang di Sistem Informasi Pengelolaan PKL!";
            $fonnteService->sendMessage($phoneNumber, $message);
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Biodata berhasil dilengkapi!');
    }

    public function redirectToGoogle()
    {
        // Check if registration is enabled
        if (!\App\Models\SystemSetting::isEnabled('registration_enabled')) {
            return redirect()->route('login')->with('error', 'Pendaftaran sedang ditutup. Silakan hubungi administrator.');
        }
        
        try {
            // Create custom HTTP client with SSL disabled
            $client = new \GuzzleHttp\Client([
                'verify' => false,
                'timeout' => 30,
                'http_errors' => false,
            ]);
            
            // Override Socialite's HTTP client
            $provider = Socialite::driver('google');
            $provider->setHttpClient($client);
            
            return $provider->redirect();
        } catch (\Exception $e) {
            Log::error('Google OAuth redirect error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['error' => 'Gagal mengarahkan ke Google. Silakan coba lagi.']);
        }
    }

    public function handleGoogleCallback()
    {
        try {
            Log::info('Google OAuth callback started');
            
            // Create custom HTTP client with SSL disabled
            $client = new \GuzzleHttp\Client([
                'verify' => false,
                'timeout' => 30,
                'http_errors' => false,
            ]);
            
            // Override Socialite's HTTP client
            $provider = Socialite::driver('google');
            $provider->setHttpClient($client);
            
            $googleUser = $provider->user();
            Log::info('Google user data received', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'id' => $googleUser->getId()
            ]);
            
            // Check if user exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // User exists, log them in
                Log::info('Existing user found, logging in', ['user_id' => $user->id]);
                Auth::login($user);
                
                // Log login activity
                \App\Models\HistoryAktivitas::create([
                    'id_user' => $user->id,
                    'id_mahasiswa' => $user->role === 'mahasiswa' ? $user->id : null,
                    'tipe' => 'login',
                    'pesan' => [
                        'action' => 'login',
                        'user' => $user->name,
                        'role' => $user->role,
                        'message' => $user->name . ' (' . ucfirst($user->role) . ') melakukan login via Google',
                    ],
                ]);
                
                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
                } else {
                    // User doesn't exist, create new account
                    Log::info('Creating new user via Google OAuth');
                    
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => 'google_' . time() . '@google.oauth', // Unique email for local account
                        'password' => Hash::make('google_oauth_' . time()),
                        'role' => 'mahasiswa', // Default role for Google OAuth
                        'google_linked' => true,
                        'google_email' => $googleUser->getEmail(), // Store actual Google email here
                        'photo' => $googleUser->getAvatar(),
                    ]);
                    
                    // Create basic profile for Google users
                    \App\Models\ProfilMahasiswa::create([
                        'id_mahasiswa' => $user->id,
                        'nim' => 'GOOGLE_' . time(), // Temporary NIM for Google users
                        'prodi' => 'Teknologi Informasi', // Default prodi
                        'semester' => 5, // Default semester
                        'jenis_kelamin' => 'L', // Default gender
                        'cek_min_semester' => false,
                        'cek_ipk_nilaisks' => false,
                        'cek_valid_biodata' => false,
                    ]);
                    
                    Log::info('New user created with profile', ['user_id' => $user->id]);
                    Auth::login($user);
                    
                    // Log registration activity
                    \App\Models\HistoryAktivitas::create([
                        'id_user' => $user->id,
                        'id_mahasiswa' => $user->id,
                        'tipe' => 'register',
                        'pesan' => [
                            'action' => 'register',
                            'user' => $user->name,
                            'role' => $user->role,
                            'message' => $user->name . ' telah melakukan registrasi via Google sebagai ' . ucfirst($user->role),
                        ],
                    ]);
                    
                    // Redirect to complete profile
                    return redirect()->route('complete-profile')->with('info', 'Silakan lengkapi biodata Anda terlebih dahulu.');
                }
        } catch (\Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            $errorMessage = 'Terjadi kesalahan saat login dengan Google. ';
            if (strpos($e->getMessage(), 'SSL') !== false) {
                $errorMessage .= 'Masalah SSL Certificate. Silakan hubungi admin.';
            } elseif (strpos($e->getMessage(), 'cURL') !== false) {
                $errorMessage .= 'Masalah koneksi internet. Silakan coba lagi.';
            } elseif (strpos($e->getMessage(), 'token') !== false) {
                $errorMessage .= 'Token Google tidak valid. Silakan hubungi admin.';
            } else {
                $errorMessage .= 'Error: ' . $e->getMessage();
            }
            
            return redirect()->route('login')->withErrors(['error' => $errorMessage]);
        }
    }
}
