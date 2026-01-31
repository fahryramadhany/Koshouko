# SISTEM PEMINJAMAN BUKU - FLOW DIAGRAM

## 📊 WORKFLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────────────────┐
│                     MEMBER (Peminjam)                               │
└─────────────────────────────────────────────────────────────────────┘

    1️⃣ SUBMIT FORM                           4️⃣ VIEW HISTORY
┌──────────────────┐                      ┌──────────────────┐
│ /borrowings/     │                      │  /borrowings     │
│  create          │                      │  (index)         │
├──────────────────┤                      ├──────────────────┤
│ • Pilih Buku     │                      │ Tab:             │
│ • Pilih Durasi   │                      │ • Semua          │
│ • Isi Syarat     │                      │ • Sedang Dipinjam│
│ • Klik Ajukan    │                      │ • Sudah Kembali  │
└────────┬─────────┘                      └────────▲─────────┘
         │                                         │
         │ POST /borrowings                        │
         │                                         │
         ▼                                         │
┌─────────────────────────────────────────┐       │
│  CREATE BORROWING RECORD                │       │
│  Status: PENDING                        │       │
│  approved_by: null                      │       │
│  qr_code: null                          │       │
└────────┬────────────────────────────────┘       │
         │                                         │
         │                                    ┌────┴─────────┐
         │                                    │              │
         │ 2️⃣ ADMIN/LIBRARIAN                 │ 3️⃣ MEMBER    │
         │    APPROVAL                        │    ACTIONS   │
         │                                    │              │
         ▼                                    │              │
     ┌────────────────────┐             ┌────▼──────┐       │
     │ ADMIN DASHBOARD    │             │ QR MODAL  │       │
     ├────────────────────┤             ├───────────┤       │
     │ /admin/borrowings  │             │ Click QR  │       │
     │                    │             │ Code Btn  │       │
     │ Filter: Status     │             │ ↓         │       │
     │ Table:             │             │ View PNG  │       │
     │ • Member Name      │             │ Image     │       │
     │ • Book Title       │             │ Print/    │       │
     │ • Tanggal          │             │ Screenshot│       │
     │ • Status (PENDING) │             └───────────┘       │
     │ • Buttons:         │                    ▲             │
     │   Setujui|Tolak    │                    │             │
     └─┬──────────────────┘                    │             │
       │                                        │             │
       │ CLICK "SETUJUI"                        │ AFTER APPROVED
       │                                        │             │
       ▼                                        │             │
    ┌────────────────────────────────┐         │             │
    │ GENERATE QR CODE               │         │             │
    ├────────────────────────────────┤         │             │
    │ 1. Create QR with:             │         │             │
    │    • ID                        │         │             │
    │    • Member Name               │         │             │
    │    • Book Title                │         │             │
    │    • Due Date                  │         │             │
    │ 2. Generate PNG image          │         │             │
    │ 3. Save: public/qr/            │         │             │
    │    borrowing_[ID].png          │         │             │
    └────────┬─────────────────────┬─┘         │             │
             │                     │           │             │
    ┌────────▼─────────┐    ┌──────▼────────┐ │             │
    │ UPDATE DATABASE  │    │ SHOW SUCCESS  │ │             │
    ├──────────────────┤    │ MESSAGE       │ │             │
    │ status: APPROVED │    └───────────────┘ │             │
    │ qr_code: 'qr/...'├─────────────────────┘             │
    │ approved_by: ID  │                                    │
    │ approved_at: NOW │     5️⃣ TAKE BOOK                  │
    │                  │     ┌───────────────┐              │
    │ STATUS: ✅ HIJAU │     │ Show QR to    │              │
    │                  │     │ Librarian     │              │
    └──────────────────┘     │ Take Book     │              │
                             │ Offline       │              │
                             └───────────────┘              │
                                                             │
                             6️⃣ RETURN BOOK                 │
                             ┌───────────────┐              │
                             │ Click         │              │
                             │ "Kembalikan"  │              │
                             │ Button        │              │
                             └────┬──────────┘              │
                                  │                         │
                                  ▼                         │
                             POST /borrowings/              │
                             {id}/return                    │
                                  │                         │
                                  ▼                         │
                             ┌───────────────┐              │
                             │ UPDATE STATUS │              │
                             │ returned_at:  │              │
                             │ NOW           │              │
                             │ status:       │              │
                             │ RETURNED      │              │
                             │ (Biru)        │              │
                             └───────────────┘              │
                                  │                         │
                                  └─────────────────────────┘


