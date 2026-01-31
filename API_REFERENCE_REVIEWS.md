# 📚 API REFERENCE - REVIEWS & RATING SYSTEM

Dokumentasi teknis lengkap untuk endpoints, models, dan controllers.

---

## 🔗 REST API ENDPOINTS

### 1. Create/Update Review
**Endpoint**: `POST /books/{book}/reviews`
**Name**: `reviews.store`

**Request Body**:
```json
{
    "rating": 5,
    "title": "Buku yang luar biasa",
    "content": "Saya sangat merekomendasikan buku ini. Ceritanya menarik dan penulisnya..."
}
```

**Validation**:
```php
'rating' => 'required|integer|min:1|max:5',
'title' => 'nullable|string|max:255',
'content' => 'required|string|max:1000',
```

**Response**:
```
Redirect to /books/{book}
With flash: success => "Ulasan berhasil dibuat/diperbarui"
```

**Behavior**:
- Jika user belum punya review untuk buku ini → **CREATE**
- Jika user sudah punya review → **UPDATE**
- Menggunakan `updateOrCreate()`

**Authentication**: Required (middleware `auth`)

**Authorization**: User bisa create untuk buku apapun

---

### 2. Update Review
**Endpoint**: `PUT /reviews/{review}`
**Name**: `reviews.update`

**Request Body**:
```json
{
    "rating": 4,
    "title": "Updated title",
    "content": "Updated content..."
}
```

**Validation**: Sama dengan create

**Response**:
```
Redirect to /books/{book}
With flash: success => "Ulasan berhasil diperbarui"
```

**Authorization**: Hanya owner review yang bisa update
```php
return $user->id === $review->user_id;
```

**Error Response**:
```
Status 403 - Unauthorized
Message: "Not authorized"
```

---

### 3. Delete Review
**Endpoint**: `DELETE /reviews/{review}`
**Name**: `reviews.destroy`

**Request Body**: None (atau `_method=DELETE` di form)

**Response**:
```
Redirect to /books/{book}
With flash: success => "Ulasan berhasil dihapus"
```

**Authorization**: Hanya owner review yang bisa delete

**Result**: Review soft-deleted (bisa di-restore)

---

### 4. Mark as Helpful
**Endpoint**: `POST /reviews/{review}/helpful`
**Name**: `reviews.helpful`

**Request Body**: None

**Action**:
- Increment `helpful_count` by 1
- Set `is_helpful` = true

**Response**:
```
Redirect back
With flash: success => "Terima kasih!"
```

**Behavior**: 
- Setiap klik tombol increment 1
- Tidak ada check apakah sudah di-vote
- User bisa vote multiple times

---

## 📦 MODELS & RELATIONSHIPS

### Review Model

```php
namespace App\Models;

class Review extends Model {
    use HasFactory, SoftDeletes;
    
    // Attributes
    protected $fillable = [
        'user_id', 'book_id', 'rating', 
        'title', 'content', 'is_helpful', 'helpful_count'
    ];
    
    protected $casts = [
        'rating' => 'integer',
        'helpful_count' => 'integer',
        'is_helpful' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function book() {
        return $this->belongsTo(Book::class);
    }
    
    // Accessors
    public function getFormattedRatingAttribute() {
        return match($this->rating) {
            5 => 'Sangat Memuaskan',
            4 => 'Memuaskan',
            3 => 'Cukup Baik',
            2 => 'Kurang Baik',
            1 => 'Tidak Memuaskan',
            default => '-'
        };
    }
    
    public function getStarRatingAttribute() {
        return str_repeat('⭐', $this->rating);
    }
}
```

**Key Points**:
- Soft deletes enabled
- Unique constraint: (user_id, book_id)
- Has timestamps (created_at, updated_at)
- Computed attributes: formatted_rating, star_rating

---

### User Model - New Relationship

```php
public function reviews() {
    return $this->hasMany(Review::class);
}
```

**Usage**:
```php
$user->reviews;           // All reviews by user
$user->reviews()->count(); // Total reviews count
$user->reviews()->with('book')->get(); // With book data
```

---

### Book Model - New Relationships & Accessors

```php
public function reviews() {
    return $this->hasMany(Review::class)
                ->orderBy('created_at', 'desc');
}

public function getAverageRatingAttribute() {
    return round($this->reviews()->avg('rating'), 1);
}

public function getRatingCountAttribute() {
    return $this->reviews()->count();
}

public function getRatingDistributionAttribute() {
    $distribution = [];
    for ($i = 5; $i >= 1; $i--) {
        $distribution[$i] = $this->reviews()
                                  ->where('rating', $i)
                                  ->count();
    }
    return $distribution;
}
```

**Usage**:
```php
$book->average_rating;      // 4.5
$book->rating_count;        // 12
$book->rating_distribution; // [5=>3, 4=>5, 3=>2, 2=>1, 1=>1]
```

**Important**: Accessors are computed on-the-fly, not cached
- For better performance, cache these:
```php
Cache::remember("book.{$book->id}.avg_rating", 3600, 
    fn() => $book->average_rating
);
```

---

## 🎮 CONTROLLER METHODS

