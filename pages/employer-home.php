<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/homepage.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/employer.css">
    <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once('../includes/employer-header.php') ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <svg class="hero-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
  <g fill="rgba(255,255,255,0.06)">
    <path d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
    <path d="M0,240 C480,360 960,120 1440,240 L1440,400 L0,400 Z"></path>
    <path d="M0,320 C480,440 960,200 1440,320 L1440,400 L0,400 Z"></path>
  </g>
</svg>

            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Hire the Right Talent Faster with MSTIP</h1>
                    <p class="hero-subtitle">Post jobs, reach thousands of qualified candidates, and build your winning team in just a few steps.</p>
                    <a href="employer-register.php" class="btn-primary">Get Started</a>
                </div>
            </div>
        </section>

        <!-- 3 Steps Section -->
        <section class="steps">
            <h2 class="section-title">Start Hiring in 3 Simple Steps</h2>
            <div class="container">
                <div class="steps-grid">
                    <div class="step">
                        <i class="fas fa-user-plus step-icon"></i>
                        <h3>Register Online</h3>
                        <p>Create your free employer account in minutes and set up your company profile.</p>
                    </div>
                    <div class="step">
                        <i class="fas fa-briefcase step-icon"></i>
                        <h3>Post a Job</h3>
                        <p>Publish your job openings and instantly reach qualified job seekers.</p>
                    </div>
                    <div class="step">
                        <i class="fas fa-filter step-icon"></i>
                        <h3>Sort Applicants</h3>
                        <p>Review, filter, and shortlist the best candidates all in one place.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose MSTIP -->
        <section class="why-choose">
            <h2 class="section-title">Why Employers Choose MSTIP</h2>
            <div class="container">
                <div class="why-grid">
                    <div class="why-item">
                        <i class="fas fa-bullseye"></i>
                        <h3>Targeted Reach</h3>
                        <p>Connect directly with job seekers actively looking for opportunities.</p>
                    </div>
                    <div class="why-item">
                        <i class="fas fa-bolt"></i>
                        <h3>Quick & Easy</h3>
                        <p>Post jobs in minutes with our simple, user-friendly platform.</p>
                    </div>
                    <div class="why-item">
                        <i class="fas fa-headset"></i>
                        <h3>Dedicated Support</h3>
                        <p>Our team is here to guide you throughout the hiring process.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta">
            <h2>Ready to Hire Your Next Star Employee?</h2>
            <a href="employer-register.php" class="btn-primary">Start Hiring Now</a>
        </section>
    </main>

    <?php include_once('../includes/employer-footer.php') ?>
</body>
</html>