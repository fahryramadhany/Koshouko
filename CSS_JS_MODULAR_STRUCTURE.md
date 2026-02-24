# CSS & JavaScript Organization Guide

## Overview
The Koshouko Digital Library has reorganized its CSS and JavaScript files into a modular, maintainable structure following the **Single Responsibility Principle**. Each file has one clear purpose, making the codebase easier to navigate, maintain, and extend.

---

## CSS File Structure

### Directory Layout
```
resources/css/
├── app.css                    (Main entry point - imports all modular CSS)
├── colors.css                 (Color theme variables and utilities)
├── components/
│   ├── alerts.css            (Alert and notification styles)
│   ├── badges.css            (Badge, label, and status styles)
│   ├── buttons.css           (Button styles and variants)
│   ├── cards.css             (Card and container styles)
│   ├── forms.css             (Form and input styling)
│   ├── inputs.css            (Custom input components)
│   ├── modals.css            (Modal dialog styling)
│   ├── pagination.css        (Pagination and breadcrumb styles)
│   └── tables.css            (Table styling)
└── layouts/
    ├── animations.css        (Keyframe animations and transitions)
    ├── navbar.css            (Navigation bar styling)
    ├── sidebar.css           (Sidebar and mobile menu styling)
    ├── typography.css        (Text, headings, and typography)
    └── utilities.css         (Utility classes for spacing, display, etc.)
```

### File Descriptions

#### `colors.css`
Contains all color theme variables using CSS custom properties:
- Primary colors (brown/tan theme)
- Secondary, success, warning, danger, info colors
- Utility classes for background, text, and border colors

#### Components

**`buttons.css`**
- `.btn-koshouko` - Primary button style
- `.btn-outline` - Outlined button
- `.btn-secondary` - Secondary button
- `.btn-success`, `.btn-danger`, `.btn-warning` - Status buttons
- Hover, active, and disabled states
- Size variants (sm, lg, xl)

**`cards.css`**
- `.gradient-card` - Card with gradient background
- `.stat-card` - Statistics display card
- `.book-card` - Book display card
- Hover animations and shadow effects

**`forms.css`**
- Form element styling (groups, labels, help text)
- Input field defaults (text, email, password, etc.)
- Focus and error states
- Checkbox and radio styling
- Select dropdown styling
- File input styling
- Error message display

**`inputs.css`**
- Advanced input components
- Input variants (primary, success, warning, error)
- Input sizes (sm, lg, xl)
- Input groups with addons
- Search input with icon
- Number, range, color, date inputs
- Character counter
- File upload styling

**`tables.css`**
- Table structure and styling
- Thead and tbody styling
- Striped table variant
- Responsive table design
- Sortable headers
- Pagination in tables
- Empty state styling
- Hover effects

**`badges.css`**
- Badge base styles
- Status badges (pending, approved, completed, etc.)
- Color variants (primary, success, warning, danger, info)
- Light variants
- Badge sizes (sm, lg)
- Pill badges
- Borrow status specific badges

**`alerts.css`**
- Alert containers with different types
- Alert success, error, warning, info variants
- Dismissible alerts
- Notification positioning
- Toast notifications
- Alert animations

**`modals.css`**
- Modal backdrop and container
- Modal dialog with header, body, footer
- Modal sizes (sm, lg, xl)
- Modal animations (fade, slide)
- Modal status variants (success, warning, error, info)
- Scrollable modals
- Responsive modal behavior

**`pagination.css`**
- Pagination controls
- Breadcrumb navigation
- Step navigation for multi-step forms
- Active and disabled states
- Pagination info display
- Responsive pagination

#### Layouts

**`navbar.css`**
- Fixed navigation bar positioning
- Responsive navbar behavior
- Navbar responsive adjustments for sidebar

**`sidebar.css`**
- Fixed sidebar layout
- Mobile menu toggle
- Navigation item styling
- Active state indicators
- Mobile menu animation

**`typography.css`**
- Heading styles (h1-h6)
- Paragraph and text utilities
- Text alignment classes
- Font weight utilities
- Line height utilities

