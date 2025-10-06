<?php 
    session_start();
    include_once('includes/db_connect.php');

    // Check for session messages
    $show_success = false;
    $show_error = false;
    $error_msg = '';

    if (isset($_SESSION['contact_success'])) {
        $show_success = true;
        unset($_SESSION['contact_success']);
    }

    if (isset($_SESSION['contact_error'])) {
        $show_error = true;
        $error_msg = $_SESSION['contact_error'];
        unset($_SESSION['contact_error']);
    }

    // Handle contact form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);

        // Validate inputs
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $_SESSION['contact_error'] = "All fields are required.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['contact_error'] = "Please enter a valid email address.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $_SESSION['contact_success'] = true;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['contact_error'] = "Error sending message. Please try again.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/employer.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <?php include_once('includes/header.php') ?>

    <main>
        <section class="advice-hero">
            <div class="container">
                <h1 class="section-titles">Contact Us</h1>
                <p class="section-subtitle">Have questions? We're here to help. Reach out to us and we'll get back to you promptly.</p>
            </div>
            
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>

        <!-- Contact Form -->
        <section class="contact-form-section">
            <div class="container">
                <h2 class="section-title">Get In Touch</h2>
                <form class="contact-form" method="POST">
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
                        <button type="submit" class="btns">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
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
        <section class="contact-infos">
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

    <?php include_once('includes/footer.php') ?>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert notifications
        <?php if ($show_success): ?>
            Swal.fire({
                icon: 'success',
                title: 'Message Sent!',
                text: 'Thank you for contacting us. We will get back to you shortly.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Clear form fields
                    document.querySelector('.contact-form').reset();
                }
            });
        <?php endif; ?>

        <?php if ($show_error): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo $error_msg; ?>',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>
</html>