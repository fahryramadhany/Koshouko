# PERBEDAAN ADMIN vs PUSTAKAWAN (Librarian)

## 📊 Tabel Perbandingan Akses

| FITUR | ADMIN | PUSTAKAWAN |
|-------|:-----:|:----------:|
| **Dashboard** | ✅ `/admin/dashboard` | ✅ `/librarian/dashboard` |
| **Kelola Buku** | ✅ CRUD Buku | ✅ CRUD Buku |
| **Kelola Kategori** | ✅ CRUD Kategori | ✅ CRUD Kategori |
| **Kelola User** | ✅ **BISA** | ❌ **TIDAK BISA** |
| **Tambah User** | ✅ **BISA** | ❌ **TIDAK BISA** |
| **Edit User** | ✅ **BISA** | ❌ **TIDAK BISA** |
| **Hapus User** | ✅ **BISA** | ❌ **TIDAK BISA** |
| **Kelola Peminjaman** | ✅ Approve/Reject | ✅ Approve/Reject |
| **Posting Pengumuman** | ✅ CRUD | ✅ CRUD |
| **Lihat Laporan** | ✅ Statistik Lengkap | ✅ Statistik Lengkap |
| **QR Code Generator** | ✅ Generate & Print | ✅ Generate & Print |

---

## 🏠 Dashboard

### Admin Dashboard (`/admin/dashboard`)
- Menampilkan statistik lengkap
- Ada tombol "Tambah User" di quick actions
- Menampilkan "Informasi Sistem"

### Pustakawan Dashboard (`/librarian/dashboard`)
- Menampilkan statistik lengkap (sama seperti admin)
- **TIDAK ADA** tombol "Tambah User" di quick actions
- Menampilkan "Informasi Sistem"

---

## 📖 Kelola Buku

### Admin Books
- URL: `/admin/books`
- Menu Title: "Kelola Buku"
- Fitur: Create, Read, Update, Delete ✅
- Route Name: `admin.books.*`

### Pustakawan Books
- URL: `/librarian/books`
- Menu Title: "Kelola Buku"
- Fitur: Create, Read, Update, Delete ✅
- Route Name: `librarian.books.*`

> **SAMA PERSIS** - Tidak ada perbedaan fitur kelola buku antara admin dan pustakawan

---

## 👥 Kelola User

### Admin User Management
- URL: `/admin/users` ✅ **TERSEDIA**
- Menu: "👥 Kelola User" ✅ **TERLIHAT**
- Fitur: Create, Read, Update, Delete
- Route Names: `admin.users.*`
- Middleware: `check.role:admin`

### Pustakawan User Management
- URL: `/librarian/users` ❌ **TIDAK ADA**
- Menu: "👥 Kelola User" ❌ **TIDAK TERLIHAT**
- Fitur: ❌ TIDAK ADA
- Route Names: ❌ TIDAK ADA
- Middleware: ❌ TIDAK ADA

---

## 📋 Kelola Peminjaman

### Admin Borrowings
- URL: `/admin/borrowings`
- Fitur: Lihat daftar, Approve, Reject
- Route Name: `admin.borrowings*`

### Pustakawan Borrowings
- URL: `/librarian/borrowings`
- Fitur: Lihat daftar, Approve, Reject
- Route Name: `librarian.borrowings*`

> **SAMA** - Keduanya bisa manage peminjaman dengan fitur yang sama

---

## 📢 Posting Pengumuman

### Admin Announcements
- URL: `/admin/announcements`
- Fitur: Create, Read, Update, Delete
- Route Name: `admin.announcements*`

### Pustakawan Announcements
- URL: `/librarian/announcements`
- Fitur: Create, Read, Update, Delete
- Route Name: `librarian.announcements*`

> **SAMA** - Keduanya bisa posting dan manage pengumuman

---

## 📈 Laporan & Statistik

### Admin Reports
- URL: `/admin/reports`
- Konten: Statistik peminjaman, denda tertunggak, buku populer

### Pustakawan Reports
- URL: `/librarian/reports`
- Konten: **SAMA** - Statistik peminjaman, denda tertunggak, buku populer

> **SAMA** - Akses laporan dan statistik yang sama

---

## 🔐 Middleware Protection

### Admin Routes
```php
Route::middleware('check.role:admin')->prefix('admin')->name('admin.')->group(function () {
    // HANYA admin yang bisa akses
});
```

### Pustakawan Routes
```php
Route::middleware('check.role:pustakawan')->prefix('librarian')->name('librarian.')->group(function () {
    // HANYA pustakawan yang bisa akses
});
```

---

## 🎯 Poin Penting

### ✅ Yang SAMA
- Kelola Buku (Create, Read, Update, Delete)
- Kelola Kategori (Create, Read, Update, Delete)
- Kelola Peminjaman (Approve, Reject)
- Posting Pengumuman
- Lihat Laporan
- QR Code Generator

### ❌ Yang BERBEDA
- **Admin BISA**, Pustakawan **TIDAK BISA** : Kelola User (Tambah, Edit, Hapus User)

---

## 📱 Menu Navigation

### Admin Sidebar Menu
```
Management
├── 📊 Dashboard
├── 📖 Kelola Buku
├── 🏷️ Kategori
└── 👥 Kelola User ← HANYA ADMIN

Operations
├── 📋 Peminjaman
├── 📢 Pengumuman
└── 📈 Laporan
```

### Pustakawan Sidebar Menu
```
Management
├── 📊 Dashboard
├── 📖 Kelola Buku
└── 🏷️ Kategori

Operations
├── 📋 Peminjaman
├── 📢 Pengumuman
└── 📈 Laporan
```

---

## 🚫 Akses Control

### Jika Pustakawan coba akses `/admin/users`
1. Middleware `check.role:admin` akan mengecek role user
2. Jika user bukan admin, akses ditolak
3. User akan mendapat error 403 atau redirect

### URL Protection
```
/admin/users           → Hanya Admin
/admin/dashboard       → Hanya Admin
/librarian/dashboard   → Hanya Pustakawan
/librarian/users       → Route TIDAK ADA (404)
```

---

## 📝 Summary

**Pustakawan (Librarian) adalah:**
- ✅ Staff yang bisa manage buku dan peminjaman
- ✅ Bisa posting pengumuman
- ✅ Bisa lihat laporan
- ❌ TIDAK bisa manage user/account
- ❌ TIDAK bisa tambah/edit/hapus member atau staff lain

**Admin adalah:**
- ✅ Manager sistem dengan akses penuh
- ✅ Bisa manage semua fitur termasuk user
- ✅ Bisa menambah staff (pustakawan) atau member
- ✅ Bisa edit/hapus user apapun
