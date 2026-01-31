@extends('layouts.auth-app')

@section('title', 'QR Scanner - Panduan & Menu')
@section('page-title', '📱 Sistem QR Scanner Perpustakaan')

@section('content')
<style>
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        padding: 2rem 0;
    }

    .menu-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .menu-icon {
        font-size: 3rem;
        text-align: center;
    }

    .menu-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .menu-description {
        color: #666;
        font-size: 0.95rem;
        margin: 0;
        flex-grow: 1;
    }

    .menu-button {
        background: #8B5A2B;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .menu-card:hover .menu-button {
        background: #6b4423;
    }

    .intro-section {
        background: linear-gradient(135deg, #8B5A2B 0%, #d9534f 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 3rem;
        box-shadow: 0 4px 15px rgba(139, 90, 43, 0.2);
    }

    .intro-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 1rem 0;
    }

    .intro-text {
        font-size: 1.05rem;
        line-height: 1.6;
        margin: 0;
        opacity: 0.95;
    }

    .features-section {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 3rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .features-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin: 0 0 1.5rem 0;
    }

    .features-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .feature-item {
        padding: 1.5rem;
        background: #f9f9f9;
        border-left: 4px solid #8B5A2B;
        border-radius: 0.5rem;
    }

    .feature-icon {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }

    .feature-title {
        font-weight: 600;
        color: #333;
        margin: 0 0 0.5rem 0;
    }

    .feature-desc {
        color: #666;
        font-size: 0.9rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .menu-grid {
            grid-template-columns: 1fr;
        }

        .intro-section {
            padding: 1.5rem;
        }

        .intro-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container mx-auto px-4 py-8">
    <!-- Intro Section -->
    <div class="intro-section">
        <h1 class="intro-title">📱 Sistem QR Scanner Perpustakaan Digital</h1>
        <p class="intro-text">
            Sistem manajemen peminjaman buku yang modern dan efisien menggunakan teknologi QR Code. 
            Proses peminjaman dan pengembalian buku menjadi lebih cepat, mudah, dan akurat.
        </p>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <h2 class="features-title">✨ Fitur Utama</h2>
        <div class="features-list">
            <div class="feature-item">
                <div class="feature-icon">📚</div>
                <p class="feature-title">Scan Buku</p>
                <p class="feature-desc">Scan QR code buku untuk memulai proses peminjaman dengan cepat</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">👤</div>
                <p class="feature-title">Scan Member</p>
                <p class="feature-desc">Verifikasi identitas member melalui scanning kartu member</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">✓</div>
                <p class="feature-title">Auto Approval</p>
                <p class="feature-desc">Peminjaman langsung disetujui saat scanning tanpa tunggu admin</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">📊</div>
                <p class="feature-title">Tracking Real-time</p>
                <p class="feature-desc">Pantau semua peminjaman aktif dan status pengembalian</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">⚠️</div>
                <p class="feature-title">Deteksi Denda</p>
                <p class="feature-desc">Otomatis hitung denda untuk keterlambatan pengembalian</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🖨️</div>
                <p class="feature-title">Print QR Code</p>
                <p class="feature-desc">Cetak QR code untuk seluruh buku dan kartu member</p>
            </div>
        </div>
    </div>

    <!-- Menu Grid -->
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 2rem;">🎯 Menu Utama</h2>
    <div class="menu-grid">
        <!-- QR Scanner -->
        <a href="{{ route('qr.index') }}" class="menu-card">
            <div class="menu-icon">📱</div>
            <h3 class="menu-title">QR Scanner</h3>
            <p class="menu-description">Akses halaman utama scanner untuk memproses peminjaman dan pengembalian buku secara real-time</p>
            <div class="menu-button">Buka Scanner →</div>
        </a>

        <!-- History -->
        <a href="{{ route('qr.history') }}" class="menu-card">
            <div class="menu-icon">📋</div>
            <h3 class="menu-title">Riwayat Peminjaman</h3>
            <p class="menu-description">Lihat riwayat lengkap semua peminjaman, pengembalian, dan filter berdasarkan status atau tanggal</p>
            <div class="menu-button">Lihat History →</div>
        </a>

        <!-- Print Book QR -->
        <a href="{{ route('admin.qr.print-books') }}" class="menu-card">
            <div class="menu-icon">📖</div>
            <h3 class="menu-title">Cetak QR Code Buku</h3>
            <p class="menu-description">Generate dan cetak QR code untuk seluruh koleksi buku di perpustakaan</p>
            <div class="menu-button">Cetak QR Buku →</div>
        </a>

        <!-- Print Member QR -->
        <a href="{{ route('admin.qr.print-members') }}" class="menu-card">
            <div class="menu-icon">🎫</div>
            <h3 class="menu-title">Cetak Kartu Member</h3>
            <p class="menu-description">Generate dan cetak kartu member dengan QR code untuk seluruh member terdaftar</p>
            <div class="menu-button">Cetak Kartu →</div>
        </a>
    </div>

    <!-- How to Use Section -->
    <div style="margin-top: 3rem; background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #333; margin: 0 0 1.5rem 0;">📖 Panduan Penggunaan</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <!-- Peminjaman -->
            <div>
                <h3 style="color: #8B5A2B; margin: 0 0 1rem 0; font-size: 1.2rem;">📚 Proses Peminjaman</h3>
                <ol style="margin: 0; padding-left: 1.5rem; color: #666; line-height: 1.8;">
                    <li>Buka halaman <strong>QR Scanner</strong></li>
                    <li>Scan QR code buku (contoh: BOOK-1)</li>
                    <li>Sistem akan menampilkan data buku</li>
                    <li>Scan kartu member (contoh: USER-5)</li>
                    <li>Sistem otomatis mencatat peminjaman</li>
                    <li>Cetak atau tampilkan bukti peminjaman</li>
                </ol>
            </div>

            <!-- Pengembalian -->
            <div>
                <h3 style="color: #8B5A2B; margin: 0 0 1rem 0; font-size: 1.2rem;">📥 Proses Pengembalian</h3>
                <ol style="margin: 0; padding-left: 1.5rem; color: #666; line-height: 1.8;">
                    <li>Buka halaman <strong>QR Scanner</strong></li>
                    <li>Scan QR code buku yang dikembalikan</li>
                    <li>Klik tombol "Kembalikan Buku"</li>
                    <li>Sistem hitung denda jika terlambat</li>
                    <li>Catat peminjam untuk konfirmasi</li>
                    <li>Proses selesai</li>
                </ol>
            </div>

            <!-- Cetak QR Code -->
            <div>
                <h3 style="color: #8B5A2B; margin: 0 0 1rem 0; font-size: 1.2rem;">🖨️ Cetak QR Code</h3>
                <ol style="margin: 0; padding-left: 1.5rem; color: #666; line-height: 1.8;">
                    <li>Pilih <strong>Cetak QR Code Buku</strong> atau <strong>Cetak Kartu Member</strong></li>
                    <li>Gunakan search untuk filter item tertentu</li>
                    <li>Klik tombol <strong>Cetak</strong></li>
                    <li>Gunakan Print Dialog (Ctrl+P atau Cmd+P)</li>
                    <li>Pilih printer dan ukuran kertas</li>
                    <li>Klik Print</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Format QR Code Section -->
    <div style="margin-top: 3rem; background: #f9f9f9; padding: 2rem; border-radius: 1rem; border-left: 4px solid #8B5A2B;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #333; margin: 0 0 1.5rem 0;">🔑 Format QR Code</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div>
                <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #333;">📚 QR Code Buku</p>
                <p style="margin: 0; font-family: 'Courier New', monospace; background: white; padding: 0.75rem; border-radius: 0.25rem; color: #8B5A2B; font-weight: 600;">
                    BOOK-{ID_BUKU}
                </p>
                <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 0.9rem;">Contoh: <code style="background: white; padding: 0.2rem 0.5rem; border-radius: 0.2rem;">BOOK-1</code>, <code style="background: white; padding: 0.2rem 0.5rem; border-radius: 0.2rem;">BOOK-42</code></p>
            </div>

            <div>
                <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #333;">👤 QR Code Member</p>
                <p style="margin: 0; font-family: 'Courier New', monospace; background: white; padding: 0.75rem; border-radius: 0.25rem; color: #d9534f; font-weight: 600;">
                    USER-{ID_MEMBER}
                </p>
                <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 0.9rem;">Contoh: <code style="background: white; padding: 0.2rem 0.5rem; border-radius: 0.2rem;">USER-5</code>, <code style="background: white; padding: 0.2rem 0.5rem; border-radius: 0.2rem;">USER-123</code></p>
            </div>
        </div>
    </div>

    <!-- Rules Section -->
    <div style="margin-top: 3rem; background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #333; margin: 0 0 1.5rem 0;">⚙️ Aturan & Ketentuan Peminjaman</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="padding: 1.5rem; background: #f0f0f0; border-radius: 0.5rem;">
                <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #333;">📚 Buku per Member</p>
                <p style="margin: 0; color: #666; font-size: 1.2rem; font-weight: 700;">Maks 5 Buku</p>
            </div>

            <div style="padding: 1.5rem; background: #f0f0f0; border-radius: 0.5rem;">
                <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #333;">📅 Durasi Peminjaman</p>
                <p style="margin: 0; color: #666; font-size: 1.2rem; font-weight: 700;">14 Hari</p>
            </div>

            <div style="padding: 1.5rem; background: #f0f0f0; border-radius: 0.5rem;">
                <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #333;">🔄 Perpanjangan</p>
                <p style="margin: 0; color: #666; font-size: 1.2rem; font-weight: 700;">Max 2 Kali</p>
            </div>

            <div style="padding: 1.5rem; background: #f0f0f0; border-radius: 0.5rem;">
                <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #333;">💰 Denda/Hari</p>
                <p style="margin: 0; color: #666; font-size: 1.2rem; font-weight: 700;">Rp 5.000</p>
            </div>
        </div>
    </div>
</div>

@endsection
