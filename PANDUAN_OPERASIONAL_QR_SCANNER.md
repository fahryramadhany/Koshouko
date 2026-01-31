# 📚 PANDUAN OPERASIONAL QR SCANNER - UNTUK PETUGAS PERPUSTAKAAN

## 🎯 Objektif
Panduan ini menjelaskan cara menggunakan sistem QR Scanner dalam operasional harian perpustakaan.

---

## 👥 Pengguna Sistem

### Yang Bisa Menggunakan:
- ✅ Admin Perpustakaan
- ✅ Pustakawan/Staff Perpustakaan
- ❌ Member (Tidak bisa akses)

---

## 📋 Persiapan Awal

### Sebelum Mulai Operasional:

1. **Print QR Code untuk Semua Buku**
   ```
   Pergi ke: /admin/qr-code/print-books
   - Cetak semua QR code buku
   - Laminating untuk durabilitas
   - Tempel di cover belakang buku
   ```

2. **Print Kartu Member**
   ```
   Pergi ke: /admin/qr-code/print-members
   - Cetak kartu untuk semua member terdaftar
   - Plastik laminating
   - Cetak ID Member di kartu
   ```

3. **Siapkan Hardware**
   ```
   - Scanner Barcode/QR (opsional, bisa manual)
   - Laptop/PC untuk operator
   - Printer untuk QR code
   - Kertas A4 atau A3
   ```

4. **Training Staff**
   ```
   - Tunjukkan interface scanner
   - Demo peminjaman 3-5x
   - Demo pengembalian
   - Demo cetak QR code
   - Biarkan staff practice
   ```

---

## 🔄 OPERASIONAL HARIAN

### SESI 1: PAGI (Peminjaman)

#### Waktu Sibuk (Banyak Member Datang)
```
WORKFLOW CEPAT:

[Member datang dengan buku & kartu]

1. PETUGAS:
   → Cek apakah member sudah 5 buku
   → Cek apakah member punya denda
   
2. JIKA OK:
   → Scan QR code buku
   → Scan kartu member
   → Sistem auto-approve ✅
   → Member bisa pergi

3. WAKTU: ±30 detik per member
```

#### Langkah Detail Peminjaman:
```
HALAMAN: /staff/scanner

1. BUKA SCANNER
   - URL: http://perpus.local/staff/scanner
   - Form terlihat dengan title "Input QR Code / Barcode"

2. SCAN BUKU PERTAMA
   - Arahkan scanner ke QR code buku
   - atau
   - Ketik manual: BOOK-1
   - Tekan ENTER
   - Tunggu hasil muncul (≤1 detik)

3. HASIL TAMPIL:
   ┌─ Info Buku ─┐
   │ Judul Buku  │
   │ Pengarang   │
   │ ISBN        │
   │             │
   │ [Pilih Buku Ini]
   │ [Batal]
   └─────────────┘

4. KLIK: "Pilih Buku Ini"
   - Sistem: "Silakan scan kartu member"
   - Input field: Siap untuk scan member

5. SCAN MEMBER
   - Arahkan scanner ke QR di kartu
   - atau
   - Ketik: USER-5
   - Tekan ENTER
   - Tunggu hasil (≤1 detik)

6. HASIL TAMPIL:
   ┌─ Info Member ─────────┐
   │ Nama: [Member Name]    │
   │ Email: [Email]         │
   │ Denda: [Rp X.XXX]      │
   │ Aktif: [Buku Count]    │
   │                        │
   │ [✓ Pilih Member Ini]   │
   │ [✕ Batal]             │
   └────────────────────────┘

7. KLIK: "Pilih Member Ini"
   - Sistem memproses...
   - ✅ SUKSES! Muncul notif:
   ┌─────────────────────────┐
   │ ✓ BERHASIL!             │
   │ Member: [Nama]          │
   │ Buku: [Judul]           │
   │ Tgl Pinjam: [Tanggal]   │
   │ Batas Kembali: [Tanggal]│
   │                         │
   │ [Proses Berikutnya]     │
   └─────────────────────────┘

8. KLIK: "Proses Berikutnya"
   - Input field di-clear
   - Fokus kembali ke input
   - Siap untuk member selanjutnya
   - ULANGI dari Step 2

SELESAI ✅
```

---

### SESI 2: SIANG (Pengembalian & Peminjaman)

