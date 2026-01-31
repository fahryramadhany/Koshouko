# ⚡ QUICK START - 5 MENIT SETUP

Ikuti langkah-langkah di bawah untuk mengaktifkan semua fitur baru dalam 5 menit!

---

## 🎯 STEP-BY-STEP GUIDE

### ✅ STEP 1: Run Migration (1 menit)

```bash
cd c:\xampp\htdocs\perpus_digit_laravel

# Run migration untuk membuat table reports
php artisan migrate
```

**Expected Output**:
```
Migrating: 2026_01_21_create_reports_table
Migrated:  2026_01_21_create_reports_table (xxx.xxms)
```

**Jika error**:
```bash
# Check database connection
php artisan migrate:status

# Rollback jika diperlukan
php artisan migrate:rollback

# Retry
php artisan migrate
```

---

### ✅ STEP 2: Clear Cache (1 menit)

```bash
# Clear semua caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

**Atau gunakan command alias**:
```bash
php artisan optimize:clear
```

---

### ✅ STEP 3: Restart Server (1 menit)

**Jika menggunakan Artisan Server**:
```bash
# Stop server (Ctrl+C)
# Restart
php artisan serve
```

**Jika menggunakan XAMPP**:
- Stop Apache di XAMPP Control Panel
- Click Start lagi

---

### ✅ STEP 4: Verify Routes (1 menit)

```bash
# Check apakah routes sudah terdaftar
php artisan route:list | grep report
php artisan route:list | grep profile
```

**Expected Output**:
```
GET|HEAD     /reports                    reports.index
GET|HEAD     /reports/create             reports.create
POST         /reports                    reports.store
GET|HEAD     /reports/{report}           reports.show
GET|HEAD     /reports/{report}/edit      reports.edit
PUT          /reports/{report}           reports.update
DELETE       /reports/{report}           reports.destroy

GET|HEAD     /profile                    profile
GET|HEAD     /profile/edit               profile.edit
PUT          /profile                    profile.update
```

---

### ✅ STEP 5: Test Features (1 menit)

**Open browser dan test**:

1. **Cek error fix**:
   ```
   http://localhost:8000/profile
   → Tidak ada error, bisa lihat data
   ```

2. **Test create report**:
   ```
   http://localhost:8000/reports/create
   → Bisa lihat form laporan
   ```

3. **Test list reports**:
   ```
   http://localhost:8000/reports
   → Bisa lihat daftar laporan
   ```

4. **Test edit profile**:
   ```
   http://localhost:8000/profile/edit
   → Bisa lihat form edit
   ```

---

## 🎨 FEATURE SCREENSHOTS (Locations)

### Profile Page (Error Fixed)
```
📍 http://localhost:8000/profile

Changes:
- Riwayat Peminjaman Terakhir (no error pada tanggal)
- Button "✏️ Edit Profil" (baru)
- Quick Actions dengan "📋 Laporan Masalah" (baru)
```

### Report Create Page
```
📍 http://localhost:8000/reports/create

Features:
- Form dengan dropdown tipe laporan
- Textarea untuk deskripsi
- Validation feedback
- Tips section
```

### Report List Page
```
📍 http://localhost:8000/reports

Features:
- List laporan dengan status badge
- Action buttons (view, edit, delete)
- Pagination
- Empty state
```

### Edit Profile Page
```
📍 http://localhost:8000/profile/edit

Features:
- Form untuk update biodata
- Pre-filled current data
- Field: name, email, phone, address, DOB
- Save & Cancel buttons
```

---

## 🚨 TROUBLESHOOTING

### Error: "Table 'reports' doesn't exist"
```bash
# Solution
php artisan migrate
php artisan cache:clear
# Restart browser
```

### Error: "Route 'reports.index' not found"
```bash
# Solution
php artisan route:clear
php artisan config:clear
# Restart server
```

### Error: "Class Report not found"
```bash
# Solution
php artisan config:clear
# Check file: app/Models/Report.php exists
```

### Error: "Unauthorized" saat edit/delete report
```
Check:
1. Anda sudah login
2. User ID match dengan report owner
3. Report status adalah "pending" (untuk edit/delete)
```

### Error: "Email already exists"
```
Reason: Email sudah dipakai user lain
Solution: Gunakan email baru
```

---

## 📋 VERIFICATION CHECKLIST

Setelah setup, checklist berikut:

- [ ] Migration berhasil (table `reports` terbuat)
- [ ] Routes registered (bisa lihat dengan `php artisan route:list`)
- [ ] Profile page loading (no error)
- [ ] Dapat akses /profile/edit
- [ ] Dapat akses /reports/create
- [ ] Dapat akses /reports
- [ ] Form validation working (submit kosong → error)
- [ ] Dapat membuat report baru
- [ ] Report muncul di list
- [ ] Dapat edit report (pending only)
- [ ] Dapat delete report (pending only)
- [ ] Dapat update profile
- [ ] Email validation working

---

## 💾 DATABASE BACKUP (OPTIONAL)

**Sebelum migration, backup database**:

```bash
# Windows Command Prompt
cd c:\xampp\mysql\bin
mysqldump -u root perpus_digit_laravel > backup_before_reports.sql

