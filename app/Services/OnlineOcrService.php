<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OnlineOcrService
{
    /**
     * Extract text from PDF using online OCR services
     */
    public function extractTextFromPdf($filePath)
    {
        try {
            $fullPath = storage_path('app/public/' . $filePath);
            
            if (!file_exists($fullPath)) {
                throw new \Exception('File not found: ' . $fullPath);
            }
            
            // Convert PDF to images first (we'll use a simple approach)
            $images = $this->convertPdfToImages($fullPath);
            
            if (empty($images)) {
                // If we can't convert to images, try direct PDF upload
                return $this->extractFromPdfDirect($fullPath);
            }
            
            $text = '';
            foreach ($images as $imagePath) {
                $imageText = $this->extractFromImage($imagePath);
                if ($imageText) {
                    $text .= $imageText . ' ';
                }
                // Clean up temporary image
                unlink($imagePath);
            }
            
            return trim($text);
            
        } catch (\Exception $e) {
            Log::error('Online OCR extraction error: ' . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Extract text from PDF directly using online service
     */
    private function extractFromPdfDirect($filePath)
    {
        try {
            // Try using a free online OCR service
            $response = Http::timeout(60)->attach('file', file_get_contents($filePath), basename($filePath))
                ->post('https://api.ocr.space/parse/image', [
                    'apikey' => 'helloworld', // Free tier
                    'language' => 'ind',
                    'isOverlayRequired' => false,
                    'filetype' => 'PDF',
                    'detectOrientation' => true,
                    'scale' => true,
                    'OCREngine' => 2
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['ParsedResults'][0]['ParsedText'])) {
                    return $data['ParsedResults'][0]['ParsedText'];
                }
            }
            
            return '';
            
        } catch (\Exception $e) {
            Log::error('Direct PDF OCR error: ' . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Extract text from image using online OCR
     */
    private function extractFromImage($imagePath)
    {
        try {
            $response = Http::timeout(60)->attach('file', file_get_contents($imagePath), basename($imagePath))
                ->post('https://api.ocr.space/parse/image', [
                    'apikey' => 'helloworld', // Free tier
                    'language' => 'ind',
                    'isOverlayRequired' => false,
                    'filetype' => 'PNG',
                    'detectOrientation' => true,
                    'scale' => true,
                    'OCREngine' => 2
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['ParsedResults'][0]['ParsedText'])) {
                    return $data['ParsedResults'][0]['ParsedText'];
                }
            }
            
            return '';
            
        } catch (\Exception $e) {
            Log::error('Image OCR error: ' . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Convert PDF to images (simplified version)
     */
    private function convertPdfToImages($filePath)
    {
        // For now, return empty array since we don't have ImageMagick
        // In a real implementation, you would use ImageMagick or similar
        return [];
    }
}

