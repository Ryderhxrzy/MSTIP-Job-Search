// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeHeader();
    setActiveLink();
});

function initializeHeader() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    // Add click event to mobile menu toggle
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', toggleMobileMenu);
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', closeMobileMenuOnClickOutside);
    
    // Header scroll effect (optional)
    window.addEventListener('scroll', handleHeaderScroll);
    
    // Add click event to nav links to update active state
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Update active state after a short delay to allow page navigation
            setTimeout(setActiveLink, 100);
        });
    });
}

function setActiveLink() {
    // Get current page URL
    const currentUrl = window.location.href;
    const currentPath = window.location.pathname;
    const currentPage = currentPath.split('/').pop() || 'index.php';
    
    console.log('Current URL:', currentUrl);
    console.log('Current Path:', currentPath);
    console.log('Current Page:', currentPage);
    
    // Get all navigation links
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    
    // Reset all links
    navLinks.forEach(link => {
        link.classList.remove('active');
    });
    
    // Find and activate the current page link
    navLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        console.log('Checking link:', linkHref);
        
        // Extract filename from href
        const linkPage = linkHref.split('/').pop() || 'index.php';
        
        // Check different matching conditions
        if (currentUrl.includes(linkHref) || 
            currentPath === linkHref ||
            currentPage === linkPage ||
            (currentPath === '/' && linkHref === 'index.php') ||
            (currentPath === '' && linkHref === 'index.php')) {
            
            link.classList.add('active');
            console.log('Active link set:', linkHref);
        }
    });
    
    // Special case for home page
    if (currentPath === '/' || currentPath === '' || currentPage === 'index.php') {
        const homeLinks = document.querySelectorAll('[href="index.php"], [href="/"]');
        homeLinks.forEach(link => {
            link.classList.add('active');
        });
        console.log('Home page active');
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

// Profile dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');
    
    if (profileToggle && profileDropdown) {
        // Toggle dropdown
        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
            profileToggle.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            profileDropdown.classList.remove('active');
            profileToggle.classList.remove('active');
        });
        
        // Prevent dropdown from closing when clicking inside
        profileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenuToggle.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
        
        // Close mobile menu when clicking on a link
        const mobileLinks = document.querySelectorAll('.mobile-nav-link');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
            });
        });
    }
});

// Update active link when navigating
window.addEventListener('popstate', setActiveLink);

// Also update when page loads completely
window.addEventListener('load', setActiveLink);
