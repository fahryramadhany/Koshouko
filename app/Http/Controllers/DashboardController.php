<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // short-circuit rendering problems in development/QA: fallback to a minimal dashboard
        try {
            // Eager load role to prevent additional database queries
            if (!$user->relationLoaded('role')) {
                $user->load('role');
            }

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->isPustakawan()) {
                return redirect()->route('librarian.dashboard');
            }

            // Member Dashboard
            $activeBorrowings = Borrowing::where('user_id', $user->id)
                ->whereIn('status', ['approved', 'overdue'])
                ->with('book')
                ->get();

            $upcomingDueDates = $activeBorrowings->sortBy('due_date')->take(5);
            $overdueBooks = $activeBorrowings->where('status', 'overdue');
            $bookmarkedBooks = $user->bookmarks()->with('book')->get();
            $totalBorrowedBooks = $activeBorrowings->count();

            // Get recommended books (latest 8 books)
            $recommendedBooks = Book::orderBy('created_at', 'desc')->limit(8)->get();

            return view('member.dashboard', [
                'activeBorrowings' => $activeBorrowings,
                'upcomingDueDates' => $upcomingDueDates,
                'overdueBooks' => $overdueBooks,
                'bookmarkedBooks' => $bookmarkedBooks,
                'totalBorrowedBooks' => $totalBorrowedBooks,
                'recommendedBooks' => $recommendedBooks,
            ]);

        } catch (\Throwable $e) {
            // log full exception for debugging and return a minimal dashboard so users can sign in
            \Log::error('Dashboard render failed: ' . $e->getMessage(), ['exception' => $e]);

            return view('member.dashboard_minimal', [
                'user' => $user,
                'errorMessage' => config('app.debug') ? $e->getMessage() : null,
            ]);
        }
    }

    public function profile()
    {
        $user = Auth::user();

        // Active borrowings
        $activeBorrowings = Borrowing::where('user_id', $user->id)
            ->where('status', 'approved')
            ->orWhere('status', 'overdue')
            ->with('book')
            ->get();

        $overdueBooks = $activeBorrowings->where('status', 'overdue');
        $bookmarkedBooks = $user->bookmarks()->with('book')->orderBy('created_at', 'desc')->get();
        $totalBorrowedBooks = $activeBorrowings->count();

        // Recent borrowings (last 10)
        $recentBorrowings = Borrowing::where('user_id', $user->id)
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('member.profile', [
            'totalBorrowedBooks' => $totalBorrowedBooks,
            'overdueBooks' => $overdueBooks,
            'bookmarkedBooks' => $bookmarkedBooks,
            'recentBorrowings' => $recentBorrowings,
        ]);
    }

    public function editProfile()
    {
        $user = Auth::user();

        return view('member.edit-profile', compact('user'));
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan',
            'date_of_birth.before' => 'Tanggal lahir tidak valid',
        ]);

        $user->update($validated);

        return redirect()->route('profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
