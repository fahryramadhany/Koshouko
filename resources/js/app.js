import './bootstrap';
/* ===== Import Modular JavaScript ===== */
// Utilities
import { initMenuToggle } from './utils/menuToggle';
import { initTabSwitcher, switchTab, switchBorrowingTab } from './utils/tabSwitcher';

// Components
import { initCarousels } from './components/carousel';
import { initBorrowModal, borrowBook, closeBorrowModal, openBorrowModal } from './components/borrowModal';

/* ===================================== */

// Initialize all modules
document.addEventListener('DOMContentLoaded', function() {
    // Initialize menu toggle
    initMenuToggle();
    
    // Initialize tab switchers
    initTabSwitcher();
    
    // Initialize carousels
    initCarousels();
    
    // Initialize borrow modal
    initBorrowModal();
    
    // Make global functions available for inline onclick handlers
    window.toggleMobileMenu = () => {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('mobile-menu-backdrop');
        if (sidebar && backdrop) {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    };
    window.switchTab = switchTab;
    window.switchBorrowingTab = switchBorrowingTab;
    window.borrowBook = borrowBook;
    window.closeBorrowModal = closeBorrowModal;
    window.openBorrowModal = openBorrowModal;
});