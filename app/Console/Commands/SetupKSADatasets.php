<?php

namespace App\Console\Commands;

use App\Services\DatasetProcessorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupKSADatasets extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'datasets:setup
                            {--force : Force regeneration of datasets even if they exist}
                            {--limit=1000 : Number of records to generate}';

    /**
     * The console command description.
     */
    protected $description = 'Setup KSA fake news datasets and populate database (for fresh installations)';

    /**
     * Dataset processor service
     */
    protected DatasetProcessorService $processor;

    /**
     * Create a new command instance.
     */
    public function __construct(DatasetProcessorService $processor)
    {
        parent::__construct();
        $this->processor = $processor;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🇸🇦 Setting up KSA Fake News Detection System...');
        $this->newLine();

        $force = $this->option('force');
        $limit = (int) $this->option('limit');

        // Step 1: Check if datasets exist
        $datasetPath = storage_path('app/datasets/ksa_comprehensive.csv');

        if (! $force && File::exists($datasetPath)) {
            $this->warn('⚠️  Datasets already exist. Use --force to regenerate.');

            if (! $this->confirm('Do you want to continue with existing datasets?')) {
                $this->info('Setup cancelled.');

                return 0;
            }
        } else {
            // Step 2: Generate datasets
            $this->info('📊 Generating KSA fake news datasets...');
            $this->generateKSADatasets($limit);
            $this->info('✅ Datasets generated successfully!');
        }

        // Step 3: Process datasets into database
        $this->info('💾 Populating database with KSA datasets...');
        $this->newLine();

        $results = $this->processor->processKSADataset($datasetPath, false, false);

        // Display results
        $this->displayResults($results);

        // Step 4: Show final database status
        $this->showDatabaseStatus();

        $this->newLine();
        $this->info('🎉 KSA Fake News Detection System setup complete!');
        $this->info('💡 Your team can now use the fake news detection features.');

        return 0;
    }

    /**
     * Generate KSA datasets
     */
    private function generateKSADatasets(int $limit): void
    {
        $templates = [
            [
                'title' => 'عاجل: الحكومة السعودية تعلن عن قرار جديد يخص {topic}',
                'content' => 'أعلنت الحكومة السعودية عن قرار جديد يتعلق بـ{topic} والذي سيدخل حيز التنفيذ من {date}. وأكدت المصادر الحكومية أن هذا القرار يهدف إلى {purpose} في إطار رؤية المملكة 2030.',
                'category' => 'government',
            ],
            [
                'title' => 'وزارة {ministry} تصدر بياناً حول {topic}',
                'content' => 'أصدرت وزارة {ministry} بياناً رسمياً حول {topic} مؤكدة أن الإجراءات الجديدة ستشمل {details}. وأشارت الوزارة إلى أن هذه الخطوات تأتي ضمن خطة شاملة لتطوير {sector}.',
                'category' => 'ministerial',
            ],
            [
                'title' => 'المحكمة العليا السعودية تصدر حكماً في قضية {case_type}',
                'content' => 'أصدرت المحكمة العليا السعودية حكماً في قضية {case_type} والتي استمرت لمدة {duration}. وقد نص الحكم على {ruling} مما يعتبر سابقة قانونية مهمة في النظام القضائي السعودي.',
                'category' => 'legal',
            ],
            [
                'title' => 'هيئة الترفيه السعودية تنظم فعالية {event} في {city}',
                'content' => 'تنظم الهيئة العامة للترفيه فعالية {event} في مدينة {city} لمدة {duration}. وتتضمن الفعالية {activities} وتهدف إلى {goal} ضمن برنامج جودة الحياة.',
                'category' => 'entertainment',
            ],
            [
                'title' => 'وزارة الصحة السعودية تحذر من {health_issue}',
                'content' => 'حذرت وزارة الصحة السعودية المواطنين من {health_issue} وطالبت بـ{precautions}. وأكدت الوزارة أن {statistics} وأنها تتابع الوضع عن كثب.',
                'category' => 'health',
            ],
        ];

        $replacements = [
            'topic' => ['الإسكان', 'التعليم', 'الصحة', 'النقل', 'الاستثمار', 'الطاقة', 'البيئة', 'السياحة'],
            'ministry' => ['الداخلية', 'الخارجية', 'التجارة', 'الصحة', 'التعليم', 'العدل', 'المالية', 'الإسكان'],
            'date' => ['بداية العام المقبل', 'الشهر المقبل', 'نهاية العام الحالي', 'خلال ستة أشهر'],
            'purpose' => ['تحسين الخدمات', 'رفع جودة الحياة', 'دعم المواطنين', 'تطوير القطاع'],
            'city' => ['الرياض', 'جدة', 'الدمام', 'مكة المكرمة', 'المدينة المنورة', 'الطائف'],
            'case_type' => ['العقارات', 'التجارة الإلكترونية', 'حقوق المستهلك', 'النزاعات التجارية'],
            'event' => ['مهرجان الأضواء', 'موسم الرياض', 'فعاليات نيوم', 'مؤتمر الترفيه'],
            'health_issue' => ['فيروس جديد', 'ارتفاع أسعار الأدوية', 'نقص المستلزمات الطبية'],
        ];

        $progressBar = $this->output->createProgressBar($limit);
        $progressBar->start();

        $data = ['id,title,content,label,category,source,region'];

        for ($i = 1; $i <= $limit; $i++) {
            $template = $templates[array_rand($templates)];

            $title = $template['title'];
            $content = $template['content'];

            // Replace placeholders
            foreach ($replacements as $key => $values) {
                $placeholder = '{'.$key.'}';
                if (strpos($title, $placeholder) !== false || strpos($content, $placeholder) !== false) {
                    $replacement = $values[array_rand($values)];
                    $title = str_replace($placeholder, $replacement, $title);
                    $content = str_replace($placeholder, $replacement, $content);
                }
            }

            // Clean up remaining placeholders
            $title = preg_replace('/\{[^}]+\}/', 'موضوع متنوع', $title);
            $content = preg_replace('/\{[^}]+\}/', 'تفاصيل إضافية', $content);

            // Randomly assign fake/real (80% fake, 20% real)
            $label = (rand(1, 100) <= 80) ? 'fake' : 'real';

            // Add validation indicator
            $content .= ' وقد أكدت المصادر المطلعة أن هذا '.($label == 'fake' ? 'الخبر غير مؤكد' : 'الإعلان رسمي').'.';

            // Escape for CSV
            $title = '"'.str_replace('"', '""', $title).'"';
            $content = '"'.str_replace('"', '""', $content).'"';

            $data[] = "$i,$title,$content,$label,{$template['category']},ksa_setup_".date('Y-m-d').',ksa';

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        // Save to file
        $datasetDir = storage_path('app/datasets');
        if (! File::exists($datasetDir)) {
            File::makeDirectory($datasetDir, 0755, true);
        }

        File::put(storage_path('app/datasets/ksa_comprehensive.csv'), implode("\n", $data));
    }

    /**
     * Display processing results
     */
    private function displayResults(array $results): void
    {
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total rows processed', $results['processed'] ?? 0],
                ['✅ Successfully imported', $results['imported'] ?? 0],
                ['🚫 Filtered (not KSA)', $results['filtered_ksa'] ?? 0],
                ['⏭️  Skipped (duplicates)', $results['duplicates'] ?? 0],
                ['⚠️  Too short', $results['too_short'] ?? 0],
            ]
        );
    }

    /**
     * Show final database status
     */
    private function showDatabaseStatus(): void
    {
        $totalRecords = \App\Models\DatasetFakeNews::count();
        $ksaRecords = \App\Models\DatasetFakeNews::where('origin_dataset_name', 'KSA_COMPREHENSIVE')->count();
        $arabicRecords = \App\Models\DatasetFakeNews::where('language', 'ar')->count();

        $this->newLine();
        $this->info('📊 Database Status:');
        $this->line("   Total records: {$totalRecords}");
        $this->line("   KSA-specific records: {$ksaRecords}");
        $this->line("   Arabic records: {$arabicRecords}");
    }
}
