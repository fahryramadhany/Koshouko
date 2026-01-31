# 📱 Dokumentasi Sistem QR Scanner Peminjaman Buku

## 📋 Daftar Isi
1. [Pengenalan](#pengenalan)
2. [Fitur Utama](#fitur-utama)
3. [Struktur File](#struktur-file)
4. [Cara Penggunaan](#cara-penggunaan)
5. [Format QR Code](#format-qr-code)
6. [Aturan Peminjaman](#aturan-peminjaman)
7. [Troubleshooting](#troubleshooting)

---

## 🎯 Pengenalan

Sistem QR Scanner adalah fitur baru dalam Perpustakaan Digital yang memungkinkan petugas perpustakaan untuk dengan cepat dan efisien memproses peminjaman dan pengembalian buku menggunakan teknologi QR Code.

### Keuntungan Sistem QR Scanner:
- ✅ **Cepat**: Proses peminjaman hanya perlu scanning 2 QR code
- ✅ **Akurat**: Mengurangi kesalahan input manual
- ✅ **Otomatis**: Approval langsung tanpa perlu admin review
- ✅ **Real-time**: Tracking peminjaman secara langsung
- ✅ **Denda Otomatis**: Hitung denda keterlambatan secara otomatis

---

## ✨ Fitur Utama

### 1. **QR Scanner Dashboard** 📱
- Input field untuk scanning QR code
- Deteksi otomatis tipe code (Buku/Member)
- Tampilan real-time data buku dan member
- History peminjaman terbaru

**Akses**: `/staff/scanner`

### 2. **Riwayat Peminjaman** 📋
- Lihat semua riwayat peminjaman
- Filter berdasarkan status (pending, approved, returned)
- Filter berdasarkan rentang tanggal
- Deteksi otomatis buku yang terlambat
- Tombol approval/rejection untuk pending requests

**Akses**: `/staff/borrowing-history`

### 3. **Cetak QR Code Buku** 📖
- Generate QR code untuk semua buku
- Search/filter buku tertentu
- Layout siap cetak (A4, A3, dll)
- Menampilkan: Judul, Penulis, ISBN, QR Code

**Akses**: `/admin/qr-code/print-books`

### 4. **Cetak Kartu Member** 🎫
- Generate kartu member dengan QR code
- Desain profesional dan menarik
- Search member tertentu
- Layout siap cetak

**Akses**: `/admin/qr-code/print-members`

---

## 📁 Struktur File

```
app/
├── Http/
│   └── Controllers/
│       ├── QRScanController.php          (Controller utama untuk scanner)
│       └── Admin/
│           └── QRGeneratorController.php (Controller untuk generate QR)
│
resources/
└── views/
    ├── staff/
    │   ├── qr-scanner.blade.php          (Halaman scanner utama)
    │   ├── qr-menu.blade.php             (Menu/dashboard staff)
    │   └── borrowing-history.blade.php   (Halaman history)
    │
    └── admin/
        ├── print-qr-books.blade.php      (Cetak QR code buku)
        └── print-qr-members.blade.php    (Cetak kartu member)

routes/
└── web.php                                (Routes sudah ditambahkan)
```

---

## 📖 Cara Penggunaan

### A. Proses Peminjaman Buku

**Step 1: Buka Scanner**
```
Pergi ke: /staff/scanner
```

**Step 2: Scan QR Code Buku**
```
Input: BOOK-1 (atau scan langsung)
Hasil: Sistem menampilkan detail buku
```

**Step 3: Pilih Buku**
```
Klik: "Pilih Buku Ini"
Instruksi: Scan kartu member berikutnya
```

**Step 4: Scan Kartu Member**
```
Input: USER-5 (atau scan langsung)
Hasil: Sistem cek data member
```

**Step 5: Konfirmasi Member**
```
Klik: "Pilih Member Ini"
Hasil: Peminjaman berhasil dicatat
```

**Step 6: Proses Selanjutnya**
```
Klik: "Proses Peminjaman Berikutnya"
Ulangi dari Step 2
```

---

### B. Proses Pengembalian Buku

**Option 1: Via Scanner**
```
1. Buka /staff/scanner
2. Scan QR code buku yang dikembalikan
3. Sistem tunjukkan tombol "Kembalikan Buku"
4. Klik tombol tersebut
5. Sistem hitung denda (jika terlambat)
6. Pengembalian tercatat
```

**Option 2: Via Riwayat**
```
1. Buka /staff/borrowing-history
2. Cari peminjaman yang masih aktif
3. Klik "Terima Kembali"
4. Konfirmasi
5. Pengembalian tercatat
```

---

### C. Cetak QR Code

**Cetak QR Code Buku:**
```
1. Pergi ke: /admin/qr-code/print-books
2. (Optional) Search buku tertentu
3. Klik: Cetak (atau Ctrl+P)
4. Pilih printer
5. Klik Print
```

**Cetak Kartu Member:**
```
1. Pergi ke: /admin/qr-code/print-members
2. (Optional) Search member tertentu
3. Klik: Cetak Kartu (atau Ctrl+P)
4. Pilih printer (A4 landscape recommended)
5. Klik Print
```

---

## 🔑 Format QR Code

### Format Umum
```
Tipe-ID
```

### QR Code Buku
```
Format: BOOK-{ID_BUKU}
Contoh: BOOK-1, BOOK-42, BOOK-150
```

### QR Code Member
```
Format: USER-{ID_MEMBER}
Contoh: USER-5, USER-123, USER-999
```

### Contoh Valid QR Codes
```
✅ BOOK-1
✅ USER-5
✅ BOOK-42
✅ USER-100

❌ book-1        (harus uppercase)
❌ BUKU-1        (harus BOOK)
❌ 1             (harus ada tipe)
❌ BOOK-ABC      (ID harus angka)
```

---

## 📏 Aturan & Ketentuan Peminjaman

### Batas Peminjaman per Member
- **Maksimal**: 5 buku
- **Durasi**: 14 hari
- **Perpanjangan**: 2 kali (masing-masing 14 hari)
- **Denda Keterlambatan**: Rp 5.000 per hari

### Kondisi untuk Bisa Meminjam
✅ Belum mencapai 5 buku aktif
✅ Tidak ada denda yang belum dibayar
✅ Belum kadaluarsa masa berlaku member (jika ada)

### Kondisi Tidak Bisa Meminjam
❌ Sudah 5 buku
❌ Ada denda belum bayar
❌ Member sudah tidak aktif

---

## 🗄️ Database Fields

### Tabel Borrowings
```php
- id (Primary Key)
- user_id (Foreign Key)
- book_id (Foreign Key)
- borrowed_at (Timestamp)
- due_date (Timestamp)
- returned_at (Timestamp, nullable)
- status (enum: pending, approved, returned, rejected)
- renewal_count (integer, default: 0)
- notes (text, nullable)
- created_at
- updated_at
- deleted_at (soft delete)
```

### Status Borrowing
```
pending   = Menunggu persetujuan admin
approved  = Sudah disetujui, sedang dipinjam
returned  = Sudah dikembalikan
rejected  = Ditolak oleh admin
```

---

## 🐛 Troubleshooting

### Masalah: QR Code tidak dibaca

**Solusi:**
- Pastikan format QR code benar (BOOK-1, USER-5)
- Cek koneksi internet (untuk generate QR online)
- Scan dengan jarak ±20-30cm dari input field
- Pastikan QR code tidak rusak atau terlalu kecil

---

### Masalah: Member tidak bisa meminjam

**Penyebab & Solusi:**
```
❌ Sudah 5 buku
→ Member harus mengembalikan minimal 1 buku

❌ Ada denda belum bayar
→ Member harus bayar denda terlebih dahulu

❌ Format member tidak ditemukan
→ Cek apakah member sudah terdaftar
→ Pastikan ID member benar
```

---

### Masalah: Buku sedang dipinjam

**Situasi**: Saat scanning buku, sistem bilang "Buku ini sedang dipinjam"

**Solusi:**
```
1. Kembalikan buku yang sedang dipinjam terlebih dahulu
2. Scan buku yang dikembalikan
3. Sistem akan update status
4. Baru bisa dipinjam lagi oleh member lain
```

---

### Masalah: Sistem lambat saat scanning

**Penyebab:**
- Koneksi internet lambat (untuk QR API)
- Database besar dengan banyak record

**Solusi:**
- Pastikan koneksi internet stabil
- Optimasi database
- Clear cache aplikasi (jika diperlukan)

---

## 🔐 Akses & Permission

### Yang Bisa Akses QR Scanner:
- ✅ Admin
- ✅ Pustakawan (Role = 2)
- ❌ Member (Role = 3)

### Cara Menambah Admin/Pustakawan:
```sql
-- User akan otomatis sebagai Member (role_id = 3)
-- Admin bisa ubah role melalui panel admin
-- Role 1 = Admin
-- Role 2 = Pustakawan
-- Role 3 = Member
```

---

## 📊 API Endpoints (Internal)

### Scan QR Code
```
POST /staff/scanner/scan
Body: {
  "qr_code": "BOOK-1" atau "USER-5"
}
Response: {
  "success": true/false,
  "message": "...",
  "data": {...}
}
```

### Create Borrowing
```
POST /staff/scanner/create-borrowing
Body: {
  "user_id": 5,
  "book_id": 1
}
Response: {
  "success": true/false,
  "message": "...",
  "data": {...}
}
```

### Return Book
```
POST /staff/scanner/return-book
Body: {
  "borrowing_id": 10
}
Response: {
  "success": true/false,
  "message": "...",
  "data": {...}
}
```

---

## 📱 Responsive Design

Sistem QR Scanner sudah dioptimasi untuk:
- ✅ Desktop (1920x1080 dan lebih besar)
- ✅ Tablet (768px - 1024px)
- ✅ Mobile (320px - 768px)

**Tips untuk Mobile:**
- Gunakan landscape mode untuk scanner
- Gunakan barcode scanner app (untuk hasil lebih baik)
- Pastikan pencahayaan cukup saat scanning

---

## 🎓 Training/Demo

### Untuk Petugas Baru:
1. Tunjukkan halaman `/staff/scanner-menu`
2. Jelaskan fitur-fitur utama
3. Demo proses peminjaman (2-3 kali)
4. Demo proses pengembalian
5. Demo cetak QR code
6. Biarkan petugas mencoba sendiri

### Untuk Anggaran:
- Print QR code dari `/admin/qr-code/print-books`
- Print kartu member dari `/admin/qr-code/print-members`
- Laminating untuk durabilitas

---

## 📞 Support & Update

Jika ada error atau fitur yang ingin ditambahkan:
1. Dokumentasikan error yang terjadi
2. Catat langkah-langkah reproduksi
3. Screenshot error message
4. Hubungi developer

---

**Versi**: 1.0
**Last Updated**: 19 Januari 2026
**Author**: Copilot Assistant
