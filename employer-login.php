<?php
session_start();
include_once('includes/db_connect.php');

// ✅ Handle logout (optional cookie clearing)
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    // Clear cookie
    setcookie('employer_remember', '', time() - 3600, '/');
    session_unset();
    session_destroy();

    echo '
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: "success",
            title: "Logged Out Successfully",
            text: "You have been successfully logged out.",
            timer: 3000,
            showConfirmButton: false
        });
    });
    </script>
    ';
}

// ✅ Check "Remember Me" cookie before anything else
if (!isset($_SESSION['logged_in']) && isset($_COOKIE['employer_remember'])) {
    $remember_token = $_COOKIE['employer_remember'];

    $stmt = $conn->prepare("SELECT id, user_id, email_address, user_type, status FROM users WHERE user_id = ? LIMIT 1");
    $stmt->bind_param("s", $remember_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['user_type'] === 'Employer' && $user['status'] === 'Active') {
            // Restore session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_code'] = $user['user_id'];
            $_SESSION['email'] = $user['email_address'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['logged_in'] = true;

            // Renew cookie (extend for another 30 days)
            setcookie('employer_remember', $user['user_id'], time() + (86400 * 30), '/');

            header('Location: pages/employer/employer-home.php');
            exit;
        }
    }
    $stmt->close();
}

// ✅ Check if session already active
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && $_SESSION['user_type'] === 'Employer') {
    header('Location: pages/employer/employer-home.php');
    exit;
}

// ✅ Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    
    // Validation
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }
    
    // Query user
    $stmt = $conn->prepare("SELECT id, user_id, email_address, password, user_type, status FROM users WHERE email_address = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Password check
    if (!password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }
    
    // Ensure employer
    if ($user['user_type'] !== 'Employer') {
        echo json_encode(['success' => false, 'message' => 'Access denied. This login is for employers only.']);
        exit;
    }
    
    // Check account status
    if ($user['status'] !== 'Active') {
        echo json_encode(['success' => false, 'message' => 'Your account has been deactivated. Please contact support.']);
        exit;
    }
    
    // ✅ Create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_code'] = $user['user_id'];
    $_SESSION['email'] = $user['email_address'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['logged_in'] = true;
    
    // ✅ Handle Remember Me
    if ($remember) {
        setcookie('employer_remember', $user['user_id'], time() + (86400 * 30), '/');
    } else {
        // Ensure cookie is cleared if previously set
        setcookie('employer_remember', '', time() - 3600, '/');
    }

    echo json_encode(['success' => true, 'message' => 'Login successful! Redirecting...']);
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Login - MSTIP Job Search</title>
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- ================= Enhanced Login Page ================= -->
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <a href="employer-home.php">
                    <img src="assets/images/mstip_logo.png" alt="MSTIP Logo" class="logo-img">
                    <div class="logo-text">
                        <span class="logo-title">MSTIP</span>
                        <span class="logo-subtitle">Seek Employee</span>
                    </div>
                </a>
            </div>
            <hr>
            <h2 class="login-title">Welcome Back</h2>
            <p class="login-subtitle">Sign in to manage your job postings</p>

            <!-- Enhanced Login Form -->
            <form class="login-form" id="loginForm" method="POST">
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Enter your email address *" required>
                    <small class="error-message" id="emailError"></small>
                </div>
                <div class="form-group">
                    <div class="password-group">
                        <input type="password" id="password" name="password" placeholder="Enter your password *" required>
                        <i class="fa fa-eye toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
                    </div>
                    <small class="error-message" id="passwordError"></small>
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
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
                    <i class="fas fa-user" style="margin-right: 0.5rem;"></i>
                    Are you a Job Searcher?
                </a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/log-employer-script.js"></script>
</body>
</html>
