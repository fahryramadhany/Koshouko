@extends('layouts.auth-app')

@section('title', 'Laporan User - Perpustakaan Digital')
@section('page-title', '📊 Laporan User')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-pages.css') }}">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-koshouko-text-muted">Total User: <strong class="text-koshouko-text text-lg">{{ \App\Models\User::count() }}</strong></p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-6 py-3 btn-koshouko rounded-lg font-semibold transition text-center">
                🖨️ Cetak Laporan
            </button>
        </div>
    </div>

    <div class="gradient-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="section-header">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-koshouko-border">
                    @forelse($users as $user)
                        <tr class="hover:bg-koshouko-cream/50 transition">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-koshouko-text">{{ $user->name }}</p>
                                <p class="text-xs text-koshouko-text-muted">{{ $user->member_id }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-koshouko-text-muted">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-koshouko-red/10 text-koshouko-red rounded-full text-xs font-semibold border border-koshouko-border">
                                    {{ $user->role->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}
                                ">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-koshouko-text-muted">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-koshouko-text-muted">Belum ada user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>

    <style media="print">
        .mb-6, button, .mt-6 {
            display: none;
        }
        body {
            background: white;
        }
        .gradient-card {
            border: none;
            box-shadow: none;
        }
    </style>
@endsection