### ReviewController

#### store(Request $request, Book $book)

```php
public function store(Request $request, Book $book) {
    $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'title' => 'nullable|string|max:255',
        'content' => 'required|string|max:1000',
    ]);

    $review = Review::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'book_id' => $book->id
        ],
        $validated
    );

    return redirect()
        ->route('books.show', $book)
        ->with('success', 'Ulasan berhasil dibuat/diperbarui');
}
```

**Logic Flow**:
1. Validate input
2. Check if review exists (by user_id + book_id)
3. If exists: UPDATE
4. If not exists: CREATE
5. Redirect with success message

**Error Handling**:
- Validation errors → back() with errors
- No explicit try-catch (Laravel default)

---

#### update(Request $request, Review $review)

```php
public function update(Request $request, Review $review) {
    $this->authorize('update', $review);
    
    $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'title' => 'nullable|string|max:255',
        'content' => 'required|string|max:1000',
    ]);

    $review->update($validated);

    return redirect()
        ->route('books.show', $review->book)
        ->with('success', 'Ulasan berhasil diperbarui');
}
```

**Authorization Check**:
- Calls `ReviewPolicy@update()`
- Throws `403 Unauthorized` if failed

---

#### destroy(Review $review)

```php
public function destroy(Review $review) {
    $this->authorize('delete', $review);
    $book = $review->book;
    
    $review->delete();

    return redirect()
        ->route('books.show', $book)
        ->with('success', 'Ulasan berhasil dihapus');
}
```

**Behavior**:
- Get book reference first (for redirect)
- Check authorization
- Soft-delete review
- Redirect to book detail

---

#### helpful(Review $review)

```php
public function helpful(Review $review) {
    $review->increment('helpful_count');
    
    return back()->with('success', 'Terima kasih!');
}
```

**Notes**:
- Increment database column directly
- No validation needed
- No authorization check
- Redirect back to previous page

---

## 🔒 AUTHORIZATION POLICY

### ReviewPolicy

```php
namespace App\Policies;

class ReviewPolicy {
    public function update(User $user, Review $review): bool {
        return $user->id === $review->user_id;
    }

    public function delete(User $user, Review $review): bool {
        return $user->id === $review->user_id;
    }
}
```

**Registration** (AppServiceProvider):
```php
protected $policies = [
    Review::class => ReviewPolicy::class,
];
```

**Usage in Controller**:
```php
$this->authorize('update', $review);  // Uses ReviewPolicy@update()
$this->authorize('delete', $review);  // Uses ReviewPolicy@delete()
```

**Error Handling**:
- Authorization fails → 403 Forbidden
- User not logged in → redirected to login

---

## 📊 DATABASE SCHEMA

### reviews Table

```sql
CREATE TABLE reviews (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id bigint unsigned NOT NULL,
    book_id bigint unsigned NOT NULL,
    rating tinyint unsigned NOT NULL,
    title varchar(255) NULL,
    content longtext NOT NULL,
    is_helpful boolean NOT NULL DEFAULT false,
    helpful_count int unsigned NOT NULL DEFAULT 0,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    deleted_at timestamp NULL,
    
    UNIQUE KEY unique_user_book (user_id, book_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    KEY (book_id),
    KEY (user_id),
    KEY (rating),
    KEY (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Indexes Explained**:
- `unique_user_book`: Enforce one review per user per book
- `user_id`: Fast lookup by user
- `book_id`: Fast lookup by book (primary for detail page)
- `rating`: Fast filter by star rating
- `created_at`: Fast sort by newest first

**Foreign Keys**:
- `ON DELETE CASCADE`: Delete reviews when user/book deleted
- Maintains referential integrity

---

## 🔗 ROUTES CONFIGURATION

In `routes/web.php`:

```php
use App\Http\Controllers\ReviewController;

Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store')
    ->middleware('auth');

Route::put('/reviews/{review}', [ReviewController::class, 'update'])
    ->name('reviews.update')
    ->middleware('auth');

Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
    ->name('reviews.destroy')
    ->middleware('auth');

Route::post('/reviews/{review}/helpful', [ReviewController::class, 'helpful'])
    ->name('reviews.helpful')
    ->middleware('auth');
```

**All endpoints require authentication** (middleware `auth`)

---

## 📄 VIEW INTEGRATION

### In book detail view:

```blade
<!-- Display reviews -->
@forelse ($reviews as $review)
    <div class="review">
        <p>{{ $review->user->name }} - {{ $review->star_rating }}</p>
        <p>{{ $review->content }}</p>
        <form action="{{ route('reviews.helpful', $review) }}" method="POST">
            @csrf
            <button type="submit">👍 {{ $review->helpful_count }}</button>
        </form>
    </div>
@empty
    <p>Belum ada review</p>
@endforelse

<!-- Pagination -->
{{ $reviews->links() }}
```

---

## 🧮 QUERY EXAMPLES

### Get book with reviews
```php
$book = Book::with('reviews.user')->find(1);
```

### Get average rating
```php
$avgRating = Review::where('book_id', 1)->avg('rating');
```

### Get reviews sorted by helpful
```php
$reviews = Review::where('book_id', 1)
                  ->orderBy('helpful_count', 'desc')
                  ->get();
