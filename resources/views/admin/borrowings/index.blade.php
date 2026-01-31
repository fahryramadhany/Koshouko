@extends('layouts.auth-app')

@section('title', 'Peminjaman - Perpustakaan Digital')
@section('page-title', '📋 Kelola Peminjaman')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <!-- Filter -->
    <div class="mb-6 gradient-card rounded-2xl p-4 flex flex-col sm:flex-row gap-4">
        <form action="{{ route('admin.borrowings') }}" method="GET" class="flex gap-2 flex-1">
            <select name="status" class="px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
            </select>
            <button type="submit" class="px-4 py-2 btn-koshouko rounded-lg font-semibold transition">
                Filter
            </button>
        </form>
    </div>

    <div class="gradient-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="section-header">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Member</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Buku</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Pinjam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Kembali</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-koshouko-text uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-koshouko-border">
                    @forelse($borrowings as $borrowing)
                        <tr class="hover:bg-koshouko-cream/50 transition">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-koshouko-text text-sm">{{ $borrowing->user->name }}</p>
                                <p class="text-xs text-koshouko-text-muted">{{ $borrowing->user->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-koshouko-text text-sm">{{ $borrowing->book->title }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-koshouko-text-muted">{{ $borrowing->borrowed_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-koshouko-text-muted">
                                {{ $borrowing->due_date->format('d M Y') }}
                                @if($borrowing->isOverdue() && $borrowing->status !== 'returned')
                                    <span class="text-xs text-red-700 block font-semibold">⚠️ Terlambat</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $borrowing->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $borrowing->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $borrowing->status === 'overdue' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $borrowing->status === 'returned' ? 'bg-blue-100 text-blue-700' : '' }}
                                ">
                                    {{ ucfirst($borrowing->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    @if($borrowing->status === 'pending')
                                        <form action="{{ route('admin.borrowings.approve', $borrowing) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-700 hover:text-green-800 font-semibold text-xs">Setujui</button>
                                        </form>
                                        <button type="button" class="text-red-700 hover:text-red-800 font-semibold text-xs" onclick="showRejectModal({{ $borrowing->id }})">Tolak</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-koshouko-text-muted">Tidak ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Removed for Performance Testing -->

<!-- Rejection Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="closeRejectModal(event)">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96" onclick="event.stopPropagation()">
        <h3 class="text-lg font-bold text-koshouko-text mb-4">Tolak Peminjaman</h3>
        <form id="rejectForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-koshouko-text mb-2">Alasan Penolakan *</label>
                <textarea name="rejection_reason" class="w-full px-3 py-2 border-2 border-koshouko-border rounded-lg bg-white text-koshouko-text focus:outline-none focus:border-koshouko-wood" rows="4" placeholder="Jelaskan alasan penolakan peminjaman ini..." required></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border-2 border-koshouko-border rounded-lg text-koshouko-text font-semibold hover:bg-koshouko-cream transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(borrowingId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const route = "{{ route('admin.borrowings.reject', ':id') }}".replace(':id', borrowingId);
    form.action = route;
    modal.classList.remove('hidden');
}

function closeRejectModal(event) {
    if (event && event.target.id !== 'rejectModal') return;
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection

