<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UniversalPdfExtractor
{
    private $pdfParser;
    private $tesseractPath;
    private $imageMagickPath;

    public function __construct()
    {
        // Initialize PDF parser
        $this->pdfParser = new \Smalot\PdfParser\Parser();
        
        // Set paths for external tools (adjust based on your system)
        $this->tesseractPath = 'tesseract'; // or full path like 'C:\Program Files\Tesseract-OCR\tesseract.exe'
        $this->imageMagickPath = 'convert'; // or full path like 'C:\Program Files\ImageMagick\convert.exe'
    }

    /**
     * Extract text from PDF using multiple methods
     */
    public function extractText($filePath)
    {
        $methods = [
            'direct_text' => 'extractDirectText',
            'stream_decode' => 'extractFromStreams',
            'ocr_vector' => 'extractWithOcrVector',
            'ocr_image' => 'extractWithOcrImage',
            'online_ocr' => 'extractWithOnlineOcr'
        ];

        $extractedText = '';
        $methodUsed = '';

        foreach ($methods as $methodName => $method) {
            try {
                Log::info("Trying PDF extraction method: {$methodName}");
                
                $text = $this->$method($filePath);
                
                if (!empty($text) && $this->isValidKhsText($text)) {
                    $extractedText = $text;
                    $methodUsed = $methodName;
                    Log::info("Successfully extracted text using method: {$methodName}");
                    break;
                }
                
                Log::info("Method {$methodName} failed or returned invalid text");
                
            } catch (\Exception $e) {
                Log::error("Method {$methodName} failed: " . $e->getMessage());
                continue;
            }
        }

        if (empty($extractedText)) {
            Log::error("All PDF extraction methods failed for file: {$filePath}");
            throw new \Exception("Unable to extract text from PDF using any method");
        }

        Log::info("Final extraction method used: {$methodUsed}");
        return $extractedText;
    }

    /**
     * Method 1: Direct text extraction (for text-based PDFs)
     */
    private function extractDirectText($filePath)
    {
        try {
            $pdf = $this->pdfParser->parseFile($filePath);
            $text = $pdf->getText();
            
            if (!empty($text)) {
                return $this->cleanText($text);
            }
            
            return '';
        } catch (\Exception $e) {
            Log::error("Direct text extraction failed: " . $e->getMessage());
            return '';
        }
    }

    /**
     * Method 2: Extract from PDF streams (for encoded text)
     */
    private function extractFromStreams($filePath)
    {
        try {
            $pdf = $this->pdfParser->parseFile($filePath);
            $text = '';
            
            // Try to extract from different stream types
            foreach ($pdf->getObjects() as $object) {
                if ($object instanceof \Smalot\PdfParser\Object\Stream) {
                    $streamText = $object->getContent();
                    if (!empty($streamText)) {
                        $text .= $streamText . "\n";
                    }
                }
            }
            
            if (!empty($text)) {
                return $this->cleanText($text);
            }
            
            return '';
        } catch (\Exception $e) {
            Log::error("Stream extraction failed: " . $e->getMessage());
            return '';
        }
    }

    /**
     * Method 3: OCR for vector-based PDFs
     */
    private function extractWithOcrVector($filePath)
    {
        try {
            // Convert PDF to images first
            $images = $this->convertPdfToImages($filePath);
            
            if (empty($images)) {
                return '';
            }
            
            $text = '';
            foreach ($images as $imagePath) {
                $ocrText = $this->performOcr($imagePath);
                if (!empty($ocrText)) {
                    $text .= $ocrText . "\n";
                }
                
                // Clean up temporary image
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            return $this->cleanText($text);
            
        } catch (\Exception $e) {
            Log::error("OCR vector extraction failed: " . $e->getMessage());
            return '';
        }
    }

    /**
     * Method 4: OCR for image-based PDFs
     */
    private function extractWithOcrImage($filePath)
    {
        try {
            // Convert PDF to high-quality images
            $images = $this->convertPdfToImages($filePath, 300); // 300 DPI for better OCR
            
            if (empty($images)) {
                return '';
            }
            
            $text = '';
            foreach ($images as $imagePath) {
                // Preprocess image for better OCR
                $processedImage = $this->preprocessImageForOcr($imagePath);
                
                $ocrText = $this->performOcr($processedImage);
                if (!empty($ocrText)) {
                    $text .= $ocrText . "\n";
                }
                
                // Clean up temporary files
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                if (file_exists($processedImage)) {
                    unlink($processedImage);
                }
            }
            
            return $this->cleanText($text);
            
        } catch (\Exception $e) {
            Log::error("OCR image extraction failed: " . $e->getMessage());
            return '';
        }
    }

    /**
     * Method 5: Online OCR services (fallback)
     */
    private function extractWithOnlineOcr($filePath)
    {
        try {
            $webOcrService = new WebOcrService();
            return $webOcrService->extractTextFromPdf($filePath);
        } catch (\Exception $e) {
            Log::error("Online OCR extraction failed: " . $e->getMessage());
            return '';
        }
    }

    /**
     * Convert PDF to images using ImageMagick
     */
    private function convertPdfToImages($filePath, $dpi = 150)
    {
        try {
            $outputDir = storage_path('app/temp/ocr/');
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $outputPattern = $outputDir . 'page_%d.png';
            
            // Use ImageMagick to convert PDF to images
            $command = sprintf(
                '"%s" -density %d "%s" "%s"',
                $this->imageMagickPath,
                $dpi,
                $filePath,
                $outputPattern
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                Log::error("ImageMagick conversion failed: " . implode("\n", $output));
                return [];
            }
            
            // Find generated image files
            $images = [];
            $page = 1;
            while (file_exists($outputDir . "page_{$page}.png")) {
                $images[] = $outputDir . "page_{$page}.png";
                $page++;
            }
            
            return $images;
            
        } catch (\Exception $e) {
            Log::error("PDF to image conversion failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Preprocess image for better OCR
     */
    private function preprocessImageForOcr($imagePath)
    {
        try {
            $processedPath = str_replace('.png', '_processed.png', $imagePath);
            
            // Use ImageMagick to preprocess image
            $command = sprintf(
                '"%s" "%s" -resize 200%% -unsharp 0x0.75+0.75+0.008 -colorspace Gray -normalize "%s"',
                $this->imageMagickPath,
                $imagePath,
                $processedPath
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                Log::error("Image preprocessing failed: " . implode("\n", $output));
                return $imagePath; // Return original if preprocessing fails
            }
            
            return $processedPath;
            
        } catch (\Exception $e) {
            Log::error("Image preprocessing failed: " . $e->getMessage());
            return $imagePath;
        }
    }

    /**
     * Perform OCR using Tesseract
     */
    private function performOcr($imagePath)
    {
        try {
            $outputPath = str_replace('.png', '_ocr.txt', $imagePath);
            
            // Use Tesseract for OCR
            $command = sprintf(
                '%s "%s" "%s" -l eng+ind',
                $this->tesseractPath,
                $imagePath,
                $outputPath
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                Log::error("Tesseract OCR failed: " . implode("\n", $output));
                return '';
            }
            
            if (file_exists($outputPath)) {
                $text = file_get_contents($outputPath);
                unlink($outputPath); // Clean up OCR output file
                return $text;
            }
            
            return '';
            
        } catch (\Exception $e) {
            Log::error("OCR failed: " . $e->getMessage());
            return '';
        }
    }

    /**
     * Clean extracted text
     */
    private function cleanText($text)
    {
        // Remove extra whitespace and normalize
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // Remove non-printable characters
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        return $text;
    }

    /**
     * Validate if extracted text looks like KHS content
     */
    private function isValidKhsText($text)
    {
        $khsKeywords = [
            'KARTU HASIL STUDI',
            'KHS',
            'SEMESTER',
            'NIM',
            'PROGRAM STUDI',
            'MATA KULIAH',
            'SKS',
            'NILAI',
            'IPS'
        ];
        
        $foundKeywords = 0;
        foreach ($khsKeywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                $foundKeywords++;
            }
        }
        
        // Consider valid if at least 3 keywords are found
        return $foundKeywords >= 3;
    }

    /**
     * Get extraction method info
     */
    public function getExtractionInfo($filePath)
    {
        $info = [
            'file_path' => $filePath,
            'file_size' => filesize($filePath),
            'methods_available' => [],
            'tools_installed' => []
        ];
        
        // Check if tools are available
        $info['tools_installed']['tesseract'] = $this->isToolAvailable($this->tesseractPath);
        $info['tools_installed']['imagemagick'] = $this->isToolAvailable($this->imageMagickPath);
        
        // Check available methods
        $info['methods_available']['direct_text'] = true; // Always available
        $info['methods_available']['stream_decode'] = true; // Always available
        $info['methods_available']['ocr_vector'] = $info['tools_installed']['tesseract'] && $info['tools_installed']['imagemagick'];
        $info['methods_available']['ocr_image'] = $info['tools_installed']['tesseract'] && $info['tools_installed']['imagemagick'];
        $info['methods_available']['online_ocr'] = true; // Always available as fallback
        
        return $info;
    }

    /**
     * Check if external tool is available
     */
    private function isToolAvailable($toolPath)
    {
        try {
            exec($toolPath . ' --version 2>&1', $output, $returnCode);
            return $returnCode === 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}
