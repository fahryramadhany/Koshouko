#!/bin/bash

# Perpustakaan Digital - Quick Setup Script
# Script ini akan setup aplikasi secara otomatis

echo "🚀 Perpustakaan Digital - Setup"
echo "=================================="
echo ""

# Check if composer.json exists
if [ ! -f "composer.json" ]; then
    echo "❌ Error: composer.json tidak ditemukan"
    exit 1
fi

echo "📦 Installing dependencies..."
composer install
npm install

echo ""
echo "🔑 Generating application key..."
php artisan key:generate

echo ""
echo "🗄️  Running migrations..."
php artisan migrate

echo ""
echo "🌱 Seeding database..."
php artisan db:seed

echo ""
echo "🎨 Building assets..."
npm run build

echo ""
echo "✅ Setup berhasil!"
echo ""
echo "📋 Demo Credentials:"
echo "  Admin: admin@perpustakaan.com / password"
echo "  Pustakawan: pustakawan@perpustakaan.com / password"
echo ""
echo "🚀 Untuk menjalankan aplikasi:"
echo "   php artisan serve"
echo ""
echo "📖 Aplikasi akan tersedia di: http://localhost:8000"
echo ""