#### Proses Pengembalian Buku

```
HALAMAN: /staff/scanner atau /staff/borrowing-history

CARA 1: Via Scanner (Cepat)
───────────────────────────
1. Buka /staff/scanner
2. Member: "Saya mau kembalikan buku"
3. Petugas: Scan QR code buku
4. Input: BOOK-1 → ENTER
5. Hasil:
   ┌─ Info Buku ─────────────┐
   │ Buku sedang dipinjam     │
   │ Peminjam: [Nama]         │
   │ Tgl Pinjam: [Tanggal]    │
   │ Batas: [Tanggal]         │
   │                          │
   │ [Kembalikan Buku Ini]    │
   │ [Batal]                  │
   └──────────────────────────┘

6. Klik: "Kembalikan Buku Ini"
7. Sistem proses...
8. Hasil:
   ┌─ HASIL PENGEMBALIAN ──────┐
   │ ✓ Buku dikembalikan       │
   │ Member: [Nama]            │
   │ Buku: [Judul]             │
   │                           │
   │ Tepat waktu ✅            │
   │ (Atau: Denda Rp X.XXX)    │
   │                           │
   │ [Proses Berikutnya]       │
   └────────────────────────────┘

9. INFO PENTING:
   - Jika tepat waktu: "Tepat waktu ✅"
   - Jika terlambat: "Denda Rp 15.000 (3 hari)"
     * Denda auto = Days × Rp 5.000
     * Member harus bayar ke kasir
     * Kasir update status pembayaran

CARA 2: Via History (Akurat)
────────────────────────────
1. Buka /staff/borrowing-history
2. Cari peminjaman member yang masih aktif
3. Status: "APPROVED" (sedang dipinjam)
4. Klik tombol: "Terima Kembali"
5. Dialog: "Konfirmasi pengembalian buku?"
6. Klik: "OK"
7. Sistem update:
   - Status: APPROVED → RETURNED
   - returned_at: sekarang
   - Fine: auto create (jika terlambat)

SELESAI ✅
```

---

### SESI 3: SORE (Pending Review - Optional)

```
HALAMAN: /staff/borrowing-history
Status: "Menunggu Persetujuan" (PENDING)

JIKA ADA YANG PENDING:
- Biasanya dari member yang request via web
- Petugas bisa: SETUJUI atau TOLAK
- Klik tombol: "Setujui" atau "Tolak"
- Sistem akan update status

CATATAN:
- Via Scanner: AUTO APPROVED (langsung OK)
- Via Web: PENDING (tunggu approval)
```

---

## ⚠️ SITUASI KHUSUS & SOLUSI

### Situasi 1: Member Tiba-tiba Punya Denda
```
KONDISI:
- Member: "Saya mau pinjam buku"
- Sistem: "Anda punya denda Rp 10.000"
- Member: "Kapan harus bayar?"

SOLUSI:
1. Arahkan member ke kasir
2. Member bayar denda
3. Kasir input pembayaran di sistem
4. Baru member bisa pinjam lagi

CATATAN:
- Member tidak bisa pinjam selama ada denda
- System akan otomatis block peminjaman
```

### Situasi 2: Buku Sedang Dipinjam Orang Lain
```
KONDISI:
- Petugas: Scan BOOK-1
- Sistem: "Buku ini sedang dipinjam oleh [Nama]"
- Member: "Saya juga mau pinjam buku itu"

SOLUSI:
1. Beri tahu member: "Maaf, buku masih dipinjam"
2. Tawarkan: "Bisa dipesan? Atau pilih buku lain?"
3. Jika member mau reserve: Catat di sistem
4. Saat buku dikembalikan, hubungi member

CATATAN:
- Fitur reserve bisa ditambah di masa depan
```

### Situasi 3: Member Sudah 5 Buku
```
KONDISI:
- Member: "Saya mau pinjam buku"
- Sistem: "Anda sudah 5 buku aktif"

SOLUSI:
1. Beri tahu member: "Harap kembalikan 1 buku dulu"
2. Member kembali ke lemari dan kembalikan buku
3. Proses pengembalian (sesuai Situasi 1)
4. Setelah kembali, member bisa pinjam lagi

CATATAN:
- Limit 5 buku tidak bisa dihindari
- System auto-check saat scan
```

