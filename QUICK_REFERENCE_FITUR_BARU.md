# 🎯 QUICK REFERENCE - FITUR BARU

## 1. ERROR FIX ✅

**File**: `resources/views/member/profile.blade.php:124`

```blade
❌ {{ $borrowing->borrowed_date->format('d M Y') }}
✅ {{ $borrowing->borrowed_at ? $borrowing->borrowed_at->format('d M Y') : '-' }}
```

---

## 2. REPORT SYSTEM 📋

### Routes
```php
/reports              GET     List laporan
/reports/create       GET     Form buat
/reports              POST    Simpan
/reports/{id}         GET     Detail
/reports/{id}/edit    GET     Form edit
/reports/{id}         PUT     Update
/reports/{id}         DELETE  Hapus
```

### Controller: `ReportController`
- `index()` - List laporan user
- `create()` - Show form
- `store()` - Save ke DB
- `show()` - Detail
- `edit()` - Edit form (pending only)
- `update()` - Update (pending only)
- `destroy()` - Delete (pending only)

### Model: `Report`
```php
$report = Report::find($id);
$report->user;           // User yang buat
$report->title;          // Judul
$report->type;           // book_issue|account_issue|technical_issue|other
$report->description;    // Deskripsi
$report->status;         // pending|in_progress|resolved|rejected
$report->response;       // Respons admin
```

### Views
- `member/reports/create.blade.php` - Form create
- `member/reports/index.blade.php` - List
- `member/reports/show.blade.php` - Detail
- `member/reports/edit.blade.php` - Form edit

### Policy
- `ReportPolicy` - Authorization checks
  - view() - Hanya milik user
  - update() - Hanya milik user + pending status
  - delete() - Hanya milik user + pending status

---

## 3. EDIT PROFILE 👤

### Routes
```php
/profile/edit    GET    Form edit
/profile         PUT    Submit
```

### Controller: `DashboardController`
- `editProfile()` - Show form edit profil
- `updateProfile()` - Save perubahan

### Fields yang bisa di-update
- `name` - Nama lengkap (required)
- `email` - Email (required, unique)
- `phone` - Nomor telepon (optional)
- `address` - Alamat (optional)
- `date_of_birth` - Tanggal lahir (optional)

### View
- `member/edit-profile.blade.php`

---

## 4. BOOKMARKS (Sudah Ada) ⭐

### Model: `Bookmark`
```php
$bookmark = Bookmark::find($id);
$bookmark->user;    // User yang bookmark
$bookmark->book;    // Book yang di-bookmark
```

### Routes (dari BookController)
```php
POST   /books/{book}/bookmark      Toggle bookmark
DELETE /bookmarks/{bookmark}       Delete bookmark
```

### View Display
```blade
<!-- Di profile.blade.php -->
{{ $bookmarkedBooks->count() }}     <!-- Total count -->
@foreach($bookmarkedBooks as $bookmark)
    {{ $bookmark->book->title }}
    {{ $bookmark->book->author }}
@endforeach
```

---

## 5. DATABASE MIGRATIONS 🗄️

### Reports Table
```sql
id, user_id, type, title, description, 
status, response, created_at, updated_at, deleted_at
```

### Relationships
```php
User hasMany Reports
Report belongsTo User
```

---

## 6. VALIDATION RULES ✔️

### Report Creation/Update
```php
'type' => 'required|in:book_issue,account_issue,technical_issue,other'
'title' => 'required|string|max:255'
'description' => 'required|string|max:2000'
```

### Profile Update
```php
'name' => 'required|string|max:255'
'email' => 'required|email|unique:users,email,' . $user->id
'phone' => 'nullable|string|max:20'
'address' => 'nullable|string|max:500'
'date_of_birth' => 'nullable|date|before:today'
```

---

## 7. KEY FILES 📁

| File | Type | Purpose |
|------|------|---------|
| `ReportController.php` | Controller | Handle report CRUD |
| `Report.php` | Model | Report data model |
| `ReportPolicy.php` | Policy | Authorization |
| `2026_01_21_create_reports_table.php` | Migration | DB table |
| `member/reports/*.blade.php` | Views | UI pages |
| `member/edit-profile.blade.php` | View | Edit form |
| `routes/web.php` | Routes | URL mapping |
| `AppServiceProvider.php` | Provider | Policy registration |

