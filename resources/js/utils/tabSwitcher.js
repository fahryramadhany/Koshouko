/* ===================================
   KOSHOUKO TAB SWITCHER UTILITY
   Tab switching and filtering functionality
   =================================== */

/**
 * Switch tabs functionality (general)
 * Switches between different tab panels based on tab name
 * @param {string} tabName - The name of the tab to switch to
 */
export function switchTab(tabName) {
    const buttons = document.querySelectorAll('.tab-btn');
    const tabs = document.querySelectorAll('[data-status]');

    // Update button states
    buttons.forEach(btn => {
        if (btn.getAttribute('data-tab') === tabName) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // Update tab visibility
    if (tabName === 'all') {
        tabs.forEach(tab => tab.style.display = 'block');
    } else {
        tabs.forEach(tab => {
            const status = tab.getAttribute('data-status');
            tab.style.display = (status === tabName) ? 'block' : 'none';
        });
    }
}

/**
 * Switch tabs for member borrowings page
 * Filters borrowing items based on status (all, active, returned)
 * @param {string} tab - The tab to switch to ('all', 'active', 'returned')
 */
export function switchBorrowingTab(tab) {
    const allItems = document.querySelectorAll('[data-status]');
    const buttons = document.querySelectorAll('.tab-btn');

    // Update button styles
    buttons.forEach(btn => {
        btn.classList.remove('active', 'border-primary');
        btn.classList.add('border-transparent');
    });
    document.querySelector(`[data-tab="${tab}"]`)?.classList.add('active', 'border-primary');

    // Filter items
    if (tab === 'all') {
        allItems.forEach(item => item.style.display = 'block');
    } else if (tab === 'active') {
        allItems.forEach(item => {
            const status = item.getAttribute('data-status');
            item.style.display = (status === 'pending' || status === 'approved' || status === 'overdue' || status === 'pending_return') ? 'block' : 'none';
        });
    } else if (tab === 'returned') {
        allItems.forEach(item => {
            const status = item.getAttribute('data-status');
            item.style.display = (status === 'returned' || status === 'rejected') ? 'block' : 'none';
        });
    }
}

/**
 * Initialize tab switcher functionality
 * Attaches click handlers to all tab buttons
 */
export function initTabSwitcher() {
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                if (this.classList.contains('borrowing-tab')) {
                    switchBorrowingTab(tabName);
                } else {
                    switchTab(tabName);
                }
            });
        });
    });
}
