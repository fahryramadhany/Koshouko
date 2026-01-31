@extends('layouts.auth-app')

@section('title', 'Riwayat Laporan - Perpustakaan Digital')
@section('page-title', '📋 Riwayat Laporan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">

<div class="mb-8 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
    <div>
        <p class="text-koshouko-text-muted text-sm mb-2">Total Laporan: <strong>{{ $reports->total() }}</strong></p>
    </div>
    <a href="{{ route('reports.create') }}" class="px-6 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition btn-koshouko">
        + Buat Laporan Baru
    </a>
</div>

<!-- Reports List -->
<div class="space-y-4">
    @forelse($reports as $report)
        <div class="gradient-card rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="p-6 border-l-4 @if($report->status === 'resolved') border-green-500 @elseif($report->status === 'in_progress') border-blue-500 @elseif($report->status === 'rejected') border-red-500 @else border-yellow-500 @endif">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div class="flex-1">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="text-2xl">
                                @switch($report->type)
                                    @case('book_issue')
                                        📚
                                    @break
                                    @case('account_issue')
                                        👤
                                    @break
                                    @case('technical_issue')
                                        🔧
                                    @break
                                    @default
                                        ❓
                                @endswitch
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-koshouko-text">{{ $report->title }}</h4>
                                <p class="text-xs text-koshouko-text-muted mt-1">
                                    Dilaporkan: {{ $report->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                        <p class="text-sm text-koshouko-text-muted line-clamp-2">{{ $report->description }}</p>
                    </div>

                    <div class="flex flex-col gap-2 items-start sm:items-end">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($report->status === 'resolved')
                                bg-green-100 text-green-700
                            @elseif($report->status === 'in_progress')
                                bg-blue-100 text-blue-700
                            @elseif($report->status === 'rejected')
                                bg-red-100 text-red-700
                            @else
                                bg-yellow-100 text-yellow-700
                            @endif
                        ">
                            @switch($report->status)
                                @case('pending')
                                    ⏳ Menunggu
                                @break
                                @case('in_progress')
                                    ⚙️ Sedang Diproses
                                @break
                                @case('resolved')
                                    ✓ Selesai
                                @break
                                @case('rejected')
                                    ✗ Ditolak
                                @break
                            @endswitch
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-4 pt-4 border-t border-koshouko-border flex gap-2">
                    <a href="{{ route('reports.show', $report) }}" class="px-4 py-2 text-sm font-semibold bg-koshouko-wood/10 text-koshouko-wood rounded hover:bg-koshouko-wood hover:text-white transition border border-koshouko-border">
                        Lihat Detail
                    </a>
                    @if($report->status === 'pending')
                        <a href="{{ route('reports.edit', $report) }}" class="px-4 py-2 text-sm font-semibold bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition border border-blue-200">
                            Edit
                        </a>
                        <form action="{{ route('reports.destroy', $report) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-semibold bg-red-50 text-red-700 rounded hover:bg-red-100 transition border border-red-200">
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="gradient-card rounded-2xl p-12 text-center shadow-lg">
            <p class="text-4xl mb-4">📭</p>
            <p class="text-koshouko-text-muted text-lg mb-6">Anda belum membuat laporan apapun</p>
            <a href="{{ route('reports.create') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition btn-koshouko">
                Buat Laporan Pertama
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($reports->hasPages())
    <div class="mt-8">
        {{ $reports->links() }}
    </div>
@endif

@endsection