---

## 8. COMMON TASKS 🛠️

### Buat Laporan Baru
```php
$report = Report::create([
    'user_id' => Auth::id(),
    'type' => 'book_issue',
    'title' => 'Judul Laporan',
    'description' => 'Deskripsi...',
    'status' => 'pending'
]);
```

### Update Status Laporan (Admin Only)
```php
$report->update([
    'status' => 'in_progress',
    'response' => 'Kami sedang mengecek...'
]);
```

### Get User Reports
```php
$reports = $user->reports()->get();
// or
$reports = Report::where('user_id', $user->id)->get();
```

### Check Authorization
```php
$this->authorize('view', $report);
$this->authorize('update', $report);
$this->authorize('delete', $report);
```

---

## 9. STATUS COLORS 🎨

| Status | Badge | Icon |
|--------|-------|------|
| pending | Yellow | ⏳ |
| in_progress | Blue | ⚙️ |
| resolved | Green | ✓ |
| rejected | Red | ✗ |

---

## 10. FORM EXAMPLES 📝

### Create Report Form
```blade
<form action="{{ route('reports.store') }}" method="POST">
    @csrf
    <select name="type" required>
        <option value="book_issue">📚 Masalah Buku</option>
        <option value="account_issue">👤 Masalah Akun</option>
        <option value="technical_issue">🔧 Masalah Teknis</option>
        <option value="other">❓ Lainnya</option>
    </select>
    <input type="text" name="title" required>
    <textarea name="description" required></textarea>
    <button type="submit">Kirim</button>
</form>
```

### Edit Profile Form
```blade
<form action="{{ route('profile.update') }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="name" required>
    <input type="email" name="email" required>
    <input type="tel" name="phone">
    <textarea name="address"></textarea>
    <input type="date" name="date_of_birth">
    <button type="submit">Simpan</button>
</form>
```

---

## 11. MIDDLEWARE 🔒

### Report Routes Protection
```php
Route::middleware('auth')->group(function () {
    Route::resource('reports', ReportController::class);
});
```

### Policy Authorization
```php
// Di ReportController
public function update(Report $report) {
    $this->authorize('update', $report); // Throws AuthorizationException
    // ...
}
```

---

## 12. ERROR HANDLING 🚨

### Try-Catch Pattern
```php
try {
    $report = Report::create($validated);
    return redirect()->route('reports.index')
        ->with('success', 'Laporan berhasil dibuat');
} catch (Exception $e) {
    return back()->with('error', 'Terjadi kesalahan');
}
```

### Validation Error Display
```blade
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

@error('title')
    <span>{{ $message }}</span>
@enderror
```

---

## 13. TESTING CHECKLIST ✅

- [ ] Error fix: Profile page load tanpa error
- [ ] Create report: Form submit berhasil
- [ ] List reports: Pagination works
- [ ] View detail: Bisa lihat semua info
- [ ] Edit report: Hanya pending status
- [ ] Delete report: Hanya pending status
- [ ] Edit profile: Semua field update
- [ ] Email unique: Tidak bisa pakai email lain
- [ ] Date validation: Tidak bisa birthdate masa depan
- [ ] Authorization: User A tidak bisa lihat Report user B

---

## 14. HELPFUL COMMANDS 💻

```bash
# List all routes
php artisan route:list

# Filter by name
php artisan route:list --name=report

# Check database
php artisan tinker
>>> Report::all()
>>> User::first()->reports

# Clear cache
php artisan cache:clear
php artisan route:clear
php artisan config:clear

# Generate model + controller + migration
php artisan make:model Report -mcr

# Generate policy
php artisan make:policy ReportPolicy --model=Report
```

---

## 15. NOTES 📌

- Semua validation error ada custom message
- UI responsif untuk mobile & desktop
- Using Koshouko design system existing
- SoftDeletes enabled di Report model
- Pagination 10 items per page
- Created_at index untuk faster queries
- Relationship eager loading di list views

---

## 🎉 DONE!

Semua fitur sudah siap pakai:
✅ Error fixed
✅ Report CRUD
✅ Edit Profile CRUD
✅ Bookmarks (sudah ada)
✅ Authorization & Validation
✅ Responsive UI

Selamat menggunakan! 🚀

