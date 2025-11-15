<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebScrapingService
{
    /**
     * Search for news content in trusted sources
     */
    public function searchInTrustedSources(string $content, array $sources): array
    {
        $results = [];
        $searchTerms = $this->extractSearchTerms($content);
        
        Log::info('Starting web scraping search', [
            'sources_count' => count($sources),
            'search_terms' => $searchTerms
        ]);

        foreach ($sources as $source) {
            try {
                $result = $this->searchInSource($source, $searchTerms, $content);
                $results[] = array_merge($result, ['source' => $source]);
            } catch (Exception $e) {
                Log::warning('Failed to search in source', [
                    'source' => $source['name'],
                    'error' => $e->getMessage()
                ]);
                
                $results[] = [
                    'source' => $source,
                    'found' => false,
                    'similarity' => 0,
                    'matched_content' => null,
                    'url' => null,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $this->processSearchResults($results, $content);
    }

    /**
     * Search for content in a specific source
     */
    protected function searchInSource(array $source, array $searchTerms, string $originalContent): array
    {
        $baseUrl = $source['url'];
        
        // Try different search strategies based on the source
        $searchResults = $this->performSourceSearch($baseUrl, $searchTerms, $source['name']);
        
        if (empty($searchResults)) {
            return [
                'found' => false,
                'similarity' => 0,
                'matched_content' => null,
                'url' => null
            ];
        }

        // Find best match among search results
        $bestMatch = $this->findBestMatch($searchResults, $originalContent);
        
        return $bestMatch;
    }

    /**
     * Perform search based on source type
     */
    protected function performSourceSearch(string $baseUrl, array $searchTerms, string $sourceName): array
    {
        // For Saudi Ministry of Justice
        if (str_contains($baseUrl, 'moj.gov.sa')) {
            return $this->searchMojSaudi($baseUrl, $searchTerms);
        }
        
        // For BBC Arabic
        if (str_contains($baseUrl, 'bbc.com/arabic')) {
            return $this->searchBBCArabic($baseUrl, $searchTerms);
        }
        
        // For Al Jazeera
        if (str_contains($baseUrl, 'aljazeera.net')) {
            return $this->searchAlJazeera($baseUrl, $searchTerms);
        }
        
        // Generic search for other sources
        return $this->performGenericSearch($baseUrl, $searchTerms);
    }

    /**
     * Search in Saudi Ministry of Justice website
     */
    protected function searchMojSaudi(string $baseUrl, array $searchTerms): array
    {
        $results = [];

        // URLs to try for MOJ Saudi - prioritize recent news pages
        $urlsToTry = [
            // Specific recent news articles (manually added important ones)
            'https://www.moj.gov.sa/ar/MediaCenter/News/Pages/NewsDetails.aspx?itemId=1743',
            'https://www.moj.gov.sa/ar/MediaCenter/News/Pages/NewsDetails.aspx?itemId=1742',
            'https://www.moj.gov.sa/ar/MediaCenter/News/Pages/NewsDetails.aspx?itemId=1741',
            'https://www.moj.gov.sa/ar/MediaCenter/News/Pages/NewsDetails.aspx?itemId=1740',
            // Try a range of recent item IDs
            ...array_map(fn($id) => "https://www.moj.gov.sa/ar/MediaCenter/News/Pages/NewsDetails.aspx?itemId={$id}", range(1735, 1745)),
            // General pages
            $baseUrl . '/ar/MediaCenter/News/Pages/default.aspx',
            $baseUrl . '/ar/MediaCenter/News/',
            $baseUrl . '/ar/',
        ];
        
        $urlsChecked = 0;
        $maxUrlsToCheck = 15; // Limit to avoid long delays

        foreach ($urlsToTry as $url) {
            if ($urlsChecked >= $maxUrlsToCheck) {
                Log::info("Reached maximum URLs to check ({$maxUrlsToCheck}), stopping");
                break;
            }

            try {
                Log::info("Checking MOJ URL: $url");
                $urlsChecked++;

                $response = Http::timeout(10) // Reduced timeout
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'ar-SA,ar;q=0.9,en;q=0.8',
                        'Accept-Encoding' => 'gzip, deflate, br',
                        'Connection' => 'keep-alive',
                        'Upgrade-Insecure-Requests' => '1',
                    ])
                    ->get($url);

                if ($response->successful()) {
                    $html = $response->body();
                    $contentLength = strlen($html);
                    Log::info("Successfully fetched content from: $url", ['content_length' => $contentLength]);

                    // Skip if content is too small (likely error page)
                    if ($contentLength < 500) {
                        Log::info("Content too small, skipping");
                        continue;
                    }

                    // Extract news items
                    $newsItems = $this->extractMojNewsItems($html, $url);
                    Log::info("Extracted news items", ['count' => count($newsItems)]);

                    // Search through news items
                    foreach ($newsItems as $item) {
                        $searchContent = $item['title'] . ' ' . $item['content'];
                        $similarity = $this->calculateContentSimilarity($searchTerms, $searchContent);

                        Log::info("Similarity check", [
                            'title' => substr($item['title'], 0, 100),
                            'content_preview' => substr($item['content'], 0, 150),
                            'similarity' => $similarity,
                            'url' => $url
                        ]);

                        if ($similarity >= 0.60) { // Require 60%+ similarity for authentic matches
                            $results[] = [
                                'title' => $item['title'],
                                'content' => $item['content'],
                                'full_text' => $searchContent, // Include full text for ChatGPT comparison
                                'url' => $item['url'] ?: $url,
                                'similarity' => $similarity,
                                'date' => $item['date'] ?? null
                            ];
                        }
                    }

                    // If we found good results (>=80% match), stop searching
                    if (!empty($results)) {
                        $highestSimilarity = max(array_column($results, 'similarity'));
                        if ($highestSimilarity >= 0.80) {
                            Log::info("Found high similarity match ({$highestSimilarity}), stopping search");
                            break;
                        }
                    }
                } else {
                    Log::warning("Failed to fetch MOJ URL: $url", ['status' => $response->status()]);
                }
            } catch (Exception $e) {
                Log::error("MOJ Saudi search failed for URL: $url", ['error' => $e->getMessage()]);
            }
        }

        Log::info("MOJ search completed", ['results_count' => count($results)]);

        return $results;
    }

    /**
     * Extract news items from MOJ Saudi website
     */
    protected function extractMojNewsItems(string $html, string $currentUrl = ''): array
    {
        $items = [];

        // For MOJ website, try to get the full page content first as it might be a single page
        $fullText = strip_tags($html);
        $title = $this->extractTitle($html);

        // If this looks like a news article page, return it as a single item
        if ($this->looksLikeNewsArticle($html, $fullText)) {
            $items[] = [
                'title' => $title,
                'content' => $fullText,
                'url' => $currentUrl,
                'date' => $this->extractDateFromContent($fullText)
            ];
        }
        
        // Also try to parse as a news listing page
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();
        
        $xpath = new \DOMXPath($dom);
        
        // Look for common news patterns in Saudi government sites
        $selectors = [
            '//div[contains(@class, "news")]',
            '//div[contains(@class, "item")]',
            '//article',
            '//div[contains(@class, "content")]',
            '//div[contains(@class, "post")]',
            '//li[contains(@class, "news")]',
            '//div[contains(@id, "news")]'
        ];
        
        foreach ($selectors as $selector) {
            $newsNodes = $xpath->query($selector);
            
            foreach ($newsNodes as $node) {
                $title = '';
                $content = '';
                $url = '';
                
                // Extract title - try multiple patterns
                $titleSelectors = ['.//h1', './/h2', './/h3', './/h4', './/a[contains(@class, "title")]', './/strong'];
                foreach ($titleSelectors as $titleSelector) {
                    $titleNode = $xpath->query($titleSelector, $node)->item(0);
                    if ($titleNode && !empty(trim($titleNode->textContent))) {
                        $title = trim($titleNode->textContent);
                        break;
                    }
                }
                
                // Extract content
                $content = trim($node->textContent);
                
                // Extract URL
                $linkNode = $xpath->query('.//a[@href]', $node)->item(0);
                if ($linkNode) {
                    $href = $linkNode->getAttribute('href');
                    $url = str_starts_with($href, 'http') ? $href : 'https://www.moj.gov.sa' . $href;
                }
                
                // Only add if we have substantial content
                if (!empty($title) && mb_strlen($content) > 50) {
                    $items[] = [
                        'title' => $title,
                        'content' => $content,
                        'url' => $url,
                        'date' => $this->extractDateFromContent($content)
                    ];
                }
            }
        }
        
        return $items;
    }

    /**
     * Check if HTML content looks like a news article
     */
    protected function looksLikeNewsArticle(string $html, string $text): bool
    {
        // First check: content length must be substantial
        if (mb_strlen($text) < 200) {
            return false;
        }

        // Check for Arabic news indicators (general)
        $arabicIndicators = [
            'وزارة',
            'الخبر',
            'أعلن',
            'أعلنت',
            'المملكة',
            'السعودية',
            'الرياض',
            'جدة',
            'مكة',
            'المدينة'
        ];

        $found = 0;
        foreach ($arabicIndicators as $indicator) {
            if (mb_stripos($text, $indicator) !== false) {
                $found++;
            }
        }

        // If found multiple Arabic indicators, it's likely an Arabic news page
        if ($found >= 3) {
            return true;
        }

        // Specific check: Look for SharePoint publishing structure
        // Check if HTML contains typical news page elements
        $htmlIndicators = [
            'PublishingPageContent',
            'NewsDetails',
            'pageContent',
            'article',
            'news-content',
            'MediaCenter'
        ];

        foreach ($htmlIndicators as $indicator) {
            if (stripos($html, $indicator) !== false) {
                return mb_strlen($text) >= 500; // If has HTML indicators, just check length
            }
        }

        return false;
    }

    /**
     * Extract date from content
     */
    protected function extractDateFromContent(string $content): ?string
    {
        // Look for Arabic date patterns
        $patterns = [
            '/في \d{1,2} [^\s]+ \d{4}/',
            '/\d{1,2}\/\d{1,2}\/\d{4}/',
            '/\d{4}-\d{2}-\d{2}/',
            '/نوفمبر \d{4}/',
            '/الثالث والعشرين من نوفمبر/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                return $matches[0];
            }
        }
        
        return null;
    }

    /**
     * Search in BBC Arabic
     */
    protected function searchBBCArabic(string $baseUrl, array $searchTerms): array
    {
        // Implement BBC Arabic search logic
        return $this->performGenericSearch($baseUrl, $searchTerms);
    }

    /**
     * Search in Al Jazeera
     */
    protected function searchAlJazeera(string $baseUrl, array $searchTerms): array
    {
        // Implement Al Jazeera search logic
        return $this->performGenericSearch($baseUrl, $searchTerms);
    }

    /**
     * Generic search method for other sources
     */
    protected function performGenericSearch(string $baseUrl, array $searchTerms): array
    {
        $results = [];
        
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get($baseUrl);

            if ($response->successful()) {
                $html = $response->body();
                $text = strip_tags($html);
                
                $similarity = $this->calculateContentSimilarity($searchTerms, $text);

                if ($similarity >= 0.60) { // Require 60%+ similarity for authentic matches
                    $results[] = [
                        'title' => $this->extractTitle($html),
                        'content' => Str::limit($text, 500),
                        'url' => $baseUrl,
                        'similarity' => $similarity
                    ];
                }
            }
        } catch (Exception $e) {
            Log::error('Generic search failed', ['url' => $baseUrl, 'error' => $e->getMessage()]);
        }

        return $results;
    }

    /**
     * Extract search terms from content
     */
    protected function extractSearchTerms(string $content): array
    {
        $text = trim($content);
        
        // Remove common stop words
        $stopWords = [
            'في', 'من', 'إلى', 'على', 'عن', 'مع', 'هذا', 'هذه', 'التي', 'التي', 'الذي', 'الذي', 'يكون', 'كان', 'كانت',
            'the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'this', 'that', 'is', 'are', 'was', 'were'
        ];
        
        // Extract important terms
        $words = preg_split('/\s+/', $text);
        $terms = [];
        
        foreach ($words as $word) {
            $word = trim($word, '.,!?;:"()[]{}');
            if (mb_strlen($word) > 3 && !in_array(mb_strtolower($word), $stopWords)) {
                $terms[] = $word;
            }
        }
        
        // Also extract key phrases
        $phrases = $this->extractKeyPhrases($text);
        
        return array_merge(array_unique($terms), $phrases);
    }

    /**
     * Extract key phrases from content
     */
    protected function extractKeyPhrases(string $text): array
    {
        $phrases = [];
        
        // Look for specific patterns
        $patterns = [
            // Names with titles
            '/صاحب السمو الملكي الأمير [^،.]+/',
            '/وزارة [^،.]+/',
            '/المؤتمر [^،.]+/',
            '/في [^،.]+ نوفمبر/',
            '/لمدة [^،.]+/',
            // Add more patterns as needed
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                $phrases = array_merge($phrases, $matches[0]);
            }
        }
        
        return $phrases;
    }

    /**
     * Calculate similarity between search terms and content
     */
    protected function calculateContentSimilarity(array $searchTerms, string $content): float
    {
        $content = mb_strtolower($content);
        $matches = 0;
        $totalTerms = count($searchTerms);
        
        if ($totalTerms === 0) {
            return 0;
        }
        
        foreach ($searchTerms as $term) {
            $term = mb_strtolower($term);
            if (mb_strpos($content, $term) !== false) {
                $matches++;
            }
        }
        
        return $matches / $totalTerms;
    }

    /**
     * Find best match among search results
     */
    protected function findBestMatch(array $searchResults, string $originalContent): array
    {
        if (empty($searchResults)) {
            return [
                'found' => false,
                'similarity' => 0,
                'matched_content' => null,
                'url' => null
            ];
        }
        
        // Sort by similarity
        usort($searchResults, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
        
        $bestMatch = $searchResults[0];
        
        return [
            'found' => $bestMatch['similarity'] >= 0.60, // Require 60%+ similarity to avoid false positives
            'similarity' => $bestMatch['similarity'],
            'matched_content' => $bestMatch['content'] ?? $bestMatch['title'],
            'url' => $bestMatch['url'],
            'title' => $bestMatch['title'] ?? null,
            'date' => $bestMatch['date'] ?? null
        ];
    }

    /**
     * Process all search results and return summary
     */
    protected function processSearchResults(array $results, string $originalContent): array
    {
        $foundSources = [];
        $highestSimilarity = 0;
        $bestMatch = null;
        $totalSources = count($results);
        $successfulSources = 0;
        
        foreach ($results as $result) {
            if (isset($result['error'])) {
                continue;
            }
            
            $successfulSources++;
            
            if ($result['found']) {
                $foundSources[] = [
                    'source_name' => $result['source']['name'],
                    'source_url' => $result['source']['url'],
                    'similarity' => $result['similarity'],
                    'matched_content' => $result['matched_content'],
                    'full_text' => $result['full_text'] ?? $result['matched_content'], // Include full scraped text
                    'article_url' => $result['url'],
                    'title' => $result['title'] ?? null,
                    'date' => $result['date'] ?? null
                ];
                
                if ($result['similarity'] > $highestSimilarity) {
                    $highestSimilarity = $result['similarity'];
                    $bestMatch = end($foundSources);
                }
            }
        }
        
        return [
            'sources_searched' => $totalSources,
            'sources_accessible' => $successfulSources,
            'sources_with_matches' => count($foundSources),
            'found_in_sources' => !empty($foundSources),
            'highest_similarity' => $highestSimilarity,
            'best_match' => $bestMatch,
            'matching_sources' => $foundSources,
            'search_summary' => [
                'total_sources' => $totalSources,
                'successful_searches' => $successfulSources,
                'matches_found' => count($foundSources),
                'success_rate' => $totalSources > 0 ? ($successfulSources / $totalSources) * 100 : 0
            ]
        ];
    }

    /**
     * Extract title from HTML
     */
    protected function extractTitle(string $html): string
    {
        if (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $matches)) {
            return trim($matches[1]);
        }
        
        return 'Untitled';
    }
}