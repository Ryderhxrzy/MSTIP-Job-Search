<?php 
    include_once('includes/db_connect.php') 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Create Account</title>
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- ================= Register Page ================= -->
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/images/mstip_logo.png" alt="MSTIP Logo" class="logo-img">
                    <div class="logo-text">
                        <span class="logo-title">MSTIP</span>
                        <span class="logo-subtitle">Job Search</span>
                    </div>
                </a>
            </div>
            <hr>
            <h2 class="login-title">Create Account</h2>
            <p class="login-subtitle">Join MSTIP to start your job search</p>

            <!-- Register Form -->
            <form class="login-form" method="POST" id="registerForm">
                <div class="form-group">
                    <input type="text" id="fullname" name="fullname" placeholder="Full Name *" required>
                    <small class="error-message" id="nameError"></small>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Enter your email address *" required>
                    <small class="error-message" id="emailError"></small>
                </div>
                <div class="form-group">
                    <div class="password-group">
                        <input type="password" id="password" name="password" placeholder="Create a password *" required>
                        <i class="fa fa-eye toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
                    </div>
                    <small class="error-message" id="passwordError"></small>
                </div>
                <div class="form-group">
                    <div class="password-group">
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password *" required>
                        <i class="fa fa-eye toggle-password" onclick="toggleConfirmPassword()"></i>
                    </div>
                    <small class="error-message" id="confirmPasswordError"></small>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-user-plus" style="margin-right: 0.5rem;"></i>
                    Register
                </button>
            </form>

            <p class="register-text">
                Already have an account? <a href="login.php" class="register-links">Sign in</a>
            </p>
            <div class="divider"><span>or</span></div>
            <p class="employer-links">
                <a href="employer-login.php">
                    <i class="fas fa-building" style="margin-right: 0.5rem;"></i>
                    Are you an Employer?
                </a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>