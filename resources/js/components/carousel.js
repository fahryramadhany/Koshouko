/* ===================================
   KOSHOUKO CAROUSEL COMPONENT
   JavaScript for carousel navigation and functionality
   =================================== */

/**
 * Initialize recommendations carousel with custom navigation
 */
function initRecommendationsCarousel() {
    const carousel = document.getElementById('recommendations-carousel');
    if (!carousel) return;

    const wrapper = carousel.querySelector('.recommendations-carousel-wrapper');
    const items = carousel.querySelectorAll('.recommendations-carousel-item');
    const prevBtn = carousel.querySelector('.recommendations-carousel-nav.prev');
    const nextBtn = carousel.querySelector('.recommendations-carousel-nav.next');
    const dots = carousel.querySelectorAll('.recommendations-carousel-dot');

    if (!wrapper || items.length === 0) return;

    let currentIndex = 0;
    let autoScrollTimer = null;

    function updateCarousel() {
        wrapper.style.transform = `translateX(-${currentIndex * 100}%)`;

        dots.forEach((dot, idx) => {
            dot.classList.toggle('active', idx === currentIndex);
        });

        if (prevBtn) prevBtn.disabled = currentIndex === 0;
        if (nextBtn) nextBtn.disabled = currentIndex >= items.length - 1;
    }

    function goToNext() {
        if (currentIndex < items.length - 1) {
            currentIndex++;
            updateCarousel();
            resetAutoScroll();
        } else {
            currentIndex = 0;
            updateCarousel();
        }
    }

    function goToPrev() {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
            resetAutoScroll();
        }
    }

    function goToItem(index) {
        currentIndex = Math.max(0, Math.min(index, items.length - 1));
        updateCarousel();
        resetAutoScroll();
    }

    function startAutoScroll() {
        autoScrollTimer = setInterval(goToNext, 5000);
    }

    function resetAutoScroll() {
        clearInterval(autoScrollTimer);
        startAutoScroll();
    }

    function pauseAutoScroll() {
        clearInterval(autoScrollTimer);
    }

    function resumeAutoScroll() {
        startAutoScroll();
    }

    // Event listeners
    if (prevBtn) prevBtn.addEventListener('click', goToPrev);
    if (nextBtn) nextBtn.addEventListener('click', goToNext);

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => goToItem(idx));
    });

    // Pause on hover
    wrapper.addEventListener('mouseenter', pauseAutoScroll);
    wrapper.addEventListener('mouseleave', resumeAutoScroll);

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (carousel.contains(document.activeElement)) {
            if (e.key === 'ArrowLeft') goToPrev();
            if (e.key === 'ArrowRight') goToNext();
        }
    });

    updateCarousel();
    startAutoScroll();
}

/**
 * Export carousel initialization
 */
export function initCarousels() {
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize recommendations carousel
        initRecommendationsCarousel();
        console.log('Carousels initialized');
    });
}

/**
 * Initialize on module load
 */
initCarousels();
