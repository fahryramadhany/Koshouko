@extends('layouts.auth-app')

@section('title', 'Edit Buku - Perpustakaan Digital')
@section('page-title', '✏️ Edit Buku')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <div class="max-w-2xl">
        <div class="gradient-card rounded-2xl p-8">
            <form action="{{ route('librarian.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Judul Buku *</label>
                        <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('title') border-red-500 @enderror">
                        @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Penulis *</label>
                        <input type="text" name="author" value="{{ old('author', $book->author) }}" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('author') border-red-500 @enderror">
                        @error('author') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Penerbit</label>
                        <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}"
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-koshouko-text mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">{{ old('description', $book->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-koshouko-text mb-2">📸 Cover Buku</label>
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <input type="file" name="cover_image" accept="image/*"
                                class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('cover_image') border-red-500 @enderror"
                                id="cover_image">
                            <p class="text-xs text-koshouko-text-muted mt-1">Format: JPG, PNG, GIF (Max 2MB)</p>
                            @error('cover_image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div id="preview" class="flex-shrink-0">
                            @if($book->cover_image)
                                <img id="previewImg" src="{{ asset($book->cover_image) }}" alt="Cover" class="h-32 w-24 object-cover border-2 border-koshouko-border rounded-lg">
                            @else
                                <img id="previewImg" src="" alt="Preview" class="h-32 w-24 object-cover border-2 border-koshouko-border rounded-lg hidden">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Kategori *</label>
                        <select name="category_id" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('category_id') border-red-500 @enderror">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Status *</label>
                        <select name="status" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                            <option value="available" {{ old('status', $book->status) === 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="unavailable" {{ old('status', $book->status) === 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                            <option value="archived" {{ old('status', $book->status) === 'archived' ? 'selected' : '' }}>Arsip</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Jumlah Salinan *</label>
                        <input type="number" name="total_copies" value="{{ old('total_copies', $book->total_copies) }}" min="1" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('total_copies') border-red-500 @enderror">
                        @error('total_copies') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Halaman</label>
                        <input type="number" name="pages" value="{{ old('pages', $book->pages) }}" min="1"
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Lokasi Rak</label>
                        <input type="text" name="location" value="{{ old('location', $book->location) }}"
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                    </div>
                </div>

                <div class="bg-koshouko-cream border-2 border-koshouko-border rounded-lg p-4">
                    <p class="text-sm text-koshouko-text"><strong>Tersedia saat ini:</strong> {{ $book->available_copies }} dari {{ $book->total_copies }}</p>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 btn-koshouko rounded-lg font-semibold transition">
                        ✓ Simpan Perubahan
                    </button>
                    <a href="{{ route('librarian.books.index') }}" class="flex-1 px-6 py-3 border-2 border-koshouko-border text-koshouko-text rounded-lg font-semibold hover:bg-koshouko-cream transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('cover_image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewImg').src = event.target.result;
                    document.getElementById('previewImg').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
