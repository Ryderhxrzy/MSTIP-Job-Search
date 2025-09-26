<!-- Header Component - header.html -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styles.css">
<link rel="stylesheet" href="../assets/css/global.css">
<link rel="stylesheet" href="../assets/css/header.css">

<header class="header">
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <a href="/">
                    <i class="fas fa-briefcase"></i>
                    <span>JobFinder</span>
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
                        Companies
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/post-job" class="nav-link" data-page="post-job">
                        Post a Job
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/about" class="nav-link" data-page="about">
                        About Us
                    </a>
                </li>
            </ul>

            <!-- Right Side Actions -->
            <div class="nav-actions">
                <!-- User Actions -->
                <div class="user-actions">
                    <a href="/login" class="btn btn-outline">Log In</a>
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
                    Companies
                </a>
                <a href="/post-job" class="mobile-nav-link" data-page="post-job">
                    Post a Job
                </a>
                <a href="/about" class="mobile-nav-link" data-page="about">
                    About Us
                </a>
                <div class="mobile-user-actions">
                    <a href="/login" class="btn btn-outline btn-block">Log In</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<script src="../assets/js/header.js"></script>