@extends('layouts.auth-app')

@section('title', $book->title . ' - Perpustakaan Digital')
@section('page-title', '📖 ' . $book->title)

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

<div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-4">
                <!-- Book Cover -->
                <div class="rounded-2xl overflow-hidden shadow-lg bg-gradient-to-br from-koshouko-wood/10 to-koshouko-red/10 aspect-[3/4] flex items-center justify-center border-4 border-koshouko-border">
                    @if($book->cover_image && file_exists(public_path($book->cover_image)))
                        <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center p-8">
                            <p class="text-6xl opacity-30 mb-2">📚</p>
                            <p class="text-sm text-koshouko-text-muted">Tidak ada sampul</p>
                        </div>
                    @endif
                </div>

                <div class="bg-koshouko-cream-light rounded-xl p-4 text-center">
                    <p class="text-koshouko-text-muted text-xs">Kategori</p>
                    <p class="font-semibold text-koshouko-wood">{{ $book->category?->name ?? 'Uncategorized' }}</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.books.edit', $book) }}" class="w-full px-6 py-3 btn-koshouko rounded-lg font-semibold transition text-center">Edit Buku</a>
                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="w-full" onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full px-6 py-3 border-2 border-red-500 text-red-700 rounded-lg font-semibold hover:bg-red-50 transition">Hapus</button>
                    </form>
                </div>

                <div class="bg-koshouko-cream-light rounded-lg p-4">
                    <p class="text-sm text-koshouko-text"><strong>Tersedia saat ini:</strong> {{ $book->available_copies }} dari {{ $book->total_copies }}</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 space-y-8">
            <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-koshouko-wood/15 to-koshouko-red/15 border-b-2 border-koshouko-border">
                    <h1 class="text-3xl font-bold text-koshouko-wood">{{ $book->title }}</h1>
                    <p class="text-koshouko-text-muted mt-2">oleh <span class="font-semibold text-koshouko-text">{{ $book->author }}</span></p>
                </div>

                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-koshouko-text-muted text-xs font-medium mb-1">ISBN</p>
                            <p class="text-koshouko-text font-mono text-sm">{{ $book->isbn ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-xs font-medium mb-1">Penerbit</p>
                            <p class="text-koshouko-text font-semibold">{{ $book->publisher ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-xs font-medium mb-1">Lokasi</p>
                            <p class="text-koshouko-text font-semibold">{{ $book->location ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-xs font-medium mb-1">Bahasa</p>
                            <p class="text-koshouko-text font-semibold">{{ $book->language ?? 'Indonesia' }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-xs font-medium mb-1">Tahun Terbit</p>
                            <p class="text-koshouko-text font-semibold">{{ $book->publication_year ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-xs font-medium mb-1">Halaman</p>
                            <p class="text-koshouko-text font-semibold">{{ $book->pages ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-koshouko-wood text-lg mb-3">📖 Deskripsi</h4>
                        <p class="text-koshouko-text leading-relaxed text-justify">{{ $book->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
