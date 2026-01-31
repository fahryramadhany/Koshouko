# 🚀 QUICK START - Testing Pemisahan Admin & Pustakawan

## Persiapan Database

Pastikan database sudah memiliki user dengan role berbeda:

```sql
-- Check roles
SELECT * FROM roles;
-- Output: 
-- 1 | admin
-- 2 | pustakawan
-- 3 | member

-- Check user dengan role berbeda
SELECT id, name, email, role_id FROM users;
```

Jika belum ada user, buat user baru dengan role berbeda melalui aplikasi atau database.

---

## 📝 Testing Checklist

### ✅ Test 1: Login sebagai Admin

```
1. Buka aplikasi
2. Login dengan akun ADMIN
3. Cek apakah redirect ke: /admin/dashboard
4. Verifikasi:
   ☐ Dashboard menampilkan "Admin Dashboard"
   ☐ Sidebar menu menampilkan "Kelola User"
   ☐ Tombol "Tambah User" terlihat di quick actions
   ☐ Bisa akses /admin/users
   ☐ Bisa lihat daftar user
```

### ✅ Test 2: Login sebagai Pustakawan

```
1. Logout dari admin
2. Login dengan akun PUSTAKAWAN
3. Cek apakah redirect ke: /librarian/dashboard
4. Verifikasi:
   ☐ Dashboard menampilkan "Librarian Dashboard"
   ☐ Sidebar menu TIDAK menampilkan "Kelola User"
   ☐ Tombol "Tambah User" TIDAK terlihat
   ☐ Coba akses /admin/users → Error/Redirect
   ☐ Bisa akses /librarian/books
   ☐ Bisa akses /librarian/borrowings
```

### ✅ Test 3: Login sebagai Member

```
1. Logout dari pustakawan
2. Login dengan akun MEMBER
3. Cek apakah redirect ke: /dashboard
4. Verifikasi:
   ☐ Member dashboard ditampilkan
   ☐ TIDAK ada sidebar menu admin/pustakawan
   ☐ TIDAK bisa akses /admin/*
   ☐ TIDAK bisa akses /librarian/*
```

### ✅ Test 4: Menu Navigation

**Admin Menu (Sidebar):**
```
Management
├── 📊 Dashboard → /admin/dashboard ✅
├── 📖 Kelola Buku → /admin/books ✅
├── 🏷️ Kategori → /admin/categories ✅
└── 👥 Kelola User → /admin/users ✅ HANYA ADMIN

Operations
├── 📋 Peminjaman → /admin/borrowings ✅
├── 📢 Pengumuman → /admin/announcements ✅
└── 📈 Laporan → /admin/reports ✅
```

**Pustakawan Menu (Sidebar):**
```
Management
├── 📊 Dashboard → /librarian/dashboard ✅
├── 📖 Kelola Buku → /librarian/books ✅
└── 🏷️ Kategori → /librarian/books-categories ✅

Operations
├── 📋 Peminjaman → /librarian/borrowings ✅
├── 📢 Pengumuman → /librarian/announcements ✅
└── 📈 Laporan → /librarian/reports ✅
```

### ✅ Test 5: Fitur Kelola User (HANYA ADMIN)

**Admin bisa:**
```
1. Akses /admin/users ✅
2. Lihat daftar user ✅
3. Klik "Edit" untuk edit user ✅
4. Klik "Hapus" untuk delete user ✅
5. Ada tombol "Tambah User" ✅
6. Bisa create user baru ✅
```

**Pustakawan TIDAK bisa:**
```
1. Akses /admin/users ❌
2. Sidebar tidak menampilkan "Kelola User" ❌
3. URL /admin/users → Error 403/Redirect ❌
4. URL /librarian/users → Error 404 (route tidak ada) ❌
```

### ✅ Test 6: Fitur Kelola Buku (SAMA untuk Admin & Pustakawan)

**Admin:**
```
1. Akses /admin/books ✅
2. Create, Read, Update, Delete buku ✅
3. Manage categories ✅
```

**Pustakawan:**
```
1. Akses /librarian/books ✅
2. Create, Read, Update, Delete buku ✅
3. Manage categories ✅
```

> Kedua role memiliki akses yang sama untuk kelola buku

### ✅ Test 7: Fitur Kelola Peminjaman (SAMA untuk Admin & Pustakawan)

**Admin:**
```
1. Akses /admin/borrowings ✅
2. Approve peminjaman ✅
3. Reject peminjaman ✅
```

**Pustakawan:**
```
1. Akses /librarian/borrowings ✅
2. Approve peminjaman ✅
3. Reject peminjaman ✅
```

> Kedua role memiliki akses yang sama untuk manage peminjaman

---

