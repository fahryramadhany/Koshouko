# Sistem Peminjaman Buku - Panduan Pengujian

## 📋 Ringkasan Sistem

Sistem peminjaman buku telah diimplementasikan dengan alur lengkap:

1. **Member** mengisi formulir peminjaman
2. Formulir dikirim dengan status **pending**
3. **Admin/Librarian** melihat daftar pending di dashboard
4. **Admin/Librarian** dapat **approve** (generate QR) atau **reject** (dengan alasan)
5. Jika approved → **QR code** dibuat otomatis
6. **Member** melihat status & **QR code** di riwayat peminjaman

---

## ✅ Komponen yang Telah Diimplementasikan

### 1. Database Migrations
- ✅ Migration `2025_01_28_000001_add_qr_approved_to_borrowings_table.php` - **SUDAH DIJALANKAN**
- Menambah fields: `qr_code`, `approved_by`, `approved_at`, `rejection_reason`

### 2. Models
- ✅ `app/Models/Borrowing.php` - Updated dengan:
  - Fillable: qr_code, approved_by, approved_at, rejection_reason
  - Relationship: `approver()` - belongsTo User
  - Casts: approved_at as datetime

### 3. Controllers
- ✅ `AdminController::approveBorrowing()` - Generate QR code + set approved_by, approved_at
- ✅ `AdminController::rejectBorrowing()` - Accept rejection_reason dari request
- ✅ `LibrarianDashboardController::approveBorrowing()` - Sama seperti Admin
- ✅ `LibrarianDashboardController::rejectBorrowing()` - Sama seperti Admin
- ✅ `BorrowingController::store()` - Create borrowing dengan status pending
- ✅ Dependencies installed: `bacon/bacon-qr-code` v3.0.3

### 4. Views
- ✅ `resources/views/admin/borrowings/index.blade.php` - Updated dengan:
  - Rejection modal dengan textarea untuk alasan
  - JavaScript: showRejectModal(), closeRejectModal()
  
- ✅ `resources/views/pustakawan/borrowings/index.blade.php` - Updated dengan:
  - Rejection modal dengan textarea untuk alasan
  - JavaScript: showRejectModal(), closeRejectModal()

- ✅ `resources/views/member/borrowings/index.blade.php` - Updated dengan:
  - "Lihat QR Code" button jika status approved
  - QR Modal untuk menampilkan QR code
  - Tampilan rejection reason jika ditolak
  - JavaScript: showQRModal(), closeQRModal()

- ✅ `resources/views/member/borrowings/create.blade.php` - Sudah ada dan lengkap

### 5. Routes
- ✅ POST /admin/borrowings/{borrowing}/approve
- ✅ POST /admin/borrowings/{borrowing}/reject
- ✅ POST /librarian/borrowings/{borrowing}/approve
- ✅ POST /librarian/borrowings/{borrowing}/reject
- ✅ GET /borrowings/create
- ✅ POST /borrowings
- ✅ GET /borrowings (member history)

---

## 🧪 Prosedur Pengujian End-to-End

### Persiapan
1. Pastikan aplikasi berjalan: `php artisan serve`
2. Login sebagai **member** dan **admin/librarian** (gunakan browser terpisah atau incognito)

### Scenario 1: Approve Peminjaman (Berhasil)

#### Step 1: Member Mengajukan Peminjaman
1. Login sebagai member
2. Klik menu **"Peminjaman"** → **"Ajukan Peminjaman"**
3. Isi formulir:
   - **Buku**: Pilih buku yang ada
   - **Durasi**: Pilih 7, 14, 21, atau 30 hari
   - **Permintaan Khusus**: (opsional)
   - **Checklist**: Tandai 3 checkbox syarat & ketentuan
4. Klik **"Ajukan Peminjaman"**
5. **Expected Result**: 
   - ✅ Redirect ke halaman riwayat dengan pesan sukses
   - ✅ Status peminjaman: **Pending** (kuning)

#### Step 2: Verifikasi di Database
```bash
# Terminal
cd c:\xampp\htdocs\perpus_digit_laravel
php artisan tinker

# Di Tinker:
$borrowing = App\Models\Borrowing::latest()->first();
dd($borrowing); // Verifikasi: status='pending', qr_code=null, approved_by=null
```

#### Step 3: Admin/Librarian Approve
1. Login sebagai **admin** atau **librarian** 
2. Navigasi ke:
   - Admin: Dashboard → **Kelola Peminjaman**
   - Librarian: Dashboard → **Kelola Peminjaman**
3. Lihat tabel peminjaman dengan **Status: Pending**
4. Klik tombol hijau **"Setujui"**
5. **Expected Result**:
   - ✅ Pesan sukses: "Peminjaman berhasil disetujui. QR code telah dibuat."
   - ✅ Status berubah menjadi **Approved** (hijau)
   - ✅ Row tidak lagi menampilkan tombol Setujui/Tolak

