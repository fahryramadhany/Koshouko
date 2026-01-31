# 🚀 PANDUAN IMPLEMENTASI FITUR BARU

Dokumen ini menjelaskan langkah-langkah untuk mengaktifkan semua fitur baru yang telah dibuat.

---

## ⚡ Quick Start

Setelah file-file baru dibuat, jalankan perintah berikut:

```bash
# 1. Jalankan migration untuk membuat table reports
php artisan migrate

# 2. Clear cache agar routes dan config ter-load dengan benar
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# 3. Opsional: Seed test data (jika diperlukan)
# php artisan db:seed
```

---

## 📁 Struktur File Baru

```
app/
├── Http/Controllers/
│   └── ReportController.php          [BARU]
├── Models/
│   └── Report.php                    [BARU]
├── Policies/
│   └── ReportPolicy.php              [BARU]
└── Providers/
    └── AppServiceProvider.php        [MODIFIED]

database/
├── migrations/
│   └── 2026_01_21_create_reports_table.php  [BARU]

resources/views/member/
├── reports/
│   ├── create.blade.php              [BARU]
│   ├── index.blade.php               [BARU]
│   ├── show.blade.php                [BARU]
│   └── edit.blade.php                [BARU]
├── edit-profile.blade.php            [BARU]
└── profile.blade.php                 [MODIFIED]

routes/
└── web.php                           [MODIFIED]
```

---

## ✅ Langkah Implementasi Detail

### 1️⃣ Backup Database (Optional tapi Recommended)

```bash
# Export database sebelum migration
mysqldump -u root perpus_digit_laravel > backup_before_migration.sql
```

### 2️⃣ Run Migration

```bash
php artisan migrate
```

**Output yang diharapkan**:
```
Migrating: 2026_01_21_create_reports_table
Migrated:  2026_01_21_create_reports_table (xxx.xx ms)
```

Jika ada error, check:
- Koneksi database
- Laravel version compatibility
- Database user permissions

### 3️⃣ Clear All Caches

```bash
# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear application cache
php artisan cache:clear

# Optional: Clear view cache
php artisan view:clear
```

### 4️⃣ Verify Routes

Jalankan perintah berikut untuk verify routes sudah terdaftar:

```bash
# List all routes related to reports
php artisan route:list | grep report

# List all routes related to profile
php artisan route:list | grep profile
```

**Output yang diharapkan**:
```
GET|HEAD     /profile                              profile                 DashboardController@profile
GET|HEAD     /profile/edit                         profile.edit           DashboardController@editProfile
PUT          /profile                              profile.update         DashboardController@updateProfile
GET|HEAD     /reports                              reports.index          ReportController@index
GET|HEAD     /reports/create                       reports.create         ReportController@create
POST         /reports                              reports.store          ReportController@store
GET|HEAD     /reports/{report}                     reports.show           ReportController@show
GET|HEAD     /reports/{report}/edit                reports.edit           ReportController@edit
PUT          /reports/{report}                     reports.update         ReportController@update
DELETE       /reports/{report}                     reports.destroy        ReportController@destroy
```

---

## 🧪 Testing

### Test 1: Akses Profile Page
```
URL: http://localhost:8000/profile
Expected: ✅ Tidak ada error, bisa lihat info akun
```

### Test 2: Edit Profil
```
URL: http://localhost:8000/profile/edit
Expected: ✅ Form edit dengan field nama, email, telepon, alamat
Actions:
- Ubah nama → Save → Success message
- Ubah email (invalid) → Save → Error message
- Fill semua field → Save → Redirect ke profile
```

### Test 3: Buat Laporan
```
URL: http://localhost:8000/reports/create
Expected: ✅ Form dengan dropdown tipe dan textarea deskripsi
Actions:
- Submit dengan tipe "book_issue" → Success
- Submit tanpa deskripsi → Error message
- Check email untuk notifikasi (jika mail configured)
```

### Test 4: Lihat Daftar Laporan
```
URL: http://localhost:8000/reports
Expected: ✅ List laporan dengan status badge dan actions
Actions:
- Lihat detail laporan (show)
- Edit laporan (hanya status pending)
- Delete laporan (hanya status pending)
- Pagination working
```

### Test 5: Buku Favorit
```
URL: http://localhost:8000/profile
Expected: ✅ Section "Buku Favorit Saya" dengan list buku
Actions:
- Click "Lihat" → Buka halaman buku
- Click "Hapus" → Remove dari favorit
```

---

## 🔧 Troubleshooting

