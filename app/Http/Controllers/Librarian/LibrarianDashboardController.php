<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LibrarianDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $totalBooks = \App\Models\Book::count();
        $totalMembers = User::where('role_id', 3)->count();
        $activeBorrowings = Borrowing::where('status', 'approved')->orWhere('status', 'overdue')->count();
        $pendingBorrowings = Borrowing::where('status', 'pending')->count();

        $recentBorrowings = Borrowing::with('user', 'book')
            ->orderByDesc('borrowed_at')
            ->limit(10)
            ->get();

        return view('pustakawan.dashboard', [
            'totalBooks' => $totalBooks,
            'totalMembers' => $totalMembers,
            'activeBorrowings' => $activeBorrowings,
            'pendingBorrowings' => $pendingBorrowings,
            'recentBorrowings' => $recentBorrowings,
        ]);
    }

    public function borrowings(\Illuminate\Http\Request $request)
    {
        $query = Borrowing::with('user', 'book')->orderByDesc('borrowed_at');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by member name or book title
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                              ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhereHas('book', function($bookQuery) use ($search) {
                    $bookQuery->where('title', 'like', '%' . $search . '%');
                });
            });
        }

        $borrowings = $query->paginate(20);

        return view('pustakawan.borrowings.index', [
            'borrowings' => $borrowings,
        ]);
    }

    public function approveBorrowing(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses.');
        }

        // Update borrowing status to approved
        $borrowing->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function rejectBorrowing(Borrowing $borrowing, \Illuminate\Http\Request $request)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses.');
        }

        // Validate rejection reason
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Silakan berikan alasan penolakan',
            'rejection_reason.max' => 'Alasan penolakan maksimal 500 karakter',
        ]);

        // Increase available copies back
        $borrowing->book->increment('available_copies');

        $borrowing->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function reports()
    {
        return view('pustakawan.reports.index');
    }
}
