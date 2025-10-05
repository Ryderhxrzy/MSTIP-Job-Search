<?php 
include_once('includes/db_connect.php');

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Details - MSTIP Job Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/company.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <?php include_once('includes/header.php'); ?>

    <main>
        <div class="company-details-container">
            <!-- Back Button -->
            <a href="javascript:history.back()" class="back-button">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <?php
            // Get company ID from URL
            $company_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            
            if ($company_id > 0) {
                // Fetch company details
                $companyQuery = "SELECT * FROM companies WHERE company_id = ?";
                $stmt = mysqli_prepare($conn, $companyQuery);
                mysqli_stmt_bind_param($stmt, "i", $company_id);
                mysqli_stmt_execute($stmt);
                $companyResult = mysqli_stmt_get_result($stmt);
                $company = mysqli_fetch_assoc($companyResult);
                
                if ($company) {
                    $coverImagePath = !empty($company['cover_image']) ? 'assets/images/' . $company['cover_image'] : 'assets/images/default-cover.jpg';
                    // Fetch company jobs
                    $jobsQuery = "SELECT * FROM job_listings WHERE company_id = ? AND status = 'Open' ORDER BY posted_date DESC";
                    $stmt = mysqli_prepare($conn, $jobsQuery);
                    mysqli_stmt_bind_param($stmt, "i", $company_id);
                    mysqli_stmt_execute($stmt);
                    $jobsResult = mysqli_stmt_get_result($stmt);
                    $jobs = mysqli_fetch_all($jobsResult, MYSQLI_ASSOC);
                    
                    // Count job types
                    $totalJobs = count($jobs);
                    $deafJobs = 0;
                    $normalJobs = 0;
                    
                    foreach ($jobs as $job) {
                        if ($job['job_category'] === 'deaf') {
                            $deafJobs++;
                        } else {
                            $normalJobs++;
                        }
                    }
                    
                    $logoPath = !empty($company['profile_picture']) ? 'assets/images/' . $company['profile_picture'] : 'assets/images/background1.jpeg';
            ?>
            
            <!-- Tabs Navigation -->
            <div class="company-tabs">
                <button class="tab-button active" onclick="switchTab('overview')">Company Overview</button>
                <button class="tab-button" onclick="switchTab('jobs')">Jobs (<?php echo $totalJobs; ?>)</button>
            </div>

            <!-- Company Overview Tab -->
            <div id="overview-tab" class="tab-content active">
                <!-- Company Header -->
                <div class="company-header">
    <div class="company-header-background" style="background-image: url('<?php echo $coverImagePath; ?>');"></div>
    <div class="company-header-content">
        <div class="company-main-info">
            <img src="<?php echo $logoPath; ?>" alt="<?php echo htmlspecialchars($company['company_name']); ?>" class="company-logo-xxl" onerror="this.src='assets/images/background1.jpeg'">
            <div class="company-title-section">
                <h1><?php echo htmlspecialchars($company['company_name']); ?></h1>
                <p class="company-industry"><?php echo htmlspecialchars($company['industry']); ?></p>
                <div class="company-rating">
                    <span>5.0</span>
                    <i class="fas fa-star"></i>
                    <span>(120 reviews)</span>
                </div>
            </div>
        </div>
        
        <div class="company-stats">
            <div class="stat-card">
                <span class="stat-number"><?php echo $totalJobs; ?></span>
                <span class="stat-label">Total Jobs</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $normalJobs; ?></span>
                <span class="stat-label">Normal Jobs</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $deafJobs; ?></span>
                <span class="stat-label">Deaf-Friendly Jobs</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">50+</span>
                <span class="stat-label">Employees</span>
            </div>
        </div>
    </div>
</div>

                <!-- Company Details Grid -->
                <div class="company-details-grid">
                    <!-- Left Column - Company Information -->
                    <div class="company-info-section">
                        <h2 class="section-title">Company Information</h2>
                        
                        <div class="info-item">
                            <i class="fas fa-industry"></i>
                            <div class="info-content">
                                <div class="info-label">Industry</div>
                                <div class="info-value"><?php echo htmlspecialchars($company['industry']); ?></div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="info-content">
                                <div class="info-label">Location</div>
                                <div class="info-value"><?php echo htmlspecialchars($company['location']); ?></div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div class="info-content">
                                <div class="info-label">Founded</div>
                                <div class="info-value"><?php echo !empty($company['founded_year']) ? htmlspecialchars($company['founded_year']) : 'Information not available'; ?></div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-users"></i>
                            <div class="info-content">
                                <div class="info-label">Company Size</div>
                                <div class="info-value"><?php echo !empty($company['company_size']) ? htmlspecialchars($company['company_size']) : 'Information not available'; ?></div>
                            </div>
                        </div>

                        <h3 class="section-title" style="margin-top: 1.5rem;">About Us</h3>
                        <div class="company-description">
                            <?php echo !empty($company['about_company']) ? nl2br(htmlspecialchars($company['about_company'])) : 'No company description available.'; ?>
                        </div>
                    </div>

                    <!-- Right Column - Contact & Additional Info -->
                    <div class="company-contact-section">
                        <h2 class="section-title">Contact Information</h2>
                        
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <div class="info-content">
                                <div class="info-label">Email</div>
                                <div class="info-value"><?php echo !empty($company['email_address']) ? htmlspecialchars($company['email_address']) : 'contact@company.com'; ?></div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <div class="info-content">
                                <div class="info-label">Phone</div>
                                <div class="info-value"><?php echo !empty($company['contact_number']) ? htmlspecialchars($company['contact_number']) : 'Not available'; ?></div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-globe"></i>
                            <div class="info-content">
                                <div class="info-label">Website</div>
                                <div class="info-value">
                                    <?php if (!empty($company['website'])): ?>
                                        <a href="<?php echo htmlspecialchars($company['website']); ?>" target="_blank">Visit Website</a>
                                    <?php else: ?>
                                        Not available
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <h3 class="section-title" style="margin-top: 1.5rem;">Company Culture</h3>
                        <div class="culture-tags">
                            <span class="culture-tag">Professional</span>
                            <span class="culture-tag">Innovative</span>
                            <span class="culture-tag">Inclusive</span>
                            <span class="culture-tag">Growth-Oriented</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jobs Tab -->
            <div id="jobs-tab" class="tab-content">
                <div class="jobs-container">
                    <div class="jobs-header">
                        <h2 class="section-title">Available Positions</h2>
                        <div class="jobs-count"><?php echo $totalJobs; ?> open positions</div>
                    </div>

                    <!-- Job Filters -->
                    <div class="job-filters">
                        <select class="filter-select" onchange="filterJobs()">
                            <option value="all">All Jobs</option>
                            <option value="normal">Normal Jobs</option>
                            <option value="deaf">Deaf-Friendly Jobs</option>
                        </select>
                        
                        <select class="filter-select" onchange="sortJobs()">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="title">Job Title A-Z</option>
                        </select>
                    </div>

                    <!-- Jobs List -->
                    <div class="job-listings-grid" id="jobs-list">
                        <?php
                        if (count($jobs) > 0) {
                            foreach ($jobs as $job) {
                                $isDeafJob = $job['job_category'] === 'deaf';
                                $categoryBadgeClass = $isDeafJob ? 'job-category-badge deaf' : 'job-category-badge normal';
                                $categoryIcon = $isDeafJob ? 'fa-deaf' : 'fa-user';
                                $categoryLabel = $isDeafJob ? 'Deaf-Friendly' : 'Open to All';
                                
                                // Format dates
                                $postedDate = new DateTime($job['posted_date']);
                                $now = new DateTime();
                                $daysAgo = $now->diff($postedDate)->days;
                                $timeAgo = $daysAgo == 0 ? 'Today' : ($daysAgo == 1 ? 'Yesterday' : $daysAgo . ' days ago');
                                
                                $deadline = 'Not specified';
                                if (!empty($job['application_deadline'])) {
                                    $deadlineDate = new DateTime($job['application_deadline']);
                                    $deadline = $deadlineDate->format('F d, Y');
                                }
                        ?>
                        <div class="job-listing-card" data-category="<?php echo $job['job_category']; ?>">
                            <div class="job-card-header">
                                <div class="job-title-wrapper">
                                    <h4 class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></h4>
                                    <span class="<?php echo $categoryBadgeClass; ?>">
                                        <i class="fas <?php echo $categoryIcon; ?>"></i><?php echo $categoryLabel; ?>
                                    </span>
                                </div>
                                <div class="job-posted-date">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo $timeAgo; ?></span>
                                </div>
                            </div>

                            <div class="job-basic-info">
                                <div class="job-meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo htmlspecialchars($company['location']); ?></span>
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
                                <button class="btn-save" onclick="saveJob(<?php echo $job['job_id']; ?>)">
                                    <i class="fas fa-bookmark"></i>Save
                                </button>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                            echo '<div class="no-jobs-message">
                                <i class="fas fa-briefcase"></i>
                                <p>No active job listings at the moment.</p>
                                <small>Check back later for new opportunities</small>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <?php
                } else {
                    echo '<div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h2>Company Not Found</h2>
                        <p>The company you are looking for does not exist.</p>
                        <a href="index.php" class="back-button">Return to Home</a>
                    </div>';
                }
            } else {
                echo '<div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h2>Invalid Company ID</h2>
                    <p>Please provide a valid company ID.</p>
                    <a href="index.php" class="back-button">Return to Home</a>
                </div>';
            }
            ?>
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

    <?php include_once('includes/footer.php'); ?>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Tab switching functionality
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Add active class to clicked tab button
            event.target.classList.add('active');
        }

        // Job filtering functionality
        function filterJobs() {
            const filterValue = document.querySelector('.filter-select').value;
            const jobCards = document.querySelectorAll('.job-listing-card');
            
            jobCards.forEach(card => {
                if (filterValue === 'all' || card.dataset.category === filterValue) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Job sorting functionality
        function sortJobs() {
            const sortValue = document.querySelectorAll('.filter-select')[1].value;
            const jobsContainer = document.getElementById('jobs-list');
            const jobCards = Array.from(document.querySelectorAll('.job-listing-card'));
            
            jobCards.sort((a, b) => {
                const titleA = a.querySelector('.job-title').textContent.toLowerCase();
                const titleB = b.querySelector('.job-title').textContent.toLowerCase();
                const dateA = a.querySelector('.job-posted-date span').textContent;
                const dateB = b.querySelector('.job-posted-date span').textContent;
                
                switch(sortValue) {
                    case 'title':
                        return titleA.localeCompare(titleB);
                    case 'newest':
                        return dateB.localeCompare(dateA);
                    case 'oldest':
                        return dateA.localeCompare(dateB);
                    default:
                        return 0;
                }
            });
            
            // Clear and re-append sorted jobs
            jobsContainer.innerHTML = '';
            jobCards.forEach(card => jobsContainer.appendChild(card));
        }

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

        function saveJob(jobId) {
            // Check if user is logged in
            <?php if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'Graduate'): ?>
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Required',
                    text: 'Please login as a graduate to save jobs.',
                    confirmButtonText: 'OK'
                });
                return;
            <?php endif; ?>

            // Send AJAX request to save job
            fetch('action/save-job-handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'job_id=' + jobId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: data.message,
                        confirmButtonText: 'View Saved Jobs',
                        showCancelButton: true,
                        cancelButtonText: 'Continue Browsing'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'save-jobs.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>
</body>
</html>