### Error: "Table 'reports' doesn't exist"
**Solusi**:
```bash
php artisan migrate
# Atau jika ada yang salah:
php artisan migrate:rollback
php artisan migrate
```

### Error: "Route 'reports.index' not found"
**Solusi**:
```bash
php artisan route:clear
php artisan config:clear
# Restart server
```

### Error: "Call to undefined method"
**Solusi**:
```bash
php artisan config:clear
php composer require --dev (jika ada missing package)
```

### Error: "Unauthorized" saat edit/delete laporan
**Solusi**:
- Pastikan user sudah login
- Check bahwa user_id match dengan report user_id
- Verify policy di AppServiceProvider terdaftar

---

## 📊 Database Schema (Reports Table)

```sql
CREATE TABLE `reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('book_issue','account_issue','technical_issue','other') NOT NULL DEFAULT 'other',
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `status` enum('pending','in_progress','resolved','rejected') NOT NULL DEFAULT 'pending',
  `response` longtext,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reports_user_id_index` (`user_id`),
  KEY `reports_status_index` (`status`),
  KEY `reports_created_at_index` (`created_at`),
  CONSTRAINT `reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🎨 UI/UX Features

### Color Scheme (Bootstrap)
- **Pending**: Yellow/Warning
- **In Progress**: Blue/Info
- **Resolved**: Green/Success
- **Rejected**: Red/Danger

### Icons Used
- 📋 Report
- 👤 Profile
- ⭐ Favorites
- ✏️ Edit
- ⚡ Quick Actions
- 📚 Books
- ✓ Success
- ⚠️ Warning

### Responsive Design
- ✅ Mobile-first approach
- ✅ Grid layout (1 col mobile, 2 col tablet, 3 col desktop)
- ✅ Touch-friendly buttons
- ✅ Readable font sizes

---

## 🔐 Security Features

### Authorization
- ✅ Policy-based authorization (Report)
- ✅ User dapat hanya akses data sendiri
- ✅ Middleware check untuk authentication

### Validation
- ✅ Server-side validation di controller
- ✅ HTML5 client-side validation
- ✅ Email format check
- ✅ Date validation

### CSRF Protection
- ✅ @csrf token di semua forms
- ✅ @method('PUT'|'DELETE') untuk non-GET requests

---

## 📝 API Endpoints (Ringkas)

### Profile Routes
```
GET    /profile          - Lihat profil
GET    /profile/edit     - Form edit profil
PUT    /profile          - Submit edit profil
```

### Report Routes
```
GET    /reports          - Lihat list laporan
GET    /reports/create   - Form buat laporan
POST   /reports          - Submit buat laporan
GET    /reports/{id}     - Lihat detail laporan
GET    /reports/{id}/edit - Form edit laporan
PUT    /reports/{id}     - Submit edit laporan
DELETE /reports/{id}     - Hapus laporan
```

---

## 📚 Controller Methods Reference

### DashboardController
```php
public function profile()              // GET /profile
public function editProfile()          // GET /profile/edit
public function updateProfile()        // PUT /profile
```

### ReportController
```php
public function index()                // GET /reports
public function create()               // GET /reports/create
public function store()                // POST /reports
public function show()                 // GET /reports/{report}
public function edit()                 // GET /reports/{report}/edit
public function update()               // PUT /reports/{report}
public function destroy()              // DELETE /reports/{report}
```

---

## 🎯 Next Steps (Optional Features)

### Fase 2 (Future Development)
- [ ] Notification system untuk laporan baru
- [ ] Email notification ke admin
- [ ] Admin dashboard untuk manage reports
- [ ] Report analytics/statistics
- [ ] Category management untuk reports
- [ ] Chat/discussion untuk laporan
- [ ] Attachment upload di reports
- [ ] Rating sistem untuk resolved reports

---

## 📞 Support

Jika ada issues atau pertanyaan:

1. **Check error logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check database**:
   ```bash
   # Login ke MySQL
   mysql -u root perpus_digit_laravel
   # Check table structure
   SHOW COLUMNS FROM reports;
   ```

3. **Check routes**:
   ```bash
   php artisan route:list
   ```

---

## ✨ Summary

Setelah mengikuti langkah-langkah di atas, Anda akan memiliki:

✅ Error fixed: `borrowed_date → borrowed_at`
✅ Report System: Create, Read, Update, Delete laporan
✅ Edit Profile: Update biodata akun member
✅ Bookmarks: Sudah integrated, tinggal digunakan
✅ Responsive UI: Mobile & desktop friendly
✅ Security: Authorization & validation

Selamat! Sistem sudah siap digunakan! 🎉