```

### Get user's review for a book
```php
$review = Review::where('user_id', $userId)
                 ->where('book_id', $bookId)
                 ->first();
```

### Get rating distribution
```php
$distribution = Review::where('book_id', 1)
                       ->selectRaw('rating, COUNT(*) as count')
                       ->groupBy('rating')
                       ->pluck('count', 'rating');
```

### Get helpful reviews
```php
$helpful = Review::where('is_helpful', true)
                  ->orderBy('helpful_count', 'desc')
                  ->get();
```

---

## 🔄 WORKFLOW SEQUENCES

### Complete Create Review Flow

```
1. User navigates to /books/{id}
   ↓
2. BookController@show loads:
   - Book data
   - Reviews paginated
   - User's review (if any)
   - Average rating
   - Rating distribution
   ↓
3. View displays review form
   ↓
4. User fills form (rating required)
   ↓
5. Submit POST /books/{book}/reviews
   ↓
6. ReviewController@store validates
   ↓
7. Check if review exists (unique constraint)
   ↓
8. Create/Update via updateOrCreate()
   ↓
9. Redirect to /books/{book}
   ↓
10. Review now visible in "Ulasan Anda"
```

---

### Complete Edit Review Flow

```
1. User clicks "Edit" on their review
   ↓
2. View shows edit form (toggle visibility)
   ↓
3. User modifies rating/content
   ↓
4. Submit PUT /reviews/{review}
   ↓
5. ReviewPolicy checks ownership
   ↓
6. ReviewController@update validates & updates
   ↓
7. Redirect to /books/{book}
   ↓
8. Form hides, updated review displayed
```

---

## 📊 PERFORMANCE CONSIDERATIONS

### N+1 Query Problem
**Bad**:
```php
$reviews = Review::all();
foreach ($reviews as $review) {
    echo $review->user->name;  // Query per review!
}
```

**Good**:
```php
$reviews = Review::with('user')->get();
foreach ($reviews as $review) {
    echo $review->user->name;  // No extra queries
}
```

### Pagination Performance
```php
// In BookController@show
$reviews = $book->reviews()
                 ->with('user')
                 ->paginate(5);  // 5 per page
```

### Caching Ratings
```php
// Cache for 1 hour
$avgRating = Cache::remember(
    "book.{$book->id}.avg_rating",
    3600,
    fn() => $book->average_rating
);

// Invalidate on new review
// In ReviewController@store, after create:
Cache::forget("book.{$book->id}.avg_rating");
```

---

## 🧪 TESTING EXAMPLES

### Unit Test - Policy
```php
public function test_user_can_update_own_review() {
    $review = Review::factory()->create(['user_id' => 1]);
    $user = User::find(1);
    
    $this->assertTrue(
        app(ReviewPolicy::class)->update($user, $review)
    );
}

public function test_user_cannot_update_others_review() {
    $review = Review::factory()->create(['user_id' => 1]);
    $user = User::factory()->create(['id' => 2]);
    
    $this->assertFalse(
        app(ReviewPolicy::class)->update($user, $review)
    );
}
```

### Feature Test - Create Review
```php
public function test_user_can_create_review() {
    $user = User::factory()->create();
    $book = Book::factory()->create();
    
    $response = $this->actingAs($user)->post(
        route('reviews.store', $book),
        [
            'rating' => 5,
            'title' => 'Great book',
            'content' => 'I really enjoyed this book...'
        ]
    );
    
    $this->assertDatabaseHas('reviews', [
        'user_id' => $user->id,
        'book_id' => $book->id,
        'rating' => 5,
    ]);
}
```

---

## 🔍 DEBUGGING TIPS

### Check Review Data
```php
// In tinker console
php artisan tinker

$review = Review::find(1);
$review->user;              // User object
$review->book;              // Book object
$review->formatted_rating;  // "Sangat Memuaskan"
```

### Check Book Ratings
```php
$book = Book::find(1);
$book->average_rating;      // 4.5
$book->rating_count;        // 12
$book->rating_distribution; // [5=>3, 4=>5, ...]
```

### Check Policy
```php
// In code
$can_update = auth()->user()->can('update', $review);

// In tinker
$review = Review::find(1);
auth()->loginUsingId(1);
$user->can('update', $review); // true/false
```

### Log Queries
```php
// Enable in .env
DB_DEBUG=true

// Or in code
DB::listen(function($query) {
    dd($query->sql, $query->bindings);
});
```

---

## 🚀 OPTIMIZATION CHECKLIST

- [ ] Eager load relationships: `.with('user')`
- [ ] Use pagination: `.paginate(5)`
- [ ] Add indexes to: book_id, user_id, rating, created_at
- [ ] Cache expensive queries: average_rating
- [ ] Validate on server side (not just client)
- [ ] Use soft deletes (for data recovery)
- [ ] Implement authorization policies
- [ ] Set up proper error handling
- [ ] Use database transactions if needed
- [ ] Monitor slow queries: `laravel.log`

---

Status: **COMPLETE DOCUMENTATION** ✨

