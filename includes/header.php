<!-- Header Component - header.html -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styles.css">
<link rel="stylesheet" href="../assets/css/global.css">
<link rel="stylesheet" href="../assets/css/header.css">

<header class="header">
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="/">
                    <img src="assets/images/mstip_logo.png" alt="MSTIP Logo" class="logo-img">
                    <div class="logo-text">
                        <span class="logo-title">MSTIP</span>
                        <span class="logo-subtitle">Job Search</span>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link" data-page="jobs">
                        Find Jobs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/companies" class="nav-link" data-page="companies">
                        Employer
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/post-job" class="nav-link" data-page="post-job">
                        Searcher Advice
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/about" class="nav-link" data-page="about">
                        About Us
                    </a>
                </li>
                 <li class="nav-item">
                    <a href="/about" class="nav-link" data-page="about">
                        Contact
                    </a>
                </li>
            </ul>

            <!-- Right Side Actions -->
            <div class="nav-actions">
                <!-- User Actions -->
                <div class="user-actions">
                    <a href="login.php" class="btn btn-outline">Log In</a>
                    <span> &nbsp;/&nbsp; </span>
                    <a href="pages/employer-home.php" class="nav-links employer-link">Employer Page</a>
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
                <a href="index.php" class="mobile-nav-link" data-page="jobs">
                    Find Jobs
                </a>
                <a href="/companies" class="mobile-nav-link" data-page="companies">
                    Employer
                </a>
                <a href="/post-job" class="mobile-nav-link" data-page="post-job">
                    Searcher Advice
                </a>
                <a href="/about" class="mobile-nav-link" data-page="about">
                    About Us
                </a>
                <a href="/about" class="mobile-nav-link" data-page="about">
                    Contact
                </a>
                <div class="mobile-user-actions">
                    <a href="/login" class="btn btn-outline btn-block">Log In</a>
                    <a href="/employer" class="nav-links employer-link">Employer Page</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<script src="../assets/js/script.js"></script>