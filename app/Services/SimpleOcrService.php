<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SimpleOcrService
{
    /**
     * Extract text from PDF using multiple methods
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
            
            // Method 2: Try Azure Computer Vision API
            $text = $this->extractWithAzureVision($filePath);
            if (!empty($text)) {
                Log::info('Text extracted using Azure Vision API');
                return $text;
            }
            
            // Method 3: Try AWS Textract
            $text = $this->extractWithAwsTextract($filePath);
            if (!empty($text)) {
                Log::info('Text extracted using AWS Textract');
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
     * Extract text using Azure Computer Vision API
     */
    private function extractWithAzureVision($filePath)
    {
        try {
            $endpoint = config('services.azure.vision_endpoint');
            $apiKey = config('services.azure.vision_api_key');
            
            if (!$endpoint || !$apiKey) {
                return '';
            }
            
            // Convert PDF to images first
            $images = $this->convertPdfToImages($filePath);
            if (empty($images)) {
                return '';
            }
            
            $text = '';
            foreach ($images as $imagePath) {
                $imageData = file_get_contents($imagePath);
                
                $response = Http::timeout(30)->withHeaders([
                    'Ocp-Apim-Subscription-Key' => $apiKey,
                    'Content-Type' => 'application/octet-stream'
                ])->post($endpoint . '/vision/v3.2/read/analyze', $imageData);
                
                if ($response->successful()) {
                    $operationLocation = $response->header('Operation-Location');
                    if ($operationLocation) {
                        // Wait for processing and get results
                        $result = $this->getAzureVisionResult($operationLocation, $apiKey);
                        if ($result) {
                            $text .= $result . ' ';
                        }
                    }
                }
                
                // Clean up temporary image
                unlink($imagePath);
            }
            
            return trim($text);
            
        } catch (\Exception $e) {
            Log::error('Azure Vision API error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Get Azure Vision API result
     */
    private function getAzureVisionResult($operationLocation, $apiKey)
    {
        try {
            $maxAttempts = 10;
            $attempt = 0;
            
            while ($attempt < $maxAttempts) {
                sleep(1); // Wait 1 second
                
                $response = Http::withHeaders([
                    'Ocp-Apim-Subscription-Key' => $apiKey
                ])->get($operationLocation);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['status']) && $data['status'] === 'succeeded') {
                        $text = '';
                        if (isset($data['analyzeResult']['readResults'])) {
                            foreach ($data['analyzeResult']['readResults'] as $page) {
                                if (isset($page['lines'])) {
                                    foreach ($page['lines'] as $line) {
                                        if (isset($line['text'])) {
                                            $text .= $line['text'] . ' ';
                                        }
                                    }
                                }
                            }
                        }
                        return trim($text);
                    }
                }
                
                $attempt++;
            }
            
            return '';
            
        } catch (\Exception $e) {
            Log::error('Azure Vision result error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Extract text using AWS Textract
     */
    private function extractWithAwsTextract($filePath)
    {
        try {
            $accessKey = config('services.aws.access_key');
            $secretKey = config('services.aws.secret_key');
            $region = config('services.aws.region', 'us-east-1');
            
            if (!$accessKey || !$secretKey) {
                return '';
            }
            
            // This would require AWS SDK - for now return empty
            return '';
            
        } catch (\Exception $e) {
            Log::error('AWS Textract error: ' . $e->getMessage());
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

