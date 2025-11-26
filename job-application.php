<?php
include_once('includes/db_connect.php');
session_start();

// Check if user is logged in as Graduate
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'Graduate') {
    header("Location: login.php");
    exit();
}

$userCode = $_SESSION['user_code'];
$job_id = $_GET['job_id'] ?? null;

if (!$job_id) {
    $_SESSION['error'] = "No job specified.";
    header("Location: index.php");
    exit();
}

// Validate job exists and get job details
$job_query = "SELECT jl.*, c.company_name, c.location as company_location 
              FROM job_listings jl 
              JOIN companies c ON jl.company_id = c.company_id 
              WHERE jl.job_id = ?";
$job_stmt = $conn->prepare($job_query);
$job_stmt->bind_param("i", $job_id);
$job_stmt->execute();
$job_result = $job_stmt->get_result();

if ($job_result->num_rows === 0) {
    $_SESSION['error'] = "Job not found.";
    header("Location: index.php");
    exit();
}

$job = $job_result->fetch_assoc();

// Check if user already applied
$check_application = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
$check_application->bind_param("si", $userCode, $job_id);
$check_application->execute();
$app_result = $check_application->get_result();

if ($app_result->num_rows > 0) {
    $_SESSION['error'] = "You have already applied for this job.";
    header("Location: my-application.php");
    exit();
}

// Check if user has complete graduate information
$grad_info_query = "SELECT * FROM graduate_information WHERE user_id = ?";
$grad_stmt = $conn->prepare($grad_info_query);
$grad_stmt->bind_param("s", $userCode);
$grad_stmt->execute();
$grad_result = $grad_stmt->get_result();
$grad_info = $grad_result->fetch_assoc();

// Check if graduate information is complete
$is_profile_complete = false;
$missing_fields = [];

if ($grad_info) {
    $required_fields = ['first_name', 'last_name', 'phone_number', 'course', 'year_graduated', 'resume'];
    foreach ($required_fields as $field) {
        if (empty($grad_info[$field])) {
            $missing_fields[] = $field;
        }
    }
    $is_profile_complete = empty($missing_fields);
}

