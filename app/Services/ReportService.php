<?php

namespace App\Services;

use Illuminate\Support\Str;

class ReportService
{
    /**
     * Generate Books Report HTML
     */
    public static function generateBooksReport($report_type = 'summary')
    {
        $books = \App\Models\Book::with('category')->orderBy('created_at', 'desc')->get();
        
        $html = self::getHeader('Laporan Data Buku');
        
        if ($report_type === 'summary') {
            $html .= self::generateBooksTableSummary($books);
        } else {
            $html .= self::generateBooksTableDetailed($books);
        }
        
        $html .= self::getFooter();
        
        return $html;
    }

    /**
     * Generate Users Report HTML
     */
    public static function generateUsersReport($report_type = 'summary')
    {
        $users = \App\Models\User::with('role')->orderBy('created_at', 'desc')->get();
        
        $html = self::getHeader('Laporan Data User');
        
        if ($report_type === 'summary') {
            $html .= self::generateUsersTableSummary($users);
        } else {
            $html .= self::generateUsersTableDetailed($users);
        }
        
        $html .= self::getFooter();
        
        return $html;
    }

    /**
     * Generate Reviews Report HTML
     */
    public static function generateReviewsReport($report_type = 'summary')
    {
        $reviews = \App\Models\Review::with('user', 'book')->orderBy('created_at', 'desc')->get();
        
        $html = self::getHeader('Laporan Ulasan & Rating');
        
        if ($report_type === 'summary') {
            $html .= self::generateReviewsTableSummary($reviews);
        } else {
            $html .= self::generateReviewsTableDetailed($reviews);
        }
        
        $html .= self::getFooter();
        
        return $html;
    }

    /**
     * Generate Borrowings Report HTML
     */
    public static function generateBorrowingsReport($report_type = 'summary')
    {
        $borrowings = \App\Models\Borrowing::with('user', 'book')->orderBy('borrowed_at', 'desc')->get();
        
        $html = self::getHeader('Laporan Peminjaman Buku');
        
        if ($report_type === 'summary') {
            $html .= self::generateBorrowingsTableSummary($borrowings);
        } else {
            $html .= self::generateBorrowingsTableDetailed($borrowings);
        }
        
        $html .= self::getFooter();
        
        return $html;
    }

    // ===== PRIVATE HELPER METHODS =====

    private static function getHeader($title)
    {
        $date = now()->format('d-m-Y H:i');
        
        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #8B5A2B;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #8B5A2B;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 12px;
        }
        