#### Step 4: Verifikasi QR Code Dibuat
```bash
# Terminal
php artisan tinker

# Di Tinker:
$borrowing = App\Models\Borrowing::latest()->first();
dd($borrowing->qr_code); // Output: "qr/borrowing_[ID].png"
dd($borrowing->status); // Output: "approved"
dd($borrowing->approved_by); // Output: [User ID]
dd($borrowing->approved_at); // Output: Carbon timestamp
```

Verifikasi file:
```bash
# Terminal
ls public/qr/  # Harus ada file: borrowing_[ID].png
```

#### Step 5: Member Melihat QR Code
1. Login sebagai **member** (user yang mengajukan)
2. Buka **Riwayat Peminjaman**
3. Cari peminjaman yang baru diapprove (Status: **Approved** hijau)
4. Klik tombol biru **"📱 Lihat QR Code"**
5. **Expected Result**:
   - ✅ Modal muncul dengan gambar QR code
   - ✅ Text: "Tunjukkan QR Code ini kepada petugas saat mengambil buku"
   - ✅ Tombol **"Tutup"** menutup modal

---

### Scenario 2: Reject Peminjaman (Dengan Alasan)

#### Step 1: Member Ajukan Peminjaman (Ulang)
- Ikuti Scenario 1 Step 1

#### Step 2: Admin/Librarian Reject
1. Login sebagai **admin** atau **librarian**
2. Buka **Kelola Peminjaman**
3. Cari peminjaman pending baru
4. Klik tombol merah **"Tolak"**
5. **Expected Result**:
   - ✅ Modal muncul dengan judul "Tolak Peminjaman"
   - ✅ Text field: "Alasan Penolakan *" (wajib diisi)
   - ✅ 2 tombol: "Batal" dan "Tolak"

#### Step 3: Isi Alasan & Submit
1. Di modal rejection:
2. Isi **Alasan Penolakan** misalnya: "Buku sedang dalam perbaikan"
3. Klik **"Tolak"**
4. **Expected Result**:
   - ✅ Modal tutup
   - ✅ Pesan sukses: "Peminjaman berhasil ditolak."
   - ✅ Status berubah menjadi **Rejected** (merah)

#### Step 4: Verifikasi di Database
```bash
php artisan tinker

$borrowing = App\Models\Borrowing::where('status', 'rejected')->latest()->first();
dd($borrowing->rejection_reason); // Output: "Buku sedang dalam perbaikan"
dd($borrowing->status); // Output: "rejected"
```

#### Step 5: Member Melihat Rejection Reason
1. Login sebagai **member**
2. Buka **Riwayat Peminjaman**
3. Filter tab **"Sudah Dikembalikan"** (rejection ada di sini)
4. **Expected Result**:
   - ✅ Card peminjaman dengan Status: **Rejected** (merah)
   - ✅ Tampil text: "Alasan: Buku sedang dalam perbaikan"

---

### Scenario 3: Member Kembalikan Buku (Setelah Approve)

#### Step 1: Member Approve & Dapatkan QR
- Ikuti Scenario 1 Step 1-4

#### Step 2: Member Kembalikan Buku
1. Login sebagai **member**
2. Buka **Riwayat Peminjaman**
3. Cari peminjaman approved
4. Klik tombol **"Kembalikan"**
5. **Expected Result**:
   - ✅ Konfirmasi dialog (jika ada)
   - ✅ Pesan sukses: "Buku berhasil dikembalikan."
   - ✅ Status berubah menjadi **Returned** (biru)
   - ✅ Tombol Kembalikan & Perpanjang hilang

#### Step 3: Verifikasi di Database
```bash
php artisan tinker

$borrowing = App\Models\Borrowing::where('status', 'returned')->latest()->first();
dd($borrowing->returned_at); // Output: Carbon timestamp (hari ini)
```

---

### Scenario 4: Member Perpanjang Peminjaman

#### Step 1: Approve & Buka Halaman History
- Ikuti Scenario 1 Step 1-4

#### Step 2: Perpanjang Peminjaman
1. Login sebagai **member**
2. Buka **Riwayat Peminjaman**
3. Cari peminjaman approved (belum dikembalikan)
4. Klik **"Perpanjang"**
5. **Expected Result**:
   - ✅ Pesan sukses: "Peminjaman berhasil diperpanjang selama 7 hari."
   - ✅ Tanggal kembali bertambah 7 hari
   - ✅ Counter perpanjang: "Sudah diperpanjang 1/2 kali"

#### Step 3: Perpanjang Kedua Kali
- Ulangi Step 2
- **Expected Result**: Pesan sukses, counter: "Sudah diperpanjang 2/2 kali"

