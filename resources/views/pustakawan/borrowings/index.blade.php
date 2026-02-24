@extends('layouts.auth-app')

@section('title', 'Peminjaman - Perpustakaan Digital')
@section('page-title', '📋 Kelola Peminjaman')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/borrowings-fix.css') }}">

    <div class="borrowings-page-wrapper">
        <div class="content-container">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-koshouko-text-muted">Total Peminjaman: <strong class="text-koshouko-text text-lg">{{ $borrowings->total() }}</strong></p>
        </div>
        <button type="button" onclick="openReportModal('borrowings')" class="px-6 py-3 border-2 border-koshouko-wood text-koshouko-wood rounded-lg font-semibold transition text-center hover:bg-koshouko-cream">
            📄 Buat Laporan
        </button>
    </div>

    <!-- Search & Filter Bar -->
    <div class="gradient-card rounded-2xl p-4">
        <form action="{{ route('librarian.borrowings') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center">
            <!-- Search -->
            <input type="text" name="search" placeholder="Cari nama member atau judul buku..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border-2 border-koshouko-border rounded-lg bg-white text-koshouko-text placeholder-koshouko-text-muted focus:outline-none focus:border-koshouko-wood">

            <!-- Submit Button -->
            <button type="submit" class="px-4 py-2 btn-koshouko rounded-lg font-semibold transition">
                🔍 Cari
            </button>

            <!-- Status Filter -->
            <select name="status" class="px-4 py-2 border-2 border-koshouko-border rounded-lg bg-white text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
            </select>
            <!-- Reset Button -->
            @if(request('search') || request('status'))
                <a href="{{ route('librarian.borrowings') }}" class="px-4 py-2 border-2 border-koshouko-border text-koshouko-text rounded-lg font-semibold transition text-center hover:bg-koshouko-cream">
                    Reset
                </a>
            @endif
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
                                <div class="flex items-center gap-3">
                                    <!-- Book Cover Thumbnail -->
                                    <div class="w-12 h-16 bg-gradient-to-br from-koshouko-wood/10 to-koshouko-red/10 rounded flex items-center justify-center flex-shrink-0 border border-koshouko-border overflow-hidden">
                                        @if($borrowing->book->cover_image && file_exists(public_path($borrowing->book->cover_image)))
                                            <img src="{{ asset($borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl opacity-50">📖</span>
                                        @endif
                                    </div>
                                    <p class="font-semibold text-koshouko-text text-sm">{{ $borrowing->book->title }}</p>
                                </div>
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
                                    {{ $borrowing->status === 'pending_return' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $borrowing->status === 'returned' ? 'bg-blue-100 text-blue-700' : '' }}
                                ">
                                    @if($borrowing->status === 'pending_return')
                                        Menunggu Konfirmasi
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    @if($borrowing->status === 'pending')
                                        <form action="{{ route('librarian.borrowings.approve', $borrowing) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-700 hover:text-green-800 font-semibold text-xs">Setujui</button>
                                        </form>
                                        <button type="button" class="text-red-700 hover:text-red-800 font-semibold text-xs" onclick="showRejectModal({{ $borrowing->id }})">Tolak</button>
                                    @elseif($borrowing->status === 'pending_return')
                                        <form action="{{ route('borrowings.confirm-return', $borrowing) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-700 hover:text-green-800 font-semibold text-xs">Konfirmasi Kembali</button>
                                        </form>
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

    <!-- Pagination -->
    <div class="mt-6 content-container">
        {{ $borrowings->links() }}
    </div>
    </div>

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
    const route = "{{ route('librarian.borrowings.reject', ':id') }}".replace(':id', borrowingId);
    form.action = route;
    modal.classList.remove('hidden');
}

function closeRejectModal(event) {
    if (event && event.target.id !== 'rejectModal') return;
    document.getElementById('rejectModal').classList.add('hidden');
}

</script>

<!-- Report Modal -->
<div id="reportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="closeReportModal(event)">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96" onclick="event.stopPropagation()">
        <h3 class="text-lg font-bold text-koshouko-text mb-4">📄 Buat Laporan Peminjaman</h3>
        <form id="reportForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-koshouko-text mb-2">Jenis Laporan *</label>
                <select name="report_type" class="w-full px-3 py-2 border-2 border-koshouko-border rounded-lg bg-white text-koshouko-text focus:outline-none focus:border-koshouko-wood" required>
                    <option value="summary">Ringkasan (Member, Buku, Tanggal)</option>
                    <option value="detailed">Rinci (Lengkap dengan Status & Durasi Peminjaman)</option>
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2 border-2 border-koshouko-border rounded-lg text-koshouko-text font-semibold hover:bg-koshouko-cream transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-koshouko-wood text-white rounded-lg font-semibold hover:bg-koshouko-red transition">Cetak PDF</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function openReportModal(type) {
    const modal = document.getElementById('reportModal');
    modal.classList.remove('hidden');
}

function closeReportModal(event) {
    if (event && event.target.id !== 'reportModal') return;
    document.getElementById('reportModal').classList.add('hidden');
}

function generatePDFReport(url, reportType, filename) {
    console.log('Generating PDF...', {url, reportType, filename});
    
    // Get CSRF token properly
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                  document.querySelector('input[name="_token"]')?.value;
    
    if (!token) {
        alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
        return;
    }

    console.log('CSRF Token exists:', !!token);
    
    // Prepare form data
    const params = new URLSearchParams();
    params.append('report_type', reportType);
    params.append('_token', token);
    
    fetch(url, {
        method: 'POST',
        body: params,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP Error! Status: ${response.status}`);
        }
        return response.text();
    })
    .then(html => {
        console.log('HTML received, length:', html.length);
        if (!html || html.length === 0) {
            throw new Error('Respons kosong dari server');
        }
        
        // Create element for PDF conversion
        const element = document.createElement('div');
        element.innerHTML = html;
        element.style.padding = '20px';
        
        const opt = {
            margin: [10, 10, 10, 10],
            filename: filename + '-' + new Date().toISOString().split('T')[0] + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, allowTaint: true },
            jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
        };
        
        console.log('Memulai konversi PDF...');
        return html2pdf().set(opt).from(element).save();
    })
    .then(() => {
        console.log('PDF berhasil disimpan');
        closeReportModal();
    })
    .catch(error => {
        console.error('Error lengkap:', error);
        alert('Terjadi kesalahan: ' + error.message);
    });
}

// Setup form submission after DOM ready
document.addEventListener('DOMContentLoaded', function() {
    const reportForm = document.getElementById('reportForm');
    if (reportForm) {
        reportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const reportType = document.querySelector('select[name="report_type"]')?.value || 'summary';
            generatePDFReport('{{ route('librarian.borrowings.report') }}', reportType, 'Laporan-Peminjaman');
        });
    }
});
</script>
@endsection

