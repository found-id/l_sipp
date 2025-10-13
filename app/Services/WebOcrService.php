<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WebOcrService
{
    /**
     * Extract text from PDF using web-based OCR services
     */
    public function extractTextFromPdf($filePath)
    {
        try {
            // Method 1: Try OCR.space API (free tier available)
            $text = $this->extractWithOcrSpace($filePath);
            if (!empty($text)) {
                Log::info('Text extracted using OCR.space API');
                return $text;
            }
            
            // Method 2: Try Free OCR API
            $text = $this->extractWithFreeOcr($filePath);
            if (!empty($text)) {
                Log::info('Text extracted using Free OCR API');
                return $text;
            }
            
            // Method 3: Try Google Vision API (if API key is available)
            $text = $this->extractWithGoogleVision($filePath);
            if (!empty($text)) {
                Log::info('Text extracted using Google Vision API');
                return $text;
            }
            
            // Method 4: Basic PDF text extraction
            $text = $this->extractBasicPdfText($filePath);
            if (!empty($text)) {
                Log::info('Text extracted using basic PDF extraction');
                return $text;
            }
            
            return '';
            
        } catch (\Exception $e) {
            Log::error('Web OCR extraction error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Extract text using OCR.space API
     */
    private function extractWithOcrSpace($filePath)
    {
        try {
            $apiKey = config('services.ocr_space.api_key');
            if (!$apiKey) {
                // Use free tier (limited requests)
                $apiKey = 'helloworld';
            }
            
            // Convert PDF to images first
            $images = $this->convertPdfToImages($filePath);
            if (empty($images)) {
                return '';
            }
            
            $text = '';
            foreach ($images as $imagePath) {
                $response = Http::timeout(60)->attach('file', file_get_contents($imagePath), basename($imagePath))
                    ->post('https://api.ocr.space/parse/image', [
                        'apikey' => $apiKey,
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
                        $text .= $data['ParsedResults'][0]['ParsedText'] . ' ';
                    }
                }
                
                // Clean up temporary image
                unlink($imagePath);
            }
            
            return trim($text);
            
        } catch (\Exception $e) {
            Log::error('OCR.space API error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Extract text using Free OCR API
     */
    private function extractWithFreeOcr($filePath)
    {
        try {
            // Convert PDF to images first
            $images = $this->convertPdfToImages($filePath);
            if (empty($images)) {
                return '';
            }
            
            $text = '';
            foreach ($images as $imagePath) {
                $response = Http::timeout(60)->attach('file', file_get_contents($imagePath), basename($imagePath))
                    ->post('https://api.free-ocr.com/ocr', [
                        'language' => 'ind',
                        'format' => 'json'
                    ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['text'])) {
                        $text .= $data['text'] . ' ';
                    }
                }
                
                // Clean up temporary image
                unlink($imagePath);
            }
            
            return trim($text);
            
        } catch (\Exception $e) {
            Log::error('Free OCR API error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Extract text using Google Vision API
     */
    private function extractWithGoogleVision($filePath)
    {
        try {
            $apiKey = config('services.google.vision_api_key');
            if (!$apiKey) {
                return '';
            }
            
            // Convert PDF to images first
            $images = $this->convertPdfToImages($filePath);
            if (empty($images)) {
                return '';
            }
            
            $text = '';
            foreach ($images as $imagePath) {
                $imageData = base64_encode(file_get_contents($imagePath));
                
                $response = Http::timeout(30)->post("https://vision.googleapis.com/v1/images:annotate?key={$apiKey}", [
                    'requests' => [
                        [
                            'image' => [
                                'content' => $imageData
                            ],
                            'features' => [
                                [
                                    'type' => 'TEXT_DETECTION',
                                    'maxResults' => 1
                                ]
                            ]
                        ]
                    ]
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['responses'][0]['textAnnotations'][0]['description'])) {
                        $text .= $data['responses'][0]['textAnnotations'][0]['description'] . ' ';
                    }
                }
                
                // Clean up temporary image
                unlink($imagePath);
            }
            
            return trim($text);
            
        } catch (\Exception $e) {
            Log::error('Google Vision API error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Basic PDF text extraction
     */
    private function extractBasicPdfText($filePath)
    {
        try {
            $content = file_get_contents($filePath);
            if (!$content) {
                return '';
            }
            
            $text = '';
            
            // Extract text between BT and ET markers
            if (preg_match_all('/BT\s*(.*?)\s*ET/s', $content, $matches)) {
                foreach ($matches[1] as $match) {
                    if (preg_match_all('/\((.*?)\)/s', $match, $textMatches)) {
                        foreach ($textMatches[1] as $textMatch) {
                            $text .= $textMatch . ' ';
                        }
                    }
                }
            }
            
            // Extract text from stream objects
            if (empty($text) && preg_match_all('/stream\s*(.*?)\s*endstream/s', $content, $streamMatches)) {
                foreach ($streamMatches[1] as $stream) {
                    $cleanStream = preg_replace('/[^\x20-\x7E\s]/', '', $stream);
                    if (strlen($cleanStream) > 10) {
                        $text .= $cleanStream . ' ';
                    }
                }
            }
            
            return trim($text);
            
        } catch (\Exception $e) {
            Log::error('Basic PDF extraction error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Convert PDF to images using basic method
     */
    private function convertPdfToImages($filePath)
    {
        try {
            // For now, return empty array since we don't have Imagick
            // In production, you would use Imagick or other PDF to image conversion
            return [];
            
        } catch (\Exception $e) {
            Log::error('PDF to image conversion error: ' . $e->getMessage());
            return [];
        }
    }
}

