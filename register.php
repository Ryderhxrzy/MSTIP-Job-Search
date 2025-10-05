<?php 
include_once('includes/db_connect.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Validation
    if (empty($email) || empty($password) || empty($confirmPassword)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }
    
    if (!str_ends_with($email, '@mstip.edu.ph')) {
        echo json_encode(['success' => false, 'message' => 'Only @mstip.edu.ph email addresses are accepted']);
        exit;
    }
    
    if ($password !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        exit;
    }
    
    if (strlen($password) <= 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 7 characters long']);
        exit;
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email_address = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }
    $stmt->close();
    
    // Generate unique user_id (E000000 format)
    $user_id = 'G' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    
    // Check if user_id exists (rare case)
    $stmt = $conn->prepare("SELECT id FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($result->num_rows > 0) {
        $user_id = 'G' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    $stmt->close();
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (user_id, email_address, password, user_type, status) VALUES (?, ?, ?, 'Graduate', 'Active')");
    $stmt->bind_param("sss", $user_id, $email, $hashed_password);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful! Redirecting to login...']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}
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

    <script src="assets/js/reg-script.js"></script>
</body>
</html>
