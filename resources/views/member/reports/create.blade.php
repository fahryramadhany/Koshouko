@extends('layouts.auth-app')

@section('title', 'Laporan - Perpustakaan Digital')
@section('page-title', '📋 Buat Laporan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">

<div class="max-w-4xl mx-auto">
    <!-- Form Section -->
    <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
        <div class="px-8 py-6 bg-gradient-to-r from-koshouko-wood/15 to-koshouko-red/15 border-b-2 border-koshouko-border">
            <h3 class="text-2xl font-bold section-title">Laporan Masalah</h3>
            <p class="text-koshouko-text-muted text-sm mt-2">Silakan jelaskan masalah yang Anda hadapi. Tim support kami akan segera menghubungi Anda.</p>
        </div>

        <div class="p-8">
            <form action="{{ route('reports.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-koshouko-text mb-2">
                        Tipe Laporan <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg focus:outline-none focus:border-koshouko-wood transition @error('type') border-red-500 @enderror">
                        <option value="">-- Pilih Tipe Laporan --</option>
                        <option value="book_issue" @selected(old('type') === 'book_issue')>📚 Masalah Buku</option>
                        <option value="account_issue" @selected(old('type') === 'account_issue')>👤 Masalah Akun</option>
                        <option value="technical_issue" @selected(old('type') === 'technical_issue')>🔧 Masalah Teknis</option>
                        <option value="other" @selected(old('type') === 'other')>❓ Lainnya</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-koshouko-text mb-2">
                        Judul Laporan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" required 
                        placeholder="Contoh: Buku tidak tersedia di toko"
                        value="{{ old('title') }}"
                        class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg focus:outline-none focus:border-koshouko-wood transition @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-koshouko-text mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" required rows="8"
                        placeholder="Jelaskan masalah Anda secara detail..."
                        class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg focus:outline-none focus:border-koshouko-wood transition resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    <p class="text-xs text-koshouko-text-muted mt-1">Maksimal 2000 karakter</p>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-6 border-t border-koshouko-border">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition btn-koshouko">
                        Kirim Laporan
                    </button>
                    <a href="{{ route('reports.index') }}" class="flex-1 px-6 py-3 bg-koshouko-cream-light text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-cream transition border-2 border-koshouko-border text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div class="mt-8 gradient-card rounded-2xl p-8 shadow-lg">
        <h4 class="font-bold text-lg text-koshouko-wood mb-4">💡 Tips Membuat Laporan</h4>
        <ul class="space-y-3 text-sm text-koshouko-text">
            <li class="flex items-start space-x-3">
                <span class="text-koshouko-wood font-bold">1.</span>
                <span>Jelaskan masalah dengan detail dan spesifik</span>
            </li>
            <li class="flex items-start space-x-3">
                <span class="text-koshouko-wood font-bold">2.</span>
                <span>Sertakan informasi penting seperti nomor buku atau tanggal kejadian</span>
            </li>
            <li class="flex items-start space-x-3">
                <span class="text-koshouko-wood font-bold">3.</span>
                <span>Jangan menunggu terlalu lama untuk melaporkan masalah</span>
            </li>
            <li class="flex items-start space-x-3">
                <span class="text-koshouko-wood font-bold">4.</span>
                <span>Tim support kami akan merespons dalam 24 jam</span>
            </li>
        </ul>
    </div>
</div>

@endsection
