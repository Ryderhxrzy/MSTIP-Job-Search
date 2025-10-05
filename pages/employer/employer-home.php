<?php 
  include_once('../../includes/db_connect.php');
  session_start();

  $isLoggedIn = false;

  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && $_SESSION['user_type'] === 'Employer') {
      $isLoggedIn = true;
  } elseif (isset($_COOKIE['employer_remember'])) {
      // Optional: verify cookie exists in database before trusting it
      $userCode = $_COOKIE['employer_remember'];
      $stmt = $conn->prepare("SELECT id FROM users WHERE user_id = ? AND user_type = 'Employer' AND status = 'Active'");
      $stmt->bind_param("s", $userCode);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) $isLoggedIn = true;
      $stmt->close();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/homepage.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/employer.css">
    <link rel="shortcut icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="../../assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once('../../includes/employer-header.php') ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
  <svg class="hero-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
    <g fill="rgba(255,255,255,0.06)">
      <path d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
      <path d="M0,240 C480,360 960,120 1440,240 L1440,400 L0,400 Z"></path>
      <path d="M0,320 C480,440 960,200 1440,320 L1440,400 L0,400 Z"></path>
    </g>
  </svg>

  <div class="container">
    <div class="hero-content">
      <h1 class="hero-title">Hire the Right Talent Faster with MSTIP</h1>
      <p class="hero-subtitle">Post jobs, reach thousands of qualified candidates, and build your winning team in just a few steps.</p>
      <a href="../../employer-register.php" class="btn-primarys">Get Started</a>

      <!-- Stats Row -->
      <div class="hero-stats">
        <div class="stat-item">
          <i class="fas fa-users"></i>
          <h3>10,000+</h3>
          <p>Active Job Seekers</p>
        </div>
        <div class="stat-item">
          <i class="fas fa-briefcase"></i>
          <h3>5,000+</h3>
          <p>Jobs Posted</p>
        </div>
        <div class="stat-item">
          <i class="fas fa-building"></i>
          <h3>1,200+</h3>
          <p>Hiring Companies</p>
        </div>
      </div>
    </div>
  </div>
</section>

        <!-- 3 Steps Section -->
        <section class="steps">
          <div class="container">
            <h2 class="section-title">Start Hiring in 3 Simple Steps</h2>
            
                <div class="steps-grid">
                    <div class="step">
                        <i class="fas fa-user-plus step-icon"></i>
                        <h3>Register Online</h3>
                        <p>Create your free employer account in minutes and set up your company profile.</p>
                    </div>
                    <div class="step">
                        <i class="fas fa-briefcase step-icon"></i>
                        <h3>Post a Job</h3>
                        <p>Publish your job openings and instantly reach qualified job seekers.</p>
                    </div>
                    <div class="step">
                        <i class="fas fa-filter step-icon"></i>
                        <h3>Sort Applicants</h3>
                        <p>Review, filter, and shortlist the best candidates all in one place.</p>
                    </div>
                </div>
            </div>
        </section>
        <div class="separator">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100">
    <path fill="var(--separator-color)" 
      d="M0,64L48,58.7C96,53,192,43,288,37.3C384,32,480,32,576,42.7C672,53,768,75,864,80C960,85,1056,75,1152,69.3C1248,64,1344,64,1392,64L1440,64L1440,0L0,0Z">
    </path>
  </svg>
