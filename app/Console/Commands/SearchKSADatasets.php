<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SearchKSADatasets extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'datasets:search-ksa 
                            {--academic : Search academic sources}
                            {--government : Search official government sources}
                            {--social : Search social media research datasets}';

    /**
     * The description of the console command.
     */
    protected $description = 'Search for real KSA-related fake news datasets from various sources';

    /**
     * Real potential sources for KSA datasets
     */
    protected array $academicSources = [
        'King Saud University' => [
            'https://www.ksu.edu.sa/research/datasets',
            'https://repositories.ksu.edu.sa/handle/123456789/datasets',
        ],
        'KAUST' => [
            'https://repository.kaust.edu.sa/handle/10754/datasets',
            'https://www.kaust.edu.sa/en/study/faculty/datasets',
        ],
        'KFUPM' => [
            'https://www.kfupm.edu.sa/departments/ics/research/datasets',
        ],
        'Arxiv Arabic NLP' => [
            'https://arxiv.org/search/?query=arabic+fake+news+saudi&searchtype=all',
            'https://arxiv.org/search/?query=arabic+misinformation+detection&searchtype=all',
        ],
    ];

    protected array $governmentSources = [
        'Saudi Data Portal' => [
            'https://data.gov.sa/en/datasets',
            'https://open.data.gov.sa/datasets',
        ],
        'Ministry of Justice' => [
            'https://www.moj.gov.sa/ar/OpenData/Pages/default.aspx',
        ],
        'CITC' => [
            'https://www.citc.gov.sa/en/RulesandSystems/RegulatoryFramework/Pages/OpenData.aspx',
        ],
    ];

    protected array $researchPlatforms = [
        'Kaggle' => [
            'https://www.kaggle.com/search?q=arabic+fake+news',
            'https://www.kaggle.com/search?q=saudi+misinformation',
            'https://www.kaggle.com/search?q=gulf+fake+news',
        ],
        'Google Dataset Search' => [
            'https://datasetsearch.research.google.com/search?query=arabic%20fake%20news',
            'https://datasetsearch.research.google.com/search?query=saudi%20misinformation',
        ],
        'IEEE Dataport' => [
            'https://ieee-dataport.org/search/arabic%20fake%20news',
            'https://ieee-dataport.org/search/saudi%20misinformation',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Searching for real KSA-related fake news datasets...');
        $this->newLine();

        $academic = $this->option('academic');
        $government = $this->option('government');
        $social = $this->option('social');

        if (! $academic && ! $government && ! $social) {
            // Search all by default
            $academic = $government = $social = true;
        }

        $foundDatasets = [];

        if ($academic) {
            $this->info('🎓 Searching Academic Sources...');
            $foundDatasets = array_merge($foundDatasets, $this->searchAcademicSources());
            $this->newLine();
        }

        if ($government) {
            $this->info('🏛️  Searching Government Sources...');
            $foundDatasets = array_merge($foundDatasets, $this->searchGovernmentSources());
            $this->newLine();
        }

        if ($social) {
            $this->info('📱 Searching Research Platforms...');
            $foundDatasets = array_merge($foundDatasets, $this->searchResearchPlatforms());
            $this->newLine();
        }

        $this->displayResults($foundDatasets);
        $this->provideRecommendations();

        return Command::SUCCESS;
    }

    /**
     * Search academic sources
     */
    protected function searchAcademicSources(): array
    {
        $found = [];

        foreach ($this->academicSources as $institution => $urls) {
            $this->line("   🔬 Checking: {$institution}");

            foreach ($urls as $url) {
                try {
                    $response = Http::timeout(5)->head($url);
                    if ($response->successful()) {
                        $found[] = [
                            'type' => 'Academic',
                            'source' => $institution,
                            'url' => $url,
                            'status' => 'accessible',
                            'priority' => 'high',
                        ];
                        $this->line("     ✅ {$url}");
                    } else {
                        $this->line("     ⚠️  {$url} (not accessible)");
                    }
                } catch (\Exception $e) {
                    $this->line("     ❌ {$url} (error)");
                }
            }
        }

        return $found;
    }

    /**
     * Search government sources
     */
    protected function searchGovernmentSources(): array
    {
        $found = [];

        foreach ($this->governmentSources as $department => $urls) {
            $this->line("   🏛️  Checking: {$department}");

            foreach ($urls as $url) {
                try {
                    $response = Http::timeout(5)->head($url);
                    if ($response->successful()) {
                        $found[] = [
                            'type' => 'Government',
                            'source' => $department,
                            'url' => $url,
                            'status' => 'accessible',
                            'priority' => 'very_high',
                        ];
                        $this->line("     ✅ {$url}");
                    } else {
                        $this->line("     ⚠️  {$url} (not accessible)");
                    }
                } catch (\Exception $e) {
                    $this->line("     ❌ {$url} (error)");
                }
            }
        }

        return $found;
    }

    /**
     * Search research platforms
     */
    protected function searchResearchPlatforms(): array
    {
        $found = [];

        foreach ($this->researchPlatforms as $platform => $urls) {
            $this->line("   📊 Checking: {$platform}");

            foreach ($urls as $url) {
                try {
                    $response = Http::timeout(5)->head($url);
                    if ($response->successful()) {
                        $found[] = [
                            'type' => 'Research Platform',
                            'source' => $platform,
                            'url' => $url,
                            'status' => 'accessible',
                            'priority' => 'medium',
                        ];
                        $this->line("     ✅ {$url}");
                    } else {
                        $this->line("     ⚠️  {$url} (search required)");
                    }
                } catch (\Exception $e) {
                    $this->line("     ❌ {$url} (error)");
                }
            }
        }

        return $found;
    }

    /**
     * Display search results
     */
    protected function displayResults(array $foundDatasets): void
    {
        $this->info('📋 Search Results Summary:');
        $this->newLine();

        if (empty($foundDatasets)) {
            $this->warn('⚠️  No accessible datasets found automatically.');
            $this->line('   This is normal as many datasets require manual search or registration.');

            return;
        }

        $byPriority = [
            'very_high' => [],
            'high' => [],
            'medium' => [],
        ];

        foreach ($foundDatasets as $dataset) {
            $byPriority[$dataset['priority']][] = $dataset;
        }

        foreach ($byPriority as $priority => $datasets) {
            if (empty($datasets)) {
                continue;
            }

            $priorityLabel = match ($priority) {
                'very_high' => '🔥 Very High Priority',
                'high' => '⭐ High Priority',
                'medium' => '📊 Medium Priority'
            };

            $this->line($priorityLabel);
            foreach ($datasets as $dataset) {
                $this->line("   • {$dataset['type']}: {$dataset['source']}");
                $this->line("     URL: {$dataset['url']}");
            }
            $this->newLine();
        }
    }

    /**
     * Provide recommendations for finding KSA datasets
     */
    protected function provideRecommendations(): void
    {
        $this->info('💡 Recommendations for Finding KSA Fake News Datasets:');
        $this->newLine();

        $recommendations = [
            '🎓 Contact Saudi Universities directly:',
            '   • King Saud University - Computer Science Department',
            '   • KAUST - Natural Language Processing Lab',
            '   • KFUPM - Information & Computer Science Department',
            '',
            '🏛️  Check Government Open Data Initiatives:',
            '   • Saudi Open Data Portal (data.gov.sa)',
            '   • Ministry of Communications and Information Technology',
            '   • National Cybersecurity Authority',
            '',
            '📚 Academic Paper Datasets:',
            '   • Check recent Arabic NLP papers on ArXiv',
            '   • Look for ACL/EMNLP papers on Arabic misinformation',
            '   • Search Google Scholar for "Arabic fake news dataset"',
            '',
            '🤝 Collaboration Opportunities:',
            '   • Partner with Saudi research institutions',
            '   • Join Arabic NLP research communities',
            '   • Participate in shared tasks like HASOC (Arabic track)',
            '',
            '🛠️  Create Your Own Dataset:',
            '   • Collect from Saudi news websites with permission',
            '   • Gather fact-checked claims from Saudi fact-checkers',
            '   • Use social media APIs (following terms of service)',
            '   • Crowdsource fact-checking of Saudi news',
        ];

        foreach ($recommendations as $rec) {
            $this->line($rec);
        }

        $this->newLine();
        $this->info('🔗 Suggested Next Steps:');
        $this->line('1. Save accessible URLs from the search results above');
        $this->line('2. Visit these URLs manually to search for datasets');
        $this->line('3. Contact dataset authors for permission to use their data');
        $this->line('4. Consider creating a custom KSA-specific dataset');

        // Save results to file
        $this->saveSearchResults($foundDatasets ?? []);
    }

    /**
     * Save search results to file
     */
    protected function saveSearchResults(array $results): void
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "ksa_dataset_search_results_{$timestamp}.json";
        $filepath = storage_path("app/datasets/search_results/{$filename}");

        // Create directory if it doesn't exist
        $directory = dirname($filepath);
        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $searchData = [
            'timestamp' => now()->toISOString(),
            'total_found' => count($results),
            'results' => $results,
            'recommendations' => [
                'academic_contacts' => [
                    'KSU Computer Science' => 'https://ccis.ksu.edu.sa',
                    'KAUST NLP Lab' => 'https://nlp.kaust.edu.sa',
                    'KFUPM ICS' => 'https://www.kfupm.edu.sa/departments/ics',
                ],
                'government_portals' => [
                    'Saudi Open Data' => 'https://data.gov.sa',
                    'MCIT' => 'https://www.mcit.gov.sa',
                    'NCA' => 'https://nca.gov.sa',
                ],
            ],
        ];

        File::put($filepath, json_encode($searchData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->newLine();
        $this->info("💾 Search results saved to: {$filename}");
    }
}
