@extends('layouts.auth-app')

@section('title', 'Pengumuman - Perpustakaan Digital')
@section('page-title', '📢 Pengumuman')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <!-- Form Tambah Pengumuman -->
    <div class="gradient-card rounded-2xl p-8 mb-8">
        <h3 class="text-xl font-bold text-koshouko-text mb-6">Posting Pengumuman Baru</h3>
        
        <form action="{{ route('librarian.announcements.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-koshouko-text mb-2">Judul Pengumuman *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('title') border-red-500 @enderror"
                    placeholder="Masukkan judul pengumuman">
                @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-koshouko-text mb-2">Isi Pengumuman *</label>
                <textarea name="content" rows="6" required
                    class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('content') border-red-500 @enderror"
                    placeholder="Tulis isi pengumuman di sini...">{{ old('content') }}</textarea>
                @error('content') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="px-6 py-3 btn-koshouko rounded-lg font-semibold transition">
                📢 Publikasikan Pengumuman
            </button>
        </form>
    </div>

    <!-- Daftar Pengumuman -->
    <div>
        <h3 class="text-xl font-bold text-koshouko-text mb-6">Pengumuman Terbaru</h3>

        @if($announcements->count() > 0)
            <div class="space-y-4">
                @foreach($announcements as $announcement)
                    <div class="gradient-card rounded-2xl p-6 hover:shadow-lg transition">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="text-lg font-bold text-koshouko-text">{{ $announcement->title }}</h4>
                                <p class="text-sm text-koshouko-text-muted">Oleh {{ $announcement->creator->name ?? 'Pustakawan' }} • {{ $announcement->published_at->format('d M Y H:i') }}</p>
                            </div>
                            <span class="px-3 py-1 bg-koshouko-wood/20 text-koshouko-wood rounded-full text-xs font-semibold border border-koshouko-border">
                                {{ ucfirst($announcement->status ?? 'published') }}
                            </span>
                        </div>

                        <p class="text-koshouko-text mb-4 whitespace-pre-wrap">{{ $announcement->content }}</p>

                        <div class="flex gap-2">
                            <a href="#" class="text-koshouko-wood hover:text-koshouko-red font-semibold text-sm">Edit</a>
                            <form action="#" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                                <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="gradient-card rounded-2xl p-12 text-center">
                <p class="text-koshouko-text-muted text-lg">Belum ada pengumuman</p>
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    </div>
@endsection
