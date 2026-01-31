# 🚀 Quick Start - QR Scanner Perpustakaan Digital

## Akses Cepat

### Untuk Petugas/Pustakawan
```
Menu Dashboard:        /staff/scanner-menu
Scanner Utama:         /staff/scanner
Riwayat Peminjaman:    /staff/borrowing-history
```

### Untuk Admin
```
Cetak QR Buku:        /admin/qr-code/print-books
Cetak Kartu Member:   /admin/qr-code/print-members
```

---

## 3 Langkah Peminjaman

### 1️⃣ Scan Buku
```
Ketik atau Scan: BOOK-1
(Ganti 1 dengan ID buku)
```

### 2️⃣ Pilih Buku
```
Klik tombol "Pilih Buku Ini"
Sistem: Tunggu scanning member
```

### 3️⃣ Scan Member & Selesai
```
Ketik atau Scan: USER-5
(Ganti 5 dengan ID member)
Klik: Pilih Member
Hasil: Peminjaman tercatat ✅
```

---

## Format QR Code

```
Buku:   BOOK-{ID}     (Contoh: BOOK-1, BOOK-42)
Member: USER-{ID}     (Contoh: USER-5, USER-123)
```

---

## Pengembalian Buku

### Via Scanner
1. Buka `/staff/scanner`
2. Scan QR buku yang dikembalikan
3. Klik "Kembalikan Buku"
4. Selesai ✅

### Via History
1. Buka `/staff/borrowing-history`
2. Cari peminjaman aktif
3. Klik "Terima Kembali"
4. Selesai ✅

---

## Print QR Code

### Cetak QR Buku
```
1. Pergi ke: /admin/qr-code/print-books
2. Ctrl+P untuk print
3. Pilih printer
4. Print ✅
```

### Cetak Kartu Member
```
1. Pergi ke: /admin/qr-code/print-members
2. Ctrl+P untuk print
3. Pilih printer (A4 landscape)
4. Print ✅
```

---

## Aturan Peminjaman

| Item | Nilai |
|------|-------|
| Max Buku | 5 |
| Durasi | 14 hari |
| Perpanjangan | 2x (masing-masing 14 hari) |
| Denda/Hari | Rp 5.000 |

---

## Troubleshooting

### Buku tidak ditemukan
→ Cek ID buku benar
→ Pastikan buku sudah diinput

### Member tidak ditemukan
→ Cek ID member benar
→ Pastikan member sudah terdaftar

### Member tidak bisa pinjam
→ Sudah 5 buku? (Kembalikan dulu 1)
→ Punya denda? (Bayar dulu)

---

## File Dokumentasi Lengkap

- **QR_SCANNER_DOCUMENTATION.md** - Dokumentasi detail
- **IMPLEMENTATION_QR_SCANNER.md** - Technical documentation

---

**Need Help?** Lihat dokumentasi lengkap atau hubungi admin.