### Situasi 4: QR Code Tidak Terbaca
```
KONDISI:
- Scanner: tidak membaca
- atau
- Manual ketik: salah format

SOLUSI:
1. Coba scan ulang (lebih dekat/jelas)
2. Jika masih error: Ketik manual
   Contoh: BOOK-1, USER-5
3. Periksa format:
   ✅ BOOK-1
   ❌ book-1 (harus besar)
   ❌ B-1 (harus BOOK)
4. Jika masih error: Cek di admin panel
   - Apakah buku/member terdaftar?
   - Apakah ID-nya benar?
```

### Situasi 5: Buku Hilang/Rusak
```
KONDISI:
- Member: "Buku hilang/rusak"
- Sistem: Masih status "APPROVED"

SOLUSI:
1. Catat di notes/memo
2. Admin buat laporan kehilangan
3. Proses klaim (jika ada asuransi)
4. Hapus buku dari sistem (soft delete)
5. Member ganti buku atau bayar

CATATAN:
- Tergantung kebijakan perpustakaan
- Bisa tambah field "lost/damaged" di future
```

---

## 📊 LAPORAN HARIAN

### Akhir Shift (Sebelum Pulang)

```
CHECKLIST:
□ Semua peminjaman hari ini tercatat
□ Semua pengembalian tercatat
□ Cek history: /staff/borrowing-history
  - Pastikan status sudah update
  - Tandai yang perlu follow-up

□ Cek member dengan denda belum bayar
  - Via history → filter by status/date
  - List member → remind mereka

□ Backup data (jika diperlukan)
  - IT admin handle
```

### Laporan Mingguan

```
METRIK PENTING:
- Total peminjaman minggu ini
- Total pengembalian
- Buku paling sering dipinjam
- Member dengan denda terbanyak
- Buku yang hilang/rusak

AKSES:
- Lihat di: /admin/reports
- Export: Bisa request ke admin
```

---

## 🔐 KEAMANAN & ETIKA

### Privasi Member
```
✅ BOLEH:
- Lihat data member saat proses peminjaman
- Lihat history peminjaman member
- Lihat denda member

❌ JANGAN:
- Share data member ke orang lain
- Publish riwayat peminjaman
- Diskriminasi berdasarkan denda
```

### Integritas Data
```
✅ BOLEH:
- Proses peminjaman/pengembalian normal
- Buat catatan di notes field

❌ JANGAN:
- Ubah tanggal pinjam/kembali
- Hapus record peminjaman
- Mengurangi denda tanpa persetujuan
- Share akun login
```

---

## 🎓 TIPS & BEST PRACTICE

### Untuk Kecepatan:
```
1. Letakkan scanner di posisi nyaman
2. Pastikan input field selalu fokus
3. Tekan ENTER, jangan klik tombol
4. Gunakan scanner barcode (lebih cepat)
5. Training regular untuk staff
```

### Untuk Akurasi:
```
1. Double-check data sebelum proses
2. Gunakan history untuk verifikasi
3. Catat anomali di notes
4. Report error ke admin
5. Update QR code yang rusak
```

### Untuk Customer Service:
```
1. Senyum & ramah saat member datang
2. Jelaskan status buku dengan jelas
3. Ingatkan batas kembali secara proaktif
4. Bantu member cari buku yang diinginkan
5. Report rekomendasi buku baru
```

---

## 📞 KONTAK & SUPPORT

### Jika Ada Masalah:
```
1. Error teknis:
   → Hubungi Admin IT
   → Screenshot error
   → Jelaskan langkah-langkahnya

2. Pertanyaan operasional:
   → Hubungi Kepala Perpustakaan
   → Atau lihat dokumentasi

3. Request fitur baru:
   → Diskusi dengan admin
   → Dokumentasikan kebutuhan
```

---

## ✅ CHECKLIST AKHIR HARI

```
□ Semua peminjaman tercatat
□ Semua pengembalian tercatat
□ Denda sudah diidentifikasi
□ Buku yang rusak/hilang dilaporkan
□ History sudah di-update
□ QR code yang rusak di-dokumentasikan
□ Laptop/scanner di-shutdown dengan baik
□ Ruangan rapi dan aman
```

---

**Versi**: 1.0
**Dibuat**: 19 Januari 2026
**Berlaku untuk**: Semua Petugas Perpustakaan
