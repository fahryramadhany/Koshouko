@echo off
REM Perpustakaan Digital - Quick Setup Script (Windows)
REM Script ini akan setup aplikasi secara otomatis

echo.
echo 🚀 Perpustakaan Digital - Setup
echo ==================================
echo.

if not exist "composer.json" (
    echo ❌ Error: composer.json tidak ditemukan
    exit /b 1
)

echo 📦 Installing dependencies...
call composer install
call npm install

echo.
echo 🔑 Generating application key...
call php artisan key:generate

echo.
echo 🗄️  Running migrations...
call php artisan migrate

echo.
echo 🌱 Seeding database...
call php artisan db:seed

echo.
echo 🎨 Building assets...
call npm run build

echo.
echo ✅ Setup berhasil!
echo.
echo 📋 Demo Credentials:
echo   Admin: admin@perpustakaan.com / password
echo   Pustakawan: pustakawan@perpustakaan.com / password
echo.
echo 🚀 Untuk menjalankan aplikasi:
echo    php artisan serve
echo.
echo 📖 Aplikasi akan tersedia di: http://localhost:8000
echo.
pause
