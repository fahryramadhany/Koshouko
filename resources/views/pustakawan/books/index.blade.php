@extends('layouts.auth-app')

@section('title', 'Kelola Buku - Perpustakaan Digital')
@section('page-title', '📖 Kelola Buku')

@section('content')

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-koshouko-text-muted">Total Buku: <strong class="text-koshouko-text text-lg">{{ \App\Models\Book::count() }}</strong></p>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="openReportModal('books')" class="px-6 py-3 border-2 border-koshouko-wood text-koshouko-wood rounded-lg font-semibold transition text-center hover:bg-koshouko-cream">
                📄 Buat Laporan
            </button>
            <a href="{{ route('librarian.books.categories') }}" class="px-6 py-3 border-2 border-koshouko-border text-koshouko-wood rounded-lg font-semibold transition text-center hover:bg-koshouko-cream">
                📚 Kelola Kategori
            </a>
            <a href="{{ route('librarian.books.create') }}" class="px-6 py-3 btn-koshouko rounded-lg font-semibold transition text-center">
                ➕ Tambah Buku Baru
            </a>
        </div>
    </div>

    <div class="gradient-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="section-header">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Cover / Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Penulis</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-koshouko-text uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-koshouko-text uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-koshouko-border">
                    @forelse($books as $book)
                        <tr class="hover:bg-koshouko-cream/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    {{-- Cover Image --}}
                                    <div class="flex-shrink-0 w-12 h-16 rounded border border-koshouko-border overflow-hidden bg-gradient-to-br from-koshouko-wood to-koshouko-red flex items-center justify-center">
                                        @if($book->cover_image && file_exists(public_path($book->cover_image)))
                                            <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl opacity-50">📖</span>
                                        @endif
                                    </div>
                                    {{-- Title --}}
                                    <div class="flex-1">
                                        <p class="font-semibold text-koshouko-text">{{ Str::limit($book->title, 40) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-koshouko-text-muted">{{ $book->author }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-koshouko-wood/10 text-koshouko-wood rounded-full text-xs font-semibold border border-koshouko-border">
                                    {{ $book->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-koshouko-text">{{ $book->available_copies }}/{{ $book->total_copies }}</p>
                                    <p class="text-xs text-koshouko-text-muted">Tersedia/Total</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $book->status === 'available' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $book->status === 'unavailable' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $book->status === 'archived' ? 'bg-koshouko-border text-koshouko-text-muted' : '' }}
                                ">
                                    {{ ucfirst($book->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('librarian.books.edit', $book) }}" class="text-koshouko-wood hover:text-koshouko-red font-semibold text-xs">Edit</a>
                                    <form action="{{ route('librarian.books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-xs">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-koshouko-text-muted">Belum ada buku</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $books->links() }}
    </div>

    <!-- Report Modal -->
    <div id="reportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="closeReportModal(event)">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96" onclick="event.stopPropagation()">
            <h3 class="text-lg font-bold text-koshouko-text mb-4">📄 Buat Laporan Buku</h3>
            <form id="reportForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-koshouko-text mb-2">Jenis Laporan *</label>
                    <select name="report_type" class="w-full px-3 py-2 border-2 border-koshouko-border rounded-lg bg-white text-koshouko-text focus:outline-none focus:border-koshouko-wood" required>
                        <option value="summary">Ringkasan (Judul, Penulis, Stok)</option>
                        <option value="detailed">Rinci (Lengkap dengan Kategori & Status)</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeReportModal()" class="px-4 py-2 border-2 border-koshouko-border rounded-lg text-koshouko-text font-semibold hover:bg-koshouko-cream transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-koshouko-wood text-white rounded-lg font-semibold hover:bg-koshouko-red transition">Cetak PDF</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
    function openReportModal(type) {
        const modal = document.getElementById('reportModal');
        modal.classList.remove('hidden');
    }

    function closeReportModal(event) {
        if (event && event.target.id !== 'reportModal') return;
        document.getElementById('reportModal').classList.add('hidden');
    }

    function generatePDFReport(url, reportType, filename) {
        console.log('Generating PDF...', {url, reportType, filename});
        
        // Get CSRF token properly
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                      document.querySelector('input[name="_token"]')?.value;
        
        if (!token) {
            alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        console.log('CSRF Token exists:', !!token);
        
        // Prepare form data
        const params = new URLSearchParams();
        params.append('report_type', reportType);
        params.append('_token', token);
        
        fetch(url, {
            method: 'POST',
            body: params,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP Error! Status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            console.log('HTML received, length:', html.length);
            if (!html || html.length === 0) {
                throw new Error('Respons kosong dari server');
            }
            
            // Create element for PDF conversion
            const element = document.createElement('div');
            element.innerHTML = html;
            element.style.padding = '20px';
            
            const opt = {
                margin: [10, 10, 10, 10],
                filename: filename + '-' + new Date().toISOString().split('T')[0] + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, allowTaint: true },
                jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
            };
            
            console.log('Memulai konversi PDF...');
            return html2pdf().set(opt).from(element).save();
        })
        .then(() => {
            console.log('PDF berhasil disimpan');
            closeReportModal();
        })
        .catch(error => {
            console.error('Error lengkap:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
    }

    // Setup form submission after DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        const reportForm = document.getElementById('reportForm');
        if (reportForm) {
            reportForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const reportType = document.querySelector('select[name="report_type"]')?.value || 'summary';
                generatePDFReport('{{ route('librarian.books.report') }}', reportType, 'Laporan-Buku');
            });
        }
    });
    </script>
@endsection
