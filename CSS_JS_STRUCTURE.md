<!-- STRUKTUR CSS DAN JS YANG SUDAH DIPISAHKAN -->

📁 resources/
├── 📁 css/
│   ├── 📄 layout.css          - CSS untuk layout global (sidebar, navbar, buttons)
│   ├── 📄 member-pages.css    - CSS untuk halaman member (dashboard, profile, books, borrowings)
│   └── 📄 admin-pages.css     - CSS untuk halaman admin (dashboard, management, forms, tables)
│
├── 📁 js/
│   ├── 📄 layout.js           - JS untuk layout global (toggleMobileMenu, closeModals)
│   └── 📄 member-pages.js     - JS untuk halaman member (switchTab, borrowBook modal)
│
└── 📁 views/
    ├── 📁 layouts/
    │   └── auth-app.blade.php - Main layout (linked to layout.css, layout.js)
    │
    ├── 📁 member/
    │   ├── dashboard.blade.php - Linked: member-pages.css
    │   ├── profile.blade.php - Linked: member-pages.css
    │   ├── 📁 books/
    │   │   ├── index.blade.php - Linked: member-pages.css, member-pages.js
    │   │   └── show.blade.php - Linked: member-pages.css
    │   └── 📁 borrowings/
    │       └── index.blade.php - Linked: member-pages.css, member-pages.js
    │
    └── 📁 admin/
        ├── dashboard.blade.php - Linked: admin-pages.css
        ├── 📁 books/
        │   ├── index.blade.php - Linked: admin-pages.css
        │   └── create.blade.php - Linked: admin-pages.css
        ├── 📁 users/
        │   ├── index.blade.php - Linked: admin-pages.css
        │   └── create.blade.php - Linked: admin-pages.css
        ├── 📁 categories/
        │   ├── index.blade.php - Linked: admin-pages.css
        │   └── create.blade.php - Linked: admin-pages.css
        ├── 📁 borrowings/
        │   └── index.blade.php - Linked: admin-pages.css
        └── 📁 reports/
            └── index.blade.php - Linked: admin-pages.css

========================================

FILE YANG SUDAH DIPISAHKAN:

✅ resources/css/layout.css
   - Sidebar styling (.sidebar-nav-item, .sidebar-section-title)
   - Global components (.gradient-card, .stat-card, .btn-koshouko, .welcome-section)
   - Tab styling (.tab-btn)

✅ resources/css/member-pages.css
   - Member page cards (.member-gradient-card, .member-stat-card)
   - Member buttons (.member-btn-koshouko)
   - Borrowing page styles (.borrowing-tab-btn, .borrowing-card)
   - Books page styles (.books-filter-card, .book-item, .book-detail-section)
   - Profile page styles (.profile-info-card, .profile-header, .profile-history-item)

✅ resources/css/admin-pages.css
   - Admin stat cards (.admin-stat-card)
   - Section headers (.section-header)
   - Admin buttons (.admin-btn-koshouko)
   - Table styling (.admin-table-card)
   - Action buttons (.admin-action-btn, .admin-edit-btn, .admin-delete-btn)
   - Category cards (.admin-category-card)
   - Borrowing cards (.admin-borrowing-card)
   - Report sections (.admin-report-section, .admin-chart-container)
   - Form styling (.admin-form-card, .admin-form-group, .admin-form-label, .admin-form-input, etc)

✅ resources/js/layout.js
   - toggleMobileMenu() - Toggle sidebar on mobile
   - Sidebar link click handlers
   - switchTab() - Tab switching functionality (for borrowings)

✅ resources/js/member-pages.js
   - switchTab() - Advanced tab filtering for borrowings page
   - borrowBook() - Modal dialog untuk peminjaman buku
   - closeBorrowModal() - Close modal functionality

========================================

HALAMAN YANG SUDAH DIUPDATE:

Member Pages:
✅ resources/views/member/dashboard.blade.php - Linked member-pages.css
✅ resources/views/member/profile.blade.php - Linked member-pages.css
✅ resources/views/member/books/index.blade.php - Linked member-pages.css, member-pages.js
✅ resources/views/member/books/show.blade.php - Linked member-pages.css
✅ resources/views/member/borrowings/index.blade.php - Linked member-pages.css, member-pages.js

Admin Pages:
✅ resources/views/admin/dashboard.blade.php - Linked admin-pages.css
✅ resources/views/admin/books/index.blade.php - Linked admin-pages.css
✅ resources/views/admin/books/create.blade.php - Linked admin-pages.css
✅ resources/views/admin/users/index.blade.php - Linked admin-pages.css
✅ resources/views/admin/users/create.blade.php - Linked admin-pages.css
✅ resources/views/admin/categories/index.blade.php - Linked admin-pages.css
✅ resources/views/admin/categories/create.blade.php - Linked admin-pages.css
✅ resources/views/admin/borrowings/index.blade.php - Linked admin-pages.css
✅ resources/views/admin/reports/index.blade.php - Linked admin-pages.css

Layout:
✅ resources/views/layouts/auth-app.blade.php - Linked layout.css, layout.js

========================================

CARA MENGGUNAKAN CSS DAN JS:

1. Di blade file, tambahkan link di bagian awal @section('content'):
   <link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">

2. Untuk JavaScript, tambahkan di akhir blade file sebelum @endsection:
   <script src="{{ asset('js/member-pages.js') }}"></script>

3. Semua asset sudah ter-link melalui public/ folder

========================================

BENEFITS:
✓ CSS dan JS terpisah dari HTML - lebih clean dan maintainable
✓ CSS dan JS bisa di-reuse di multiple pages
✓ Lebih mudah untuk maintenance dan update styles
✓ Performa lebih baik (caching)
✓ Kode lebih organized dan terstruktur
✓ Tidak ada duplikasi CSS atau JS di multiple blade files
