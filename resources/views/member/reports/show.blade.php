@extends('layouts.auth-app')

@section('title', 'Detail Laporan - Perpustakaan Digital')
@section('page-title', '📋 Detail Laporan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">

<div class="max-w-4xl mx-auto">
    <!-- Report Detail -->
    <div class="gradient-card rounded-2xl shadow-lg overflow-hidden mb-8">
        <div class="px-8 py-6 bg-gradient-to-r from-koshouko-wood/15 to-koshouko-red/15 border-b-2 border-koshouko-border flex justify-between items-start">
            <div>
                <h3 class="text-2xl font-bold section-title">{{ $report->title }}</h3>
                <p class="text-koshouko-text-muted text-sm mt-2">Dibuat: {{ $report->created_at->format('d M Y H:i') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
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

        <div class="p-8 space-y-6">
            <!-- Type & Status Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-koshouko-text-muted text-sm font-medium mb-1">Tipe Laporan</p>
                    <p class="text-koshouko-text font-semibold flex items-center gap-2">
                        @switch($report->type)
                            @case('book_issue')
                                📚 Masalah Buku
                            @break
                            @case('account_issue')
                                👤 Masalah Akun
                            @break
                            @case('technical_issue')
                                🔧 Masalah Teknis
                            @break
                            @default
                                ❓ Lainnya
                        @endswitch
                    </p>
                </div>
                <div>
                    <p class="text-koshouko-text-muted text-sm font-medium mb-1">Status</p>
                    <p class="text-koshouko-text font-semibold capitalize">{{ str_replace('_', ' ', $report->status) }}</p>
                </div>
                <div>
                    <p class="text-koshouko-text-muted text-sm font-medium mb-1">Terakhir Diupdate</p>
                    <p class="text-koshouko-text font-semibold">{{ $report->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <!-- Description -->
            <div class="border-t border-koshouko-border pt-6">
                <p class="text-koshouko-text-muted text-sm font-medium mb-3">Deskripsi</p>
                <p class="text-koshouko-text whitespace-pre-wrap">{{ $report->description }}</p>
            </div>

            <!-- Response -->
            @if($report->response)
                <div class="bg-koshouko-cream-light rounded-lg p-6 border-l-4 border-green-500">
                    <p class="text-koshouko-text-muted text-sm font-medium mb-3">⭐ Respons Tim Support</p>
                    <p class="text-koshouko-text whitespace-pre-wrap">{{ $report->response }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4">
        @if($report->status === 'pending')
            <a href="{{ route('reports.edit', $report) }}" class="flex-1 px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition text-center">
                Edit Laporan
            </a>
            <form action="{{ route('reports.destroy', $report) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-6 py-3 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">
                    Hapus Laporan
                </button>
            </form>
        @endif
        <a href="{{ route('reports.index') }}" class="flex-1 px-6 py-3 bg-koshouko-cream-light text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-cream transition border-2 border-koshouko-border text-center">
            Kembali
        </a>
    </div>
</div>

@endsection
