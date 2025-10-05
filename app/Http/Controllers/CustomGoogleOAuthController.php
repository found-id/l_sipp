<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use GuzzleHttp\Client;

class CustomGoogleOAuthController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            // SSL verification is disabled globally in AppServiceProvider
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Log::error('Google OAuth redirect error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['error' => 'Gagal mengarahkan ke Google. Silakan coba lagi.']);
        }
    }

    public function handleGoogleCallback()
    {
        try {
            Log::info('Google OAuth callback started');
            
            // SSL verification is disabled globally in AppServiceProvider
            $googleUser = Socialite::driver('google')->user();
            
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
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make('google_oauth_' . time()),
                    'role' => 'mahasiswa', // Default role for Google OAuth
                    'google_linked' => true,
                    'google_email' => $googleUser->getEmail(),
                    'photo' => $googleUser->getAvatar(),
                ]);
                
                Log::info('New user created', ['user_id' => $user->id]);
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
            return redirect()->route('login')->withErrors(['error' => 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi. Error: ' . $e->getMessage()]);
        }
    }
}
