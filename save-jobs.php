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
$show_success = false;
$show_error = false;
$success_msg = '';
$error_msg = '';

if (isset($_SESSION['save_success'])) {
    $show_success = true;
    $success_msg = $_SESSION['save_success'];
    unset($_SESSION['save_success']);
}

if (isset($_SESSION['save_error'])) {
    $show_error = true;
    $error_msg = $_SESSION['save_error'];
    unset($_SESSION['save_error']);
}

// Handle unsave job
if (isset($_GET['unsave']) && !empty($_GET['unsave'])) {
    $saved_id = intval($_GET['unsave']);
    
    $delete_stmt = $conn->prepare("DELETE FROM saved_jobs WHERE saved_id = ? AND user_id = ?");
    $delete_stmt->bind_param("is", $saved_id, $userCode);
    
    if ($delete_stmt->execute()) {
        $_SESSION['save_success'] = "Job removed from saved list.";
    } else {
        $_SESSION['save_error'] = "Error removing job from saved list.";
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch saved jobs with job and company details
$query = "SELECT sj.*, jl.*, c.company_name, c.location as company_location, c.profile_picture as company_logo
          FROM saved_jobs sj
          JOIN job_listings jl ON sj.job_id = jl.job_id
          JOIN companies c ON jl.company_id = c.company_id
          WHERE sj.user_id = ?
          ORDER BY sj.saved_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userCode);
$stmt->execute();
$result = $stmt->get_result();
$savedJobs = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/employer.css">
    <link rel="stylesheet" href="assets/css/company.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Remove Button (Unsave) */
.btn-unsave {
    padding: 0.75rem 1.5rem;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-unsave:hover {
    background: #c82333;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.btn-unsave:active {
    transform: translateY(0);
}

.btn-unsave i {
    font-size: 0.9rem;
}

/* View Company Button */
.btn-view {
    padding: 0.75rem 1.5rem;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-view:hover {
    background: #5a6268;
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

.btn-view:active {
    transform: translateY(0);
}

.btn-view i {
    font-size: 0.9rem;
}

.saved-date {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.85rem;
    color: #666;
    margin-top: 0.25rem;
}

.saved-date i {
    color: var(--accent-color);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-unsave,
    .btn-view {
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
    }
    
    .job-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .job-actions button {
        width: 100%;
    }
}
    </style>
</head>

<body>
    <?php include_once('includes/header.php') ?>

    <main>
        <section class="advice-hero">
            <div class="container">
                <h1 class="section-titles">Saved Jobs</h1>
                <p class="section-subtitles">View and manage the jobs you have saved.</p>
            </div>
            <!-- Decorative pattern behind hero -->
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>
        <div class="profile-container">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-bookmark"></i> Saved Jobs</h2>
                    
                    <p style="margin-top: 0.5rem; color: #666; font-size: 0.9rem;">
                        Jobs you've saved for later review (<?php echo count($savedJobs); ?>)
                    </p>
                    
                </div>
                <div class="card-body">
                    <?php if (count($savedJobs) > 0): ?>
                        <div class="job-listings-grid">
                            <?php foreach ($savedJobs as $job): 
                                $isDeafJob = $job['job_category'] === 'deaf';
                                $categoryBadgeClass = $isDeafJob ? 'job-category-badge deaf' : 'job-category-badge normal';
                                $categoryIcon = $isDeafJob ? 'fa-deaf' : 'fa-user';
                                $categoryLabel = $isDeafJob ? 'Deaf-Friendly' : 'Open to All';
                                
                                // Format dates
                                $savedDate = new DateTime($job['saved_date']);
                                $savedDateFormatted = $savedDate->format('F d, Y');
                                
                                $postedDate = new DateTime($job['posted_date']);
                                $now = new DateTime();
                                $daysAgo = $now->diff($postedDate)->days;
                                $timeAgo = $daysAgo == 0 ? 'Today' : ($daysAgo == 1 ? 'Yesterday' : $daysAgo . ' days ago');
                                
                                $deadline = 'Not specified';
                                if (!empty($job['application_deadline'])) {
                                    $deadlineDate = new DateTime($job['application_deadline']);
                                    $deadline = $deadlineDate->format('F d, Y');
                                }
                                
                                $logoPath = !empty($job['company_logo']) ? 'assets/images/' . $job['company_logo'] : 'assets/images/background1.jpeg';
                            ?>
                            <div class="job-listing-card">
                                <!-- Company Header -->
                                <div class="job-company-header">
                                    <img src="<?php echo $logoPath; ?>" alt="<?php echo htmlspecialchars($job['company_name']); ?>" class="company-logo-small" onerror="this.src='assets/images/background1.jpeg'">
                                    <div class="company-info">
                                        <h5 class="company-name"><?php echo htmlspecialchars($job['company_name']); ?></h5>
                                        <span class="saved-date">
                                            <i class="fas fa-bookmark"></i> Saved on <?php echo $savedDateFormatted; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="job-card-header">
                                    <div class="job-title-wrapper">
                                        <h4 class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></h4>
                                        <span class="<?php echo $categoryBadgeClass; ?>">
                                            <i class="fas <?php echo $categoryIcon; ?>"></i><?php echo $categoryLabel; ?>
                                        </span>
                                    </div>
                                    <div class="job-posted-date">
                                        <i class="fas fa-clock"></i>
                                        <span>Posted <?php echo $timeAgo; ?></span>
                                    </div>
                                </div>

                                <div class="job-basic-info">
                                    <div class="job-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo htmlspecialchars($job['company_location']); ?></span>
                                    </div>
                                    <div class="job-meta-item">
                                        <i class="fas fa-briefcase"></i>
                                        <span><?php echo htmlspecialchars($job['job_type_shift']); ?></span>
                                    </div>
                                </div>

                                <div class="job-details-grid">
                                    <div class="detail-item">
                                        <i class="fas fa-layer-group"></i>
                                        <div class="detail-content">
                                            <span class="detail-label">Position</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($job['job_position']); ?></span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-dollar-sign"></i>
                                        <div class="detail-content">
                                            <span class="detail-label">Salary</span>
                                            <span class="detail-value"><?php echo !empty($job['salary_range']) ? htmlspecialchars($job['salary_range']) : 'Negotiable'; ?></span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-users"></i>
                                        <div class="detail-content">
                                            <span class="detail-label">Slots</span>
                                            <span class="detail-value"><?php echo $job['slots_available']; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="job-description-section">
                                    <h5>Job Description</h5>
                                    <p class="job-description-text"><?php echo htmlspecialchars($job['job_description']); ?></p>
                                </div>

                                <div class="job-description-section">
                                    <h5>Qualification</h5>
                                    <p class="job-description-text"><?php echo htmlspecialchars($job['qualifications']); ?></p>
                                </div>

                                <div class="job-deadline-contact">
                                    <div class="deadline-info">
                                        <i class="fas fa-calendar-alt"></i>
                                        <div>
                                            <span class="deadline-label">Application Deadline</span>
                                            <span class="deadline-date"><?php echo $deadline; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="job-actions">
                                    <button class="btn-apply" onclick="applyJob(<?php echo $job['job_id']; ?>)">
                                        <i class="fas fa-paper-plane"></i>Apply Now
                                    </button>
                                    <button class="btn-unsave" onclick="confirmUnsave(<?php echo $job['saved_id']; ?>)">
                                        <i class="fas fa-trash"></i>Remove
                                    </button>
                                    <button class="btn-view" onclick="window.location.href='company-details.php?id=<?php echo $job['company_id']; ?>'">
                                        <i class="fas fa-eye"></i>View Company
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-jobs-message" style="text-align: center; padding: 3rem 1rem;">
                            <i class="fas fa-bookmark" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
                            <h3 style="color: #666; margin-bottom: 0.5rem;">No Saved Jobs</h3>
                            <p style="color: #999; margin-bottom: 1.5rem;">You haven't saved any jobs yet. Browse available positions and save the ones you're interested in.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <div id="applicationModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        
        <div class="modal-header">
            <h2><i class="fas fa-file-alt"></i> Confirm Job Application</h2>
            <p>Please review your information before submitting your application</p>
        </div>
        
        <div class="graduate-info-card">
            <h3><i class="fas fa-user-graduate"></i> Applicant Information</h3>
            <div class="info-grid" id="graduateInfo">
                <!-- Graduate information will be populated here -->
            </div>
        </div>
        
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <button id="confirmApplyBtn" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> &nbsp;Submit Application
            </button>
        </div>
    </div>
</div>

    <?php include_once('includes/footer.php') ?>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmUnsave(savedId) {
            Swal.fire({
                title: 'Remove Saved Job?',
                text: "Are you sure you want to remove this job from your saved list?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?unsave=' + savedId;
                }
            });
        }

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

        function applyJob(jobId) {
    <?php if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'Graduate'): ?>
        Swal.fire({
            icon: 'warning',
            title: 'Login Required',
            text: 'Please login as a graduate to apply.',
            confirmButtonText: 'OK'
        });
        return;
    <?php endif; ?>

    // Fetch graduate info via AJAX
    fetch('action/fetch-graduate-info.php')
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let infoHtml = `
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">${data.first_name} ${data.middle_name ?? ''} ${data.last_name}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone Number</span>
                    <span class="info-value">${data.phone_number}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Course</span>
                    <span class="info-value">${data.course}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Year Graduated</span>
                    <span class="info-value">${data.year_graduated}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Skills</span>
                    <span class="info-value">${data.skills ?? 'N/A'}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">LinkedIn Profile</span>
                    <span class="info-value">
                        ${data.linkedin_profile ? 
                            `<a href="${data.linkedin_profile}" target="_blank">View Profile</a>` : 
                            'Not provided'
                        }
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Resume</span>
                    <span class="info-value">
                        ${data.resume ? 
                            `<a href="assets/resumes/${data.resume}" target="_blank">View Resume</a>` : 
                            'Not uploaded'
                        }
                    </span>
                </div>
            `;
            document.getElementById('graduateInfo').innerHTML = infoHtml;
            
            // Show modal - make sure it's visible first, then add active class
            const modal = document.getElementById('applicationModal');
            modal.style.display = 'flex';
            
            // Use setTimeout to ensure the display change has taken effect before adding active class
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
            
            // Attach jobId to confirm button
            document.getElementById('confirmApplyBtn').onclick = function() {
                confirmApplication(jobId);
            };
        } else {
            Swal.fire("Error", "Unable to fetch your information.", "error");
        }
    });
}

function closeModal() {
    const modal = document.getElementById('applicationModal');
    
    // Remove active class first for animation
    modal.classList.remove('active');
    
    // Wait for animation to complete before hiding
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

function confirmApplication(jobId) {
    fetch('action/apply-job-handler.php', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'job_id=' + jobId
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        return res.json();
    })
    .then(data => {
        closeModal();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Application Submitted!',
                text: data.message,
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Application Failed',
                text: data.message,
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        closeModal();
        Swal.fire({
            icon: 'error',
            title: 'Application Error',
            text: 'Something went wrong. Please try again.',
            confirmButtonText: 'OK'
        });
    });
}

// Close modal when clicking outside content
document.getElementById('applicationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Add event listener for Escape key to close modal
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('applicationModal');
    if (e.key === 'Escape' && modal.classList.contains('active')) {
        closeModal();
    }
});
    </script>
</body>
</html>