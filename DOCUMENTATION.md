# 📚 Perpustakaan Digital - Aplikasi Web Responsif

Sebuah sistem manajemen perpustakaan digital yang modern, responsif, dan user-friendly dengan tema offline seperti meminjam buku fisik.

## 🎯 Fitur Utama

### 📱 Responsif & Multi-Platform
- ✅ Desktop dan Mobile Responsive
- ✅ Sidebar yang dapat dikembangkan/dikecilkan
- ✅ Loading Screen profesional
- ✅ Tema Modern dengan Gradien

### 🔐 Sistem Autentikasi & Role
- ✅ Login & Register dengan validasi
- ✅ Tiga Role: Admin, Pustakawan, Member
- ✅ Middleware Role-Based Access Control

### 📚 Manajemen Buku (Admin/Pustakawan)
- ✅ CRUD Buku (Create, Read, Update, Delete)
- ✅ Kategori Buku
- ✅ Filter dan Pencarian
- ✅ Status Ketersediaan

### 👥 Manajemen User (Admin)
- ✅ CRUD User
- ✅ Assign Role ke User
- ✅ Status User (Active, Inactive, Suspended)

### 📋 Sistem Peminjaman
- ✅ Permintaan Peminjaman (pending approval)
- ✅ Persetujuan Peminjaman
- ✅ Pengembalian Buku
- ✅ Perpanjangan Peminjaman (max 2x)
- ✅ Sistem Denda Otomatis untuk Keterlambatan

### ⭐ Fitur Member
- ✅ Dashboard dengan statistik peminjaman
- ✅ Katalog Buku dengan filter
- ✅ Bookmark/Favorit Buku
- ✅ Riwayat Peminjaman
- ✅ Notifikasi Buku Terlambat

### 📊 Dashboard Admin
- ✅ Statistik Sistem (Total Buku, Member, Peminjaman)
- ✅ Kelola Peminjaman (Approve/Reject)
- ✅ Laporan & Statistik
- ✅ Pengumuman

### 📢 Pengumuman
- ✅ Posting Pengumuman
- ✅ Daftar Pengumuman
- ✅ Status Publikasi

## 🛠️ Teknologi

- **Backend**: Laravel 11
- **Frontend**: Blade Template + Tailwind CSS 4.0
- **Database**: MySQL
- **Authentication**: Laravel Built-in

## 📋 Struktur Database

### Tabel Utama
- `users` - Pengguna aplikasi dengan role
- `roles` - Admin, Pustakawan, Member
- `books` - Koleksi buku
- `categories` - Kategori buku
- `borrowings` - Riwayat peminjaman
- `fines` - Denda keterlambatan
- `bookmarks` - Buku favorit user
- `announcements` - Pengumuman

## 🚀 Instalasi & Setup

### 1. Clone Repository
```bash
git clone <repo-url>
cd perpus_digit_laravel
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
```bash
# Edit .env dengan database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpus_digit_laravel
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migration & Seeding
```bash
php artisan migrate
php artisan db:seed
```

### 6. Build Assets
```bash
npm run build
# atau untuk development
npm run dev
```

### 7. Run Server
```bash
php artisan serve
```

Akses di `http://localhost:8000`

## 👤 Akun Demo

### Admin
- Email: `admin@perpustakaan.com`
- Password: `password`

### Pustakawan
- Email: `pustakawan@perpustakaan.com`
- Password: `password`

### Member
- Email: `member@example.com` (auto-generated)
- Password: `password`

## 📖 Role & Permission

### 👨‍💼 Admin
- Akses penuh ke semua fitur
- Manajemen User
- Manajemen Buku & Kategori
- Approve/Reject Peminjaman
- Kelola Denda
- Lihat Laporan & Statistik
- Posting Pengumuman

### 📚 Pustakawan
- Manajemen Buku & Kategori
- Approve/Reject Peminjaman
- Kelola Denda
- Lihat Laporan

### 👤 Member
- Lihat Katalog Buku
- Pinjam Buku
- Kembalikan Buku
- Perpanjang Peminjaman
- Bookmark Buku Favorit
- Lihat Riwayat Peminjaman
- Lihat Denda Pribadi

## 📝 Workflow Peminjaman

1. **Member** - Klik tombol "Pinjam" pada buku
2. **Permintaan Pending** - Menunggu approval dari Pustakawan
3. **Pustakawan** - Approve atau Reject permintaan
4. **Member** - Menerima notifikasi persetujuan
5. **Pengembalian** - Member kembalikan buku sebelum due date
6. **Denda** - Sistem otomatis hitung denda jika terlambat

## 🎨 Tema & Warna

```
Primary: #0ba5e9 (Biru)
Secondary: #8b5cf6 (Ungu)
Accent: #eab308 (Kuning)
```

## 📱 Responsive Breakpoints

- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

## 🔒 Keamanan

- ✅ CSRF Protection
- ✅ Password Hashing (Bcrypt)
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ XSS Protection
- ✅ Role-Based Authorization

## 📂 Struktur Folder

```
app/
  ├── Http/
  │   ├── Controllers/
  │   │   ├── AuthController.php
  │   │   ├── AdminController.php
  │   │   ├── BookController.php
  │   │   ├── BorrowingController.php
  │   │   └── Admin/ (sub-controllers)
  │   └── Middleware/
  │       └── CheckRole.php
  ├── Models/
  │   ├── User.php
  │   ├── Book.php
  │   ├── Category.php
  │   ├── Borrowing.php
  │   ├── Role.php
  │   └── ...
  └── Policies/
      └── BorrowingPolicy.php

database/
  ├── migrations/
  └── seeders/
      ├── RoleSeeder.php
      └── DatabaseSeeder.php

resources/
  ├── views/
  │   ├── auth/
  │   │   ├── login.blade.php
  │   │   └── register.blade.php
  │   ├── member/
  │   │   ├── dashboard.blade.php
  │   │   ├── books/
  │   │   └── borrowings/
  │   ├── admin/
  │   │   ├── dashboard.blade.php
  │   │   ├── books/
  │   │   ├── users/
  │   │   ├── categories/
  │   │   ├── borrowings/
  │   │   ├── reports/
  │   │   └── announcements/
  │   └── layouts/
  │       ├── app.blade.php
  │       └── auth-app.blade.php
  └── css/
      └── app.css (Tailwind)

routes/
  └── web.php
```

## 🐛 Troubleshooting

### Error: "SQLSTATE[HY000]: General error"
- Jalankan: `php artisan migrate:fresh`

### Assets tidak di-load
- Jalankan: `npm run build`
- atau: `npm run dev` (development)

### Permission Denied pada storage/
```bash
chmod -R 777 storage bootstrap/cache
```

### Database tidak tersambung
- Cek `.env` database credentials
- Pastikan MySQL running
- Jalankan: `php artisan migrate`

## 📖 Dokumentasi Lengkap

Lihat dokumentasi lengkap di folder `docs/` atau akses online.

## 🤝 Kontribusi

Contributions welcome! Silakan buat Pull Request.

## 📄 Lisensi

MIT License - Gratis untuk digunakan dan dimodifikasi.

## 📞 Support

Untuk pertanyaan atau bantuan, hubungi developer.

---

**Made with ❤️ untuk Perpustakaan Digital Indonesia**

Version: 1.0.0  
Last Updated: 2025-01-16
