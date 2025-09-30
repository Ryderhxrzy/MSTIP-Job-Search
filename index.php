<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MSTIP - Job Search</title>
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
                                <input type="text" placeholder="Job title, keywords, or company">
                            </div>
                            <div class="search-field">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" placeholder="City, state, or remote">
                            </div>
                            <button class="btn-primary search-btn">
                                <i class="fas fa-search"></i> Search Jobs
                            </button>
                        </div>
                    </div>

                    <div class="popular-searches">
                        <span>Popular searches:</span>
                        <a href="#">Remote Work</a>
                        <a href="#">Software Engineer</a>
                        <a href="#">Marketing</a>
                        <a href="#">Data Analyst</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Companies Hiring Section -->
        <section class="companies-hiring">
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
                        <!-- Companies loaded dynamically -->
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
        const companiesData = [
    { name: "Amazon", logo: "A", overview: "Global e-commerce and cloud computing giant offering innovative solutions and services worldwide.", jobsCount: 1245, jobsDeafCount: 50, industry: "E-commerce & Cloud", location: "Seattle, WA" },
    { name: "Google", logo: "G", overview: "Leading technology company focused on search, advertising, cloud computing, and AI innovation.", jobsCount: 892, jobsDeafCount: 40, industry: "Technology", location: "Mountain View, CA" },
    { name: "Microsoft", logo: "M", overview: "Multinational technology corporation developing software, services, and hardware solutions.", jobsCount: 756, jobsDeafCount: 35, industry: "Software & Technology", location: "Redmond, WA" },
    { name: "Apple", logo: "🍎", overview: "Innovative technology company designing consumer electronics and digital services.", jobsCount: 634, jobsDeafCount: 25, industry: "Consumer Electronics", location: "Cupertino, CA" },
    { name: "Meta", logo: "M", overview: "Social technology company connecting people through apps and virtual reality experiences.", jobsCount: 423, jobsDeafCount: 20, industry: "Social Media & VR", location: "Menlo Park, CA" },
    { name: "Tesla", logo: "T", overview: "Electric vehicle and clean energy company accelerating sustainable transportation.", jobsCount: 567, jobsDeafCount: 30, industry: "Automotive & Energy", location: "Austin, TX" },
    { name: "Netflix", logo: "N", overview: "Global streaming entertainment service with original content and personalized recommendations.", jobsCount: 289, jobsDeafCount: 15, industry: "Entertainment & Media", location: "Los Gatos, CA" },
    { name: "Spotify", logo: "S", overview: "Digital music streaming platform connecting millions of users with their favorite audio content.", jobsCount: 345, jobsDeafCount: 10, industry: "Music & Audio", location: "Stockholm, Sweden" },
    { name: "Adobe", logo: "A", overview: "Creative software company providing digital tools for content creation and marketing.", jobsCount: 412, jobsDeafCount: 18, industry: "Creative Software", location: "San Jose, CA" },
    { name: "Salesforce", logo: "S", overview: "Cloud-based customer relationship management platform helping businesses connect with customers.", jobsCount: 678, jobsDeafCount: 25, industry: "CRM & Cloud", location: "San Francisco, CA" },
    { name: "IBM", logo: "I", overview: "Technology and consulting company providing enterprise solutions and AI-powered services.", jobsCount: 834, jobsDeafCount: 28, industry: "Enterprise Technology", location: "Armonk, NY" },
    { name: "Oracle", logo: "O", overview: "Enterprise software company specializing in database management and cloud applications.", jobsCount: 523, jobsDeafCount: 22, industry: "Database & Cloud", location: "Austin, TX" }
];


        const companiesPerPage = 3;
        let currentPage = 1;
        const totalPages = Math.ceil(companiesData.length / companiesPerPage);

        function renderCompanies(page) {
            const startIndex = (page - 1) * companiesPerPage;
            const endIndex = startIndex + companiesPerPage;
            const companiesToShow = companiesData.slice(startIndex, endIndex);

            const companiesGrid = document.getElementById('companiesGrid');
            companiesGrid.innerHTML = '';

            companiesToShow.forEach(company => {
    const companyCard = document.createElement('div');
    companyCard.className = 'company-card';
    companyCard.innerHTML = `
        <div class="company-star">
            <span class="star-number">5</span>
            <i class="fas fa-star"></i>
        </div>
        <div class="company-logo">
            <img src="assets/images/background1.jpeg" alt="Placeholder Logo">
        </div>

        <div class="company-info">
            <h3 class="company-name">${company.name}</h3>
            <p class="company-industry">${company.industry}</p>
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

        function renderPaginationDots() {
            const dotsContainer = document.getElementById('paginationDots');
            dotsContainer.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const dot = document.createElement('span');
                dot.className = `dot ${i === currentPage ? 'active' : ''}`;
                dot.addEventListener('click', () => goToPage(i));
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

            // Hide buttons if not available
            prevBtn.classList.toggle('hidden', currentPage === 1);
            nextBtn.classList.toggle('hidden', currentPage === totalPages);
        }

        document.getElementById('prevBtn').addEventListener('click', () => goToPage(currentPage - 1));
        document.getElementById('nextBtn').addEventListener('click', () => goToPage(currentPage + 1));

        document.addEventListener('DOMContentLoaded', () => {
            renderCompanies(currentPage);
            renderPaginationDots();
            updatePaginationButtons();
        });
    </script>
</body>
</html>