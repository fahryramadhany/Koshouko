@extends('layouts.auth-app')

@section('title', 'Edit User - Perpustakaan Digital')
@section('page-title', '✏️ Edit User')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <div class="max-w-2xl">
        <div class="gradient-card rounded-2xl p-8">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Nama *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Role *</label>
                        <select name="role_id" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('role_id') border-red-500 @enderror">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Telepon</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}"
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Status *</label>
                        <select name="status" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-koshouko-text mb-2">Alamat</label>
                    <textarea name="address" rows="3"
                        class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="bg-koshouko-cream border-2 border-koshouko-border rounded-lg p-4">
                    <p class="text-sm text-koshouko-text"><strong>ID Member:</strong> {{ $user->member_id }}</p>
                    <p class="text-sm text-koshouko-text"><strong>Terdaftar:</strong> {{ $user->created_at->format('d M Y') }}</p>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 btn-koshouko rounded-lg font-semibold transition">
                        ✓ Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="flex-1 px-6 py-3 border-2 border-koshouko-border text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-cream transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
