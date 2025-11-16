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
                'name' => 'وزارة العدل السعودية',
                'url' => 'https://www.moj.gov.sa/',
                'description' => 'وزارة العدل السعودية هي المصدر الرسمي للأخبار القانونية والإعلانات الحكومية في المملكة العربية السعودية.',
                'reliability_score' => 1.00,
                'is_active' => true,
            ],
            [
                'name' => 'الوكالة العربية السعودية للأنباء (واس)',
                'url' => 'https://www.spa.gov.sa/',
                'description' => 'الوكالة العربية السعودية للأنباء (واس) هي المصدر الرسمي للأخبار في المملكة العربية السعودية.',
                'reliability_score' => 1.00,
                'is_active' => true,
            ],
            [
                'name' => 'المنصة الوطنية',
                'url' => 'https://my.gov.sa/ar/',
                'description' => 'المنصة الوطنية هي منصة شاملة للخدمات الحكومية في المملكة العربية السعودية.',
                'reliability_score' => 0.85,
                'is_active' => true,
            ],
            [
                'name' => 'ديوان المظالم',
                'url' => 'https://www.bog.gov.sa/Pages/default.aspx',
                'description' => 'ديوان المظالم هو الجهة القضائية المختصة بالنظر في القضايا الإدارية في المملكة العربية السعودية.',
                'reliability_score' => 0.85,
                'is_active' => true,
            ],
        ];

        foreach ($sources as $source) {
            Source::create($source);
        }
    }
}
