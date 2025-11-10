<?php

namespace App\Helpers;

class LanguageDetector
{
    /**
     * Detect if text is primarily Arabic or English
     *
     * @param  string  $text  Text to analyze
     * @return string Language code: 'ar' for Arabic, 'en' for English
     */
    public static function detect(string $text): string
    {
        // Remove whitespace and newlines for accurate counting
        $cleanText = preg_replace('/\s+/', '', $text);

        // Count Arabic characters (Unicode range for Arabic script)
        $arabicCount = preg_match_all('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u', $cleanText);

        // Count English/Latin characters
        $englishCount = preg_match_all('/[a-zA-Z]/u', $cleanText);

        // Total meaningful characters
        $totalChars = $arabicCount + $englishCount;

        // If no meaningful characters, default to Arabic
        if ($totalChars === 0) {
            return 'ar';
        }

        // Calculate percentages
        $arabicPercentage = ($arabicCount / $totalChars) * 100;

        // If more than 50% Arabic characters, consider it Arabic
        return $arabicPercentage > 50 ? 'ar' : 'en';
    }

    /**
     * Check if text is Arabic
     *
     * @param  string  $text  Text to check
     * @return bool True if text is primarily Arabic
     */
    public static function isArabic(string $text): bool
    {
        return self::detect($text) === 'ar';
    }

    /**
     * Check if text is English
     *
     * @param  string  $text  Text to check
     * @return bool True if text is primarily English
     */
    public static function isEnglish(string $text): bool
    {
        return self::detect($text) === 'en';
    }

    /**
     * Get language name in Arabic
     *
     * @param  string  $languageCode  Language code (ar or en)
     * @return string Language name in Arabic
     */
    public static function getLanguageNameArabic(string $languageCode): string
    {
        return match ($languageCode) {
            'ar' => 'العربية',
            'en' => 'الإنجليزية',
            default => 'غير معروف'
        };
    }

    /**
     * Get language name in English
     *
     * @param  string  $languageCode  Language code (ar or en)
     * @return string Language name in English
     */
    public static function getLanguageNameEnglish(string $languageCode): string
    {
        return match ($languageCode) {
            'ar' => 'Arabic',
            'en' => 'English',
            default => 'Unknown'
        };
    }
}
