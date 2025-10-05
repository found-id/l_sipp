# SSL Certificate Fix untuk Google OAuth

## Masalah
Error: `cURL error 60: SSL certificate problem: unable to get local issuer certificate`

## Solusi yang Sudah Diimplementasi

### 1. ✅ Disable SSL Verification (Development)
- Sudah ditambahkan `->with(['verify' => false])` di AuthController
- Ini akan disable SSL verification untuk development

### 2. 🔧 Solusi Alternatif (Production)

#### A. Download CA Bundle
1. Download file `cacert.pem` dari https://curl.se/ca/cacert.pem
2. Simpan di folder `l_sipp/storage/ssl/`
3. Update `.env`:
   ```env
   CURL_CA_BUNDLE=storage/ssl/cacert.pem
   ```

#### B. Update php.ini
1. Buka file `php.ini`
2. Cari `curl.cainfo`
3. Set ke path file `cacert.pem`:
   ```ini
   curl.cainfo = "C:\path\to\cacert.pem"
   ```

#### C. Environment Variable
Set environment variable:
```bash
CURL_CA_BUNDLE=C:\path\to\cacert.pem
```

### 3. 🚀 Test SSL Fix

1. **Start server**:
   ```bash
   cd l_sipp
   php artisan serve --host=0.0.0.0 --port=8000
   ```

2. **Test Google OAuth**:
   - Buka `http://localhost:8000/login`
   - Klik "Login dengan Google"
   - Seharusnya tidak ada error SSL lagi

### 4. 📝 Logs untuk Debugging

Cek file `storage/logs/laravel.log` untuk melihat:
- Google OAuth callback started
- Google user data received
- User creation/login process

### 5. ⚠️ Security Note

**PENTING**: Disable SSL verification hanya untuk development!
Untuk production, gunakan solusi dengan CA bundle yang proper.
