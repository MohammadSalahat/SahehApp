<?php

namespace App\Console\Commands;

use App\Services\ChatGPTService;
use App\Services\WebScrapingService;
use Illuminate\Console\Command;

class TestSourceVerification extends Command
{
    protected $signature = 'test:source-verification {text}';
    protected $description = 'Test the source verification system';

    public function handle()
    {
        $text = $this->argument('text');
        
        $this->info("Testing source verification for text:");
        $this->line("\"$text\"");
        $this->newLine();

        // Test web scraping service
        $this->info("Step 1: Testing Web Scraping Service");
        $webScrapingService = app(WebScrapingService::class);
        $sources = \App\Models\Source::active()->minReliability(0.7)->limit(3)->get()->toArray();
        
        $this->info("Found " . count($sources) . " trusted sources to check");
        
        $sourceResults = $webScrapingService->searchInTrustedSources($text, $sources);
        
        $this->table([
            'Metric', 'Value'
        ], [
            ['Sources Searched', $sourceResults['sources_searched']],
            ['Sources Accessible', $sourceResults['sources_accessible']],
            ['Matches Found', $sourceResults['sources_with_matches']],
            ['Found in Sources', $sourceResults['found_in_sources'] ? 'YES' : 'NO'],
            ['Highest Similarity', round($sourceResults['highest_similarity'] * 100, 2) . '%'],
        ]);

        if (!empty($sourceResults['matching_sources'])) {
            $this->info("Matching Sources Found:");
            foreach ($sourceResults['matching_sources'] as $match) {
                $this->line("• {$match['source_name']}: " . round($match['similarity'] * 100, 1) . "% match");
                $this->line("  URL: {$match['article_url']}");
                if (!empty($match['title'])) {
                    $this->line("  Title: {$match['title']}");
                }
                $this->newLine();
            }
        }

        // Test ChatGPT service if configured
        if (config('services.chatgpt.enabled') && !empty(config('services.chatgpt.api_key'))) {
            $this->newLine();
            $this->info("Step 2: Testing ChatGPT with Source Verification");
            
            try {
                $chatGPTService = app(ChatGPTService::class);
                $result = $chatGPTService->verifyNews($text);
                
                $this->table([
                    'Field', 'Value'
                ], [
                    ['Method', $result['method']],
                    ['Is Potentially Fake', $result['is_potentially_fake'] ? 'YES' : 'NO'],
                    ['Confidence Score', round($result['confidence_score'] * 100, 1) . '%'],
                    ['Credibility Level', $result['credibility_level']],
                    ['Found in Sources', $result['source_verification_status']['found_in_sources'] ? 'YES' : 'NO'],
                    ['Sources Searched', $result['source_verification_status']['sources_searched']],
                    ['Matching Sources', implode(', ', $result['source_verification_status']['matching_sources'])],
                    ['Tokens Used', $result['tokens_used']],
                ]);

                if (!empty($result['analysis'])) {
                    $this->newLine();
                    $this->info("Analysis (Arabic):");
                    $this->line($result['analysis']['ar']);
                    $this->newLine();
                    $this->info("Analysis (English):");
                    $this->line($result['analysis']['en']);
                }

            } catch (\Exception $e) {
                $this->error("ChatGPT test failed: " . $e->getMessage());
            }
        } else {
            $this->warn("ChatGPT not configured. Skipping ChatGPT test.");
        }

        return 0;
    }
}