@extends('layouts.auth-app')

@section('title', $book->title . ' - Perpustakaan Digital')
@section('page-title', '📖 ' . $book->title)

@section('content')
<link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">

<div class="max-w-7xl mx-auto">
    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">
        <!-- Left Sidebar - Book Cover & Actions -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-4">
                <!-- Book Cover -->
                <div class="rounded-2xl overflow-hidden shadow-lg bg-gradient-to-br from-koshouko-wood/10 to-koshouko-red/10 aspect-[3/4] flex items-center justify-center">
                    @if($book->cover_image && file_exists(public_path($book->cover_image)))
                        <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    @else
                        <p class="text-6xl opacity-30">📚</p>
                    @endif
                </div>

                <!-- Availability Badge -->
                @if($book->is_available)
                    <div class="bg-green-50 border-2 border-green-300 rounded-xl p-4 text-center">
                        <p class="text-green-700 font-bold">✓ Tersedia</p>
                        <p class="text-green-600 text-sm mt-1">{{ $book->available_copies }}/{{ $book->total_copies }}</p>
                    </div>
                @else
                    <div class="bg-red-50 border-2 border-red-300 rounded-xl p-4 text-center">
                        <p class="text-red-700 font-bold">✗ Tidak Tersedia</p>
                        <p class="text-red-600 text-sm mt-1">Semua sedang dipinjam</p>
                    </div>
                @endif

                <!-- Action Buttons -->
                @if($book->is_available)
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('books.borrow', $book) }}" class="w-full px-6 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition btn-koshouko text-center">
                            📤 Pinjam Buku
                        </a>

                        <a href="{{ route('books.borrow', $book) }}" class="w-full sm:w-44 px-4 py-3 border-2 border-koshouko-border text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-cream transition text-center">
                            📝 Isi Formulir
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        <button disabled class="w-full px-6 py-3 bg-gray-300 text-gray-600 rounded-lg font-semibold cursor-not-allowed">
                            Tidak Bisa Dipinjam
                        </button>
                        <a href="{{ route('books.borrow', $book) }}" class="w-full px-6 py-3 border-2 border-koshouko-border text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-cream transition text-center">
                            🔍 Lihat Formulir Peminjaman
                        </a>
                    </div>
                @endif

                <form action="{{ route('books.bookmark', $book) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 {{ $isBookmarked ? 'bg-koshouko-red text-white' : 'bg-koshouko-red/10 text-koshouko-red border-2 border-koshouko-red' }} rounded-lg font-semibold hover:shadow-lg transition">
                        {{ $isBookmarked ? '❌ Hapus Favorit' : '⭐ Tambah Favorit' }}
                    </button>
                </form>

                <!-- Book Stats -->
                <div class="space-y-3">
                    <div class="bg-koshouko-cream-light rounded-lg p-4">
                        <p class="text-koshouko-text-muted text-xs font-medium mb-1">Kategori</p>
                        <p class="text-koshouko-wood font-bold">{{ $book->category?->name ?? 'Uncategorized' }}</p>
                    </div>
                    <div class="bg-koshouko-cream-light rounded-lg p-4">
                        <p class="text-koshouko-text-muted text-xs font-medium mb-1">Penerbit</p>
                        <p class="text-koshouko-text font-semibold text-sm">{{ $book->publisher ?? '-' }}</p>
                    </div>
                    <div class="bg-koshouko-cream-light rounded-lg p-4">
                        <p class="text-koshouko-text-muted text-xs font-medium mb-1">Tahun Terbit</p>
                        <p class="text-koshouko-text font-semibold">{{ $book->publication_year ?? '-' }}</p>
                    </div>
                    <div class="bg-koshouko-cream-light rounded-lg p-4">
                        <p class="text-koshouko-text-muted text-xs font-medium mb-1">Halaman</p>
                        <p class="text-koshouko-text font-semibold">{{ $book->pages ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content - Book Details & Reviews -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Book Information Card -->
            <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-koshouko-wood/15 to-koshouko-red/15 border-b-2 border-koshouko-border">
                    <h1 class="text-3xl font-bold text-koshouko-wood">{{ $book->title }}</h1>
                    <p class="text-koshouko-text-muted mt-2">oleh <span class="font-semibold text-koshouko-text">{{ $book->author }}</span></p>
                </div>

                <div class="p-8 space-y-6">
                    <!-- Rating Summary -->
                    <div class="border-b border-koshouko-border pb-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <!-- Rating Stats -->
                            <div>
                                <div class="flex items-baseline gap-3 mb-2">
                                    <span class="text-5xl font-bold text-koshouko-wood">{{ $averageRating }}</span>
                                    <div>
                                        <div class="text-xl">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $averageRating)
                                                    <span class="text-yellow-400">⭐</span>
                                                @elseif($i - 0.5 <= $averageRating)
                                                    <span class="text-yellow-300">⭐</span>
                                                @else
                                                    <span class="text-gray-300">⭐</span>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="text-xs text-koshouko-text-muted mt-1">{{ $totalReviews }} ulasan</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rating Distribution Bar -->
                            <div class="w-full md:w-72 space-y-2">
                                @for($rating = 5; $rating >= 1; $rating--)
                                    @php
                                        $count = $ratingDistribution[$rating] ?? 0;
                                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-koshouko-text w-12">{{ $rating }}⭐</span>
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-yellow-400 h-full transition-all" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-xs text-koshouko-text-muted w-8 text-right">{{ $count }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Book Details Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-koshouko-text-muted text-xs font-medium mb-1">ISBN</p>
                            <p class="text-koshouko-text font-mono text-sm">{{ $book->isbn ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-xs font-medium mb-1">Bahasa</p>
                            <p class="text-koshouko-text font-semibold">{{ $book->language ?? 'Indonesia' }}</p>
                        </div>
                        <div>
                            <p class="text-koshouko-text-muted text-xs font-medium mb-1">Lokasi</p>
                            <p class="text-koshouko-text font-semibold">{{ $book->location ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h4 class="font-bold text-koshouko-wood text-lg mb-3">📖 Deskripsi</h4>
                        <p class="text-koshouko-text leading-relaxed text-justify">{{ $book->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    <!-- Borrowing History -->
                    @if($userBorrowings->count() > 0)
                        <div class="border-t border-koshouko-border pt-6">
                            <h4 class="font-bold text-koshouko-wood text-lg mb-3">📚 Riwayat Peminjaman Anda</h4>
                            <div class="space-y-2">
                                @foreach($userBorrowings->take(3) as $borrowing)
                                    <div class="flex justify-between items-center p-3 bg-koshouko-cream-light rounded-lg">
                                        <div>
                                            <p class="text-sm font-semibold text-koshouko-text">
                                                {{ $borrowing->borrowed_at->format('d M Y') }}
                                            </p>
                                            <p class="text-xs text-koshouko-text-muted">
                                                Dikembalikan: {{ $borrowing->returned_at ? $borrowing->returned_at->format('d M Y') : 'Belum dikembalikan' }}
                                            </p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($borrowing->status === 'returned')
                                                bg-blue-100 text-blue-700
                                            @elseif($borrowing->status === 'overdue')
                                                bg-red-100 text-red-700
                                            @else
                                                bg-green-100 text-green-700
                                            @endif
                                        ">
                                            {{ ucfirst($borrowing->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-koshouko-red/15 to-koshouko-pink/15 border-b-2 border-koshouko-border">
                    <h3 class="text-2xl font-bold section-title">💬 Ulasan & Rating</h3>
                    <p class="text-koshouko-text-muted text-sm mt-2">{{ $totalReviews }} ulasan dari pembaca</p>
                </div>

                <div class="p-8">
                    <!-- Write/Edit Review Section -->
                    @if($userReview)
                        <div class="mb-8 p-6 bg-blue-50 border-2 border-blue-200 rounded-xl">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-blue-900">✏️ Ulasan Anda</h4>
                                <button type="button" onclick="document.getElementById('edit-review-form').classList.toggle('hidden')" class="text-sm font-semibold text-blue-700 hover:text-blue-900">
                                    Edit
                                </button>
                            </div>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="text-lg">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $userReview->rating)
                                            <span class="text-yellow-400">⭐</span>
                                        @else
                                            <span class="text-gray-300">⭐</span>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm font-semibold text-blue-900">{{ $userReview->formatted_rating }}</span>
                            </div>
                            @if($userReview->title)
                                <p class="font-semibold text-blue-900 mb-2">{{ $userReview->title }}</p>
                            @endif
                            <p class="text-blue-900 text-sm leading-relaxed mb-3">{{ $userReview->content }}</p>
                            <p class="text-xs text-blue-600">Diposkan: {{ $userReview->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <!-- Edit Review Form -->
                        <form action="{{ route('reviews.update', $userReview) }}" method="POST" class="mb-8 p-6 bg-yellow-50 border-2 border-yellow-200 rounded-xl hidden" id="edit-review-form">
                            @csrf
                            @method('PUT')
                            
                            <h4 class="font-bold text-yellow-900 mb-4">Edit Ulasan</h4>

                            <!-- Rating -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-yellow-900 mb-3">Rating</label>
                                <div class="flex gap-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating" id="edit-rating-{{ $i }}" value="{{ $i }}" @checked($userReview->rating == $i) required style="display: none;">
                                        <label for="edit-rating-{{ $i }}" class="text-4xl cursor-pointer transition hover:scale-110">
                                            <span class="@if($i <= $userReview->rating) text-yellow-400 @else text-gray-300 @endif">⭐</span>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Title -->
                            <div class="mb-4">
                                <label for="edit-title" class="block text-sm font-semibold text-yellow-900 mb-2">Judul Ulasan</label>
                                <input type="text" name="title" id="edit-title" value="{{ $userReview->title }}" class="w-full px-4 py-2 border-2 border-yellow-300 rounded-lg focus:outline-none focus:border-yellow-500">
                            </div>

                            <!-- Content -->
                            <div class="mb-4">
                                <label for="edit-content" class="block text-sm font-semibold text-yellow-900 mb-2">Ulasan</label>
                                <textarea name="content" id="edit-content" rows="4" required class="w-full px-4 py-2 border-2 border-yellow-300 rounded-lg focus:outline-none focus:border-yellow-500">{{ $userReview->content }}</textarea>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg font-semibold hover:bg-yellow-700">
                                    Simpan
                                </button>
                                <button type="button" onclick="document.getElementById('edit-review-form').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400">
                                    Batal
                                </button>
                                <form action="{{ route('reviews.destroy', $userReview) }}" method="POST" class="inline ml-auto" onsubmit="return confirm('Hapus ulasan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </form>
                    @else
                        <!-- Create Review Form -->
                        <form action="{{ route('reviews.store', $book) }}" method="POST" class="mb-8 p-6 bg-koshouko-cream-light rounded-xl border-2 border-koshouko-border">
                            @csrf
                            
                            <h4 class="font-bold text-koshouko-wood mb-4">Beri Rating & Ulasan</h4>

                            <!-- Rating Stars -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-koshouko-text mb-3">Berapa rating Anda?</label>
                                <div class="flex gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating" id="rating-{{ $i }}" value="{{ $i }}" required style="display: none;">
                                        <label for="rating-{{ $i }}" class="text-5xl cursor-pointer transition hover:scale-110" onclick="updateStars({{ $i }})">
                                            <span id="star-{{ $i }}" class="text-gray-300">⭐</span>
                                        </label>
                                    @endfor
                                </div>
                                <p id="rating-text" class="text-sm text-koshouko-text-muted mt-2">Pilih rating</p>
                            </div>

                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-semibold text-koshouko-text mb-2">Judul Ulasan (Opsional)</label>
                                <input type="text" name="title" id="title" placeholder="Contoh: Buku yang Sangat Bagus" class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg focus:outline-none focus:border-koshouko-wood @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Content -->
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-semibold text-koshouko-text mb-2">Ulasan Anda</label>
                                <textarea name="content" id="content" rows="5" required placeholder="Bagikan pengalaman Anda membaca buku ini..." class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg focus:outline-none focus:border-koshouko-wood resize-none @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                                <p class="text-xs text-koshouko-text-muted mt-1">Maksimal 1000 karakter</p>
                                @error('content')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition btn-koshouko">
                                Kirim Ulasan
                            </button>
                        </form>
                    @endif

                    <!-- Reviews List -->
                    <div class="border-t border-koshouko-border pt-6">
                        <h4 class="font-bold text-koshouko-wood text-lg mb-4">Ulasan dari Pembaca Lain</h4>
                        
                        @forelse($reviews as $review)
                            <div class="pb-6 mb-6 border-b border-koshouko-border last:border-b-0">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="font-semibold text-koshouko-text">{{ $review->user->name }}</p>
                                        <p class="text-xs text-koshouko-text-muted">{{ $review->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <div class="text-lg">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <span class="text-yellow-400">⭐</span>
                                            @else
                                                <span class="text-gray-300">⭐</span>
                                            @endif
                                        @endfor
                                    </div>
                                </div>

                                @if($review->title)
                                    <p class="font-semibold text-koshouko-wood mb-2">{{ $review->title }}</p>
                                @endif

                                <p class="text-koshouko-text text-sm mb-3 leading-relaxed">{{ $review->content }}</p>

                                <!-- Helpful Button -->
                                <form action="{{ route('reviews.helpful', $review) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold text-koshouko-wood hover:text-koshouko-red transition">
                                        👍 Membantu ({{ $review->helpful_count }})
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-koshouko-text-muted text-center py-8">Belum ada ulasan. Jadilah yang pertama memberikan ulasan!</p>
                        @endforelse

                        <!-- Pagination -->
                        @if($reviews->hasPages())
                            <div class="mt-6">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStars(rating) {
    document.getElementById('rating-' + rating).checked = true;

    for (let i = 1; i <= 5; i++) {
        const star = document.getElementById('star-' + i);
        if (i <= rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    }

    const ratingTexts = {
        1: '⭐ Tidak Memuaskan',
        2: '⭐⭐ Kurang Baik',
        3: '⭐⭐⭐ Cukup Baik',
        4: '⭐⭐⭐⭐ Memuaskan',
        5: '⭐⭐⭐⭐⭐ Sangat Memuaskan'
    };
    document.getElementById('rating-text').textContent = ratingTexts[rating];
}
</script>

@endsection
