<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QRScanController extends Controller
{
    /**
     * Tampilkan halaman QR Scanner untuk Petugas
     */
    public function index()
    {
        // Hanya untuk admin dan pustakawan
        if (!auth()->user() || !in_array(auth()->user()->role_id, [1, 2])) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        $recentBorrowings = Borrowing::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('staff.qr-scanner', compact('recentBorrowings'));
    }

    /**
     * Process QR/Barcode Scanner
     */
    public function scan(Request $request)
    {
        try {
            $qrCode = $request->input('qr_code');
            
            if (!$qrCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak boleh kosong'
                ], 422);
            }

            // Format QR Code: BOOK-{book_id} atau USER-{user_id}
            $parts = explode('-', $qrCode);
            
            if (count($parts) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format QR Code tidak valid'
                ], 422);
            }

            $type = strtoupper($parts[0]);
            $id = $parts[1];

            if ($type === 'BOOK') {
                return $this->handleBookScan($id);
            } elseif ($type === 'USER') {
                return $this->handleUserScan($id);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe QR Code tidak dikenali'
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Book QR Scan
     */
    private function handleBookScan($bookId)
    {
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        // Check apakah buku sudah dipinjam
        $activeBorrowing = Borrowing::where('book_id', $bookId)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($activeBorrowing) {
            return response()->json([
                'success' => false,
                'message' => 'Buku ini sedang dipinjam oleh ' . $activeBorrowing->user->name,
                'data' => [
                    'type' => 'book',
                    'book' => $book,
                    'borrowing' => $activeBorrowing
                ]
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Buku ditemukan',
            'data' => [
                'type' => 'book',
                'book' => $book,
                'action' => 'select_member' // Tunggu pilihan member
            ]
        ]);
    }

    /**
     * Handle User QR Scan
     */
    private function handleUserScan($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan'
            ], 404);
        }

        // Get user's active borrowings
        $activeBorrowings = Borrowing::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->with('book')
            ->get();

        // Check denda
        $fines = Fine::where('user_id', $userId)
            ->where('paid', false)
            ->sum('amount');

        return response()->json([
            'success' => true,
            'message' => 'Member ditemukan',
            'data' => [
                'type' => 'member',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? 'N/A'
                ],
                'active_borrowings' => $activeBorrowings->map(function ($b) {
                    return [
                        'id' => $b->id,
                        'book_title' => $b->book->title,
                        'borrowed_at' => $b->borrowed_at->format('d/m/Y H:i'),
                        'due_date' => $b->due_date->format('d/m/Y'),
                        'status' => $b->status,
                        'is_overdue' => $b->isOverdue()
                    ];
                }),
                'unpaid_fines' => $fines,
                'can_borrow' => count($activeBorrowings) < 5 && $fines == 0
            ]
        ]);
    }

    /**
     * Create Borrowing Record
     */
    public function createBorrowing(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id'
            ]);

            $user = User::find($request->user_id);
            $book = Book::find($request->book_id);

            // Check apakah user bisa meminjam
            $activeBorrowings = Borrowing::where('user_id', $request->user_id)
                ->whereIn('status', ['pending', 'approved'])
                ->count();

            if ($activeBorrowings >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'User sudah mencapai batas maksimal peminjaman (5 buku)'
                ], 422);
            }

            // Check denda
            $unpaidFines = Fine::where('user_id', $request->user_id)
                ->where('paid', false)
                ->exists();

            if ($unpaidFines) {
                return response()->json([
                    'success' => false,
                    'message' => 'User memiliki denda yang belum dibayar'
                ], 422);
            }

            // Buat borrowing record
            $borrowing = Borrowing::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'borrowed_at' => now(),
                'due_date' => now()->addDays(14),
                'status' => 'approved', // Auto-approve saat scan
                'renewal_count' => 0,
                'notes' => 'Peminjaman melalui QR Scanner - ' . now()->format('d/m/Y H:i')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil dicatat',
                'data' => [
                    'borrowing_id' => $borrowing->id,
                    'user_name' => $user->name,
                    'book_title' => $book->title,
                    'borrowed_at' => $borrowing->borrowed_at->format('d/m/Y H:i'),
                    'due_date' => $borrowing->due_date->format('d/m/Y')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Return Book
     */
    public function returnBook(Request $request)
    {
        try {
            $request->validate([
                'borrowing_id' => 'required|exists:borrowings,id'
            ]);

            $borrowing = Borrowing::find($request->borrowing_id);

            if ($borrowing->status === 'returned') {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku ini sudah dikembalikan'
                ], 422);
            }

            // Check apakah overdue dan create fine jika diperlukan
            $daysOverdue = 0;
            if ($borrowing->due_date < now()) {
                $daysOverdue = now()->diffInDays($borrowing->due_date);
                $fineAmount = $daysOverdue * 5000; // Rp 5000 per hari

                Fine::create([
                    'user_id' => $borrowing->user_id,
                    'borrowing_id' => $borrowing->id,
                    'amount' => $fineAmount,
                    'reason' => 'Keterlambatan pengembalian buku',
                    'paid' => false
                ]);
            }

            // Update borrowing status
            $borrowing->update([
                'returned_at' => now(),
                'status' => 'returned',
                'notes' => ($borrowing->notes ?? '') . ' | Dikembalikan: ' . now()->format('d/m/Y H:i')
            ]);

            return response()->json([
                'success' => true,
                'message' => $daysOverdue > 0 ? 
                    "Buku dikembalikan. Denda Rp " . ($daysOverdue * 5000) . " untuk " . $daysOverdue . " hari keterlambatan" :
                    'Buku berhasil dikembalikan tepat waktu',
                'data' => [
                    'borrowing_id' => $borrowing->id,
                    'user_name' => $borrowing->user->name,
                    'book_title' => $borrowing->book->title,
                    'days_overdue' => $daysOverdue,
                    'fine_amount' => $daysOverdue * 5000
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Borrowing History
     */
    public function history(Request $request)
    {
        $query = Borrowing::with(['user', 'book'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('borrowed_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('borrowed_at', '<=', $request->date_to);
        }

        $borrowings = $query->paginate(20);

        return view('staff.borrowing-history', compact('borrowings'));
    }
}
