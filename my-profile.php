<?php
include_once('includes/db_connect.php');
session_start();

// Check if user is logged in as Graduate
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'Graduate') {
    header("Location: login.php");
    exit();
}

$userCode = $_SESSION['user_code'];

// Check for session messages
$show_success = false;
$show_error = false;
$show_password_success = false;
$show_password_error = false;
$error_msg = '';
$password_error_msg = '';

if (isset($_SESSION['profile_success'])) {
    $show_success = true;
    unset($_SESSION['profile_success']);
}

if (isset($_SESSION['profile_error'])) {
    $show_error = true;
    $error_msg = $_SESSION['profile_error'];
    unset($_SESSION['profile_error']);
}

if (isset($_SESSION['password_success'])) {
    $show_password_success = true;
    unset($_SESSION['password_success']);
}

if (isset($_SESSION['password_error'])) {
    $show_password_error = true;
    $password_error_msg = $_SESSION['password_error'];
    unset($_SESSION['password_error']);
}

// Fetch graduate profile data
$graduate = [];
$stmt = $conn->prepare("SELECT * FROM graduate_information WHERE user_id = ?");
$stmt->bind_param("s", $userCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $graduate = $result->fetch_assoc();
} else {
    $graduate = [
        'first_name' => '', 'middle_name' => '', 'last_name' => '',
        'phone_number' => '', 'course' => '', 'year_graduated' => '',
        'skills' => '', 'resume' => '', 'linkedin_profile' => '', 'profile' => ''
    ];
}

// Handle profile form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['change_password'])) {
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $phone_number = trim($_POST['phone_number']);
    $course = trim($_POST['course']);
    $year_graduated = $_POST['year_graduated'];
    $skills = implode(', ', array_map('trim', explode(',', $_POST['skills'])));
    $linkedin_profile = trim($_POST['linkedin_profile']);

    $profile_picture = $graduate['profile'];
    $resume_file = $graduate['resume'];

    // Upload profile picture
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (in_array($_FILES['profile']['type'], $allowed_types)) {
            $extension = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
            $new_filename = "graduate_profile_" . $userCode . "." . $extension;
            $upload_path = "assets/images/" . $new_filename;
            if (move_uploaded_file($_FILES['profile']['tmp_name'], $upload_path)) {
                $profile_picture = $new_filename;
            }
        }
    }

    // Upload resume
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (in_array($_FILES['resume']['type'], $allowed_types)) {
            $extension = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
            $new_filename = "resume_" . $userCode . "." . $extension;
            $upload_path = "assets/resumes/" . $new_filename;
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $upload_path)) {
                $resume_file = $new_filename;
            }
        }
    }

    if (empty($graduate['id'])) {
        $stmt = $conn->prepare("INSERT INTO graduate_information (user_id, first_name, middle_name, last_name, phone_number, course, year_graduated, skills, resume, linkedin_profile, profile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssissss", $userCode, $first_name, $middle_name, $last_name, $phone_number, $course, $year_graduated, $skills, $resume_file, $linkedin_profile, $profile_picture);
    } else {
        $stmt = $conn->prepare("UPDATE graduate_information SET first_name = ?, middle_name = ?, last_name = ?, phone_number = ?, course = ?, year_graduated = ?, skills = ?, resume = ?, linkedin_profile = ?, profile = ? WHERE user_id = ?");
        $stmt->bind_param("sssssssssss", $first_name, $middle_name, $last_name, $phone_number, $course, $year_graduated, $skills, $resume_file, $linkedin_profile, $profile_picture, $userCode);
    }

    if ($stmt->execute()) {
        $_SESSION['profile_success'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['profile_error'] = "Error updating profile. Please try again.";
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
    <title>My Profile - MSTIP Seek Employee</title>
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
                <h1 class="section-titles">Profile</h1>
                <p class="section-subtitle">Manage and update your profile information.</p>
            </div>
            <!-- Decorative pattern behind hero -->
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>
        <div class="profile-container">
            <form method="POST" enctype="multipart/form-data" id="graduateProfileForm">
                <!-- Images Card - Facebook Style -->
                <div class="cards">
                    <div class="fb-header-section">
                        <!-- Cover Image (Default) -->
                        <div class="cover-upload">
                            <div class="cover-display" id="coverPreview">
                                <img src="assets/images/default-cover.jpg" alt="Cover">
                            </div>
                        </div>

                        <!-- Profile Picture Overlay -->
                        <div class="profile-overlay">
                            <div class="profile-upload">
                                <input type="file" name="profile" accept="image/*" onchange="previewImage(this, 'profilePreview')" id="profileInput">
                                <div class="profile-display" id="profilePreview">
                                    <?php if (!empty($graduate['profile'])): ?>
                                        <img src="assets/images/<?php echo $graduate['profile']; ?>" alt="Profile">
                                    <?php else: ?>
                                        <div class="profile-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="edit-profile-btn" onclick="document.getElementById('profileInput').click()">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-user"></i> Personal Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name" class="required">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($graduate['first_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($graduate['middle_name']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="required">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($graduate['last_name']); ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone_number" class="required">Phone Number</label>
                                <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($graduate['phone_number']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="linkedin_profile">LinkedIn Profile</label>
                                <input type="url" id="linkedin_profile" name="linkedin_profile" value="<?php echo htmlspecialchars($graduate['linkedin_profile']); ?>" placeholder="https://linkedin.com/in/yourprofile">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Education & Skills Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-graduation-cap"></i> Education & Skills</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="course" class="required">Course</label>
                                <input type="text" id="course" name="course" value="<?php echo htmlspecialchars($graduate['course']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="year_graduated" class="required">Year Graduated</label>
                                <input type="number" id="year_graduated" name="year_graduated" min="1990" max="<?php echo date('Y'); ?>" value="<?php echo $graduate['year_graduated']; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="skills">Skills (separate by comma)</label>
                            <textarea id="skills" name="skills" placeholder="e.g., JavaScript, PHP, Python, Communication, Problem Solving"><?php echo htmlspecialchars($graduate['skills']); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Resume Upload Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-file-alt"></i> Resume</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="resume">Upload Resume (PDF or DOC)</label>
                            <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx">
                            <?php if (!empty($graduate['resume'])): ?>
                                <small class="file-info">
                                    Current file: <a href="assets/resumes/<?php echo $graduate['resume']; ?>" target="_blank"><?php echo $graduate['resume']; ?></a>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                            <i class="fas fa-arrow-left"></i>&nbsp;Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>&nbsp; Save Profile
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
        // Image preview
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Profile">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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

        // SweetAlert notifications for profile
        <?php if ($show_success): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Profile has been updated successfully.',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        <?php if ($show_error): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo $error_msg; ?>',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>
</html>