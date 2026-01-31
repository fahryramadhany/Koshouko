/* ===================================
   KOSHOUKO LAYOUT FUNCTIONS
   Global JavaScript functions for layout interactions
   =================================== */

/**
 * Toggle mobile menu sidebar
 */
function toggleMobileMenu() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('mobile-menu-backdrop');
    
    if (sidebar && backdrop) {
        // Toggle sidebar visibility
        sidebar.classList.toggle('-translate-x-full');
        
        // Toggle backdrop visibility
        backdrop.classList.toggle('hidden');
        
        console.log('Menu toggled - Sidebar hidden:', sidebar.classList.contains('-translate-x-full'));
    } else {
        console.error('Sidebar or backdrop element not found');
    }
}

/**
 * Close menu when clicking on navigation links
 */
document.addEventListener('DOMContentLoaded', function() {
    const sidebarLinks = document.querySelectorAll('#sidebar a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 1024) {
                toggleMobileMenu();
            }
        });
    });
});

/**
 * Switch tabs functionality (for pages with multiple tabs)
 */
function switchTab(tabName) {
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
