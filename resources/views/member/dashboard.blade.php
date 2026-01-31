@extends('layouts.auth-app')

@section('title', 'Dashboard - Perpustakaan Digital')
@section('page-title', '🏠 Dashboard Saya')

@section('content')
<link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">
<link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    .swiper-slide {
        height: 450px;
        border-radius: 1.5rem;
        overflow: hidden;
        position: relative;
    }
    
    .swiper-pagination-bullet-active {
        background-color: #fff !important;
    }
    
    .swiper-button-prev, .swiper-button-next {
        color: #fff;
        background: rgba(0,0,0,0.5);
        padding: 10px;
        border-radius: 50%;
        width: 45px;
        height: 45px;
    }
    
    .swiper-button-prev::after, .swiper-button-next::after {
        font-size: 18px;
    }

    /* Hero Creative Carousel Animations */
    @keyframes slideInRight {
        from {
            transform: translateX(100%) scaleX(0.9);
            opacity: 0;
        }
        to {
            transform: translateX(0) scaleX(1);
            opacity: 1;
        }
    }

    @keyframes slideOutLeft {
        from {
            transform: translateX(0) scaleX(1);
            opacity: 1;
        }
        to {
            transform: translateX(-100%) scaleX(0.85);
            opacity: 0;
        }
    }

    @keyframes stackLayer {
        0% { transform: translateY(0) scaleY(0.95); opacity: 0.6; }
        100% { transform: translateY(-8px) scaleY(1); opacity: 0.8; }
    }

    #hero-bg {
        transition: background-image 0.8s ease-in-out;
    }

    .hero-container {
        position: relative;
    }

    .hero-content {
        animation: slideInRight 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .hero-content.slide-out {
        animation: slideOutLeft 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .stack-layer {
        position: absolute;
        inset: 0;
        border-radius: 1rem;
        background: rgba(59, 42, 26, 0.3);
        pointer-events: none;
        opacity: 0;
        z-index: -1;
    }

    .stack-layer.active {
        animation: stackLayer 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .stack-layer:nth-child(1) { animation-delay: 0.05s; }
    .stack-layer:nth-child(2) { animation-delay: 0.1s; }
    .stack-layer:nth-child(3) { animation-delay: 0.15s; }
</style>

    <!-- Welcome Section -->
    <div class="welcome-section rounded-2xl p-4 sm:p-6 lg:p-8 mb-8 border-l-4 border-koshouko-wood">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-koshouko-text mb-2">Selamat Datang, {{ auth()->user()->name ?? 'Pengguna' }}! 👋</h2>
                <p class="text-koshouko-text-muted text-sm sm:text-base lg:text-lg">Kelola peminjaman buku dan jelajahi koleksi perpustakaan kami</p>
            </div>
            <div class="text-4xl sm:text-5xl lg:text-6xl opacity-40 flex-shrink-0">📚</div>
        </div>
    </div>

    <!-- Featured Hero (replaces Swiper carousel) -->
    @if(!empty($recommendedBooks) && $recommendedBooks->count() > 0)
        @php $hero = $recommendedBooks->first(); @endphp
        <div class="mb-8">
            <div class="hero-container relative rounded-2xl overflow-hidden shadow-2xl bg-white">
                @if($hero->cover_image && file_exists(public_path($hero->cover_image)))
                    <div id="hero-bg" class="absolute inset-0 bg-center bg-cover"
                        style="background-image: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)), url('{{ asset($hero->cover_image) }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    </div>
                @else
                    <div id="hero-bg" class="absolute inset-0 bg-center bg-cover" style="background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                @endif

                <!-- Stacking effect layers -->
                <div class="stack-layer"></div>
                <div class="stack-layer"></div>
                <div class="stack-layer"></div>

                <div class="relative p-6 lg:p-10">
                    <h2 class="section-title">Popular New Titles</h2>

                    <div class="hero-content content-wrapper mt-6 flex flex-col lg:flex-row items-start gap-6">
                        <div class="flex-shrink-0">
                            @if($hero->cover_image && file_exists(public_path($hero->cover_image)))
                                <img src="{{ asset($hero->cover_image) }}" alt="{{ $hero->title }}" class="cover-image w-48 lg:w-56 rounded-lg shadow-lg object-cover">
                            @else
                                <div class="cover-image w-48 lg:w-56 h-72 rounded-lg shadow-lg bg-gradient-to-br from-koshouko-wood to-koshouko-red flex items-center justify-center text-6xl">
                                    📖
                                </div>
                            @endif
                        </div>

                        <div class="info-section flex-1">
                            <div>
                                <h1 class="manga-title text-2xl lg:text-4xl font-extrabold text-white">{{ $hero->title }}</h1>

                                <div class="tags mt-3 flex items-center gap-3">
                                    <span class="tag bg-koshouko-red text-white text-xs font-bold px-3 py-1 rounded-full uppercase">{{ $hero->category?->name ?? 'Koleksi' }}</span>
                                </div>

                                <p class="description mt-4 text-base text-gray-100 max-w-3xl">{{ Str::limit($hero->description ?? 'Koleksi terbaru dari perpustakaan kami', 260) }}</p>
                            </div>

                            <div class="card-footer mt-6 flex items-center justify-between border-t border-white/20 pt-4">
                                <span class="author font-semibold italic text-white">{{ $hero->author }}</span>

                                <div class="navigation flex items-center gap-3">
                                    <span class="rank text-sm font-bold text-white">NO. {{ $recommendedBooks->search(fn($b) => $b->id === $hero->id) + 1 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2 hero-dots"></div>

                    <!-- Navigation buttons at bottom-right -->
                    <div class="absolute bottom-6 right-6 flex items-center gap-3 z-10">
                        <button type="button" aria-label="Previous" class="nav-btn hero-prev w-10 h-10 rounded-full bg-white text-koshouko-wood shadow-lg hover:shadow-xl flex items-center justify-center transition">&lt;</button>
                        <button type="button" aria-label="Next" class="nav-btn hero-next w-10 h-10 rounded-full bg-white text-koshouko-wood shadow-lg hover:shadow-xl flex items-center justify-center transition">&gt;</button>
                    </div>
                </div>
            </div>
        </div>
    @endif



    <!-- Additional Content Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tata Cara Meminjam Buku -->
            <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 bg-gradient-to-r from-koshouko-wood/15 to-koshouko-red/15 border-b-2 border-koshouko-border">
                    <h3 class="text-lg sm:text-xl font-bold section-title">📖 Tata Cara Meminjam Buku</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="space-y-3">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-koshouko-red text-white font-bold text-sm">1</div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-koshouko-text">Cari Buku yang Diinginkan</h4>
                                <p class="text-sm text-koshouko-text-muted">Gunakan fitur pencarian atau jelajahi katalog buku yang tersedia di perpustakaan digital kami</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-koshouko-red text-white font-bold text-sm">2</div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-koshouko-text">Ajukan Permintaan Peminjaman</h4>
                                <p class="text-sm text-koshouko-text-muted">Klik tombol "Pinjam" dan tunggu persetujuan dari pustakawan</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-koshouko-red text-white font-bold text-sm">3</div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-koshouko-text">Tunggu Persetujuan</h4>
                                <p class="text-sm text-koshouko-text-muted">Cek status peminjaman Anda di menu "Riwayat Peminjaman"</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-koshouko-red text-white font-bold text-sm">4</div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-koshouko-text">Baca dan Kembalikan</h4>
                                <p class="text-sm text-koshouko-text-muted">Baca buku dalam durasi 14 hari, kemudian kembalikan sesuai tanggal jatuh tempo</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-koshouko-border">
                        <h4 class="font-semibold text-koshouko-text mb-3">⚠️ Peraturan Penting:</h4>
                        <ul class="space-y-2 text-sm">
                             <li class="flex items-start gap-2">
                                <span class="text-koshouko-red font-bold">•</span>
                                <span class="text-koshouko-text">Sebagai Jaminan <strong>KTP/Kartu Identitas</strong> (online)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-koshouko-red font-bold">•</span>
                                <span class="text-koshouko-text">Maksimal peminjaman <strong>5 buku</strong> sekaligus</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-koshouko-red font-bold">•</span>
                                <span class="text-koshouko-text">Durasi peminjaman <strong>14 hari</strong> per buku</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-koshouko-red font-bold">•</span>
                                <span class="text-koshouko-text">Perpanjangan peminjaman maksimal <strong>2 kali</strong></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-koshouko-red font-bold">•</span>
                                <span class="text-koshouko-text">Denda keterlambatan <strong>Rp 5.000/hari</strong></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-koshouko-red font-bold">•</span>
                                <span class="text-koshouko-text">Jaga kondisi buku agar tetap baik dan tidak rusak</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi Buku -->
            <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 bg-gradient-to-r from-koshouko-wood/15 to-koshouko-red/15 border-b-2 border-koshouko-border">
                    <h3 class="text-lg sm:text-xl font-bold section-title">💡 Rekomendasi Untuk Anda</h3>
                </div>
                
                <div class="p-6">
                    @if($recommendedBooks->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
                        @foreach($recommendedBooks->take(8) as $book)
                        <div class="bg-gradient-to-br from-koshouko-cream-light to-white rounded-lg p-4 hover:shadow-md transition cursor-pointer border border-koshouko-border">
                            <div class="w-full h-48 bg-gradient-to-br from-koshouko-wood to-koshouko-red rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                            @if($book->cover_image && file_exists(public_path($book->cover_image)))
                                <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl opacity-50">📖</span>
                            @endif
                            </div>
                            <p class="font-semibold text-koshouko-text text-sm truncate">{{ $book->title }}</p>
                            <p class="text-xs text-koshouko-text-muted">{{ $book->category?->name ?? 'Koleksi' }}</p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-koshouko-text-muted text-center py-8">Belum ada rekomendasi buku untuk Anda</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Profile Card -->
            <div class="gradient-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 bg-gradient-to-r from-koshouko-wood/15 to-koshouko-red/15 border-b-2 border-koshouko-border">
                    <h3 class="text-lg sm:text-xl font-bold section-title">👤 Profil Saya</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-koshouko-wood to-koshouko-red rounded-full text-white">
                            <span class="text-2xl font-bold">{{ \Illuminate\Support\Str::substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <p class="font-semibold text-koshouko-text mt-3">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-koshouko-text-muted">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profile') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-md transition text-sm text-center">
                        Lihat Profil Lengkap →
                    </a>
                </div>
            </div>

            <!-- Info Penting -->
            <div class="gradient-card rounded-2xl border-l-4 border-koshouko-wood p-6 shadow-lg">
                <h4 class="font-bold section-title text-lg mb-4">ℹ️ Info Perpustakaan</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-koshouko-wood"></span>
                        <span class="text-koshouko-text">Maks <strong>5 buku</strong></span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-koshouko-wood"></span>
                        <span class="text-koshouko-text">Durasi <strong>14 hari</strong></span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-koshouko-wood"></span>
                        <span class="text-koshouko-text">Perpanjang <strong>2x</strong></span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-koshouko-red"></span>
                        <span class="text-koshouko-text">Denda <strong>Rp 5k/hari</strong></span>
                    </li>
                </ul>
            </div>

            <!-- Quick Actions -->
            <div class="gradient-card rounded-2xl shadow-lg p-6">
                <h4 class="font-bold section-title text-lg mb-4">⚡ Akses Cepat</h4>
                <div class="space-y-3">
                    <button id="openBorrowModalBtn" type="button" class="block w-full text-left px-4 py-3 bg-gradient-to-r from-koshouko-red to-koshouko-orange text-white rounded-lg font-semibold hover:shadow-lg transition text-sm">
                        🎯 Ajukan Peminjaman
                    </button>
                    <a href="{{ route('books.index') }}" class="block px-4 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition text-sm text-center">
                        🔍 Cari Buku
                    </a>
                    <a href="{{ route('borrowings.index') }}" class="block px-4 py-3 bg-white text-koshouko-wood rounded-lg font-semibold hover:shadow-lg transition text-sm text-center border-2 border-koshouko-wood">
                        📋 Riwayat
                    </a>
                </div>
            </div>

            <!-- Borrowing Modal (server-rendered partial) -->
            <div id="borrowModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4" role="dialog" aria-modal="true" aria-labelledby="borrowModalTitle" aria-describedby="borrowingFormErrors">
                <div id="borrowModalBackdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true"></div>
                <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-auto max-h-[90vh] z-10" tabindex="-1">
                    <div class="flex items-center justify-between p-5 border-b">
                        <h3 id="borrowModalTitle" class="font-bold">Ajukan Peminjaman</h3>
                        <button id="closeBorrowModalBtn" class="text-koshouko-text-muted hover:text-koshouko-wood" aria-label="Tutup formulir pinjaman">✕</button>
                    </div>
                    <div class="p-6">
                        @include('member.borrowings._form', ['renderedIn' => 'modal'])
                    </div>
                </div>
            </div>

            <script>
                (function(){
                    const openBtn = document.getElementById('openBorrowModalBtn');
                    const modal = document.getElementById('borrowModal');
                    const closeBtn = document.getElementById('closeBorrowModalBtn');
                    const backdrop = document.getElementById('borrowModalBackdrop');
                    let _trapHandler = null;

                    function firstFocusable(form) {
                        return form.querySelector('button:not([disabled]), [href], input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled])');
                    }

                    function trapFocus(container) {
                        const focusable = Array.from(container.querySelectorAll('a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled])'));
                        if (!focusable.length) return () => {};
                        const first = focusable[0];
                        const last = focusable[focusable.length - 1];
                        _trapHandler = function(e) {
                            if (e.key !== 'Tab') return;
                            if (e.shiftKey && document.activeElement === first) {
                                e.preventDefault(); last.focus();
                            } else if (!e.shiftKey && document.activeElement === last) {
                                e.preventDefault(); first.focus();
                            }
                        };
                        document.addEventListener('keydown', _trapHandler);
                        return () => document.removeEventListener('keydown', _trapHandler);
                    }

                    function openModal() {
                        modal.classList.remove('hidden');
                        const form = modal.querySelector('#borrowingForm');

                        const errorsContainer = modal.querySelector('#borrowingFormErrors');
                        if (errorsContainer && errorsContainer.textContent.trim().length) {
                            errorsContainer.setAttribute('tabindex', '-1');
                            errorsContainer.focus();
                        } else {
                            const first = firstFocusable(form) || form.querySelector('input[name="book_id"]');
                            first && first.focus();
                        }

                        if (openBtn && !document.body.classList.contains('preserve-borrowing-old')) {
                            form.reset();
                            const borrowedAt = form.querySelector('#borrowed_at');
                            if (borrowedAt) borrowedAt.value = new Date().toISOString().split('T')[0];
                            const borrowDateDisplay = form.querySelector('#borrowDateDisplay');
                            if (borrowDateDisplay) borrowDateDisplay.textContent = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                            if (typeof updateDueDate === 'function') updateDueDate();
                            if (typeof updateBookInfo === 'function') updateBookInfo();
                        } else {
                            document.body.classList.remove('preserve-borrowing-old');
                        }

                        modal._release = trapFocus(modal);
                    }

                    function closeModal() {
                        modal.classList.add('hidden');
                        if (typeof modal._release === 'function') modal._release();
                    }

                    openBtn && openBtn.addEventListener('click', function(){ document.body.classList.remove('preserve-borrowing-old'); openModal(); });
                    closeBtn && closeBtn.addEventListener('click', closeModal);
                    backdrop && backdrop.addEventListener('click', closeModal);
                    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeModal(); });
                })();
            </script>

            @if($errors->any() && old('from') === 'modal')
                <script>
                    document.addEventListener('DOMContentLoaded', function(){
                        document.getElementById('openBorrowModalBtn')?.click();
                    });
                </script>
            @endif
        </div>
    </div>

<script>
    // Interactive hero: expose recommended books and implement next/prev/autoplay/dots
    window.recommendedBooks = @json(($recommendedBooks ?? collect())->take(8));

    (function () {
        const books = window.recommendedBooks || [];
        if (!books.length) return;

        let index = 0;
        const intervalMs = 3500;
        let timer = null;

        const titleEl = document.querySelector('.manga-title');
        const tagEl = document.querySelector('.tags .tag');
        const descEl = document.querySelector('.description');
        const authorEl = document.querySelector('.author');
        const rankEl = document.querySelector('.rank');
        const coverEl = document.querySelector('.cover-image');
        const heroBg = document.getElementById('hero-bg');
        const prevBtn = document.querySelector('.hero-prev');
        const nextBtn = document.querySelector('.hero-next');
        const dotsWrap = document.querySelector('.hero-dots');
        const heroContainer = document.querySelector('.hero-container');

        function renderDots() {
            dotsWrap.innerHTML = '';
            books.forEach((b, i) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'w-3 h-3 rounded-full bg-koshouko-border';
                dot.style.opacity = i === index ? '1' : '0.4';
                dot.addEventListener('click', () => { goTo(i); });
                dotsWrap.appendChild(dot);
            });
        }

        function updateHero() {
            const book = books[index];
            if (!book) return;

            // Trigger slide-out animation
            const heroContent = document.querySelector('.hero-content');
            if (heroContent) {
                heroContent.classList.add('slide-out');
                setTimeout(() => {
                    heroContent.classList.remove('slide-out');
                }, 600);
            }

            // Update content after animation starts
            if (titleEl) titleEl.textContent = book.title || '';
            if (tagEl) tagEl.textContent = (book.category ? book.category.name : 'Koleksi') || 'Koleksi';
            if (descEl) descEl.textContent = (book.description || '').substring(0, 260);
            if (authorEl) authorEl.textContent = book.author || '';
            if (rankEl) rankEl.textContent = 'NO. ' + (index + 1);

            if (coverEl) {
                if (coverEl.tagName === 'IMG') {
                    coverEl.src = book.cover_image ? ('/' + book.cover_image).replace(/\\/g,'/') : '';
                    coverEl.alt = book.title || 'Cover';
                } else {
                    coverEl.style.backgroundImage = book.cover_image ? `url('${('/' + book.cover_image).replace(/\\/g,'/')}')` : '';
                }
            }

            if (heroBg) {
                const imageUrl = book.cover_image ? `url('${('/' + book.cover_image).replace(/\\/g,'/')}')` : '';
                const overlay = 'linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65))';
                heroBg.style.backgroundImage = imageUrl ? `${overlay}, ${imageUrl}` : overlay;
                heroBg.style.backgroundSize = 'cover';
                heroBg.style.backgroundPosition = 'center';
            }

            // Trigger stacking animation
            const stackLayers = document.querySelectorAll('.stack-layer');
            stackLayers.forEach(layer => {
                layer.classList.remove('active');
                setTimeout(() => layer.classList.add('active'), 50);
            });

            // update dots opacity
            Array.from(dotsWrap.children).forEach((d, i) => d.style.opacity = i === index ? '1' : '0.4');
        }

        function next() { index = (index + 1) % books.length; updateHero(); }
        function prev() { index = (index - 1 + books.length) % books.length; updateHero(); }
        function goTo(i) { index = (i + books.length) % books.length; updateHero(); resetTimer(); }

        function startTimer() { timer = setInterval(next, intervalMs); }
        function resetTimer() { clearInterval(timer); startTimer(); }

        // pause on hover
        if (heroContainer) {
            heroContainer.addEventListener('mouseenter', () => { clearInterval(timer); });
            heroContainer.addEventListener('mouseleave', () => { resetTimer(); });
        }

        // keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') { prev(); resetTimer(); }
            if (e.key === 'ArrowRight') { next(); resetTimer(); }
        });

        if (prevBtn) prevBtn.addEventListener('click', () => { prev(); resetTimer(); });
        if (nextBtn) nextBtn.addEventListener('click', () => { next(); resetTimer(); });

        renderDots();
        updateHero();
        startTimer();
    })();
</script>
@endsection
