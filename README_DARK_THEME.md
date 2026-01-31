# 🌙 Perpustakaan Digital - Shinigami Dark Theme Edition

> Aplikasi Perpustakaan Digital dengan tema gelap profesional dan modern

## 🎯 Overview

**Perpustakaan Digital** adalah aplikasi web lengkap untuk manajemen perpustakaan digital dengan fitur-fitur:

✅ Manajemen koleksi buku digital
✅ Sistem peminjaman otomatis dengan approval workflow
✅ Dashboard admin dengan statistik & laporan
✅ Profile member dengan riwayat peminjaman
✅ Sistem denda otomatis untuk keterlambatan
✅ Pengumuman dan bookmark favorit
✅ **Tema Gelap (Dark Theme) Shinigami** - Dioptimalkan untuk readability & eye comfort

---

## 📋 Quick Start

### Prerequisites
```bash
- PHP 8.1+
- Composer
- Node.js & npm
- SQLite atau database lainnya
```

### Installation
```bash
# 1. Clone/Extract repository
cd perpus_digit_laravel

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Setup database dengan demo data
php artisan migrate:fresh --seed

# 5. Start development server
php artisan serve
```

Server berjalan di: **http://localhost:8000**

---

## 👤 Demo Accounts

Login dengan akun berikut untuk testing:

### Admin
```
Email: admin@perpustakaan.com
Password: password
Akses: Full system, dashboards, reports, user management
```

### Pustakawan (Librarian)
```
Email: pustakawan@perpustakaan.com
Password: password
Akses: Book management, borrowing approvals, member management
```

### Member
```
Email: member1@perpustakaan.com (atau member2, member3)
Password: password
Akses: View books, request borrowing, manage bookmarks
```

---

## 🎨 Shinigami Dark Theme

### Color Palette

```
Primary Colors:
• #D4C09A - Aged Paper Beige (Accents, Buttons, Highlights)
• #8B7355 - Dark Brown (Secondary, Supporting Elements)
• #A0826D - Accent Brown (Additional Emphasis)

Background:
• #0a0e27 - Main Background (Deep Dark Navy)
• #1a1f3a - Card Backgrounds (Slightly Lighter Navy)
• #0f1629 - Navigation/Input Backgrounds (Darkest)

Text:
• White (#ffffff) - Primary text
• Gray-300 (#d1d5db) - Secondary text
• Gray-400 (#9ca3af) - Tertiary text

Status Indicators:
• Green (#22c55e) - Success, Available, Approved
• Yellow (#eab308) - Warning, Pending
• Red (#ef4444) - Error, Overdue, Inactive
• Blue (#3b82f6) - Info, In Progress
```

### Features

✨ **Professional Design**
- Consistent dark theme across all pages
- High contrast for readability (WCAG 2.1 AA compliant)
- Modern aesthetic with smooth transitions

✨ **User Experience**
- Reduced eye strain
- Professional appearance
- Clear visual hierarchy
- Intuitive navigation

✨ **Responsive**
- Optimized for mobile, tablet, desktop
- Touch-friendly interface
- Adaptive layouts

---

## 📁 Project Structure

```
perpus_digit_laravel/
│
├── app/
│   ├── Http/Controllers/          # Controller untuk business logic
│   ├── Models/                    # Database models
│   └── Providers/                 # Service providers
│
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.blade.php    ✅ Dark Theme
│   │   │   └── register.blade.php ✅ Dark Theme
│   │   ├── layouts/
│   │   │   ├── auth-app.blade.php ✅ Dark Sidebar & Nav
│   │   │   └── app.blade.php      ✅ Dark Background
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php ✅
│   │   │   ├── books/
│   │   │   ├── users/
│   │   │   ├── borrowings/        ✅
│   │   │   ├── reports/           ✅
│   │   │   ├── announcements/     ✅
│   │   │   └── categories/        ✅
│   │   └── member/
│   │       ├── dashboard.blade.php ✅
│   │       ├── books/
│   │       │   ├── index.blade.php ✅
│   │       │   └── show.blade.php  ✅
│   │       └── borrowings/
│   │           └── index.blade.php ✅
│   ├── css/                       # CSS files
│   └── js/                        # JavaScript files
│
├── public/
│   ├── index.php
│   ├── logo_koshouko.jpeg         # New Logo ✅
│   └── ...
│
├── database/
│   ├── migrations/                # Database schema
│   └── seeders/                   # Demo data seeder
│
├── routes/
│   ├── web.php                    # Web routes
│   └── console.php                # Artisan commands
│
├── tests/                         # Unit & feature tests
│
├── TEST_GUIDE.md                  # 🧪 Comprehensive Testing Guide
├── DARK_THEME_UPDATE.md           # 🌙 Dark Theme Documentation
├── DOCUMENTATION.md               # 📖 Full API Documentation
├── SUMMARY.md                     # 📋 Implementation Summary
└── README.md                      # 📄 This file
```

---

## 🎯 Main Features

### 1. 📚 Book Management
- **Admin can:** Add, edit, delete books
- **Categories:** Organize books by category
- **Availability:** Track stock and availability
- **Details:** ISBN, publisher, year, pages, language, location

### 2. 👥 User Management
- **Admin can:** Create, edit, deactivate users
- **Roles:** Admin, Pustakawan (Librarian), Member
- **Status:** Active, Inactive, Suspended
- **Member ID:** Auto-generated for members

### 3. 📋 Borrowing System
- **Members can:** Request to borrow books
- **Admin can:** Approve or reject requests
- **Duration:** 14 days per borrowing
- **Extension:** Max 2x extension per book
- **Return:** Track returns and updates

### 4. 💰 Fine System
- **Automatic:** Fines calculated for overdue books
- **Rate:** Rp 5.000 per day
- **Tracking:** Admin can view pending fines
- **Payment:** Mark as paid or waived

