<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Searcher Advice - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/employer.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once('includes/header.php') ?>

    <main>
        <section class="main-hero">
            <div class="container">
                <h1 class="section-titles">Searcher Advice</h1>
                <p class="section-subtitle">Tips and strategies to help you find the right job faster and grow your career.</p>
            </div>
            <!-- Decorative pattern behind hero -->
             <svg class="pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                        <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
                    </svg>
        </section>

        <!-- How It Works -->
        <section class="how-it-works">
            <div class="container">
                <h2 class="section-title">How To Search Jobs Effectively</h2>
                <p class="section-subtitle">Follow these simple steps to maximize your chances of getting hired.</p>

                <div class="steps-list">
                    <div class="step-item">
                        <div class="step-content">
                            <div class="step-number">1</div>
                            <h3>Create Your Profile</h3>
                            <p>Complete your profile with accurate information and your updated resume to attract potential employers.</p>
                        </div>
                        <div class="step-image">
                            <img src="assets/images/searcher_register.png" alt="Create Profile">
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-content">
                            <div class="step-number">2</div>
                            <h3>Search & Filter</h3>
                            <p>Use filters to find jobs that match your skills, location, and desired salary range.</p>
                        </div>
                        <div class="step-image">
                            <img src="assets/images/background1.jpeg" alt="Search Jobs">
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-content">
                            <div class="step-number">3</div>
                            <h3>Apply Quickly</h3>
                            <p>Submit applications with a personalized message and ensure your resume is tailored for each job.</p>
                        </div>
                        <div class="step-image">
                            <img src="assets/images/background1.jpeg" alt="Apply Jobs">
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-content">
                            <div class="step-number">4</div>
                            <h3>Track Applications</h3>
                            <p>Monitor your applications, follow up when necessary, and stay organized during your job search.</p>
                        </div>
                        <div class="step-image">
                            <img src="assets/images/background1.jpeg" alt="Track Applications">
                        </div>
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

        <!-- Tips Section -->
        <section class="searcher-tips">
            <div class="container">
                <h2 class="section-title">Job Search Tips</h2>
                <p class="section-subtitle">Helpful tips to improve your chances of getting hired faster.</p>

                <div class="tips-grid">
                    <div class="tip">
                        <i class="fas fa-user-graduate tip-icon"></i>
                        <h3>Keep Your Resume Updated</h3>
                        <p>Always include your latest experience, skills, and certifications to stand out to employers.</p>
                    </div>
                    <div class="tip">
                        <i class="fas fa-search tip-icon"></i>
                        <h3>Use Job Alerts</h3>
                        <p>Set up alerts to be notified of new job postings matching your criteria immediately.</p>
                    </div>
                    <div class="tip">
                        <i class="fas fa-envelope tip-icon"></i>
                        <h3>Follow Up Applications</h3>
                        <p>Politely follow up with employers after submitting your application to show interest and initiative.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include_once('includes/footer.php') ?>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/profile.js"></script>
</body>
</html>
