# 🎉 SISTEM QR SCANNER - SIAP DIGUNAKAN!

Halo Petugas Perpustakaan! 👋

Sistem QR Scanner untuk peminjaman buku sudah **SIAP DIGUNAKAN**. Ikuti panduan di bawah ini.

---

## 🚀 MULAI SEKARANG

### AKSES HALAMAN SCANNER
Buka link ini di browser:
```
http://localhost/perpus_digit_laravel/staff/scanner
```

### MENU UTAMA
Buka halaman menu untuk melihat semua pilihan:
```
http://localhost/perpus_digit_laravel/staff/scanner-menu
```

---

## 📱 3 LANGKAH PEMINJAMAN

### LANGKAH 1️⃣ - SCAN BUKU
- Buka halaman scanner
- Arahkan scanner ke QR code buku
- QR code format: `BOOK-1`, `BOOK-2`, dst
- Tunggu sistem menampilkan info buku

### LANGKAH 2️⃣ - SCAN PEMINJAM
- Arahkan scanner ke QR code peminjam
- QR code format: `USER-1`, `USER-2`, dst
- Tunggu sistem menampilkan info peminjam
- Sistem **otomatis approve** peminjaman

### LANGKAH 3️⃣ - KONFIRMASI
- Lihat pesan "Peminjaman Berhasil" ✅
- Record sudah tersimpan di database
- Peminjaman selesai!

---

## 🔄 PENGEMBALIAN BUKU

### Cara Return Buku
1. Buka halaman scanner
2. Cari borrowing ID di history
3. Input ID di form "Return Book"
4. Klik tombol "Return"
5. Sistem hitung denda otomatis (jika ada)
6. Selesai!

### Denda Otomatis
- **Tepat waktu** (≤ 14 hari) = Rp 0
- **Terlambat 1 hari** = Rp 5,000
- **Terlambat 5 hari** = Rp 25,000
- **Terlambat 10 hari** = Rp 50,000

---

## 📋 ATURAN PENTING

| Aturan | Nilai |
|--------|-------|
| 📚 Max buku per member | **5 buku** |
| 📅 Periode peminjaman | **14 hari** |
| 🔁 Max renewal | **2x** |
| 💰 Denda per hari | **Rp 5,000** |

---

## ❌ PEMINJAMAN BISA DITOLAK JIKA

1. **Member sudah pinjam 5 buku** → Tunggu member return
2. **Member punya denda belum bayar** → Suruh bayar dulu
3. **Buku sedang dipinjam orang lain** → Tunggu dikembalikan
4. **Member tidak aktif** → Hubungi admin

---

## 📊 LIHAT HISTORY

### Akses History Page
```
http://localhost/perpus_digit_laravel/staff/borrowing-history
```

### Di sini Anda bisa:
- ✅ Lihat semua peminjaman
- ✅ Filter berdasarkan status
- ✅ Cari berdasarkan tanggal
- ✅ Lihat denda yang belum dibayar
- ✅ Approve/Reject peminjaman
- ✅ Proses pengembalian

---

## 🖨️ PRINT QR CODE

### Print Book QR Codes
```
Buka: http://localhost/perpus_digit_laravel/admin/qr-code/print-books
1. Halaman akan menampilkan QR semua buku
2. Klik "Print" di browser
3. Laminate untuk durability
4. Tempel di belakang buku
```

### Print Member Cards
```
Buka: http://localhost/perpus_digit_laravel/admin/qr-code/print-members
1. Halaman akan menampilkan member cards
2. Klik "Print" di browser
3. Print di kertas tebal
4. Laminate
5. Bagikan ke members
```

---

## 🎯 FORMAT QR CODE

### Book QR Format
```
BOOK-1  (untuk buku dengan ID 1)
BOOK-2  (untuk buku dengan ID 2)
BOOK-3  (untuk buku dengan ID 3)
dst...
```

### Member/User QR Format
```
USER-1  (untuk member dengan ID 1)
USER-2  (untuk member dengan ID 2)
USER-3  (untuk member dengan ID 3)
dst...
```

