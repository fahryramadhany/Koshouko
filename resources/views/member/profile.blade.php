@extends('layouts.auth-app')

@section('title', 'Profile - Perpustakaan Digital')
@section('page-title', '👤 Profile Saya')

@section('content')
<link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 mb-8">
        <!-- Buku Sedang Dipinjam -->
        <div class="stat-card rounded-2xl p-4 sm:p-6 lg:p-8 border-l-4 border-koshouko-wood shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-koshouko-text-muted text-xs sm:text-sm font-semibold uppercase tracking-wider">Sedang Dipinjam</p>
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-koshouko-wood mt-2 sm:mt-3">{{ $totalBorrowedBooks }}</p>
                </div>
                <div class="text-3xl sm:text-4xl lg:text-5xl opacity-30">📚</div>
            </div>
        </div>

        <!-- Buku Favorit -->
        <div class="stat-card rounded-2xl p-4 sm:p-6 lg:p-8 border-l-4 border-koshouko-red shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-koshouko-text-muted text-xs sm:text-sm font-semibold uppercase tracking-wider">Buku Favorit</p>
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-koshouko-red mt-2 sm:mt-3">{{ $bookmarkedBooks->count() }}</p>
                </div>
                <div class="text-3xl sm:text-4xl lg:text-5xl opacity-30">⭐</div>
            </div>
        </div>

        <!-- Keterlambatan -->
        <div class="stat-card rounded-2xl p-4 sm:p-6 lg:p-8 border-l-4 border-red-500 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-koshouko-text-muted text-xs sm:text-sm font-semibold uppercase tracking-wider">Keterlambatan</p>
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-red-600 mt-2 sm:mt-3">{{ $overdueBooks->count() }}</p>
                </div>
                <div class="text-3xl sm:text-4xl lg:text-5xl opacity-30">⚠️</div>
            </div>
        </div>

        <!-- Status Akun -->
        <div class="stat-card rounded-2xl p-4 sm:p-6 lg:p-8 border-l-4 border-green-500 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-koshouko-text-muted text-xs sm:text-sm font-semibold uppercase tracking-wider">Status Akun</p>
                    <p class="text-sm sm:text-base font-bold text-green-600 mt-2 sm:mt-3 capitalize">{{ auth()->user()->status }}</p>
                </div>
                <div class="text-3xl sm:text-4xl lg:text-5xl opacity-30">✓</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Profil User -->
            <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-koshouko-wood/15 to-koshouko-red/15 border-b-2 border-koshouko-border">
                    <h3 class="text-2xl font-bold section-title">📋 Informasi Akun</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-koshouko-text-muted text-sm font-medium mb-1">Nama Lengkap</p>
                            <p class="text-koshouko-text font-semibold">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-sm font-medium mb-1">Email</p>
                            <p class="text-koshouko-text font-semibold">{{ auth()->user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-sm font-medium mb-1">ID Member</p>
                            <p class="text-koshouko-text font-semibold font-mono text-sm">{{ auth()->user()->member_id }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-sm font-medium mb-1">Nomor Telepon</p>
                            <p class="text-koshouko-text font-semibold">{{ auth()->user()->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-sm font-medium mb-1">Tanggal Lahir</p>
                            <p class="text-koshouko-text font-semibold">{{ auth()->user()->date_of_birth ? auth()->user()->date_of_birth->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-sm font-medium mb-1">Terdaftar Sejak</p>
                            <p class="text-koshouko-text font-semibold">{{ auth()->user()->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-koshouko-text-muted text-sm font-medium mb-1">Alamat</p>
                        <p class="text-koshouko-text font-semibold">{{ auth()->user()->address ?? 'Tidak diisi' }}</p>
                    </div>
                </div>

                <!-- Edit Profile Button -->
                <div class="mt-6 pt-6 border-t border-koshouko-border">
                    <a href="{{ route('profile.edit') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition btn-koshouko">
                        ✏️ Edit Profil
                    </a>
                </div>

            <!-- Riwayat Peminjaman Terakhir -->
            <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-koshouko-wood/15 to-koshouko-red/15 border-b-2 border-koshouko-border">
                    <h3 class="text-2xl font-bold section-title">📖 Riwayat Peminjaman Terakhir</h3>
                </div>
                
                @if($recentBorrowings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-koshouko-cream-light border-b border-koshouko-border">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-koshouko-text">Judul Buku</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-koshouko-text">Tanggal Pinjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-koshouko-text">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-koshouko-border">
                                @foreach($recentBorrowings as $borrowing)
                                    <tr class="hover:bg-koshouko-cream-light transition">
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-koshouko-text">{{ $borrowing->book->title }}</p>
                                            <p class="text-xs text-koshouko-text-muted">{{ $borrowing->book->author }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-koshouko-text">{{ $borrowing->borrowed_at ? $borrowing->borrowed_at->format('d M Y') : '-' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                                @if($borrowing->status === 'returned')
                                                    bg-blue-100 text-blue-700
                                                @elseif($borrowing->status === 'overdue')
                                                    bg-red-100 text-red-700
                                                @else
                                                    bg-green-100 text-green-700
                                                @endif
                                            ">
                                                {{ ucfirst($borrowing->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center">
                        <p class="text-koshouko-text-muted text-lg">Anda belum pernah meminjam buku</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Buku Favorit -->
            <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-koshouko-red/15 to-koshouko-pink/15 border-b-2 border-koshouko-border">
                    <h3 class="text-2xl font-bold section-title">⭐ Buku Favorit Saya</h3>
                </div>
                
                @if($bookmarkedBooks->count() > 0)
                    <div class="divide-y divide-koshouko-border">
                        @forelse($bookmarkedBooks->take(8) as $bookmark)
                            <div class="p-4 hover:bg-koshouko-cream-light transition">
                                <p class="font-semibold text-koshouko-text text-sm line-clamp-2">{{ $bookmark->book->title }}</p>
                                <p class="text-xs text-koshouko-text-muted mt-1">{{ $bookmark->book->author }}</p>
                                <p class="text-xs text-koshouko-wood font-semibold mt-2">Tersedia: {{ $bookmark->book->available_copies }} kopi</p>
                                <div class="mt-3 flex gap-2">
                                    <a href="{{ route('books.show', $bookmark->book) }}" class="flex-1 px-2 py-1 bg-koshouko-wood/10 text-koshouko-wood rounded text-xs font-semibold hover:bg-koshouko-wood hover:text-white transition text-center border border-koshouko-border">
                                        Lihat
                                    </a>
                                    <form action="{{ route('bookmarks.destroy', $bookmark) }}" method="POST" class="flex-1">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full px-2 py-1 bg-red-50 text-red-700 rounded text-xs font-semibold hover:bg-red-100 transition border border-red-200">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-koshouko-text-muted">
                                Tidak ada favorit
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>

            <!-- Informasi Penting -->
            <div class="gradient-card rounded-2xl border-l-4 border-koshouko-wood p-8 shadow-lg">
                <h4 class="font-bold section-title text-lg mb-6">ℹ️ Kebijakan Perpustakaan</h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start space-x-3">
                        <span class="w-2 h-2 rounded-full bg-koshouko-wood mt-2"></span>
                        <span class="text-koshouko-text">Maksimal <strong>5 buku</strong> sekaligus</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <span class="w-2 h-2 rounded-full bg-koshouko-wood mt-2"></span>
                        <span class="text-koshouko-text">Durasi <strong>14 hari</strong></span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <span class="w-2 h-2 rounded-full bg-koshouko-wood mt-2"></span>
                        <span class="text-koshouko-text">Perpanjangan <strong>2 kali</strong></span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <span class="w-2 h-2 rounded-full bg-koshouko-red mt-2"></span>
                        <span class="text-koshouko-text">Denda <strong>Rp 5.000/hari</strong></span>
                    </li>
                </ul>
            </div>

            <!-- Quick Actions -->
            <div class="gradient-card rounded-2xl shadow-lg p-8">
                <h4 class="font-bold section-title text-lg mb-6">⚡ Akses Cepat</h4>
                <div class="space-y-3">
                    <a href="{{ route('books.index') }}" class="block px-4 py-4 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition text-sm text-center btn-koshouko">
                        Cari Buku Baru
                    </a>
                    <a href="{{ route('borrowings.index') }}" class="block px-4 py-4 bg-gradient-to-r from-koshouko-cream to-koshouko-cream-light text-koshouko-wood rounded-lg font-semibold hover:shadow-lg transition text-sm text-center border-2 border-koshouko-wood">
                        Riwayat Lengkap
                    </a>
                    <a href="{{ route('reports.index') }}" class="block px-4 py-4 bg-gradient-to-r from-amber-100 to-amber-50 text-amber-800 rounded-lg font-semibold hover:shadow-lg transition text-sm text-center border-2 border-amber-300">
                        📋 Laporan Masalah
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
