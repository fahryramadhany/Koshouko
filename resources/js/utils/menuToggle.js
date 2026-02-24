/* ===================================
   KOSHOUKO MENU TOGGLE UTILITY
   Mobile menu sidebar toggle functionality
   =================================== */

/**
 * Toggle mobile menu sidebar
 */
export function toggleMobileMenu() {
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
 * Automatically attaches event listeners to sidebar links
 */
export function initMenuLinkClosers() {
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
}

/**
 * Initialize menu toggle functionality
 * Call this once on page load
 */
export function initMenuToggle() {
    initMenuLinkClosers();
}
