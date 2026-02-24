# Quick Start - Integrasi Foto Profil

## Setup Awal

Fitur foto profil sudah sepenuhnya diimplementasikan! Berikut adalah langkah-langkah untuk mengintegrasikannya sepenuhnya.

## 1. Buat Symbolic Link (Jika Belum)

Jalankan perintah untuk membuat symbolic link agar foto dapat diakses dari web:

```bash
php artisan storage:link
```

## 2. Akses Halaman Edit Foto Profil

URL: `http://localhost/profile/{user_id}/photo/edit`

Atau di Blade:
```blade
<a href="{{ route('profile-photo.edit', Auth::user()) }}" class="btn btn-primary">
    Edit Foto Profil
</a>
```

## 3. Tambahkan Link di Member Profile

Edit file `resources/views/member/profile.blade.php` dan tambahkan link:

```blade
<div class="profile-photo-section">
    <h3>Foto Profil</h3>
    @if($user->profile_photo)
        <img src="{{ Storage::url($user->profile_photo) }}" alt="Foto Profil" class="profile-avatar">
    @else
        <img src="/images/default-avatar.png" alt="Avatar Default" class="profile-avatar">
    @endif
    
    <a href="{{ route('profile-photo.edit', Auth::user()) }}" class="btn btn-primary">
        {{ $user->profile_photo ? 'Ganti Foto' : 'Upload Foto' }}
    </a>
</div>
```

## 4. Tambahkan di Dashboard Member

Edit file `resources/views/member/dashboard.blade.php`:

```blade
<div class="user-profile-card">
    <div class="profile-pic">
        @if(Auth::user()->profile_photo)
            <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Foto Profil">
        @else
            <img src="/images/default-avatar.png" alt="Avatar Default">
        @endif
    </div>
    
    <h2>{{ Auth::user()->name }}</h2>
    
    <a href="{{ route('profile-photo.edit', Auth::user()) }}" class="btn btn-sm btn-outline-primary">
        Edit Foto
    </a>
</div>
```

## 5. Validasi dan Testing

Setelah setup, test fitur dengan:

1. **Upload foto:**
   - Buka `/profile/{user_id}/photo/edit`
   - Upload gambar JPG/PNG (max 2MB)
   - Verifikasi foto muncul di halaman profil

2. **Ganti foto:**
   - Upload foto baru
   - Foto lama otomatis dihapus

3. **Hapus foto:**
   - Klik tombol "Hapus Foto"
   - Konfirmasi
   - Foto akan dihapus

## 6. CSS Styling (Optional)

Tambahkan CSS untuk styling foto profil di `public/css/custom.css`:

```css
.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #007bff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.profile-photo-section {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 20px;
}

.profile-photo-section h3 {
    margin-bottom: 15px;
    color: #333;
}

.user-profile-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.profile-pic {
    margin-bottom: 10px;
}

.profile-pic img {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #007bff;
}
```

## 7. Authentication & Authorization

Fitur sudah dilengkapi dengan:
- ✅ Autentikasi (harus login)
- ✅ Otorisasi (hanya bisa edit profil sendiri)
- ✅ Admin dapat edit profil user manapun

Tidak perlu setup tambahan!

## 8. Storage Configuration

File disimpan di:
- **Direktori:** `storage/app/public/profile-photos/`
- **Web URL:** `/storage/profile-photos/{filename}`
- **Akses di code:** `Storage::url($user->profile_photo)`

## 9. Backup/Migration

Jika perlu backup foto-foto:

```bash
# Backup folder profile-photos
cp -r storage/app/public/profile-photos backup/profile-photos
```

## API Endpoints (untuk integration external)

```
GET    /profile/{user_id}/photo/edit          - Tampilkan form edit
POST   /profile/{user_id}/photo               - Upload foto baru
DELETE /profile/{user_id}/photo               - Hapus foto
GET    /profile/{user_id}/photo               - Unduh foto
```

## Troubleshooting

### Foto tidak muncul
```bash
# Cek apakah symbolic link sudah dibuat
php artisan storage:link

# Cek permissions
chmod -R 775 storage/app/public
```

### Upload fail
- Pastikan folder `storage/app/public/profile-photos/` ada
- Cek file size (max 2MB)
- Cek format file (JPG, PNG, GIF, WebP)

### Authorization error
- Pastikan user sudah login
- Cek bahwa policy sudah registered di AppServiceProvider

## Complete Checklist

- [x] Migration dibuat dan dijalankan
- [x] Model dan Fillable diupdate
- [x] Controller dibuat
- [x] Policy dibuat dan registered
- [x] Views dibuat
- [x] Routes ditambahkan
- [ ] Symbolic link dibuat (`php artisan storage:link`)
- [ ] Integrated ke member profile page
- [ ] Integrated ke member dashboard
- [ ] CSS styling ditambahkan (optional)
- [ ] Testing dilakukan

## Next Steps

1. Jalankan: `php artisan storage:link`
2. Buka: `http://localhost/profile/{user_id}/photo/edit`
3. Test upload foto
4. Tambahkan link ke profile page sesuai langkah #3
5. Customize CSS sesuai kebutuhan

Enjoy! 🎉
