<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Welcome Back</title>
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include_once('includes/header-log-reg.php') ?>

    <!-- ================= Enhanced Login Page ================= -->
    <div class="login-container">
        <div class="login-box">
            <h2 class="login-title">Welcome Back</h2>
            <p class="login-subtitle">Sign in to find your next opportunity</p>

            <!-- Enhanced Login Form -->
            <form class="login-form">
                <div class="form-group">
                    <input type="email" placeholder="Enter your email address" required>
                </div>
                <div class="form-group password-group">
                    <input type="password" id="password" placeholder="Enter your password" required>
                    <i class="fa fa-eye toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
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
                Don't have an account? <a href="#" class="register-links">Create Account</a>
            </p>

            <p class="employer-link">
                <a href="#">
                    <i class="fas fa-building" style="margin-right: 0.5rem;"></i>
                    Are you an Employer?
                </a>
            </p>
        </div>
    </div>

    <?php include_once('includes/footer.php') ?>
    <script src="assets/js/log-reg-script.js"></script>
</body>
</html>