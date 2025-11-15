# 📊 Dataset Fetching Logic - Complete Tour

## 🎯 Overview

The system fetches fake news data from external datasets (LIAR, CredBank) and stores them in MySQL for verification purposes. Here's the complete flow:

---

## 🏗️ Architecture

```
External Datasets → Python Scripts → Laravel Commands → MySQL Database → Verification System
```

---

## 📁 File Structure

### Laravel Side (PHP)
```
SahehApp/
├── app/Console/Commands/
│   ├── FetchFakeNewsFromDatasets.php     # Main command to trigger Python scripts
│   └── ProcessFakeNewsDatasets.php       # Process CSV files with filtering
├── app/Services/
│   └── DatasetProcessorService.php       # Core dataset processing logic
├── app/Models/
│   └── DatasetFakeNews.php               # Database model for fake news data
└── database/migrations/
    └── create_datasets_fake_news_table.php # Database schema
```

### Python Side
```
SahehAIPython/
├── scripts/
│   ├── fetch_datasets.py                 # Main Python script to fetch data
│   └── process_datasets.py               # Process and send to Laravel API
├── datasets/
│   ├── liar/politifact_fake.csv         # LIAR dataset (English)
│   └── credbank/credbank_sample.csv     # CredBank dataset  
└── app/services/
    └── laravel_api_client.py             # API client to send data to Laravel
```

---

## 🚀 Commands Available

### 1. **Fetch from Datasets** (Primary Command)
```bash
php artisan news:fetch-datasets --limit=50
```

**What it does:**
- Executes Python script `fetch_datasets.py`
- Reads LIAR and CredBank CSV files
- Processes and sends data to Laravel API
- Stores in `datasets_fake_news` table

### 2. **Process Datasets** (Alternative Command)
```bash
php artisan fakenews:process --dataset=all
php artisan fakenews:process --dataset=liar --no-arabic-filter
php artisan fakenews:process --dataset=credbank --no-ksa-filter
```

**What it does:**
- Processes CSV files directly in PHP
- Applies Arabic and KSA legal filtering
- Stores filtered results in database

---

## 🔄 Workflow Explained

### Step 1: Dataset Fetching
```php
// Command: FetchFakeNewsFromDatasets.php
$command = "cd {$pythonProjectPath} && {$venvPath} {$scriptPath} --limit={$limit}";
$result = Process::run($command);
```

### Step 2: Python Processing
```python
# Script: fetch_datasets.py
def fetch_liar_dataset(limit: int = 50) -> int:
    dataset_path = Path("datasets/liar/politifact_fake.csv")
    df = pd.read_csv(dataset_path)
    
    for index, row in df.head(limit).iterrows():
        # Process each row
        news_data = {
            'title': row['title'],
            'content': row['content'],
            'confidence_score': row['confidence'],
            'origin_dataset_name': 'LIAR'
        }
        
        # Send to Laravel API
        laravel_client.store_fake_news(news_data)
```

### Step 3: Laravel Storage
```php
// Service: DatasetProcessorService.php
public function processLiarDataset($csvPath, $arabicFilter = true, $ksaFilter = true)
{
    $csv = Reader::createFromPath($csvPath);
    
    foreach ($csv as $record) {
        // Apply filters
        if ($arabicFilter && !$this->isArabicText($record['content'])) {
            continue;
        }
        
        if ($ksaFilter && !$this->isKSALegalRelated($record['content'])) {
            continue;
        }
        
        // Store in database
        DatasetFakeNews::create($record);
    }
}
```

### Step 4: Database Storage
```sql
-- Table: datasets_fake_news
CREATE TABLE datasets_fake_news (
    id BIGINT PRIMARY KEY,
    title TEXT,
    content TEXT,
    language VARCHAR(2) DEFAULT 'en',
    content_hash VARCHAR(64),           -- SHA256 hash for deduplication
    confidence_score DECIMAL(3,2),      -- 0.00 to 1.00
    origin_dataset_name VARCHAR(255),   -- 'LIAR', 'CredBank', etc.
    detected_at TIMESTAMP,
    added_by_ai BOOLEAN DEFAULT FALSE,
    
    -- Indexes
    FULLTEXT KEY (title, content),      -- For English FULLTEXT search
    INDEX (language),
    INDEX (confidence_score),
    INDEX (origin_dataset_name)
);
```

