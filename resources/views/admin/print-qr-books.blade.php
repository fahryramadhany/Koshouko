@extends('layouts.auth-app')

@section('title', 'Cetak QR Code Buku - Perpustakaan Digital')
@section('page-title', '🖨️ Cetak QR Code Buku')

@section('content')
<style>
    .print-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
        padding: 2rem;
    }

    .qr-card {
        background: white;
        border: 2px solid #ddd;
        border-radius: 0.75rem;
        padding: 1.5rem;
        text-align: center;
        page-break-inside: avoid;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .qr-code {
        width: 200px;
        height: 200px;
        margin: 1rem auto;
        border: 2px solid #333;
        padding: 0.5rem;
        background: white;
    }

    .qr-code img {
        width: 100%;
        height: 100%;
    }

    .qr-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #333;
        margin: 1rem 0 0.5rem 0;
        word-break: break-word;
    }

    .qr-code-text {
        font-size: 0.85rem;
        color: #666;
        font-family: 'Courier New', monospace;
        background: #f5f5f5;
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
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

        .qr-card {
            page-break-inside: avoid;
            box-shadow: none;
            border: 1px solid #999;
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
    }
</style>

<div class="container mx-auto px-4 py-8">
    <!-- Control Section -->
    <div class="control-section">
        <h3 style="margin: 0 0 1rem 0; color: #333;">Opsi Cetak</h3>
        <div class="filter-group">
            <input 
                type="text" 
                class="filter-input" 
                id="search" 
                placeholder="Cari buku..."
                style="flex: 1;"
            >
        </div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak</button>
            <button class="btn btn-secondary" onclick="selectAll()">✓ Pilih Semua</button>
            <button class="btn btn-secondary" onclick="deselectAll()">✕ Batal Pilih</button>
        </div>
    </div>

    <!-- QR Code Cards -->
    <div class="print-container" id="qr-container">
        @forelse($books as $book)
            <div class="qr-card" data-title="{{ $book->title }}">
                <p class="qr-label">{{ $book->title }}</p>
                <p style="font-size: 0.8rem; color: #999;">{{ $book->author }}</p>
                
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=BOOK-{{ $book->id }}" 
                         alt="QR Code {{ $book->id }}"
                         loading="lazy"
                    >
                </div>
                
                <p class="qr-code-text">BOOK-{{ $book->id }}</p>
                <p style="font-size: 0.8rem; color: #666;">ISBN: {{ $book->isbn ?? 'N/A' }}</p>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                <p style="font-size: 3rem; margin: 0;">📚</p>
                <p style="color: #999;">Tidak ada buku</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    const searchInput = document.getElementById('search');
    const qrContainer = document.getElementById('qr-container');
    const qrCards = qrContainer.querySelectorAll('.qr-card');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        qrCards.forEach(card => {
            const title = card.dataset.title.toLowerCase();
            if (title.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    function selectAll() {
        qrCards.forEach(card => {
            card.style.opacity = '1';
        });
    }

    function deselectAll() {
        qrCards.forEach(card => {
            card.style.opacity = '0.5';
        });
    }
</script>

@endsection