# Atau gunakan phpMyAdmin
# 1. Login ke phpMyAdmin
# 2. Select database perpus_digit_laravel
# 3. Click "Export"
# 4. Download SQL file
```

**Restore jika diperlukan**:
```bash
cd c:\xampp\mysql\bin
mysql -u root perpus_digit_laravel < backup_before_reports.sql
```

---

## 🎯 KEY ENDPOINTS SUMMARY

| Feature | URL | Method | Purpose |
|---------|-----|--------|---------|
| **Error Fix** | /profile | GET | View profile (fixed) |
| **Report List** | /reports | GET | View laporan |
| **Create Report** | /reports/create | GET | Form create |
| **Submit Report** | /reports | POST | Save laporan |
| **View Report** | /reports/{id} | GET | Detail laporan |
| **Edit Report** | /reports/{id}/edit | GET | Form edit |
| **Update Report** | /reports/{id} | PUT | Save edit |
| **Delete Report** | /reports/{id} | DELETE | Hapus laporan |
| **Edit Profile** | /profile/edit | GET | Form edit |
| **Update Profile** | /profile | PUT | Save profile |

---

## 📱 RESPONSIVE TEST

Test di berbagai ukuran:

- [ ] Desktop (1920x1080)
  - Form buttons aligned
  - Table readable
  - Layout proper
  
- [ ] Tablet (768x1024)
  - Single column layout
  - Touch-friendly buttons
  - Readable text
  
- [ ] Mobile (375x667)
  - Stack layout
  - Full-width inputs
  - Proper spacing

---

## 🔍 WHAT'S NEW

### Untuk Member
```
✨ Bisa lapor masalah langsung di aplikasi
✨ Bisa track status laporan
✨ Bisa update profil (nama, email, telepon, alamat, DOB)
✨ Error "borrowed_date" sudah diperbaiki
✨ Buku favorit sudah integrated
```

### Untuk Admin/Developer
```
✨ ReportController dengan CRUD lengkap
✨ Report model dengan relationship
✨ ReportPolicy untuk authorization
✨ Database migration dengan proper schema
✨ Full validation & error handling
✨ Responsive UI dengan existing design system
```

---

## 📞 QUICK REFERENCE

### Important Files
```
✏️ Error Fix: resources/views/member/profile.blade.php
📋 Report Controller: app/Http/Controllers/ReportController.php
🗄️ Report Model: app/Models/Report.php
🛂 Report Policy: app/Policies/ReportPolicy.php
📄 Routes: routes/web.php
🎨 Views: resources/views/member/reports/ dan edit-profile.blade.php
```

### Common Commands
```
php artisan migrate              # Run migration
php artisan migrate:status       # Check migration status
php artisan route:list           # List all routes
php artisan tinker               # Interactive shell
php artisan cache:clear          # Clear cache
```

---

## ✅ FINAL CHECKLIST

Before declaring complete:

- [x] Code sudah written
- [x] Migration file created
- [x] Views sudah dibuat
- [x] Routes sudah ditambah
- [x] Models & Relationships updated
- [x] Authorization (Policy) implemented
- [x] Validation rules defined
- [x] Error handling added
- [x] UI/UX responsive
- [x] Documentation completed
- [ ] Migration dijalankan (USER RESPONSIBILITY)
- [ ] Cache cleared (USER RESPONSIBILITY)
- [ ] Features tested (USER RESPONSIBILITY)

---

## 🎉 DONE!

Setelah STEP 1-5 selesai, semua fitur siap digunakan!

Jika ada masalah:
1. Check logs: `tail -f storage/logs/laravel.log`
2. Check database: `mysql -u root perpus_digit_laravel`
3. Clear cache: `php artisan optimize:clear`
4. Restart server

**Happy coding! 🚀**

---

Created: 2026-01-21
Last Updated: 2026-01-21
Status: READY

