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

</head>
<body>
    <?php include_once('includes/db_connect.php') ?>
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
                    <div class="search-form" id="mainSearchForm">
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
                    <span style="color:var(--text-light); font-style: italic;">
                        Note: Yellow indicates jobs for Deaf applicants
                        </span>
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
                <p class="newsletter-hint">Don't have an account?
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

        // ===== STICKY SEARCH FORM FUNCTIONALITY =====
        
        let isSearchActive = false;
        let searchForm = document.getElementById('mainSearchForm');
        let searchFormOriginalPosition = null;

        // Function to update sticky state
        function updateStickySearchForm() {
            if (!isSearchActive) return;

            const scrollPosition = window.scrollY;
            const heroSection = document.querySelector('.hero');
            const heroBottom = heroSection.offsetTop + heroSection.offsetHeight;
            
            // If scrolled past the hero section, make search form sticky
            if (scrollPosition > heroBottom - 100) {
                if (!searchForm.classList.contains('sticky')) {
                    // Store original position before making sticky
                    searchFormOriginalPosition = {
                        parent: searchForm.parentNode,
                        nextSibling: searchForm.nextElementSibling
                    };
                    
                    // Move search form to body for fixed positioning
                    document.body.appendChild(searchForm);
                    searchForm.classList.add('sticky');
                    
                    // Add active class with delay for smooth animation
                    setTimeout(() => {
                        searchForm.classList.add('active');
                    }, 10);
                    
                    // Add margin to search results to account for sticky form
                    document.getElementById('searchResultsContainer').classList.add('with-sticky');
                }
            } else {
                // If scrolled back up, return search form to original position
                if (searchForm.classList.contains('sticky')) {
                    searchForm.classList.remove('active');
                    
                    setTimeout(() => {
                        if (searchFormOriginalPosition) {
                            // Return search form to its original position
                            const heroContent = document.querySelector('.hero-content');
                            heroContent.insertBefore(searchForm, searchFormOriginalPosition.nextSibling);
                        }
                        searchForm.classList.remove('sticky');
                        
                        // Remove margin from search results
                        document.getElementById('searchResultsContainer').classList.remove('with-sticky');
                    }, 300);
                }
            }
        }

        // Listen for scroll events
        window.addEventListener('scroll', updateStickySearchForm);

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
                alert("Please enter a search term or location");
                return;
            }

            isSearchActive = true;

            // Hide newsletter
            document.querySelector('.newsletter').style.display = 'none';

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
            
            // Update sticky form after search
            setTimeout(updateStickySearchForm, 100);
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
                const logoPath = company.logo ? 'assets/images/' + company.logo : 'assets/images/background1.jpeg';
                
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
                const logoPath = job.company_logo ? 'assets/images/' + job.company_logo : 'assets/images/background1.jpeg';
                
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
            const logoPath = company.logo ? 'assets/images/' + company.logo : 'assets/images/background1.jpeg';
            
            panel.innerHTML = '<div class="company-details-header">' +
                    '<img src="' + logoPath + '" alt="' + company.name + '" class="company-logo-large" onerror="this.src=\'assets/images/background1.jpeg\'">' +
                    '<div class="company-header-info">' +
                                '<h2><a href="company-details.php?id=' + company.id + '" class="company-name-link">' + company.name + '</a></h2>' +

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
            const logoPath = job.company_logo ? 'assets/images/' + job.company_logo : 'assets/images/background1.jpeg';
            
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
                            '<i class="fas fa-users"></i> Only <strong>' + job.slots + '</strong> available' +
                        '</div>' +
                        '<div class="job-meta-item">' +
                            '<i class="fas fa-calendar-alt"></i> Apply before <strong>' + job.deadline + '</strong>' +
                        '</div>' +
                        '<div class="job-meta-item">' +
                            '<i class="fas fa-clock"></i> Posted on <strong>' + job.posted + '</strong>' +
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
        return '<div class="no-jobs-message">' +
            '<i class="fas fa-briefcase"></i>' +
            '<p>No active job listings at the moment.</p>' +
            '<small>Check back later for new opportunities</small>' +
        '</div>';
    }
    
    var jobsHTML = '';
    for (var i = 0; i < jobs.length; i++) {
        var job = jobs[i];
        var isDeafJob = job.category === 'deaf';
        var cardClass = isDeafJob ? 'job-listing-card deaf-job' : 'job-listing-card normal-job';
        var categoryBadgeClass = isDeafJob ? 'job-category-badge deaf' : 'job-category-badge normal';
        var categoryIcon = isDeafJob ? 'fa-deaf' : 'fa-user';
        var categoryLabel = isDeafJob ? 'Deaf-Friendly' : 'Open to All';
        
        // Format job description to show only first 150 characters with ellipsis
        var shortDescription = job.description.length > 150 ? 
            job.description.substring(0, 150) + '...' : job.description;
        
        jobsHTML += 
        '<div class="' + cardClass + '">' +
            // Header with title and category badge
            '<div class="job-card-header">' +
                '<div class="job-title-wrapper">' +
                    '<h4 class="job-title">' + job.title + '</h4>' +
                    '<span class="' + categoryBadgeClass + '">' +
                        '<i class="fas ' + categoryIcon + '"></i>' + ' ' + categoryLabel +
                    '</span>' +
                '</div>' +
                '<div class="job-posted-date">' +
                    '<i class="fas fa-clock"></i>' +
                    '<span>' + job.posted + '</span>' +
                '</div>' +
            '</div>' +

            // Basic job info
            '<div class="job-basic-info">' +
                '<div class="job-meta-item">' +
                    '<i class="fas fa-building"></i>' +
                    '<span>' + job.company_name + '</span>' +
                '</div>' +
                '<div class="job-meta-item">' +
                    '<i class="fas fa-map-marker-alt"></i>' +
                    '<span>' + job.location + '</span>' +
                '</div>' +
            '</div>' +

            // Job details grid
            '<div class="job-details-grid">' +
                '<div class="detail-item">' +
                    '<i class="fas fa-briefcase"></i>' +
                    '<div class="detail-content">' +
                        '<span class="detail-label">Job Type</span>' +
                        '<span class="detail-value">' + job.type + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="detail-item">' +
                    '<i class="fas fa-layer-group"></i>' +
                    '<div class="detail-content">' +
                        '<span class="detail-label">Position</span>' +
                        '<span class="detail-value">' + job.position + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="detail-item">' +
                    '<i class="fas fa-dollar-sign"></i>' +
                    '<div class="detail-content">' +
                        '<span class="detail-label">Salary</span>' +
                        '<span class="detail-value">' + job.salary + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="detail-item">' +
                    '<i class="fas fa-users"></i>' +
                    '<div class="detail-content">' +
                        '<span class="detail-label">Slots Available</span>' +
                        '<span class="detail-value">' + job.slots + '</span>' +
                    '</div>' +
                '</div>' +
            '</div>' +

            // Job description
            '<div class="job-description-section">' +
                '<h5>Job Description</h5>' +
                '<p class="job-description-text">' + shortDescription + '</p>' +
            '</div>' +

            // Qualifications (if available)
            (job.qualifications && job.qualifications !== 'Not specified' ? 
            '<div class="job-qualifications-section">' +
                '<h5>Qualifications</h5>' +
                '<p class="job-qualifications-text">' + job.qualifications + '</p>' +
            '</div>' : '') +

            // Deadline and contact
            '<div class="job-deadline-contact">' +
                '<div class="deadline-info">' +
                    '<i class="fas fa-calendar-alt"></i>' +
                    '<div>' +
                        '<span class="deadline-label">Application Deadline</span>' +
                        '<span class="deadline-date">' + job.deadline + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="contact-info">' +
                    '<i class="fas fa-envelope"></i>' +
                    '<span>' + job.email + '</span>' +
                '</div>' +
            '</div>' +

            // Action buttons
            '<div class="job-actions">' +
                '<button class="btn-apply" onclick="applyJob(' + job.id + ')">' +
                    '<i class="fas fa-paper-plane"></i>Apply Now' +
                '</button>' +
                '<button class="btn-save" onclick="saveJob(' + job.id + ')">' +
                    '<i class="fas fa-bookmark"></i>Save' +
                '</button>' +
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
            isSearchActive = false;
            
            document.getElementById('searchResultsContainer').classList.remove('active');
            document.getElementById('companiesHiringSection').style.display = 'block';
            document.getElementById('searchInput').value = '';
            document.getElementById('locationInput').value = '';

            document.querySelector('.newsletter').style.display = 'block';
            
            // Return search form to original position if it's sticky
            if (searchForm.classList.contains('sticky')) {
                searchForm.classList.remove('active');
                
                setTimeout(() => {
                    if (searchFormOriginalPosition) {
                        const heroContent = document.querySelector('.hero-content');
                        heroContent.insertBefore(searchForm, searchFormOriginalPosition.nextSibling);
                    }
                    searchForm.classList.remove('sticky');
                    document.getElementById('searchResultsContainer').classList.remove('with-sticky');
                }, 300);
            }
            
            // Auto-focus search input for better UX
            document.getElementById('searchInput').focus();
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
        
        // Calculate normal jobs count
        const normalJobsCount = company.jobsCount - company.jobsDeafCount;
        
        companyCard.innerHTML = 
            '<div class="company-star">' +
                '<span class="star-number">5</span>' +
                '<i class="fas fa-star"></i>' +
            '</div>' +
            '<div class="company-logo">' +
                '<img src="assets/images/' + company.logo + '" alt="' + company.name + ' Logo" onerror="this.src=\'assets/images/background1.jpeg\'">' +
            '</div>' +
            '<div class="company-info">' +
                '<h3 class="company-name">' + company.name + '</h3>' +
                '<p class="company-industry">' + company.industry + '</p>' +
                '<div class="job-counts">' +
                    '<p class="job-count-normal">' +
                        '<i class="fas fa-user"></i> ' + normalJobsCount + ' Normal Jobs' +
                    '</p>' +
                    '<p class="job-count-deaf">' +
                        '<i class="fas fa-deaf"></i> ' + company.jobsDeafCount + ' Deaf-Friendly Jobs' +
                    '</p>' +
                '</div>' +
            '</div>' +
            '<div class="company-hover-arrow">' +
                '<i class="fas fa-arrow-right"></i>' +
            '</div>';
        
        // Add click event to redirect to company details page
        companyCard.addEventListener('click', function() {
            window.location.href = 'company-details.php?id=' + company.id;
        });
        
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