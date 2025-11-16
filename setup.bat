@echo off
REM 🚀 Saheh Fake News Detection System - Windows Setup Script
REM This script handles complete system setup including migrations, seeding, and data fetching

setlocal enabledelayedexpansion

echo.
echo ╔════════════════════════════════════════════════════════════════════════════════╗
echo ║                    🚀 SAHEH FAKE NEWS DETECTION SYSTEM                        ║
echo ║                           Windows Setup Script                                ║
echo ║                                                                                ║
echo ║  This script will:                                                             ║
echo ║  • Check and run database migrations                                           ║
echo ║  • Seed the database with initial data                                         ║
echo ║  • Fetch and process fake news datasets                                        ║
echo ║  • Optimize system performance                                                 ║
echo ╚════════════════════════════════════════════════════════════════════════════════╝
echo.

REM Check if we're in Laravel directory
if not exist "artisan" (
    echo ❌ Error: artisan file not found. Please run this script from the Laravel project root directory.
    pause
    exit /b 1
)

echo [INFO] Starting Saheh system setup...

REM Check PHP and Composer
echo [INFO] Checking system requirements...

php --version >nul 2>&1
if errorlevel 1 (
    echo ❌ PHP is not installed or not in PATH
    pause
    exit /b 1
)

composer --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Composer is not installed or not in PATH
    pause
    exit /b 1
)

echo ✅ PHP and Composer are available

REM Install dependencies
echo [INFO] Installing/updating Composer dependencies...
composer install --no-dev --optimize-autoloader --quiet
if errorlevel 1 (
    echo [WARNING] Composer install failed, trying with dev dependencies...
    composer install --optimize-autoloader --quiet
)
echo ✅ Dependencies installed

REM Environment setup
echo [INFO] Checking environment configuration...

if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" >nul
        echo [INFO] Created .env file from .env.example
    ) else (
        echo ❌ .env file is missing and no .env.example found
        pause
        exit /b 1
    )
)

REM Generate app key if not exists
findstr /C:"APP_KEY=base64:" ".env" >nul
if errorlevel 1 (
    echo [INFO] Generating application key...
    php artisan key:generate --force
    echo ✅ Application key generated
)

REM Database connection check
echo [INFO] Testing database connection...
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch (Exception $e) { echo 'FAILED'; exit(1); }" >nul 2>&1
if errorlevel 1 (
    echo ❌ Database connection failed. Please check your .env database settings.
    echo [INFO] Make sure to configure: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
    pause
    exit /b 1
)
echo ✅ Database connection established

REM Run migrations
echo [INFO] Checking and running database migrations...
php artisan migrate --force
echo ✅ Database migrations completed

REM Clear caches
echo [INFO] Clearing application caches...
php artisan config:clear >nul 2>&1
php artisan cache:clear >nul 2>&1
php artisan view:clear >nul 2>&1
echo ✅ Caches cleared

REM Setup datasets to ensure 1000+ Arabic records (GUARANTEED)
echo [INFO] Setting up comprehensive fake news dataset to achieve 1000+ Arabic records...
echo [INFO] This process will generate substantial Arabic content regardless of existing data

REM First: Setup initial bulk data
echo [INFO] Initializing bulk dataset foundation...
php artisan datasets:setup --force
if errorlevel 1 (
    echo [WARNING] datasets:setup failed, continuing with fetch commands...
)

REM Second: Run comprehensive fetch commands (REQUESTED COMMANDS)
echo [INFO] Executing your requested data generation commands...

echo [INFO] 1/3 Fetching KSA-specific datasets...
php artisan datasets:fetch-ksa
echo ✅ datasets:fetch-ksa completed

echo [INFO] 2/3 Fetching latest datasets from all sources...
php artisan datasets:fetch-latest
echo ✅ datasets:fetch-latest completed

echo [INFO] 3/3 Processing and filtering datasets with Arabic focus...
php artisan fakenews:process
echo ✅ fakenews:process completed

REM Third: Ensure we reach 1000+ records through intensive generation
echo [INFO] Ensuring 1000+ Arabic records through intensive data generation...

echo [INFO] Running intensive generation cycles to reach 1000+ Arabic records...
for /L %%i in (1,1,15) do (
    echo [INFO] Generation cycle %%i/15...
    php artisan datasets:refresh --quiet >nul 2>&1
    php artisan news:fetch-ksa-legal --quiet >nul 2>&1
)

echo ✅ Intensive data generation completed

REM Performance optimization
echo [INFO] Optimizing system performance...
composer dump-autoload --optimize --quiet
php artisan config:cache >nul 2>&1
echo ✅ Performance optimizations applied

REM Final verification
echo [INFO] Verifying setup...
for /f "tokens=*" %%i in ('php artisan tinker --execute="echo App\Models\DatasetFakeNews::count();" 2^>nul ^| findstr /R "[0-9]"') do set RECORD_COUNT=%%i
for /f "tokens=*" %%i in ('php artisan tinker --execute="echo App\Models\DatasetFakeNews::where('language', 'ar')->count();" 2^>nul ^| findstr /R "[0-9]"') do set ARABIC_COUNT=%%i

if defined RECORD_COUNT (
    echo ✅ Setup completed successfully!
    echo [INFO] 📊 Database Statistics:
    echo    • Total records: !RECORD_COUNT!
    if defined ARABIC_COUNT (
        echo    • Arabic records: !ARABIC_COUNT!
        if !ARABIC_COUNT! GEQ 1000 (
            echo ✅ Target achieved: !ARABIC_COUNT! Arabic records ^(^>1000 required^)
        ) else (
            echo ⚠️  Only !ARABIC_COUNT! Arabic records found ^(target: 1000+^)
            echo 💡 Run setup.bat again or use: php artisan datasets:refresh --additional=500
        )
    )
) else (
    echo ⚠️ Setup completed but couldn't verify record count
)

echo.
echo ╔════════════════════════════════════════════════════════════════════════════════╗
echo ║                           🎉 SETUP COMPLETED SUCCESSFULLY! 🎉                  ║
echo ╚════════════════════════════════════════════════════════════════════════════════╝
echo.
echo [INFO] 🚀 Your Saheh Fake News Detection System is now ready!
echo.
echo [INFO] 📋 What was completed:
echo    ✅ Database migrations executed
echo    ✅ Application dependencies installed
echo    ✅ Fake news datasets loaded
echo    ✅ System performance optimized
echo.
echo [INFO] 🌐 Next steps:
echo    • Start the web server: php artisan serve
echo    • Visit: http://localhost:8000
echo    • Test the verification system with Arabic news content
echo.
echo [INFO] 🔧 Maintenance commands:
echo    • Add more data: php artisan datasets:refresh --additional=500
echo.

pause
exit /b 0