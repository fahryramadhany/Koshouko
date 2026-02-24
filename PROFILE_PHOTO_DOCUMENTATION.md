# Fitur Foto Profil - Dokumentasi CRUD

## Ringkasan
Fitur Foto Profil memungkinkan pengguna untuk mengunggah, melihat, dan menghapus foto profil mereka. Fitur ini mencakup validasi gambar, penyimpanan aman, dan antarmuka pengguna yang intuitif.

## Komponen yang Ditambahkan

### 1. Database Migration
**File:** `database/migrations/2026_02_10_153624_add_profile_photo_to_users_table.php`

Menambahkan kolom `profile_photo` ke tabel `users`:
- Tipe: String (nullable)
- Posisi: Setelah kolom `status`
- Fungsi: Menyimpan path file foto profil

```php
$table->string('profile_photo')->nullable()->after('status');
```

### 2. Model
**File:** `app/Models/User.php`

Pembaruan pada model User:
- Menambahkan `profile_photo` ke fillable array
- Memungkinkan model untuk massal menerima data foto profil

```php
protected $fillable = [
    // ... field lainnya
    'profile_photo',
];
```

### 3. Controller
**File:** `app/Http/Controllers/ProfilePhotoController.php`

Controller menangani operasi CRUD lengkap:

#### Methods:
- **edit()** - Menampilkan form edit/upload foto profil
- **store()** - Menyimpan/upload foto profil baru
- **show()** - Menampilkan foto profil pengguna
- **destroy()** - Menghapus foto profil

#### Validasi:
- Format: JPEG, PNG, JPG, GIF, WebP
- Ukuran maksimal: 2MB
- Tipe: Image/file

#### Fitur Keamanan:
- Autentikasi: Pengguna harus login
- Otorisasi: Hanya bisa mengubah foto profil sendiri (atau admin)
- Penghapusan otomatis: Foto lama dihapus saat upload foto baru

### 4. Policy
**File:** `app/Policies/UserPolicy.php`

Policy untuk otorisasi:
- `update()` - Pengguna bisa update profil sendiri atau admin bisa update siapa saja
- `delete()` - Hanya admin bisa delete user
- `view()` - Pengguna bisa lihat profil sendiri atau admin bisa lihat siapa saja

### 5. View
**File:** `resources/views/profile-photo/edit.blade.php`

Interface untuk:
- Menampilkan foto profil saat ini
- Upload foto baru dengan drag & drop
- Menghapus foto profil
- Validasi dan pesan kesalahan
- Panduan penggunaan

### 6. Routes
**File:** `routes/web.php`

Routes yang ditambahkan:
```php
Route::get('/profile/{user}/photo/edit', [ProfilePhotoController::class, 'edit'])->name('profile-photo.edit');
Route::post('/profile/{user}/photo', [ProfilePhotoController::class, 'store'])->name('profile-photo.store');
Route::delete('/profile/{user}/photo', [ProfilePhotoController::class, 'destroy'])->name('profile-photo.destroy');
Route::get('/profile/{user}/photo', [ProfilePhotoController::class, 'show'])->name('profile-photo.show');
```

## Cara Penggunaan

### Untuk Pengguna:

1. **Edit Foto Profil:**
   - Kunjungi: `/profile/{user}/photo/edit`
   - Atau: `route('profile-photo.edit', $user)`

2. **Upload Foto Baru:**
   - Klik area drag & drop atau pilih file
   - Pilih gambar (JPG, PNG, GIF, WebP)
   - Klik tombol "Upload Foto"
   - Foto lama akan otomatis dihapus

3. **Hapus Foto Profil:**
   - Klik tombol "Hapus Foto"
   - Konfirmasi penghapusan
   - Foto akan dihapus dari server

### Untuk Developer:

Menampilkan foto profil di template Blade:
```blade
@if($user->profile_photo)
    <img src="{{ Storage::url($user->profile_photo) }}" alt="Foto Profil {{ $user->name }}">
@else
    <img src="/images/default-avatar.png" alt="Avatar Default">
@endif
```

Mendapatkan URL foto profil:
```php
$photoUrl = Storage::url($user->profile_photo);
```

## Penyimpanan File

- **Direktori:** `storage/app/public/profile-photos/`
- **Akses:** `/storage/profile-photos/{filename}`
- **Symbolic Link:** Perlu menjalankan `php artisan storage:link` jika belum ada

## Integrasi dengan Profile

Untuk menambahkan link ke halaman edit foto profil di halaman profil member:

```blade
<a href="{{ route('profile-photo.edit', Auth::user()) }}" class="btn btn-primary">
    Edit Foto Profil
</a>
```

## Testing

### Test Upload:
```bash
POST /profile/{user}/photo
Content-Type: multipart/form-data
profile_photo: [image file, max 2MB]
```

### Test Delete:
```bash
DELETE /profile/{user}/photo
```

### Test View:
```bash
GET /profile/{user}/photo/edit
```

## Error Handling

Fitur menangani:
- File tidak valid (bukan gambar)
- Ukuran file terlalu besar (> 2MB)
- Format file tidak didukung
- Penghapusan file fail dari storage

Semua error ditampilkan dengan pesan yang jelas dalam Bahasa Indonesia.

## Database Schema

```sql
Column: profile_photo
Type: VARCHAR (String)
Nullable: YES
Default: NULL
After: status
```

## File Struktur Lengkap

```
app/
├── Http/
│   └── Controllers/
│       └── ProfilePhotoController.php          [BARU]
├── Models/
│   └── User.php                                [UPDATE - tambah fillable]
├── Policies/
│   └── UserPolicy.php                          [BARU]
└── Providers/
    └── AppServiceProvider.php                  [UPDATE - register policy]

database/
└── migrations/
    └── 2026_02_10_153624_add_profile_photo_to_users_table.php [BARU]

resources/
└── views/
    └── profile-photo/
        └── edit.blade.php                      [BARU]

routes/
└── web.php                                     [UPDATE - tambah routes]

storage/
└── app/
    └── public/
        └── profile-photos/                     [DIRECTORY]
```

## Checklist Implementasi

- ✅ Migration dibuat dan dijalankan
- ✅ Model User diupdate dengan fillable field
- ✅ Controller ProfilePhotoController dibuat
- ✅ Policy UserPolicy dibuat dan terdaftar
- ✅ View edit.blade.php dibuat
- ✅ Routes ditambahkan ke web.php
- ✅ Storage directory siap untuk menyimpan file
- ⏳ Integrate link ke profile page (manual)
- ⏳ Test fitur secara menyeluruh

## Troubleshooting

1. **Foto tidak tampil:**
   - Pastikan `php artisan storage:link` sudah dijalankan
   - Periksa permissions folder `storage/app/public/`

2. **Upload fail:**
   - Cek ukuran file (max 2MB)
   - Cek format file (harus JPEG, PNG, GIF, WebP)
   - Cek permissions folder `storage/app/public/profile-photos/`

3. **Authorization error:**
   - Pastikan pengguna login
   - Pastikan policy sudah terdaftar di AppServiceProvider

## Catatan Penting

- Foto disimpan di `storage/app/public/profile-photos/`
- Symbolic link harus aktif untuk akses publik
- Foto lama otomatis dihapus saat upload foto baru
- Default avatar masih bisa digunakan jika user belum upload foto
- Fitur fully terintegrasi dengan sistem autentikasi Laravel
