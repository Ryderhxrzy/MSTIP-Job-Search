<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companies Hiring</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/footer.css">
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
                            <button class="btn btn-primary search-btn">
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

                <div class="social-login-row">
                    <a href="#" class="btn-social btn-google">
                        <svg class="social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 533.5 544.3">
                            <path fill="#4285F4" d="M533.5 278.4c0-18.9-1.5-37.2-4.4-55H272v104.5h146.9c-6.3 34-25 62.9-53.2 82.2v68.2h85.9c50.2-46.3 79.9-114.7 79.9-199.9z"/>
                            <path fill="#34A853" d="M272 544.3c72.6 0 133.6-23.9 178.1-65l-85.9-68.2c-23.8 16-54.5 25.5-92.2 25.5-70.9 0-131-47.8-152.4-112.3H33.9v70.8C77.9 486.5 170 544.3 272 544.3z"/>
                            <path fill="#FBBC05" d="M119.6 322.3c-10.4-31-10.4-64.8 0-95.8v-70.8H33.9C12 197.4 0 232.6 0 272s12 74.6 33.9 104.3l85.7-70.9z"/>
                            <path fill="#EA4335" d="M272 107.7c39.5-.6 77.4 14 106 41.2l79.3-79.3C406.1 24 344 0 272 0 170 0 77.9 57.8 33.9 145.2l85.7 70.8C141 155.5 201.1 107.7 272 107.7z"/>
                        </svg>
                        Continue with Google
                    </a>

                    <span class="or-separator">or</span>

                    <a href="#" class="btn-social btn-linkedin">
                        <svg class="social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path fill="#0077B5" d="M100.28 448H7.4V148.9h92.88zm-46.44-340.5C24.06 107.5 0 83.4 0 53.2S24.06-1 53.84-1 107.7 23.1 107.7 53.2s-24.06 54.3-54.44 54.3zM447.9 448h-92.68V302.4c0-34.7-12.4-58.3-43.34-58.3-23.63 0-37.68 15.9-43.88 31.3-2.26 5.5-2.83 13.1-2.83 20.7V448h-92.68s1.23-266.5 0-294.1h92.68v41.6c12.3-19 34.3-46 83.5-46 60.9 0 106.6 39.7 106.6 124.9V448z"/>
                        </svg>
                        Continue with LinkedIn
                    </a>
                </div>

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
        <div class="company-logo">${company.logo}</div>
        <div class="company-info">
            <h3 class="company-name">${company.name}</h3>
            <p class="company-industry">${company.industry}</p>
            <p class="company-location">
                <i class="fas fa-map-marker-alt"></i>
                ${company.location}
            </p>
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
