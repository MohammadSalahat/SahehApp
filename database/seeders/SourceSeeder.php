<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [
                'name' => 'Ministry of Justice (MOJ)',
                'url' => 'https://www.moj.gov.sa',
                'description' => 'Official website of the Ministry of Justice of Saudi Arabia. Primary source for legal news, announcements, and official statements.',
                'reliability_score' => 1.00,
                'is_active' => true,
            ],
            [
                'name' => 'Saudi Press Agency (SPA)',
                'url' => 'https://www.spa.gov.sa',
                'description' => 'Official news agency of the Kingdom of Saudi Arabia. Provides authoritative news and official government announcements.',
                'reliability_score' => 1.00,
                'is_active' => true,
            ],
            [
                'name' => 'Okaz',
                'url' => 'https://www.okaz.com.sa',
                'description' => 'Leading Saudi Arabian daily newspaper covering news, politics, and legal affairs.',
                'reliability_score' => 0.85,
                'is_active' => true,
            ],
            [
                'name' => 'Saudi Gazette',
                'url' => 'https://saudigazette.com.sa',
                'description' => 'English-language daily newspaper providing news and updates from Saudi Arabia.',
                'reliability_score' => 0.80,
                'is_active' => true,
            ],
            [
                'name' => 'Arab News',
                'url' => 'https://www.arabnews.com',
                'description' => 'Leading English-language daily newspaper in Saudi Arabia.',
                'reliability_score' => 0.80,
                'is_active' => true,
            ],
        ];

        foreach ($sources as $source) {
            Source::create($source);
        }
    }
}
