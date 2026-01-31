@extends('layouts.auth-app')

@section('title', 'Edit Kategori - Perpustakaan Digital')
@section('page-title', '✏️ Edit Kategori Buku')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <div class="max-w-2xl">
        <div class="gradient-card rounded-2xl p-8">
            <form action="{{ route('librarian.books.categories.update', $category) }}" method="POST" class="space-y-6">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-koshouko-text mb-2">Nama Kategori *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                        class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-koshouko-text mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="bg-koshouko-cream border-2 border-koshouko-border rounded-lg p-4">
                    <p class="text-sm text-koshouko-text"><strong>Total Buku:</strong> {{ $category->books->count() }} buku</p>
                    <p class="text-sm text-koshouko-text"><strong>Dibuat:</strong> {{ $category->created_at->format('d M Y') }}</p>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 btn-koshouko rounded-lg font-semibold transition">
                        ✓ Simpan Perubahan
                    </button>
                    <a href="{{ route('librarian.books.categories') }}" class="flex-1 px-6 py-3 border-2 border-koshouko-border text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-cream transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
