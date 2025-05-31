/**
 * Author Pages JavaScript
 * Enhances the functionality of author-related pages
 */

// Function to initialize author page features
function initAuthorPageFeatures() {
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    } else {
        console.warn('Bootstrap not loaded yet, tooltips will not be initialized');
    }
    
    // Back to top button functionality
    var backToTopButton = document.createElement('div');
    backToTopButton.className = 'back-to-top shadow';
    backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopButton.setAttribute('title', 'Back to top');
    backToTopButton.setAttribute('data-bs-toggle', 'tooltip');
    backToTopButton.setAttribute('data-bs-placement', 'left');
    document.body.appendChild(backToTopButton);
    
    // Show/hide back to top button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.style.display = 'block';
        } else {
            backToTopButton.style.display = 'none';
        }
    });
    
    // Scroll to top when button is clicked
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Add lazy loading to images
    var images = document.querySelectorAll('img');
    images.forEach(function(img) {
        if (!img.hasAttribute('loading')) {
            img.setAttribute('loading', 'lazy');
        }
    });
    
    // Add animation to author cards
    var authorCards = document.querySelectorAll('.author-card');
    if (authorCards.length > 0) {
        // Use Intersection Observer to animate cards as they come into view
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        authorCards.forEach(function(card) {
            observer.observe(card);
        });
    }
    
    // Author search functionality enhancement
    var searchForm = document.querySelector('form[action="all-authors.php"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            var searchInput = this.querySelector('input[name="search"]');
            if (searchInput && searchInput.value.trim() === '') {
                e.preventDefault();
                window.location.href = 'all-authors.php';
            }
        });
    }
    
    // Add click event to category tags for filtering
    var categoryTags = document.querySelectorAll('.category-tag');
    categoryTags.forEach(function(tag) {
        tag.addEventListener('click', function() {
            var category = this.getAttribute('data-category');
            if (category) {
                window.location.href = 'category.php?c=' + encodeURIComponent(category);
            }
        });
    });
    
    // Initialize biography expandable sections
    var bioExpandButtons = document.querySelectorAll('.bio-expand-btn');
    bioExpandButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var bioContent = this.closest('.card').querySelector('.author-bio');
            if (bioContent) {
                if (bioContent.style.maxHeight) {
                    bioContent.style.maxHeight = null;
                    this.innerHTML = 'Read More <i class="fas fa-chevron-down"></i>';
                } else {
                    bioContent.style.maxHeight = bioContent.scrollHeight + 'px';
                    this.innerHTML = 'Read Less <i class="fas fa-chevron-up"></i>';
                }
            }
        });
    });
}

// Wait for DOM content to be loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if Bootstrap is already loaded
    if (typeof bootstrap !== 'undefined') {
        // Bootstrap is already loaded, initialize features
        initAuthorPageFeatures();
    } else {
        // Bootstrap is not loaded yet, wait for it
        console.log('Waiting for Bootstrap to load before initializing author features');
        
        // Create a function to check for Bootstrap and initialize when ready
        function checkBootstrap() {
            if (typeof bootstrap !== 'undefined') {
                // Bootstrap is now loaded, initialize features
                initAuthorPageFeatures();
                clearInterval(bootstrapCheckInterval);
            }
        }
        
        // Check every 100ms if Bootstrap has loaded
        var bootstrapCheckInterval = setInterval(checkBootstrap, 100);
        
        // Set a timeout to stop checking after 5 seconds to prevent infinite checking
        setTimeout(function() {
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap did not load within timeout period');
                clearInterval(bootstrapCheckInterval);
                // Initialize features without Bootstrap functionality
                initAuthorPageFeatures();
            }
        }, 5000);
    }
});