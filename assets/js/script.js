// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeHeader();
    setActiveLink();
});

function initializeHeader() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    // Add click event to mobile menu toggle
    mobileMenuToggle.addEventListener('click', toggleMobileMenu);
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', closeMobileMenuOnClickOutside);
    
    // Header scroll effect (optional)
    window.addEventListener('scroll', handleHeaderScroll);
}

function setActiveLink() {
    // Get current page path
    const currentPath = window.location.pathname;
    
    // Remove trailing slash if present
    const cleanPath = currentPath.endsWith('/') ? currentPath.slice(0, -1) : currentPath;
    
    // Get all navigation links
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    
    // Reset all links
    navLinks.forEach(link => {
        link.classList.remove('active');
    });
    
    // Find and activate the current page link
    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');
        const cleanLinkPath = linkPath.endsWith('/') ? linkPath.slice(0, -1) : linkPath;
        
        // Check if this link matches the current page
        if (cleanPath === cleanLinkPath || 
            (cleanPath === '' && cleanLinkPath === '/') ||
            (cleanPath !== '/' && cleanPath.startsWith(cleanLinkPath) && cleanLinkPath !== '/')) {
            link.classList.add('active');
        }
    });
    
    // Special case for home page
    if (cleanPath === '' || cleanPath === '/') {
        const homeLink = document.querySelector('.nav-logo a');
        if (homeLink) {
            // You can add visual indication for home if needed
        }
    }
}

function toggleMobileMenu() {
    const mobileMenu = document.querySelector('.mobile-menu');
    const toggleButton = document.querySelector('.mobile-menu-toggle');
    
    mobileMenu.classList.toggle('active');
    toggleButton.classList.toggle('active');
}

function closeMobileMenuOnClickOutside(event) {
    const mobileMenu = document.querySelector('.mobile-menu');
    const toggleButton = document.querySelector('.mobile-menu-toggle');
    const navbar = document.querySelector('.navbar');
    
    if (!navbar.contains(event.target) && mobileMenu.classList.contains('active')) {
        mobileMenu.classList.remove('active');
        toggleButton.classList.remove('active');
    }
}

function handleHeaderScroll() {
    const header = document.querySelector('.header');
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > 10) {
        header.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
    } else {
        header.style.boxShadow = '0 1px 3px 0 rgba(0, 0, 0, 0.1)';
    }
}

// Update active link when navigating (for single page applications)
window.addEventListener('popstate', setActiveLink);