<?php 
    include_once('../../includes/db_connect.php');

    // Check if user is logged in as Employer, redirect to login if not
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'Employer') {
        header("Location: ../../employer-login.php");
        exit();
    }

    $userEmail = $_SESSION['email'];
    $userCode = $_SESSION['user_code'];

    // Function to get profile picture URL
    function getProfilePicture($userCode) {
        // Check if profile picture exists in database or file system
        $profilePicPath = "../../assets/images/profiles/" . $userCode . ".jpg";
        $defaultProfilePic = "../../assets/images/default-profile.jpg";
        
        if (file_exists($profilePicPath)) {
            return $profilePicPath;
        } else {
            return $defaultProfilePic;
        }
    }
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/styles.css">
<link rel="stylesheet" href="../../assets/css/global.css">
<link rel="stylesheet" href="../../assets/css/header.css">

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
                    <a href="dashboard.php" class="nav-link" data-page="dashboard">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="post-a-job.php" class="nav-link" data-page="post-a-job">
                        Post a Job
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage-jobs.php" class="nav-link" data-page="manage-jobs">
                        Manage Jobs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="applicants.php" class="nav-link" data-page="applicants">
                        Applicants
                    </a>
                </li>
            </ul>

            <!-- Right Side Actions -->
            <div class="nav-actions">
                <!-- User Actions -->
                <div class="user-actions">
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
                        <a href="employer-home.php" class="employer-link">Home</a>
                    </div>
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
                <a href="dashboard.php" class="mobile-nav-link" data-page="dashboard">
                    Dashboard
                </a>
                 <a href="post-a-job.php" class="mobile-nav-link" data-page="post-a-job">
                    Post a Job
                </a>
                <a href="manage-jobs.php" class="mobile-nav-link" data-page="manage-jobs">
                    Manage Jobs
                </a>
                <a href="applicants.php" class="mobile-nav-link" data-page="applicants">
                    Applicants
                </a>
                
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
                    
                    <a href="../../logout.php" class="mobile-nav-link logout-mobile">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>

                    <a href="employer-home.php" class="employer-link">Home</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<script src="../../assets/js/script.js"></script>