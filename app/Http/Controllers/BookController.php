<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $books = $query->paginate(12);
        $categories = \App\Models\Category::all();

        $user = Auth::user();
        $bookmarkedIds = $user->bookmarks()->pluck('book_id')->toArray();

        return view('member.books.index', [
            'books' => $books,
            'categories' => $categories,
            'bookmarkedIds' => $bookmarkedIds,
        ]);
    }

    public function show(Book $book)
    {
        $user = Auth::user();
        $isBookmarked = Bookmark::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->exists();

        $userBorrowings = $user->borrowings()
            ->where('book_id', $book->id)
            ->orderByDesc('borrowed_at')
            ->get();

        // Get reviews with user data
        $reviews = $book->reviews()->with('user')->paginate(5);
        
        // Get user's review if exists
        $userReview = $book->reviews()
            ->where('user_id', $user->id)
            ->first();

        // Get rating stats
        $averageRating = $book->average_rating;
        $totalReviews = $book->rating_count;
        $ratingDistribution = $book->rating_distribution;

        return view('member.books.show', [
            'book' => $book,
            'isBookmarked' => $isBookmarked,
            'userBorrowings' => $userBorrowings,
            'reviews' => $reviews,
            'userReview' => $userReview,
            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }

    public function toggleBookmark(Book $book, Request $request)
    {
        $user = Auth::user();

        $bookmark = Bookmark::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return back()->with('success', 'Bookmark dihapus dari daftar favorit');
        }

        Bookmark::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        return back()->with('success', 'Bookmark ditambahkan ke daftar favorit');
    }

    public function deleteBookmark(Bookmark $bookmark)
    {
        $this->authorize('delete', $bookmark);
        
        $bookmark->delete();
        return back()->with('success', 'Buku favorit dihapus');
    }
}