## 🔐 Security Testing

### Test 1: Pustakawan coba akses admin URLs

```bash
# Jika login sebagai Pustakawan, coba akses:
http://localhost/admin/users
http://localhost/admin/dashboard
http://localhost/admin/users/create

# Expected: Error 403 atau redirect
```

### Test 2: Member coba akses staff URLs

```bash
# Jika login sebagai Member, coba akses:
http://localhost/admin/dashboard
http://localhost/librarian/dashboard
http://localhost/admin/users

# Expected: Error 403 atau redirect
```

### Test 3: Direct URL Access (tanpa melalui sidebar)

```bash
# Admin akses librarian route
http://localhost/librarian/dashboard
# Expected: TIDAK error (tapi jangan, biarkan admin akses librarian routes)

# Pustakawan akses admin route
http://localhost/admin/users
# Expected: ERROR / FORBIDDEN (dilindungi middleware)
```

---

## 📋 Database Queries untuk Setup Test

```sql
-- Cek apakah sudah ada role
SELECT * FROM roles;

-- Jika belum, insert roles
INSERT INTO roles (id, name, created_at, updated_at) VALUES
(1, 'admin', NOW(), NOW()),
(2, 'pustakawan', NOW(), NOW()),
(3, 'member', NOW(), NOW());

-- Buat test user admin
INSERT INTO users (name, email, password, role_id, member_id, created_at, updated_at) VALUES
('Admin Perpustakaan', 'admin@perpus.local', '$2y$10$...', 1, NULL, NOW(), NOW());

-- Buat test user pustakawan
INSERT INTO users (name, email, password, role_id, member_id, created_at, updated_at) VALUES
('Pustakawan Test', 'pustakawan@perpus.local', '$2y$10$...', 2, NULL, NOW(), NOW());

-- Buat test user member
INSERT INTO users (name, email, password, role_id, member_id, created_at, updated_at) VALUES
('Member Test', 'member@perpus.local', '$2y$10$...', 3, 'M001', NOW(), NOW());
```

---

## 🎯 Hasil yang Diharapkan

### ✅ Semua test HARUS Passed:

1. **Admin Dashboard Accessible** ✅
   - Login admin → `/admin/dashboard`
   - Menu lengkap terlihat

2. **Pustakawan Dashboard Accessible** ✅
   - Login pustakawan → `/librarian/dashboard`
   - Menu tanpa user management

3. **User Management Protected** ✅
   - `/admin/users` hanya bisa admin akses
   - Pustakawan tidak bisa akses

4. **Navigation Menu Different** ✅
   - Admin sidebar tampilkan "Kelola User"
   - Pustakawan sidebar TIDAK tampilkan "Kelola User"

5. **Fitur Lain Sama** ✅
   - Admin dan Pustakawan bisa akses buku, peminjaman, dll dengan cara yang sama

6. **Security Protected** ✅
   - Middleware melindungi routes
   - Unauthorized access ditolak

---

## 🚨 Troubleshooting

### Problem: Setelah login masih ke halaman lama

**Solution:**
- Clear browser cache (Ctrl+Shift+Delete)
- Clear Laravel cache: `php artisan cache:clear`
- Clear config cache: `php artisan config:clear`
- Restart server

### Problem: Routes tidak ditemukan

**Solution:**
- Jalankan: `php artisan cache:clear`
- Jalankan: `php artisan route:clear`
- Restart server

### Problem: Middleware tidak jalan

**Solution:**
- Cek apakah user role_id sudah benar di database
- Jalankan: `php artisan cache:clear`

### Problem: Menu tidak berubah

**Solution:**
- Logout dan login ulang
- Clear browser cache
- Check di `resources/views/layouts/auth-app.blade.php` apakah kondisi `isPustakawan()` sudah ada

---

## 📞 Log Testing

Gunakan checklist ini untuk mencatat hasil testing:

```
Date: ___________
Tester: ___________

TEST RESULTS:
☐ Admin Login & Redirect: PASS / FAIL
☐ Pustakawan Login & Redirect: PASS / FAIL
☐ Member Login & Redirect: PASS / FAIL
☐ Admin Menu Correct: PASS / FAIL
☐ Pustakawan Menu Correct: PASS / FAIL
☐ User Management Protected: PASS / FAIL
☐ Routes Protected: PASS / FAIL
☐ Dashboard Features: PASS / FAIL

OVERALL STATUS: ☐ PASS / ☐ FAIL

Notes:
_________________________________________________________________
_________________________________________________________________
```

---

## ✅ Final Checklist

Jika semua test PASSED, maka implementasi pemisahan Admin & Pustakawan sudah **BERHASIL** ✅

---

**Happy Testing! 🎉**
