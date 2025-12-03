<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected $client;
    protected $token;
    protected $baseUrl = 'https://api.fonnte.com';

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, // Disable SSL verification for development
            'timeout' => 30,
            'http_errors' => false,
        ]);
        $this->token = config('services.fonnte.token');
    }

    /**
     * Send WhatsApp message
     */
    public function sendMessage($phone, $message)
    {
        // Check if WhatsApp notifications are enabled
        if (!\App\Models\SystemSetting::isEnabled('whatsapp_notification_enabled')) {
            Log::info('WhatsApp notification skipped (disabled in settings)', [
                'phone' => $phone,
                'message_preview' => substr($message, 0, 50) . '...'
            ]);
            return [
                'status' => false,
                'message' => 'WhatsApp notifications are disabled in system settings'
            ];
        }

        try {
            // Clean phone number - remove leading 0 if present
            $cleanPhone = $phone;
            if (strpos($phone, '+62') === 0) {
                // Already has +62 prefix
                $cleanPhone = $phone;
            } elseif (strpos($phone, '62') === 0) {
                // Has 62 prefix, add +
                $cleanPhone = '+' . $phone;
            } elseif (strpos($phone, '0') === 0) {
                // Has 0 prefix, replace with +62
                $cleanPhone = '+62' . substr($phone, 1);
            } else {
                // No prefix, add +62
                $cleanPhone = '+62' . $phone;
            }
            
            // Remove any non-digit characters except +
            $cleanPhone = preg_replace('/[^0-9+]/', '', $cleanPhone);
            
            Log::info('Sending WhatsApp message', [
                'original_phone' => $phone,
                'clean_phone' => $cleanPhone,
                'message_length' => strlen($message)
            ]);
            
            $response = $this->client->post($this->baseUrl . '/send', [
                'headers' => [
                    'Authorization' => $this->token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'target' => $cleanPhone,
                    'message' => $message,
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            Log::info('Fonnte API Response', [
                'phone' => $phone,
                'status' => $result['status'] ?? 'unknown',
                'response' => $result
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Fonnte API Error', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send registration success message
     */
    public function sendRegistrationSuccess($phone, $name, $role = 'Mahasiswa')
    {
        $message = "🎉 *Selamat!* 🎉\n\n";
        $message .= "Halo *{$name}*,\n\n";
        $message .= "Anda telah berhasil mendaftar ke *Sistem Informasi Pengelolaan PKL (SIP PKL)* sebagai *{$role}*.\n\n";
        $message .= "📱 *Akses Dashboard:*\n";
        $message .= "Silakan login ke dashboard untuk mulai menggunakan sistem.\n\n";
        $message .= "🔗 *Link Dashboard:*\n";
        $message .= "http://localhost:8000/dashboard\n\n";
        $message .= "📞 *Bantuan:*\n";
        $message .= "Jika ada pertanyaan, silakan hubungi admin.\n\n";
        $message .= "Terima kasih! 🙏";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send document validation notification
     */
    public function sendDocumentValidation($phone, $name, $documentType, $status, $notes = null)
    {
        $statusText = $status === 'tervalidasi' ? '✅ Tervalidasi' : 
                     ($status === 'belum_valid' ? '❌ Belum Valid' : '⚠️ Perlu Revisi');
        
        $message = "📄 *Notifikasi Validasi Dokumen*\n\n";
        $message .= "Halo *{$name}*,\n\n";
        $message .= "Dokumen *{$documentType}* Anda telah divalidasi dengan status: *{$statusText}*\n\n";
        
        if ($notes) {
            $message .= "📝 *Catatan:*\n";
            $message .= "{$notes}\n\n";
        }
        
        $message .= "Silakan cek dashboard untuk detail lebih lanjut.\n\n";
        $message .= "Terima kasih! 🙏";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send login notification
     */
    public function sendLoginNotification($phone, $name, $role)
    {
        $message = "🔐 *Notifikasi Login*\n\n";
        $message .= "Halo *{$name}*,\n\n";
        $message .= "Anda baru saja login ke *SIP PKL* sebagai *{$role}*.\n\n";
        $message .= "Jika ini bukan Anda, segera hubungi admin.\n\n";
        $message .= "Terima kasih! 🙏";

        return $this->sendMessage($phone, $message);
    }
}
