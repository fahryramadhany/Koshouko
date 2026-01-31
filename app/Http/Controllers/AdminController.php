<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class AdminController extends Controller
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

        return view('admin.dashboard', [
            'totalBooks' => $totalBooks,
            'totalMembers' => $totalMembers,
            'activeBorrowings' => $activeBorrowings,
            'pendingBorrowings' => $pendingBorrowings,
            'recentBorrowings' => $recentBorrowings,
        ]);
    }

    public function borrowings()
    {
        $borrowings = Borrowing::with('user', 'book')
            ->orderByDesc('borrowed_at')
            ->limit(15)
            ->get();

        return view('admin.borrowings.index', [
            'borrowings' => $borrowings,
        ]);
    }

    public function approveBorrowing(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses.');
        }

        // Generate QR code with borrowing information
        $qrData = "ID: {$borrowing->id} | Member: {$borrowing->user->name} | Book: {$borrowing->book->title} | Due: {$borrowing->due_date->format('Y-m-d')}";
        
        // Create QR code renderer
        $renderer = new ImageRenderer(
            new RendererStyle(200),
        );
        $writer = new Writer($renderer);
        
        // Create public/qr directory if not exists
        if (!is_dir(public_path('qr'))) {
            mkdir(public_path('qr'), 0755, true);
        }
        
        // Generate filename
        $filename = 'borrowing_' . $borrowing->id . '.png';
        $filepath = public_path('qr/' . $filename);
        
        // Write QR code to file
        $writer->writeFile($qrData, $filepath);
        
        // Save QR code path to database
        $borrowing->update([
            'status' => 'approved',
            'qr_code' => 'qr/' . $filename,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Peminjaman berhasil disetujui. QR code telah dibuat.');
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
        return view('admin.reports.index');
    }
}
