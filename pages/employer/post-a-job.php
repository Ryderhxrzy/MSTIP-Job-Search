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

// Check if editing existing job
$is_edit = false;
$job_data = [];
if (isset($_GET['id'])) {
    $job_id = intval($_GET['id']);
    
    // Verify that the job belongs to the employer's company
    $verify_stmt = $conn->prepare("SELECT j.* FROM job_listings j 
                                  JOIN companies c ON j.company_id = c.company_id 
                                  WHERE j.job_id = ? AND c.user_id = ?");
    $verify_stmt->bind_param("is", $job_id, $userCode);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();
    
    if ($verify_result->num_rows > 0) {
        $is_edit = true;
        $job_data = $verify_result->fetch_assoc();
    } else {
        $_SESSION['job_error'] = "Job not found or you don't have permission to edit it.";
        header("Location: manage-jobs.php");
        exit();
    }
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
    
    $image_url = isset($_POST['existing_image']) ? $_POST['existing_image'] : null;

    // Upload job image
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image_url']['type'], $allowed_types)) {
            $extension = pathinfo($_FILES['image_url']['name'], PATHINFO_EXTENSION);
            $new_filename = "job_image_" . time() . "_" . $userCode . "." . $extension;
            $upload_path = "../../assets/images/" . $new_filename;
            if (move_uploaded_file($_FILES['image_url']['tmp_name'], $upload_path)) {
                // Delete old image if exists and updating
                if ($is_edit && !empty($job_data['image_url'])) {
                    $old_image_path = "../../assets/images/" . $job_data['image_url'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
                $image_url = $new_filename;
            }
        }
    }

    // Validate required fields
    if (empty($job_title) || empty($slots_available) || empty($job_description) || empty($qualifications) || empty($application_deadline)) {
        $_SESSION['job_error'] = "Please fill in all required fields.";
        header("Location: " . $_SERVER['PHP_SELF'] . ($is_edit ? "?id=" . $_POST['job_id'] : ""));
        exit();
    }

    // Validate application deadline
    if (strtotime($application_deadline) < strtotime(date('Y-m-d'))) {
        $_SESSION['job_error'] = "Application deadline cannot be in the past.";
        header("Location: " . $_SERVER['PHP_SELF'] . ($is_edit ? "?id=" . $_POST['job_id'] : ""));
        exit();
    }

    if ($is_edit && isset($_POST['job_id'])) {
        // Update existing job
        $job_id = intval($_POST['job_id']);
        $stmt = $conn->prepare("UPDATE job_listings SET job_title = ?, job_position = ?, job_category = ?, slots_available = ?, salary_range = ?, job_description = ?, qualifications = ?, job_type_shift = ?, application_deadline = ?, contact_email = ?, image_url = ? WHERE job_id = ? AND company_id = ?");
        $stmt->bind_param("sssssssssssii", $job_title, $job_position, $job_category, $slots_available, $salary_range, $job_description, $qualifications, $job_type_shift, $application_deadline, $contact_email, $image_url, $job_id, $company_id);
        
        if ($stmt->execute()) {
            $_SESSION['job_success'] = "Job has been updated successfully.";
            header("Location: manage-jobs.php");
            exit();
        } else {
            $_SESSION['job_error'] = "Error updating job. Please try again.";
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $job_id);
            exit();
        }
    } else {
        // Insert new job listing
        $stmt = $conn->prepare("INSERT INTO job_listings (company_id, job_title, job_position, job_category, slots_available, salary_range, job_description, qualifications, job_type_shift, application_deadline, contact_email, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssisssssss", $company_id, $job_title, $job_position, $job_category, $slots_available, $salary_range, $job_description, $qualifications, $job_type_shift, $application_deadline, $contact_email, $image_url);

        if ($stmt->execute()) {
            $_SESSION['job_success'] = "Job has been posted successfully.";
            header("Location: manage-jobs.php");
            exit();
        } else {
            $_SESSION['job_error'] = "Error posting job. Please try again.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_edit ? 'Edit Job' : 'Post a Job'; ?> - MSTIP Seek Employee</title>
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
</head>
<body>
    <?php include_once('../../includes/log-employer-header.php') ?>

    <main>
        <section class="advice-hero">
            <div class="container">
                <h1 class="section-titles"><?php echo $is_edit ? 'Edit Job' : 'Post a Job'; ?></h1>
                <p class="section-subtitle"><?php echo $is_edit ? 'Update your job posting information' : 'Easily post new job opportunities and connect with the right talent for your company.'; ?></p>
            </div>
            <!-- Decorative pattern behind hero -->
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>
        <div class="job-container">
            <form method="POST" enctype="multipart/form-data" id="postJobForm">
                <?php if ($is_edit): ?>
                    <input type="hidden" name="job_id" value="<?php echo $job_data['job_id']; ?>">
                    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($job_data['image_url']); ?>">
                <?php endif; ?>
                
                <!-- Job Basic Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-briefcase"></i> Job Basic Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="job_title" class="required">Job Title</label>
                            <input type="text" id="job_title" name="job_title" value="<?php echo $is_edit ? htmlspecialchars($job_data['job_title']) : (isset($_POST['job_title']) ? htmlspecialchars($_POST['job_title']) : ''); ?>" placeholder="e.g., Software Engineer, Marketing Manager" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="job_position" class="required">Job Position Level</label>
                                <select id="job_position" name="job_position" required>
                                    <option value="">Select Position Level</option>
                                    <?php 
                                    $positions = ['Entry Level', 'Junior', 'Mid-Level', 'Senior', 'Managerial'];
                                    $selected_position = $is_edit ? $job_data['job_position'] : (isset($_POST['job_position']) ? $_POST['job_position'] : '');
                                    foreach ($positions as $position): 
                                    ?>
                                        <option value="<?php echo $position; ?>" <?php echo $selected_position == $position ? 'selected' : ''; ?>><?php echo $position; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="job_category" class="required">Job Category</label>
                                <select id="job_category" name="job_category" required>
                                    <option value="">Select Category</option>
                                    <?php 
                                    $categories = ['normal' => 'Normal', 'deaf' => 'Deaf'];
                                    $selected_category = $is_edit ? $job_data['job_category'] : (isset($_POST['job_category']) ? $_POST['job_category'] : '');
                                    foreach ($categories as $value => $label): 
                                    ?>
                                        <option value="<?php echo $value; ?>" <?php echo $selected_category == $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="slots_available" class="required">Slots Available</label>
                                <input type="number" id="slots_available" name="slots_available" min="1" value="<?php echo $is_edit ? htmlspecialchars($job_data['slots_available']) : (isset($_POST['slots_available']) ? htmlspecialchars($_POST['slots_available']) : '1'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="job_type_shift" class="required">Job Type</label>
                                <select id="job_type_shift" name="job_type_shift" required>
                                    <option value="">Select Job Type</option>
                                    <?php 
                                    $job_types = ['Full-Time', 'Part-Time'];
                                    $selected_type = $is_edit ? $job_data['job_type_shift'] : (isset($_POST['job_type_shift']) ? $_POST['job_type_shift'] : '');
                                    foreach ($job_types as $type): 
                                    ?>
                                        <option value="<?php echo $type; ?>" <?php echo $selected_type == $type ? 'selected' : ''; ?>><?php echo $type; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="salary_range">Salary Range</label>
                            <input type="text" id="salary_range" name="salary_range" value="<?php echo $is_edit ? htmlspecialchars($job_data['salary_range']) : (isset($_POST['salary_range']) ? htmlspecialchars($_POST['salary_range']) : ''); ?>" placeholder="e.g., ₱20,000 - ₱30,000 per month">
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
                            <textarea id="job_description" name="job_description" placeholder="Describe the job responsibilities, duties, and expectations..." required><?php echo $is_edit ? htmlspecialchars($job_data['job_description']) : (isset($_POST['job_description']) ? htmlspecialchars($_POST['job_description']) : ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="qualifications" class="required">Qualifications</label>
                            <textarea id="qualifications" name="qualifications" placeholder="List the required skills, education, experience, and other qualifications..." required><?php echo $is_edit ? htmlspecialchars($job_data['qualifications']) : (isset($_POST['qualifications']) ? htmlspecialchars($_POST['qualifications']) : ''); ?></textarea>
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
                                <input type="date" id="application_deadline" name="application_deadline" value="<?php echo $is_edit ? htmlspecialchars($job_data['application_deadline']) : (isset($_POST['application_deadline']) ? htmlspecialchars($_POST['application_deadline']) : ''); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contact_email" class="required">Contact Email</label>
                                <input type="email" id="contact_email" name="contact_email" value="<?php echo $is_edit ? htmlspecialchars($job_data['contact_email']) : (isset($_POST['contact_email']) ? htmlspecialchars($_POST['contact_email']) : ''); ?>" placeholder="email@company.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="image_url">Job Image</label>
                            <div class="image-upload" onclick="document.getElementById('imageInput').click()">
                                <input type="file" name="image_url" accept="image/*" onchange="previewJobImage(this)" id="imageInput">
                                <div class="upload-placeholder" <?php echo ($is_edit && !empty($job_data['image_url'])) ? 'style="display:none;"' : ''; ?>>
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Click to upload job image (Optional)</span>
                                </div>
                                <div class="image-preview" id="imagePreview" <?php echo ($is_edit && !empty($job_data['image_url'])) ? 'style="display:block;"' : ''; ?>>
                                    <?php if ($is_edit && !empty($job_data['image_url'])): ?>
                                        <img src="../../assets/images/<?php echo htmlspecialchars($job_data['image_url']); ?>" alt="Current Job Image">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='manage-jobs.php'">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-<?php echo $is_edit ? 'save' : 'paper-plane'; ?>"></i> <?php echo $is_edit ? 'Update Job' : 'Post Job'; ?>
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
                text: 'Job has been <?php echo $is_edit ? "updated" : "posted"; ?> successfully.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
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