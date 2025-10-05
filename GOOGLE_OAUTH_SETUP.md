# Google OAuth Setup Guide

## Konfigurasi Google OAuth untuk SIPP PKL

### 1. Setup Google Cloud Console

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau pilih project yang sudah ada
3. Aktifkan Google+ API:
   - Pergi ke "APIs & Services" > "Library"
   - Cari "Google+ API" dan klik "Enable"

### 2. Buat OAuth 2.0 Credentials

1. Pergi ke "APIs & Services" > "Credentials"
2. Klik "Create Credentials" > "OAuth 2.0 Client IDs"
3. Pilih "Web application" sebagai Application type
4. Tambahkan Authorized redirect URIs:
   - `http://localhost:8000/auth/google/callback` (untuk development)
   - `https://yourdomain.com/auth/google/callback` (untuk production)

### 3. Konfigurasi Environment Variables

Tambahkan ke file `.env`:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 4. Fitur yang Sudah Diimplementasi

- ✅ Login dengan Google
- ✅ Daftar dengan Google  
- ✅ Auto-redirect ke complete-profile untuk user baru
- ✅ Log aktivitas untuk login/register via Google
- ✅ Tombol Google OAuth di halaman login dan register

### 5. Testing

1. Pastikan server Laravel berjalan: `php artisan serve`
2. Buka `http://localhost:8000/login`
3. Klik "Login dengan Google"
4. Pilih akun Google
5. User akan otomatis login atau diarahkan ke complete-profile

### 6. Production Setup

Untuk production, pastikan:
1. Update `GOOGLE_REDIRECT_URI` di `.env` dengan domain production
2. Tambahkan domain production di Google Cloud Console
3. Set `APP_URL` di `.env` sesuai domain production
