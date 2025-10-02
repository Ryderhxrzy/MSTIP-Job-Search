<?php include_once('includes/db_connect.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employers - MSTIP Job Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    
    <style>
        /* Companies Page Specific Styles */
        .companies-page-hero {
            background: var(--dark-color);
            color: white;
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
        }

        .companies-page-hero .container {
            position: relative;
            z-index: 2;
        }

        .companies-hero-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .companies-hero-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .companies-hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .companies-search-section {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            margin-top: 2rem;
        }

        .companies-search-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .search-filters {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            align-items: end;
        }

        /* Search Field Style for All Inputs */
        .search-field {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-field i {
            position: absolute;
            left: 1rem;
            color: var(--secondary-color);
            z-index: 1;
        }

        .search-field input,
        .search-field select {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--primary-border) !important;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: var(--transition);
            background: white;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .search-field select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
            padding-right: 2.5rem;
        }

        .search-field input:focus,
        .search-field select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-field input::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        .search-button-container {
            display: flex;
            gap: 1rem;
            height: fit-content;
        }

        .search-button-container .btn-primary {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            height: auto;
            white-space: nowrap;
        }

        .companies-main-section {
            padding: 3rem 0;
            background: var(--surface-color);
        }

        .companies-results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .results-count {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .sort-options {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sort-select {
            padding: 0.5rem;
            border: 1px solid var(--primary-border);
            border-radius: 0.25rem;
            background: white;
            cursor: pointer;
        }

        .companies-grid-full {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .pagination-full {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 3rem;
        }

        .pagination-btn-full {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--primary-border);
            background: white;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-btn-full:hover:not(:disabled) {
            border-color: var(--primary-color);
            background: var(--primary-border);
            color: var(--text-light);
        }

        .pagination-btn-full:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-numbers {
            display: flex;
            gap: 0.5rem;
        }

        .page-number {
            padding: 0.75rem 1rem;
            border: 2px solid var(--primary-border);
            background: white;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            min-width: 45px;
            text-align: center;
        }

        .page-number.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .page-number:hover:not(.active) {
            border-color: var(--primary-color);
        }

        .btn-secondary {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            height: auto;
            white-space: nowrap;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: #4b5563; /* darker gray on hover */
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .search-filters {
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .companies-page-hero {
                padding: 2rem 0;
            }

            .companies-hero-title {
                font-size: 2rem;
            }

            .companies-search-section {
                padding: 1.5rem;
                margin-top: 1.5rem;
            }

            .search-filters {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .search-button-container {
                width: 100%;
            }

            .search-button-container .btn-primary {
                width: 100%;
            }

            .search-button-container {
                flex-direction: column;
            }
            .search-button-container button {
                width: 100%;
            }

            .companies-results-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .companies-grid-full {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .pagination-full {
                flex-wrap: wrap;
            }

            .pagination-numbers {
                order: -1;
                width: 100%;
                justify-content: center;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 480px) {
            .companies-page-hero {
                padding: 1.5rem 0;
            }

            .companies-hero-title {
                font-size: 1.75rem;
            }

            .companies-hero-subtitle {
                font-size: 1rem;
            }

            .companies-search-section {
                padding: 1rem;
                border-radius: 0.75rem;
            }

            .search-field input,
            .search-field select {
                padding: 0.875rem 0.875rem 0.875rem 2.5rem;
                font-size: 0.95rem;
            }

            .search-field select {
                background-position: right 0.875rem center;
                padding-right: 2.25rem;
            }

            .search-button-container .btn-primary {
                padding: 0.875rem 1rem;
                font-size: 0.95rem;
            }

            .companies-main-section {
                padding: 2rem 0;
            }

            .companies-grid-full {
                gap: 1rem;
            }

            .pagination-btn-full {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .page-number {
                padding: 0.6rem 0.8rem;
                min-width: 40px;
                font-size: 0.9rem;
            }
        }

        /* Very Small Screens */
        @media (max-width: 360px) {
            .companies-hero-title {
                font-size: 1.5rem;
            }

            .search-field input,
            .search-field select {
                padding: 0.75rem 0.75rem 0.75rem 2.25rem;
                font-size: 0.9rem;
            }

            .search-field select {
                background-position: right 0.75rem center;
                padding-right: 2rem;
            }

            .pagination-full {
                gap: 0.5rem;
            }

            .pagination-btn-full {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }

            .page-number {
                padding: 0.5rem 0.7rem;
                min-width: 35px;
                font-size: 0.85rem;
            }
        }

        /* No Results Styling */
        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 1rem;
            border: 2px solid var(--primary-border);
        }

        .no-results i {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .no-results h3 {
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .no-results p {
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <?php include_once('includes/header.php'); ?>

    <main>
        <!-- Companies Hero Section -->
        <section class="companies-page-hero">
            <svg class="hero-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 400" preserveAspectRatio="none">
                <g fill="rgba(255,255,255,0.05)">
                    <!-- First Wave -->
                    <path d="M0 120 C 200 200, 400 40, 800 160 L800 400 L0 400 Z" />
                    <!-- Second Wave -->
                    <path d="M0 200 C 250 300, 550 100, 800 220 L800 400 L0 400 Z" />
                    <!-- Third Wave -->
                    <path d="M0 280 C 200 380, 600 180, 800 300 L800 400 L0 400 Z" />
                </g>
            </svg>
            <div class="container">
                <div class="companies-hero-content">
                    <h1 class="companies-hero-title">Discover Top Companies</h1>
                    <p class="companies-hero-subtitle">
                        Explore leading organizations and find your perfect workplace match. 
                        Connect with companies that value talent and innovation.
                    </p>
                </div>

                <!-- Search Section -->
                <div class="companies-search-section">
                    <form class="companies-search-form" id="companiesSearchForm">
                        <div class="search-filters">
                            <div class="search-field">
                                <i class="fas fa-search"></i>
                                <input type="text" id="companySearch" placeholder="Search by company name..." class="filter-input">
                            </div>
                            <div class="search-field">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" id="locationFilter" placeholder="Enter location..." class="filter-input">
                            </div>
                            <div class="search-field">
                                <i class="fas fa-building"></i>
                                <select id="typeFilter" class="filter-select">
                                    <option value="">All Company Types</option>
                                    <option value="Government">Government</option>
                                    <option value="Private">Private</option>
                                </select>
                            </div>
                            <div class="search-button-container">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-search"></i> Search Companies
                                </button>
                                <button type="button" id="resetButton" class="btn-primary" style="background: var(--secondary-color);">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </div>
                        </div>
                        <span style="color:var(--error-color); font-style: italic;">
                        Note: Yellow indicates jobs for Deaf applicants
                        </span>
                    </form>
                </div>
            </div>
        </section>

        <!-- Companies Results Section -->
        <section class="companies-main-section">
            <div class="container">
                <div class="companies-results-header">
                    <div>
                        <h2 class="section-title">Featured Companies</h2>
                        <p class="results-count" id="resultsCount">Loading companies...</p>
                    </div>
                    <div class="sort-options">
                        <label for="sortResults">Sort by:</label>
                        <select id="sortResults" class="sort-select">
                            <option value="jobs">Most Jobs</option>
                            <option value="name">Name (A-Z)</option>
                            <option value="newest">Newest First</option>
                        </select>
                    </div>
                </div>

                <div class="companies-grid-full" id="companiesGrid">
                    <!-- Companies will be loaded here by JavaScript -->
                </div>

                <!-- Pagination -->
                <div class="pagination-full" id="paginationContainer">
                    <!-- Pagination will be loaded here by JavaScript -->
                </div>
            </div>
        </section>
    </main>

    <?php include_once('includes/footer.php'); ?>

    <script src="assets/js/script.js"></script>
    <script>
        // Reset button event
        document.getElementById('resetButton').addEventListener('click', function() {
            // Clear input values
            document.getElementById('companySearch').value = '';
            document.getElementById('locationFilter').value = '';
            document.getElementById('typeFilter').value = '';

            // Reset filtered companies
            filteredCompanies = [...allCompanies];
            currentPage = 1;
            currentSort = 'jobs';

            // Re-render
            sortCompanies();
            renderCompanies();
            renderPagination();
            updateResultsCount();
        });

        // Companies data from PHP
        const allCompanies = [
            <?php
            $companiesQuery = "SELECT 
                                c.company_id,
                                c.company_name,
                                c.industry,
                                c.location,
                                c.company_type,
                                c.profile_picture,
                                c.about_company,
                                COUNT(j.job_id) as total_jobs,
                                SUM(CASE WHEN j.job_category = 'deaf' THEN 1 ELSE 0 END) as deaf_jobs
                             FROM companies c
                             LEFT JOIN job_listings j ON c.company_id = j.company_id 
                             WHERE j.status = 'Open' OR j.status IS NULL
                             GROUP BY c.company_id, c.company_name, c.industry, c.location, c.company_type, c.profile_picture, c.about_company
                             ORDER BY total_jobs DESC";
            
            $companiesResult = mysqli_query($conn, $companiesQuery);
            
            if ($companiesResult && mysqli_num_rows($companiesResult) > 0) {
                while ($company = mysqli_fetch_assoc($companiesResult)) {
                    $logo = !empty($company['profile_picture']) ? $company['profile_picture'] : 'assets/images/background1.jpeg';
                    $companyName = addslashes($company['company_name']);
                    $industry = addslashes($company['industry']);
                    $location = addslashes($company['location']);
                    $about = addslashes($company['about_company']);
                    
                    echo "{
                        id: {$company['company_id']},
                        name: \"{$companyName}\",
                        industry: \"{$industry}\",
                        location: \"{$location}\",
                        type: \"{$company['company_type']}\",
                        logo: \"{$logo}\",
                        about: \"{$about}\",
                        jobsCount: {$company['total_jobs']},
                        jobsDeafCount: {$company['deaf_jobs']}
                    },";
                }
            }
            ?>
        ];

        // Pagination and filtering variables
        let currentPage = 1;
        const companiesPerPage = 9;
        let filteredCompanies = [...allCompanies];
        let currentSort = 'jobs';

        // DOM Elements
        const companiesGrid = document.getElementById('companiesGrid');
        const paginationContainer = document.getElementById('paginationContainer');
        const resultsCount = document.getElementById('resultsCount');
        const searchForm = document.getElementById('companiesSearchForm');
        const sortSelect = document.getElementById('sortResults');

        // Function to render companies
        function renderCompanies() {
            const startIndex = (currentPage - 1) * companiesPerPage;
            const endIndex = startIndex + companiesPerPage;
            const companiesToShow = filteredCompanies.slice(startIndex, endIndex);

            companiesGrid.innerHTML = '';

            if (companiesToShow.length === 0) {
                companiesGrid.innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-building"></i>
                        <h3>No companies found</h3>
                        <p>Try adjusting your search filters</p>
                    </div>
                `;
                return;
            }

            companiesToShow.forEach(company => {
                const companyCard = document.createElement('div');
                companyCard.className = 'company-card';
                companyCard.innerHTML = `
                    <div class="company-star">
                        <span class="star-number">5</span>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="company-logo">
                        <img src="${company.logo}" alt="${company.name} Logo" onerror="this.src='assets/images/background1.jpeg'">
                    </div>
                    <div class="company-info">
                        <h3 class="company-name">${company.name}</h3>
                        <p class="company-industry">${company.industry}</p>
                        <div class="company-location">
                            <i class="fas fa-map-marker-alt"></i>
                            ${company.location}
                        </div>
                        <div class="job-counts">
                            <p class="job-count-normal">
                                <i class="fas fa-user"></i> ${company.jobsCount} Jobs
                            </p>
                            <p class="job-count-deaf">
                                <i class="fas fa-deaf"></i> ${company.jobsDeafCount} Jobs
                            </p>
                        </div>
                    </div>
                    <div class="company-hover-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                `;
                companiesGrid.appendChild(companyCard);
            });
        }

        // Function to render pagination
        function renderPagination() {
            const totalPages = Math.ceil(filteredCompanies.length / companiesPerPage);
            
            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let paginationHTML = `
                <button class="pagination-btn-full" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <div class="pagination-numbers">
            `;

            // Show page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    paginationHTML += `
                        <button class="page-number ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    paginationHTML += `<span class="page-number">...</span>`;
                }
            }

            paginationHTML += `
                </div>
                <button class="pagination-btn-full" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            `;

            paginationContainer.innerHTML = paginationHTML;
        }

        // Function to go to specific page
        function goToPage(page) {
            const totalPages = Math.ceil(filteredCompanies.length / companiesPerPage);
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderCompanies();
                renderPagination();
                updateResultsCount();
            }
        }

        // Function to update results count
        function updateResultsCount() {
            const total = filteredCompanies.length;
            const start = ((currentPage - 1) * companiesPerPage) + 1;
            const end = Math.min(currentPage * companiesPerPage, total);
            
            resultsCount.textContent = `Showing ${start}-${end} of ${total} companies`;
        }

        // Function to filter companies
        function filterCompanies() {
            const searchTerm = document.getElementById('companySearch').value.toLowerCase();
            const location = document.getElementById('locationFilter').value.toLowerCase();
            const type = document.getElementById('typeFilter').value;

            filteredCompanies = allCompanies.filter(company => {
                const matchesSearch = !searchTerm || 
                    company.name.toLowerCase().includes(searchTerm);
                
                const matchesLocation = !location || 
                    company.location.toLowerCase().includes(location);
                
                const matchesType = !type || company.type === type;

                return matchesSearch && matchesLocation && matchesType;
            });

            // Apply sorting
            sortCompanies();

            currentPage = 1;
            renderCompanies();
            renderPagination();
            updateResultsCount();
        }

        // Function to sort companies
        function sortCompanies() {
            switch(currentSort) {
                case 'name':
                    filteredCompanies.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'newest':
                    // Assuming newer companies have higher IDs
                    filteredCompanies.sort((a, b) => b.id - a.id);
                    break;
                case 'jobs':
                default:
                    filteredCompanies.sort((a, b) => b.jobsCount - a.jobsCount);
                    break;
            }
        }

        // Event Listeners
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            filterCompanies();
        });

        sortSelect.addEventListener('change', function() {
            currentSort = this.value;
            sortCompanies();
            currentPage = 1;
            renderCompanies();
            renderPagination();
            updateResultsCount();
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            sortCompanies();
            renderCompanies();
            renderPagination();
            updateResultsCount();
        });
    </script>
</body>
</html>