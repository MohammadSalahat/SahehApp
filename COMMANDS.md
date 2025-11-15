# KSA Fake News Detection - Command Reference

## 🚀 Quick Start Commands (Recommended)

### For Fresh Installations
```bash
# Setup database with 1000+ KSA fake news records
php artisan datasets:setup --limit=1000

# Setup with smaller dataset for testing
php artisan datasets:setup --limit=100
```

### For Existing Installations
```bash
# Add more data to existing database
php artisan datasets:refresh --additional=200

# Clean and refresh with new data
php artisan datasets:refresh --additional=100 --clean
```

## 📊 Current Database Status
- **Total Records**: 1,099+ fake news articles
- **KSA-Specific**: 813+ articles
- **Language**: 100% Arabic content
- **Categories**: Government, Legal, Health, Entertainment, Banking, Education

## 🛠️ All Available Commands

### Core Dataset Commands
| Command | Purpose | Usage |
|---------|---------|-------|
| `datasets:setup` | Fresh installation setup | `php artisan datasets:setup --limit=1000` |
| `datasets:refresh` | Add new data to existing DB | `php artisan datasets:refresh --additional=200` |

### Data Fetching Commands
| Command | Purpose | Usage |
|---------|---------|-------|
| `datasets:fetch-ksa` | Fetch KSA-specific datasets | `php artisan datasets:fetch-ksa` |
| `datasets:fetch-latest` | Fetch latest from all sources | `php artisan datasets:fetch-latest` |
| `datasets:fetch-and-update` | Fetch and update database | `php artisan datasets:fetch-and-update` |

### Processing Commands
| Command | Purpose | Usage |
|---------|---------|-------|
| `fakenews:process` | Process with Arabic filtering | `php artisan fakenews:process` |
| `news:fetch-datasets` | Python service integration | `php artisan news:fetch-datasets` |
| `news:fetch-ksa-legal` | Fetch KSA legal news | `php artisan news:fetch-ksa-legal` |

### Search Commands
| Command | Purpose | Usage |
|---------|---------|-------|
| `datasets:search-ksa` | Search KSA-related datasets | `php artisan datasets:search-ksa` |

## 💡 Usage Tips

1. **For Team Members**: Start with `datasets:setup --limit=1000`
2. **For Development**: Use `datasets:setup --limit=100` for faster testing
3. **For Updates**: Use `datasets:refresh --additional=200` to add more data
4. **Check Status**: Run `php artisan datasets:setup --limit=1` to see current database status

## 🔍 Troubleshooting

- If setup fails: Use `--force` flag to overwrite existing data
- For memory issues: Reduce `--limit` value
- For Arabic encoding: Ensure MySQL charset is `utf8mb4`

---
*Generated for Saheh Fake News Detection System*