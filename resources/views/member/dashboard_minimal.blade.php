@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-16 px-6">
    <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
        <div class="text-4xl">👋</div>
        <h2 class="mt-4 text-2xl font-bold">Halo, {{ $user->name ?? 'Anggota' }} — dashboard (safe mode)</h2>
        <p class="mt-2 text-sm text-gray-600">Halaman dashboard utama sedang mengalami masalah rendering. Anda tetap dapat masuk dan mengakses beberapa fungsi dasar dari sini.</p>

        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('profile') }}" class="px-4 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg">Lihat Profil</a>
            <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="px-4 py-3 border rounded-lg">Keluar</button></form>
        </div>

        @if(!empty($errorMessage) && config('app.debug'))
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 text-sm text-yellow-900 rounded-lg text-left">
                <strong>Debug — pesan error (singkat):</strong>
                <pre class="mt-2 text-xs break-words">{{ $errorMessage }}</pre>
            </div>
        @endif

        <div class="mt-6 text-sm text-gray-500">Untuk perbaikan: jalankan <code>php artisan tail storage/logs/laravel.log</code> atau kirimkan 20 baris terakhir dari <code>storage/logs/laravel.log</code> kepada saya.</div>
    </div>
</div>
@endsection
