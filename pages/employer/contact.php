<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - MSTIP Seek Employee</title>
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

    <mai>
        <section class="advice-hero">
                    <div class="container">
                <h1 class="section-titles">Contact Us</h1>
                <p class="section-subtitle">Have questions? We’re here to help. Reach out to us and we’ll get back to you promptly.</p>
                    </div>
                    
                    <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                        <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
                    </svg>
                </section>

        <!-- Contact Form -->
        <section class="contact-form-section">
            <div class="container">
                <h2 class="section-title">Get In Touch</h2>
                <form class="contact-form" action="#" method="POST">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Your name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Your email" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="Subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="6" placeholder="Write your message here..." required></textarea>
                    </div>
                    <div class="button-wrapper">
                        <button type="submit" class="btns">Send Message</button>
                    </div>
                </form>
            </div>
        </section>

        <div class="separator">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100">
                <path fill="var(--separator-color)" 
                    d="M0,64L48,58.7C96,53,192,43,288,37.3C384,32,480,32,576,42.7C672,53,768,75,864,80C960,85,1056,75,1152,69.3C1248,64,1344,64,1392,64L1440,64L1440,0L0,0Z">
                </path>
            </svg>
        </div>

        <!-- Contact Info -->
        <section class="contact-info">
            <div class="container">
                <h2 class="section-title">Our Contact Info</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <h3>Address</h3>
                        <p>123 MSTIP Street, Makati City, Philippines</p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-phone contact-icon"></i>
                        <h3>Phone</h3>
                        <p>+63 912 345 6789</p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-envelope contact-icon"></i>
                        <h3>Email</h3>
                        <p>support@mstip.com</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include_once('../../includes/employer-footer.php') ?>
</body>
</html>