</div>

        <!-- Why Choose MSTIP -->
        <section class="why-choose">
            <h2 class="section-title">Why Employers Choose MSTIP</h2>
            <div class="container">
                <div class="why-grid">
                    <div class="why-item">
                        <i class="fas fa-bullseye"></i>
                        <h3>Targeted Reach</h3>
                        <p>Connect directly with job seekers actively looking for opportunities.</p>
                    </div>
                    <div class="why-item">
                        <i class="fas fa-bolt"></i>
                        <h3>Quick & Easy</h3>
                        <p>Post jobs in minutes with our simple, user-friendly platform.</p>
                    </div>
                    <div class="why-item">
                        <i class="fas fa-headset"></i>
                        <h3>Dedicated Support</h3>
                        <p>Our team is here to guide you throughout the hiring process.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="featured">
        <!-- Background SVG Pattern -->
        <svg class="featured-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
          <defs>
            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
              <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(0,0,0,0.10)" stroke-width="1"/>
            </pattern>
          </defs>
          <rect width="100%" height="100%" fill="url(#grid)" />
          <g fill="rgba(0,0,0,0.08)">
            <path d="M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,133.3C672,117,768,107,864,122.7C960,139,1056,181,1152,181.3C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
          </g>
        </svg>

        <div class="container">
          <h2 class="section-titles">Trusted by Leading Companies/Organization</h2>

          <!-- Logos marquee -->
          <div class="logos-wrapper">
            <div class="logos-track">
              <?php
                $query = "SELECT profile_picture FROM companies WHERE profile_picture IS NOT NULL AND profile_picture != ''";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    $imagePath = "../../assets/images/" . htmlspecialchars($row['profile_picture']);
                    echo '<img src="' . $imagePath . '" alt="Company Logo">';
                  }
                } else {
                  echo '<p style="color: #888;">No company logos available.</p>';
                }
              ?>
            </div>

            <!-- Gradient fade edges -->
            <div class="fade fade-left"></div>
            <div class="fade fade-right"></div>
          </div>
        </div>
      </section>

        <!-- FAQ -->
        <section class="faq">
            <div class="container">
<h2 class="section-title">Frequently Asked Questions</h2>
        <div class="faq-list">
            <div class="faq-item">
            <h4>Is it free to post a job?</h4>
            <p>Yes, you can start with one free job post.</p>
            </div>
            <div class="faq-item">
            <h4>Can I edit or delete my job post?</h4>
            <p>Absolutely! You can manage posts anytime from your dashboard.</p>
            </div>
            <div class="faq-item">
            <h4>How long does it take for my job to appear?</h4>
            <p>Your job post is live instantly after submission.</p>
            </div>
        </div>
            </div>
        
        </section>

        <!-- Call to Action -->
        <?php if (!$isLoggedIn): ?>
          <section class="cta">
            <div class="container">
              <h2 class="newsletter-title">Ready to Hire Your Next Star Employee?</h2>
              <p class="newsletter-subtitle">
                Join thousands of companies already hiring top talent. Post jobs, connect with skilled candidates, and start <br>
                building your winning team today.
              </p>
              <form class="email-login-form">
                <div class="email-input-wrapper">
                  <input type="email" placeholder="Enter your email" required>
                  <button type="submit" class="btn-primary">Register</button>
                </div>
              </form>
              <p class="newsletter-hint">
                Learn more about 
                <a href="#" class="register-link">MSTIP</a>
              </p>
            </div>
          </section>
          <?php endif; ?>


    </main>

    <?php include_once('../../includes/employer-footer.php') ?>
    <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/profile.js"></script>
    
    <script>
     const track = document.querySelector(".logos-track");
const logos = document.querySelectorAll(".logos-track img");
let speed = 1;          // bilis ng galaw
let minSpacing = 40;    // minimum gap sa mobile

function getContainerWidth() {
  return track.offsetWidth;
}

// Initialize positions
function initPositions() {
  const containerWidth = getContainerWidth();
  let spacing;

  // Dynamic spacing depende sa screen width at number of logos
  if (window.innerWidth <= 480) spacing = 60;        // small mobile
  else if (window.innerWidth <= 768) spacing = 100;  // tablet
  else spacing = Math.max(minSpacing, containerWidth / logos.length); // desktop

  logos.forEach((logo, i) => {
    logo.dataset.index = i;
    logo.style.left = containerWidth + i * spacing + "px";
  });

  track.dataset.spacing = spacing;
}

function animate() {
  const containerWidth = getContainerWidth();
  const spacing = parseFloat(track.dataset.spacing);

  logos.forEach((logo) => {
    let current = parseFloat(logo.style.left);
    current -= speed;

    // kapag lumampas sa kaliwa, ilipat sa pinakakanan
    if (current < -logo.width) {
      let maxX = Math.max(...Array.from(logos).map(l => parseFloat(l.style.left)));
      current = maxX + spacing;
    }

    logo.style.left = current + "px";
  });

  requestAnimationFrame(animate);
}

// Run
initPositions();
animate();

// Update positions sa window resize
window.addEventListener("resize", initPositions);
    </script>
</body>
</html>