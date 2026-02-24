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
    // Backward-compatibility: redirect to the full borrowing form page
    window.location.href = '/books/' + bookId + '/borrow';
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
