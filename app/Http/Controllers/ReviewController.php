<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review
     */
    public function store(Request $request, Book $book)
    {
        $user = Auth::user();

        // Check if user already reviewed this book
        $existingReview = Review::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|max:1000',
        ], [
            'rating.required' => 'Rating harus dipilih',
            'rating.min' => 'Rating minimal 1 bintang',
            'rating.max' => 'Rating maksimal 5 bintang',
            'content.required' => 'Ulasan harus diisi',
            'content.max' => 'Ulasan maksimal 1000 karakter',
        ]);

        if ($existingReview) {
            // Update existing review
            $existingReview->update($validated);
            $message = 'Ulasan berhasil diperbarui';
        } else {
            // Create new review
            $validated['user_id'] = $user->id;
            $validated['book_id'] = $book->id;
            Review::create($validated);
            $message = 'Ulasan berhasil ditambahkan';
        }

        return redirect()->route('books.show', $book)
            ->with('success', $message);
    }

    /**
     * Update a review
     */
    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|max:1000',
        ]);

        $review->update($validated);

        return redirect()->route('books.show', $review->book)
            ->with('success', 'Ulasan berhasil diperbarui');
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $book = $review->book;
        $review->delete();

        return redirect()->route('books.show', $book)
            ->with('success', 'Ulasan berhasil dihapus');
    }

    /**
     * Mark review as helpful
     */
    public function helpful(Review $review)
    {
        $user = Auth::user();

        $review->increment('helpful_count');
        $review->update(['is_helpful' => true]);

        return back()->with('success', 'Terima kasih atas penilaian Anda');
    }
}
