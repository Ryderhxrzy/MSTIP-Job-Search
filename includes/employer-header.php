<?php 
    include_once('../../includes/db_connect.php');
    $isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && $_SESSION['user_type'] === 'Employer';
    $userEmail = $isLoggedIn ? $_SESSION['email'] : '';
    $userCode = $isLoggedIn ? $_SESSION['user_code'] : '';

    // Get notification count for the employer
    function getNotificationCount($userCode) {
        global $conn;
        
        // Get company_id
        $company_stmt = $conn->prepare("SELECT company_id FROM companies WHERE user_id = ?");
        $company_stmt->bind_param("s", $userCode);
        $company_stmt->execute();
        $company_result = $company_stmt->get_result();
        
        if ($company_result->num_rows > 0) {
            $company = $company_result->fetch_assoc();
            $company_id = $company['company_id'];
            
            // Count all applications (notifications)
            $notif_stmt = $conn->prepare("SELECT COUNT(*) as count FROM applications a 
                                         JOIN job_listings j ON a.job_id = j.job_id 
                                         WHERE j.company_id = ?");
            $notif_stmt->bind_param("i", $company_id);
            $notif_stmt->execute();
            $notif_result = $notif_stmt->get_result();
            $count = $notif_result->fetch_assoc()['count'];
            
            return $count;
        }
        
        return 0;
    }

    // Get recent notifications
    function getRecentNotifications($userCode) {
        global $conn;
        
        // Get company_id
        $company_stmt = $conn->prepare("SELECT company_id FROM companies WHERE user_id = ?");
        $company_stmt->bind_param("s", $userCode);
        $company_stmt->execute();
        $company_result = $company_stmt->get_result();
        
        if ($company_result->num_rows > 0) {
            $company = $company_result->fetch_assoc();
            $company_id = $company['company_id'];
            
            // Get recent applications
            $recent_stmt = $conn->prepare("SELECT a.application_id, a.application_date, a.status, j.job_title as job_title_full,
                                         gi.first_name, gi.last_name
                                         FROM applications a 
                                         JOIN job_listings j ON a.job_id = j.job_id 
                                         JOIN graduate_information gi ON a.user_id = gi.user_id
                                         WHERE j.company_id = ?
                                         ORDER BY a.application_date DESC 
                                         LIMIT 5");
            $recent_stmt->bind_param("i", $company_id);
            $recent_stmt->execute();
            return $recent_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }

    // Get notification data if logged in
    $notificationCount = $isLoggedIn ? getNotificationCount($userCode) : 0;
    $recentNotifications = $isLoggedIn ? getRecentNotifications($userCode) : [];

    // Function to get profile picture URL for Employer
    function getProfilePicture($userCode) {
        global $conn;
        
        $defaultProfilePic = "../../assets/images/default-profile.jpg";
        
        // Query the companies table for the profile picture
        $stmt = $conn->prepare("SELECT profile_picture FROM companies WHERE user_id = ?");
        $stmt->bind_param("s", $userCode);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (!empty($row['profile_picture'])) {
                $profilePicPath = "../../assets/images/" . $row['profile_picture'];
                // Check if file actually exists
                if (file_exists($profilePicPath)) {
                    return $profilePicPath;
                }
            }
        }
        
        return $defaultProfilePic;
    }
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
<link rel="stylesheet" href="../../assets/css/global.css">
<link rel="stylesheet" href="../../assets/css/header.css">
<link rel="stylesheet" href="../../assets/css/notifications.css">

<header class="header">
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="/employer">
                    <img src="../../assets/images/mstip_logo.png" alt="MSTIP Logo" class="logo-img">
                    <div class="logo-text">
                        <span class="logo-title">MSTIP</span>
                        <span class="logo-subtitle">Seek Employee</span>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="employer-home.php" class="nav-link" data-page="post-job">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="how-it-works.php" class="nav-link" data-page="how-it-works">
                        How It Works
                    </a>
                </li>
                <li class="nav-item">
                    <a href="employer-advice.php" class="nav-link" data-page="pricing">
                        Employer Advice
                    </a>
                </li>
                <li class="nav-item">
                    <a href="about-us.php" class="nav-link" data-page="about">
                        About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a href="contact.php" class="nav-link" data-page="contact">
                        Contact
                    </a>
                </li>
            </ul>

            <!-- Right Side Actions -->
            <div class="nav-actions">
                <!-- User Actions -->
                <div class="user-actions">
                    <?php if ($isLoggedIn): ?>
                        <!-- User Profile Dropdown and Post a Job button -->
                        <div class="logged-in-actions">
                            <!-- Profile Dropdown First -->
                            <div class="user-profile-dropdown">
                                <button class="profile-toggle" id="profileToggle">
                                    <img src="<?php echo getProfilePicture($userCode); ?>" alt="Profile Picture" class="profile-pic">
                                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                                </button>
                                <div class="profile-dropdown-menu" id="profileDropdown">
                                    <a href="company-profile.php" class="dropdown-item">
                                        <i class="fas fa-user"></i>
                                        Company Profile
                                    </a>
                                    <a href="change-password.php" class="dropdown-item">
                                        <i class="fas fa-key"></i>
                                        Change Password
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="../../logout.php" class="dropdown-item logout-item">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Separator -->
                            <span class="action-separator">/</span>
                            
                            <!-- Post a Job Button -->
                            <a href="post-a-job.php" class="employer-link">Post a Job</a>
                            
                            <!-- Notification Icon -->
                            <div class="notification-dropdown">
                                <button class="notification-toggle" id="notificationToggle">
                                    <i class="fas fa-bell"></i>
                                    <?php if (!empty($notificationCount)): ?>
                                        <span class="notification-badge"><?php echo $notificationCount; ?></span>
                                    <?php endif; ?>
                                </button>
                                <div class="notification-dropdown-menu" id="notificationDropdown">
                                    <div class="notification-header">
                                        <h6>Notifications</h6>
                                        <a href="applicants.php" class="view-all-link">View All</a>
                                    </div>
                                    <div class="notification-list">
                                        <?php if (empty($recentNotifications)): ?>
                                            <div class="no-notifications">
                                                <i class="fas fa-check-circle"></i>
                                                <p>No new notifications</p>
                                            </div>
                                        <?php else: ?>
                                            <?php foreach ($recentNotifications as $notif): ?>
                                                <a href="applicants.php?filter_application=<?php echo $notif['application_id']; ?>" class="notification-item">
                                                    <div class="notification-content">
                                                        <div class="notification-title">
                                                            Application: <?php echo htmlspecialchars($notif['first_name'] . ' ' . $notif['last_name']); ?>
                                                            <span class="notification-status status-<?php echo strtolower($notif['status']); ?>">
                                                                <?php echo htmlspecialchars($notif['status']); ?>
                                                            </span>
                                                        </div>
                                                        <div class="notification-description">
                                                            Applied for: <?php echo htmlspecialchars($notif['job_title_full']); ?>
                                                        </div>
                                                        <div class="notification-time">
                                                            <?php 
                                                            $date = new DateTime($notif['application_date']);
                                                            echo $date->format('M d, Y - h:i A');
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="notification-action">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Show login button and Post a Job when not logged in -->
                        <div class="logged-out-actions">
                            <a href="../../employer-login.php" class="btn btn-outline">Log In</a>
                            <span class="action-separator">/</span>
                            <a href="../../employer-register.php" class="employer-link">Post a Job</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <div class="mobile-menu-content">
                <a href="employer-home.php" class="mobile-nav-link" data-page="post-job">
                    Home
                </a>
                 <a href="how-it-works.php" class="mobile-nav-link" data-page="how-it-works">
                    How It Works
                </a>
                <a href="employer-advice.php" class="mobile-nav-link" data-page="pricing">
                    Employer Advice
                </a>
                <a href="about-us.php" class="mobile-nav-link" data-page="about">
                    About Us
                </a>
                <a href="contact.php" class="mobile-nav-link" data-page="contact">
                    Contact
                </a>
                
                <?php if ($isLoggedIn): ?>
                    <!-- Mobile User Profile Options -->
                    <div class="mobile-user-profile">
                        <div class="mobile-profile-header">
                            <img src="<?php echo getProfilePicture($userCode); ?>" alt="Profile Picture" class="mobile-profile-pic">
                            <span class="mobile-profile-email"><?php echo htmlspecialchars($userEmail); ?></span>
                        </div>
                        
                        <!-- Profile Links -->
                        <a href="company-profile.php" class="mobile-nav-link">
                            <i class="fas fa-user"></i>
                            Company Profile
                        </a>
                        <a href="change-password.php" class="mobile-nav-link">
                            <i class="fas fa-key"></i>
                            Change Password
                        </a>
                        <a href="../../logout.php" class="mobile-nav-link logout-mobile">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>

                        <a href="post-a-job.php" class="employer-link">Post a Job</a>
                        
                        <!-- Mobile Notification Section -->
                        <div class="mobile-notification-section">
                            <div class="mobile-notification-header">
                                <h6>Notifications</h6>
                                <?php if (!empty($notificationCount)): ?>
                                    <span class="mobile-notification-badge"><?php echo $notificationCount; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="mobile-notification-list">
                                <?php if (empty($recentNotifications)): ?>
                                    <div class="mobile-no-notifications">
                                        <i class="fas fa-check-circle"></i>
                                        <p>No new notifications</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($recentNotifications as $notif): ?>
                                        <a href="applicants.php?filter_application=<?php echo $notif['application_id']; ?>" class="mobile-notification-item">
                                            <div class="mobile-notification-content">
                                                <div class="mobile-notification-title">
                                                    Application: <?php echo htmlspecialchars($notif['first_name'] . ' ' . $notif['last_name']); ?>
                                                    <span class="mobile-notification-status status-<?php echo strtolower($notif['status']); ?>">
                                                        <?php echo htmlspecialchars($notif['status']); ?>
                                                    </span>
                                                </div>
                                                <div class="mobile-notification-description">
                                                    Applied for: <?php echo htmlspecialchars($notif['job_title_full']); ?>
                                                </div>
                                                <div class="mobile-notification-time">
                                                    <?php 
                                                    $date = new DateTime($notif['application_date']);
                                                    echo $date->format('M d, Y - h:i A');
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="mobile-notification-action">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($recentNotifications)): ?>
                                <div class="mobile-notification-footer">
                                    <a href="applicants.php" class="mobile-view-all-link">View All Applications</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Mobile login and Post a Job when not logged in -->
                    <div class="mobile-user-actions">
                        <a href="../../employer-login.php" class="btn btn-outline btn-block">Log In</a>
                        <a href="../../employer-register.php" class="employer-link btn-block">Post a Job</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<script src="../../assets/js/script.js"></script>
<script>
// Notification dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const notificationToggle = document.getElementById('notificationToggle');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');
    
    // Toggle notification dropdown
    if (notificationToggle && notificationDropdown) {
        notificationToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Close profile dropdown if open
            if (profileDropdown && profileDropdown.parentElement.classList.contains('active')) {
                profileDropdown.parentElement.classList.remove('active');
            }
            
            // Toggle notification dropdown
            notificationDropdown.parentElement.classList.toggle('active');
        });
    }
    
    // Toggle profile dropdown
    if (profileToggle && profileDropdown) {
        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Close notification dropdown if open
            if (notificationDropdown && notificationDropdown.parentElement.classList.contains('active')) {
                notificationDropdown.parentElement.classList.remove('active');
            }
            
            // Toggle profile dropdown
            profileDropdown.parentElement.classList.toggle('active');
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (notificationDropdown && !notificationDropdown.parentElement.contains(e.target)) {
            notificationDropdown.parentElement.classList.remove('active');
        }
        
        if (profileDropdown && !profileDropdown.parentElement.contains(e.target)) {
            profileDropdown.parentElement.classList.remove('active');
        }
    });
    
    // Prevent clicks inside dropdowns from closing them
    if (notificationDropdown) {
        notificationDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    if (profileDropdown) {
        profileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Mark notifications as read when viewed (optional enhancement)
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(function(item) {
        item.addEventListener('click', function() {
            // You could add AJAX call here to mark notification as read
            // For now, just close the dropdown
            if (notificationDropdown) {
                notificationDropdown.parentElement.classList.remove('active');
            }
        });
    });
});
</script>