### 5. ⭐ Bookmarks
- **Members can:** Add books to favorites
- **Management:** View and manage bookmarks
- **Persistence:** Saved per user

### 6. 📢 Announcements
- **Admin can:** Post announcements
- **Publish:** Draft and publish functionality
- **Distribution:** Visible to all users

### 7. 📊 Dashboard & Reports
- **Admin Dashboard:** System statistics and metrics
- **Member Dashboard:** Personal borrowing info
- **Reports:** Borrowing trends, overdue books, fine status
- **Analytics:** Most borrowed books, member activity

---

## 🧪 Testing

### Running Tests
```bash
# Unit tests
php artisan test

# Feature tests
php artisan test --filter=Feature

# Specific test class
php artisan test tests/Feature/ExampleTest.php
```

### Manual Testing
See [TEST_GUIDE.md](./TEST_GUIDE.md) for comprehensive testing scenarios and checklists.

### Test Coverage
- Authentication & Authorization ✅
- CRUD Operations ✅
- Borrowing Workflow ✅
- Dark Theme Consistency ✅
- Responsive Design ✅

---

## 🔐 Security Features

✅ **Authentication**
- Secure login/register with validation
- Password hashing with Bcrypt
- Session management

✅ **Authorization**
- Role-based access control (RBAC)
- Route middleware protection
- Admin-only operations

✅ **Validation**
- Input validation on all forms
- Server-side validation
- Error messaging

---

## 📱 Responsive Design

| Device | Width | Status |
|--------|-------|--------|
| Mobile | 320px+ | ✅ Optimized |
| Tablet | 768px+ | ✅ Optimized |
| Desktop | 1024px+ | ✅ Optimized |

---

## 🎨 Dark Theme Pages Included

### Admin Pages (7)
- ✅ Dashboard
- ✅ Books Management
- ✅ Users Management
- ✅ Borrowing Management
- ✅ Reports & Statistics
- ✅ Announcements
- ✅ Categories Management

### Member Pages (4)
- ✅ Dashboard
- ✅ Books Catalog
- ✅ Book Details
- ✅ Borrowing History

### Auth Pages (2)
- ✅ Login
- ✅ Register

### Layout Components (2)
- ✅ Sidebar Navigation
- ✅ Top Navigation Bar

**Total Pages: 15+ pages with dark theme** ✅

---

## 📚 Documentation

### Available Guides
1. [TEST_GUIDE.md](./TEST_GUIDE.md) - Complete testing guide with scenarios
2. [DARK_THEME_UPDATE.md](./DARK_THEME_UPDATE.md) - Dark theme implementation details
3. [DOCUMENTATION.md](./DOCUMENTATION.md) - Full API and feature documentation
4. [SUMMARY.md](./SUMMARY.md) - Project implementation summary

---

## 🚀 Deployment

### Production Checklist
- [ ] Update `.env` with production variables
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate app key if not done
- [ ] Run migrations on production database
- [ ] Test all features in production

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Dark theme not loading | Clear browser cache (Ctrl+Shift+Delete) and reload |
| Logo not displaying | Check `/public/logo_koshouko.jpeg` exists |
| Database error | Run `php artisan migrate:fresh --seed` |
| Routes not working | Run `php artisan route:clear` and `php artisan cache:clear` |
| Assets not loading | Run `npm install && npm run dev` |

---

## 📞 Support

For issues or questions:
1. Check [TEST_GUIDE.md](./TEST_GUIDE.md) for testing scenarios
2. Review [DOCUMENTATION.md](./DOCUMENTATION.md) for API details
3. Check [DARK_THEME_UPDATE.md](./DARK_THEME_UPDATE.md) for theme specifics

---

## 📝 Version History

### v1.0 - Shinigami Dark Theme Edition
- ✅ Complete dark theme implementation
- ✅ Logo integration (logo_koshouko.jpeg)
- ✅ All pages converted to dark theme
- ✅ Comprehensive testing guide
- ✅ Full feature set (same as previous versions)

---

## 💡 Tips & Best Practices

### For Administrators
1. Regularly check Reports & Statistics for overdue books
2. Approve borrowing requests promptly
3. Post regular announcements for important information
4. Monitor user activity through dashboard

### For Librarians
1. Keep book inventory updated
2. Verify borrowing requests before approval
3. Update book information (ISBN, publisher, etc.)
4. Manage book categories effectively

### For Members
1. Check borrowing history regularly
2. Return books on time to avoid fines
3. Use bookmarks to save favorite books
4. Request book extensions before due date

---

## 🎯 Future Enhancements

Possible future features:
- [ ] Light theme toggle
- [ ] Export reports to PDF
- [ ] Email notifications for overdue books
- [ ] QR code for book search
- [ ] Advanced search filters
- [ ] Book cover uploads
- [ ] Rating and review system
- [ ] Reading progress tracking

---

## 📄 License

Project ini adalah milik internal dan dikembangkan untuk keperluan Perpustakaan Digital Koshouko.

---

## ✨ Credits

**Developed with:**
- Laravel 11
- Tailwind CSS
- Blade Template Engine
- SQLite Database

**Theme:** Shinigami Dark Theme v1.0

---

**Status:** ✅ Ready for Production
**Last Updated:** $(date)
**Support:** For questions, refer to documentation files in project root.

---

## 🌟 Key Highlights

✨ **Modern Dark Theme** - Professional and eye-friendly
🔐 **Secure** - RBAC, input validation, password hashing
📱 **Responsive** - Works on all devices
⚡ **Fast** - Optimized database queries
🎯 **Complete** - All requested features implemented
📚 **Well Documented** - Comprehensive guides and API docs

---

**Made with ❤️ for a Better Library Experience**
