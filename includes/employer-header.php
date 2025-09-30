<!-- Employer Header Component (Not Logged In) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styles.css">
<link rel="stylesheet" href="../assets/css/global.css">
<link rel="stylesheet" href="../assets/css/header.css">

<header class="header">
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="/employer">
                    <img src="../assets/images/mstip_logo.png" alt="MSTIP Logo" class="logo-img">
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
                    <a href="../employer-login.php" class="btn btn-outline">Log In</a>
                    <span> &nbsp;/&nbsp; </span>
                    <a href="register.php" class="nav-links employer-link">Post a Job</a>
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
                <div class="mobile-user-actions">
                    <a href="login.php" class="btn btn-outline btn-block">Log In</a>
                    <a href="register.php" class="nav-links employer-link">Post a Job</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<script src="../assets/js/script.js"></script>
