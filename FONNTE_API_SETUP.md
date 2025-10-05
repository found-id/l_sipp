# 📱 Setup Fonnte WhatsApp API

## 🔑 API Configuration

### 1. Daftar di Fonnte
- Kunjungi: https://fonnte.com
- Daftar akun dan verifikasi nomor WhatsApp
- Dapatkan API Token dari dashboard

### 2. Update .env File
Tambahkan konfigurasi berikut ke file `.env`:

```env
# Fonnte WhatsApp API Configuration
FONNTE_TOKEN=your_fonnte_api_token_here
FONNTE_DEVICE=default
```

### 3. Contoh Token
```
FONNTE_TOKEN=abc123def456ghi789jkl012mno345pqr678stu901vwx234yz
```

## 🚀 Features yang Tersedia

### ✅ Registrasi Otomatis
- Kirim notifikasi WhatsApp setelah registrasi berhasil
- Pesan berisi link dashboard dan informasi akun

### ✅ Notifikasi Validasi Dokumen
- Kirim notifikasi saat dokumen divalidasi
- Status: Tervalidasi, Belum Valid, atau Perlu Revisi

### ✅ Notifikasi Login
- Kirim notifikasi saat user login (opsional)

## 📝 Format Pesan

### Registrasi Berhasil
```
🎉 Selamat! 🎉

Halo [Nama],

Anda telah berhasil mendaftar ke Sistem Informasi Pengelolaan PKL (SIPP PKL) sebagai [Role].

📱 Akses Dashboard:
Silakan login ke dashboard untuk mulai menggunakan sistem.

🔗 Link Dashboard:
http://localhost:8000/dashboard

📞 Bantuan:
Jika ada pertanyaan, silakan hubungi admin.

Terima kasih! 🙏
```

### Validasi Dokumen
```
📄 Notifikasi Validasi Dokumen

Halo [Nama],

Dokumen [Jenis Dokumen] Anda telah divalidasi dengan status: [Status]

📝 Catatan:
[Catatan jika ada]

Silakan cek dashboard untuk detail lebih lanjut.

Terima kasih! 🙏
```

## 🔧 Testing

### Test API Connection
```bash
php artisan tinker
```

```php
use App\Services\FonnteService;

$fonnte = new FonnteService();
$result = $fonnte->sendMessage('+6281234567890', 'Test message from SIPP PKL');
dd($result);
```

## 📊 Monitoring

- Logs tersimpan di `storage/logs/laravel.log`
- Cek response API di log untuk debugging
- Monitor quota dan status API di dashboard Fonnte

## 🛠️ Troubleshooting

### Error: "Invalid Token"
- Pastikan token benar di `.env`
- Cek token di dashboard Fonnte

### Error: "Device Not Connected"
- Pastikan WhatsApp terhubung di dashboard Fonnte
- Restart koneksi device

### Error: "Quota Exceeded"
- Cek quota di dashboard Fonnte
- Upgrade paket jika diperlukan

## 📞 Support

- Fonnte Documentation: https://fonnte.com/docs
- SIPP PKL Support: Contact Admin
