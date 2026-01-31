@extends('layouts.auth-app')

@section('title', 'Riwayat Peminjaman - Perpustakaan Digital')
@section('page-title', '📋 Riwayat Peminjaman')

@section('content')
<style>
    .filter-section {
        background: #fff;
        padding: 1.5rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .filter-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .filter-input {
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        font-size: 1rem;
    }

    .filter-input:focus {
        outline: none;
        border-color: #8B5A2B;
        box-shadow: 0 0 0 3px rgba(139, 90, 43, 0.1);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .btn-primary {
        background: #8B5A2B;
        color: white;
    }

    .btn-primary:hover {
        background: #6b4423;
        box-shadow: 0 4px 12px rgba(139, 90, 43, 0.3);
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #333;
    }

    .btn-secondary:hover {
        background: #d0d0d0;
    }

    .borrowing-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .borrowing-table thead {
        background: #8B5A2B;
        color: white;
    }

    .borrowing-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #6b4423;
    }

    .borrowing-table td {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .borrowing-table tbody tr:hover {
        background: #f9f9f9;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-approved {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-returned {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .overdue-badge {
        background: #dc3545;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        margin-left: 0.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #8B5A2B;
        margin: 0.5rem 0;
    }

    .stat-label {
        color: #666;
        font-size: 0.95rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .pagination a,
    .pagination .page-item {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        text-decoration: none;
        color: #333;
        transition: all 0.3s ease;
    }

    .pagination a:hover,
    .pagination .active {
        background: #8B5A2B;
        color: white;
        border-color: #8B5A2B;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #999;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-success:hover {
        background: #218838;
    }

    @media (max-width: 768px) {
        .borrowing-table {
            font-size: 0.9rem;
        }

        .borrowing-table th,
        .borrowing-table td {
            padding: 0.75rem;
        }

        .filter-group {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container mx-auto px-4 py-8">
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <p class="stat-label">Total Peminjaman</p>
            <p class="stat-number">{{ $borrowings->total() }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Sedang Dipinjam</p>
            <p class="stat-number" style="color: #ffc107;">{{ $borrowings->where('status', 'approved')->count() }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Sudah Dikembalikan</p>
            <p class="stat-number" style="color: #17a2b8;">{{ $borrowings->where('status', 'returned')->count() }}</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Menunggu Persetujuan</p>
            <p class="stat-number" style="color: #6c757d;">{{ $borrowings->where('status', 'pending')->count() }}</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <h3 style="margin: 0 0 1.5rem 0; color: #333;">🔍 Filter & Pencarian</h3>
        <form method="GET" action="{{ route('qr.history') }}" class="filter-form">
            <div class="filter-group">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Status:</label>
                    <select name="status" class="filter-input">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Sedang Dipinjam</option>
                        <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Dari Tanggal:</label>
                    <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Sampai Tanggal:</label>
                    <input type="date" name="date_to" class="filter-input" value="{{ request('date_to') }}">
                </div>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">🔍 Cari</button>
                <a href="{{ route('qr.history') }}" class="btn btn-secondary">↻ Reset</a>
            </div>
        </form>
    </div>

    <!-- Borrowings Table -->
    @if($borrowings->count() > 0)
        <div style="overflow-x: auto;">
            <table class="borrowing-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Member</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowings as $index => $borrowing)
                        <tr>
                            <td>{{ ($borrowings->currentPage() - 1) * $borrowings->perPage() + $index + 1 }}</td>
                            <td>
                                <strong>{{ $borrowing->user->name }}</strong><br>
                                <small style="color: #666;">{{ $borrowing->user->email }}</small>
                            </td>
                            <td>
                                <strong>{{ $borrowing->book->title }}</strong><br>
                                <small style="color: #666;">{{ $borrowing->book->author }}</small>
                            </td>
                            <td>{{ $borrowing->borrowed_at->format('d/m/Y H:i') }}</td>
                            <td>
                                {{ $borrowing->due_date->format('d/m/Y') }}
                                @if($borrowing->isOverdue() && $borrowing->status === 'approved')
                                    <span class="overdue-badge">⚠️ Terlambat {{ $borrowing->days_overdue }} hari</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ $borrowing->status }}">
                                    {{ ucfirst($borrowing->status) }}
                                </span>
                            </td>
                            <td>
                                @if($borrowing->status === 'approved')
                                    <form method="POST" action="{{ route('borrowings.return', $borrowing->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-small btn-success" onclick="return confirm('Konfirmasi pengembalian buku?')">
                                            ✓ Terima Kembali
                                        </button>
                                    </form>
                                @elseif($borrowing->status === 'pending')
                                    <form method="POST" action="{{ route('admin.borrowings.approve', $borrowing->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-small btn-success" onclick="return confirm('Setujui peminjaman?')">
                                            ✓ Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.borrowings.reject', $borrowing->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Tolak peminjaman?')">
                                            ✕ Tolak
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($borrowings->hasPages())
            <div class="pagination">
                {{ $borrowings->render() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <p style="font-size: 3rem; margin: 0;">📚</p>
            <p style="font-size: 1.2rem; margin: 1rem 0 0 0;">Belum ada riwayat peminjaman</p>
        </div>
    @endif
</div>

@endsection
