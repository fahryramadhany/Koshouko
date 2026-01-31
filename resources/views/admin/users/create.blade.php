@extends('layouts.auth-app')

@section('title', 'Tambah User - Perpustakaan Digital')
@section('page-title', '➕ Tambah User Baru')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <div class="max-w-2xl">
        <div class="gradient-card rounded-2xl p-8">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Nama *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood placeholder-koshouko-text-muted @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood placeholder-koshouko-text-muted @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Password *</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood placeholder-koshouko-text-muted @error('password') border-red-500 @enderror">
                        @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Role *</label>
                        <select name="role_id" required
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood @error('role_id') border-red-500 @enderror">
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Telepon</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood placeholder-koshouko-text-muted">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                            class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-koshouko-text mb-2">Alamat</label>
                    <textarea name="address" rows="3"
                        class="w-full px-4 py-2 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text focus:outline-none focus:border-koshouko-wood placeholder-koshouko-text-muted">{{ old('address') }}</textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 btn-koshouko rounded-lg font-semibold transition">
                        ✓ Simpan User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="flex-1 px-6 py-3 border-2 border-koshouko-border text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-cream transition text-center">
@endsection
