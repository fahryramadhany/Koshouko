@extends('layouts.app')

@section('title', 'Edit Foto Profil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-8 text-gray-800">Kelola Foto Profil</h1>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <h2 class="text-red-800 font-semibold mb-2">Terjadi kesalahan:</h2>
                    <ul class="list-disc list-inside text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Current Photo -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Foto Profil Saat Ini</h2>
                <div class="flex justify-center">
                    @if ($user->profile_photo)
                        <div class="relative">
                            <img src="{{ Storage::url($user->profile_photo) }}" 
                                 alt="Foto Profil {{ $user->name }}"
                                 class="w-32 h-32 rounded-full object-cover border-4 border-blue-500">
                            <form action="{{ route('profile-photo.destroy', $user) }}" method="POST" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil?')">
                                    Hapus Foto
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center border-4 border-gray-400">
                            <span class="text-gray-600 text-center">Tidak ada foto</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upload Form -->
            <div class="border-t pt-8">
                <h2 class="text-xl font-semibold mb-6 text-gray-700">Upload Foto Baru</h2>
                
                <form action="{{ route('profile-photo.store', $user) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf

                    <div>
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Foto (Maksimal 2MB)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer"
                             onclick="document.getElementById('profile_photo').click()">
                            <input type="file" 
                                   name="profile_photo" 
                                   id="profile_photo"
                                   class="hidden"
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                   onchange="updateFileName(this)">
                            <div class="space-y-2">
                                <p class="text-gray-600">Klik untuk memilih atau drag & drop file</p>
                                <p class="text-sm text-gray-500">Format: JPG, PNG, GIF, WebP (Max: 2MB)</p>
                                <p id="file-name" class="text-sm text-blue-600 font-semibold mt-2"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" 
                                class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Upload Foto
                        </button>
                        <a href="{{ route('profile') }}" 
                           class="flex-1 px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-medium text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- Info -->
            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-semibold text-blue-900 mb-2">Panduan:</h3>
                <ul class="list-disc list-inside text-sm text-blue-800 space-y-1">
                    <li>Gunakan foto dengan format JPG, PNG, GIF, atau WebP</li>
                    <li>Ukuran file tidak boleh lebih dari 2MB</li>
                    <li>Foto akan dipotong menjadi bentuk persegi panjang untuk profil</li>
                    <li>Gunakan foto yang jelas dan profesional</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0] ? input.files[0].name : '';
    document.getElementById('file-name').textContent = fileName ? `✓ File dipilih: ${fileName}` : '';
}
</script>
@endsection
