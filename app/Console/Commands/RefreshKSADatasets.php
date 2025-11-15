<?php

namespace App\Console\Commands;

use App\Services\DatasetProcessorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RefreshKSADatasets extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'datasets:refresh
                            {--additional=100 : Number of additional records to generate}
                            {--clean : Clean existing datasets before refresh}';

    /**
     * The console command description.
     */
    protected $description = 'Refresh KSA datasets with new data and update database';

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
        $this->info('🔄 Refreshing KSA Fake News Datasets...');
        $this->newLine();

        $additional = (int) $this->option('additional');
        $clean = $this->option('clean');

        // Step 1: Handle cleaning if requested
        if ($clean) {
            $this->handleCleaning();
        }

        // Step 2: Generate additional datasets
        if ($additional > 0) {
            $this->info("📊 Generating {$additional} additional KSA records...");
            $this->generateAdditionalDatasets($additional);
            $this->info('✅ Additional datasets generated!');
        }

        // Step 3: Fetch latest datasets using existing commands
        $this->info('📡 Fetching latest KSA datasets...');
        $this->call('datasets:fetch-ksa', ['--limit' => 10, '--include-arabic' => true]);

        // Step 4: Process all datasets
        $this->info('💾 Processing and updating database...');
        $this->call('fakenews:process', ['--dataset' => 'ksa_comprehensive']);

        // Step 5: Show updated database status
        $this->showDatabaseStatus();

        $this->newLine();
        $this->info('🎉 KSA datasets refresh complete!');

        return 0;
    }

    /**
     * Handle cleaning existing datasets
     */
    private function handleCleaning(): void
    {
        if ($this->confirm('⚠️  This will remove all existing KSA datasets. Continue?')) {
            $this->info('🧹 Cleaning existing KSA datasets...');

            // Remove KSA_COMPREHENSIVE records from database
            $deletedCount = \App\Models\DatasetFakeNews::where('origin_dataset_name', 'KSA_COMPREHENSIVE')->delete();
            $this->line("   Removed {$deletedCount} records from database");

            // Remove CSV files
            $csvFiles = File::glob(storage_path('app/datasets/ksa*.csv'));
            foreach ($csvFiles as $file) {
                File::delete($file);
            }
            $this->line('   Removed '.count($csvFiles).' CSV files');

            $this->info('✅ Cleaning complete!');
            $this->newLine();
        }
    }

    /**
     * Generate additional datasets
     */
    private function generateAdditionalDatasets(int $count): void
    {
        $templates = [
            [
                'title' => 'تحديث: {ministry} تطلق مبادرة {initiative}',
                'content' => 'أطلقت {ministry} مبادرة {initiative} الجديدة والتي تهدف إلى {objective}. وتأتي هذه المبادرة ضمن التطوير المستمر للخدمات الحكومية في المملكة العربية السعودية.',
                'category' => 'initiatives',
            ],
            [
                'title' => 'البنك المركزي السعودي يعلن عن {announcement}',
                'content' => 'أعلن البنك المركزي السعودي (ساما) عن {announcement} والذي يهدف إلى {purpose}. وأوضح البنك أن هذا الإجراء يأتي في إطار تطوير النظام المصرفي السعودي.',
                'category' => 'banking',
            ],
            [
                'title' => 'جامعة {university} تفتح باب التسجيل في {program}',
                'content' => 'أعلنت جامعة {university} عن فتح باب التسجيل في برنامج {program} للفصل الدراسي المقبل. ويهدف البرنامج إلى إعداد الطلاب للمستقبل وتزويدهم بالمهارات اللازمة.',
                'category' => 'education',
            ],
        ];

        $replacements = [
            'ministry' => ['وزارة التجارة', 'وزارة الاستثمار', 'وزارة الطاقة', 'وزارة البيئة', 'وزارة السياحة'],
            'initiative' => ['التحول الرقمي', 'الاستدامة البيئية', 'ريادة الأعمال', 'الابتكار التقني'],
            'objective' => ['تسهيل الخدمات', 'رفع الكفاءة', 'دعم المواطنين', 'تطوير القطاع'],
            'announcement' => ['أسعار فائدة جديدة', 'خدمات مصرفية رقمية', 'تنظيمات جديدة للبنوك'],
            'purpose' => ['تحفيز الاقتصاد', 'دعم الشركات الناشئة', 'تطوير السوق المالي'],
            'university' => ['الملك سعود', 'الملك عبدالعزيز', 'الملك فهد للبترول', 'الأميرة نورة'],
            'program' => ['الذكاء الاصطناعي', 'علوم البيانات', 'الأمن السيبراني', 'إدارة الأعمال'],
        ];

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        // Read existing data or create new
        $datasetPath = storage_path('app/datasets/ksa_comprehensive.csv');
        $existingData = [];

        if (File::exists($datasetPath)) {
            $existingData = explode("\n", File::get($datasetPath));
        } else {
            $existingData = ['id,title,content,label,category,source,region'];
        }

        // Find starting ID
        $startId = count($existingData); // Account for header

        for ($i = 0; $i < $count; $i++) {
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
            $title = preg_replace('/\{[^}]+\}/', 'موضوع محدث', $title);
            $content = preg_replace('/\{[^}]+\}/', 'معلومات محدثة', $content);

            // Randomly assign fake/real (75% fake, 25% real for refresh)
            $label = (rand(1, 100) <= 75) ? 'fake' : 'real';

            // Add validation indicator
            $content .= ' وقد أكدت المصادر الإعلامية أن هذا '.($label == 'fake' ? 'الخبر يحتاج للتحقق' : 'الإعلان موثق').'.';

            // Escape for CSV
            $title = '"'.str_replace('"', '""', $title).'"';
            $content = '"'.str_replace('"', '""', $content).'"';

            $currentId = $startId + $i;
            $existingData[] = "$currentId,$title,$content,$label,{$template['category']},ksa_refresh_".date('Y-m-d').',ksa';

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        // Save updated data
        File::put($datasetPath, implode("\n", $existingData));
    }

    /**
     * Show updated database status
     */
    private function showDatabaseStatus(): void
    {
        $totalRecords = \App\Models\DatasetFakeNews::count();
        $ksaRecords = \App\Models\DatasetFakeNews::where('origin_dataset_name', 'KSA_COMPREHENSIVE')->count();
        $arabicRecords = \App\Models\DatasetFakeNews::where('language', 'ar')->count();
        $recentRecords = \App\Models\DatasetFakeNews::where('created_at', '>=', now()->subHours(1))->count();

        $this->newLine();
        $this->info('📊 Updated Database Status:');
        $this->line("   Total records: {$totalRecords}");
        $this->line("   KSA-specific records: {$ksaRecords}");
        $this->line("   Arabic records: {$arabicRecords}");
        $this->line("   📈 Recently added: {$recentRecords}");
    }
}
