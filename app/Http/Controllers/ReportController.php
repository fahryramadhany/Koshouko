<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of reports
     */
    public function index()
    {
        $reports = Report::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new report
     */
    public function create()
    {
        $types = [
            'book_issue' => 'Masalah Buku',
            'account_issue' => 'Masalah Akun',
            'technical_issue' => 'Masalah Teknis',
            'other' => 'Lainnya'
        ];

        return view('member.reports.create', compact('types'));
    }

    /**
     * Store a newly created report
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:book_issue,account_issue,technical_issue,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ], [
            'type.required' => 'Tipe laporan harus dipilih',
            'title.required' => 'Judul laporan harus diisi',
            'title.max' => 'Judul maksimal 255 karakter',
            'description.required' => 'Deskripsi laporan harus diisi',
            'description.max' => 'Deskripsi maksimal 2000 karakter',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        Report::create($validated);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dibuat. Tim support akan menghubungi Anda segera.');
    }

    /**
     * Display a specific report
     */
    public function show(Report $report)
    {
        $this->authorize('view', $report);

        return view('member.reports.show', compact('report'));
    }

    /**
     * Show the form for editing a report (only for pending status)
     */
    public function edit(Report $report)
    {
        $this->authorize('update', $report);

        if ($report->status !== 'pending') {
            return redirect()->route('reports.show', $report)
                ->with('error', 'Laporan tidak dapat diubah setelah status berubah.');
        }

        $types = [
            'book_issue' => 'Masalah Buku',
            'account_issue' => 'Masalah Akun',
            'technical_issue' => 'Masalah Teknis',
            'other' => 'Lainnya'
        ];

        return view('member.reports.edit', compact('report', 'types'));
    }

    /**
     * Update a report
     */
    public function update(Request $request, Report $report)
    {
        $this->authorize('update', $report);

        if ($report->status !== 'pending') {
            return redirect()->route('reports.show', $report)
                ->with('error', 'Laporan tidak dapat diubah setelah status berubah.');
        }

        $validated = $request->validate([
            'type' => 'required|in:book_issue,account_issue,technical_issue,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ]);

        $report->update($validated);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan berhasil diubah.');
    }

    /**
     * Delete a report
     */
    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);

        if ($report->status !== 'pending') {
            return redirect()->route('reports.index')
                ->with('error', 'Hanya laporan dengan status "pending" yang dapat dihapus.');
        }

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
