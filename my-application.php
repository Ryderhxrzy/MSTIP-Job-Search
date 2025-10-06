<?php 
include_once('includes/db_connect.php');
session_start();

// Check if user is logged in as Graduate
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'Graduate') {
    header("Location: login.php");
    exit();
}

$userCode = $_SESSION['user_code'];

// Check for session messages
$show_success = false;
$show_error = false;
$success_msg = '';
$error_msg = '';

if (isset($_SESSION['application_success'])) {
    $show_success = true;
    $success_msg = $_SESSION['application_success'];
    unset($_SESSION['application_success']);
}

if (isset($_SESSION['application_error'])) {
    $show_error = true;
    $error_msg = $_SESSION['application_error'];
    unset($_SESSION['application_error']);
}

// Handle withdraw application
if (isset($_GET['withdraw']) && !empty($_GET['withdraw'])) {
    $application_id = intval($_GET['withdraw']);
    
    // Check if application belongs to user and is still pending
    $check_stmt = $conn->prepare("SELECT status FROM applications WHERE application_id = ? AND user_id = ?");
    $check_stmt->bind_param("is", $application_id, $userCode);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $app = $check_result->fetch_assoc();
        if ($app['status'] === 'Pending') {
            $delete_stmt = $conn->prepare("DELETE FROM applications WHERE application_id = ? AND user_id = ?");
            $delete_stmt->bind_param("is", $application_id, $userCode);
            
            if ($delete_stmt->execute()) {
                $_SESSION['application_success'] = "Application withdrawn successfully.";
            } else {
                $_SESSION['application_error'] = "Error withdrawing application.";
            }
        } else {
            $_SESSION['application_error'] = "Cannot withdraw application with status: " . $app['status'];
        }
    } else {
        $_SESSION['application_error'] = "Application not found.";
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch applications with job and company details
$query = "SELECT a.application_id, a.user_id, a.job_id, a.application_date, a.status, a.remarks,
          jl.job_title, jl.job_position, jl.job_category, jl.slots_available, jl.salary_range, 
          jl.job_description, jl.qualifications, jl.job_type_shift, jl.application_deadline, 
          jl.posted_date, jl.company_id,
          c.company_name, c.location as company_location, c.profile_picture as company_logo
          FROM applications a
          JOIN job_listings jl ON a.job_id = jl.job_id
          JOIN companies c ON jl.company_id = c.company_id
          WHERE a.user_id = ?
          ORDER BY a.application_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userCode);
$stmt->execute();
$result = $stmt->get_result();
$applications = $result->fetch_all(MYSQLI_ASSOC);

// Count applications by status
$statusCounts = [
    'Pending' => 0,
    'Reviewed' => 0,
    'Accepted' => 0,
    'Rejected' => 0
];