**`utilities.css`**
- Display utilities (flex, grid, block, inline, etc.)
- Flexbox utilities (direction, alignment, gap)
- Margin and padding utilities
- Width and height utilities
- Border radius utilities
- Border and shadow utilities
- Z-index utilities

**`animations.css`**
- Keyframe animations (fade, slide, zoom, bounce, pulse, spin, shake, etc.)
- Animation utility classes
- Animation timing classes
- Transform utility classes
- Transition utilities

### Using CSS Classes

Example HTML:
```html
<!-- Button with styles -->
<button class="btn-koshouko mt-4 mb-2">Click Me</button>

<!-- Card with animation -->
<div class="gradient-card shadow-lg animate-fade-in">
    <h3>Title</h3>
    <p>Content here</p>
</div>

<!-- Alert -->
<div class="alert alert-success">
    <div class="alert-icon"></div>
    <div class="alert-content">
        <div class="alert-title">Success!</div>
        <p class="alert-text">Your action was successful.</p>
    </div>
</div>

<!-- Form -->
<form class="form-group mb-4">
    <label class="required">Username</label>
    <input type="text" class="input input-primary" placeholder="Enter username">
    <span class="help-text">Choose a unique username</span>
</form>

<!-- Table -->
<div class="table-responsive">
    <table class="striped table-bordered">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Data 1</td>
                <td>Data 2</td>
            </tr>
        </tbody>
    </table>
</div>
```

---

## JavaScript File Structure

### Directory Layout
```
resources/js/
├── app.js                     (Main entry point - imports all modules)
├── bootstrap.js               (Laravel framework bootstrap)
├── utils/
│   ├── menuToggle.js         (Mobile menu toggle functionality)
│   └── tabSwitcher.js        (Tab switching and filtering)
└── components/
    ├── carousel.js           (Carousel navigation)
    └── borrowModal.js        (Borrow modal functionality)
```

### File Descriptions

#### Utilities

**`menuToggle.js`**
```javascript
// Export functions
export function toggleMobileMenu()  // Toggle sidebar visibility
export function initMenuToggle()    // Initialize menu toggle
```

Example usage:
```html
<!-- In your blade template -->
<button onclick="window.toggleMobileMenu()">Menu</button>
```

**`tabSwitcher.js`**
```javascript
// Export functions
export function switchTab(tabName)              // Generic tab switcher
export function switchBorrowingTab(tab)         // Borrowing-specific tab switcher
export function initTabSwitcher()               // Initialize tab functionality
```

Example usage:
```html
<!-- Tab buttons -->
<button class="tab-btn" data-tab="all" onclick="window.switchTab('all')">All</button>
<button class="tab-btn" data-tab="active" onclick="window.switchTab('active')">Active</button>

<!-- Tab content -->
<div data-status="active">Active items</div>
<div data-status="completed">Completed items</div>
```

#### Components

**`carousel.js`**
```javascript
// Export function
export function initCarousels()  // Initialize all carousels
```

Automatically initializes carousels with ID `recommendations-carousel`.

**`borrowModal.js`**
```javascript
// Export functions
export function borrowBook(bookId)      // Trigger book borrowing
export function closeBorrowModal()      // Close modal
export function openBorrowModal()       // Open modal
export function initBorrowModal()       // Initialize modal
```

Example usage:
```html
<!-- Borrow button -->
<button onclick="window.borrowBook(123)">Borrow Book</button>

<!-- Modal -->
<div id="borrowModal" class="modal hidden">
    <div class="modal-dialog">
        <div class="modal-header">
            <h2 class="modal-title">Borrow Book</h2>
            <button class="modal-close" onclick="window.closeBorrowModal()">×</button>
        </div>
        <div class="modal-body">
            <!-- Modal content -->
        </div>
    </div>
</div>
```

### App.js Integration

The main `app.js` file:
1. Imports all utility and component modules
2. Initializes all modules on `DOMContentLoaded`
3. Exposes functions to global `window` object for backward compatibility with inline HTML attributes

This allows both modern module-based code and legacy inline handlers to work seamlessly.

---

## Migration from Old Structure

### Old Structure
- `layout.css` - Mixed layout styles
- `carousel.css` - Only carousel
- `member-pages.css` - Member page styles
- `admin-pages.css` - Admin page styles
- `layout.js` - Mixed layout functions
- `carousel.js` - Only carousel
- `member-pages.js` - Mixed member page functions

