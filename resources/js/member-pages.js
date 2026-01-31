/* ===================================
   KOSHOUKO MEMBER PAGES JAVASCRIPT
   JavaScript functions for member pages (borrowings, books)
   =================================== */

/**
 * Switch tabs for borrowings page
 * Filters borrowing items based on status
 */
function switchTab(tab) {
    const allItems = document.querySelectorAll('[data-status]');
    const buttons = document.querySelectorAll('.tab-btn');

    // Update button styles
    buttons.forEach(btn => {
        btn.classList.remove('active', 'border-primary');
        btn.classList.add('border-transparent');
    });
    document.querySelector(`[data-tab="${tab}"]`).classList.add('active', 'border-primary');

    // Filter items
    if (tab === 'all') {
        allItems.forEach(item => item.style.display = 'block');
    } else if (tab === 'active') {
        allItems.forEach(item => {
            const status = item.getAttribute('data-status');
            item.style.display = (status === 'pending' || status === 'approved' || status === 'overdue') ? 'block' : 'none';
        });
    } else if (tab === 'returned') {
        allItems.forEach(item => {
            const status = item.getAttribute('data-status');
            item.style.display = (status === 'returned' || status === 'rejected') ? 'block' : 'none';
        });
    }
}

/**
 * Borrow book - opens modal dialog
 * @param {number} bookId - The ID of the book to borrow
 */
function borrowBook(bookId) {
    const modal = document.getElementById('borrowModal');
    // support modal form id used across pages
    const form = document.getElementById('borrowForm') || document.getElementById('borrowingForm');
    const titleEl = document.getElementById('borrowBookTitle');

    // Get book title from the card
    const bookCard = event?.target?.closest('[class*="grid"]')?.querySelector('h3');
    if (bookCard && titleEl) {
        titleEl.textContent = 'Anda akan meminjam: ' + bookCard.textContent;
    }

    // POST must go to the collection endpoint; set book_id on the form instead of targeting the GET route
    if (form) {
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
            // create hidden input if not present
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'book_id';
            hidden.value = String(bookId);
            form.appendChild(hidden);
        }
    }

    modal && modal.classList.remove('hidden');
}

/**
 * Close borrow modal dialog
 */
function closeBorrowModal() {
    document.getElementById('borrowModal').classList.add('hidden');
}

/**
 * Close modal when clicking backdrop
 */
document.addEventListener('DOMContentLoaded', function() {
    const borrowModal = document.getElementById('borrowModal');
    if (borrowModal) {
        borrowModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBorrowModal();
            }
        });
    }
});
