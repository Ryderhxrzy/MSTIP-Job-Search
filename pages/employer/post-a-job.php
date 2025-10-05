<?php
include_once('../../includes/db_connect.php');
session_start();

// Check if user is logged in as Employer
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'Employer') {
    header("Location: ../../employer-login.php");
    exit();
}

$userCode = $_SESSION['user_code'];

// Get company_id from companies table
$company_id = null;
$stmt = $conn->prepare("SELECT company_id FROM companies WHERE user_id = ?");
$stmt->bind_param("s", $userCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $company = $result->fetch_assoc();
    $company_id = $company['company_id'];
} else {
    $_SESSION['job_error'] = "Please complete your company profile first before posting jobs.";
    header("Location: company-profile.php");
    exit();
}

// Check for session messages
$show_success = false;
$show_error = false;
$error_msg = '';

if (isset($_SESSION['job_success'])) {
    $show_success = true;
    unset($_SESSION['job_success']);
}

if (isset($_SESSION['job_error'])) {
    $show_error = true;
    $error_msg = $_SESSION['job_error'];
    unset($_SESSION['job_error']);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_title = trim($_POST['job_title']);
    $job_position = $_POST['job_position'];
    $job_category = $_POST['job_category'];
    $slots_available = intval($_POST['slots_available']);
    $salary_range = trim($_POST['salary_range']);
    $job_description = trim($_POST['job_description']);
    $qualifications = trim($_POST['qualifications']);
    $job_type_shift = $_POST['job_type_shift'];
    $application_deadline = $_POST['application_deadline'];
    $contact_email = trim($_POST['contact_email']);
    
    $image_url = null;

    // Upload job image
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image_url']['type'], $allowed_types)) {
            $extension = pathinfo($_FILES['image_url']['name'], PATHINFO_EXTENSION);
            $new_filename = "job_image_" . time() . "_" . $userCode . "." . $extension;
            $upload_path = "../../assets/images/" . $new_filename;
            if (move_uploaded_file($_FILES['image_url']['tmp_name'], $upload_path)) {
                $image_url = $new_filename;
            }
        }
    }

    // Validate required fields
    if (empty($job_title) || empty($slots_available) || empty($job_description) || empty($qualifications) || empty($application_deadline)) {
        $_SESSION['job_error'] = "Please fill in all required fields.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Validate application deadline
    if (strtotime($application_deadline) < strtotime(date('Y-m-d'))) {
        $_SESSION['job_error'] = "Application deadline cannot be in the past.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Insert job listing
    $stmt = $conn->prepare("INSERT INTO job_listings (company_id, job_title, job_position, job_category, slots_available, salary_range, job_description, qualifications, job_type_shift, application_deadline, contact_email, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssisssssss", $company_id, $job_title, $job_position, $job_category, $slots_available, $salary_range, $job_description, $qualifications, $job_type_shift, $application_deadline, $contact_email, $image_url);

    if ($stmt->execute()) {
        $_SESSION['job_success'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['job_error'] = "Error posting job. Please try again.";
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
    <title>Post a Job - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/homepage.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/employer.css">
    <link rel="stylesheet" href="../../assets/css/sweetalert.css">
    <link rel="stylesheet" href="../../assets/css/text.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>

    </style>
</head>
<body>
    <?php include_once('../../includes/log-employer-header.php') ?>

    <main>
        <section class="advice-hero">
            <div class="container">
                <h1 class="section-titles">Post a Job</h1>
                <p class="section-subtitle">Easily post new job opportunities and connect with the right talent for your company.</p>
            </div>
            <!-- Decorative pattern behind hero -->
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>
        <div class="job-container">
            <form method="POST" enctype="multipart/form-data" id="postJobForm">
                <!-- Job Basic Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-briefcase"></i> Job Basic Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="job_title" class="required">Job Title</label>
                            <input type="text" id="job_title" name="job_title" value="<?php echo isset($_POST['job_title']) ? htmlspecialchars($_POST['job_title']) : ''; ?>" placeholder="e.g., Software Engineer, Marketing Manager" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="job_position" class="required">Job Position Level</label>
                                <select id="job_position" name="job_position" required>
                                    <option value="">Select Position Level</option>
                                    <option value="Entry Level" <?php echo (isset($_POST['job_position']) && $_POST['job_position'] == 'Entry Level') ? 'selected' : ''; ?>>Entry Level</option>
                                    <option value="Junior" <?php echo (isset($_POST['job_position']) && $_POST['job_position'] == 'Junior') ? 'selected' : ''; ?>>Junior</option>
                                    <option value="Mid-Level" <?php echo (isset($_POST['job_position']) && $_POST['job_position'] == 'Mid-Level') ? 'selected' : ''; ?>>Mid-Level</option>
                                    <option value="Senior" <?php echo (isset($_POST['job_position']) && $_POST['job_position'] == 'Senior') ? 'selected' : ''; ?>>Senior</option>
                                    <option value="Managerial" <?php echo (isset($_POST['job_position']) && $_POST['job_position'] == 'Managerial') ? 'selected' : ''; ?>>Managerial</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="job_category" class="required">Job Category</label>
                                <select id="job_category" name="job_category" required>
                                    <option value="">Select Category</option>
                                    <option value="normal" <?php echo (isset($_POST['job_category']) && $_POST['job_category'] == 'normal') ? 'selected' : ''; ?>>Normal</option>
                                    <option value="deaf" <?php echo (isset($_POST['job_category']) && $_POST['job_category'] == 'deaf') ? 'selected' : ''; ?>>Deaf</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="slots_available" class="required">Slots Available</label>
                                <input type="number" id="slots_available" name="slots_available" min="1" value="<?php echo isset($_POST['slots_available']) ? htmlspecialchars($_POST['slots_available']) : '1'; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="job_type_shift" class="required">Job Type</label>
                                <select id="job_type_shift" name="job_type_shift" required>
                                    <option value="">Select Job Type</option>
                                    <option value="Full-Time" <?php echo (isset($_POST['job_type_shift']) && $_POST['job_type_shift'] == 'Full-Time') ? 'selected' : ''; ?>>Full-Time</option>
                                    <option value="Part-Time" <?php echo (isset($_POST['job_type_shift']) && $_POST['job_type_shift'] == 'Part-Time') ? 'selected' : ''; ?>>Part-Time</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="salary_range">Salary Range</label>
                            <input type="text" id="salary_range" name="salary_range" value="<?php echo isset($_POST['salary_range']) ? htmlspecialchars($_POST['salary_range']) : ''; ?>" placeholder="e.g., ₱20,000 - ₱30,000 per month">
                        </div>
                    </div>
                </div>

                <!-- Job Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-file-alt"></i> Job Details</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="job_description" class="required">Job Description</label>
                            <textarea id="job_description" name="job_description" placeholder="Describe the job responsibilities, duties, and expectations..." required><?php echo isset($_POST['job_description']) ? htmlspecialchars($_POST['job_description']) : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="qualifications" class="required">Qualifications</label>
                            <textarea id="qualifications" name="qualifications" placeholder="List the required skills, education, experience, and other qualifications..." required><?php echo isset($_POST['qualifications']) ? htmlspecialchars($_POST['qualifications']) : ''; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Application Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-calendar-alt"></i> Application Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="application_deadline" class="required">Application Deadline</label>
                                <input type="date" id="application_deadline" name="application_deadline" value="<?php echo isset($_POST['application_deadline']) ? htmlspecialchars($_POST['application_deadline']) : ''; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contact_email" class="required">Contact Email</label>
                                <input type="email" id="contact_email" name="contact_email" value="<?php echo isset($_POST['contact_email']) ? htmlspecialchars($_POST['contact_email']) : ''; ?>" placeholder="email@company.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="image_url">Job Image</label>
                            <div class="image-upload" onclick="document.getElementById('imageInput').click()">
                                <input type="file" name="image_url" accept="image/*" onchange="previewJobImage(this)" id="imageInput">
                                <div class="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Click to upload job image (Optional)</span>
                                </div>
                                <div class="image-preview" id="imagePreview"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Post Job
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include_once('../../includes/employer-footer.php') ?>
    <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Image preview for job image
        function previewJobImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Job Image Preview">';
                    preview.style.display = 'block';
                    
                    // Hide placeholder
                    document.querySelector('.upload-placeholder').style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Set minimum date for application deadline to today
        document.getElementById('application_deadline').min = new Date().toISOString().split('T')[0];

        // SweetAlert notifications
        <?php if ($show_success): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Job has been posted successfully.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Optionally redirect to jobs list or clear form
                    window.location.href = 'manage-jobs.php';
                }
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

        // Form validation
        document.getElementById('postJobForm').addEventListener('submit', function(e) {
            const deadline = document.getElementById('application_deadline').value;
            const today = new Date().toISOString().split('T')[0];
            
            if (deadline < today) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'Application deadline cannot be in the past.',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
</body>
</html>