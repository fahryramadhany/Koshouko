@extends('layouts.auth-app')

@section('title', 'Kategori - Perpustakaan Digital')
@section('page-title', '🏷️ Kelola Kategori')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-koshouko-text-muted">Total Kategori: <strong class="text-koshouko-text text-lg">{{ \App\Models\Category::count() }}</strong></p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="px-6 py-3 btn-koshouko rounded-lg font-semibold transition text-center">
            ➕ Tambah Kategori
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="gradient-card rounded-2xl p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-koshouko-text">{{ $category->name }}</h3>
                        <p class="text-sm text-koshouko-text-muted mt-1">{{ $category->books->count() }} buku</p>
                    </div>
                    <span class="text-3xl opacity-30">🏷️</span>
                </div>

                <p class="text-koshouko-text-muted text-sm mb-4 line-clamp-2">{{ $category->description ?? '-' }}</p>

                <div class="flex gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="flex-1 px-3 py-2 bg-koshouko-wood/10 text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-wood/20 transition text-sm text-center border border-koshouko-border">
                        Edit
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg font-semibold hover:bg-red-200 transition text-sm border border-red-200">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-koshouko-text-muted text-lg">Belum ada kategori</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
@endsection
