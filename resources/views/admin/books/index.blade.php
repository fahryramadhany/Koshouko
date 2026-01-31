@extends('layouts.auth-app')

@section('title', 'Kelola Buku - Perpustakaan Digital')
@section('page-title', '📖 Kelola Buku')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-koshouko-text-muted">Total Buku: <strong class="text-koshouko-text text-lg">{{ \App\Models\Book::count() }}</strong></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.books.categories') }}" class="px-6 py-3 border-2 border-koshouko-border text-koshouko-wood rounded-lg font-semibold transition text-center hover:bg-koshouko-cream">
                📚 Kelola Kategori
            </a>
            <a href="{{ route('admin.books.create') }}" class="px-6 py-3 btn-koshouko rounded-lg font-semibold transition text-center">
                ➕ Tambah Buku Baru
            </a>
        </div>
    </div>

    <div class="gradient-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="section-header">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Cover / Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Penulis</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-koshouko-text uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-koshouko-border">
                    @forelse($books as $book)
                        <tr class="hover:bg-koshouko-cream/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    {{-- Cover Image --}}
                                    <div class="flex-shrink-0 w-12 h-16 rounded border border-koshouko-border overflow-hidden bg-gradient-to-br from-koshouko-wood to-koshouko-red flex items-center justify-center">
                                        @if($book->cover_image && file_exists(public_path($book->cover_image)))
                                            <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl opacity-50">📖</span>
                                        @endif
                                    </div>
                                    {{-- Title --}}
                                    <div class="flex-1">
                                        <p class="font-semibold text-koshouko-text">{{ Str::limit($book->title, 40) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-koshouko-text-muted">{{ $book->author }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-koshouko-wood/10 text-koshouko-wood rounded-full text-xs font-semibold border border-koshouko-border">
                                    {{ $book->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-koshouko-text">{{ $book->available_copies }}/{{ $book->total_copies }}</p>
                                    <p class="text-xs text-koshouko-text-muted">Tersedia/Total</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $book->status === 'available' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $book->status === 'unavailable' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $book->status === 'archived' ? 'bg-koshouko-border text-koshouko-text-muted' : '' }}
                                ">
                                    {{ ucfirst($book->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('admin.books.edit', $book) }}" class="text-koshouko-wood hover:text-koshouko-red font-semibold text-xs">Edit</a>
                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-xs">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-koshouko-text-muted">Belum ada buku</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $books->links() }}
    </div>
@endsection
