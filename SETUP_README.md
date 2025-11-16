# 🚀 Saheh Fake News Detection System - Quick Setup

This directory contains automated setup scripts that will configure your Saheh system completely.

## 📋 What the Scripts Do

✅ **Check system requirements** (PHP, Composer)  
✅ **Install dependencies** automatically  
✅ **Run database migrations** if needed  
✅ **Setup environment** (.env file)  
✅ **Fetch and load fake news datasets** (1000+ records)  
✅ **Optimize system performance**  
✅ **Test verification system**  

## 🖥️ For Linux/Mac Users

Run the bash script:
```bash
./setup.sh
```

If you get permission error:
```bash
chmod +x setup.sh
./setup.sh
```

## 🪟 For Windows Users

Double-click on:
```
setup.bat
```

Or run from Command Prompt:
```cmd
setup.bat
```

## 📊 What You'll Get

After running the script:
- **1000+ Arabic fake news records** for testing
- **Optimized database** with proper indexes
- **Enhanced verification system** with semantic similarity
- **Ready-to-use web interface**

## 🌐 After Setup

1. **Start the server:**
   ```bash
   php artisan serve
   ```

2. **Visit:** http://localhost:8000

3. **Test with Arabic news** like:
   ```
   أعلن البنك المركزي السعودي عن قرارات جديدة تهدف إلى تطوير السوق المالي
   ```

## 🔧 Maintenance Commands

Add more data anytime:
```bash
php artisan datasets:refresh --additional=500
```

Check database status:
```bash
php artisan tinker --execute="echo App\Models\DatasetFakeNews::count()"
```

## ❗ Requirements

- **PHP 8.1+**
- **Composer**
- **MySQL/MariaDB** database
- **Internet connection** (for fetching datasets)

## 🆘 Troubleshooting

**Database connection issues:**
- Check your `.env` file database settings
- Ensure your database server is running
- Verify database credentials

**Permission issues (Linux/Mac):**
```bash
chmod +x setup.sh
```

**Missing PHP/Composer:**
- Install PHP: https://php.net/downloads
- Install Composer: https://getcomposer.org/download/

## 📞 Support

If you encounter any issues:
1. Check the error messages in the script output
2. Ensure all requirements are met
3. Verify database connection in `.env` file

---
*Automated setup for Saheh Fake News Detection System*