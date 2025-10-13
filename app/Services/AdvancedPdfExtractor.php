<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AdvancedPdfExtractor
{
    /**
     * Extract text from PDF using advanced manual parsing
     */
    public function extractText($filePath)
    {
        try {
            $content = file_get_contents($filePath);
            if (!$content) {
                return '';
            }
            
            // Method 1: Try to extract from PDF streams
            $text = $this->extractFromStreams($content);
            if (!empty($text) && $this->isValidText($text)) {
                return $text;
            }
            
            // Method 2: Try to extract from PDF objects
            $text = $this->extractFromObjects($content);
            if (!empty($text) && $this->isValidText($text)) {
                return $text;
            }
            
            // Method 3: Try to extract from text operators
            $text = $this->extractFromTextOperators($content);
            if (!empty($text) && $this->isValidText($text)) {
                return $text;
            }
            
            // Method 4: Try to extract from content streams
            $text = $this->extractFromContentStreams($content);
            if (!empty($text) && $this->isValidText($text)) {
                return $text;
            }
            
            return '';
            
        } catch (\Exception $e) {
            Log::error('Advanced PDF extraction error: ' . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Extract text from PDF streams
     */
    private function extractFromStreams($content)
    {
        $text = '';
        
        // Look for stream objects
        if (preg_match_all('/stream\s*(.*?)\s*endstream/s', $content, $matches)) {
            foreach ($matches[1] as $stream) {
                // Try to decode the stream
                $decoded = $this->decodeStream($stream);
                if ($decoded) {
                    $streamText = $this->extractTextFromDecoded($decoded);
                    if (strlen($streamText) > 50) {
                        $text .= $streamText . ' ';
                    }
                }
            }
        }
        
        return trim($text);
    }
    
    /**
     * Extract text from PDF objects
     */
    private function extractFromObjects($content)
    {
        $text = '';
        
        // Look for text in PDF objects
        if (preg_match_all('/obj\s*(.*?)\s*endobj/s', $content, $matches)) {
            foreach ($matches[1] as $object) {
                $objectText = $this->extractTextFromObject($object);
                if (strlen($objectText) > 20) {
                    $text .= $objectText . ' ';
                }
            }
        }
        
        return trim($text);
    }
    
    /**
     * Extract text from text operators
     */
    private function extractFromTextOperators($content)
    {
        $text = '';
        
        // Look for text operators like Tj, TJ, etc.
        if (preg_match_all('/\((.*?)\)\s*Tj/', $content, $matches)) {
            foreach ($matches[1] as $match) {
                $cleanText = $this->cleanText($match);
                if (strlen($cleanText) > 2) {
                    $text .= $cleanText . ' ';
                }
            }
        }
        
        // Look for text in square brackets
        if (preg_match_all('/\[(.*?)\]\s*TJ/', $content, $matches)) {
            foreach ($matches[1] as $match) {
                $cleanText = $this->cleanText($match);
                if (strlen($cleanText) > 2) {
                    $text .= $cleanText . ' ';
                }
            }
        }
        
        return trim($text);
    }
    
    /**
     * Extract text from content streams
     */
    private function extractFromContentStreams($content)
    {
        $text = '';
        
        // Look for content streams
        if (preg_match_all('/\/Contents\s*\[\s*(\d+)\s+(\d+)\s+R\s*\]/', $content, $matches)) {
            foreach ($matches[0] as $match) {
                $streamText = $this->extractTextFromStream($match);
                if (strlen($streamText) > 20) {
                    $text .= $streamText . ' ';
                }
            }
        }
        
        return trim($text);
    }
    
    /**
     * Decode PDF stream
     */
    private function decodeStream($stream)
    {
        try {
            // Try different decoding methods
            
            // Method 1: Try gzuncompress
            $decoded = @gzuncompress($stream);
            if ($decoded !== false) {
                return $decoded;
            }
            
            // Method 2: Try gzinflate
            $decoded = @gzinflate($stream);
            if ($decoded !== false) {
                return $decoded;
            }
            
            // Method 3: Try base64 decode
            $decoded = @base64_decode($stream);
            if ($decoded !== false && strlen($decoded) > 10) {
                return $decoded;
            }
            
            // Method 4: Return as is if no decoding works
            return $stream;
            
        } catch (\Exception $e) {
            return $stream;
        }
    }
    
    /**
     * Extract text from decoded content
     */
    private function extractTextFromDecoded($content)
    {
        $text = '';
        
        // Look for text between parentheses
        if (preg_match_all('/\((.*?)\)/', $content, $matches)) {
            foreach ($matches[1] as $match) {
                $cleanText = $this->cleanText($match);
                if (strlen($cleanText) > 2) {
                    $text .= $cleanText . ' ';
                }
            }
        }
        
        // Look for text patterns
        if (preg_match_all('/[A-Za-z0-9\s\.\,\:\-\+\/]+/', $content, $matches)) {
            foreach ($matches[0] as $match) {
                $cleanText = trim($match);
                if (strlen($cleanText) > 3 && preg_match('/[A-Za-z]/', $cleanText)) {
                    $text .= $cleanText . ' ';
                }
            }
        }
        
        return $text;
    }
    
    /**
     * Extract text from PDF object
     */
    private function extractTextFromObject($object)
    {
        $text = '';
        
        // Look for text in the object
        if (preg_match_all('/\((.*?)\)/', $object, $matches)) {
            foreach ($matches[1] as $match) {
                $cleanText = $this->cleanText($match);
                if (strlen($cleanText) > 2) {
                    $text .= $cleanText . ' ';
                }
            }
        }
        
        return $text;
    }
    
    /**
     * Extract text from stream
     */
    private function extractTextFromStream($stream)
    {
        $text = '';
        
        // Look for text in the stream
        if (preg_match_all('/\((.*?)\)/', $stream, $matches)) {
            foreach ($matches[1] as $match) {
                $cleanText = $this->cleanText($match);
                if (strlen($cleanText) > 2) {
                    $text .= $cleanText . ' ';
                }
            }
        }
        
        return $text;
    }
    
    /**
     * Clean extracted text
     */
    private function cleanText($text)
    {
        // Remove escape sequences
        $text = str_replace(['\\n', '\\r', '\\t'], [' ', ' ', ' '], $text);
        
        // Remove special characters but keep letters, numbers, and basic punctuation
        $text = preg_replace('/[^\x20-\x7E\s]/', '', $text);
        
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }
    
    /**
     * Validate if extracted text is meaningful
     */
    private function isValidText($text)
    {
        // Check for minimum length
        if (strlen($text) < 50) {
            return false;
        }
        
        // Check for readable characters
        $readableChars = preg_match_all('/[A-Za-z]/', $text);
        if ($readableChars < 20) {
            return false;
        }
        
        // Check for common words
        $commonWords = ['the', 'and', 'or', 'of', 'in', 'to', 'a', 'is', 'that', 'it', 'with', 'for', 'as', 'was', 'on', 'are', 'but', 'not', 'you', 'all', 'can', 'had', 'her', 'was', 'one', 'our', 'out', 'day', 'get', 'has', 'him', 'his', 'how', 'man', 'new', 'now', 'old', 'see', 'two', 'way', 'who', 'boy', 'did', 'its', 'let', 'put', 'say', 'she', 'too', 'use'];
        
        $textLower = strtolower($text);
        $foundWords = 0;
        
        foreach ($commonWords as $word) {
            if (strpos($textLower, $word) !== false) {
                $foundWords++;
            }
        }
        
        // If we found at least 3 common words, consider it valid
        return $foundWords >= 3;
    }
}

