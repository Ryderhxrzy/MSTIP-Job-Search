<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/homepage.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/employer.css">
    <link rel="shortcut icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="../../assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once('../../includes/employer-header.php') ?>

    <main>
        <!-- Hero Section -->
        <section class="advice-hero">
            <div class="container">
                <h1 class="section-titles">About Us</h1>
                <p class="section-subtitle">Learn more about our mission, values, and the team that drives our success.</p>
            </div>
            <!-- Decorative pattern behind hero -->
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>

        <!-- Company Story -->
        <section class="company-story">
            <div class="container">
                <h2 class="section-titles">Our Story</h2>
                <p class="section-subtitle">Learn how MSTIP started and why we are committed to helping employers find the best talent.</p>
                <div class="story-content">
                    <div class="story-text">
                        <p>MSTIP Job Search / Employee Seek was founded to streamline the hiring process. Our mission is to connect businesses with qualified professionals quickly and efficiently, saving time and resources.</p>
                        <p>We believe that every company deserves the right team, and every talent deserves the right opportunity. Through our platform, we make this connection seamless and transparent.</p>
                    </div>
                    <div class="story-image">
                        <img src="../../assets/images/background1.jpeg" alt="Company Story">
                    </div>
                </div>
            </div>
        </section>

        <div class="separator">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100">
            <path fill="var(--separator-color)" 
            d="M0,64L48,58.7C96,53,192,43,288,37.3C384,32,480,32,576,42.7C672,53,768,75,864,80C960,85,1056,75,1152,69.3C1248,64,1344,64,1392,64L1440,64L1440,0L0,0Z">
            </path>
        </svg>
        </div>

        <!-- Mission & Vision -->
        <section class="mission-vision">
            <div class="container">
                <h2 class="section-title">Mission & Vision</h2>
                <div class="mv-grid">
                    <div class="mv-item">
                        <h3>Our Mission</h3>
                        <p>Engaging with alumni to provide mentorship opportunities and facilitate networking for current graduates.</p>
                    </div>
                    <div class="mv-item">
                        <h3>Our Vision</h3>
                        <p>Our team expertise that not only facilitates job search but graduates with the necessary tools and to succeed in their careers.</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="separator">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100">
            <path fill="var(--separator-color)" 
            d="M0,64L48,58.7C96,53,192,43,288,37.3C384,32,480,32,576,42.7C672,53,768,75,864,80C960,85,1056,75,1152,69.3C1248,64,1344,64,1392,64L1440,64L1440,0L0,0Z">
            </path>
        </svg>
        </div>

        <!-- Team Section -->
        <section class="team">
            <div class="container">
                <h2 class="section-title">Meet Our Team</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <img src="../../assets/images/MAANDREA.png" alt="Team Member">
                        <h4>Ma. Andrea</h4>
                        <p>Programmer (Leader)</p>
                    </div>
                    <div class="team-member">
                        <img src="../../assets/images/VINCEP.png" alt="Team Member">
                        <h4>Vince Peter</h4>
                        <p>Researcher</p>
                    </div>
                    <div class="team-member">
                        <img src="../../assets/images/FAITHANN.png" alt="Team Member">
                        <h4>Faith Ann</h4>
                        <p>Design</p>
                    </div>
                    <div class="team-member">
                        <img src="../../assets/images/ADRIAN1.png" alt="Team Member">
                        <h4>Adrian</h4>
                        <p>Support</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/profile.js"></script>
    <?php include_once('../../includes/employer-footer.php') ?>
</body>
</html>
