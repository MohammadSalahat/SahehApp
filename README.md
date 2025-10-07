
# Laravel 12 — Starter Kit with Auth, Flux UI & Filament Admin Panel

This repository is a ready-to-use **Laravel 12** application that includes:

- ✅ **Built-in Authentication** (Laravel Breeze)
- 🎨 **Flux UI** (modern UI components)
- 🧩 **Filament Admin Panel** pre-installed
- ⚙️ Standard Laravel folder structure and configurations

This guide walks new developers through setting up the project **on Windows**, even if PHP, Composer, Node.js, or Laravel are not yet installed.

---

## 🧰 1. Prerequisites

Before running the application, ensure your local machine has **PHP**, **Composer**, **Laravel Installer**, and **Node.js** installed.

### 1.1. Install PHP, Composer, and Laravel Installer (One Command Setup)

If you’re on **Windows**, you can install everything with a single command.

> **Note:** Open PowerShell as **Administrator**.

```bash
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
````

After installation, restart PowerShell and verify:

```bash
php -v
composer -V
laravel --version
```

You should now have **PHP 8.4+**, **Composer**, and the **Laravel Installer**.

📖 [Laravel Installation Docs](https://laravel.com/docs/12.x/installation)

---

### 1.2. Install Node.js and npm

Flux UI and Laravel's frontend tooling require Node.js.

* Download from: [https://nodejs.org/en/download/](https://nodejs.org/en/download/)
* After installation, verify:

  ```bash
  node -v
  npm -v
  ```

---

## 🚀 2. Clone the Repository

Open **PowerShell**, **CMD**, or **Git Bash**, then run:

```bash
git clone https://github.com/your-username/your-laravel-app.git](https://github.com/MohammadSalahat/SahehApp.git
cd SahehApp
```

---

## ⚙️ 3. Install Dependencies

### Backend (Composer)

```bash
composer install
```

### Frontend (Node / Flux UI)

```bash
npm install
```

---

## 🔑 4. Environment Setup

Copy the example environment file:

```bash
cp .env.example .env
```

If the above doesn’t work on Windows CMD:

```bash
copy .env.example .env
```

Generate your application key:

```bash
php artisan key:generate
```

---

## 🗄️ 5. Database Configuration

1. Start **MySQL** (via XAMPP, Laravel Herd, or your preferred method).
2. Visit [http://localhost/phpmyadmin](http://localhost/phpmyadmin) and create a new database (e.g., `mydb`).
3. In the `.env` file, update:

   ```
   DB_DATABASE=mydb
   DB_USERNAME=root
   DB_PASSWORD=
   ```

Then run migrations and seeders:

```bash
php artisan migrate --seed
```

This will create all required tables and (if configured) a default Filament admin user.

---

## 🎨 6. Build Frontend Assets (Flux UI)

Compile the frontend assets:

```bash
npm run dev
```

For production builds:

```bash
npm run build
```

---

## 🧮 7. Start the Application

Run the development server:

```bash
composer run dev
```

Then open your browser at:

👉 [http://localhost:8000](http://localhost:8000)

### Authentication

You can register a new user or log in using the website directly.


---

## 🧭 8. Filament Admin Panel

Filament Dashboard URL:

👉 [http://localhost:8000/admin](http://localhost:8000/admin)

Log in using your admin credentials.

📖 [Filament Documentation](https://filamentphp.com/docs)

---

## 🌈 9. Flux UI Overview

Flux UI components are available in:

```
resources/views/
resources/js/
```

📖 [Flux UI Documentation](https://fluxui.dev/docs)

---

## 🧑‍💻 10. Common Commands

| Purpose                      | Command                            |
| ---------------------------- | ---------------------------------- |
| Start the app                | `php artisan serve`                |
| Run migrations               | `php artisan migrate`              |
| Install dependencies         | `composer install` / `npm install` |
| Build assets                 | `npm run build`                    |
| Watch for changes            | `npm run dev`                      |
| Create a Filament admin user | `php artisan make:filament-user`   |

---

## 🧩 11. Troubleshooting

**Error:** `'php' is not recognized`
→ Restart PowerShell and ensure PHP was installed via the `php.new` command.

**Error:** `'composer' is not recognized`
→ Restart PowerShell after installation or verify your PATH variable.

**Error:** `SQLSTATE[HY000] [1049] Unknown database`
→ Ensure the database exists in phpMyAdmin and the `.env` file values are correct.

**Error:** `npm run dev` fails
→ Ensure Node.js and npm are installed properly, then run `npm install` again.

---

## 📚 12. References

* [Laravel Documentation](https://laravel.com/docs/12.x)
* [Flux UI Documentation](https://fluxui.dev/docs)
* [Filament Admin Docs](https://filamentphp.com/docs)
* [Composer Docs](https://getcomposer.org/doc/)
* [Node.js Docs](https://nodejs.org/en/docs)

---

## 👨‍💻 Maintainer

**Author:** Mohammad Salahat
**Framework:** Laravel 12
**License:** MIT

---

> “Write clean code, commit often, and enjoy building amazing things with Laravel.”

```

---

Would you like me to **customize it with your actual repository name and admin credentials** (for example: `cheer-events` instead of `your-laravel-app`)? I can adjust the URLs and examples accordingly before you upload it.
```