---

## 📚 Available Datasets

### 1. **LIAR Dataset**
- **Source**: PolitiFact fake news
- **Language**: English
- **File**: `datasets/liar/politifact_fake.csv`
- **Fields**: title, content, label, confidence_score
- **Size**: ~12,000 records

### 2. **CredBank Dataset**  
- **Source**: Social media credibility
- **Language**: Mixed (English/Arabic)
- **File**: `datasets/credbank/credbank_sample.csv`
- **Fields**: text, credibility, source, timestamp
- **Size**: ~5,000 sample records

---

## 🔍 Filtering Logic

### Arabic Text Filter
```php
private function isArabicText(string $text): bool
{
    // Check for Arabic Unicode range (U+0600 to U+06FF)
    return preg_match('/[\x{0600}-\x{06FF}]/u', $text) > 0;
}
```

### KSA Legal Content Filter
```php
private array $ksaLegalKeywords = [
    'السعودية', 'السعودي', 'المملكة', 'الرياض',
    'وزارة العدل', 'النيابة', 'المحكمة', 'القضاء',
    'نظام', 'قانون', 'لائحة', 'قرار', 'مرسوم',
    // ... more keywords
];

private function isKSALegalRelated(string $text): bool
{
    $matchCount = 0;
    foreach ($this->ksaLegalKeywords as $keyword) {
        if (mb_strpos($text, $keyword) !== false) {
            $matchCount++;
        }
    }
    return $matchCount >= 2; // Require at least 2 matches
}
```

---

## 🔗 How Verification Uses Datasets

### For Arabic Text (AraBERT)
```php
// PythonAIService sends Arabic text to Python
$response = Http::post($this->pythonApiUrl . '/verify-arabic', [
    'text' => $text,
    'top_k' => 5
]);

// Python uses AraBERT to compare with Arabic dataset records
```

### For English Text (FULLTEXT)
```php
// Direct MySQL FULLTEXT search
$matches = DatasetFakeNews::query()
    ->where('language', 'en')
    ->whereRaw('MATCH(title, content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$text])
    ->limit(5)
    ->get();
```

---

## 🛠️ Management Commands

### View Current Dataset Status
```bash
php artisan tinker --execute="
echo 'Total records: ' . App\Models\DatasetFakeNews::count() . PHP_EOL;
echo 'Arabic records: ' . App\Models\DatasetFakeNews::where('language', 'ar')->count() . PHP_EOL;
echo 'English records: ' . App\Models\DatasetFakeNews::where('language', 'en')->count() . PHP_EOL;
echo 'LIAR dataset: ' . App\Models\DatasetFakeNews::where('origin_dataset_name', 'LIAR')->count() . PHP_EOL;
echo 'CredBank dataset: ' . App\Models\DatasetFakeNews::where('origin_dataset_name', 'CredBank')->count() . PHP_EOL;
"
```

### Add More Datasets
```bash
# Fetch more records from existing datasets
php artisan news:fetch-datasets --limit=100

# Process with different filters
php artisan fakenews:process --dataset=liar --no-arabic-filter
```

### Clean/Reset Datasets
```bash
php artisan tinker --execute="
App\Models\DatasetFakeNews::truncate();
echo 'All datasets cleared!' . PHP_EOL;
"
```

---

## 📊 Dataset Flow Summary

```
1. CSV Files (LIAR, CredBank)
   ↓
2. Python Script reads CSV
   ↓  
3. Processes each record
   ↓
4. Sends via API to Laravel
   ↓
5. Laravel applies filters:
   - Arabic text only
   - KSA legal keywords
   ↓
6. Stores in datasets_fake_news table
   ↓
7. Verification system queries this table:
   - Arabic: via Python AraBERT
   - English: via MySQL FULLTEXT
```

---

## 🎯 Key Points

1. **Two approaches**: Python scripts OR direct Laravel processing
2. **Bilingual support**: Arabic (AraBERT) + English (FULLTEXT)
3. **Smart filtering**: Only Arabic + KSA legal content
4. **Deduplication**: SHA256 hash prevents duplicates
5. **Scalable**: Can add more datasets easily

The system is designed to continuously grow the dataset while maintaining quality through filtering and deduplication.

---

*Dataset logic documented: November 15, 2025*