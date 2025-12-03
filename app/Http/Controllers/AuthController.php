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
        
        // Get dosen pembimbing list
        $dosenPembimbingList = User::where('role', 'dospem')->orderBy('name')->get();
        
        return view('auth.register', compact('dosenPembimbingList'));
    }

    public function register(Request $request)
    {
        // Convert comma to dot in IPK if present (support Indonesian format)
        if ($request->has('ipk')) {
            $request->merge([
                'ipk' => str_replace(',', '.', $request->ipk)
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:100|regex:/^[^0-9]+$/',
            'email' => 'required|string|email|max:190|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:mahasiswa,dospem,admin',
            // Biodata validation for mahasiswa
            'nim' => 'required_if:role,mahasiswa|regex:/^\\d+$/|max:20|unique:profil_mahasiswa,nim',
            'semester' => 'required_if:role,mahasiswa|integer|min:5|max:8',
            'jenis_kelamin' => 'required_if:role,mahasiswa|string|in:L,P',
            'no_wa' => 'required_if:role,mahasiswa|string|regex:/^8\d{8,11}$/',
            'ipk' => 'required_if:role,mahasiswa|numeric|min:0|max:4.0',
            'id_dospem' => 'required_if:role,mahasiswa|exists:users,id',
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
                    'prodi' => 'D3 Teknologi Informasi', // Default value
                    'semester' => $request->semester,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'no_whatsapp' => $request->no_wa,
                    'ipk' => $request->ipk,
                    'id_dospem' => $request->id_dospem,
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
        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di SIP PKL.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        // If user is not authenticated, redirect to login immediately
        if (!$user) {
            return redirect()->route('login')->with('info', 'Anda sudah logout.');
        }

        // Log logout activity
        try {
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
        } catch (\Exception $e) {
            // Ignore logging errors during logout
            Log::error('Failed to log logout activity', [
                'error' => $e->getMessage()
            ]);
        }

        // Perform logout
        Auth::logout();

        // Invalidate session if it exists
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Redirect to login without message
        return redirect()->route('login');
    }

    public function cancelRegistration(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user && $user->google_linked) {
                // Log cancellation activity
                \App\Models\HistoryAktivitas::create([
                    'id_user' => $user->id,
                    'id_mahasiswa' => $user->role === 'mahasiswa' ? $user->id : null,
                    'tipe' => 'cancel_registration',
                    'pesan' => [
                        'action' => 'cancel_registration',
                        'user' => $user->name,
                        'role' => $user->role,
                        'message' => $user->name . ' membatalkan registrasi Google OAuth',
                    ],
                ]);
                
                // Delete the user account since they cancelled registration
                $user->delete();
                
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->with('info', 'Registrasi dibatalkan. Akun Google telah dihapus.');
            }
            
            // If not Google user, just logout normally
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('info', 'Registrasi dibatalkan.');
            
        } catch (\Exception $e) {
            Log::error('Error cancelling registration: ' . $e->getMessage());
            
            // Fallback: just logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat membatalkan registrasi.');
        }
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

        // Convert comma to dot in IPK if present (support Indonesian format)
        if ($request->has('ipk')) {
            $request->merge([
                'ipk' => str_replace(',', '.', $request->ipk)
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:100|regex:/^[^0-9]+$/',
            'nim' => 'required|regex:/^\\d+$/|max:20|unique:profil_mahasiswa,nim',
            'semester' => 'required|integer|min:5|max:14',
            'jenis_kelamin' => 'required|string|in:L,P',
            'no_whatsapp' => 'required|string|regex:/^8\d{8,11}$/',
            'ipk' => 'required|numeric|min:0|max:4.0',
            'id_dospem' => 'required|exists:users,id',
        ]);

        // Update user name if provided
        if ($request->name) {
            $user->update(['name' => $request->name]);
        }

        // Create profil mahasiswa
        \App\Models\ProfilMahasiswa::create([
            'id_mahasiswa' => $user->id,
            'nim' => $request->nim,
            'prodi' => 'D3 Teknologi Informasi', // Default value
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
            Log::info('Google OAuth redirect initiated');
            
            // Create custom HTTP client with enhanced configuration
            $client = new \GuzzleHttp\Client([
                'verify' => false,
                'timeout' => 60, // Increased timeout
                'connect_timeout' => 30, // Connection timeout
                'http_errors' => false,
                'allow_redirects' => [
                    'max' => 10,
                    'strict' => false,
                    'referer' => true,
                    'protocols' => ['http', 'https'],
                    'track_redirects' => true
                ],
                'headers' => [
                    'User-Agent' => 'Laravel-SIPP-PKL/1.0',
                    'Accept' => 'application/json',
                ]
            ]);
            
            // Override Socialite's HTTP client
            $provider = Socialite::driver('google');
            $provider->setHttpClient($client);
            
            // Store OAuth state in session for verification with enhanced persistence
            $state = bin2hex(random_bytes(16));
            $timestamp = time();
            
            // Store state with multiple fallbacks
            session(['google_oauth_state' => $state]);
            session(['google_oauth_timestamp' => $timestamp]);
            
            // Also store in cache as backup (if available)
            try {
                \Illuminate\Support\Facades\Cache::put('google_oauth_state_' . session()->getId(), $state, 300); // 5 minutes
                \Illuminate\Support\Facades\Cache::put('google_oauth_timestamp_' . session()->getId(), $timestamp, 300);
            } catch (\Exception $e) {
                Log::warning('Cache not available for OAuth state backup', ['error' => $e->getMessage()]);
            }
            
            Log::info('Google OAuth state stored', [
                'state' => $state,
                'timestamp' => $timestamp,
                'session_id' => session()->getId()
            ]);
            
            return $provider->redirect();
        } catch (\Exception $e) {
            Log::error('Google OAuth redirect error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'Gagal mengarahkan ke Google. ';
            if (strpos($e->getMessage(), 'SSL') !== false) {
                $errorMessage .= 'Masalah SSL Certificate. Silakan hubungi admin.';
            } elseif (strpos($e->getMessage(), 'cURL') !== false) {
                $errorMessage .= 'Masalah koneksi internet. Silakan coba lagi.';
            } elseif (strpos($e->getMessage(), 'timeout') !== false) {
                $errorMessage .= 'Koneksi timeout. Silakan coba lagi.';
            } else {
                $errorMessage .= 'Error: ' . $e->getMessage();
            }
            
            return redirect()->route('login')->withErrors(['error' => $errorMessage]);
        }
    }

    public function handleGoogleCallback()
    {
        try {
            Log::info('Google OAuth callback started');
            
            // Verify OAuth state with enhanced fallback mechanism
            $storedState = session('google_oauth_state');
            $storedTimestamp = session('google_oauth_timestamp');
            
            // Fallback: Try to get state from cache if session is empty
            if (!$storedState || !$storedTimestamp) {
                try {
                    $sessionId = session()->getId();
                    $cachedState = \Illuminate\Support\Facades\Cache::get('google_oauth_state_' . $sessionId);
                    $cachedTimestamp = \Illuminate\Support\Facades\Cache::get('google_oauth_timestamp_' . $sessionId);
                    
                    if ($cachedState && $cachedTimestamp) {
                        $storedState = $cachedState;
                        $storedTimestamp = $cachedTimestamp;
                        Log::info('Google OAuth state recovered from cache', [
                            'session_id' => $sessionId,
                            'cached_state' => $cachedState
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Cache fallback failed', ['error' => $e->getMessage()]);
                }
            }
            
            // Final fallback: If still no state found (server restart), allow OAuth to continue
            if (!$storedState || !$storedTimestamp) {
                Log::warning('Google OAuth state not found in session or cache - allowing fallback for server restart', [
                    'has_session_state' => !empty(session('google_oauth_state')),
                    'has_session_timestamp' => !empty(session('google_oauth_timestamp')),
                    'session_id' => session()->getId()
                ]);
                
                // Don't block OAuth if state is missing (likely server restart)
                // Just log the warning and continue
            } else {
                // Check if OAuth session is not too old (5 minutes) only if state exists
                if (time() - $storedTimestamp > 300) {
                    Log::warning('Google OAuth session expired', [
                        'stored_timestamp' => $storedTimestamp,
                        'current_time' => time(),
                        'difference' => time() - $storedTimestamp
                    ]);
                    session()->forget(['google_oauth_state', 'google_oauth_timestamp']);
                    return redirect()->route('login')->withErrors(['error' => 'Sesi OAuth telah expired. Silakan coba lagi.']);
                }
            }
            
            // Create custom HTTP client with enhanced configuration
            $client = new \GuzzleHttp\Client([
                'verify' => false,
                'timeout' => 60, // Increased timeout
                'connect_timeout' => 30, // Connection timeout
                'http_errors' => false,
                'allow_redirects' => [
                    'max' => 10,
                    'strict' => false,
                    'referer' => true,
                    'protocols' => ['http', 'https'],
                    'track_redirects' => true
                ],
                'headers' => [
                    'User-Agent' => 'Laravel-SIPP-PKL/1.0',
                    'Accept' => 'application/json',
                ]
            ]);
            
            // Override Socialite's HTTP client
            $provider = Socialite::driver('google');
            $provider->setHttpClient($client);
            
            // Clear OAuth state from session
            session()->forget(['google_oauth_state', 'google_oauth_timestamp']);
            
            $googleUser = $provider->user();
            Log::info('Google user data received', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'id' => $googleUser->getId()
            ]);
            
            // Check if user exists by google_email (not by email)
            $user = User::where('google_email', $googleUser->getEmail())->first();
            
            if ($user) {
                // User exists, log them in
                Log::info('Existing Google user found, logging in', ['user_id' => $user->id]);
                Auth::login($user);
                
                // Check if user has completed profile (for mahasiswa)
                if ($user->role === 'mahasiswa' && !$user->profilMahasiswa) {
                    Log::info('Google user needs to complete profile', ['user_id' => $user->id]);
                    return redirect()->route('complete-profile')->with('info', 'Silakan lengkapi biodata Anda terlebih dahulu.');
                }
                
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
                    'email' => $googleUser->getEmail(), // Use actual Google email as primary email
                    'password' => Hash::make('google_oauth_' . time()),
                    'role' => 'mahasiswa', // Default role for Google OAuth
                    'google_linked' => true,
                    'google_email' => $googleUser->getEmail(), // Store actual Google email here
                    'photo' => $googleUser->getAvatar(),
                ]);
                
                // DON'T create profile automatically - let user complete it
                Log::info('New Google user created, redirecting to complete profile', ['user_id' => $user->id]);
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
                'trace' => $e->getTraceAsString(),
                'request_data' => request()->all()
            ]);
            
            // Clear any remaining OAuth state
            session()->forget(['google_oauth_state', 'google_oauth_timestamp']);
            
            $errorMessage = 'Terjadi kesalahan saat login dengan Google. ';
            $retryMessage = ' Silakan coba lagi dalam beberapa detik.';
            
            if (strpos($e->getMessage(), 'SSL') !== false) {
                $errorMessage .= 'Masalah SSL Certificate. Silakan hubungi admin.';
            } elseif (strpos($e->getMessage(), 'cURL') !== false) {
                $errorMessage .= 'Masalah koneksi internet.' . $retryMessage;
            } elseif (strpos($e->getMessage(), 'timeout') !== false) {
                $errorMessage .= 'Koneksi timeout.' . $retryMessage;
            } elseif (strpos($e->getMessage(), 'token') !== false) {
                $errorMessage .= 'Token Google tidak valid. Silakan hubungi admin.';
            } elseif (strpos($e->getMessage(), 'state') !== false) {
                $errorMessage .= 'Sesi OAuth tidak valid.' . $retryMessage;
            } else {
                $errorMessage .= 'Error: ' . $e->getMessage() . $retryMessage;
            }
            
            // Add special flag for auto-retry mechanism
            $errorMessage .= ' [AUTO_RETRY]';
            
            return redirect()->route('login')->withErrors(['error' => $errorMessage]);
        }
    }
}
