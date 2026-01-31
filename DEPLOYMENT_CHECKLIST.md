# ✅ DEPLOYMENT CHECKLIST - REVIEWS & RATING SYSTEM

Checklist lengkap untuk deploy dan testing fitur reviews & rating ke production.

---

## 🎯 PRE-DEPLOYMENT CHECKLIST

### 1. Code Quality Check
- [ ] Verify migration file syntax: `database/migrations/2026_01_22_create_reviews_table.php`
- [ ] Verify Review model: `app/Models/Review.php`
- [ ] Verify ReviewController: `app/Http/Controllers/ReviewController.php`
- [ ] Verify ReviewPolicy: `app/Policies/ReviewPolicy.php`
- [ ] Check Book model updates: `app/Models/Book.php`
- [ ] Check User model updates: `app/Models/User.php`
- [ ] Check BookController updates: `app/Http/Controllers/BookController.php`
- [ ] Check AppServiceProvider updates: `app/Providers/AppServiceProvider.php`
- [ ] Check routes/web.php updates: review routes added
- [ ] Check view updates: `resources/views/member/books/show.blade.php`

### 2. Database Preparation
- [ ] Backup current database (CRITICAL!)
  ```bash
  # On Windows with XAMPP
  # Go to MySQL folder and run:
  mysqldump -u root -p perpus_digit_laravel > backup_perpus_digit_$(date +%Y%m%d_%H%M%S).sql
  ```
- [ ] Verify database connection in `.env`
- [ ] Check database user has necessary privileges
- [ ] Verify no existing `reviews` table (if first time)
- [ ] Test migration in development first (if possible)

### 3. Configuration Check
- [ ] Verify `.env` file is configured correctly
- [ ] Check `config/app.php` has correct app name
- [ ] Verify `config/auth.php` has correct auth driver
- [ ] Check `config/database.php` has correct DB driver
- [ ] Verify all environment variables set

### 4. Dependencies Check
- [ ] Verify Laravel version: 12.47.0
- [ ] Verify PHP version: 8.2.12
- [ ] Verify MySQL installed and running
- [ ] Run `composer install` (if fresh)
- [ ] Run `php artisan --version` (verify CLI works)

### 5. File Permission Check
- [ ] Storage folder writable: `chmod 755 storage`
- [ ] Bootstrap cache writable: `chmod 755 bootstrap/cache`
- [ ] Vendor folder accessible

---

## 🚀 DEPLOYMENT STEPS (IN ORDER)

### Step 1: Backup Everything
```bash
# Backup database
mysqldump -u root -p perpus_digit_laravel > backup_perpus_digit_2026_01_22.sql

# Backup project files
# Use backup software or git:
git add . && git commit -m "Before reviews feature deployment"
```

**Checklist**:
- [ ] Database backup created
- [ ] Project files backed up
- [ ] Backup location noted

---

### Step 2: Run Database Migration
```bash
# In terminal/PowerShell, navigate to project folder:
cd c:\xampp\htdocs\perpus_digit_laravel

# Run migration
php artisan migrate

# Expected output:
# Migrating: 2026_01_22_create_reviews_table
# Migrated:  2026_01_22_create_reviews_table (156ms)
```

**Verification**:
```bash
# Verify table created
php artisan tinker

# Inside tinker:
DB::table('reviews')->get()  # Should return empty collection
DB::table('migrations')->where('migration', 'like', '%reviews%')->get()  # Should show migration
exit()
```

**Checklist**:
- [ ] Migration runs without errors
- [ ] Table created in database
- [ ] Foreign keys created
- [ ] Unique constraint verified
- [ ] Indexes created

**If Error**:
- [ ] Check error message in console
- [ ] Verify migration file syntax
- [ ] Check database connection
- [ ] Look at `storage/logs/laravel.log`
- [ ] Rollback if needed: `php artisan migrate:rollback`
- [ ] Fix issues and re-migrate

---

### Step 3: Clear Application Cache
```bash
# Clear all caches
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Expected output:
# Application cache cleared!
# Route cache cleared!
# Configuration cache cleared!
# View cache cleared!
```

**Checklist**:
- [ ] Cache clear command runs successfully
- [ ] No errors in console
- [ ] All 4 cache clears complete

---

### Step 4: Restart Application Server
```bash
# Option 1: If using Laravel Serve
php artisan serve
# Then access at: http://localhost:8000

# Option 2: If using XAMPP
# 1. Open XAMPP Control Panel
# 2. Stop Apache (if running)
# 3. Start Apache again
# Then access at: http://localhost/perpus_digit_laravel/public
```

