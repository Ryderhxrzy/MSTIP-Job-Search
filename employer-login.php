<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MSTIP Job Search</title>
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- ================= Enhanced Login Page ================= -->
    <div class="login-container">
        <div class="login-box">
        <div class="logo">
                <a href="index.php">
                    <img src="assets/images/mstip_logo.png" alt="MSTIP Logo" class="logo-img">
                    <div class="logo-text">
                        <span class="logo-title">MSTIP</span>
                        <span class="logo-subtitle">Seek Talent</span>
                    </div>
                </a>
            </div>
            <hr>
        <h2 class="login-title">Welcome Back</h2>
        <p class="login-subtitle">Sign in to manage your job postings</p>

        <!-- Enhanced Login Form -->
        <form class="login-form" id="loginForm" novalidate>
            <div class="form-group">
            <input type="email" id="email" placeholder="Enter your email address *" required>
            <small class="error-message" id="emailError"></small>
            </div>
            <div class="form-group">
            <div class="password-group">
                <input type="password" id="password" placeholder="Enter your password *" required>
                <i class="fa fa-eye toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
            </div>
            <small class="error-message" id="passwordError"></small>
            </div>
            <div class="form-options">
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            <a href="#" class="forgot-link">Forgot Password?</a>
            </div>
            <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>
            Sign In
            </button>
        </form>

        <p class="register-text">
            Don't have an account? <a href="employer-register.php" class="register-links">Create account</a>
        </p>
        <div class="divider"><span>or</span></div>
        <p class="employer-links">
            <a href="login.php">
            <i class="fas fa-building" style="margin-right: 0.5rem;"></i>
            Are you an Job Searcher?
            </a>
        </p>
        </div>
    </div>
    <!-- ================= Why Choose MSTIP for Employers ================= -->
<section class="why-mstip">
    <div class="why-container">
        <h2 class="why-title">Start Hiring in 3 Easy Steps</h2>
        <p class="why-subtitle">MSTIP makes it simple for employers to find the right talent quickly.</p>
        <div class="why-cards">
            <div class="why-card">
                <i class="fas fa-user-plus fa-2x"></i>
                <h3>1. Register Online</h3>
                <p>Create your free employer account in just minutes and get access to hiring tools.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-briefcase fa-2x"></i>
                <h3>2. Post a Job</h3>
                <p>Publish your job listings and reach thousands of qualified job seekers instantly.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-filter fa-2x"></i>
                <h3>3. Sort Applicants</h3>
                <p>Review applications, filter candidates, and connect with the right talent fast.</p>
            </div>
        </div>
    </div>
</section>



    <script src="assets/js/log-employer-script.js"></script>
</body>
</html>