#### Step 4: Coba Perpanjang Ketiga Kali
- Klik **"Perpanjang"** lagi
- **Expected Result**: 
  - ✅ Button berubah menjadi disabled
  - ✅ Text: "Tidak Bisa Perpanjang"

---

## 🐛 Troubleshooting

### QR Code Tidak Muncul
1. **Check public directory permissions**:
   ```bash
   # Windows
   icacls "C:\xampp\htdocs\perpus_digit_laravel\public\qr" /grant Users:F
   ```

2. **Check file generated**:
   ```bash
   ls C:\xampp\htdocs\perpus_digit_laravel\public\qr\
   ```

3. **Check logs**:
   ```bash
   tail storage/logs/laravel.log
   ```

### Modal Tidak Muncul
1. **Clear view cache**:
   ```bash
   php artisan view:clear
   ```

2. **Check browser console** (F12 → Console) untuk JavaScript errors

3. **Verifikasi JavaScript functions** di halaman

### Approval Button Tidak Muncul
1. Verifikasi status = 'pending':
   ```bash
   php artisan tinker
   App\Models\Borrowing::where('status', 'pending')->count(); # Harus > 0
   ```

2. Check view: `resources/views/admin/borrowings/index.blade.php` line dengan `@if($borrowing->status === 'pending')`

### QR Modal Blank
1. Clear browser cache (Ctrl+Shift+Delete)
2. Check file exists: `public/qr/borrowing_[ID].png`
3. Verify asset path is correct: `{{ asset($borrowing->qr_code) }}`

---

## 📊 Key Database Queries

### Melihat Semua Borrowing Pending
```bash
php artisan tinker
App\Models\Borrowing::where('status', 'pending')->with('user', 'book')->get();
```

### Melihat Semua Approved (dengan QR)
```bash
App\Models\Borrowing::where('status', 'approved')->with('user', 'book')->get();
```

### Melihat Rejected (dengan alasan)
```bash
App\Models\Borrowing::where('status', 'rejected')->select('id', 'rejection_reason', 'rejected_at')->get();
```

### Melihat Info Approver
```bash
$b = App\Models\Borrowing::find(1);
$b->approver; # User yang approve
$b->approved_at; # Kapan diapprove
```

---

## ✨ File yang Telah Dimodifikasi

1. ✅ `app/Http/Controllers/AdminController.php` - approveBorrowing(), rejectBorrowing()
2. ✅ `app/Http/Controllers/Librarian/LibrarianDashboardController.php` - approveBorrowing(), rejectBorrowing()
3. ✅ `app/Models/Borrowing.php` - fillable, casts, approver() relationship
4. ✅ `resources/views/admin/borrowings/index.blade.php` - Rejection modal
5. ✅ `resources/views/pustakawan/borrowings/index.blade.php` - Rejection modal
6. ✅ `resources/views/member/borrowings/index.blade.php` - QR display & modal
7. ✅ `database/migrations/2025_01_28_000001_add_qr_approved_to_borrowings_table.php` - NEW
8. ✅ `composer.json` - Added bacon/bacon-qr-code v3.0.3

---

## 🎯 Hasil Yang Diharapkan

### Admin/Librarian Melihat:
- ✅ Dashboard Peminjaman dengan list semua borrowing
- ✅ Filter berdasarkan status
- ✅ Tombol Setujui & Tolak untuk status pending
- ✅ Rejection modal dengan textarea untuk alasan

### Member Melihat:
- ✅ Formulir peminjaman lengkap dengan validasi
- ✅ Riwayat peminjaman dengan status badge
- ✅ QR Code button untuk peminjaman approved
- ✅ QR Modal menampilkan gambar QR code
- ✅ Rejection reason tampil jika ditolak
- ✅ Tombol Kembalikan & Perpanjang (sesuai kondisi)

### Database:
- ✅ Field baru: qr_code, approved_by, approved_at, rejection_reason
- ✅ QR file: `public/qr/borrowing_[ID].png`
- ✅ Status tracking: pending → approved/rejected → taken/returned/overdue

---

## 🚀 Next Steps (Opsional)

1. **Email Notifications**:
   - Kirim email ke member saat diapprove
   - Kirim email ke member saat ditolak dengan alasan

2. **SMS Notifications**:
   - Integrasi Twilio atau sejenisnya
   - Notifikasi SMS saat approval

3. **QR Code Batch Print**:
   - Fitur print multiple QR codes untuk admin

4. **Statistics Dashboard**:
   - Grafik approval rate
   - Grafik rejection reasons

5. **Fine System**:
   - Sudah ada di database (fine relationship)
   - Bisa ditampilkan di member history

---

**Status**: ✅ IMPLEMENTATION COMPLETE - Ready for Testing
**Last Updated**: 2025-01-28
