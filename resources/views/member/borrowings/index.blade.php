@extends('layouts.auth-app')

@section('title', 'Riwayat Peminjaman - Perpustakaan Digital')
@section('page-title', '📋 Riwayat Peminjaman')

@section('content')
<link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">

    <div class="max-w-6xl mx-auto">
        <!-- Tabs -->
        <div class="flex gap-4 mb-8 border-b-2 border-koshouko-border">
            <button onclick="switchTab('all')" class="tab-btn px-6 py-3 font-semibold text-koshouko-text-muted border-b-2 border-transparent hover:border-koshouko-wood/50 transition active" data-tab="all">
                Semua Peminjaman
            </button>
            <button onclick="switchTab('active')" class="tab-btn px-6 py-3 font-semibold text-koshouko-text-muted border-b-2 border-transparent hover:border-koshouko-wood/50 transition" data-tab="active">
                Sedang Dipinjam
            </button>
            <button onclick="switchTab('returned')" class="tab-btn px-6 py-3 font-semibold text-koshouko-text-muted border-b-2 border-transparent hover:border-koshouko-wood/50 transition" data-tab="returned">
                Sudah Dikembalikan
            </button>
        </div>

        @if($borrowings->count() > 0)
            <div class="space-y-4">
                @foreach($borrowings as $borrowing)
                    <div class="gradient-card rounded-2xl overflow-hidden hover:shadow-2xl transition" data-status="{{ $borrowing->status }}">
                        <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                            <!-- Book Cover & Info -->
                            <div class="flex-1">
                                <div class="flex gap-4">
                                    <!-- Book Cover -->
                                    <div class="w-24 h-32 bg-gradient-to-br from-koshouko-wood/10 to-koshouko-red/10 rounded-lg flex items-center justify-center flex-shrink-0 border border-koshouko-border overflow-hidden">
                                        @if($borrowing->book->cover_image && file_exists(public_path($borrowing->book->cover_image)))
                                            <img src="{{ asset($borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-4xl opacity-50">📖</span>
                                        @endif
                                    </div>

                                    <!-- Details -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-koshouko-text">{{ $borrowing->book->title }}</h3>
                                        <p class="text-koshouko-text-muted mb-3">{{ $borrowing->book->author }}</p>

                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <p class="text-koshouko-text-muted">Tanggal Pinjam</p>
                                                <p class="font-semibold text-koshouko-text">{{ $borrowing->borrowed_at->format('d M Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-koshouko-text-muted">Tanggal Kembali</p>
                                                <p class="font-semibold text-koshouko-text">{{ $borrowing->due_date->format('d M Y') }}</p>
                                                @if($borrowing->status === 'approved' || $borrowing->status === 'overdue')
                                                    <p class="text-xs {{ $borrowing->isOverdue() ? 'text-red-600' : 'text-green-700' }} mt-1 font-semibold">
                                                        {{ $borrowing->isOverdue() ? '⚠️ Terlambat ' . $borrowing->days_overdue . ' hari' : '✓ ' . $borrowing->due_date->diffForHumans() }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-koshouko-text-muted">Status</p>
                                                <div class="mt-1">
                                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                                        {{ $borrowing->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                        {{ $borrowing->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                                        {{ $borrowing->status === 'overdue' ? 'bg-red-100 text-red-700' : '' }}
                                                        {{ $borrowing->status === 'pending_return' ? 'bg-blue-100 text-blue-700' : '' }}
                                                        {{ $borrowing->status === 'returned' ? 'bg-blue-100 text-blue-700' : '' }}
                                                        {{ $borrowing->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                                    ">
                                                        @if($borrowing->status === 'pending_return')
                                                            Menunggu Konfirmasi
                                                        @else
                                                            {{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        @if($borrowing->renewal_count > 0)
                                            <p class="text-xs text-koshouko-text-muted mt-3">
                                                📌 Sudah diperpanjang {{ $borrowing->renewal_count }}/2 kali
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col gap-2 md:flex-row">
                                @if($borrowing->status === 'pending')
                                    <div class="px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg text-sm font-semibold text-center border border-yellow-200">
                                        ⏳ Menunggu Persetujuan
                                    </div>
                                @elseif($borrowing->status === 'approved' || $borrowing->status === 'overdue')
                                    <a href="{{ route('borrowings.receipt', $borrowing) }}" target="_blank" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg font-semibold transition text-sm border border-green-200 text-center">
                                        📄 QR & Bukti
                                    </a>
                                    <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 btn-koshouko rounded-lg font-semibold transition text-sm">
                                            Kembalikan
                                        </button>
                                    </form>

                                    @if($borrowing->renewal_count < 2)
                                        <form action="{{ route('borrowings.renew', $borrowing) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-koshouko-cream to-koshouko-wood text-koshouko-text rounded-lg font-semibold hover:shadow-lg transition text-sm border border-koshouko-border">
                                                Perpanjang
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="px-4 py-2 bg-koshouko-border text-koshouko-text-muted rounded-lg font-semibold cursor-not-allowed text-sm border border-koshouko-border">
                                            Tidak Bisa Perpanjang
                                        </button>
                                    @endif
                                @elseif($borrowing->status === 'pending_return')
                                    <div class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold text-center border border-blue-200">
                                        ⏳ Menunggu Konfirmasi Pengembalian
                                    </div>
                                @elseif($borrowing->status === 'returned')
                                    <div class="px-4 py-2 bg-green-50 text-green-700 rounded-lg text-sm font-semibold border border-green-200">
                                        ✓ Sudah Dikembalikan
                                    </div>
                                    @if($borrowing->returned_at)
                                        <p class="text-xs text-koshouko-text-muted px-2">
                                            Tanggal: {{ $borrowing->returned_at->format('d M Y') }}
                                        </p>
                                    @endif
                                @elseif($borrowing->status === 'rejected')
                                    <div class="px-4 py-2 bg-red-50 text-red-700 rounded-lg text-sm font-semibold border border-red-200">
                                        ✗ Ditolak
                                    </div>
                                    @if($borrowing->rejection_reason)
                                        <p class="text-xs text-red-600 px-2">
                                            Alasan: {{ $borrowing->rejection_reason }}
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Fine Info (jika ada) -->
                        @if($borrowing->fine)
                            <div class="px-6 py-4 bg-red-50 border-t-2 border-red-200 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-red-700">💰 Denda Keterlambatan</p>
                                    <p class="text-xs text-red-600">{{ $borrowing->fine->reason ?? 'Pengembalian terlambat' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-red-700">Rp {{ number_format($borrowing->fine->amount, 0) }}</p>
                                    <p class="text-xs text-red-600">Status: {{ ucfirst($borrowing->fine->status) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $borrowings->links() }}
            </div>
        @else
            <div class="gradient-card rounded-2xl p-12 text-center">
                <p class="text-5xl mb-4">📚</p>
                <h3 class="text-2xl font-bold text-koshouko-text mb-2">Tidak Ada Riwayat Peminjaman</h3>
                <p class="text-koshouko-text-muted mb-6">Anda belum meminjam buku apapun. Jelajahi katalog kami sekarang!</p>
                <a href="{{ route('books.index') }}" class="inline-block px-6 py-3 btn-koshouko rounded-lg font-semibold transition">
                    Jelajahi Katalog Buku
                </a>
            </div>
        @endif
    </div>

    <script>
        function switchTab(tab) {
            const allItems = document.querySelectorAll('[data-status]');
            const buttons = document.querySelectorAll('.tab-btn');

            // Update button styles
            buttons.forEach(btn => {
                btn.classList.remove('active', 'border-primary');
                btn.classList.add('border-transparent');
            });
            document.querySelector(`[data-tab="${tab}"]`).classList.add('active', 'border-primary');

            // Filter items
            if (tab === 'all') {
                allItems.forEach(item => item.style.display = 'block');
            } else if (tab === 'active') {
                allItems.forEach(item => {
                    const status = item.getAttribute('data-status');
                    item.style.display = (status === 'pending' || status === 'approved' || status === 'overdue') ? 'block' : 'none';
                });
            } else if (tab === 'returned') {
                allItems.forEach(item => {
                    const status = item.getAttribute('data-status');
                    item.style.display = (status === 'returned' || status === 'rejected') ? 'block' : 'none';
                });
            }
        }

        function showQRModal(qrUrl) {
            const modal = document.getElementById('qrModal');
            const qrImage = document.getElementById('qrImage');
            qrImage.src = qrUrl;
            modal.classList.remove('hidden');
        }

        function closeQRModal() {
            document.getElementById('qrModal').classList.add('hidden');
        }
    </script>

    <!-- QR Code Modal -->
    <div id="qrModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="closeQRModal()">
        <div class="bg-white rounded-lg shadow-xl p-8 w-96 text-center" onclick="event.stopPropagation()">
            <h3 class="text-xl font-bold text-koshouko-text mb-6">📱 QR Code Peminjaman</h3>
            <img id="qrImage" src="" alt="QR Code" class="w-full border-4 border-koshouko-border rounded-lg mb-4">
            <p class="text-sm text-koshouko-text-muted mb-6">Tunjukkan QR Code ini kepada petugas saat mengambil buku</p>
            <button onclick="closeQRModal()" class="px-6 py-2 bg-koshouko-wood text-white rounded-lg font-semibold hover:opacity-90 transition">Tutup</button>
        </div>
    </div>

    <script src="{{ asset('js/member-pages.js') }}"></script>
@endsection