**Checklist**:
- [ ] Server started without errors
- [ ] No port conflicts
- [ ] Application accessible

---

### Step 5: Verify Routes
```bash
# List all routes
php artisan route:list

# Filter review routes
php artisan route:list | grep -i review
```

**Expected Output**:
```
POST   /books/{book}/reviews         reviews.store   ReviewController@store
PUT    /reviews/{review}             reviews.update  ReviewController@update
DELETE /reviews/{review}             reviews.destroy ReviewController@destroy
POST   /reviews/{review}/helpful     reviews.helpful ReviewController@helpful
```

**Checklist**:
- [ ] All 4 review routes appear
- [ ] Routes have correct names
- [ ] Routes map to correct controllers
- [ ] Middleware shows 'auth' (required auth)

---

## 🧪 TESTING PHASE (IN ORDER)

### Test 1: Database Connection
```bash
# Test database connection
php artisan tinker

# Inside tinker:
Review::all()          # Should return empty collection
Book::first()          # Should return a book
User::first()          # Should return a user
exit()
```

**Expected**:
- No errors
- Models load correctly
- Database connected

**Checklist**:
- [ ] Database connection works
- [ ] Review model accessible
- [ ] Book model loads
- [ ] User model loads

---

### Test 2: View Route Access
Open browser and navigate to book detail page:
```
http://localhost/perpus_digit_laravel/public/books/1
```

**Expected**:
- Page loads without error
- Book information displays
- Rating section visible
- Review form visible
- No console errors (F12 → Console)

**Checklist**:
- [ ] Page loads (no 500 error)
- [ ] Layout displays (4-column on desktop)
- [ ] Book info shows
- [ ] Rating summary shows
- [ ] Review form shows

---

### Test 3: Create Review (Authenticated User)
1. Ensure you're logged in as a member
2. Go to `/books/1`
3. Click on first star (to select 1-star rating)
4. See text change to "Tidak Memuaskan"
5. Enter title: "Test review"
6. Enter content: "This is a test review"
7. Click "Kirim Ulasan" button
8. Verify page redirects and review appears

**Expected**:
- Form submits without error
- Review saved to database
- Page reloads with success message
- New review visible in "Ulasan Anda" section
- Rating updated in summary

**Checklist**:
- [ ] Form validation works
- [ ] Review saves to database
- [ ] Success message appears
- [ ] Review displays on page
- [ ] Rating summary updates

---

### Test 4: Edit Review
1. Click "Edit" on your review
2. Edit form appears
3. Change rating to 5 stars
4. Change content
5. Click "Simpan"
6. Form hides, review updated

**Expected**:
- Edit form toggles visibility
- Changes save successfully
- Review updates on page
- No validation errors
- Success message appears

**Checklist**:
- [ ] Edit form toggles
- [ ] Form validation works
- [ ] Changes save
- [ ] Page updates
- [ ] Success message shows

---

### Test 5: Delete Review
1. Click "Edit" on your review
2. Click "Hapus" button
3. Confirm delete dialog
4. Review disappears
5. Create form reappears

**Expected**:
- Confirm dialog appears
- Review soft-deleted from database
- Page updates
- Create form shows again
- No errors

**Checklist**:
- [ ] Delete confirmation works
- [ ] Review deleted
- [ ] Page updates
- [ ] Create form shows again
- [ ] No errors in console

---

### Test 6: Rating Distribution
1. Check rating summary section
2. Verify bars show correct heights
3. Verify counts match actual reviews
4. Test with multiple reviews (admin: create from different users)

**Expected**:
- Bars display correctly
- Counts accurate
- Percentages calculated correctly
- Visual representation matches data

**Checklist**:
- [ ] Rating distribution displays
- [ ] Counts are correct
- [ ] Bars scale properly
- [ ] Percentages calculated

---

### Test 7: Review Pagination
1. Ensure book has 5+ reviews
2. Scroll to review list
3. Verify "Next" pagination link
4. Click "Next"
5. Verify next page of reviews shows
6. Click "Previous" or specific page

**Expected**:
- 5 reviews per page
- Pagination links work
- Correct reviews on each page
- Page number highlighted

**Checklist**:
- [ ] Pagination shows (if 5+ reviews)
- [ ] Page changes work
- [ ] Correct reviews display
- [ ] Navigation works

---

### Test 8: Helpful Button
1. View another user's review
2. Click "👍 Membantu" button
3. Count increments
4. Success message appears

**Expected**:
- Click submits form
- helpful_count increments by 1
- Page reloads
- Count updated on display
- Success message shows

