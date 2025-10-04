<?php
include_once('../../includes/db_connect.php');
session_start();

// Check if user is logged in as Employer
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'Employer') {
    header("Location: ../../employer-login.php");
    exit();
}

$userCode = $_SESSION['user_code'];

// Check for session messages
$show_success = false;
$show_error = false;
$error_msg = '';

if (isset($_SESSION['profile_success'])) {
    $show_success = true;
    unset($_SESSION['profile_success']);
}

if (isset($_SESSION['profile_error'])) {
    $show_error = true;
    $error_msg = $_SESSION['profile_error'];
    unset($_SESSION['profile_error']);
}

// Fetch company profile data
$company = [];
$stmt = $conn->prepare("SELECT * FROM companies WHERE user_id = ?");
$stmt->bind_param("s", $userCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $company = $result->fetch_assoc();
} else {
    $company = [
        'company_name' => '', 'company_type' => '', 'government_agency' => '',
        'location' => '', 'website' => '', 'industry' => '', 'contact_number' => '',
        'email_address' => '', 'company_size' => '', 'founded_year' => '',
        'company_culture' => '', 'work_environment' => '', 'benefits' => '',
        'about_company' => '', 'profile_picture' => '', 'cover_image' => ''
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = trim($_POST['company_name']);
    $company_type = $_POST['company_type'];
    $government_agency = trim($_POST['government_agency']);
    $location = trim($_POST['location']);
    $website = trim($_POST['website']);
    $industry = trim($_POST['industry']);
    $contact_number = trim($_POST['contact_number']);
    $email_address = trim($_POST['email_address']);
    $company_size = trim($_POST['company_size']);
    $founded_year = $_POST['founded_year'] ?: null;
    
    // Separate values by comma
    $company_culture = implode(', ', array_map('trim', explode(',', $_POST['company_culture'])));
    $work_environment = implode(', ', array_map('trim', explode(',', $_POST['work_environment'])));
    $benefits = implode(', ', array_map('trim', explode(',', $_POST['benefits'])));
    
    $about_company = trim($_POST['about_company']);

    $profile_picture = $company['profile_picture'];
    $cover_image = $company['cover_image'];

    // Upload profile picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (in_array($_FILES['profile_picture']['type'], $allowed_types)) {
            $extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $new_filename = "company_profile_" . $userCode . "." . $extension;
            $upload_path = "../../assets/images/" . $new_filename;
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                $profile_picture = $new_filename;
            }
        }
    }

    // Upload cover image
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (in_array($_FILES['cover_image']['type'], $allowed_types)) {
            $extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $new_filename = "company_cover_" . $userCode . "." . $extension;
            $upload_path = "../../assets/images/" . $new_filename;
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_path)) {
                $cover_image = $new_filename;
            }
        }
    }

    if (empty($company['company_id'])) {
        $stmt = $conn->prepare("INSERT INTO companies (user_id, company_name, company_type, government_agency, location, website, industry, contact_number, email_address, company_size, founded_year, company_culture, work_environment, benefits, about_company, profile_picture, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssissssss", $userCode, $company_name, $company_type, $government_agency, $location, $website, $industry, $contact_number, $email_address, $company_size, $founded_year, $company_culture, $work_environment, $benefits, $about_company, $profile_picture, $cover_image);
    } else {
        $stmt = $conn->prepare("UPDATE companies SET company_name = ?, company_type = ?, government_agency = ?, location = ?, website = ?, industry = ?, contact_number = ?, email_address = ?, company_size = ?, founded_year = ?, company_culture = ?, work_environment = ?, benefits = ?, about_company = ?, profile_picture = ?, cover_image = ? WHERE user_id = ?");
        $stmt->bind_param("sssssssssisssssss", $company_name, $company_type, $government_agency, $location, $website, $industry, $contact_number, $email_address, $company_size, $founded_year, $company_culture, $work_environment, $benefits, $about_company, $profile_picture, $cover_image, $userCode);
    }

    if ($stmt->execute()) {
        $_SESSION['profile_success'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['profile_error'] = "Error updating profile. Please try again.";
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
    <title>Company Profile - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/homepage.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/employer.css">
    <link rel="stylesheet" href="../../assets/css/sweetalert.css">
    <link rel="stylesheet" href="../../assets/css/text.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        
    </style>
</head>
<body>
    <?php include_once('../../includes/log-employer-header.php') ?>

    <main>
        <div class="profile-container">
            <form method="POST" enctype="multipart/form-data" id="companyProfileForm">
                <!-- Images Card - Facebook Style -->
                <div class="cards">
                    <div class="fb-header-section">
                        <!-- Cover Image -->
                        <div class="cover-upload">
                            <input type="file" name="cover_image" accept="image/*" onchange="previewImage(this, 'coverPreview')" id="coverInput">
                            <div class="cover-display" id="coverPreview">
                                <?php if (!empty($company['cover_image'])): ?>
                                    <img src="../../assets/images/<?php echo $company['cover_image']; ?>" alt="Cover">
                                <?php else: ?>
                                    <div class="cover-placeholder">
                                        <i class="fas fa-camera"></i>
                                        <span>Add Cover Photo</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="edit-cover-btn" onclick="document.getElementById('coverInput').click()">
                                <i class="fas fa-camera"></i> Edit Cover
                            </button>
                        </div>

                        <!-- Profile Picture Overlay -->
                        <div class="profile-overlay">
                            <div class="profile-upload">
                                <input type="file" name="profile_picture" accept="image/*" onchange="previewImage(this, 'profilePreview')" id="profileInput">
                                <div class="profile-display" id="profilePreview">
                                    <?php if (!empty($company['profile_picture'])): ?>
                                        <img src="../../assets/images/<?php echo $company['profile_picture']; ?>" alt="Logo">
                                    <?php else: ?>
                                        <div class="profile-placeholder">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="edit-profile-btn" onclick="document.getElementById('profileInput').click()">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-info-circle"></i> Basic Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="company_name" class="required">Company Name</label>
                                <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company['company_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="company_type" class="required">Company Type</label>
                                <select id="company_type" name="company_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Government" <?php echo $company['company_type'] == 'Government' ? 'selected' : ''; ?>>Government</option>
                                    <option value="Private" <?php echo $company['company_type'] == 'Private' ? 'selected' : ''; ?>>Private</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="gov_agency" style="display: <?php echo $company['company_type'] == 'Government' ? 'block' : 'none'; ?>;">
                            <label for="government_agency">Government Agency</label>
                            <input type="text" id="government_agency" name="government_agency" value="<?php echo htmlspecialchars($company['government_agency']); ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="industry">Industry</label>
                                <input type="text" id="industry" name="industry" value="<?php echo htmlspecialchars($company['industry']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($company['location']); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="company_size">Company Size</label>
                                <input type="text" id="company_size" name="company_size" value="<?php echo htmlspecialchars($company['company_size']); ?>" placeholder="e.g., 50-100 or 100+">
                            </div>
                            <div class="form-group">
                                <label for="founded_year">Founded Year</label>
                                <input type="number" id="founded_year" name="founded_year" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo $company['founded_year']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-address-book"></i> Contact Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email_address">Email Address</label>
                                <input type="email" id="email_address" name="email_address" value="<?php echo htmlspecialchars($company['email_address']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="tel" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($company['contact_number']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="url" id="website" name="website" value="<?php echo htmlspecialchars($company['website']); ?>" placeholder="https://">
                        </div>
                    </div>
                </div>

                <!-- Company Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-file-alt"></i> Company Details</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="about_company">About Company</label>
                            <textarea id="about_company" name="about_company"><?php echo htmlspecialchars($company['about_company']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="company_culture">Company Culture (separate by comma)</label>
                            <textarea id="company_culture" name="company_culture" placeholder="e.g., Collaborative, Innovative, Team-oriented"><?php echo htmlspecialchars($company['company_culture']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="work_environment">Work Environment (separate by comma)</label>
                            <textarea id="work_environment" name="work_environment" placeholder="e.g., Remote-friendly, Flexible hours, Modern office"><?php echo htmlspecialchars($company['work_environment']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="benefits">Benefits & Perks (separate by comma)</label>
                            <textarea id="benefits" name="benefits" placeholder="e.g., Health insurance, Paid time off, Professional development"><?php echo htmlspecialchars($company['benefits']); ?></textarea>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                            <i class="fas fa-arrow-left"></i>&nbsp;Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>&nbsp; Save Profile
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php include_once('../../includes/employer-footer.php') ?>
    <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Show/hide government agency field
        document.getElementById('company_type').addEventListener('change', function() {
            document.getElementById('gov_agency').style.display = this.value === 'Government' ? 'block' : 'none';
        });

        // Image preview
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    if (previewId === 'coverPreview') {
                        preview.innerHTML = '<img src="' + e.target.result + '" alt="Cover">';
                    } else {
                        preview.innerHTML = '<img src="' + e.target.result + '" alt="Logo">';
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // SweetAlert notifications
        <?php if ($show_success): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Company profile has been updated successfully.',
                confirmButtonText: 'OK'
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