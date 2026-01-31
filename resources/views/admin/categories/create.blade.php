@extends('layouts.auth-app')

@section('title', 'Tambah Kategori - Perpustakaan Digital')
@section('page-title', '➕ Tambah Kategori')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <div class="max-w-2xl">
        <div class="gradient-card rounded-2xl p-8">
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-koshouko-text mb-2">Nama Kategori *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood placeholder-koshouko-text-muted @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-koshouko-text mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood placeholder-koshouko-text-muted">{{ old('description') }}</textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 btn-koshouko rounded-lg font-semibold transition">
                        ✓ Simpan Kategori
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="flex-1 px-6 py-3 border-2 border-koshouko-border text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-cream transition text-center">
@endsection
