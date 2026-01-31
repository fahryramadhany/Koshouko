<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class QRGeneratorController extends Controller
{
    /**
     * Generate QR Code untuk Buku
     */
    public function generateBookQR($bookId)
    {
        $book = Book::findOrFail($bookId);
        $qrCode = "BOOK-{$book->id}";
        
        // Gunakan service QR code library
        return $this->generateQRImage($qrCode, $book->title);
    }

    /**
     * Generate QR Code untuk User (Member)
     */
    public function generateUserQR($userId)
    {
        $user = User::findOrFail($userId);
        $qrCode = "USER-{$user->id}";
        
        return $this->generateQRImage($qrCode, $user->name);
    }

    /**
     * Generate QR Image
     */
    private function generateQRImage($data, $label = '')
    {
        // Menggunakan API QR Code online
        $size = 300;
        $url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($data);
        
        header('Content-Type: image/png');
        echo file_get_contents($url);
        exit;
    }

    /**
     * Halaman Cetak QR Code Buku
     */
    public function printBookQR()
    {
        $books = Book::all();
        return view('admin.print-qr-books', compact('books'));
    }

    /**
     * Halaman Cetak QR Code Member
     */
    public function printMemberQR()
    {
        $users = User::where('role_id', 3)->get(); // Role 3 = Member
        return view('admin.print-qr-members', compact('users'));
    }
}
