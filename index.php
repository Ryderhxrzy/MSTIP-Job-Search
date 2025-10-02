<?php include_once('includes/db_connect.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MSTIP Job Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    
    <style>
        /* Search Results Layout */
        .search-results-container {
            display: none;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
            gap: 2rem;
            position: relative;
        }

        .search-results-container.active {
            display: flex;
            flex-wrap: wrap;
        }

        /* Back Button */
        .back-to-search-btn {
            position: absolute;
            top: -10px;
            margin-bottom: 20px;
            left: 2rem;
            padding: 0.75rem 1.5rem;
            background: white;
            color: var(--text-primary);
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-to-search-btn:hover {
            background: var(--text-secondary);
            color: var(--text-light);
        }

        .back-to-search-btn i {
            font-size: 0.875rem;
        }

        /* Left Sidebar */
        .results-sidebar {
            width: 350px;
            flex-shrink: 0;
            background: var(--surface-color);
            border-radius: 12px;
            border: 1px solid var(--primary-border);
            overflow: hidden;
            max-height: calc(100vh - 200px);
            display: flex;
            flex-direction: column;
            margin-top: 40px;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--primary-border);
            background: #f9fafb;
        }

        .sidebar-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
        }

        .results-count {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .results-list {
            overflow-y: auto;
            flex: 1;
        }

        /* ===== COMPANY LOGO SIZES ===== */
        .company-logo-small {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .company-logo-medium {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .company-logo-large {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
        }

        /* Company List Item */
        .company-list-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--primary-border);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .company-list-item:hover {
            background: #f9fafb;
        }

        .company-list-item.selected {
            background: #eff6ff;
            border-left: 3px solid var(--primary-color);
        }

        .company-list-item .company-brief {
            flex: 1;
            min-width: 0;
        }

        .company-list-item .company-name-small {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9375rem;
            margin: 0 0 0.25rem 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .company-list-item .company-meta {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .company-list-item .company-meta i {
            font-size: 0.75rem;
        }

        /* Job List Item */
        .job-list-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--primary-border);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .job-list-item:hover {
            background: #f9fafb;
        }

        .job-list-item.selected {
            background: #eff6ff;
            border-left: 3px solid var(--primary-color);
        }

        .job-list-item .job-brief {
            flex: 1;
            min-width: 0;
        }

        .job-list-item .job-title-small {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9375rem;
            margin: 0 0 0.25rem 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .job-list-item .job-company {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .job-list-item .job-meta-small {
            font-size: 0.75rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .job-list-item .job-meta-small i {
            font-size: 0.7rem;
        }

        /* Right Panel */
        .details-panel {
            flex: 1;
            background: var(--surface-color);
            border: 1px solid var(--primary-border);
            border-radius: 12px;
            padding: 2rem;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            margin-top: 40px;
        }

        .details-panel.empty {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
        }

        .empty-state {
            text-align: center;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(text-secondary);
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        /* Company Details */
        .company-details-header {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--primary-border);
            align-items: flex-start;
        }

        .company-header-info {
            flex: 1;
        }

        .company-header-info h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
        }

        .company-header-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9375rem;
            color: var(--text-secondary);
        }

        .meta-item i {
            color: var(--primary-color);
        }

        .company-rating {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #fef3c7;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            color: #92400e;
        }

        .company-rating i {
            color: var(--accent-color);
        }

        .company-details-section {
            margin-bottom: 2rem;
        }

        .company-details-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 1rem 0;
        }

        /* Job Details */
        .job-details-header {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--primary-border);
        }

        .job-company-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .job-details-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
        }

        .job-company-large {
            font-size: 1.125rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .job-header-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .job-details-section {
            margin-bottom: 2rem;
        }

        .job-details-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 1rem 0;
        }

        /* Job Listings Grid */
        .job-listings-grid {
            display: grid;
            gap: 1rem;
        }

        .job-listing-card {
            padding: 1.25rem;
            border: 1px solid var(--primary-border);
            border-radius: 8px;
            transition: all 0.2s;
        }

        .job-listing-card:hover {
            border-color: var(--primary-color);
        }

        .job-title {
            font-size: 1.0625rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
        }

        /* Job Meta Styles */
        .job-meta-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 20px;
            margin: 0.5rem 0;
        }

        .job-meta-inline .job-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .job-meta-block {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin: 0.75rem 0;
        }

        .job-meta-block .job-meta-item {
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }

        .job-meta-item i {
            margin-top: 3px;
            color: #555;
        }

        .job-description {
            font-size: 0.9375rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .job-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn-apply {
            padding: 0.5rem 1.25rem;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-apply:hover {
            background: #2563eb;
        }

        .btn-save {
            padding: 0.5rem 1rem;
            background: white;
            color: #6b7280;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-save:hover {
            background: #f9fafb;
            color: #111827;
        }

        /* Category Badges */
        .job-category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-left: 0.5rem;
        }

        .job-category-badge.deaf {
            background: #e0f2fe;
            color: #0369a1;
        }

        .job-category-badge.normal {
            background: #f0fdf4;
            color: #166534;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .search-results-container {
                flex-direction: column;
            }
            
            .results-sidebar {
                width: 100%;
                max-height: 400px;
                margin-top: 60px;
            }
            
            .details-panel {
                max-height: none;
                margin-top: 0;
            }

            .back-to-search-btn {
                position: static;
                width: 100%;
                margin-bottom: 1rem;
                justify-content: center;
            }

            .company-details-header {
                flex-direction: column;
                text-align: center;
            }

            .company-header-meta {
                justify-content: center;
            }

            .job-company-header {
                flex-direction: column;
                text-align: center;
            }

            .job-header-meta {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include_once('includes/header.php'); ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <svg class="hero-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 400" preserveAspectRatio="none">
            <g fill="rgba(255,255,255,0.05)">
                <path d="M150 0C220 20 300 60 400 30C500 0 600 60 700 20C800 -20 900 40 950 60L950 400H0V0Z"/>
                <path d="M0 100C80 80 160 150 260 120C360 90 460 150 560 130C660 110 760 180 800 150L800 400H0V100Z"/>
                <path d="M50 200C130 220 210 250 310 220C410 190 510 250 610 210C710 170 790 260 850 220L850 400H0V200Z"/>
            </g>
            </svg>
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Find Your Dream Job Today</h1>
                    <p class="hero-subtitle">Connect with top employers and discover opportunities that match your skills and ambitions.</p>
                    <div class="search-form">
                        <div class="search-inputs">
                            <div class="search-field">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchInput" placeholder="Job title, keywords, or company">
                            </div>
                            <div class="search-field">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" id="locationInput" placeholder="City, state, or remote">
                            </div>
                            <button class="btn-primary search-btn" id="searchBtn">
                                <i class="fas fa-search"></i> Search Jobs
                            </button>
                        </div>
                    </div>

                    <div class="popular-searches">
                        <span>Popular searches:</span>
                        <a href="#" onclick="quickSearch('Remote Work'); return false;">Remote Work</a>
                        <a href="#" onclick="quickSearch('Software Engineer'); return false;">Software Engineer</a>
                        <a href="#" onclick="quickSearch('Marketing'); return false;">Marketing</a>
                        <a href="#" onclick="quickSearch('Data Analyst'); return false;">Data Analyst</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Search Results Section (Hidden by default) -->
        <section class="search-results-container" id="searchResultsContainer">
            <!-- Back Button -->
            <button class="back-to-search-btn" id="backToSearchBtn" onclick="resetSearch()">
                <i class="fas fa-arrow-left"></i> Back to Browse
            </button>
            
            <!-- Left Sidebar -->
            <div class="results-sidebar">
                <div class="sidebar-header">
                    <h3 id="sidebarTitle">Search Results</h3>
                    <p class="results-count" id="resultsCount">0 results</p>
                </div>
                <div class="results-list" id="resultsList">
                    <!-- Results will be dynamically added here -->
                </div>
            </div>

            <!-- Right Panel -->
            <div class="details-panel empty" id="detailsPanel">
                <div class="empty-state">
                    <i class="fas fa-building"></i>
                    <p>Select an item to view details</p>
                </div>
            </div>
        </section>

        <!-- Companies Hiring Section (Visible by default) -->
        <section class="companies-hiring" id="companiesHiringSection">
            <div class="container">
                <h2 class="section-title">Companies/Organization Currently Hiring</h2>
                <p class="section-subtitle">
                    Explore companies actively seeking talented professionals like you. 
                    Discover top organizations, find the right role that matches your skills, 
                    and take the next step in your career journey today.
                </p>                

                <div class="companies-pagination-wrapper">
                    <button class="pagination-btn prev" id="prevBtn">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <div class="companies-grid" id="companiesGrid">
                        <!-- Companies will be loaded here by JavaScript -->
                    </div>

                    <button class="pagination-btn next" id="nextBtn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="pagination">
                    <div class="pagination-dots" id="paginationDots"></div>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="newsletter">
            <div class="container">
                <h2 class="newsletter-title">Login to Your JobFinder Account</h2>
                <p class="newsletter-subtitle">Access your personalized job alerts, saved applications, and career dashboard quickly and securely.</p>

                <form class="email-login-form">
                    <div class="email-input-wrapper">
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit" class="btn-primary">Sign in</button>
                    </div>
                </form>

                <p class="newsletter-hint">
                    Don't have an account? 
                    <a href="#" class="register-link">Register here</a>
                </p>
            </div>
        </section>
    </main>

    <?php include_once('includes/footer.php'); ?>

    <script src="assets/js/script.js"></script>
    <script>
        // Get companies data from PHP
        var companiesData = [
            <?php
            // Fetch companies from database
            $companiesQuery = "SELECT 
                                c.company_id,
                                c.company_name,
                                c.industry,
                                c.location,
                                c.profile_picture,
                                COUNT(DISTINCT j.job_id) as total_jobs,
                                COUNT(DISTINCT CASE WHEN j.job_category = 'deaf' THEN j.job_id END) as deaf_jobs
                             FROM companies c
                             LEFT JOIN job_listings j ON c.company_id = j.company_id AND j.status = 'Open'
                             GROUP BY c.company_id, c.company_name, c.industry, c.location, c.profile_picture
                             ORDER BY total_jobs DESC";
            
            $companiesResult = mysqli_query($conn, $companiesQuery);
            
            if ($companiesResult && mysqli_num_rows($companiesResult) > 0) {
                $companiesArray = array();
                while ($company = mysqli_fetch_assoc($companiesResult)) {
                    $logo = !empty($company['profile_picture']) ? $company['profile_picture'] : 'images/background1.jpeg';
                    $companyName = addslashes($company['company_name']);
                    $industry = addslashes($company['industry']);
                    $location = addslashes($company['location']);
                    $totalJobs = isset($company['total_jobs']) ? $company['total_jobs'] : 0;
                    $deafJobs = isset($company['deaf_jobs']) ? $company['deaf_jobs'] : 0;
                    $companyId = $company['company_id'];
                    
                    $companiesArray[] = "{
                        id: " . $companyId . ",
                        name: \"" . $companyName . "\",
                        industry: \"" . $industry . "\",
                        location: \"" . $location . "\",
                        logo: \"" . $logo . "\",
                        jobsCount: " . $totalJobs . ",
                        jobsDeafCount: " . $deafJobs . "
                    }";
                }
                echo implode(',', $companiesArray);
            } else {
                // Provide empty array if no companies
                echo '{"id": 0, "name": "", "industry": "", "location": "", "logo": "", "jobsCount": 0, "jobsDeafCount": 0}';
            }
            ?>
        ];

        // Get job listings data from PHP
        var jobListingsData = {
            <?php
            // Fetch job listings grouped by company
            $jobsQuery = "SELECT 
                            j.job_id,
                            j.company_id,
                            j.job_title,
                            j.job_description,
                            j.job_position,
                            j.job_type_shift,
                            j.job_category,
                            j.salary_range,
                            j.posted_date,
                            j.slots_available,
                            j.qualifications,
                            j.application_deadline,
                            j.contact_email,
                            c.company_name,
                            c.location,
                            c.profile_picture
                         FROM job_listings j
                         JOIN companies c ON j.company_id = c.company_id
                         WHERE j.status = 'Open'
                         ORDER BY j.posted_date DESC";
            
            $jobsResult = mysqli_query($conn, $jobsQuery);
            
            if ($jobsResult && mysqli_num_rows($jobsResult) > 0) {
                $jobsByCompany = array();
                $allJobs = array(); // For job title searches
                
                while ($job = mysqli_fetch_assoc($jobsResult)) {
                    $companyId = $job['company_id'];
                    if (!isset($jobsByCompany[$companyId])) {
                        $jobsByCompany[$companyId] = array();
                    }
                    
                    $jobData = array(
                        'id' => $job['job_id'],
                        'company_id' => $job['company_id'],
                        'title' => addslashes($job['job_title']),
                        'description' => !empty($job['job_description']) ? addslashes($job['job_description']) : 'No description available',
                        'location' => addslashes($job['location']),
                        'type' => addslashes($job['job_type_shift']),
                        'category' => addslashes($job['job_category']),
                        'position' => addslashes($job['job_position']),
                        'salary' => !empty($job['salary_range']) ? addslashes($job['salary_range']) : 'Negotiable',
                        'posted' => $job['posted_date'],
                        'slots' => $job['slots_available'],
                        'qualifications' => !empty($job['qualifications']) ? addslashes($job['qualifications']) : 'Not specified',
                        'deadline' => !empty($job['application_deadline']) ? $job['application_deadline'] : null,
                        'email' => addslashes($job['contact_email']),
                        'company_name' => addslashes($job['company_name']),
                        'company_logo' => !empty($job['profile_picture']) ? $job['profile_picture'] : 'images/background1.jpeg'
                    );
                    
                    $jobsByCompany[$companyId][] = $jobData;
                    $allJobs[] = $jobData;
                }
                
                // Format jobs for company grouping
                $companyJobsArray = array();
                foreach ($jobsByCompany as $companyId => $jobs) {
                    $jobsArray = array();
                    foreach ($jobs as $job) {
                        // Calculate days ago
                        $postedDate = new DateTime($job['posted']);
                        $now = new DateTime();
                        $daysAgo = $now->diff($postedDate)->days;
                        $timeAgo = $daysAgo == 0 ? 'Today' : ($daysAgo == 1 ? 'Yesterday' : $daysAgo . ' days ago');
                        
                        // Format deadline
                        $deadline = 'Not specified';
                        if (!empty($job['deadline'])) {
                            $deadlineDate = new DateTime($job['deadline']);
                            $deadline = $deadlineDate->format('F d, Y');
                        }
                        
                        $jobsArray[] = "{
                            id: " . $job['id'] . ",
                            title: \"" . $job['title'] . "\",
                            description: \"" . substr($job['description'], 0, 200) . "\",
                            location: \"" . $job['location'] . "\",
                            type: \"" . $job['type'] . "\",
                            category: \"" . $job['category'] . "\",
                            position: \"" . $job['position'] . "\",
                            salary: \"" . $job['salary'] . "\",
                            posted: \"" . $timeAgo . "\",
                            slots: " . $job['slots'] . ",
                            qualifications: \"" . $job['qualifications'] . "\",
                            deadline: \"" . $deadline . "\",
                            email: \"" . $job['email'] . "\",
                            company_name: \"" . $job['company_name'] . "\",
                            company_logo: \"" . $job['company_logo'] . "\"
                        }";
                    }
                    $companyJobsArray[] = $companyId . ": [" . implode(',', $jobsArray) . "]";
                }
                
                // Store all jobs for job title searches
                echo '"all_jobs": [' . implode(',', array_map(function($job) {
                    $postedDate = new DateTime($job['posted']);
                    $now = new DateTime();
                    $daysAgo = $now->diff($postedDate)->days;
                    $timeAgo = $daysAgo == 0 ? 'Today' : ($daysAgo == 1 ? 'Yesterday' : $daysAgo . ' days ago');
                    
                    $deadline = 'Not specified';
                    if (!empty($job['deadline'])) {
                        $deadlineDate = new DateTime($job['deadline']);
                        $deadline = $deadlineDate->format('F d, Y');
                    }
                    
                    return "{
                        id: " . $job['id'] . ",
                        title: \"" . $job['title'] . "\",
                        description: \"" . $job['description'] . "\",
                        location: \"" . $job['location'] . "\",
                        type: \"" . $job['type'] . "\",
                        category: \"" . $job['category'] . "\",
                        position: \"" . $job['position'] . "\",
                        salary: \"" . $job['salary'] . "\",
                        posted: \"" . $timeAgo . "\",
                        slots: " . $job['slots'] . ",
                        qualifications: \"" . $job['qualifications'] . "\",
                        deadline: \"" . $deadline . "\",
                        email: \"" . $job['email'] . "\",
                        company_name: \"" . $job['company_name'] . "\",
                        company_logo: \"" . $job['company_logo'] . "\",
                        company_id: " . $job['company_id'] . "
                    }";
                }, $allJobs)) . '],';
                
                echo implode(',', $companyJobsArray);
            } else {
                // Provide a dummy entry to prevent empty object syntax error
                echo '"all_jobs": [], "0": []';
            }
            ?>
        };

        // ===== SEARCH FUNCTIONALITY =====
        
        // Search button click event
        document.getElementById('searchBtn').addEventListener('click', performSearch);
        
        // Enter key press on search input
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') performSearch();
        });
        
        document.getElementById('locationInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') performSearch();
        });

        // Quick search function for popular searches
        function quickSearch(term) {
            document.getElementById('searchInput').value = term;
            performSearch();
        }

        function performSearch() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
            const locationTerm = document.getElementById('locationInput').value.toLowerCase().trim();
            
            if (!searchTerm && !locationTerm) {
                alert('Please enter a search term or location');
                return;
            }
            
            // Determine if search is for company or job title
            const isCompanySearch = companiesData.some(function(company) {
                return company.name.toLowerCase().includes(searchTerm);
            });
            
            let filteredResults = [];
            let searchType = '';
            
            if (isCompanySearch && searchTerm) {
                // Search for companies
                searchType = 'companies';
                filteredResults = companiesData.filter(function(company) {
                    const matchesSearch = company.name.toLowerCase().includes(searchTerm) ||
                                         company.industry.toLowerCase().includes(searchTerm);
                    const matchesLocation = !locationTerm || company.location.toLowerCase().includes(locationTerm);
                    return matchesSearch && matchesLocation;
                });
            } else {
                // Search for job titles
                searchType = 'jobs';
                const allJobs = jobListingsData.all_jobs || [];
                filteredResults = allJobs.filter(function(job) {
                    const matchesSearch = job.title.toLowerCase().includes(searchTerm) ||
                                         job.description.toLowerCase().includes(searchTerm) ||
                                         job.position.toLowerCase().includes(searchTerm);
                    const matchesLocation = !locationTerm || job.location.toLowerCase().includes(locationTerm);
                    return matchesSearch && matchesLocation;
                });
            }
            
            // Show search results container and hide companies hiring section
            const resultsContainer = document.getElementById('searchResultsContainer');
            const companiesSection = document.getElementById('companiesHiringSection');
            
            resultsContainer.classList.add('active');
            companiesSection.style.display = 'none';
            
            // Scroll to results
            resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            
            // Update results count and title
            const resultText = filteredResults.length === 1 ? 'result' : 'results';
            document.getElementById('resultsCount').textContent = filteredResults.length + ' ' + resultText;
            
            if (searchType === 'companies') {
                document.getElementById('sidebarTitle').textContent = 'Companies Found';
                renderCompanyList(filteredResults);
                // Select first company by default
                if (filteredResults.length > 0) {
                    showCompanyDetails(filteredResults[0], 0);
                } else {
                    showEmptyState();
                }
            } else {
                document.getElementById('sidebarTitle').textContent = 'Jobs Found';
                renderJobList(filteredResults);
                // Select first job by default
                if (filteredResults.length > 0) {
                    showJobDetails(filteredResults[0], 0);
                } else {
                    showEmptyState();
                }
            }
        }

        function renderCompanyList(companies) {
            const listContainer = document.getElementById('resultsList');
            listContainer.innerHTML = '';
            
            if (companies.length === 0) {
                listContainer.innerHTML = '<p style="padding: 2rem; text-align: center; color: #6b7280;">No companies found</p>';
                return;
            }
            
            companies.forEach(function(company, index) {
                const item = document.createElement('div');
                item.className = 'company-list-item' + (index === 0 ? ' selected' : '');
                
                // Check if logo exists and create proper image path
                const logoPath = company.logo ? 'assets/' + company.logo : 'assets/images/background1.jpeg';
                
                item.innerHTML = '<img src="' + logoPath + '" alt="' + company.name + '" class="company-logo-small" onerror="this.src=\'assets/images/background1.jpeg\'">' +
                    '<div class="company-brief">' +
                        '<h4 class="company-name-small">' + company.name + '</h4>' +
                        '<div class="company-meta">' +
                            '<span><i class="fas fa-briefcase"></i> ' + company.jobsCount + ' Jobs</span>' +
                            '<span><i class="fas fa-map-marker-alt"></i> ' + company.location + '</span>' +
                        '</div>' +
                    '</div>';
                
                item.addEventListener('click', function() {
                    // Remove selected class from all items
                    var allItems = document.querySelectorAll('.company-list-item');
                    for (var i = 0; i < allItems.length; i++) {
                        allItems[i].classList.remove('selected');
                    }
                    item.classList.add('selected');
                    showCompanyDetails(company, index);
                });
                listContainer.appendChild(item);
            });
        }

        function renderJobList(jobs) {
            const listContainer = document.getElementById('resultsList');
            listContainer.innerHTML = '';
            
            if (jobs.length === 0) {
                listContainer.innerHTML = '<p style="padding: 2rem; text-align: center; color: #6b7280;">No jobs found</p>';
                return;
            }
            
            jobs.forEach(function(job, index) {
                const item = document.createElement('div');
                item.className = 'job-list-item' + (index === 0 ? ' selected' : '');
                
                // Check if company logo exists and create proper image path
                const logoPath = job.company_logo ? 'assets/' + job.company_logo : 'assets/images/background1.jpeg';
                
                item.innerHTML = '<img src="' + logoPath + '" alt="' + job.company_name + '" class="company-logo-small" onerror="this.src=\'assets/images/background1.jpeg\'">' +
                    '<div class="job-brief">' +
                        '<h4 class="job-title-small">' + job.title + '</h4>' +
                        '<p class="job-company">' + job.company_name + '</p>' +
                        '<div class="job-meta-small">' +
                            '<span><i class="fas fa-map-marker-alt"></i> ' + job.location + '</span>' +
                            '<span><i class="fas fa-briefcase"></i> ' + job.type + '</span>' +
                            '<span><i class="fas fa-dollar-sign"></i> ' + job.salary + '</span>' +
                        '</div>' +
                    '</div>';
                
                item.addEventListener('click', function() {
                    // Remove selected class from all items
                    var allItems = document.querySelectorAll('.job-list-item');
                    for (var i = 0; i < allItems.length; i++) {
                        allItems[i].classList.remove('selected');
                    }
                    item.classList.add('selected');
                    showJobDetails(job, index);
                });
                listContainer.appendChild(item);
            });
        }

        function showCompanyDetails(company, index) {
            const panel = document.getElementById('detailsPanel');
            panel.classList.remove('empty');
            
            // Get jobs for this company
            const companyJobs = jobListingsData[company.id] || [];
            
            // Check if logo exists and create proper image path
            const logoPath = company.logo ? 'assets/' + company.logo : 'assets/images/background1.jpeg';
            
            panel.innerHTML = '<div class="company-details-header">' +
                    '<img src="' + logoPath + '" alt="' + company.name + '" class="company-logo-large" onerror="this.src=\'assets/images/background1.jpeg\'">' +
                    '<div class="company-header-info">' +
                        '<h2>' + company.name + '</h2>' +
                        '<div class="company-header-meta">' +
                            '<div class="meta-item">' +
                                '<i class="fas fa-industry"></i>' +
                                company.industry +
                            '</div>' +
                            '<div class="meta-item">' +
                                '<i class="fas fa-map-marker-alt"></i>' +
                                company.location +
                            '</div>' +
                            '<div class="company-rating">' +
                                '<span>5.0</span>' +
                                '<i class="fas fa-star"></i>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="company-details-section">' +
                    '<h3>Available Positions (' + companyJobs.length + ')</h3>' +
                    '<div class="job-listings-grid">' +
                        generateJobListings(company, companyJobs) +
                    '</div>' +
                '</div>';
        }

        function showJobDetails(job, index) {
            const panel = document.getElementById('detailsPanel');
            panel.classList.remove('empty');
            
            const categoryBadgeClass = job.category === 'deaf' ? 'job-category-badge deaf' : 'job-category-badge normal';
            const categoryIcon = job.category === 'deaf' ? 'fa-deaf' : 'fa-user';
            const categoryLabel = job.category === 'deaf' ? ' Deaf-Friendly Job' : ' Open to All';
            
            // Check if company logo exists and create proper image path
            const logoPath = job.company_logo ? 'assets/' + job.company_logo : 'assets/images/background1.jpeg';
            
            panel.innerHTML = '<div class="job-details-header">' +
                    '<div class="company-logo-header">' +
                        '<img src="' + logoPath + '" alt="' + job.company_name + '" class="company-logo-medium" onerror="this.src=\'assets/images/background1.jpeg\'">' +
                        '<div class="company-info-header">' +
                            '<h2>' + job.title + '</h2>' +
                            '<p class="job-company-large">' + job.company_name + '</p>' +
                        '</div>' +
                    '</div>' +
                    '<div class="job-header-meta">' +
                        '<div class="meta-item">' +
                            '<i class="fas fa-map-marker-alt"></i>' +
                            job.location +
                        '</div>' +
                        '<div class="meta-item">' +
                            '<i class="fas fa-briefcase"></i>' +
                            job.type +
                        '</div>' +
                        '<div class="meta-item">' +
                            '<i class="fas fa-dollar-sign"></i>' +
                            job.salary +
                        '</div>' +
                        '<div class="meta-item">' +
                            '<i class="fas fa-layer-group"></i>' +
                            job.position +
                        '</div>' +
                        '<span class="' + categoryBadgeClass + '">' +
                            '<i class="fas ' + categoryIcon + '"></i> ' + categoryLabel +
                        '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="job-details-section">' +
                    '<h3>Job Description</h3>' +
                    '<p class="job-description">' + job.description + '</p>' +
                '</div>' +
                '<div class="job-details-section">' +
                    '<h3>Job Details</h3>' +
                    '<div class="job-meta-block">' +
                        '<div class="job-meta-item">' +
                            '<i class="fas fa-users"></i> <strong>Available Slots:</strong> ' + job.slots +
                        '</div>' +
                        '<div class="job-meta-item">' +
                            '<i class="fas fa-calendar-alt"></i> <strong>Application Deadline:</strong> ' + job.deadline +
                        '</div>' +
                        '<div class="job-meta-item">' +
                            '<i class="fas fa-clock"></i> <strong>Posted:</strong> ' + job.posted +
                        '</div>' +
                    '</div>' +
                '</div>';
                
            if (job.qualifications && job.qualifications !== 'Not specified') {
                panel.innerHTML += '<div class="job-details-section">' +
                    '<h3>Qualifications</h3>' +
                    '<p class="job-description">' + job.qualifications + '</p>' +
                '</div>';
            }
            
            panel.innerHTML += '<div class="job-details-section">' +
                    '<h3>Contact Information</h3>' +
                    '<div class="job-meta-block">' +
                        '<div class="job-meta-item">' +
                            '<i class="fas fa-envelope"></i> <strong>Contact Email:</strong> ' + job.email +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="job-actions">' +
                    '<button class="btn-apply" onclick="applyJob(' + job.id + ')">Apply Now</button>' +
                    '<button class="btn-save" onclick="saveJob(' + job.id + ')"><i class="fas fa-bookmark"></i> Save Job</button>' +
                '</div>';
        }

        function generateJobListings(company, jobs) {
            if (jobs.length === 0) {
                return '<p style="color: #6b7280; padding: 1rem;">No active job listings at the moment.</p>';
            }
            
            var jobsHTML = '';
            for (var i = 0; i < jobs.length; i++) {
                var job = jobs[i];
                var cardClass = job.category === 'deaf' ? 'job-listing-card deaf-job' : 'job-listing-card normal-job';
                var categoryBadgeClass = job.category === 'deaf' ? 'job-category-badge deaf' : 'job-category-badge normal';
                var categoryIcon = job.category === 'deaf' ? 'fa-deaf' : 'fa-user';
                var categoryLabel = job.category === 'deaf' ? ' Deaf-Friendly Job' : ' Open to All';
                
                jobsHTML += '<div class="' + cardClass + '">' +
                    '<h4 class="job-title">' +
                        job.title + ' ' +
                        '<span class="' + categoryBadgeClass + '">' +
                            '<i class="fas ' + categoryIcon + '"></i> ' + categoryLabel +
                        '</span>' +
                    '</h4>' +

                    '<div class="job-meta-inline">' +
                        '<span class="job-meta-item"><i class="fas fa-map-marker-alt"></i> ' + job.location + '</span>' +
                        '<span class="job-meta-item"><i class="fas fa-briefcase"></i> ' + job.type + '</span>' +
                        '<span class="job-meta-item"><i class="fas fa-layer-group"></i> ' + job.position + '</span>' +
                        '<span class="job-meta-item"><i class="fas fa-dollar-sign"></i> ' + job.salary + '</span>' +
                        '<span class="job-meta-item"><i class="fas fa-users"></i> ' + job.slots + ' slot' + (job.slots > 1 ? 's' : '') + '</span>' +
                        '<span class="job-meta-item"><i class="fas fa-clock"></i> ' + job.posted + '</span>' +
                    '</div>' +

                    '<div class="job-meta-block">' +
                        '<div class="job-meta-item">' +
                            '<i class="fas fa-file-alt"></i> <strong>Description:</strong> ' + job.description +
                        '</div>';

                if (job.qualifications && job.qualifications !== 'Not specified') {
                    jobsHTML += '<div class="job-meta-item">' +
                        '<i class="fas fa-check-circle"></i> <strong>Qualifications:</strong> ' + job.qualifications +
                    '</div>';
                }

                jobsHTML += '</div>' +

                    '<div class="job-meta-inline">' +
                        '<span class="job-meta-item"><i class="fas fa-calendar-alt"></i> <strong>Deadline:</strong> ' + job.deadline + '</span>' +
                        '<span class="job-meta-item"><i class="fas fa-envelope"></i> ' + job.email + '</span>' +
                    '</div>' +

                    '<div class="job-actions">' +
                        '<button class="btn-apply" onclick="applyJob(' + job.id + ')">Apply Now</button>' +
                        '<button class="btn-save" onclick="saveJob(' + job.id + ')"><i class="fas fa-bookmark"></i></button>' +
                    '</div>' +
                '</div>';
            }
            
            return jobsHTML;
        }

        function showEmptyState() {
            const panel = document.getElementById('detailsPanel');
            panel.classList.add('empty');
            panel.innerHTML = '<div class="empty-state">' +
                    '<i class="fas fa-search"></i>' +
                    '<p>No results found matching your search</p>' +
                '</div>';
        }

        function applyJob(jobId) {
            // Redirect to job application page or show application modal
            alert('Apply for job ID: ' + jobId);
            // window.location.href = 'apply.php?job_id=' + jobId;
        }

        function saveJob(jobId) {
            // Save job to user's saved jobs
            alert('Job saved! ID: ' + jobId);
            // Implement save functionality here
        }

        // Reset search and go back to browse companies
        function resetSearch() {
            // Hide search results
            document.getElementById('searchResultsContainer').classList.remove('active');
            
            // Show companies hiring section
            document.getElementById('companiesHiringSection').style.display = 'block';
            
            // Clear search inputs
            document.getElementById('searchInput').value = '';
            document.getElementById('locationInput').value = '';
            
            // Scroll to companies section
            document.getElementById('companiesHiringSection').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }

        // ===== ORIGINAL PAGINATION FUNCTIONALITY =====
        
        const companiesPerPage = 3;
        var currentPage = 1;
        const totalPages = Math.ceil(companiesData.length / companiesPerPage);

        function renderCompanies(page) {
            const startIndex = (page - 1) * companiesPerPage;
            const endIndex = startIndex + companiesPerPage;
            const companiesToShow = companiesData.slice(startIndex, endIndex);

            const companiesGrid = document.getElementById('companiesGrid');
            companiesGrid.innerHTML = '';

            companiesToShow.forEach(function(company) {
                const companyCard = document.createElement('div');
                companyCard.className = 'company-card';
                companyCard.innerHTML = '<div class="company-star">' +
                        '<span class="star-number">5</span>' +
                        '<i class="fas fa-star"></i>' +
                    '</div>' +
                    '<div class="company-logo">' +
                        '<img src="assets/' + company.logo + '" alt="' + company.name + ' Logo" onerror="this.src=\'assets/images/background1.jpeg\'">' +
                    '</div>' +
                    '<div class="company-info">' +
                        '<h3 class="company-name">' + company.name + '</h3>' +
                        '<p class="company-industry">' + company.industry + '</p>' +
                        '<div class="job-counts">' +
                            '<p class="job-count-normal">' +
                                '<i class="fas fa-user"></i> ' + company.jobsCount + ' Jobs' +
                            '</p>' +
                            '<p class="job-count-deaf">' +
                                '<i class="fas fa-deaf"></i> ' + company.jobsDeafCount + ' Jobs' +
                            '</p>' +
                        '</div>' +
                    '</div>' +
                    '<div class="company-hover-arrow">' +
                        '<i class="fas fa-arrow-right"></i>' +
                    '</div>';
                companiesGrid.appendChild(companyCard);
            });
        }

        function renderPaginationDots() {
            const dotsContainer = document.getElementById('paginationDots');
            dotsContainer.innerHTML = '';
            
            for (var i = 1; i <= totalPages; i++) {
                const dot = document.createElement('span');
                dot.className = 'dot' + (i === currentPage ? ' active' : '');
                dot.setAttribute('data-page', i);
                dot.addEventListener('click', function() {
                    goToPage(parseInt(this.getAttribute('data-page')));
                });
                dotsContainer.appendChild(dot);
            }
        }

        function goToPage(page) {
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderCompanies(currentPage);
                renderPaginationDots();
                updatePaginationButtons();
            }
        }

        function updatePaginationButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            prevBtn.style.visibility = currentPage === 1 ? 'hidden' : 'visible';
            nextBtn.style.visibility = currentPage === totalPages ? 'hidden' : 'visible';
        }

        document.getElementById('prevBtn').addEventListener('click', function() {
            goToPage(currentPage - 1);
        });
        
        document.getElementById('nextBtn').addEventListener('click', function() {
            goToPage(currentPage + 1);
        });

        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            renderCompanies(currentPage);
            renderPaginationDots();
            updatePaginationButtons();
        });
    </script>
</body>
</html>