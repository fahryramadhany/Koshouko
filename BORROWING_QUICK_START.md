# ⚡ QUICK START - BORROWING SYSTEM

## 🚀 3 MENIT UNTUK MULAI

### 1️⃣ Pastikan Database Siap (1 menit)
```bash
cd c:\xampp\htdocs\perpus_digit_laravel

# Cek migrasi sudah jalan
php artisan migrate:status

# Jika 2025_01_28_000001 sudah "Ran" → OK ✅
# Jika belum, jalankan:
php artisan migrate
```

### 2️⃣ Start Laravel Server (1 menit)
```bash
php artisan serve
# Buka: http://localhost:8000
```

### 3️⃣ Login & Test (1 menit)

#### Browser 1 - MEMBER
```
1. Login sebagai member
2. Buka: http://localhost:8000/borrowings/create
3. Pilih buku, durasi, setujui syarat
4. Klik "Ajukan Peminjaman"
5. Lihat status: PENDING (kuning)
```

#### Browser 2 (atau Tab Baru) - ADMIN
```
1. Login sebagai admin/librarian
2. Dashboard → Kelola Peminjaman
3. Lihat borrowing PENDING
4. Klik tombol hijau "Setujui"
5. Lihat status jadi APPROVED (hijau)
6. Cek file QR dibuat: public/qr/borrowing_[ID].png
```

#### Kembali ke Browser 1 - MEMBER
```
1. Refresh halaman riwayat
2. Lihat tombol baru: "📱 Lihat QR Code"
3. Klik → Lihat QR code image
```

---

## 📋 TESTING CHECKLIST (5 MENIT)

### ✅ Approval Test
- [ ] Member submit form → Status: PENDING
- [ ] Admin lihat di dashboard
- [ ] Admin klik approve → Status: APPROVED
- [ ] QR file created di `public/qr/`
- [ ] Member lihat QR button & bisa buka modal

### ✅ Rejection Test
- [ ] Member submit form → Status: PENDING
- [ ] Admin klik tolak → Modal muncul
- [ ] Admin isi alasan → Klik tolak
- [ ] Status jadi REJECTED (merah)
- [ ] Member lihat alasan di history

### ✅ Return Test
- [ ] Approve borrowing
- [ ] Member lihat tombol "Kembalikan"
- [ ] Klik kembalikan → Status: RETURNED (biru)

---

## 🔍 QUICK DEBUG

### QR File Tidak Ada?
```bash
# Cek directory
dir C:\xampp\htdocs\perpus_digit_laravel\public\qr\

# Jika directory tidak ada, buat manual:
mkdir C:\xampp\htdocs\perpus_digit_laravel\public\qr
```

### Modal Tidak Muncul?
```bash
# Clear view cache
php artisan view:clear

# Check browser console (F12 → Console) untuk JS errors
```

### Status Tidak Update?
```bash
# Clear cache & restart
php artisan config:cache
php artisan cache:clear
php artisan serve
```

---

## 📱 URLS PENTING

| Fitur | URL | Role |
|-------|-----|------|
| Form Peminjaman | `/borrowings/create` | Member |
| Riwayat Member | `/borrowings` | Member |
| Admin Dashboard | `/admin/borrowings` | Admin |
| Librarian Dashboard | `/librarian/borrowings` | Librarian |

---

## 📝 FORM FIELDS

### Member Form (`/borrowings/create`)
```
- Pilih Buku (dropdown dengan available_copies)
- Durasi (radio: 7, 14, 21, 30 hari)
- Tanggal Pinjam (readonly - hari ini)
- Tanggal Kembali (auto-calculated)
- Nama Member (readonly - dari session)
- Email (readonly)
- Member ID (readonly)
- Status Keanggotaan (readonly)
- Permintaan Khusus (textarea)
- ✓ Saya setuju syarat & ketentuan
- ✓ Kondisi buku sudah saya periksa
- ✓ Saya bersedia bayar denda keterlambatan
- [Ajukan Peminjaman]
```

---

## 🎨 STATUS COLORS

```
Pending   → Yellow  (#FEF08A) → Menunggu persetujuan
Approved  → Green   (#DCFCE7) → Disetujui, ambil buku
Returned  → Blue    (#DBEAFE) → Sudah dikembalikan
Rejected  → Red     (#FEE2E2) → Ditolak
Overdue   → Red     (#FCA5A5) → Terlambat
```

---

## 💾 DATABASE FIELDS (New)

Dalam tabel `borrowings`:
- `qr_code` - Path ke file QR (nullable)
- `approved_by` - User ID yang approve (FK, nullable)
- `approved_at` - Timestamp approval (nullable)
- `rejection_reason` - Alasan tolak (nullable)

---

## 🔄 STATE FLOW

```
PENDING → APPROVED (+ QR file created)
       ↓
       RETURNED (member kembalikan)
       or
       OVERDUE (automatic jika lewat due_date)

PENDING → REJECTED (+ reason stored)
```

---

## 🛠️ FILES MODIFIED RINGKAS

1. **AdminController.php** - approveBorrowing() & rejectBorrowing()
2. **LibrarianDashboardController.php** - approveBorrowing() & rejectBorrowing()
3. **Borrowing.php** - Model updates
4. **admin/borrowings/index.blade.php** - Rejection modal
5. **pustakawan/borrowings/index.blade.php** - Rejection modal
6. **member/borrowings/index.blade.php** - QR button & modal

---

## ✨ FEATURES

✅ QR Code auto-generate saat approve
✅ Rejection modal dengan textarea
✅ Member lihat QR code di riwayat
✅ Member lihat alasan tolak
✅ Color-coded status badges
✅ Tab filter untuk member history
✅ One-click approve/reject

---

## 📖 FULL DOCUMENTATION

Untuk detail lengkap, baca:
- `BORROWING_SYSTEM_TESTING.md` - Testing guide lengkap
- `BORROWING_SYSTEM_FLOW.md` - Flow diagrams & struktur
- `BORROWING_SYSTEM_SUMMARY.md` - Complete summary

---

**Status**: ✅ READY TO USE
**Time to Deploy**: < 5 minutes
**Time to Test**: < 10 minutes
