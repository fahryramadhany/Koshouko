# 🧪 TESTING GUIDE - PERPUSTAKAAN DIGITAL SHINIGAMI DARK THEME

## ✅ Persiapan

### Server Setup
```bash
# Install dependencies
composer install
npm install

# Setup database
php artisan migrate:fresh --seed

# Start development server
php artisan serve
```

Server akan berjalan di: **http://localhost:8000**

---

## 👤 Demo Accounts (Dari DatabaseSeeder)

### 1. **Admin Account**
- **Email:** `admin@perpustakaan.com`
- **Password:** `password`
- **Role:** Administrator
- **Access:** Full system access, dashboards, reports, user management

### 2. **Pustakawan Account**
- **Email:** `pustakawan@perpustakaan.com`
- **Password:** `password`
- **Role:** Librarian
- **Access:** Book management, borrowing approvals, member management

### 3. **Member Accounts** (Auto-generated)
- **Email:** `member1@perpustakaan.com`, `member2@perpustakaan.com`, `member3@perpustakaan.com`
- **Password:** `password`
- **Role:** Regular Member
- **Access:** View books, request borrowing, manage bookmarks

---

## 🧪 TEST SCENARIOS

### Phase 1: Authentication & Layout (Login/Register)

#### Test 1.1 - Login Page Dark Theme
**Steps:**
1. Go to http://localhost:8000/login
2. Verify the page has dark background (#0a0e27 or similar dark color)
3. Verify the navbar is dark
4. Verify the logo is displaying correctly
5. Verify input fields have dark borders and backgrounds

**Expected Results:**
- ✅ Dark theme applied throughout
- ✅ Logo visible and properly sized
- ✅ Input fields are dark with light text
- ✅ Buttons have correct gradient styling
- ✅ All text is visible and readable

#### Test 1.2 - Register Page Dark Theme
**Steps:**
1. Go to http://localhost:8000/register
2. Verify dark theme consistency
3. Verify form inputs are dark-themed
4. Check logo display

**Expected Results:**
- ✅ All elements match dark theme
- ✅ Form is properly styled
- ✅ Validation messages are visible

#### Test 1.3 - Login Functionality
**Steps:**
1. Use Admin credentials: `admin@perpustakaan.com` / `password`
2. Click "Masuk"
3. Should redirect to admin dashboard

**Expected Results:**
- ✅ Login successful
- ✅ Redirected to dashboard
- ✅ User info shows in sidebar

---

### Phase 2: Admin Dashboard & Navigation

#### Test 2.1 - Admin Dashboard Dark Theme
**After logging in with admin account:**

1. **Verify Layout:**
   - Sidebar is dark (#0f1629 background)
   - Navigation links are gray (#text-gray-300)
   - Hover effect shows darker background (#hover:bg-gray-700)

2. **Verify Dashboard Cards:**
   - Stat cards have dark backgrounds
   - Numbers display in primary color (#D4C09A)
   - All text is readable

3. **Verify Tables & Data:**
   - Table headers are dark
   - Row backgrounds alternate properly
   - Hover effects work correctly

**Expected Results:**
- ✅ Sidebar is properly dark-themed
- ✅ Navigation is fully functional
- ✅ All dashboard elements match dark theme
- ✅ Colors are consistent

#### Test 2.2 - Navigation Menu
**Steps:**
1. Check Admin Navigation Links:
   - 📊 Dashboard
   - 📚 Manajemen Buku
   - 👥 Manajemen Pengguna
   - 📋 Kelola Peminjaman
   - 📈 Laporan & Statistik
   - 📢 Pengumuman
2. Click each link and verify page loads with dark theme
3. Verify active link has primary color highlight

**Expected Results:**
- ✅ All links work correctly
- ✅ Active link is highlighted
- ✅ All pages have dark theme applied
- ✅ No layout breaks

---

### Phase 3: Admin Features Dark Theme

#### Test 3.1 - Books Management (Admin)
**Path:** Admin → Manajemen Buku

**Verify:**
1. Table has dark background
2. Book list displays correctly
3. Category badges are visible (primary color)
4. Status badges are visible (color-coded)
5. Action buttons (Edit, Delete) are accessible

**Expected Results:**
- ✅ Table is fully dark-themed
- ✅ All data displays correctly
- ✅ Badges are properly styled
- ✅ Actions work correctly

#### Test 3.2 - Users Management (Admin)
**Path:** Admin → Manajemen Pengguna

**Verify:**
1. User table is dark-themed
2. User status badges display correctly
3. Active/Inactive status is visible
4. Edit and Delete buttons work

**Expected Results:**
- ✅ User table fully dark-themed
- ✅ Status badges are color-coded
- ✅ All functions work

#### Test 3.3 - Borrowing Management (Admin)
**Path:** Admin → Kelola Peminjaman

**Verify:**
1. Borrowing table has dark background
2. Status filter dropdown works
3. Status badges show correctly (Pending, Approved, Overdue, Returned)
4. Approve/Reject buttons work for pending items
5. Overdue indicator displays

**Expected Results:**
- ✅ Table is properly dark-themed
- ✅ All filters work
- ✅ Status badges are correct
- ✅ Actions are functional

#### Test 3.4 - Reports & Statistics
**Path:** Admin → Laporan & Statistik

**Verify:**
1. Stat cards have dark backgrounds
2. Numbers display in correct colors
3. Fine table is dark-themed
4. Most borrowed books list displays correctly

**Expected Results:**
- ✅ All cards are dark-themed
- ✅ Numbers are visible and readable
- ✅ Tables display correctly
- ✅ Statistics are accurate

#### Test 3.5 - Announcements
**Path:** Admin → Pengumuman

**Verify:**
1. Form card is dark-themed
2. Input fields are dark with light text
3. Submit button works
4. Posted announcements display in dark theme
5. Status badges are visible

**Expected Results:**
- ✅ Form is properly styled
- ✅ Inputs work correctly
- ✅ Announcements display correctly
- ✅ All UI elements are dark-themed

#### Test 3.6 - Categories Management
**Path:** Admin → Manajemen Buku → Categories (sidebar)

**Verify:**
1. Category cards are dark-themed
2. Book count displays correctly
3. Edit button works
4. Delete button works and has correct styling (red)

**Expected Results:**
- ✅ Cards are dark-themed
- ✅ All data displays correctly
- ✅ Actions are functional

---

### Phase 4: Member Features Dark Theme

#### Test 4.1 - Member Dashboard
**Login with:** `member1@perpustakaan.com` / `password`

**Path:** Member Dashboard

**Verify:**
1. Sidebar shows member-specific navigation
2. Dashboard cards are dark-themed
3. Borrowing history displays correctly
4. All text is readable

**Expected Results:**
- ✅ Dashboard is properly themed
- ✅ Member data displays correctly
- ✅ All elements are dark-themed

#### Test 4.2 - Books Catalog
**Path:** Member → Katalog Buku

**Verify:**
1. Filter sidebar is dark (#0f1629)
2. Filter dropdown has dark background
3. Book cards are dark-themed
4. Category badges display correctly
5. Availability indicator works
6. "Pinjam" button is visible and functional

**Expected Results:**
- ✅ Sidebar filter is properly themed
- ✅ Book catalog displays correctly
- ✅ All filters work
- ✅ Borrowing buttons are functional

#### Test 4.3 - Book Details Page
**Path:** Click any book in catalog → View Details

**Verify:**
1. Book detail card is dark-themed
2. Book cover placeholder displays
3. All book information is readable
4. "Pinjam Buku" button displays and works
5. "Tambah/Hapus Favorit" button works
6. Related books section shows books in dark theme
7. Borrowing history displays correctly

**Expected Results:**
- ✅ All elements are dark-themed
- ✅ Text is readable
- ✅ Buttons function correctly
- ✅ Related books display properly

#### Test 4.4 - Borrowing History
**Path:** Member → Riwayat Peminjaman

**Verify:**
1. Borrowing cards are dark-themed
2. Status badges display correctly
3. Tab navigation works (Semua, Dipinjam, Tertunggak, Dikembalikan)
4. Extension button works for active borrowings
5. Return button works

**Expected Results:**
- ✅ Cards are dark-themed
- ✅ Tabs function correctly
- ✅ All actions work
- ✅ Data displays accurately

---

### Phase 5: Complete Workflow Test

#### Test 5.1 - Full Borrowing Process
**Steps:**
1. **Login as Member:** `member1@perpustakaan.com` / `password`
2. **Browse Books:** Go to Katalog Buku
3. **View Details:** Click on a book
4. **Request Borrow:** Click "Pinjam Buku" button
5. **Verify Status:** Check Riwayat Peminjaman for "Pending" status
6. **Logout and Login as Admin:** `admin@perpustakaan.com` / `password`
7. **Approve Request:** Go to Kelola Peminjaman, find pending request, click "Setujui"
8. **Login Back as Member:** Verify status changed to "Dipinjam"

**Expected Results:**
- ✅ All steps complete successfully
- ✅ Status updates correctly
- ✅ UI remains consistent throughout
- ✅ Dark theme applied on all pages

---

### Phase 6: Responsive Design & Dark Theme

#### Test 6.1 - Mobile View
**Steps:**
1. Open Developer Tools (F12)
2. Set viewport to mobile (375px width)
3. Navigate through different pages
4. Verify:
   - Sidebar toggles correctly
   - Content is readable on small screens
   - Dark theme is consistent
   - Navigation is accessible

**Expected Results:**
- ✅ Layout adjusts properly
- ✅ Dark theme is maintained
- ✅ All elements are accessible
- ✅ Navigation works smoothly

#### Test 6.2 - Tablet View
**Steps:**
1. Set viewport to tablet (768px)
2. Verify layout is optimal
3. Check sidebar behavior
4. Verify dark theme consistency

**Expected Results:**
- ✅ Layout is optimal for tablet
- ✅ Dark theme is consistent
- ✅ All features work correctly

---

## 🎨 Dark Theme Color Verification

### Current Color Scheme
- **Background (Main):** `#0a0e27` (class: `bg-gray-900`)
- **Background (Cards):** `#1a1f3a` (class: `bg-gray-800`)
- **Background (Nav/Inputs):** `#0f1629` (class: `bg-gray-700`)
- **Primary Color:** `#D4C09A` (aged paper beige)
- **Secondary Color:** `#8B7355` (dark brown)
- **Text (Primary):** `white`
- **Text (Secondary):** `text-gray-300` or `text-gray-400`
- **Status Badges (Success):** `bg-green-900/50 text-green-400`
- **Status Badges (Warning):** `bg-yellow-900/50 text-yellow-400`
- **Status Badges (Error):** `bg-red-900/50 text-red-400`

### Verification Checklist
- [ ] All backgrounds are dark
- [ ] All text is light (white or gray-300/400)
- [ ] Primary color accents are visible and consistent
- [ ] Status badges are color-coded
- [ ] Borders are dark gray
- [ ] Hover effects are visible

---

## 📋 Testing Checklist

### Pages Tested ✅
- [ ] Login Page
- [ ] Register Page
- [ ] Admin Dashboard
- [ ] Admin Books Index
- [ ] Admin Users Index
- [ ] Admin Borrowings Index
- [ ] Admin Reports
- [ ] Admin Announcements
- [ ] Admin Categories
- [ ] Member Dashboard
- [ ] Member Books Catalog
- [ ] Member Books Detail
- [ ] Member Borrowing History

### Features Tested ✅
- [ ] Authentication (Login/Register)
- [ ] Navigation (Sidebar, Links)
- [ ] CRUD Operations (Books, Users, Categories)
- [ ] Borrowing Workflow
- [ ] Filtering and Search
- [ ] Status Updates
- [ ] Responsive Design

### Dark Theme Consistency ✅
- [ ] All pages use dark colors
- [ ] Text is readable (sufficient contrast)
- [ ] Badges are properly colored
- [ ] Buttons are functional and visible
- [ ] Inputs are dark-themed
- [ ] Hover effects work

---

## 🐛 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Text not visible on dark background | Check text color utility classes (text-white, text-gray-300, etc.) |
| Buttons not clickable | Ensure buttons have proper z-index and are not covered by other elements |
| Dark theme not loading | Clear browser cache (Ctrl+Shift+Delete) |
| Logo not showing | Check file path in src attribute (should be `/logo_koshouko.jpeg`) |
| Colors not matching | Verify CSS variables in `:root` styles match expected values |

---

## 📝 Notes

- All pages have been updated to use the **Shinigami Dark Theme**
- Color scheme is optimized for readability and aesthetic appeal
- Primary and secondary colors are maintained for branding consistency
- Demo accounts are seeded automatically with `php artisan migrate:fresh --seed`
- Logo has been updated to `logo_koshouko.jpeg`

---

## ✨ What's New in This Version

✅ **Complete Dark Theme Implementation**
- All admin pages converted to dark theme
- All member pages converted to dark theme
- Consistent color scheme throughout
- Improved readability on dark backgrounds

✅ **Logo Update**
- New logo file: `logo_koshouko.jpeg`
- Updated all references in layouts, login, and register pages
- Logo displays correctly in all locations

✅ **Enhanced Status Badges**
- Color-coded status indicators
- Semi-transparent backgrounds for depth
- Better visual distinction between statuses

✅ **Improved Form Styling**
- Dark input fields with light borders
- Better focus states
- Improved accessibility

---

## 🚀 Next Steps

After testing:
1. Deploy to production
2. Monitor user feedback
3. Adjust colors based on feedback if needed
4. Consider adding light theme toggle in future versions

---

**Last Updated:** $(date)
**Status:** ✅ Ready for Testing
**Theme:** Shinigami Dark Theme v1.0