        .timestamp {
            text-align: right;
            font-size: 11px;
            color: #999;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        thead {
            background-color: #8B5A2B;
            color: white;
        }
        
        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: #f0e4c8;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-muted {
            color: #999;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 11px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .summary-stats {
            margin: 20px 0;
            padding: 15px;
            background-color: #f0e4c8;
            border-left: 4px solid #8B5A2B;
        }
        
        .summary-stats strong {
            color: #8B5A2B;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            a {
                color: inherit;
                text-decoration: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>$title</h1>
        <p>Perpustakaan Digital Koshouko</p>
    </div>
    
    <div class="timestamp">
        Tanggal Cetak: $date
    </div>
HTML;
    }

    private static function getFooter()
    {
        $year = date('Y');
        
        return <<<HTML
    <div class="footer">
        <p>&copy; $year Perpustakaan Digital Koshouko. Semua hak cipta dilindungi.</p>
        <p style="margin-top: 5px; font-size: 10px;">Dokumen ini dicetak secara otomatis oleh sistem dan berlaku sebagai dokumen resmi.</p>
    </div>
</body>
</html>
HTML;
    }

    private static function generateBooksTableSummary($books)
    {
        $html = '<div class="summary-stats"><strong>Total Buku:</strong> ' . $books->count() . ' | <strong>Tersedia:</strong> ' . $books->sum('available_copies') . ' | <strong>Total Stok:</strong> ' . $books->sum('total_copies') . '</div>';
        
        $html .= '<table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Judul</th>
                    <th style="width: 25%;">Penulis</th>
                    <th style="width: 15%;">Stok</th>
                    <th style="width: 20%;">Kategori</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($books as $key => $book) {
            $html .= '<tr>
                <td class="text-center">' . ($key + 1) . '</td>
                <td>' . htmlspecialchars(Str::limit($book->title, 50)) . '</td>
                <td>' . htmlspecialchars($book->author) . '</td>
                <td class="text-center">' . $book->available_copies . '/' . $book->total_copies . '</td>
                <td>' . htmlspecialchars($book->category->name) . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }

    private static function generateBooksTableDetailed($books)
    {
        $html = '<div class="summary-stats"><strong>Total Buku:</strong> ' . $books->count() . ' | <strong>Tersedia:</strong> ' . $books->sum('available_copies') . ' | <strong>Total Stok:</strong> ' . $books->sum('total_copies') . '</div>';
        
        $html .= '<table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 25%;">Judul</th>
                    <th style="width: 18%;">Penulis</th>
                    <th style="width: 12%;">Kategori</th>
                    <th style="width: 10%;">Stok</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 19%;">Penerbit</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($books as $key => $book) {
            $status_badge = $book->status === 'available' 
                ? '<span class="badge badge-success">Tersedia</span>'
                : ($book->status === 'unavailable' ? '<span class="badge badge-warning">Tidak Tersedia</span>' : '<span class="badge badge-danger">Archived</span>');
            
            $html .= '<tr>
                <td class="text-center">' . ($key + 1) . '</td>
                <td>' . htmlspecialchars(Str::limit($book->title, 40)) . '</td>
                <td>' . htmlspecialchars($book->author) . '</td>
                <td>' . htmlspecialchars($book->category->name) . '</td>
                <td class="text-center">' . $book->available_copies . '/' . $book->total_copies . '</td>
                <td class="text-center">' . $status_badge . '</td>
                <td>' . htmlspecialchars($book->publisher ?? '-') . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }

    private static function generateUsersTableSummary($users)
    {
        $html = '<div class="summary-stats"><strong>Total User:</strong> ' . $users->count() . ' | <strong>Admin:</strong> ' . $users->where('role.name', 'admin')->count() . ' | <strong>Petugas:</strong> ' . $users->where('role.name', 'pustakawan')->count() . ' | <strong>Anggota:</strong> ' . $users->where('role.name', 'member')->count() . '</div>';
        
        $html .= '<table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama</th>
                    <th style="width: 30%;">Email</th>
                    <th style="width: 15%;">Role</th>
                    <th style="width: 25%;">ID Member</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($users as $key => $user) {
            $html .= '<tr>
                <td class="text-center">' . ($key + 1) . '</td>
                <td>' . htmlspecialchars($user->name) . '</td>
                <td>' . htmlspecialchars($user->email) . '</td>
                <td>' . htmlspecialchars($user->role->name ?? '-') . '</td>
                <td class="text-center">' . htmlspecialchars($user->member_id) . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }

    private static function generateUsersTableDetailed($users)
    {
        $html = '<div class="summary-stats"><strong>Total User:</strong> ' . $users->count() . ' | <strong>Aktif:</strong> ' . $users->where('status', 'active')->count() . ' | <strong>Nonaktif:</strong> ' . $users->where('status', 'inactive')->count() . '</div>';
        
        $html .= '<table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 18%;">Nama</th>
                    <th style="width: 22%;">Email</th>
                    <th style="width: 12%;">Role</th>
                    <th style="width: 14%;">ID Member</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 18%;">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($users as $key => $user) {
            $status_badge = $user->status === 'active'
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Nonaktif</span>';
            
            $html .= '<tr>
                <td class="text-center">' . ($key + 1) . '</td>
                <td>' . htmlspecialchars($user->name) . '</td>
                <td>' . htmlspecialchars($user->email) . '</td>
                <td>' . htmlspecialchars($user->role->name ?? '-') . '</td>
                <td class="text-center">' . htmlspecialchars($user->member_id) . '</td>
                <td class="text-center">' . $status_badge . '</td>
                <td class="text-center">' . $user->created_at->format('d-m-Y') . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }

    private static function generateReviewsTableSummary($reviews)
    {
        $html = '<div class="summary-stats"><strong>Total Ulasan:</strong> ' . $reviews->count() . ' | <strong>Rating Rata-rata:</strong> ' . number_format($reviews->avg('star_rating'), 1) . ' / 5</div>';
        
        $html .= '<table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">User</th>
                    <th style="width: 30%;">Buku</th>
                    <th style="width: 12%;">Rating</th>
                    <th style="width: 33%;">Tanggal</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($reviews as $key => $review) {
            $html .= '<tr>
                <td class="text-center">' . ($key + 1) . '</td>
                <td>' . htmlspecialchars($review->user->name ?? '-') . '</td>
                <td>' . htmlspecialchars(Str::limit($review->book->title ?? '-', 40)) . '</td>
                <td class="text-center">⭐ ' . $review->star_rating . '</td>
                <td class="text-center">' . $review->created_at->format('d-m-Y H:i') . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }

    private static function generateReviewsTableDetailed($reviews)
    {
        $html = '<div class="summary-stats"><strong>Total Ulasan:</strong> ' . $reviews->count() . ' | <strong>Rating Rata-rata:</strong> ' . number_format($reviews->avg('star_rating'), 1) . ' / 5</div>';
        
        $html .= '<table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 15%;">User</th>
                    <th style="width: 22%;">Buku</th>
                    <th style="width: 8%;">Rating</th>
                    <th style="width: 20%;">Judul Ulasan</th>
                    <th style="width: 26%;">Isi Ulasan</th>
                    <th style="width: 5%;">Tanggal</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($reviews as $key => $review) {
            $html .= '<tr>
                <td class="text-center">' . ($key + 1) . '</td>
                <td>' . htmlspecialchars($review->user->name ?? '-') . '</td>
                <td>' . htmlspecialchars(Str::limit($review->book->title ?? '-', 25)) . '</td>
                <td class="text-center">⭐' . $review->star_rating . '</td>
                <td>' . htmlspecialchars(Str::limit($review->title ?? '-', 20)) . '</td>
                <td>' . htmlspecialchars(Str::limit($review->content ?? '-', 30)) . '</td>
                <td class="text-center" style="font-size: 10px;">' . $review->created_at->format('d-m-Y') . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }

    private static function generateBorrowingsTableSummary($borrowings)
    {
        $html = '<div class="summary-stats"><strong>Total Peminjaman:</strong> ' . $borrowings->count() . ' | <strong>Aktif:</strong> ' . $borrowings->whereIn('status', ['pending', 'approved'])->count() . ' | <strong>Selesai:</strong> ' . $borrowings->where('status', 'returned')->count() . '</div>';
        
        $html .= '<table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 18%;">Member</th>
                    <th style="width: 27%;">Buku</th>
                    <th style="width: 15%;">Tgl Pinjam</th>
                    <th style="width: 15%;">Tgl Kembali</th>
                    <th style="width: 20%;">Status</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($borrowings as $key => $borrowing) {
            $status_badge = match($borrowing->status) {
                'pending' => '<span class="badge badge-warning">Pending</span>',
                'approved' => '<span class="badge badge-success">Disetujui</span>',
                'returned' => '<span class="badge badge-success">Dikembalikan</span>',
                'overdue' => '<span class="badge badge-danger">Terlambat</span>',
                default => '<span class="badge">' . ucfirst($borrowing->status) . '</span>'
            };
            
            $html .= '<tr>
                <td class="text-center">' . ($key + 1) . '</td>
                <td>' . htmlspecialchars($borrowing->user->name ?? '-') . '</td>
                <td>' . htmlspecialchars(Str::limit($borrowing->book->title ?? '-', 40)) . '</td>
                <td class="text-center">' . $borrowing->borrowed_at->format('d-m-Y') . '</td>
                <td class="text-center">' . ($borrowing->returned_at ? $borrowing->returned_at->format('d-m-Y') : $borrowing->due_date->format('d-m-Y')) . '</td>
                <td class="text-center">' . $status_badge . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }

    private static function generateBorrowingsTableDetailed($borrowings)
    {
        $html = '<div class="summary-stats"><strong>Total Peminjaman:</strong> ' . $borrowings->count() . ' | <strong>Aktif:</strong> ' . $borrowings->whereIn('status', ['pending', 'approved'])->count() . ' | <strong>Terlambat:</strong> ' . $borrowings->where('status', 'overdue')->count() . '</div>';
        
        $html .= '<table>
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 12%;">Member</th>
                    <th style="width: 18%;">Buku</th>
                    <th style="width: 10%;">Pinjam</th>
                    <th style="width: 10%;">Kembali</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 12%;">Durasi</th>
                    <th style="width: 27%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($borrowings as $key => $borrowing) {
            $status_badge = match($borrowing->status) {
                'pending' => '<span class="badge badge-warning">Pending</span>',
                'approved' => '<span class="badge badge-success">Disetujui</span>',
                'returned' => '<span class="badge badge-success">Dikembalikan</span>',
                'overdue' => '<span class="badge badge-danger">Terlambat</span>',
                default => '<span class="badge">' . ucfirst($borrowing->status) . '</span>'
            };
            
            $borrowed_date = $borrowing->borrowed_at->format('d-m-Y');
            $return_date = $borrowing->returned_at ? $borrowing->returned_at->format('d-m-Y') : $borrowing->due_date->format('d-m-Y');
            $duration = $borrowing->borrowed_at->diffInDays($borrowing->returned_at ?? now());
            $notes = $borrowing->rejection_reason ? htmlspecialchars(Str::limit($borrowing->rejection_reason, 20)) : '-';
            
            $html .= '<tr>
                <td class="text-center">' . ($key + 1) . '</td>
                <td>' . htmlspecialchars($borrowing->user->name ?? '-') . '</td>
                <td>' . htmlspecialchars(Str::limit($borrowing->book->title ?? '-', 20)) . '</td>
                <td class="text-center" style="font-size: 10px;">' . $borrowed_date . '</td>
                <td class="text-center" style="font-size: 10px;">' . $return_date . '</td>
                <td class="text-center">' . $status_badge . '</td>
                <td class="text-center">' . $duration . ' hari</td>
                <td style="font-size: 10px;">' . $notes . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }
}
