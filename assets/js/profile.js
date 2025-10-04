// Profile Dropdown Functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeProfileDropdown();
});

function initializeProfileDropdown() {
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');
    
    console.log('Profile dropdown initialization:');
    console.log('Profile toggle element:', profileToggle);
    console.log('Profile dropdown element:', profileDropdown);
    
    if (profileToggle && profileDropdown) {
        // Toggle dropdown when profile is clicked
        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event from bubbling up
            e.preventDefault(); // Prevent any default behavior
            
            console.log('Profile toggle clicked');
            
            // Toggle active classes
            profileDropdown.classList.toggle('active');
            profileToggle.classList.toggle('active');
            
            console.log('Dropdown active:', profileDropdown.classList.contains('active'));
            console.log('Toggle active:', profileToggle.classList.contains('active'));
        });
        
        // Close dropdown when clicking anywhere else on the page
        document.addEventListener('click', function(e) {
            // Check if click is outside the dropdown
            if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('active');
                profileToggle.classList.remove('active');
                console.log('Dropdown closed - clicked outside');
            }
        });
        
        // Close dropdown when clicking on dropdown items (optional)
        const dropdownItems = profileDropdown.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                // Optional: close dropdown when item is clicked
                // profileDropdown.classList.remove('active');
                // profileToggle.classList.remove('active');
                console.log('Dropdown item clicked:', this.textContent.trim());
            });
        });
        
        // Close dropdown when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && profileDropdown.classList.contains('active')) {
                profileDropdown.classList.remove('active');
                profileToggle.classList.remove('active');
                console.log('Dropdown closed - Escape key pressed');
            }
        });
        
        console.log('Profile dropdown event listeners attached successfully');
        
    } else {
        console.error('Profile dropdown elements not found!');
        console.log('Available #profileToggle:', document.getElementById('profileToggle'));
        console.log('Available #profileDropdown:', document.getElementById('profileDropdown'));
    }
}

// Optional: Function to manually close dropdown
function closeProfileDropdown() {
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');
    
    if (profileToggle && profileDropdown) {
        profileDropdown.classList.remove('active');
        profileToggle.classList.remove('active');
    }
}

// Optional: Function to manually open dropdown
function openProfileDropdown() {
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');
    
    if (profileToggle && profileDropdown) {
        profileDropdown.classList.add('active');
        profileToggle.classList.add('active');
    }
}