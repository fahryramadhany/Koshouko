<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // Preview route for borrowing form (temporary - available only in local/debug)
    if (app()->environment('local') || config('app.debug')) {
        Route::get('/preview-borrowing-form', function() {
            $availableBooks = \App\Models\Book::where('available_copies', '>', 0)
                ->with('category')
                ->orderBy('title')
                ->get();
            
            // Create dummy user for preview
            $dummyUser = (object) [
                'id' => 1,
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'status' => 'active',
                'member_id' => 'MEM001'
            ];
            
            return view('member.borrowings.create', [
                'availableBooks' => $availableBooks,
                'user' => $dummyUser,
            ]);
        })->name('preview.borrowing.form');

        // Dev-only status route to help verify routing/host when using XAMPP or php artisan serve
        Route::get('/_dev/status', function () {
            return response()->json([
                'ok' => true,
                'app_url' => config('app.url'),
                'suggested_dashboard_urls' => [
                    url('/dashboard'),
                    url('/public/dashboard'),
                    request()->getSchemeAndHttpHost() . '/perpus_digit_laravel/public/dashboard'
                ],
                'routes_containing_dashboard' => collect(Route::getRoutes())->filter(fn($r) => str_contains($r->uri(), 'dashboard'))->map->uri()->values(),
            ]);
        })->name('dev.status');
    }
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

    // Reports
    Route::resource('reports', ReportController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

    // Books
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

    // Per-book borrow page (pre-fills the borrowing form for a specific book)
    Route::get('/books/{book}/borrow', [BorrowingController::class, 'createForBook'])->name('books.borrow');

    // Backwards-compatibility: accept POSTs to /books/{book}/borrow (some clients/scripts may still post here)
    // This will merge the route-model book id into the request and delegate to the normal store() handler.
    Route::post('/books/{book}/borrow', [BorrowingController::class, 'storeFromBook'])->name('books.borrow.store');

    Route::post('/books/{book}/bookmark', [BookController::class, 'toggleBookmark'])->name('books.bookmark');
    Route::delete('/bookmarks/{bookmark}', [BookController::class, 'deleteBookmark'])->name('bookmarks.destroy');

    // Reviews
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/{review}/helpful', [ReviewController::class, 'helpful'])->name('reviews.helpful');

    // Borrowing
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');

    // Backwards-compatible redirects (some links / docs use /member/borrowings/*)
    Route::get('/member/borrowings', function () { return redirect()->route('borrowings.index'); });
    Route::get('/member/borrowings/create', function () { return redirect()->route('borrowings.create'); });
    Route::get('/member/borrowings/{any}', function ($any) { return redirect()->route('borrowings.index'); })->where('any', '.*');
    Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('borrowings.return');
    Route::post('/borrowings/{borrowing}/renew', [BorrowingController::class, 'renew'])->name('borrowings.renew');

    // QR Scanner Routes - hanya untuk staff (admin dan pustakawan)
    Route::middleware('check.role:admin,pustakawan')->prefix('staff')->name('qr.')->group(function () {
        Route::get('/scanner-menu', function() {
            return view('staff.qr-menu');
        })->name('menu');
        Route::get('/scanner', 'App\Http\Controllers\QRScanController@index')->name('index');
        Route::post('/scanner/scan', 'App\Http\Controllers\QRScanController@scan')->name('scan');
        Route::post('/scanner/create-borrowing', 'App\Http\Controllers\QRScanController@createBorrowing')->name('create-borrowing');
        Route::post('/scanner/return-book', 'App\Http\Controllers\QRScanController@returnBook')->name('return-book');
        Route::get('/borrowing-history', 'App\Http\Controllers\QRScanController@history')->name('history');
    });

    // Admin Routes (ADMIN ONLY - Full Access)
    Route::middleware('check.role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/borrowings', [AdminController::class, 'borrowings'])->name('borrowings');
        Route::post('/borrowings/{borrowing}/approve', [AdminController::class, 'approveBorrowing'])->name('borrowings.approve');
        Route::post('/borrowings/{borrowing}/reject', [AdminController::class, 'rejectBorrowing'])->name('borrowings.reject');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/announcements', 'App\Http\Controllers\Admin\AnnouncementController@index')->name('announcements');
        Route::post('/announcements', 'App\Http\Controllers\Admin\AnnouncementController@store')->name('announcements.store');
        
        // Books Routes - includes category management
        Route::resource('books', 'App\Http\Controllers\Admin\BookController');
        Route::get('/books-categories', 'App\Http\Controllers\Admin\BookController@categories')->name('books.categories');
        Route::post('/books-categories', 'App\Http\Controllers\Admin\BookController@storeCategory')->name('books.categories.store');
        Route::get('/books-categories/{category}/edit', 'App\Http\Controllers\Admin\BookController@editCategory')->name('books.categories.edit');
        Route::put('/books-categories/{category}', 'App\Http\Controllers\Admin\BookController@updateCategory')->name('books.categories.update');
        Route::delete('/books-categories/{category}', 'App\Http\Controllers\Admin\BookController@destroyCategory')->name('books.categories.destroy');
        
        // Category routes for backward compatibility
        Route::resource('categories', 'App\Http\Controllers\Admin\CategoryController');
        
        // QR Code Generator Routes
        Route::get('/qr-code/print-books', 'App\Http\Controllers\Admin\QRGeneratorController@printBookQR')->name('qr.print-books');
        Route::get('/qr-code/print-members', 'App\Http\Controllers\Admin\QRGeneratorController@printMemberQR')->name('qr.print-members');
        Route::get('/qr-code/book/{book}', 'App\Http\Controllers\Admin\QRGeneratorController@generateBookQR')->name('qr.generate-book');
        Route::get('/qr-code/user/{user}', 'App\Http\Controllers\Admin\QRGeneratorController@generateUserQR')->name('qr.generate-user');
        
        // User Management Routes (ONLY ADMIN)
        Route::get('/users', 'App\Http\Controllers\Admin\UserController@index')->name('users.index');
        Route::get('/users/{user}/edit', 'App\Http\Controllers\Admin\UserController@edit')->name('users.edit');
        Route::put('/users/{user}', 'App\Http\Controllers\Admin\UserController@update')->name('users.update');
        Route::delete('/users/{user}', 'App\Http\Controllers\Admin\UserController@destroy')->name('users.destroy');
        Route::get('/users/create', 'App\Http\Controllers\Admin\UserController@create')->name('users.create');
        Route::post('/users', 'App\Http\Controllers\Admin\UserController@store')->name('users.store');
    });

    // Librarian Routes (PUSTAKAWAN ONLY - Limited Access)
    Route::middleware('check.role:pustakawan')->prefix('librarian')->name('librarian.')->group(function () {
        Route::get('/dashboard', 'App\Http\Controllers\Librarian\LibrarianDashboardController@dashboard')->name('dashboard');
        Route::get('/borrowings', 'App\Http\Controllers\Librarian\LibrarianDashboardController@borrowings')->name('borrowings');
        Route::post('/borrowings/{borrowing}/approve', 'App\Http\Controllers\Librarian\LibrarianDashboardController@approveBorrowing')->name('borrowings.approve');
        Route::post('/borrowings/{borrowing}/reject', 'App\Http\Controllers\Librarian\LibrarianDashboardController@rejectBorrowing')->name('borrowings.reject');
        Route::get('/reports', 'App\Http\Controllers\Librarian\LibrarianDashboardController@reports')->name('reports');
        Route::get('/announcements', 'App\Http\Controllers\Librarian\AnnouncementController@index')->name('announcements');
        Route::post('/announcements', 'App\Http\Controllers\Librarian\AnnouncementController@store')->name('announcements.store');
        
        // Books Routes - includes category management
        Route::resource('books', 'App\Http\Controllers\Librarian\BookController');
        Route::get('/books-categories', 'App\Http\Controllers\Librarian\BookController@categories')->name('books.categories');
        Route::post('/books-categories', 'App\Http\Controllers\Librarian\BookController@storeCategory')->name('books.categories.store');
        Route::get('/books-categories/{category}/edit', 'App\Http\Controllers\Librarian\BookController@editCategory')->name('books.categories.edit');
        Route::put('/books-categories/{category}', 'App\Http\Controllers\Librarian\BookController@updateCategory')->name('books.categories.update');
        Route::delete('/books-categories/{category}', 'App\Http\Controllers\Librarian\BookController@destroyCategory')->name('books.categories.destroy');
        
        // QR Code Generator Routes
        Route::get('/qr-code/print-books', 'App\Http\Controllers\Admin\QRGeneratorController@printBookQR')->name('qr.print-books');
        Route::get('/qr-code/print-members', 'App\Http\Controllers\Admin\QRGeneratorController@printMemberQR')->name('qr.print-members');
        Route::get('/qr-code/book/{book}', 'App\Http\Controllers\Admin\QRGeneratorController@generateBookQR')->name('qr.generate-book');
        Route::get('/qr-code/user/{user}', 'App\Http\Controllers\Admin\QRGeneratorController@generateUserQR')->name('qr.generate-user');
    });
});
