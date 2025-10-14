<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OcrService
{
    /**
     * Extract text from PDF using multiple OCR methods
     */
    public function extractTextFromPdf($filePath)
    {
        try {
            // Method 1: Try Google Vision API (if API key is available)
            $text = $this->extractWithGoogleVision($filePath);
            if (!empty($text)) {
                Log::info('Text extracted using Google Vision API');
                return $text;
            }
            
            // Method 2: Try Tesseract OCR
            $text = $this->extractWithTesseract($filePath);
            if (!empty($text)) {
                Log::info('Text extracted using Tesseract OCR');
                return $text;
            }
            
            // Method 3: Try Imagick OCR
            $text = $this->extractWithImagick($filePath);
            if (!empty($text)) {
                Log::info('Text extracted using Imagick OCR');
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
            Log::error('OCR extraction error: ' . $e->getMessage());
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
                
                $response = Http::post("https://vision.googleapis.com/v1/images:annotate?key={$apiKey}", [
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
     * Extract text using Tesseract OCR
     */
    private function extractWithTesseract($filePath)
    {
        try {
            $tesseractPath = $this->findTesseractPath();
            if (!$tesseractPath) {
                return '';
            }
            
            // Convert PDF to images
            $images = $this->convertPdfToImages($filePath);
            if (empty($images)) {
                return '';
            }
            
            $text = '';
            foreach ($images as $imagePath) {
                $command = escapeshellcmd($tesseractPath) . ' ' . escapeshellarg($imagePath) . ' stdout -l ind+eng';
                $output = shell_exec($command);
                
                if ($output) {
                    $text .= $output . ' ';
                }
                
                unlink($imagePath);
            }
            
            return trim($text);
            
        } catch (\Exception $e) {
            Log::error('Tesseract OCR error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Extract text using Imagick
     */
    private function extractWithImagick($filePath)
    {
        try {
            if (!extension_loaded('imagick')) {
                return '';
            }
            
            $imagick = new \Imagick();
            $imagick->setResolution(300, 300);
            $imagick->readImage($filePath);
            
            $text = '';
            foreach ($imagick as $page) {
                $page->setImageFormat('png');
                $imageData = $page->getImageBlob();
                
                // Save temporary image
                $tempImage = tempnam(sys_get_temp_dir(), 'pdf_page_') . '.png';
                file_put_contents($tempImage, $imageData);
                
                // Try to extract text using basic image processing
                $pageText = $this->extractTextFromImage($tempImage);
                $text .= $pageText . ' ';
                
                unlink($tempImage);
            }
            
            $imagick->clear();
            $imagick->destroy();
            
            return trim($text);
            
        } catch (\Exception $e) {
            Log::error('Imagick OCR error: ' . $e->getMessage());
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
     * Convert PDF to images
     */
    private function convertPdfToImages($filePath)
    {
        try {
            if (!extension_loaded('imagick')) {
                return [];
            }
            
            $imagick = new \Imagick();
            $imagick->setResolution(300, 300);
            $imagick->readImage($filePath);
            
            $images = [];
            foreach ($imagick as $pageNumber => $page) {
                $page->setImageFormat('png');
                $tempImage = tempnam(sys_get_temp_dir(), 'pdf_page_' . $pageNumber . '_') . '.png';
                $page->writeImage($tempImage);
                $images[] = $tempImage;
            }
            
            $imagick->clear();
            $imagick->destroy();
            
            return $images;
            
        } catch (\Exception $e) {
            Log::error('PDF to image conversion error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Extract text from image using basic image processing
     */
    private function extractTextFromImage($imagePath)
    {
        try {
            // This is a placeholder - in production you'd use proper OCR
            // For now, we'll return empty string and rely on other methods
            return '';
            
        } catch (\Exception $e) {
            Log::error('Image text extraction error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Find Tesseract executable path
     */
    private function findTesseractPath()
    {
        $possiblePaths = [
            'tesseract',
            '/usr/bin/tesseract',
            '/usr/local/bin/tesseract',
            'C:\\Program Files\\Tesseract-OCR\\tesseract.exe',
            'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe',
        ];
        
        foreach ($possiblePaths as $path) {
            if (is_executable($path) || shell_exec("which $path 2>/dev/null")) {
                return $path;
            }
        }
        
        return null;
    }
}

