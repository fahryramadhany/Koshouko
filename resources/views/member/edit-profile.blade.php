@extends('layouts.auth-app')

@section('title', 'Edit Profil - Perpustakaan Digital')
@section('page-title', '👤 Edit Profil Saya')

@section('content')
@use('Illuminate\Support\Facades\Storage')
<link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">

<div class="max-w-4xl mx-auto pt-28">
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-6 rounded-lg">
            <h4 class="font-bold text-red-700 mb-3">⚠️ Terjadi Kesalahan</h4>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
            <p class="text-green-700 font-semibold">✓ {{ session('success') }}</p>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
        <div class="px-8 py-6 bg-gradient-to-r from-koshouko-wood/15 to-koshouko-red/15 border-b-2 border-koshouko-border">
            <h3 class="text-2xl font-bold section-title">Ubah Informasi Akun</h3>
            <p class="text-koshouko-text-muted text-sm mt-2">Perbarui data pribadi Anda di sini</p>
        </div>

        <div class="p-8">
            <!-- Profile Photo Section -->
            <div class="mb-8 pb-8 border-b-2 border-koshouko-border">
                <h4 class="font-bold text-koshouko-wood text-lg mb-6">📷 Foto Profil</h4>
                
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <!-- Photo Display -->
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            @if($user->profile_photo)
                                <img src="{{ Storage::url($user->profile_photo) }}" 
                                     alt="Foto Profil {{ $user->name }}"
                                     id="profilePhotoDisplay"
                                     class="w-40 h-40 rounded-full object-cover border-4 border-koshouko-wood shadow-lg">
                            @else
                                <div class="w-40 h-40 rounded-full bg-gradient-to-br from-koshouko-wood/20 to-koshouko-red/20 border-4 border-koshouko-border flex items-center justify-center">
                                    <div class="text-center">
                                        <span class="text-4xl">👤</span>
                                        <p class="text-koshouko-text-muted text-sm mt-2">Belum ada foto</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        @if($user->profile_photo)
                            <p class="text-xs text-koshouko-text-muted mt-3 text-center">
                                Diupload: {{ $user->updated_at->format('d M Y') }}
                            </p>
                        @endif
                    </div>

                    <!-- Photo Upload Info & Button -->
                    <div class="flex-1">
                        <div class="space-y-4">
                            <div class="bg-koshouko-cream-light rounded-lg p-6 border-l-4 border-koshouko-wood">
                                <p class="text-sm text-koshouko-text">
                                    <strong>💡 Tips:</strong>
                                </p>
                                <ul class="text-xs text-koshouko-text-muted mt-2 space-y-1 ml-4 list-disc">
                                    <li>Gunakan foto yang jelas dan profesional</li>
                                    <li>Format: JPG, PNG, GIF, atau WebP</li>
                                    <li>Ukuran maksimal: 2MB</li>
                                    <li>Foto akan ditampilkan dalam bentuk lingkaran</li>
                                </ul>
                            </div>

                            <div class="flex flex-col gap-2">
                                          <a href="{{ route('profile-photo.edit', $user) }}" 
                                              class="text-center px-6 py-3 btn-primary rounded-lg font-semibold hover:shadow-lg transition">
                                    {{ $user->profile_photo ? '🖼️ Ganti Foto Profil' : '📤 Upload Foto Profil' }}
                                </a>

                                @if($user->profile_photo)
                                    <form action="{{ route('profile-photo.destroy', $user) }}" method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil?');">
                                        @csrf
                                        @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full px-6 py-2 bg-red-600 text-white rounded-lg font-semibold hover:shadow-md transition border border-red-600 text-sm">
                                            ❌ Hapus Foto
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-koshouko-text mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg focus:outline-none focus:border-koshouko-wood transition @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-koshouko-text mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" required
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg focus:outline-none focus:border-koshouko-wood transition @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Member ID (Read Only) -->
                <div>
                    <label for="member_id" class="block text-sm font-semibold text-koshouko-text mb-2">
                        ID Member
                    </label>
                    <input type="text" name="member_id" id="member_id" disabled
                        value="{{ $user->member_id }}"
                        class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg bg-koshouko-cream-light text-koshouko-text-muted font-mono text-sm">
                    <p class="text-xs text-koshouko-text-muted mt-1">Nomor ID Anda tidak dapat diubah</p>
                </div>

                <div class="border-t border-koshouko-border pt-6">
                    <h4 class="font-bold text-koshouko-wood mb-6">Informasi Tambahan</h4>

                    <!-- Phone -->
                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-semibold text-koshouko-text mb-2">
                            Nomor Telepon
                        </label>
                        <input type="tel" name="phone" id="phone"
                            placeholder="Contoh: 081234567890"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg focus:outline-none focus:border-koshouko-wood transition @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="mb-6">
                        <label for="date_of_birth" class="block text-sm font-semibold text-koshouko-text mb-2">
                            Tanggal Lahir
                        </label>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                            value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg focus:outline-none focus:border-koshouko-wood transition @error('date_of_birth') border-red-500 @enderror">
                        @error('date_of_birth')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-koshouko-text mb-2">
                            Alamat
                        </label>
                        <textarea name="address" id="address" rows="4"
                            placeholder="Masukkan alamat lengkap Anda"
                            class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg focus:outline-none focus:border-koshouko-wood transition resize-none @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                        <p class="text-xs text-koshouko-text-muted mt-1">Maksimal 500 karakter</p>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-koshouko-cream-light rounded-lg p-6 border-l-4 border-koshouko-wood mt-8">
                    <p class="text-sm text-koshouko-text">
                        <strong>ℹ️ Informasi:</strong> Data Anda akan disimpan secara aman di database perpustakaan. 
                        Pastikan informasi yang Anda masukkan akurat dan terbaru.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-6 border-t border-koshouko-border">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('profile') }}" class="flex-1 px-6 py-3 bg-white text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-wood/5 transition border-2 border-koshouko-wood text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Account Info -->
        <div class="gradient-card rounded-2xl p-8 shadow-lg">
            <h4 class="font-bold text-koshouko-wood text-lg mb-4">ℹ️ Informasi Akun</h4>
            <div class="space-y-3 text-sm text-koshouko-text">
                <div class="flex justify-between">
                    <span class="text-koshouko-text-muted">Status:</span>
                    <span class="font-semibold capitalize">{{ $user->status }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-koshouko-text-muted">Terdaftar:</span>
                    <span class="font-semibold">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-koshouko-text-muted">Terakhir Update:</span>
                    <span class="font-semibold">{{ $user->updated_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Security Info -->
        <div class="gradient-card rounded-2xl p-8 shadow-lg">
            <h4 class="font-bold text-koshouko-wood text-lg mb-4">🔒 Keamanan</h4>
            <p class="text-sm text-koshouko-text-muted mb-4">
                Untuk mengubah password, hubungi staf perpustakaan kami.
            </p>
            <a href="{{ route('login') }}" class="inline-block px-4 py-2 bg-white text-koshouko-wood rounded font-semibold hover:bg-koshouko-wood hover:text-white transition border-2 border-koshouko-wood text-sm">
                Keluar dari Akun
            </a>
        </div>
    </div>
</div>