```

---

## 🔀 REJECTION FLOW

```
MEMBER SUBMIT FORM (Status: PENDING)
        │
        ▼
ADMIN CLICK "TOLAK"
        │
        ▼
┌──────────────────────────────────┐
│ REJECTION MODAL APPEARS          │
├──────────────────────────────────┤
│ Title: Tolak Peminjaman          │
│ Label: Alasan Penolakan *        │
│ TextArea: [Input Required]       │
│ Buttons: [Batal] [Tolak]         │
└──────────┬───────────────────────┘
           │
           │ Admin isi alasan
           ▼
       SUBMIT FORM (rejection_reason)
           │
           ▼
    ┌────────────────────┐
    │ VALIDATE REQUEST   │
    │ • rejection_reason │
    │   required: ✓      │
    │   max 500 char: ✓  │
    └────────┬───────────┘
             │
             ▼
    ┌──────────────────────────┐
    │ UPDATE DATABASE          │
    │ • status: REJECTED       │
    │ • rejection_reason: [..] │
    │ • available_copies++     │
    │   (kembalikan stok)       │
    └────────┬─────────────────┘
             │
             ▼
    ┌──────────────────────┐
    │ SHOW SUCCESS MESSAGE │
    │ "Ditolak"            │
    │ STATUS: 🔴 MERAH     │
    └────────┬─────────────┘
             │
             ▼
MEMBER LIHAT DI HISTORY
┌──────────────────────────────┐
│ Status: REJECTED (Merah)     │
│ "✗ Ditolak"                  │
│ "Alasan: [Admin's Reason]"   │
└──────────────────────────────┘

```

---

## 🔔 STATUS BADGE COLORS

```
┌────────────┬──────────┬─────────────────┐
│  STATUS    │  COLOR   │  BUTTON STATUS  │
├────────────┼──────────┼─────────────────┤
│ PENDING    │  Yellow  │ Setujui/Tolak   │
│ APPROVED   │  Green   │ Kembalikan/     │
│            │          │ Perpanjang      │
│ RETURNED   │  Blue    │ (disabled)      │
│ REJECTED   │  Red     │ (disabled)      │
│ OVERDUE    │  Red     │ Kembalikan      │
│            │          │ (late fees)     │
└────────────┴──────────┴─────────────────┘
```

---

## 📁 FILE STRUCTURE

```
resources/
├── views/
│   ├── admin/
│   │   └── borrowings/
│   │       └── index.blade.php ✅ Updated
│   │           • Approval table
│   │           • Rejection modal
│   │
│   ├── pustakawan/
│   │   └── borrowings/
│   │       └── index.blade.php ✅ Updated
│   │           • Approval table
│   │           • Rejection modal
│   │
│   └── member/
│       └── borrowings/
│           ├── create.blade.php ✅ Ready
│           │   • Form submission
│           │
│           └── index.blade.php ✅ Updated
│               • History display
│               • QR modal
│               • Rejection reason
│
app/
├── Http/
│   └── Controllers/
│       ├── AdminController.php ✅ Updated
│       │   • approveBorrowing() + QR
│       │   • rejectBorrowing() + reason
│       │
│       ├── Librarian/
│       │   └── LibrarianDashboardController.php ✅ Updated
│       │       • approveBorrowing() + QR
│       │       • rejectBorrowing() + reason
│       │
│       └── BorrowingController.php ✅ Exists
│           • create()
│           • store()
│           • index()
│           • return()
│           • renew()
│
└── Models/
    └── Borrowing.php ✅ Updated
        • fillable: qr_code, approved_by, etc.
        • approver() relationship
        • casts: approved_at

