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
        
        // Load existing questions for this job
        $questions_stmt = $conn->prepare("SELECT * FROM job_questions WHERE job_id = ? ORDER BY question_order");
        $questions_stmt->bind_param("i", $job_id);
        $questions_stmt->execute();
        $existing_questions = $questions_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
            // Handle questions update
            saveJobQuestions($conn, $job_id, $_POST);
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
            $job_id = $conn->insert_id;
            // Handle questions save
            saveJobQuestions($conn, $job_id, $_POST);
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

function saveJobQuestions($conn, $job_id, $post_data) {
    // First, delete existing questions for this job (if editing)
    $delete_stmt = $conn->prepare("DELETE FROM job_questions WHERE job_id = ?");
    $delete_stmt->bind_param("i", $job_id);
    $delete_stmt->execute();
    
    // Extract questions from POST data
    $questions = [];
    foreach ($post_data as $key => $value) {
        if (strpos($key, 'question_text_') === 0 && !empty(trim($value))) {
            $question_id = str_replace('question_text_', '', $key);
            $question_text = trim($value);
            $is_required = isset($post_data["question_required_$question_id"]) ? 1 : 0;
            
            $questions[] = [
                'question_text' => $question_text,
                'is_required' => $is_required,
                'question_order' => count($questions) + 1
            ];
        }
    }
    
    // Insert new questions
    if (!empty($questions)) {
        $insert_stmt = $conn->prepare("INSERT INTO job_questions (job_id, question_text, is_required, question_order) VALUES (?, ?, ?, ?)");
        
        foreach ($questions as $question) {
            $insert_stmt->bind_param("isii", $job_id, $question['question_text'], $question['is_required'], $question['question_order']);
            $insert_stmt->execute();
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
    <style>
        /* Question Management Styles */
        .card-subtitle {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 5px;
            font-weight: normal;
        }
        
        .question-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #f9fafb;
            transition: all 0.3s ease;
        }
        
        .question-item:hover {
            border-color: #d1d5db;
            background: #f3f4f6;
        }
        
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            border-radius: 8px 8px 0 0;
        }
        
        .question-number {
            font-weight: 600;
            color: #374151;
            font-size: 0.95rem;
        }
        
        .question-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #6b7280;
            cursor: pointer;
            margin: 0;
        }
        
        .checkbox-label input[type="checkbox"] {
            margin: 0;
            transform: scale(0.9);
        }
        
        .question-content {
            padding: 15px;
        }
        
        .question-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }
        
        .question-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .question-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }
        
        .btn-outline-primary {
            background: transparent;
            border: 1px solid #3b82f6;
            color: #3b82f6;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-outline-primary:hover {
            background: #3b82f6;
            color: white;
        }
        
        .btn-outline-secondary {
            background: transparent;
            border: 1px solid #6b7280;
            color: #6b7280;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-outline-secondary:hover {
            background: #6b7280;
            color: white;
        }
        
        .question-help {
            margin-top: 15px;
            padding: 12px;
            background: #f0f9ff;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
        }
        
        .question-help i {
            margin-right: 6px;
            color: #3b82f6;
        }
        
        .remove-question {
            background: #ef4444;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .remove-question:hover {
            background: #dc2626;
        }
        
        /* Animation for new questions */
        .question-item {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
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

                <!-- Application Questions Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-question-circle"></i> Application Questions</h2>
                        <p class="card-subtitle">Add custom questions that applicants will answer when applying for this position</p>
                    </div>
                    <div class="card-body">
                        <div id="questionsContainer">
                            <?php 
                            $questions_to_display = $is_edit && !empty($existing_questions) ? $existing_questions : [];
                            if (empty($questions_to_display)): 
                            ?>
                                <div class="question-item" data-question-id="1">
                                    <div class="question-header">
                                        <span class="question-number">Question 1</span>
                                        <div class="question-controls">
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="question_required_1" class="question-required" checked>
                                                <span>Required</span>
                                            </label>
                                            <button type="button" class="btn btn-danger btn-sm remove-question" onclick="removeQuestion(1)" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="question-content">
                                        <input type="text" name="question_text_1" class="form-control question-input" placeholder="Enter your question here..." maxlength="255">
                                        <small class="text-muted">Max 255 characters</small>
                                    </div>
                                </div>
                            <?php 
                            else:
                                foreach ($questions_to_display as $index => $question):
                                    $question_num = $index + 1;
                            ?>
                                <div class="question-item" data-question-id="<?php echo $question_num; ?>">
                                    <div class="question-header">
                                        <span class="question-number">Question <?php echo $question_num; ?></span>
                                        <div class="question-controls">
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="question_required_<?php echo $question_num; ?>" class="question-required" <?php echo $question['is_required'] ? 'checked' : ''; ?>>
                                                <span>Required</span>
                                            </label>
                                            <button type="button" class="btn btn-danger btn-sm remove-question" onclick="removeQuestion(<?php echo $question_num; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="question-content">
                                        <input type="text" name="question_text_<?php echo $question_num; ?>" class="form-control question-input" placeholder="Enter your question here..." maxlength="255" value="<?php echo htmlspecialchars($question['question_text']); ?>">
                                        <small class="text-muted">Max 255 characters</small>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </div>
                        
                        <div class="question-actions">
                            <button type="button" class="btn btn-outline-primary" onclick="addQuestion()">
                                <i class="fas fa-plus"></i> Add Question
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearAllQuestions()">
                                <i class="fas fa-times"></i> Clear All
                            </button>
                        </div>
                        
                        <div class="question-help">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Add questions to gather specific information from applicants. You can add up to 10 questions per job posting.
                            </small>
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

        // Question Management
        let questionCount = <?php echo $is_edit && !empty($existing_questions) ? count($existing_questions) : 1; ?>;
        const maxQuestions = 10;

        function addQuestion() {
            if (questionCount >= maxQuestions) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maximum Questions Reached',
                    text: 'You can add up to 10 questions per job posting.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            questionCount++;
            const questionsContainer = document.getElementById('questionsContainer');
            
            const questionDiv = document.createElement('div');
            questionDiv.className = 'question-item';
            questionDiv.setAttribute('data-question-id', questionCount);
            
            questionDiv.innerHTML = `
                <div class="question-header">
                    <span class="question-number">Question ${questionCount}</span>
                    <div class="question-controls">
                        <label class="checkbox-label">
                            <input type="checkbox" name="question_required_${questionCount}" class="question-required" checked>
                            <span>Required</span>
                        </label>
                        <button type="button" class="btn btn-danger btn-sm remove-question" onclick="removeQuestion(${questionCount})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="question-content">
                    <input type="text" name="question_text_${questionCount}" class="form-control question-input" placeholder="Enter your question here..." maxlength="255">
                    <small class="text-muted">Max 255 characters</small>
                </div>
            `;
            
            questionsContainer.appendChild(questionDiv);
            updateRemoveButtons();
        }

        function removeQuestion(questionId) {
            const questionDiv = document.querySelector(`[data-question-id="${questionId}"]`);
            if (questionDiv) {
                questionDiv.remove();
                updateQuestionNumbers();
                updateRemoveButtons();
            }
        }

        function clearAllQuestions() {
            Swal.fire({
                title: 'Clear All Questions?',
                text: 'Are you sure you want to remove all questions?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Clear All',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const questionsContainer = document.getElementById('questionsContainer');
                    questionsContainer.innerHTML = `
                        <div class="question-item" data-question-id="1">
                            <div class="question-header">
                                <span class="question-number">Question 1</span>
                                <div class="question-controls">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="question_required_1" class="question-required" checked>
                                        <span>Required</span>
                                    </label>
                                    <button type="button" class="btn btn-danger btn-sm remove-question" onclick="removeQuestion(1)" style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="question-content">
                                <input type="text" name="question_text_1" class="form-control question-input" placeholder="Enter your question here..." maxlength="255">
                                <small class="text-muted">Max 255 characters</small>
                            </div>
                        </div>
                    `;
                    questionCount = 1;
                    updateRemoveButtons();
                }
            });
        }

        function updateQuestionNumbers() {
            const questions = document.querySelectorAll('.question-item');
            questionCount = questions.length;
            
            questions.forEach((question, index) => {
                const questionId = index + 1;
                const questionNumber = question.querySelector('.question-number');
                const requiredCheckbox = question.querySelector('.question-required');
                const questionInput = question.querySelector('.question-input');
                const removeButton = question.querySelector('.remove-question');
                
                // Update question number
                questionNumber.textContent = `Question ${questionId}`;
                
                // Update input names
                requiredCheckbox.name = `question_required_${questionId}`;
                questionInput.name = `question_text_${questionId}`;
                
                // Update data attribute and onclick
                question.setAttribute('data-question-id', questionId);
                removeButton.setAttribute('onclick', `removeQuestion(${questionId})`);
            });
        }

        function updateRemoveButtons() {
            const questions = document.querySelectorAll('.question-item');
            questions.forEach((question, index) => {
                const removeButton = question.querySelector('.remove-question');
                if (removeButton) {
                    removeButton.style.display = questions.length > 1 ? 'inline-block' : 'none';
                }
            });
        }

        // Initialize remove buttons on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });
    </script>
</body>
</html>