### New Structure
Same functionality, but:
- Organized by component type (buttons, forms, tables, etc.)
- Clear separation of concerns
- Layout in `/layouts` directory
- Components in `/components` directory
- Utilities in `/utils` directory
- Better maintainability and scalability

### What Changed
1. **CSS is now modular** - Each component type has its own file
2. **JavaScript is modular** - Each feature in its own file with exports
3. **Single entry point** - `app.css` and `app.js` import everything
4. **Better organization** - Easier to find and modify specific styles/functions
5. **Backward compatible** - Old inline handlers still work

---

## Adding New Styles

### To add new button style:
1. Open `/resources/css/components/buttons.css`
2. Add the new class at the end
3. The style is automatically available (via app.css import)

### To add new component:
1. Create new file in `/resources/css/components/mycomponent.css`
2. Add import to `/resources/css/app.css`:
   ```css
   @import 'components/mycomponent.css';
   ```
3. Use the classes in your templates

---

## Adding New Functions

### To add new utility function:
1. Create new file in `/resources/js/utils/myfeature.js`
2. Export your functions:
   ```javascript
   export function myFunction() {
       // Your code
   }
   ```
3. Import in `/resources/js/app.js`:
   ```javascript
   import { myFunction } from './utils/myfeature';
   ```
4. Export to window for inline handlers (if needed):
   ```javascript
   window.myFunction = myFunction;
   ```

---

## Best Practices

1. **Keep files focused** - One responsibility per file
2. **Use modular imports** - Import only what you need
3. **Export reusable functions** - Make functions available globally if used in HTML
4. **Comment your code** - Especially component headers and complex functions  
5. **Test after importing** - Verify functionality works with new structure
6. **Update documentation** - Keep this guide updated with changes

---

## Troubleshooting

### Styles not applying
- Ensure `app.css` is included in your layout
- Check that the module is imported in `app.css`
- Run `npm run build` to compile CSS

### Functions not available
- Check that the function is exported from its module
- Verify it's imported in `app.js`
- Check if it's added to `window` for inline handlers
- Open browser console and check for errors

### Performance issues
- Only import what you need
- Consider lazy-loading heavy components
- Use CSS custom properties for theming

---

## File Summary

| File | Purpose | Lines | Status |
|------|---------|-------|--------|
| colors.css | Color variables | ~80 | ✅ Active |
| buttons.css | Button styles | ~150 | ✅ Active |
| cards.css | Card styles | ~100 | ✅ Active |
| forms.css | Form styling | ~250 | ✅ Active |
| inputs.css | Input components | ~350 | ✅ Active |
| tables.css | Table styling | ~300 | ✅ Active |
| badges.css | Badge/status styles | ~400 | ✅ Active |
| alerts.css | Alert styling | ~300 | ✅ Active |
| modals.css | Modal styling | ~280 | ✅ Active |
| pagination.css | Pagination styles | ~250 | ✅ Active |
| utilities.css | Utility classes | ~200 | ✅ Active |
| animations.css | Animations | ~450 | ✅ Active |
| navbar.css | Navbar styling | ~80 | ✅ Active |
| sidebar.css | Sidebar styling | ~100 | ✅ Active |
| typography.css | Text styling | ~100 | ✅ Active |
| menuToggle.js | Menu toggle | ~40 | ✅ Active |
| tabSwitcher.js | Tab switching | ~80 | ✅ Active |
| carousel.js | Carousel | ~100 | ✅ Active |
| borrowModal.js | Borrow modal | ~80 | ✅ Active |
| **Total CSS** | | ~3200+ lines | ✅ |
| **Total JS** | | ~300+ lines | ✅ |

---

## Next Steps (Optional)

1. **Add more components** - Create additional component files as needed
2. **Optimize CSS** - Consider critical path CSS for faster page loads
3. **Lazy load JS** - Load carousel/modal JS only when needed
4. **Create variants** - Add theme files for different color schemes
5. **Document components** - Create a component library/storybook

---

Last Updated: February 2025
Author: Koshouko Development Team