// Debug: Check if applications are being fetched
if (!empty($applications)) {
    foreach ($applications as $app) {
        $status = trim($app['status']); // Remove any whitespace
        if (array_key_exists($status, $statusCounts)) {
            $statusCounts[$status]++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/employer.css">
    <link rel="stylesheet" href="assets/css/company.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="stylesheet" href="assets/css/text.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Status Badge Styles */
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.reviewed {
            background: #cfe2ff;
            color: #084298;
        }

        .status-badge.accepted {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-badge.rejected {
            background: #f8d7da;
            color: #842029;
        }

        /* Application Stats */
        .application-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--surface-color);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.pending {
            background: #fff3cd;
            color: #856404;
        }

        .stat-icon.reviewed {
            background: #cfe2ff;
            color: #084298;
        }

        .stat-icon.accepted {
            background: #d1e7dd;
            color: #0f5132;
        }

        .stat-icon.rejected {
            background: #f8d7da;
            color: #842029;
        }

        .stat-content h3 {
            margin: 0;
            font-size: 1.8rem;
            color: #333;
        }

        .stat-content p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        /* Withdraw Button */
        .btn-withdraw {
            padding: 0.75rem 1.5rem;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-withdraw:hover {
            background: #c82333;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .btn-withdraw:disabled {
            background: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-withdraw:disabled:hover {
            box-shadow: none;
        }

        /* View Details Button */
        .btn-details {
            padding: 0.75rem 1.5rem;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-details:hover {
            background: #5a6268;
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .application-date {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .application-date i {
            color: var(--accent-color);
        }

        .remarks-section {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid var(--accent-color);
        }

        .remarks-section h5 {
            margin: 0 0 0.5rem 0;
            color: #333;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remarks-section p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 0.75rem 1.5rem;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #666;
        }

        .filter-tab.active {
            background: var(--primary-color);
            border-color: var(--primary-border);
            color: white;
        }

        .filter-tab:hover {
            border-color: var(--primary-border);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .application-stats {
                grid-template-columns: 1fr;
            }

            .btn-withdraw,
            .btn-details {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
            }
            
            .job-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .job-actions button {
                width: 100%;
            }

            .filter-tabs {
                gap: 0.5rem;
            }

            .filter-tab {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <?php include_once('includes/header.php') ?>

    <main>
        <section class="advice-hero">
            <div class="container">
                <h1 class="section-titles">My Applications</h1>
                <p class="section-subtitle">Track the status of the jobs you’ve applied for.</p>
            </div>
            <!-- Decorative pattern behind hero -->
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>
        <div class="profile-container">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-file-alt"></i> My Applications</h2>
                    <p style="margin-top: 0.5rem; color: #666; font-size: 0.9rem;">
                        Track all your job applications in one place (<?php echo count($applications); ?> total)
                    </p>
                </div>

                <div class="card-body">
                    <!-- Application Statistics -->
                    <div class="application-stats">
                        <div class="stat-card">
                            <div class="stat-icon pending">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo $statusCounts['Pending']; ?></h3>
                                <p>Pending</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon reviewed">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo $statusCounts['Reviewed']; ?></h3>
                                <p>Reviewed</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon accepted">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo $statusCounts['Accepted']; ?></h3>
                                <p>Accepted</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon rejected">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo $statusCounts['Rejected']; ?></h3>
                                <p>Rejected</p>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="filter-tabs">
                        <div class="filter-tab active" data-filter="all">All Applications</div>
                        <div class="filter-tab" data-filter="Pending">Pending</div>
                        <div class="filter-tab" data-filter="Reviewed">Reviewed</div>
                        <div class="filter-tab" data-filter="Accepted">Accepted</div>
                        <div class="filter-tab" data-filter="Rejected">Rejected</div>
                    </div>

                    <?php if (count($applications) > 0): ?>
                        <div class="job-listings-grid">
                            <?php foreach ($applications as $app): 
                                $isDeafJob = $app['job_category'] === 'deaf';
                                $categoryBadgeClass = $isDeafJob ? 'job-category-badge deaf' : 'job-category-badge normal';
                                $categoryIcon = $isDeafJob ? 'fa-deaf' : 'fa-user';
                                $categoryLabel = $isDeafJob ? 'Deaf-Friendly' : 'Open to All';
                                
                                // Status badge
                                $statusClass = strtolower($app['status']);
                                $statusIcons = [
                                    'pending' => 'fa-clock',
                                    'reviewed' => 'fa-eye',
                                    'accepted' => 'fa-check-circle',
                                    'rejected' => 'fa-times-circle'
                                ];
                                
                                // Default icon if status not found
                                $statusIcon = isset($statusIcons[$statusClass]) ? $statusIcons[$statusClass] : 'fa-circle';
                                
                                // Format dates
                                $applicationDate = new DateTime($app['application_date']);
                                $applicationDateFormatted = $applicationDate->format('F d, Y');
                                
                                $postedDate = new DateTime($app['posted_date']);
                                $now = new DateTime();
                                $daysAgo = $now->diff($postedDate)->days;
                                $timeAgo = $daysAgo == 0 ? 'Today' : ($daysAgo == 1 ? 'Yesterday' : $daysAgo . ' days ago');
                                
                                $deadline = 'Not specified';
                                if (!empty($app['application_deadline'])) {
                                    $deadlineDate = new DateTime($app['application_deadline']);
                                    $deadline = $deadlineDate->format('F d, Y');
                                }
                                
                                $logoPath = !empty($app['company_logo']) ? 'assets/images/' . $app['company_logo'] : 'assets/images/background1.jpeg';
                            ?>
                            <div class="job-listing-card" data-status="<?php echo $app['status']; ?>">
                                <!-- Company Header -->
                                <div class="job-company-header">
                                    <img src="<?php echo $logoPath; ?>" alt="<?php echo htmlspecialchars($app['company_name']); ?>" class="company-logo-small" onerror="this.src='assets/images/background1.jpeg'">
                                    <div class="company-info">
                                        <h5 class="company-name"><?php echo htmlspecialchars($app['company_name']); ?></h5>
                                        <span class="application-date">
                                            <i class="fas fa-calendar-check"></i> Applied on <?php echo $applicationDateFormatted; ?>
                                        </span>
                                    </div>
                                    <span class="status-badge <?php echo $statusClass; ?>">
                                        <i class="fas <?php echo $statusIcon; ?>"></i>
                                        <?php echo $app['status']; ?>
                                    </span>
                                </div>

                                <div class="job-card-header">
                                    <div class="job-title-wrapper">
                                        <h4 class="job-title"><?php echo htmlspecialchars($app['job_title']); ?></h4>
                                        <span class="<?php echo $categoryBadgeClass; ?>">
                                            <i class="fas <?php echo $categoryIcon; ?>"></i><?php echo $categoryLabel; ?>
                                        </span>
                                    </div>
                                    <div class="job-posted-date">
                                        <i class="fas fa-clock"></i>
                                        <span>Posted <?php echo $timeAgo; ?></span>
                                    </div>
                                </div>

                                <div class="job-basic-info">
                                    <div class="job-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo htmlspecialchars($app['company_location']); ?></span>
                                    </div>
                                    <div class="job-meta-item">
                                        <i class="fas fa-briefcase"></i>
                                        <span><?php echo htmlspecialchars($app['job_type_shift']); ?></span>
                                    </div>
                                </div>

                                <div class="job-details-grid">
                                    <div class="detail-item">
                                        <i class="fas fa-layer-group"></i>
                                        <div class="detail-content">
                                            <span class="detail-label">Position</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($app['job_position']); ?></span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-dollar-sign"></i>
                                        <div class="detail-content">
                                            <span class="detail-label">Salary</span>
                                            <span class="detail-value"><?php echo !empty($app['salary_range']) ? htmlspecialchars($app['salary_range']) : 'Negotiable'; ?></span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-users"></i>
                                        <div class="detail-content">
                                            <span class="detail-label">Slots</span>
                                            <span class="detail-value"><?php echo $app['slots_available']; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="job-description-section">
                                    <h5>Job Description</h5>
                                    <p class="job-description-text"><?php echo htmlspecialchars($app['job_description']); ?></p>
                                </div>

                                <div class="job-description-section">
                                    <h5>Qualification</h5>
                                    <p class="job-description-text"><?php echo htmlspecialchars($app['qualifications']); ?></p>
                                </div>

                                <?php if (!empty($app['remarks'])): ?>
                                <div class="remarks-section">
                                    <h5><i class="fas fa-comment-dots"></i> Remarks from Employer</h5>
                                    <p><?php echo htmlspecialchars($app['remarks']); ?></p>
                                </div>
                                <?php endif; ?>

                                <div class="job-deadline-contact">
                                    <div class="deadline-info">
                                        <i class="fas fa-calendar-alt"></i>
                                        <div>
                                            <span class="deadline-label">Application Deadline</span>
                                            <span class="deadline-date"><?php echo $deadline; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="job-actions">
                                    <button class="btn-details" onclick="window.location.href='company-details.php?id=<?php echo $app['company_id']; ?>'">
                                        <i class="fas fa-eye"></i>View Company
                                    </button>
                                    <?php if ($app['status'] === 'Pending'): ?>
                                    <button class="btn-withdraw" onclick="confirmWithdraw(<?php echo $app['application_id']; ?>)">
                                        <i class="fas fa-times"></i>Withdraw Application
                                    </button>
                                    <?php else: ?>
                                    <button class="btn-withdraw" disabled title="Cannot withdraw <?php echo strtolower($app['status']); ?> applications">
                                        <i class="fas fa-ban"></i>Cannot Withdraw
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-jobs-message" style="text-align: center; padding: 3rem 1rem;">
                            <i class="fas fa-file-alt" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
                            <h3 style="color: #666; margin-bottom: 0.5rem;">No Applications Yet</h3>
                            <p style="color: #999; margin-bottom: 1.5rem;">You haven't applied to any jobs yet. Start exploring opportunities and submit your applications.</p>
                            <button class="btn-apply" onclick="window.location.href='job-listings.php'" style="margin: 0 auto;">
                                <i class="fas fa-search"></i>Browse Jobs
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include_once('includes/footer.php') ?>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmWithdraw(applicationId) {
            Swal.fire({
                title: 'Withdraw Application?',
                text: "Are you sure you want to withdraw this application? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, withdraw it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?withdraw=' + applicationId;
                }
            });
        }

        // Filter functionality
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active tab
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                const cards = document.querySelectorAll('.job-listing-card');
                
                cards.forEach(card => {
                    if (filter === 'all') {
                        card.style.display = 'block';
                    } else {
                        const status = card.getAttribute('data-status');
                        card.style.display = status === filter ? 'block' : 'none';
                    }
                });
            });
        });

        // SweetAlert notifications
        <?php if ($show_success): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $success_msg; ?>',
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