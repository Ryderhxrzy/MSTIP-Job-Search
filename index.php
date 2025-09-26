<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companies Hiring</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
</head>
<body>
    <?php
        include_once ('includes/header.php');
    ?>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Find Your Dream Job Today</h1>
                    <p class="hero-subtitle">Connect with top employers and discover opportunities that match your skills and ambitions.</p>
                    
                    <!-- Job Search Form -->
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
                                <i class="fas fa-search"></i>
                                Search Jobs
                            </button>
                        </div>
                    </div>
                    
                    <!-- Popular Searches -->
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
                <h2 class="section-title">Companies Currently Hiring</h2>
                <p class="section-subtitle">Explore companies actively seeking talented professionals like you.</p>
                
                <div class="companies-grid" id="companiesGrid">
                    <!-- Companies will be dynamically loaded here -->
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <button class="pagination-btn prev" id="prevBtn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="pagination-dots" id="paginationDots">
                        <!-- Dots will be dynamically created -->
                    </div>
                    <button class="pagination-btn next" id="nextBtn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </section>
    </main>

    <script src="assets/js/script.js"></script>
    <script>
        // Dummy data for companies
        const companiesData = [
            {
                name: "Amazon",
                logo: "A",
                overview: "Global e-commerce and cloud computing giant offering innovative solutions and services worldwide.",
                jobsCount: 1245,
                industry: "E-commerce & Cloud",
                location: "Seattle, WA"
            },
            {
                name: "Google",
                logo: "G",
                overview: "Leading technology company focused on search, advertising, cloud computing, and AI innovation.",
                jobsCount: 892,
                industry: "Technology",
                location: "Mountain View, CA"
            },
            {
                name: "Microsoft",
                logo: "M",
                overview: "Multinational technology corporation developing software, services, and hardware solutions.",
                jobsCount: 756,
                industry: "Software & Technology",
                location: "Redmond, WA"
            },
            {
                name: "Apple",
                logo: "🍎",
                overview: "Innovative technology company designing consumer electronics and digital services.",
                jobsCount: 634,
                industry: "Consumer Electronics",
                location: "Cupertino, CA"
            },
            {
                name: "Meta",
                logo: "M",
                overview: "Social technology company connecting people through apps and virtual reality experiences.",
                jobsCount: 423,
                industry: "Social Media & VR",
                location: "Menlo Park, CA"
            },
            {
                name: "Tesla",
                logo: "T",
                overview: "Electric vehicle and clean energy company accelerating sustainable transportation.",
                jobsCount: 567,
                industry: "Automotive & Energy",
                location: "Austin, TX"
            },
            {
                name: "Netflix",
                logo: "N",
                overview: "Global streaming entertainment service with original content and personalized recommendations.",
                jobsCount: 289,
                industry: "Entertainment & Media",
                location: "Los Gatos, CA"
            },
            {
                name: "Spotify",
                logo: "S",
                overview: "Digital music streaming platform connecting millions of users with their favorite audio content.",
                jobsCount: 345,
                industry: "Music & Audio",
                location: "Stockholm, Sweden"
            },
            {
                name: "Adobe",
                logo: "A",
                overview: "Creative software company providing digital tools for content creation and marketing.",
                jobsCount: 412,
                industry: "Creative Software",
                location: "San Jose, CA"
            },
            {
                name: "Salesforce",
                logo: "S",
                overview: "Cloud-based customer relationship management platform helping businesses connect with customers.",
                jobsCount: 678,
                industry: "CRM & Cloud",
                location: "San Francisco, CA"
            },
            {
                name: "IBM",
                logo: "I",
                overview: "Technology and consulting company providing enterprise solutions and AI-powered services.",
                jobsCount: 834,
                industry: "Enterprise Technology",
                location: "Armonk, NY"
            },
            {
                name: "Oracle",
                logo: "O",
                overview: "Enterprise software company specializing in database management and cloud applications.",
                jobsCount: 523,
                industry: "Database & Cloud",
                location: "Austin, TX"
            }
        ];

        // Pagination variables
        const companiesPerPage = 4;
        let currentPage = 1;
        const totalPages = Math.ceil(companiesData.length / companiesPerPage);

        // Function to render companies
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
                        <p class="company-overview">${company.overview}</p>
                        <div class="company-stats">
                            <div class="stat">
                                <span class="stat-number">${company.jobsCount} Jobs</span>
                            </div>
                        </div>
                    </div>
                `;
                companiesGrid.appendChild(companyCard);
            });
        }

        // Function to render pagination dots
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

        // Function to go to specific page
        function goToPage(page) {
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderCompanies(currentPage);
                renderPaginationDots();
                updatePaginationButtons();
            }
        }

        // Function to update pagination buttons
        function updatePaginationButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;

            prevBtn.style.opacity = currentPage === 1 ? '0.5' : '1';
            nextBtn.style.opacity = currentPage === totalPages ? '0.5' : '1';
        }

        // Event listeners for pagination buttons
        document.getElementById('prevBtn').addEventListener('click', () => {
            if (currentPage > 1) {
                goToPage(currentPage - 1);
            }
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            if (currentPage < totalPages) {
                goToPage(currentPage + 1);
            }
        });

        // Initialize the page
        document.addEventListener('DOMContentLoaded', () => {
            renderCompanies(currentPage);
            renderPaginationDots();
            updatePaginationButtons();
        });
    </script>
</body>
</html>