**Checklist**:
- [ ] Button submits form
- [ ] Counter increments
- [ ] Page updates
- [ ] Success message appears

---

### Test 9: Authorization (Security)
1. Create review as User A
2. Logout
3. Login as User B
4. Go to User A's review detail page (if it exists)
5. Verify User B cannot edit/delete User A's review
6. Check browser console for 403 error when trying

**Expected**:
- User B cannot see edit/delete buttons
- Attempting to edit returns 403 Forbidden
- Authorization policy works
- User A can edit/delete their own

**Checklist**:
- [ ] User can edit own review
- [ ] User cannot edit others
- [ ] Authorization works
- [ ] 403 error returned when unauthorized

---

### Test 10: Responsive Design
1. Open book detail page
2. Use browser DevTools (F12 → Device Toolbar)

**Mobile (iPhone 12: 390px)**:
- [ ] Layout single column
- [ ] Sidebar hidden/stacked
- [ ] Touch buttons large enough
- [ ] Text readable
- [ ] Form inputs full-width

**Tablet (iPad: 768px)**:
- [ ] Layout 2-3 columns
- [ ] Content side-by-side
- [ ] Readable spacing
- [ ] Buttons properly sized

**Desktop (1920px)**:
- [ ] Layout 4 columns
- [ ] Sticky sidebar
- [ ] Full use of space
- [ ] Proper alignment

**Checklist**:
- [ ] Mobile responsive
- [ ] Tablet responsive
- [ ] Desktop layout good
- [ ] No horizontal scroll
- [ ] Text readable on all sizes

---

### Test 11: Form Validation
Try invalid submissions:

**Test 11a: Submit without rating**
- Click "Kirim Ulasan" without selecting rating
- Expected: Error message "Rating harus dipilih"

**Test 11b: Submit with empty content**
- Select rating but leave content empty
- Expected: Error message "Content wajib diisi"

**Test 11c: Submit with 1001+ characters**
- Try paste 1001+ chars in content
- Expected: Error message "Max 1000 karakter"

**Checklist**:
- [ ] Rating validation works
- [ ] Content validation works
- [ ] Length validation works
- [ ] Error messages display correctly

---

### Test 12: Data Integrity
Check database directly:

```bash
php artisan tinker

# Check reviews table
$reviews = DB::table('reviews')->get();
dd($reviews);  # Should show all reviews

# Check unique constraint
$userBookPair = DB::table('reviews')
    ->where('user_id', 1)
    ->where('book_id', 1)
    ->count();
dd($userBookPair);  # Should be 0 or 1, never >1

# Check foreign key
$orphaned = DB::table('reviews')
    ->whereNotIn('user_id', DB::table('users')->pluck('id'))
    ->count();
dd($orphaned);  # Should be 0 (no orphaned reviews)

exit()
```

**Expected**:
- All reviews exist
- No duplicate user_id+book_id pairs
- All reviews have valid user_id & book_id
- Soft delete working (deleted_at has value for deleted)

**Checklist**:
- [ ] Reviews stored in database
- [ ] Unique constraint enforced
- [ ] Foreign keys valid
- [ ] Soft deletes working

---

## 📊 PERFORMANCE TESTING

### Test 13: Page Load Time
1. Open DevTools (F12 → Performance)
2. Record page load for `/books/1` with 20+ reviews
3. Check load time (should be < 2 seconds)
4. Check number of queries (should be minimal)

**Expected**:
- Page loads fast (< 2 seconds)
- Minimal database queries
- No N+1 queries
- CSS/JS loads properly

**Checklist**:
- [ ] Page load time acceptable
- [ ] No slow queries
- [ ] No N+1 problems
- [ ] Assets load properly

---

### Test 14: Database Query Count
Enable query logging:

```php
// In AppServiceProvider.php boot() method, temporarily add:
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

DB::listen(function (QueryExecuted $query) {
    Log::info($query->sql . ' | Bindings: ' . json_encode($query->bindings));
});
```

Then reload book detail page and check `storage/logs/laravel.log`

**Expected**:
- Queries use eager loading (with())
- No duplicate queries
- < 10 queries for book detail page
- Reviews use pagination (not load all)

**Checklist**:
- [ ] Query count minimal
- [ ] Eager loading used
- [ ] No N+1 queries
- [ ] Pagination working

---

## 🎯 FINAL VERIFICATION

### Checklist Before Going Live

**Code Quality**:
- [ ] No PHP syntax errors: `php artisan tinker` works
- [ ] No undefined variables or methods
- [ ] All imports correct
- [ ] No debugging code left (dd, var_dump, etc)