// Get job questions
$questions_query = "SELECT * FROM job_questions WHERE job_id = ? ORDER BY question_order";
$questions_stmt = $conn->prepare($questions_query);
$questions_stmt->bind_param("i", $job_id);
$questions_stmt->execute();
$questions_result = $questions_stmt->get_result();
$questions = $questions_result->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_profile' && !$is_profile_complete) {
        // Update graduate information
        $first_name = $_POST['first_name'] ?? '';
        $middle_name = $_POST['middle_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        $course = $_POST['course'] ?? '';
        $year_graduated = (int)($_POST['year_graduated'] ?? 0);
        $skills = $_POST['skills'] ?? '';
        $linkedin_profile = $_POST['linkedin_profile'] ?? '';
        
        // Handle resume upload
        $resume = $grad_info['resume'] ?? ''; // Keep existing if no new upload
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
            $resume_file = $_FILES['resume'];
            $allowed_types = ['pdf', 'doc', 'docx'];
            $file_ext = strtolower(pathinfo($resume_file['name'], PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $allowed_types)) {
                $upload_dir = 'assets/resumes/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $new_filename = $userCode . '_resume_' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($resume_file['tmp_name'], $upload_path)) {
                    $resume = $new_filename;
                }
            }
        }
        
        // Debug: Check what we're trying to save
        error_log("Saving profile data - Course: '$course', Year: '$year_graduated', User: '$userCode'");
        
        if ($grad_info) {
            // Update existing record
            $update_query = "UPDATE graduate_information SET first_name=?, middle_name=?, last_name=?, phone_number=?, course=?, year_graduated=?, skills=?, linkedin_profile=?, resume=? WHERE user_id=?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("sssssissss", $first_name, $middle_name, $last_name, $phone_number, $course, $year_graduated, $skills, $linkedin_profile, $resume, $userCode);
            $result = $update_stmt->execute();
            error_log("Update result: " . ($result ? 'success' : 'failed: ' . $update_stmt->error));
        } else {
            // Insert new record
            $insert_query = "INSERT INTO graduate_information (user_id, first_name, middle_name, last_name, phone_number, course, year_graduated, skills, linkedin_profile, resume) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ssssssssss", $userCode, $first_name, $middle_name, $last_name, $phone_number, $course, $year_graduated, $skills, $linkedin_profile, $resume);
            $result = $insert_stmt->execute();
            error_log("Insert result: " . ($result ? 'success' : 'failed: ' . $insert_stmt->error));
        }
        
        // Refresh graduate information
        $grad_stmt->execute();
        $grad_result = $grad_stmt->get_result();
        $grad_info = $grad_result->fetch_assoc();
        $is_profile_complete = true;
        
        // Debug: Check what was retrieved
        error_log("Retrieved profile data - Course: '" . ($grad_info['course'] ?? 'NULL') . "', Year: '" . ($grad_info['year_graduated'] ?? 'NULL') . "'");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application - <?php echo htmlspecialchars($job['job_title']); ?> - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/employer.css">
    <link rel="stylesheet" href="assets/css/company.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="stylesheet" href="assets/css/text.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .application-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .application-header {
            background: linear-gradient(135deg, #08135c, #0077ff);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .application-header h1 {
            margin: 0 0 1rem 0;
            font-size: 2rem;
        }
        
        .job-details {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 1rem;
        }
        
        .job-details h3 {
            margin: 0 0 0.5rem 0;
            color: #fff;
        }
        
        .job-details p {
            margin: 0.25rem 0;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .application-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .card-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-title i {
            color: var(--primary-color);
        }
        
        .profile-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .required {
            color: #dc3545;
        }
        
        .question-card {
            background: #f8f9fa;
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
        }
        
        .question-number {
            background: var(--primary-color);
            color: white;
            width: 18px !important;
            height: 18px !important;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.7rem;
            margin-right: 0.75rem;
            flex-shrink: 0 !important;
            flex: none !important;
        }
        
        .question-text {
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
        }
        
        .question-text span {
            flex: 1;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            background: var(--primary-border);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }
        
        .alert-info {
            background: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
        }
        
        .file-upload {
            position: relative;
            display: inline-block;
            cursor: pointer;
            width: 100%;
        }
        
        .file-upload input[type=file] {
            position: absolute;
            left: -9999px;
        }
        
        .file-upload-label {
            display: block;
            padding: 0.75rem;
            border: 2px dashed #e0e0e0;
            border-radius: 8px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            border-color: var(--primary-color);
            background: #f0f8ff;
        }
        
        @media (max-width: 768px) {
            .profile-section {
                grid-template-columns: 1fr;
            }
            
            .application-header {
                padding: 1.5rem;
            }
            
            .application-header h1 {
                font-size: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <?php include_once('includes/header.php') ?>

    <main>
        <div class="application-container">
            <!-- Job Header -->
            <div class="application-header">
                <h1><i class="fas fa-briefcase"></i> Job Application</h1>
                <div class="job-details">
                    <h3><?php echo htmlspecialchars($job['job_title']); ?></h3>
                    <p><i class="fas fa-building"></i> <?php echo htmlspecialchars($job['company_name']); ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['company_location']); ?></p>
                    <p><i class="fas fa-clock"></i> <?php echo htmlspecialchars($job['job_type_shift']); ?></p>
                    <p><i class="fas fa-money-bill"></i> <?php echo htmlspecialchars($job['salary_range']); ?></p>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!$is_profile_complete): ?>
                <!-- Profile Completion Required -->
                <div class="application-card">
                    <h2 class="card-title">
                        <i class="fas fa-user-edit"></i> Complete Your Profile
                    </h2>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i> 
                        Please complete your profile information before proceeding with the application. 
                        All fields marked with <span class="required">*</span> are required.
                    </div>

                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="profile-section">
                            <div class="form-group">
                                <label for="first_name">First Name <span class="required">*</span></label>
                                <input type="text" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($grad_info['first_name'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" 
                                       value="<?php echo htmlspecialchars($grad_info['middle_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name">Last Name <span class="required">*</span></label>
                                <input type="text" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($grad_info['last_name'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone_number">Phone Number <span class="required">*</span></label>
                                <input type="tel" id="phone_number" name="phone_number" 
                                       value="<?php echo htmlspecialchars($grad_info['phone_number'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="course">Course <span class="required">*</span></label>
                                <input type="text" id="course" name="course" 
                                       value="<?php echo htmlspecialchars($grad_info['course'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="year_graduated">Year Graduated <span class="required">*</span></label>
                                <input type="number" id="year_graduated" name="year_graduated" 
                                       value="<?php echo htmlspecialchars($grad_info['year_graduated'] ?? ''); ?>" 
                                       min="1950" max="<?php echo date('Y'); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="skills">Skills</label>
                            <textarea id="skills" name="skills" placeholder="Describe your skills and expertise..."><?php echo htmlspecialchars($grad_info['skills'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="linkedin_profile">LinkedIn Profile</label>
                            <input type="url" id="linkedin_profile" name="linkedin_profile" 
                                   value="<?php echo htmlspecialchars($grad_info['linkedin_profile'] ?? ''); ?>"
                                   placeholder="https://linkedin.com/in/yourprofile">
                        </div>
                        
                        <div class="form-group">
                            <label for="resume">Resume <span class="required">*</span></label>
                            <?php if (!empty($grad_info['resume'])): ?>
                                <div style="margin-bottom: 1rem;">
                                    <p style="margin: 0.5rem 0; color: #666;">Current Resume:</p>
                                    <a href="assets/resumes/<?php echo htmlspecialchars($grad_info['resume']); ?>" 
                                       target="_blank" 
                                       style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">
                                        <i class="fas fa-eye"></i> View Current Resume
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="file-upload">
                                <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx">
                                <label for="resume" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <?php if (!empty($grad_info['resume'])): ?>
                                        Upload New Resume (Optional)
                                    <?php else: ?>
                                        Click to upload resume (PDF, DOC, DOCX)
                                    <?php endif; ?>
                                </label>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="button" class="btn-secondary" onclick="window.location.href='index.php'">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Save & Continue
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- Application Questions -->
                <div class="application-card">
                    <h2 class="card-title">
                        <i class="fas fa-user-check"></i> Profile Confirmation
                    </h2>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Please review your profile information below. If everything is correct, proceed to answer the application questions.
                    </div>
                    
                    <div class="profile-section" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                        <div>
                            <h4>Personal Information</h4>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($grad_info['first_name'] . ' ' . $grad_info['middle_name'] . ' ' . $grad_info['last_name']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($grad_info['phone_number']); ?></p>
                            <p><strong>Course:</strong> <?php echo htmlspecialchars($grad_info['course']); ?></p>
                            <p><strong>Year Graduated:</strong> <?php echo htmlspecialchars($grad_info['year_graduated']); ?></p>
                        </div>
                        <div>
                            <h4>Additional Information</h4>
                            <p><strong>Resume:</strong> 
                                <?php if (!empty($grad_info['resume'])): ?>
                                    <a href="assets/resumes/<?php echo htmlspecialchars($grad_info['resume']); ?>" 
                                       target="_blank" 
                                       style="color: #007bff; text-decoration: none;">
                                        <i class="fas fa-eye"></i> View Resume
                                    </a>
                                <?php else: ?>
                                    Not uploaded
                                <?php endif; ?>
                            </p>
                            <?php if (!empty($grad_info['linkedin_profile'])): ?>
                                <p><strong>LinkedIn:</strong> <a href="<?php echo htmlspecialchars($grad_info['linkedin_profile']); ?>" target="_blank">View Profile</a></p>
                            <?php endif; ?>
                            <?php if (!empty($grad_info['skills'])): ?>
                                <p><strong>Skills:</strong> <?php echo htmlspecialchars($grad_info['skills']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <form method="POST" id="applicationForm" action="action/apply-job-handler.php">
                    <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">
                    
                    <div class="application-card">
                        <h2 class="card-title">
                            <i class="fas fa-question-circle"></i> Application Questions
                        </h2>
                        
                        <?php if (empty($questions)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No additional questions for this position.
                            </div>
                        <?php else: ?>
                            <?php foreach ($questions as $index => $question): ?>
                                <div class="question-card">
                                    <div class="question-text">
                                        <span class="question-number"><?php echo $index + 1; ?></span>
                                        <span><?php echo htmlspecialchars($question['question_text']); ?><?php if ($question['is_required']) echo '<span class="required">*</span>'; ?></span>
                                    </div>
                                    <textarea name="answer_<?php echo $question['question_id']; ?>" 
                                              <?php echo $question['is_required'] ? 'required' : ''; ?>
                                              placeholder="Type your answer here..."
                                              rows="4"></textarea>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="application-card">
                        <h2 class="card-title">
                            <i class="fas fa-comment"></i> Message to Employer
                        </h2>
                        
                        <div class="form-group">
                            <label for="message_to_employer">Additional Message (Optional)</label>
                            <textarea id="message_to_employer" name="message_to_employer" 
                                      placeholder="Add any additional information you'd like the employer to know..."
                                      rows="4"></textarea>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="button" class="btn-secondary" onclick="window.location.href='index.php'">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <?php include_once('includes/footer.php') ?>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // File upload preview
        const resumeInput = document.getElementById('resume');
        if (resumeInput) {
            resumeInput.addEventListener('change', function(e) {
                const label = document.querySelector('.file-upload-label');
                const fileName = e.target.files[0]?.name || 'Click to upload resume (PDF, DOC, DOCX)';
                label.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> ' + fileName;
            });
        }

        // Form validation
        const applicationForm = document.getElementById('applicationForm');
        if (applicationForm) {
            applicationForm.addEventListener('submit', function(e) {
                const requiredTextareas = this.querySelectorAll('textarea[required]');
                let isValid = true;
                
                requiredTextareas.forEach(textarea => {
                    if (textarea.value.trim() === '') {
                        isValid = false;
                        textarea.style.borderColor = '#dc3545';
                    } else {
                        textarea.style.borderColor = '#e0e0e0';
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Required Fields Missing',
                        text: 'Please answer all required questions before submitting.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Show confirmation
                    e.preventDefault();
                    Swal.fire({
                        icon: 'question',
                        title: 'Submit Application?',
                        text: 'Are you sure you want to submit your application? You cannot modify your answers after submission.',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Submit',
                        cancelButtonText: 'Review Again'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Submitting Application',
                                html: 'Please wait while we submit your application and send confirmation email...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            // Submit the form
                            this.submit();
                        }
                    });
                }
            });
        }

        // SweetAlert notifications
        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $_SESSION['success']; ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo $_SESSION['error']; ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
