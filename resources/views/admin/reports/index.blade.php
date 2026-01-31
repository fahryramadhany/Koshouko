@extends('layouts.auth-app')

@section('title', 'Laporan - Perpustakaan Digital')
@section('page-title', '📈 Laporan & Statistik')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card rounded-2xl p-6">
            <p class="text-koshouko-text-muted text-sm font-semibold uppercase tracking-wider mb-2">Total Peminjaman</p>
            <p class="text-4xl font-bold text-koshouko-text">{{ \App\Models\Borrowing::count() }}</p>
        </div>
        <div class="stat-card rounded-2xl p-6">
            <p class="text-koshouko-text-muted text-sm font-semibold uppercase tracking-wider mb-2">Belum Dikembalikan</p>
            <p class="text-4xl font-bold text-yellow-600">{{ \App\Models\Borrowing::whereIn('status', ['pending', 'approved', 'overdue'])->count() }}</p>
        </div>
        <div class="stat-card rounded-2xl p-6">
            <p class="text-koshouko-text-muted text-sm font-semibold uppercase tracking-wider mb-2">Terlambat</p>
            <p class="text-4xl font-bold text-red-600">{{ \App\Models\Borrowing::where('status', 'overdue')->count() }}</p>
        </div>
        <div class="stat-card rounded-2xl p-6">
            <p class="text-koshouko-text-muted text-sm font-semibold uppercase tracking-wider mb-2">Dikembalikan</p>
            <p class="text-4xl font-bold text-blue-600">{{ \App\Models\Borrowing::where('status', 'returned')->count() }}</p>
        </div>
    </div>

    <div class="gradient-card rounded-2xl p-6 mb-8">
        <h3 class="text-lg font-bold text-koshouko-text mb-4">📊 Denda Tertunggak</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="section-header">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold text-koshouko-text uppercase tracking-wider">Member</th>
                        <th class="px-6 py-4 text-left font-bold text-koshouko-text uppercase tracking-wider">Jumlah Denda</th>
                        <th class="px-6 py-4 text-left font-bold text-koshouko-text uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-koshouko-border">
                    @foreach(\App\Models\Fine::where('status', 'pending')->with('user')->get() as $fine)
                        <tr class="hover:bg-koshouko-cream/50 transition">
                            <td class="px-6 py-4 font-semibold text-koshouko-text">{{ $fine->user->name }}</td>
                            <td class="px-6 py-4 text-koshouko-text-muted">Rp {{ number_format($fine->amount, 0) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Belum Dibayar</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="gradient-card rounded-2xl p-6">
        <h3 class="text-lg font-bold text-koshouko-text mb-4">📚 Buku Paling Sering Dipinjam</h3>
        <div class="space-y-3">
            @foreach(\App\Models\Book::with(['borrowings' => function($q) { $q->where('status', 'returned'); }])->get()->sortByDesc(function($b) { return $b->borrowings->count(); })->take(10) as $book)
                <div class="flex items-center justify-between p-4 bg-koshouko-cream rounded-xl">
                    <div>
                        <p class="font-semibold text-koshouko-text">{{ $book->title }}</p>
                        <p class="text-xs text-koshouko-text-muted">{{ $book->author }}</p>
                    </div>
                    <span class="px-3 py-1 bg-koshouko-wood/10 text-koshouko-wood rounded-full font-semibold text-sm border border-koshouko-border">
                        {{ $book->borrowings->count() }} kali
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endsection
                        <p class="font-semibold text-koshouko-text">{{ $book->title }}</p>
                        <p class="text-xs text-koshouko-text-muted">{{ $book->author }}</p>
                    </div>
                    <span class="px-3 py-1 bg-koshouko-wood/10 text-koshouko-wood rounded-full font-semibold text-sm border border-koshouko-border">
@endsection
