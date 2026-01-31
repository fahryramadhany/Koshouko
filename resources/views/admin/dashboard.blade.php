@extends('layouts.auth-app')

@section('title', 'Admin Dashboard - Perpustakaan Digital')
@section('page-title', '📊 Admin Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 mb-8">
        <div class="stat-card rounded-2xl p-4 sm:p-6 lg:p-8 border-l-4 border-koshouko-wood">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-koshouko-text-muted text-xs sm:text-sm font-semibold uppercase tracking-wider">Total Buku</p>
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-bold text-koshouko-text mt-2 sm:mt-3">{{ $totalBooks }}</p>
                </div>
                <div class="text-3xl sm:text-4xl lg:text-5xl opacity-20">📖</div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-4 sm:p-6 lg:p-8 border-l-4 border-koshouko-red">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-koshouko-text-muted text-xs sm:text-sm font-semibold uppercase tracking-wider">Total Member</p>
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-bold text-koshouko-text mt-2 sm:mt-3">{{ $totalMembers }}</p>
                </div>
                <div class="text-3xl sm:text-4xl lg:text-5xl opacity-20">👥</div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-4 sm:p-6 lg:p-8 border-l-4 border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-koshouko-text-muted text-xs sm:text-sm font-semibold uppercase tracking-wider">Peminjaman Aktif</p>
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-bold text-koshouko-text mt-2 sm:mt-3">{{ $activeBorrowings }}</p>
                </div>
                <div class="text-3xl sm:text-4xl lg:text-5xl opacity-20">✓</div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-4 sm:p-6 lg:p-8 border-l-4 border-yellow-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-koshouko-text-muted text-xs sm:text-sm font-semibold uppercase tracking-wider">Menunggu Persetujuan</p>
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-bold text-koshouko-text mt-2 sm:mt-3">{{ $pendingBorrowings }}</p>
                </div>
                <div class="text-3xl sm:text-4xl lg:text-5xl opacity-20">⏳</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Borrowings -->
        <div class="lg:col-span-2 gradient-card rounded-2xl overflow-hidden">
            <div class="px-6 py-4 section-header">
                <h3 class="text-xl font-bold text-koshouko-text">📋 Peminjaman Terbaru</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-koshouko-paper border-b-2 border-koshouko-border">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Member</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Buku</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-koshouko-text uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-koshouko-border">
                        @forelse($recentBorrowings as $borrowing)
                            <tr class="hover:bg-koshouko-cream/50 transition">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-koshouko-text text-sm">{{ $borrowing->user->name }}</p>
                                    <p class="text-xs text-koshouko-text-muted">{{ $borrowing->user->member_id }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-koshouko-text text-sm">{{ $borrowing->book->title }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-koshouko-text-muted">
                                    {{ $borrowing->borrowed_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $borrowing->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $borrowing->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $borrowing->status === 'overdue' ? 'bg-red-100 text-red-700' : '' }}
                                    ">
                                        {{ ucfirst($borrowing->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($borrowing->status === 'pending')
                                        <form action="{{ route('admin.borrowings.approve', $borrowing) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-koshouko-wood hover:text-koshouko-red font-semibold text-xs">Setujui</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-koshouko-text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t-2 border-koshouko-border">
                <a href="{{ route('admin.borrowings') }}" class="text-koshouko-wood hover:text-koshouko-red font-semibold text-sm">
                    Lihat Semua →
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <div class="gradient-card rounded-2xl p-6">
                <h3 class="text-lg font-bold text-koshouko-text mb-4">⚡ Akses Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.books.create') }}" class="block px-4 py-3 bg-koshouko-wood/10 text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-wood/20 transition text-center border border-koshouko-border">
                        ➕ Tambah Buku
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="block px-4 py-3 bg-koshouko-red/10 text-koshouko-red rounded-lg font-semibold hover:bg-koshouko-red/20 transition text-center border border-koshouko-border">
                        ➕ Tambah User
                    </a>
                    <a href="{{ route('admin.borrowings') }}" class="block px-4 py-3 btn-koshouko rounded-lg font-semibold transition text-center">
                        ✓ Kelola Peminjaman
                    </a>
                    <a href="{{ route('admin.announcements') }}" class="block px-4 py-3 bg-koshouko-red/10 text-koshouko-red rounded-lg font-semibold hover:bg-koshouko-red/20 transition text-center border border-koshouko-border">
                        📢 Posting Pengumuman
                    </a>
                </div>
            </div>

            <!-- System Info -->
            <div class="gradient-card rounded-2xl border border-koshouko-border p-6">
                <h4 class="font-bold text-koshouko-text mb-4">ℹ️ Informasi Sistem</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-koshouko-text-muted">Status</span>
                        <span class="font-semibold text-green-700">✓ Online</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-koshouko-text-muted">Version</span>
                        <span class="font-semibold text-koshouko-text">1.0.0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-koshouko-text-muted">Last Update</span>
                        <span class="font-semibold text-koshouko-text">{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