**Database**:
- [ ] Migration complete: `php artisan migrate:status`
- [ ] Tables created with correct structure
- [ ] Relationships working
- [ ] Constraints enforced
- [ ] Indexes present

**Routes**:
- [ ] 4 review routes registered: `php artisan route:list | grep review`
- [ ] Routes point to correct controllers
- [ ] Authentication required (middleware auth)

**Models**:
- [ ] Review model: has relationships, accessors
- [ ] Book model: has reviews relationship + computed attributes
- [ ] User model: has reviews relationship

**Controllers**:
- [ ] ReviewController: has 4 methods (store, update, destroy, helpful)
- [ ] BookController: show() loads reviews correctly
- [ ] All validation rules present

**Views**:
- [ ] Book detail view completely redesigned
- [ ] Rating section displays
- [ ] Review form works
- [ ] Edit form toggles
- [ ] Pagination links work

**Authorization**:
- [ ] ReviewPolicy registered in AppServiceProvider
- [ ] Policy methods: update(), delete()
- [ ] Authorization checks work (403 on unauthorized)

**Testing**:
- [ ] All 14 tests pass
- [ ] No console errors (F12)
- [ ] No database errors
- [ ] Responsive on all sizes
- [ ] Validation messages clear
- [ ] Success messages appear

---

## 🆘 TROUBLESHOOTING

### If Migration Fails

**Problem**: "reviews table already exists"
```bash
# Solution: Drop table and re-migrate
php artisan migrate:rollback
php artisan migrate
```

**Problem**: "Foreign key constraint failed"
```bash
# Check if users or books tables exist first
php artisan migrate

# If still fails, check foreign key syntax in migration
```

**Problem**: "SQLSTATE[HY000]: General error"
```bash
# Check MySQL connection
# Verify .env DB_HOST, DB_NAME, DB_USER, DB_PASSWORD
# Test connection:
php artisan db
```

---

### If Routes Don't Show Up

**Problem**: Review routes not in `php artisan route:list`
```bash
# Solution: Clear route cache
php artisan route:clear
php artisan cache:clear

# Re-check
php artisan route:list | grep review
```

---

### If Views Show Errors

**Problem**: "Undefined variable $reviews"
```bash
# Check BookController@show method
# Verify $reviews passed to view:
return view('member.books.show', [
    'reviews' => $reviews,
    'userReview' => $userReview,
    'averageRating' => $averageRating,
    'totalReviews' => $totalReviews,
    'ratingDistribution' => $ratingDistribution,
]);
```

**Problem**: "Class ReviewController not found"
```bash
# Verify namespace and import in routes/web.php
use App\Http\Controllers\ReviewController;

# Check file exists:
# app/Http/Controllers/ReviewController.php
```

---

### If Authorization Fails

**Problem**: "The Gate does not have a [destroy] ability registered for [App\Models\Review]"
```bash
# Check AppServiceProvider has:
protected $policies = [
    Review::class => ReviewPolicy::class,
];

# Re-clear cache:
php artisan cache:clear
```

---

## 📝 POST-DEPLOYMENT

### After Going Live

**Week 1**:
- [ ] Monitor error logs: `tail -f storage/logs/laravel.log`
- [ ] Check user feedback
- [ ] Fix any bugs found
- [ ] Monitor database performance

**Week 2**:
- [ ] Analyze usage patterns
- [ ] Check most-rated books
- [ ] Monitor helpful votes
- [ ] Plan improvements

**Ongoing**:
- [ ] Backup database weekly
- [ ] Monitor slow queries
- [ ] Clean up old deleted reviews (optional)
- [ ] Update documentation
- [ ] Plan new features

---

## 📋 SIGN-OFF

After all tests pass, sign off:

```
✅ DEPLOYMENT CHECKLIST COMPLETE

Date: [DATE]
Deployed By: [NAME]
Environment: [DEVELOPMENT/STAGING/PRODUCTION]
Version: 1.0

Features Verified:
✅ Create review
✅ Update review
✅ Delete review
✅ Mark helpful
✅ Rating summary
✅ Pagination
✅ Authorization
✅ Responsive design
✅ Form validation
✅ Data integrity
✅ Performance acceptable

Database Backup: [LOCATION]
Rollback Plan: Ready (backup available)
Monitoring: Logs checked regularly

Status: LIVE ✨
```

---

Status: **DEPLOYMENT READY** ✅

Gunakan checklist ini untuk memastikan deployment berjalan smooth dan semua fitur bekerja dengan baik!

