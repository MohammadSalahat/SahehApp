# KSA Fake News Detection System

A Laravel-based system for detecting fake news specifically focused on Saudi Arabian (KSA) content, with Arabic language support and comprehensive dataset management.

## 🚀 Quick Start for New Teams

### Prerequisites
- PHP 8.1+
- MySQL 8.0+
- Composer
- Node.js & NPM

### Initial Setup

1. **Clone and Install**
```bash
git clone <your-repo-url>
cd SahehApp
composer install
npm install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
# Configure your database in .env file
php artisan migrate
```

4. **Setup KSA Datasets (Fresh Installation)**
```bash
# This command will generate datasets and populate the database
php artisan datasets:setup --limit=1000

# Optional: Force regeneration if datasets exist
php artisan datasets:setup --force --limit=1500
```

### 📊 Dataset Management Commands

#### For Fresh Installations
```bash
# Setup complete KSA fake news dataset system
php artisan datasets:setup --limit=1000
```
**What it does:**
- Generates comprehensive KSA fake news datasets
- Populates database with Arabic content
- Creates realistic fake/real news ratio (80/20)
- Includes government, legal, health, entertainment categories

#### For Existing Installations (Updates)
```bash
# Refresh datasets with new content
php artisan datasets:refresh --additional=100

# Clean and refresh everything
php artisan datasets:refresh --clean --additional=200
```
**What it does:**
- Adds new KSA fake news records
- Fetches latest datasets from external sources
- Updates database incrementally
- Shows progress and statistics

### 🔧 Individual Component Commands

If you need granular control:

```bash
# Fetch KSA-specific datasets from external sources
php artisan datasets:fetch-ksa --limit=50 --include-arabic

# Search for real KSA dataset sources
php artisan datasets:search-ksa --academic

# Process specific datasets into database
php artisan fakenews:process --dataset=ksa_comprehensive

# Fetch latest from multiple sources
php artisan datasets:fetch-latest --limit=100
```

### 📈 Database Status

After setup, verify your database:

```bash
# Check total records in tinker
php artisan tinker
>>> App\Models\DatasetFakeNews::count()
```

Expected results after `datasets:setup --limit=1000`:
- **1000+ total records**
- **80% fake news, 20% real news**
- **100% Arabic language content**
- **KSA-specific categories**: government, legal, health, entertainment, etc.

### 🏗️ Architecture Overview

#### Key Models
- `DatasetFakeNews` - Main news records with metadata
- `DatasetProcessorService` - Handles dataset processing and filtering

#### Dataset Structure
```
storage/app/datasets/
├── ksa_comprehensive.csv      # Main KSA dataset
├── ksa/                       # Individual KSA sources
│   ├── arabic_ksa/
│   ├── legal_ksa/
│   └── social_media_ksa/
└── bulk_ksa_news*.csv        # Bulk generated datasets
```

#### Database Schema: `datasets_fake_news`
- `title` - News headline
- `content` - Full news content
- `language` - Language code (ar/en)
- `detected_at` - Processing timestamp
- `confidence_score` - AI confidence (0-1)
- `origin_dataset_name` - Source dataset identifier
- `content_hash` - SHA-256 hash for duplicates prevention

### 🔄 Workflow for Teams

#### New Developer Setup
1. Clone repo
2. Run `composer install`
3. Setup `.env` with database credentials
4. Run `php artisan migrate`
5. Run `php artisan datasets:setup --limit=1000`
6. Start development: `php artisan serve`

#### Regular Dataset Updates
```bash
# Weekly refresh with 50 new records
php artisan datasets:refresh --additional=50

# Monthly complete refresh
php artisan datasets:refresh --clean --additional=500
```

### 📋 Command Reference

| Command | Purpose | When to Use |
|---------|---------|-------------|
| `datasets:setup` | Fresh installation setup | New project setup |
| `datasets:refresh` | Update existing datasets | Regular maintenance |
| `datasets:fetch-ksa` | Fetch external KSA data | Manual data collection |
| `datasets:search-ksa` | Find dataset sources | Research new sources |
| `fakenews:process` | Process CSV to database | After manual CSV edits |

### 🛠️ Development Tips

#### Custom Dataset Generation
To add your own dataset templates, edit:
- `app/Console/Commands/SetupKSADatasets.php` (templates array)
- `app/Console/Commands/RefreshKSADatasets.php` (refresh templates)

#### Adding New Categories
1. Add category to templates
2. Update database seeders if needed
3. Test with small dataset first

#### Performance Optimization
- Use `--limit` parameter for testing
- Monitor database size with large datasets
- Consider chunked processing for 10k+ records

### 🔍 Troubleshooting

#### Common Issues

**"Language column too long" error:**
- Ensure language detection returns 'ar' or 'en' only
- Check `DatasetProcessorService::detectLanguage()`

**Duplicate records:**
- System prevents duplicates via content hashing
- Use `--force` flag to regenerate datasets

**Low import counts:**
- Check KSA content filtering in `DatasetProcessorService`
- Verify Arabic text detection

#### Database Verification
```bash
# Check record counts by origin
php artisan tinker
>>> App\Models\DatasetFakeNews::select('origin_dataset_name', DB::raw('count(*) as total'))->groupBy('origin_dataset_name')->get()

# Check language distribution
>>> App\Models\DatasetFakeNews::select('language', DB::raw('count(*) as total'))->groupBy('language')->get()
```

### 📝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature/new-dataset-source`
3. Test with: `php artisan datasets:setup --limit=100`
4. Commit changes: `git commit -m "Add new dataset source"`
5. Push to branch: `git push origin feature/new-dataset-source`
6. Create Pull Request

### 📞 Support

For issues related to:
- **Dataset generation**: Check `SetupKSADatasets.php`
- **Processing errors**: Check `DatasetProcessorService.php`
- **Database issues**: Verify migrations and model relationships
- **Arabic content**: Ensure UTF-8 encoding and proper Arabic detection

---

## 🎯 Ready to Use Commands

```bash
# For new installations
php artisan datasets:setup --limit=1000

# For updates
php artisan datasets:refresh --additional=100

# Verify setup
php artisan tinker
>>> App\Models\DatasetFakeNews::count()
```

**Your KSA Fake News Detection System is ready! 🇸🇦🎉**