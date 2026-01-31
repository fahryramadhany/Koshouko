<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    // Show borrowing form
    public function create()
    {
        $user = Auth::user();
        
        // Get available books
        $availableBooks = Book::where('available_copies', '>', 0)
            ->with('category')
            ->orderBy('title')
            ->get();

        return view('member.borrowings.create', [
            'availableBooks' => $availableBooks,
            'user' => $user,
        ]);
    }

    // Show borrowing form for a specific book (URL: /books/{book}/borrow)
    public function createForBook(Book $book)
    {
        $user = Auth::user();

        // Load the usual available books list but ensure the requested book is present
        $availableBooks = Book::where('available_copies', '>', 0)
            ->with('category')
            ->orderBy('title')
            ->get();

        if (! $availableBooks->contains('id', $book->id)) {
            $availableBooks->push($book->load('category'));
        }

        return view('member.borrowings.create', [
            'availableBooks' => $availableBooks,
            'user' => $user,
            'selectedBookId' => $book->id,
        ]);
    }

    /**
     * Compatibility handler: accept POSTs to /books/{book}/borrow from legacy clients or cached JS.
     * Merges the route-model book id into the request and delegates to the canonical store() logic.
     */
    public function storeFromBook(Request $request, Book $book)
    {
        // merge book id so validation/store logic remains unchanged
        $request->merge(['book_id' => $book->id]);

        return $this->store($request);
    }

    public function index()
    {
        $user = Auth::user();

        $borrowings = Borrowing::where('user_id', $user->id)
            ->with('book')
            ->orderByDesc('borrowed_at')
            ->paginate(10);

        return view('member.borrowings.index', [
            'borrowings' => $borrowings,
        ]);
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'duration_days' => 'required|in:7,14,21,30',
            'due_date' => 'required|date',
            'special_request' => 'nullable|string|max:500',
            'agree_terms' => 'required|accepted',
            'agree_condition' => 'required|accepted',
            'agree_loss' => 'required|accepted',
        ], [
            'book_id.required' => 'Silakan pilih buku yang akan dipinjam',
            'book_id.exists' => 'Buku yang dipilih tidak valid',
            'duration_days.required' => 'Silakan pilih durasi peminjaman',
            'duration_days.in' => 'Durasi peminjaman tidak valid',
            'due_date.required' => 'Tanggal kembali harus diisi',
            'due_date.date' => 'Format tanggal tidak valid',
            'agree_terms.required' => 'Anda harus menyetujui syarat & ketentuan',
            'agree_condition.required' => 'Anda harus menyetujui kondisi buku',
            'agree_loss.required' => 'Anda harus setuju untuk membayar ganti rugi',
        ]);

        $user = Auth::user();
        $book = Book::findOrFail($validated['book_id']);

        // Check if book is available
        if ($book->available_copies <= 0) {
            return back()->with('error', 'Buku tidak tersedia untuk dipinjam saat ini.')->withInput();
        }

        // Check if user already borrowed this book (not yet returned)
        $existingBorrowing = Borrowing::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', '!=', 'returned')
            ->exists();

        if ($existingBorrowing) {
            return back()->with('error', 'Anda sudah meminjam buku ini. Silakan kembalikan terlebih dahulu.')->withInput();
        }

        // Check max active borrowings (limit 5 books)
        $activeBorrowings = Borrowing::where('user_id', $user->id)
            ->where('status', '!=', 'returned')
            ->where('status', '!=', 'rejected')
            ->count();

        if ($activeBorrowings >= 5) {
            return back()->with('error', 'Anda sudah mencapai batas maksimal peminjaman (5 buku). Kembalikan beberapa buku terlebih dahulu.')->withInput();
        }

        // Create borrowing record
        try {
            $borrowing = Borrowing::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'due_date' => Carbon::parse($validated['due_date']),
                'status' => 'pending',
                'special_request' => $validated['special_request'] ?? null,
                'duration_days' => (int) $validated['duration_days'],
            ]);

            // Decrease available copies
            $book->decrement('available_copies');

            return redirect()->route('borrowings.index')->with('success', 
                'Permintaan peminjaman berhasil dikirim! Silakan tunggu persetujuan dari pustakawan. Anda akan menerima notifikasi melalui email.'
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.')->withInput();
        }
    }

    public function return(Borrowing $borrowing, Request $request)
    {
        $this->authorize('own', $borrowing);

        if ($borrowing->status !== 'approved') {
            return back()->with('error', 'Peminjaman tidak dapat dikembalikan saat ini.');
        }

        $borrowing->update([
            'returned_at' => now(),
            'status' => 'returned',
        ]);

        // Increase available copies
        $borrowing->book->increment('available_copies');

        // Check if there's a fine
        if ($borrowing->isOverdue()) {
            $fine = $borrowing->fine;
            if (!$fine) {
                $daysOverdue = now()->diffInDays($borrowing->due_date);
                $fineAmount = $daysOverdue * 5000; // Rp 5000 per hari

                $fine = $borrowing->fine()->create([
                    'user_id' => $borrowing->user_id,
                    'amount' => $fineAmount,
                    'reason' => 'Denda keterlambatan pengembalian buku',
                ]);
            }
        }

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }

    public function renew(Borrowing $borrowing, Request $request)
    {
        $this->authorize('own', $borrowing);

        if ($borrowing->status !== 'approved' && $borrowing->status !== 'overdue') {
            return back()->with('error', 'Peminjaman tidak dapat diperpanjang saat ini.');
        }

        if ($borrowing->renewal_count >= 2) {
            return back()->with('error', 'Peminjaman hanya dapat diperpanjang maksimal 2 kali.');
        }

        $borrowing->update([
            'due_date' => $borrowing->due_date->addDays(7),
            'renewal_count' => $borrowing->renewal_count + 1,
        ]);

        return back()->with('success', 'Peminjaman berhasil diperpanjang selama 7 hari.');
    }
}
