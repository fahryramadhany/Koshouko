@extends('layouts.auth-app')

@section('title', 'QR Scanner - Perpustakaan Digital')
@section('page-title', '📱 Scanner Peminjaman Buku')

@section('content')
<style>
    .scanner-container {
        background: #fff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .scanner-input {
        font-size: 1.1rem;
        padding: 1rem;
        border: 2px solid #8B5A2B;
        border-radius: 0.5rem;
        width: 100%;
        font-family: 'Courier New', monospace;
        background: #FEF9F3;
    }

    .scanner-input:focus {
        outline: none;
        border-color: #d9534f;
        box-shadow: 0 0 0 3px rgba(217, 83, 79, 0.1);
    }

    .info-box {
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin: 1rem 0;
        border-left: 4px solid;
    }

    .info-box.success {
        background: #d4edda;
        border-color: #28a745;
        color: #155724;
    }

    .info-box.error {
        background: #f8d7da;
        border-color: #dc3545;
        color: #721c24;
    }

    .info-box.info {
        background: #d1ecf1;
        border-color: #17a2b8;
        color: #0c5460;
    }

    .book-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin: 1rem 0;
        transition: all 0.3s ease;
    }

    .book-card:hover {
        border-color: #8B5A2B;
        box-shadow: 0 4px 15px rgba(139, 90, 43, 0.1);
    }

    .book-card.selected {
        border-color: #28a745;
        background: #f0fff4;
    }

    .member-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin: 1rem 0;
    }

    .member-card.success {
        border-color: #28a745;
        background: #f0fff4;
    }

    .member-card.warning {
        border-color: #ffc107;
        background: #fffbf0;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
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

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-success:hover {
        background: #218838;
    }

    .recent-list {
        background: #fff;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .recent-item {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .recent-item:last-child {
        border-bottom: none;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-approved {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-returned {
        background: #d1ecf1;
        color: #0c5460;
    }

    .step-indicator {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        justify-content: space-between;
    }

    .step {
        flex: 1;
        padding: 1rem;
        border-radius: 0.5rem;
        background: #f0f0f0;
        text-align: center;
        font-weight: 600;
    }

    .step.active {
        background: #8B5A2B;
        color: white;
    }

    .step.completed {
        background: #28a745;
        color: white;
    }

    .loading {
        display: none;
        text-align: center;
        padding: 2rem;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #8B5A2B;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="container mx-auto px-4 py-8">
    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step active" id="step1">1️⃣ Scan QR/Barcode</div>
        <div class="step" id="step2">2️⃣ Verifikasi Data</div>
        <div class="step" id="step3">3️⃣ Proses</div>
    </div>

    <!-- Scanner Container -->
    <div class="scanner-container">
        <h2 style="margin-bottom: 1.5rem; color: #333;">📱 Input QR Code / Barcode</h2>
        
        <div style="margin-bottom: 1rem;">
            <label for="qr-input" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                Masukkan Kode atau Scan QR Code:
            </label>
            <input 
                type="text" 
                id="qr-input" 
                class="scanner-input" 
                placeholder="Contoh: BOOK-1 atau USER-5"
                autocomplete="off"
                autofocus
            >
        </div>

        <div style="font-size: 0.9rem; color: #666; padding: 1rem; background: #f5f5f5; border-radius: 0.5rem;">
            💡 <strong>Cara Penggunaan:</strong>
            <ul style="margin: 0.5rem 0 0 1.5rem;">
                <li>Scan kode buku atau kartu member</li>
                <li>Sistem akan mendeteksi tipe kode secara otomatis</li>
                <li>Ikuti instruksi yang muncul</li>
            </ul>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
        <p>Memproses kode...</p>
    </div>

    <!-- Result Container -->
    <div id="result-container"></div>

    <!-- Recent Borrowings -->
    <div style="margin-top: 2rem;">
        <h3 style="margin-bottom: 1rem; color: #333;">📋 Peminjaman Terbaru</h3>
        <div class="recent-list">
            @if($recentBorrowings->count() > 0)
                @foreach($recentBorrowings as $borrowing)
                    <div class="recent-item">
                        <div>
                            <p style="margin: 0 0 0.5rem 0; font-weight: 600;">{{ $borrowing->user->name }}</p>
                            <p style="margin: 0 0 0.25rem 0; font-size: 0.9rem; color: #666;">{{ $borrowing->book->title }}</p>
                            <p style="margin: 0; font-size: 0.85rem; color: #999;">{{ $borrowing->borrowed_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="status-badge status-{{ $borrowing->status }}">{{ strtoupper($borrowing->status) }}</span>
                    </div>
                @endforeach
            @else
                <p style="text-align: center; color: #999; padding: 2rem;">Belum ada peminjaman</p>
            @endif
        </div>
    </div>
</div>

<script>
    let currentData = null;

    const qrInput = document.getElementById('qr-input');
    const resultContainer = document.getElementById('result-container');
    const loading = document.getElementById('loading');

    // Handle input
    qrInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const qrCode = this.value.trim().toUpperCase();
            if (qrCode) {
                scanQR(qrCode);
                this.value = '';
            }
        }
    });

    function showLoading() {
        loading.style.display = 'block';
        resultContainer.innerHTML = '';
    }

    function hideLoading() {
        loading.style.display = 'none';
    }

    function scanQR(qrCode) {
        showLoading();

        fetch('{{ route("qr.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ qr_code: qrCode })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                currentData = data.data;
                displayResult(data);
            } else {
                showError(data.message, data.data);
            }
        })
        .catch(error => {
            hideLoading();
            showError('Terjadi kesalahan: ' + error.message);
        });
    }

    function displayResult(data) {
        const type = data.data.type;

        if (type === 'book') {
            displayBookResult(data.data);
        } else if (type === 'member') {
            displayMemberResult(data.data);
        }
    }

    function displayBookResult(data) {
        const book = data.book;
        resultContainer.innerHTML = `
            <div class="book-card selected" id="book-${book.id}">
                <h3 style="margin: 0 0 1rem 0; color: #333;">📚 Buku Ditemukan</h3>
                <p style="margin: 0.5rem 0;"><strong>Judul:</strong> ${book.title}</p>
                <p style="margin: 0.5rem 0;"><strong>Pengarang:</strong> ${book.author}</p>
                <p style="margin: 0.5rem 0;"><strong>Penerbit:</strong> ${book.publisher || 'N/A'}</p>
                <p style="margin: 0.5rem 0;"><strong>ISBN:</strong> ${book.isbn || 'N/A'}</p>
                
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="selectBook(${book.id})">
                        ✓ Pilih Buku Ini
                    </button>
                    <button class="btn btn-secondary" onclick="resetScanner()">
                        ✕ Batal
                    </button>
                </div>
            </div>
        `;
        updateSteps(1, 2);
    }

    function displayMemberResult(data) {
        const member = data.user;
        let html = `
            <div class="member-card ${data.can_borrow ? 'success' : 'warning'}">
                <h3 style="margin: 0 0 1rem 0; color: #333;">👤 Member Ditemukan</h3>
                <p style="margin: 0.5rem 0;"><strong>Nama:</strong> ${member.name}</p>
                <p style="margin: 0.5rem 0;"><strong>Email:</strong> ${member.email}</p>
                <p style="margin: 0.5rem 0;"><strong>Telepon:</strong> ${member.phone}</p>
        `;

        if (data.unpaid_fines > 0) {
            html += `<p style="margin: 0.5rem 0; color: #dc3545;"><strong>⚠️ Denda Belum Dibayar:</strong> Rp ${formatCurrency(data.unpaid_fines)}</p>`;
        }

        if (data.active_borrowings.length > 0) {
            html += `<div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e0e0e0;">
                <p style="margin: 0 0 0.5rem 0; font-weight: 600;">Peminjaman Aktif:</p>
                <ul style="margin: 0; padding: 0 1.5rem;">`;
            
            data.active_borrowings.forEach(b => {
                const overdueClass = b.is_overdue ? 'color: #dc3545;' : '';
                html += `<li style="margin: 0.25rem 0; ${overdueClass}">
                    ${b.book_title} ${b.is_overdue ? '(⚠️ Terlambat)' : ''}
                </li>`;
            });
            
            html += `</ul></div>`;
        }

        html += `
            <div class="action-buttons">
                ${data.can_borrow ? `
                    <button class="btn btn-success" onclick="selectMember(${member.id})">
                        ✓ Pilih Member Ini
                    </button>
                ` : `
                    <button class="btn btn-primary" style="opacity: 0.5; cursor: not-allowed;" disabled>
                        Member Tidak Bisa Meminjam
                    </button>
                `}
                <button class="btn btn-secondary" onclick="resetScanner()">
                    ✕ Batal
                </button>
            </div>
        </div>`;

        resultContainer.innerHTML = html;
        updateSteps(1, 2);
    }

    function selectBook(bookId) {
        if (!currentData || currentData.type !== 'book') return;
        
        // Tampilkan instruksi untuk scan member
        resultContainer.innerHTML = `
            <div class="info-box info">
                ℹ️ <strong>Langkah Selanjutnya:</strong> Silakan scan kartu member atau masukkan ID member
            </div>
        `;
        updateSteps(2, 2);
        qrInput.focus();
        
        // Set state untuk menunggu member
        currentData.waiting_for = 'member';
    }

    function selectMember(userId) {
        if (!currentData || currentData.type !== 'member') return;

        // Check apakah sudah ada buku yang dipilih
        if (!currentData.waiting_for || currentData.waiting_for !== 'member') {
            if (!currentData.book_id) {
                showError('Harap pilih buku terlebih dahulu');
                return;
            }
        }

        createBorrowing(currentData.book_id || window.selectedBookId, userId);
    }

    function createBorrowing(bookId, userId) {
        showLoading();

        fetch('{{ route("qr.create-borrowing") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                book_id: bookId,
                user_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showSuccess(data);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            hideLoading();
            showError('Terjadi kesalahan: ' + error.message);
        });
    }

    function showSuccess(data) {
        resultContainer.innerHTML = `
            <div class="info-box success">
                <strong>✓ Berhasil!</strong> ${data.message}
                <hr style="margin: 1rem 0; border: none; border-top: 1px solid rgba(0,0,0,0.2);">
                <p><strong>Member:</strong> ${data.data.user_name}</p>
                <p><strong>Buku:</strong> ${data.data.book_title}</p>
                <p><strong>Tanggal Pinjam:</strong> ${data.data.borrowed_at}</p>
                <p><strong>Batas Kembali:</strong> ${data.data.due_date}</p>
                <button class="btn btn-primary" onclick="resetScanner();" style="width: 100%; margin-top: 1rem;">
                    Proses Peminjaman Berikutnya
                </button>
            </div>
        `;
        updateSteps(3);
    }

    function showError(message, extraData = null) {
        let html = `<div class="info-box error"><strong>✕ Error!</strong> ${message}`;
        
        if (extraData && extraData.borrowing) {
            html += `<hr style="margin: 1rem 0; border: none; border-top: 1px solid rgba(0,0,0,0.2);">
                <p><strong>Peminjam Saat Ini:</strong> ${extraData.borrowing.user.name}</p>
                <p><strong>Tanggal Pinjam:</strong> ${new Date(extraData.borrowing.borrowed_at).toLocaleDateString('id-ID')}</p>
                <button class="btn btn-danger" onclick="returnBook(${extraData.borrowing.id});" style="margin-top: 1rem;">
                    Kembalikan Buku Ini Sekarang
                </button>`;
        }
        
        html += `<button class="btn btn-secondary" onclick="resetScanner();" style="margin-top: 1rem;">
            Ulangi Scan
        </button></div>`;
        
        resultContainer.innerHTML = html;
        updateSteps(1);
    }

    function returnBook(borrowingId) {
        showLoading();

        fetch('{{ route("qr.return-book") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ borrowing_id: borrowingId })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showSuccess(data);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            hideLoading();
            showError('Terjadi kesalahan: ' + error.message);
        });
    }

    function resetScanner() {
        resultContainer.innerHTML = '';
        currentData = null;
        qrInput.value = '';
        qrInput.focus();
        updateSteps(1);
        
        // Reload recent borrowings
        location.reload();
    }

    function updateSteps(active, completed = null) {
        document.getElementById('step1').className = 'step' + (active >= 1 ? ' active' : '') + (completed >= 1 ? ' completed' : '');
        document.getElementById('step2').className = 'step' + (active >= 2 ? ' active' : '') + (completed >= 2 ? ' completed' : '');
        document.getElementById('step3').className = 'step' + (active >= 3 ? ' active' : '') + (completed >= 3 ? ' completed' : '');
    }

    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value);
    }

    // Auto-focus input saat halaman load
    window.addEventListener('load', function() {
        qrInput.focus();
    });
</script>

@endsection
