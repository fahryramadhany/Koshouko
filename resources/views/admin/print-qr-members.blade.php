@extends('layouts.auth-app')

@section('title', 'Cetak QR Code Member - Perpustakaan Digital')
@section('page-title', '🖨️ Cetak QR Code Kartu Member')

@section('content')
<style>
    .print-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        padding: 2rem;
    }

    .member-card {
        background: white;
        border: 2px solid #8B5A2B;
        border-radius: 0.75rem;
        padding: 1.5rem;
        text-align: center;
        page-break-inside: avoid;
        box-shadow: 0 4px 15px rgba(139, 90, 43, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #8B5A2B 0%, #d9534f 100%);
        color: white;
        padding: 1rem;
        margin: -1.5rem -1.5rem 1rem -1.5rem;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
    }

    .member-info {
        margin: 1rem 0;
        text-align: left;
    }

    .member-info p {
        margin: 0.5rem 0;
        font-size: 0.9rem;
    }

    .member-info strong {
        display: inline-block;
        width: 100px;
        color: #333;
    }

    .qr-code {
        width: 250px;
        height: 250px;
        margin: 1.5rem auto;
        border: 2px solid #333;
        padding: 0.75rem;
        background: white;
    }

    .qr-code img {
        width: 100%;
        height: 100%;
    }

    .qr-code-text {
        font-size: 0.8rem;
        color: #666;
        font-family: 'Courier New', monospace;
        background: #f5f5f5;
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin: 0.75rem 0;
    }

    .control-section {
        background: white;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .btn-primary {
        background: #8B5A2B;
        color: white;
    }

    .btn-primary:hover {
        background: #6b4423;
        box-shadow: 0 4px 12px rgba(139, 90, 43, 0.3);
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #333;
    }

    .btn-secondary:hover {
        background: #d0d0d0;
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .filter-input {
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        font-size: 1rem;
    }

    @media print {
        .control-section {
            display: none;
        }

        .print-container {
            gap: 1rem;
            padding: 0;
        }

        .member-card {
            box-shadow: none;
            page-break-inside: avoid;
        }
    }

    @media (max-width: 768px) {
        .print-container {
            grid-template-columns: 1fr;
        }

        .filter-group {
            flex-direction: column;
        }

        .filter-input {
            width: 100%;
        }

        .member-card {
            padding: 1rem;
        }

        .qr-code {
            width: 180px;
            height: 180px;
        }
    }
</style>

<div class="container mx-auto px-4 py-8">
    <!-- Control Section -->
    <div class="control-section">
        <h3 style="margin: 0 0 1rem 0; color: #333;">Opsi Cetak Kartu Member</h3>
        <div class="filter-group">
            <input 
                type="text" 
                class="filter-input" 
                id="search" 
                placeholder="Cari member..."
                style="flex: 1;"
            >
        </div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak Kartu</button>
            <button class="btn btn-secondary" onclick="selectAll()">✓ Pilih Semua</button>
            <button class="btn btn-secondary" onclick="deselectAll()">✕ Batal Pilih</button>
        </div>
    </div>

    <!-- Member Cards -->
    <div class="print-container" id="card-container">
        @forelse($users as $user)
            <div class="member-card" data-name="{{ $user->name }}">
                <div class="card-header">
                    <p class="card-title">📚 KARTU MEMBER</p>
                </div>

                <div class="member-info">
                    <p><strong>Nama:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>No. Member:</strong> {{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</p>
                    @if($user->phone)
                        <p><strong>Telepon:</strong> {{ $user->phone }}</p>
                    @endif
                </div>

                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=USER-{{ $user->id }}" 
                         alt="QR Code {{ $user->id }}"
                         loading="lazy"
                    >
                </div>

                <p class="qr-code-text">USER-{{ $user->id }}</p>

                <p style="font-size: 0.8rem; color: #666; margin-top: 1rem; border-top: 1px solid #e0e0e0; padding-top: 1rem;">
                    Berlaku Seumur Hidup
                </p>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                <p style="font-size: 3rem; margin: 0;">👤</p>
                <p style="color: #999;">Tidak ada member</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    const searchInput = document.getElementById('search');
    const cardContainer = document.getElementById('card-container');
    const memberCards = cardContainer.querySelectorAll('.member-card');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        memberCards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            if (name.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    function selectAll() {
        memberCards.forEach(card => {
            card.style.opacity = '1';
        });
    }

    function deselectAll() {
        memberCards.forEach(card => {
            card.style.opacity = '0.5';
        });
    }
</script>

@endsection
