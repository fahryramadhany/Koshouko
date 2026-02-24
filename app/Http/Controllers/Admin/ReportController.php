<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Generate Books Report
     */
    public function booksReport(Request $request)
    {
        $report_type = $request->input('report_type', 'summary');
        $html = ReportService::generateBooksReport($report_type);
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="Laporan-Buku-' . now()->format('d-m-Y-His') . '.html"');
    }

    /**
     * Generate Users Report
     */
    public function usersReport(Request $request)
    {
        $report_type = $request->input('report_type', 'summary');
        $html = ReportService::generateUsersReport($report_type);
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="Laporan-User-' . now()->format('d-m-Y-His') . '.html"');
    }

    /**
     * Generate Reviews Report
     */
    public function reviewsReport(Request $request)
    {
        $report_type = $request->input('report_type', 'summary');
        $html = ReportService::generateReviewsReport($report_type);
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="Laporan-Ulasan-' . now()->format('d-m-Y-His') . '.html"');
    }

    /**
     * Generate Borrowings Report
     */
    public function borrowingsReport(Request $request)
    {
        $report_type = $request->input('report_type', 'summary');
        $html = ReportService::generateBorrowingsReport($report_type);
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="Laporan-Peminjaman-' . now()->format('d-m-Y-His') . '.html"');
    }
}
