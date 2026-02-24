/* ===================================
   KOSHOUKO BORROW MODAL COMPONENT
   Modal dialog for book borrowing functionality
   =================================== */

/**
 * Borrow book - opens modal or redirects to form page
 * @param {number} bookId - The ID of the book to borrow
 */
export function borrowBook(bookId) {
    // Backward-compatibility: redirect to the full borrowing form page
    window.location.href = '/books/' + bookId + '/borrow';
}

/**
 * Close borrow modal dialog
 */
export function closeBorrowModal() {
    const modal = document.getElementById('borrowModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

/**
 * Open borrow modal dialog
 */
export function openBorrowModal() {
    const modal = document.getElementById('borrowModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

/**
 * Initialize borrow modal functionality
 * Attaches event listeners for modal interactions
 */
export function initBorrowModal() {
    document.addEventListener('DOMContentLoaded', function() {
        const borrowModal = document.getElementById('borrowModal');
        
        if (borrowModal) {
            // Close when clicking backdrop
            borrowModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBorrowModal();
                }
            });

            // Close when clicking close button
            const closeBtn = borrowModal.querySelector('[data-close-modal]');
            if (closeBtn) {
                closeBtn.addEventListener('click', closeBorrowModal);
            }

            // Optional: Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !borrowModal.classList.contains('hidden')) {
                    closeBorrowModal();
                }
            });
        }

        // Attach click handlers to borrow buttons
        const borrowButtons = document.querySelectorAll('[data-borrow-btn]');
        borrowButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const bookId = this.getAttribute('data-book-id');
                if (bookId) {
                    borrowBook(bookId);
                }
            });
        });
    });
}

/**
 * Initialize on module load
 */
initBorrowModal();
