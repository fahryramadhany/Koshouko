@extends('layouts.auth-app')

@section('title', 'Katalog Buku - Perpustakaan Digital')
@section('page-title', '📚 Katalog Buku')

@section('content')
<link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Filter Sidebar -->
        <div class="lg:col-span-1">
            <div class="gradient-card rounded-2xl shadow-lg p-8 sticky top-20">
                <h3 class="text-xl font-bold section-title mb-6">🔍 Filter</h3>
                
                <form action="{{ route('books.index') }}" method="GET" class="space-y-5">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Cari Buku</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full px-4 py-2 border-2 border-koshouko-border bg-koshouko-cream text-koshouko-text rounded-lg focus:outline-none focus:border-koshouko-wood placeholder-koshouko-text-muted"
                            placeholder="Judul, penulis...">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Kategori</label>
                        <select name="category" class="w-full px-4 py-2 border-2 border-koshouko-border bg-koshouko-cream text-koshouko-text rounded-lg focus:outline-none focus:border-koshouko-wood">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Availability -->
                    <div>
                        <label class="block text-sm font-semibold text-koshouko-text mb-2">Ketersediaan</label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="available" {{ request('available') ? 'checked' : '' }} class="w-4 h-4 rounded text-koshouko-wood border-koshouko-border">
                            <span class="text-sm text-koshouko-text">Hanya Buku Tersedia</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition btn-koshouko">
                        Terapkan Filter
                    </button>

                    @if(request('search') || request('category') || request('available'))
                        <a href="{{ route('books.index') }}" class="block w-full px-4 py-3 text-center border-2 border-koshouko-border rounded-lg text-koshouko-wood font-semibold hover:bg-koshouko-cream-light transition">
                            Reset Filter
                        </a>
                    @endif
                </form>

                <!-- Statistics -->
                <div class="mt-8 pt-8 border-t-2 border-koshouko-border">
                    <h4 class="font-semibold text-koshouko-text mb-4 text-sm uppercase tracking-wider">📊 Statistik</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-koshouko-text">
                            <span>Total Buku</span>
                            <span class="font-semibold">{{ $books->total() }}</span>
                        </div>
                        <div class="flex justify-between text-koshouko-text">
                            <span>Kategori</span>
                            <span class="font-semibold">{{ $categories->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="lg:col-span-3">
            <!-- Books Count -->
            <div class="mb-6 flex items-center justify-between">
                <p class="text-koshouko-text-muted">
                    Menampilkan <strong class="text-koshouko-text">{{ $books->count() }}</strong> dari <strong class="text-koshouko-text">{{ $books->total() }}</strong> buku
                </p>
            </div>

            @if($books->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($books as $book)
                        <div class="gradient-card rounded-2xl overflow-hidden hover:shadow-2xl transition duration-300 group">
                            <!-- Book Cover -->
                            <div class="relative h-64 bg-gradient-to-br from-koshouko-wood/10 to-koshouko-red/10 flex items-center justify-center overflow-hidden">
                                @if($book->cover_image && file_exists(public_path($book->cover_image)))
                                    <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="text-6xl opacity-30 group-hover:scale-110 transition duration-300">📖</div>
                                @endif
                                
                                <!-- Bookmark Button -->
                                <form action="{{ route('books.bookmark', $book) }}" method="POST" class="absolute top-3 right-3">
                                    @csrf
                                    <button type="submit" class="bg-koshouko-paper rounded-full p-2 shadow-md hover:shadow-lg transition border border-koshouko-border">
                                        <span class="text-xl">
                                            {{ in_array($book->id, $bookmarkedIds) ? '⭐' : '☆' }}
                                        </span>
                                    </button>
                                </form>

                                <!-- Availability Badge -->
                                <div class="absolute top-3 left-3">
                                    @if($book->available_copies > 0)
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                            {{ $book->available_copies }} Tersedia
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                            Tidak Tersedia
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Book Info -->
                            <div class="p-5">
                                <h3 class="font-bold text-koshouko-text line-clamp-2 mb-1">{{ $book->title }}</h3>
                                <p class="text-sm text-koshouko-text-muted mb-3">{{ $book->author }}</p>
                                
                                <div class="flex items-center justify-between mb-4 pb-4 border-b border-koshouko-border">
                                    <span class="text-xs bg-koshouko-wood/10 text-koshouko-wood px-3 py-1 rounded-full font-semibold">{{ $book->category->name }}</span>
                                    <span class="text-xs text-koshouko-text-muted">{{ $book->publication_year ?? '-' }}</span>
                                </div>

                                <p class="text-sm text-koshouko-text-muted mb-4 line-clamp-2">{{ $book->description ?? 'Tidak ada deskripsi' }}</p>

                                <div class="flex gap-2">
                                    <a href="{{ route('books.show', $book) }}" class="flex-1 px-3 py-2 bg-koshouko-wood/10 text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-wood/20 transition text-center text-sm border border-koshouko-border">
                                        Lihat Detail
                                    </a>
                                    @if($book->available_copies > 0)
                                        <button type="button" onclick="borrowBook({{ $book->id }})" class="flex-1 px-3 py-2 btn-koshouko rounded-lg font-semibold transition text-sm">
                                            Pinjam
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            @else
                <div class="gradient-card rounded-2xl p-12 text-center">
                    <p class="text-4xl mb-4">🔍</p>
                    <h3 class="text-2xl font-bold text-koshouko-text mb-2">Buku Tidak Ditemukan</h3>
                    <p class="text-koshouko-text-muted mb-6">Coba ubah filter atau cari dengan kata kunci yang berbeda</p>
                    <a href="{{ route('books.index') }}" class="inline-block px-6 py-3 btn-koshouko rounded-lg font-semibold transition">
                        Reset Pencarian
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Borrow Modal -->
    <div id="borrowModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4" role="dialog" aria-modal="true" aria-labelledby="borrowModalSimpleTitle" aria-describedby="borrowFormErrors">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" tabindex="-1">
            <h3 class="text-xl font-bold text-gray-900 mb-4">📚 Pinjam Buku</h3>
            <p class="text-gray-600 mb-6" id="borrowBookTitle"></p>
            
            <form id="borrowForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="from" value="modal">
                <div id="borrowFormErrors" role="status" aria-live="polite" class="sr-only"></div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-900">
                    <p class="font-semibold mb-2">Ketentuan Peminjaman:</p>
                    <ul class="space-y-1 text-xs">
                        <li>• Durasi peminjaman: 14 hari</li>
                        <li>• Dapat diperpanjang 2 kali</li>
                        <li>• Denda keterlambatan: Rp 5.000/hari</li>
                    </ul>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeBorrowModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg font-semibold hover:shadow-md transition">
                        Pinjam Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function borrowBook(bookId) {
            const modal = document.getElementById('borrowModal');
            const form = document.getElementById('borrowForm');
            const titleEl = document.getElementById('borrowBookTitle');
            
            // Get book title from the card (you might want to use an API call instead)
            const bookCard = event?.target?.closest('[class*="grid"]')?.querySelector('h3');
            if (bookCard && titleEl) {
                titleEl.textContent = 'Anda akan meminjam: ' + bookCard.textContent;
            }

            // Ensure POST goes to the correct endpoint and set the book_id on the form
            form.action = '/borrowings';
            const bookInput = form.querySelector('input[name="book_id"]') || form.querySelector('select[name="book_id"]');
            if (bookInput) {
                if (bookInput.tagName === 'SELECT') {
                    bookInput.value = String(bookId);
                    bookInput.dispatchEvent(new Event('change', { bubbles: true }));
                } else {
                    bookInput.value = String(bookId);
                }
            } else {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'book_id';
                hidden.value = String(bookId);
                form.appendChild(hidden);
            }

            modal.classList.remove('hidden');
            setTimeout(()=>{ form.querySelector('button, input, select, textarea')?.focus(); }, 10);
            modal._release = (function() {
                function handler(e){ if (e.key !== 'Tab') return; const focusable = Array.from(modal.querySelectorAll('a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled])')); if (!focusable.length) return; const first = focusable[0]; const last = focusable[focusable.length-1]; if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); } else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); } }
                document.addEventListener('keydown', handler);
                return () => document.removeEventListener('keydown', handler);
            })();
        }

        function closeBorrowModal() {
            document.getElementById('borrowModal').classList.add('hidden');
        }

        // Close modal when clicking backdrop
        document.getElementById('borrowModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBorrowModal();
            }
        });
    </script>

    @if($errors->any() && old('from') === 'modal')
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                document.getElementById('borrowModal') && document.getElementById('borrowModal').classList.remove('hidden');
            });
        </script>
    @endif

    <script src="{{ asset('js/member-pages.js') }}"></script>
@endsection
