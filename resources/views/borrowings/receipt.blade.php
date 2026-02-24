@extends('layouts.auth-app')

@section('title', 'Bukti Peminjaman Buku')
@section('page-title', '📄 Bukti Peminjaman')

@section('content')
<div class="max-w-lg mx-auto gradient-card rounded-2xl p-8 mt-8 print:mt-0 print:shadow-none print:bg-white">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-koshouko-text">Bukti Peminjaman</h2>
        <button onclick="window.print()" class="btn-koshouko px-4 py-2 rounded-lg font-semibold print:hidden">🖨️ Cetak</button>
    </div>
    <div class="flex flex-col items-center mb-6">
        @if($borrowing->qr_code)
            <img src="{{ asset($borrowing->qr_code) }}" alt="QR Bukti Peminjaman" class="w-40 h-40 mb-2 border border-koshouko-border rounded-lg">
        @else
            <div class="w-40 h-40 flex items-center justify-center bg-gray-100 text-gray-400 border border-koshouko-border rounded-lg mb-2">QR Tidak Tersedia</div>
        @endif
        <div class="text-xs text-koshouko-text-muted mb-2">Scan QR ini saat pengambilan buku</div>
        <div class="font-mono text-lg font-bold text-koshouko-wood tracking-widest">{{ $borrowing->code ?? ('BRW-' . $borrowing->borrowed_at->format('Ymd') . '-' . str_pad($borrowing->id, 4, '0', STR_PAD_LEFT)) }}</div>
        <div class="text-xs text-koshouko-text-muted">Kode Peminjaman</div>
    </div>
    <div class="mb-4">
        <div class="flex justify-between mb-2">
            <span class="font-semibold">Kode Peminjaman:</span>
            <span class="font-mono">{{ $borrowing->code ?? ('BRW-' . $borrowing->borrowed_at->format('Ymd') . '-' . str_pad($borrowing->id, 4, '0', STR_PAD_LEFT)) }}</span>
        </div>
        <div class="flex justify-between mb-2">
            <span class="font-semibold">Nama Peminjam:</span>
            <span>{{ $borrowing->user->name }}</span>
        </div>
        <div class="flex justify-between mb-2">
            <span class="font-semibold">Email:</span>
            <span>{{ $borrowing->user->email }}</span>
        </div>
        <div class="flex justify-between mb-2">
            <span class="font-semibold">Judul Buku:</span>
            <span>{{ $borrowing->book->title }}</span>
        </div>
        <div class="flex justify-between mb-2">
            <span class="font-semibold">Tanggal Pinjam:</span>
            <span>{{ $borrowing->borrowed_at->format('d M Y') }}</span>
        </div>
        <div class="flex justify-between mb-2">
            <span class="font-semibold">Tanggal Kembali:</span>
            <span>{{ $borrowing->due_date->format('d M Y') }}</span>
        </div>
        <div class="flex justify-between mb-2">
            <span class="font-semibold">Status:</span>
            <span>{{ ucfirst($borrowing->status) }}</span>
        </div>
    </div>
    <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
        <p class="text-sm text-koshouko-text"><strong>Catatan:</strong> Tunjukkan bukti ini (atau QR) saat mengambil buku di perpustakaan. Simpan baik-baik bukti ini sebagai arsip peminjaman Anda.</p>
    </div>
</div>
@endsection