database/
└── migrations/
    ├── 2025_01_16_000007_create_borrowings_table.php
    │   (existing)
    │
    └── 2025_01_28_000001_add_qr_approved_to_borrowings_table.php ✅
        (NEW - adds QR & approval fields)

public/
└── qr/ ✅ Auto-created
    ├── borrowing_1.png
    ├── borrowing_2.png
    └── ... (generated on approval)
```

---

## 🔄 REQUEST/RESPONSE FLOW

### APPROVAL FLOW

**REQUEST**:
```
POST /admin/borrowings/{borrowing}/approve
Headers:
  Content-Type: application/x-www-form-urlencoded
Body:
  _token: [CSRF_TOKEN]
  (No additional fields needed)
```

**RESPONSE**:
```
Redirect /admin/borrowings
With Session Message: "Peminjaman berhasil disetujui. QR code telah dibuat."
Side Effects:
  • QR PNG created: public/qr/borrowing_[ID].png
  • Database updated:
    - status = 'approved'
    - qr_code = 'qr/borrowing_[ID].png'
    - approved_by = [logged in user ID]
    - approved_at = current timestamp
```

### REJECTION FLOW

**REQUEST**:
```
POST /admin/borrowings/{borrowing}/reject
Headers:
  Content-Type: application/x-www-form-urlencoded
Body:
  _token: [CSRF_TOKEN]
  rejection_reason: "Buku sedang dalam perbaikan"
```

**VALIDATION**:
```
rejection_reason:
  • required (wajib ada)
  • string
  • max:500 characters
```

**RESPONSE**:
```
Redirect /admin/borrowings
With Session Message: "Peminjaman berhasil ditolak."
Side Effects:
  • Database updated:
    - status = 'rejected'
    - rejection_reason = [provided reason]
    - available_copies incremented (stok dikembalikan)
```

---

## 🎯 EXPECTED USER BEHAVIOR

### Admin/Librarian
1. Login → Dashboard
2. Click "Kelola Peminjaman"
3. See table dengan filter status
4. Filter by PENDING
5. Click "Setujui" atau "Tolak"
6. If Tolak → Modal appears → Fill reason → Submit
7. See status updated immediately

### Member
1. Login → Dashboard
2. Click "Ajukan Peminjaman"
3. Fill form → Submit
4. Redirect to history dengan pesan "pending"
5. See yellow PENDING status
6. Wait for admin approval
7. When approved → See green APPROVED status
8. See "📱 Lihat QR Code" button
9. Click → QR Modal shows
10. Take screenshot / print QR
11. Show to librarian when taking book
12. Return book → Click "Kembalikan"
13. See blue RETURNED status

---

## ⚠️ ERROR HANDLING

```
SCENARIO: Alasan tolak tidak diisi
├─ Admin klik "Tolak"
├─ Modal appear
├─ Admin klik "Tolak" tanpa isi
└─ Validation error: "Silakan berikan alasan penolakan"

SCENARIO: QR Code tidak bisa generate
├─ Admin approve
├─ QR directory tidak writable
├─ Try/catch error handling
├─ Log error di storage/logs/laravel.log
└─ Show error message ke admin

SCENARIO: Database update gagal
├─ After QR generated
├─ DB transaction rollback
├─ Error message ditampilkan
└─ QR file dihapus (opsional)
```

---

## 📈 PERFORMANCE NOTES

- QR generation: ~100-200ms per image
- Database update: ~10-20ms
- Modal open/close: Instant (vanilla JS)
- No pagination (uses limit for performance)
- Lazy load member history (10 items per page)

---

## 🔐 SECURITY

- ✅ CSRF token protection (Laravel default)
- ✅ Model binding (automatic parameter validation)
- ✅ Authorization checking (implicitly via controller)
- ✅ Input validation on rejection reason
- ✅ File stored outside public would be safer (optional)

---

**Last Updated**: 2025-01-28
**Status**: ✅ COMPLETE