> **PENTING**: Format harus sesuai, jika tidak QR tidak bisa scan!

---

## 🔐 KEAMANAN

- ✅ Hanya Admin & Librarian bisa akses
- ✅ Harus login terlebih dahulu
- ✅ Sistem terproteksi dari unauthorized access
- ✅ Semua transaksi tercatat

---

## 🐛 JIKA ADA MASALAH

| Masalah | Solusi |
|---------|--------|
| **QR tidak scan** | Cek format BOOK-{id} / Print ulang |
| **Member blocked** | Check denda / Cek buku yang masih dipinjam |
| **Buku tidak ditemukan** | Cek ID buku di database |
| **Sistem error** | Refresh browser / Cek koneksi internet |
| **Lain-lain** | Hubungi Admin IT |

---

## 📚 DOKUMENTASI LENGKAP

Jika ingin tahu lebih detail:

1. **QUICK_REFERENCE.md** → Quick tips & reference
2. **QR_SCANNER_QUICKSTART.md** → Get started quickly
3. **PANDUAN_OPERASIONAL_QR_SCANNER.md** → Full operational guide
4. **DOKUMENTASI_GUIDE.md** → Guide ke semua dokumentasi

Semua file ada di folder root project.

---

## ✅ CHECKLIST SEBELUM MULAI

- [ ] Sudah login dengan akun Admin/Librarian
- [ ] QR Scanner device sudah siap
- [ ] Sudah tahu akses URL scanner
- [ ] Sudah baca panduan ini
- [ ] Sudah coba 1x di test environment
- [ ] Sudah print & laminate QR codes
- [ ] Sudah distribute member cards
- [ ] Ready to go! 🚀

---

## 💡 TIPS & TRICK

### Scanning Tips
```
1. Pastikan QR code jelas & tidak rusak
2. Posisikan scanner 10-20 cm dari QR
3. Tunggu beep/visual indication
4. Pastikan input auto-clear sebelum scan berikutnya
```

### Efisiensi
```
1. Scan buku duluan
2. Kemudian scan member
3. Sistem auto-process (cepat!)
4. Tidak perlu input manual
5. Tidak perlu click approve
```

### Error Recovery
```
1. QR tidak terdeteksi? Scan ulang
2. Member blocked? Cek history terlebih dulu
3. Sistem hanging? Refresh halaman
4. Data tidak update? Refresh atau restart browser
```

---

## 📞 KONTAK SUPPORT

Jika ada pertanyaan atau masalah:

- **Email**: [Admin IT Email]
- **Phone**: [Admin IT Phone]
- **WhatsApp**: [Admin IT WhatsApp]
- **Lokasi**: [Admin IT Location]

---

## 📝 CATATAN PENTING

⚠️ **PENTING**: 
- Jangan modifikasi database secara manual
- Jangan delete borrowing records
- Jangan edit QR code manually
- Hubungi admin jika ada data salah
- Backup dilakukan setiap hari

---

## 🎓 TRAINING

Jika belum terbiasa:
1. Minta demo dari Admin IT
2. Praktik dengan data test dulu
3. Tanya jika ada yang tidak jelas
4. Jangan takut membuat kesalahan (data test)
5. Semua ada dokumentasi untuk referensi

---

## 🎉 SELAMAT!

Anda sekarang siap menggunakan **Sistem QR Scanner**! 

Jika ada pertanyaan, jangan ragu untuk menghubungi Admin IT.

**Semoga lancar dan produktif!** 🚀

---

## 📱 QUICK LINKS

| Halaman | URL |
|--------|-----|
| Scanner | /staff/scanner |
| History | /staff/borrowing-history |
| Menu | /staff/scanner-menu |
| Print Books | /admin/qr-code/print-books |
| Print Members | /admin/qr-code/print-members |

---

**Last Updated**: 19 Januari 2026
**Version**: 1.0
**Status**: Ready to Use ✅
