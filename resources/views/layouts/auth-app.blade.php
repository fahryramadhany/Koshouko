<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Koshouko')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #E6CFA1 0%, #F3E9D2 100%);
            color: #3B2A1A;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #8B5A2B 0%, #B63A2B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-light {
            background: #FEF9F3;
            border: 1px solid rgba(139, 90, 43, 0.1);
            box-shadow: 0 4px 15px rgba(139, 90, 43, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #8B5A2B 0%, #B63A2B 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 90, 43, 0.3);
        }

        .input-light {
            background: #E6CFA1;
            border: 1px solid #CFC7B8;
            color: #3B2A1A;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .input-light:focus {
            outline: none;
            border-color: #8B5A2B;
            box-shadow: 0 0 0 3px rgba(139, 90, 43, 0.1);
        }

        .input-light::placeholder {
            color: #6B4F3F;
        }

        .nav-bar {
            background: #F0E4C8;
            border-bottom: 1px solid #D4C9B0;
            box-shadow: 0 1px 3px rgba(139, 90, 43, 0.08);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease;
        }

        .glow {
            box-shadow: 0 0 20px rgba(139, 90, 43, 0.2);
        }

        /* Card Classes with Explicit Colors */
        .stat-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .gradient-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .member-gradient-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .member-stat-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .borrowing-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .books-filter-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .book-item {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .book-detail-section {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .profile-info-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .profile-history-item {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .admin-stat-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .admin-table-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .admin-category-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .admin-borrowing-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .admin-report-section {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .admin-form-card {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        .welcome-section {
            background-color: #FEF9F3 !important;
            background-image: none !important;
        }

        /* Navbar & Sidebar */
        .navbar {
            background-color: #F0E4C8 !important;
            background-image: none !important;
        }

        .sidebar {
            background-color: #F0E4C8 !important;
            background-image: none !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
</head>
<body style="background: linear-gradient(135deg, #E6CFA1 0%, #F3E9D2 100%);" class="text-koshouko-text min-h-screen">
    <!-- Mobile Sidebar Toggle -->
    <div id="mobile-menu-backdrop" class="hidden fixed inset-0 bg-black/50 lg:hidden z-40" onclick="toggleMobileMenu()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed left-0 top-0 h-full w-64 shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50 overflow-y-auto border-r-2 border-koshouko-border" style="background: #F0E4C8;">
        <!-- Logo Section with Gradient -->
        <div class="p-6 border-b-2 border-koshouko-border" style="background: #F0E4C8;">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-koshouko-wood to-koshouko-red flex items-center justify-center text-white font-bold text-lg">
                    <img src="/logo_koshouko.jpeg" alt="Logo" class="w-10 h-10 rounded-full object-cover">
                </div>
                <div class="flex-1">
                    <h1 class="font-bold text-koshouko-text text-lg">Koshouko</h1>
                    <p class="text-xs text-koshouko-text-muted font-medium">Perpustakaan Digital</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="p-4 space-y-6">
            @auth
                @if(auth()->user()->isAdmin())
                    <!-- ADMIN Navigation -->
                    <div>
                        <div class="sidebar-section-title px-4 py-2 mb-3">Management</div>
                        <div class="space-y-2">
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📊</span>
                                <span class="font-medium">Dashboard</span>
                            </a>

                            <a href="{{ route('admin.books.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.books*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.books*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📖</span>
                                <span class="font-medium">Kelola Buku</span>
                            </a>

                            <a href="{{ route('admin.categories.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.categories*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">🏷️</span>
                                <span class="font-medium">Kategori</span>
                            </a>

                            <a href="{{ route('admin.users.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.users*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">👥</span>
                                <span class="font-medium">Kelola User</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <div class="sidebar-section-title px-4 py-2 mb-3">Operations</div>
                        <div class="space-y-2">
                            <a href="{{ route('admin.borrowings') }}" class="sidebar-nav-item {{ request()->routeIs('admin.borrowings*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.borrowings*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📋</span>
                                <span class="font-medium">Peminjaman</span>
                            </a>

                            <a href="{{ route('admin.announcements') }}" class="sidebar-nav-item {{ request()->routeIs('admin.announcements*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.announcements*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📢</span>
                                <span class="font-medium">Pengumuman</span>
                            </a>

                            <a href="{{ route('admin.reports') }}" class="sidebar-nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.reports*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📈</span>
                                <span class="font-medium">Laporan</span>
                            </a>
                        </div>
                    </div>
                @elseif(auth()->user()->isPustakawan())
                    <!-- PUSTAKAWAN (Librarian) Navigation -->
                    <div>
                        <div class="sidebar-section-title px-4 py-2 mb-3">Management</div>
                        <div class="space-y-2">
                            <a href="{{ route('librarian.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('librarian.dashboard') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('librarian.dashboard') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📊</span>
                                <span class="font-medium">Dashboard</span>
                            </a>

                            <a href="{{ route('librarian.books.index') }}" class="sidebar-nav-item {{ request()->routeIs('librarian.books*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('librarian.books*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📖</span>
                                <span class="font-medium">Kelola Buku</span>
                            </a>

                            <a href="{{ route('librarian.books.categories') }}" class="sidebar-nav-item {{ request()->routeIs('librarian.books.categories*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('librarian.books.categories*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">🏷️</span>
                                <span class="font-medium">Kategori</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <div class="sidebar-section-title px-4 py-2 mb-3">Operations</div>
                        <div class="space-y-2">
                            <a href="{{ route('librarian.borrowings') }}" class="sidebar-nav-item {{ request()->routeIs('librarian.borrowings*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('librarian.borrowings*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📋</span>
                                <span class="font-medium">Peminjaman</span>
                            </a>

                            <a href="{{ route('librarian.announcements') }}" class="sidebar-nav-item {{ request()->routeIs('librarian.announcements*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('librarian.announcements*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📢</span>
                                <span class="font-medium">Pengumuman</span>
                            </a>

                            <a href="{{ route('librarian.reports') }}" class="sidebar-nav-item {{ request()->routeIs('librarian.reports*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('librarian.reports*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📈</span>
                                <span class="font-medium">Laporan</span>
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Member Navigation -->
                    <div>
                        <div class="sidebar-section-title px-4 py-2 mb-3">Exploration</div>
                        <div class="space-y-2">
                            <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">🏠</span>
                                <span class="font-medium">Beranda</span>
                            </a>

                            <a href="{{ route('profile') }}" class="sidebar-nav-item {{ request()->routeIs('profile') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('profile') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">👤</span>
                                <span class="font-medium">Profil Saya</span>
                            </a>

                            <a href="{{ route('books.index') }}" class="sidebar-nav-item {{ request()->routeIs('books*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('books*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📚</span>
                                <span class="font-medium">Katalog Buku</span>
                            </a>

                            <a href="{{ route('borrowings.index') }}" class="sidebar-nav-item {{ request()->routeIs('borrowings*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('borrowings*') ? 'bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white shadow-lg' : 'text-koshouko-text hover:bg-koshouko-cream-light' }} transition">
                                <span class="text-xl mr-3">📋</span>
                                <span class="font-medium">Riwayat Peminjaman</span>
                            </a>
                        </div>
                    </div>
                @endif
            @endauth
        </nav>

        <!-- User Info (sticky bottom) -->
        @auth
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t-2 border-koshouko-border bg-gradient-to-t from-koshouko-cream to-koshouko-paper">
                <div class="bg-gradient-to-r from-koshouko-cream to-koshouko-cream-light rounded-lg p-3 mb-3 border border-koshouko-border">
                    <p class="font-semibold text-koshouko-text text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-koshouko-text-muted capitalize font-medium">{{ optional(auth()->user()->role)->name ?? 'Member' }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:shadow-lg transition text-sm font-medium">
                        🚪 Logout
                    </button>
                </form>
            </div>
        @endauth
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Top Navbar -->
        <nav class="navbar sticky top-0 z-30 border-b-2 border-koshouko-border" style="background: #F0E4C8; box-shadow: 0 1px 3px rgba(139, 90, 43, 0.08);">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleMobileMenu()" class="lg:hidden text-koshouko-text hover:text-koshouko-wood transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h2 class="text-xl font-bold text-koshouko-text">@yield('page-title', 'Dashboard')</h2>
                </div>
                
                @auth
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="relative text-koshouko-text-muted hover:text-koshouko-wood transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute -top-1 -right-1 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">2</span>
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-3 pl-4 border-l-2 border-koshouko-border">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-koshouko-text">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-koshouko-text-muted">{{ auth()->user()->member_id ?? 'Admin' }}</p>
                            </div>
                            <div class="w-10 h-10 bg-gradient-to-br from-koshouko-wood to-koshouko-red rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>

        <!-- Page Content -->
        <main class="px-3 sm:px-4 md:px-6 py-4 sm:py-5 md:py-6 min-h-[calc(100vh-120px)]">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-red-800 mb-2">Terjadi kesalahan!</h3>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 animate-pulse">
                    <p class="text-sm font-semibold text-green-800">✓ {{ session('success') }}</p>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <!-- Footer Content Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 mb-8">
                    <!-- Brand Section -->
                    <div class="sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <img src="/logo_koshouko.jpeg" alt="Koshouko" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-white">Koshouko</h3>
                                <p class="text-xs sm:text-sm text-white/80">Digital Library System</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/80 leading-relaxed">Platform Perpustakaan Digital Modern untuk Manajemen Koleksi Buku yang Efisien</p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="font-semibold text-sm sm:text-base mb-4 border-b border-white/30 pb-2 text-transparent bg-clip-text bg-gradient-to-r from-[#8B5A2B] to-[#B63A2B]">Navigasi</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('dashboard') }}" class="text-white/80 hover:text-[#8B5A2B] transition font-medium">Beranda</a></li>
                            <li><a href="{{ route('books.index') }}" class="text-white/80 hover:text-[#8B5A2B] transition font-medium">Katalog</a></li>
                            <li><a href="{{ route('borrowings.index') }}" class="text-white/80 hover:text-[#8B5A2B] transition font-medium">Peminjaman</a></li>
                            <li><a href="{{ route('profile') }}" class="text-white/80 hover:text-[#8B5A2B] transition font-medium">Profil</a></li>
                        </ul>
                    </div>

                    <!-- Resources -->
                    <div>
                        <h4 class="font-semibold text-sm sm:text-base mb-4 border-b border-white/30 pb-2 text-transparent bg-clip-text bg-gradient-to-r from-[#8B5A2B] to-[#B63A2B]">Resources</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="text-white/80 hover:text-[#8B5A2B] transition font-medium">Bantuan</a></li>
                            <li><a href="#" class="text-white/80 hover:text-[#8B5A2B] transition font-medium">FAQ</a></li>
                            <li><a href="#" class="text-white/80 hover:text-[#8B5A2B] transition font-medium">Kebijakan</a></li>
                            <li><a href="#" class="text-white/80 hover:text-[#8B5A2B] transition font-medium">Kontak</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h4 class="font-semibold text-sm sm:text-base mb-4 border-b border-white/30 pb-2 text-transparent bg-clip-text bg-gradient-to-r from-[#8B5A2B] to-[#B63A2B]">Hubungi Kami</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-start space-x-2">
                                <span class="text-lg">📧</span>
                                <span class="text-white/80">info@koshouko.com</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-lg">📱</span>
                                <span class="text-white/80">(+62) 812-3456-7890</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-lg">📍</span>
                                <span class="text-white/80">Jakarta, Indonesia</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-white/20 pt-6 sm:pt-8">
                    <!-- Footer Bottom -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-sm text-white/80">© 2025 Koshouko. All rights reserved.</p>
                        <div class="flex gap-4">
                            <a href="#" class="text-white/80 hover:text-[#8B5A2B] transition text-sm font-medium">Privacy Policy</a>
                            <span class="text-white/30">•</span>
                            <a href="#" class="text-white/80 hover:text-[#8B5A2B] transition text-sm font-medium">Terms of Service</a>
                            <span class="text-white/30">•</span>
                            <a href="#" class="text-white/80 hover:text-[#8B5A2B] transition text-sm font-medium">Cookie Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('js/layout.js') }}"></script>
    <script src="{{ asset('js/carousel.js') }}"></script>
    
    <!-- Inline fallback for mobile menu toggle -->
    <script>
        // Ensure toggleMobileMenu is globally available
        if (typeof toggleMobileMenu === 'undefined') {
            window.toggleMobileMenu = function() {
                const sidebar = document.getElementById('sidebar');
                const backdrop = document.getElementById('mobile-menu-backdrop');
                
                if (sidebar && backdrop) {
                    sidebar.classList.toggle('-translate-x-full');
                    backdrop.classList.toggle('hidden');
                    console.log('Toggle executed via inline script');
                }
            };
        }
        
        // Verify script loaded
        console.log('Layout script loaded, toggleMobileMenu:', typeof toggleMobileMenu);
    </script>
</body>
</html>
