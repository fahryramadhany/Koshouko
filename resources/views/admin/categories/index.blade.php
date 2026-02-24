@extends('layouts.auth-app')

@section('title', 'Kategori - Perpustakaan Digital')
@section('page-title', '🏷️ Kelola Kategori')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-koshouko-text">Daftar Kategori Buku</h1>
        <button type="button" onclick="toggleAddForm()" class="px-4 py-2 btn-koshouko rounded-lg font-semibold transition">
            ➕ Tambah Kategori
        </button>
    </div>

    {{-- Add Category Form --}}
    <div id="addForm" class="hidden mb-6 gradient-card rounded-2xl p-8">
        <h2 class="text-xl font-bold text-koshouko-text mb-4">Tambah Kategori Baru</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-koshouko-text mb-2">Nama Kategori *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('name') border-red-500 @enderror"
                        placeholder="Contoh: Fiksi, Non-Fiksi">
                    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end gap-4">
                    <button type="submit" class="px-6 py-2 btn-koshouko rounded-lg font-semibold transition">
                        ✓ Tambah
                    </button>
                    <button type="button" onclick="toggleAddForm()" class="px-6 py-2 border-2 border-koshouko-border text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-cream transition">
                        Batal
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-koshouko-text mb-2">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood placeholder-koshouko-text-muted"
                    placeholder="Deskripsi kategori (opsional)">{{ old('description') }}</textarea>
            </div>
        </form>
    </div>
<script>
    function toggleAddForm() {
        const form = document.getElementById('addForm');
        if (form) form.classList.toggle('hidden');
    }
</script>

    <div class="gradient-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="section-header">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Jumlah Buku</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-koshouko-text uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-koshouko-border">
                    @forelse($categories as $category)
                        <tr class="hover:bg-koshouko-cream/50 transition">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-koshouko-text">{{ $category->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-koshouko-text-muted">
                                {{ Str::limit($category->description, 50) ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-koshouko-wood/10 text-koshouko-wood rounded-full font-semibold text-sm border border-koshouko-border">
                                    {{ $category->books->count() }} buku
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-koshouko-wood hover:underline text-sm font-semibold">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-koshouko-red hover:underline text-sm font-semibold" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-koshouko-text-muted">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
@endsection
