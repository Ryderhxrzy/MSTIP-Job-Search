<?php
include_once('../../includes/db_connect.php');
session_start();

// Check if user is logged in as Employer
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'Employer') {
    header("Location: ../../employer-login.php");
    exit();
}

$userCode = $_SESSION['user_code'];

// Check for session messages
$show_success = false;
$show_error = false;
$success_msg = $error_msg = '';

if (isset($_SESSION['job_success'])) {
    $show_success = true;
    $success_msg = $_SESSION['job_success'];
    unset($_SESSION['job_success']);
}

if (isset($_SESSION['job_error'])) {
    $show_error = true;
    $error_msg = $_SESSION['job_error'];
    unset($_SESSION['job_error']);
}

// Get company_id and fetch jobs
$company_id = null;
$stmt = $conn->prepare("SELECT company_id FROM companies WHERE user_id = ?");
$stmt->bind_param("s", $userCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $company = $result->fetch_assoc();
    $company_id = $company['company_id'];
    
    // Fetch job listings
    $jobs_stmt = $conn->prepare("SELECT * FROM job_listings WHERE company_id = ? ORDER BY posted_date DESC");
    $jobs_stmt->bind_param("i", $company_id);
    $jobs_stmt->execute();
    $jobs = $jobs_stmt->get_result();
} else {
    $jobs = [];
}

// Handle job deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    // Verify that the job belongs to the employer's company
    $verify_stmt = $conn->prepare("SELECT j.* FROM job_listings j 
                                  JOIN companies c ON j.company_id = c.company_id 
                                  WHERE j.job_id = ? AND c.user_id = ?");
    $verify_stmt->bind_param("is", $delete_id, $userCode);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();
    
    if ($verify_result->num_rows > 0) {
        $delete_stmt = $conn->prepare("DELETE FROM job_listings WHERE job_id = ?");
        $delete_stmt->bind_param("i", $delete_id);
        
        if ($delete_stmt->execute()) {
            $_SESSION['job_success'] = "Job has been deleted successfully.";
        } else {
            $_SESSION['job_error'] = "Error deleting job. Please try again.";
        }
    } else {
        $_SESSION['job_error'] = "Job not found or you don't have permission to delete it.";
    }
    
    header("Location: manage-jobs.php");
    exit();
}

// Handle job status update
if (isset($_GET['toggle_status'])) {
    $job_id = intval($_GET['toggle_status']);
    
    // Verify that the job belongs to the employer's company
    $verify_stmt = $conn->prepare("SELECT j.* FROM job_listings j 
                                  JOIN companies c ON j.company_id = c.company_id 
                                  WHERE j.job_id = ? AND c.user_id = ?");
    $verify_stmt->bind_param("is", $job_id, $userCode);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();
    
    if ($verify_result->num_rows > 0) {
        $job = $verify_result->fetch_assoc();
        $new_status = $job['status'] == 'Open' ? 'Closed' : 'Open';
        
        $update_stmt = $conn->prepare("UPDATE job_listings SET status = ? WHERE job_id = ?");
        $update_stmt->bind_param("si", $new_status, $job_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['job_success'] = "Job status updated to {$new_status}.";
        } else {
            $_SESSION['job_error'] = "Error updating job status. Please try again.";
        }
    } else {
        $_SESSION['job_error'] = "Job not found or you don't have permission to update it.";
    }
    
    header("Location: manage-jobs.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - MSTIP Seek Employee</title>
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
                <h1 class="section-titles">Manage Jobs</h1>
                <p class="section-subtitle">View, edit, and track all your active and archived job postings in one place.</p>
            </div>
            <!-- Decorative pattern behind hero -->
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>
        <div class="jobs-container">
            <div class="card">
                <div class="card-headers">
                    <h2><i class="fas fa-briefcase"></i> Manage Job Listings</h2>
                    <div class="header-actions">
                        <a href="post-a-job.php" class="btns btn-primary" style="margin-left: 10px;">
                            <i class="fas fa-plus"></i> Post New Job
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($jobs->num_rows > 0): ?>
                        <div class="jobs-grid">
                            <?php while($job = $jobs->fetch_assoc()): ?>
                                <div class="job-cards">
                                    <div class="job-header">
                                        <h3 class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></h3>
                                        <span class="job-status status-<?php echo strtolower($job['status']); ?>">
                                            <?php echo htmlspecialchars($job['status']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="job-details">
                                        <div class="job-detail">
                                            <i class="fas fa-layer-group"></i>
                                            <span><?php echo htmlspecialchars($job['job_position']); ?></span>
                                        </div>
                                        <div class="job-detail">
                                            <i class="fas fa-tag"></i>
                                            <span><?php echo htmlspecialchars($job['job_category']); ?></span>
                                        </div>
                                        <div class="job-detail">
                                            <i class="fas fa-users"></i>
                                            <span><?php echo htmlspecialchars($job['slots_available']); ?> slots available</span>
                                        </div>
                                        <div class="job-detail">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo htmlspecialchars($job['job_type_shift']); ?></span>
                                        </div>
                                        <?php if (!empty($job['salary_range'])): ?>
                                        <div class="job-detail">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <span><?php echo htmlspecialchars($job['salary_range']); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="job-detail">
                                            <i class="fas fa-calendar-day"></i>
                                            <span>Deadline: <?php echo date('M d, Y', strtotime($job['application_deadline'])); ?></span>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($job['job_description'])): ?>
                                    <div class="job-description">
                                        <?php 
                                        $description = htmlspecialchars($job['job_description']);
                                        echo strlen($description) > 150 ? substr($description, 0, 150) . '...' : $description;
                                        ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="job-actions">
                                        <a href="edit-job.php?id=<?php echo $job['job_id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="?toggle_status=<?php echo $job['job_id']; ?>" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-toggle-<?php echo $job['status'] == 'Open' ? 'on' : 'off'; ?>"></i>
                                            <?php echo $job['status'] == 'Open' ? 'Close' : 'Open'; ?>
                                        </a>
                                        <a href="view-applications.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-eye"></i> View Applications
                                        </a>
                                        <button onclick="confirmDelete(<?php echo $job['job_id']; ?>)" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-briefcase"></i>
                            <h3>No Jobs Posted Yet</h3>
                            <p>Start posting your first job to find qualified candidates.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include_once('../../includes/employer-footer.php') ?>
    <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert notifications
        <?php if ($show_success): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $success_msg; ?>',
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

        // Confirm delete function
        function confirmDelete(jobId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'manage-jobs.php?delete_id=' + jobId;
                }
            });
        }

        // Confirm status toggle
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('a[href*="toggle_status"]');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    const action = href.includes('toggle_status') ? 'open/close' : '';
                    
                    Swal.fire({
                        title: 'Update Job Status?',
                        text: `Are you sure you want to ${action} this job?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, update it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>