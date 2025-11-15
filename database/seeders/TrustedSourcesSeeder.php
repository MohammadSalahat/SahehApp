<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class TrustedSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [
                'name' => 'BBC News Arabic',
                'url' => 'https://www.bbc.com/arabic',
                'description' => 'BBC Arabic news service - trusted international news source',
                'reliability_score' => 0.95,
                'is_active' => true,
            ],
            [
                'name' => 'Al Jazeera',
                'url' => 'https://www.aljazeera.net',
                'description' => 'Al Jazeera news network',
                'reliability_score' => 0.85,
                'is_active' => true,
            ],
            [
                'name' => 'Reuters',
                'url' => 'https://www.reuters.com',
                'description' => 'Reuters international news agency',
                'reliability_score' => 0.92,
                'is_active' => true,
            ],
            [
                'name' => 'Associated Press',
                'url' => 'https://apnews.com',
                'description' => 'Associated Press news agency',
                'reliability_score' => 0.90,
                'is_active' => true,
            ],
            [
                'name' => 'CNN Arabic',
                'url' => 'https://arabic.cnn.com',
                'description' => 'CNN Arabic news service',
                'reliability_score' => 0.80,
                'is_active' => true,
            ],
            [
                'name' => 'Sky News Arabia',
                'url' => 'https://www.skynewsarabia.com',
                'description' => 'Sky News Arabia',
                'reliability_score' => 0.78,
                'is_active' => true,
            ],
            [
                'name' => 'Arab News',
                'url' => 'https://www.arabnews.com',
                'description' => 'Arab News - Saudi-based English and Arabic news',
                'reliability_score' => 0.75,
                'is_active' => true,
            ],
            [
                'name' => 'Middle East Eye',
                'url' => 'https://www.middleeasteye.net',
                'description' => 'Middle East Eye news and analysis',
                'reliability_score' => 0.72,
                'is_active' => true,
            ],
        ];

        foreach ($sources as $source) {
            Source::create($source);
        }

        $this->command->info('Trusted sources seeded successfully!');
    }
}