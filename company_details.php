<?php include_once('includes/db_connect.php') ?>
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
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    
    <style>
        
    </style>
</head>
<body>
    <?php include_once('includes/header.php'); ?>

    <main>
        <div class="company-details-container">
            <!-- Back Button -->
            <a href="index.php" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Home
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
                                <p class="job-description-text"><?php echo htmlspecialchars(substr($job['job_description'], 0, 150) . (strlen($job['job_description']) > 150 ? '...' : '')); ?></p>
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

    <?php include_once('includes/footer.php'); ?>

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
            alert('Apply for job ID: ' + jobId);
            // window.location.href = 'apply.php?job_id=' + jobId;
        }

        function saveJob(jobId) {
            alert('Job saved! ID: ' + jobId);
        }
    </script>
</body>
</html>