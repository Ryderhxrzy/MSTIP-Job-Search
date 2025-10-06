<?php
include_once('includes/db_connect.php');
session_start();

// Check if user is logged in as Graduate
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'Graduate') {
    header("Location: graduate-login.php");
    exit();
}

$userCode = $_SESSION['user_code'];

// Check for session messages
$show_password_success = false;
$show_password_error = false;
$password_error_msg = '';

if (isset($_SESSION['password_success'])) {
    $show_password_success = true;
    unset($_SESSION['password_success']);
}

if (isset($_SESSION['password_error'])) {
    $show_password_error = true;
    $password_error_msg = $_SESSION['password_error'];
    unset($_SESSION['password_error']);
}

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['password_error'] = "All password fields are required.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    // Check if new password matches confirm password
    if ($new_password !== $confirm_password) {
        $_SESSION['password_error'] = "New password and confirm password do not match.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    // Validate password strength
    if (strlen($new_password) < 8) {
        $_SESSION['password_error'] = "Password must be at least 8 characters long.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    if (!preg_match('/[A-Z]/', $new_password)) {
        $_SESSION['password_error'] = "Password must contain at least one uppercase letter.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    if (!preg_match('/[a-z]/', $new_password)) {
        $_SESSION['password_error'] = "Password must contain at least one lowercase letter.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    if (!preg_match('/[0-9]/', $new_password)) {
        $_SESSION['password_error'] = "Password must contain at least one number.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $userCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Check if current password is correct
        if (password_verify($current_password, $user['password'])) {
            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $update_stmt->bind_param("ss", $hashed_password, $userCode);
            
            if ($update_stmt->execute()) {
                $_SESSION['password_success'] = true;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $_SESSION['password_error'] = "Error updating password. Please try again.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            $_SESSION['password_error'] = "Current password is incorrect.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['password_error'] = "User not found.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/employer.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="stylesheet" href="assets/css/text.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Password Input Wrapper */
        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-wrapper input {
            width: 100%;
            padding-right: 45px;
        }

        /* Toggle Password Button */
        .toggle-password {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            color: #666;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #333;
        }

        .toggle-password i {
            font-size: 18px;
        }

        /* Password Hint Text */
        .password-hint {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php include_once('includes/header.php') ?>

    <main>
        <section class="advice-hero">
            <div class="container">
                <h1 class="section-titles">Change Password</h1>
                <p class="section-subtitle">Update your account password to keep it secure.</p>
            </div>
            <!-- Decorative pattern behind hero -->
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>
        <div class="profile-container">
            <!-- Change Password Form -->
            <form method="POST" id="changePasswordForm">
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-key"></i> Change Password</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="current_password" class="required">Current Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="current_password" name="current_password" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye" id="current_password_icon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password" class="required">New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="new_password" name="new_password" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye" id="new_password_icon"></i>
                                </button>
                            </div>
                            <small class="password-hint">
                                Password must be at least 8 characters and contain uppercase, lowercase, and numbers.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password" class="required">Confirm New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="confirm_password" name="confirm_password" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye" id="confirm_password_icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php include_once('includes/footer.php') ?>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // SweetAlert notifications for password
        <?php if ($show_password_success): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Password has been changed successfully.',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        <?php if ($show_password_error): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo $password_error_msg; ?>',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>
</html>