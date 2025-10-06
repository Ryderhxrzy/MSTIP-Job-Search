<?php
include_once('../../includes/db_connect.php');
session_start();

// Check if user is logged in as Employer
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'Employer') {
    header("Location: ../../employer-login.php");
    exit();
}

$userCode = $_SESSION['user_code'];

// Get company information
$company_id = null;
$company_name = '';
$stmt = $conn->prepare("SELECT company_id, company_name FROM companies WHERE user_id = ?");
$stmt->bind_param("s", $userCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $company = $result->fetch_assoc();
    $company_id = $company['company_id'];
    $company_name = $company['company_name'];
}

// Dashboard Statistics
$total_jobs = 0;
$open_jobs = 0;
$total_applications = 0;
$pending_applications = 0;
$reviewed_applications = 0;
$accepted_applications = 0;
$rejected_applications = 0;

if ($company_id) {
    // Total jobs
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM job_listings WHERE company_id = ?");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_jobs = $result->fetch_assoc()['total'];

    // Open jobs
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM job_listings WHERE company_id = ? AND status = 'Open'");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $open_jobs = $result->fetch_assoc()['total'];

    // Total applications
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM applications a JOIN job_listings j ON a.job_id = j.job_id WHERE j.company_id = ?");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_applications = $result->fetch_assoc()['total'];

    // Application status counts - FIXED: Specify a.status to avoid ambiguity
    $stmt = $conn->prepare("SELECT a.status, COUNT(*) as count FROM applications a JOIN job_listings j ON a.job_id = j.job_id WHERE j.company_id = ? GROUP BY a.status");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        switch ($row['status']) {
            case 'Pending':
                $pending_applications = $row['count'];
                break;
            case 'Reviewed':
                $reviewed_applications = $row['count'];
                break;
            case 'Accepted':
                $accepted_applications = $row['count'];
                break;
            case 'Rejected':
                $rejected_applications = $row['count'];
                break;
        }
    }

    // Recent jobs (last 5)
    $recent_jobs_stmt = $conn->prepare("SELECT * FROM job_listings WHERE company_id = ? ORDER BY posted_date DESC LIMIT 5");
    $recent_jobs_stmt->bind_param("i", $company_id);
    $recent_jobs_stmt->execute();
    $recent_jobs = $recent_jobs_stmt->get_result();

    // Recent applications (last 5)
    $recent_apps_stmt = $conn->prepare("SELECT a.*, j.job_title, g.first_name, g.last_name 
                                       FROM applications a 
                                       JOIN job_listings j ON a.job_id = j.job_id 
                                       JOIN graduate_information g ON a.user_id = g.user_id 
                                       WHERE j.company_id = ? 
                                       ORDER BY a.application_date DESC 
                                       LIMIT 5");
    $recent_apps_stmt->bind_param("i", $company_id);
    $recent_apps_stmt->execute();
    $recent_applications = $recent_apps_stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard - MSTIP Seek Employee</title>
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/homepage.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/employer.css">
    <link rel="stylesheet" href="../../assets/css/sweetalert.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Dashboard Styles */
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-card:nth-child(1) .stat-icon { background: #4f46e5; }
.stat-card:nth-child(2) .stat-icon { background: #10b981; }
.stat-card:nth-child(3) .stat-icon { background: #f59e0b; }
.stat-card:nth-child(4) .stat-icon { background: #ef4444; }

.stat-info h3 {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    color: #1f2937;
}

.stat-info p {
    margin: 5px 0 0 0;
    color: #6b7280;
    font-size: 0.9rem;
}

.application-stats {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.application-stats h2 {
    margin-bottom: 20px;
    color: #1f2937;
    font-size: 1.5rem;
}

.quick-actions {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.quick-actions h2 {
    margin-bottom: 20px;
    color: #1f2937;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    text-decoration: none;
    color: #374151;
    transition: all 0.3s ease;
}

.action-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-border);
}

.action-btn i {
    font-size: 24px;
    margin-bottom: 10px;
}

.action-btn span {
    font-weight: 600;
}

.dashboard-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.dashboard-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    padding: 20px 25px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.view-all {
    margin-top: 2px;
    margin-left: 20px;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
}

.card-body {
    padding: 25px;
}

.recent-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.recent-item {
    padding: 15px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.recent-item:hover {
    background: #f8fafc;
}

.item-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.item-main h4 {
    margin: 0;
    color: #1f2937;
    flex: 1;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-open { background: #d1fae5; color: #065f46; }
.status-closed { background: #fee2e2; color: #991b1b; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-reviewed { background: #dbeafe; color: #1e40af; }
.status-accepted { background: #d1fae5; color: #065f46; }
.status-rejected { background: #fee2e2; color: #991b1b; }

.item-details {
    display: flex;
    gap: 15px;
    font-size: 0.85rem;
    color: #6b7280;
}

.item-details span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #d1d5db;
}

.empty-state p {
    margin-bottom: 15px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-content {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
    }
    
    .item-details {
        flex-direction: column;
        gap: 5px;
    }
}
    </style>
</head>
<body>
    <?php include_once('../../includes/log-employer-header.php') ?>

    <main>
        <section class="advice-hero">
            <div class="container">
                <h1 class="section-titles">Employer Dashboard</h1>
                <p class="section-subtitle">Welcome back, <?php echo htmlspecialchars($company_name); ?>! Here's your hiring overview.</p>
            </div>
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>

        <div class="dashboard-container">
            <!-- Job Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_jobs; ?></h3>
                        <p>Total Jobs Posted</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $open_jobs; ?></h3>
                        <p>Open Positions</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_applications; ?></h3>
                        <p>Total Applications</p>
                    </div>
                </div>
            </div>

            <!-- Application Status Statistics -->
            <div class="application-stats">
                <h2>Application Status Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #f59e0b;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $pending_applications; ?></h3>
                            <p>Pending Applications</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #3b82f6;">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $reviewed_applications; ?></h3>
                            <p>Reviewed Applications</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #10b981;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $accepted_applications; ?></h3>
                            <p>Accepted Applications</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #ef4444;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $rejected_applications; ?></h3>
                            <p>Rejected Applications</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="post-a-job.php" class="action-btn">
                        <i class="fas fa-plus"></i>
                        <span>Post New Job</span>
                    </a>
                    <a href="manage-jobs.php" class="action-btn">
                        <i class="fas fa-tasks"></i>
                        <span>Manage Jobs</span>
                    </a>
                    <a href="applicants.php" class="action-btn">
                        <i class="fas fa-users"></i>
                        <span>View Applications</span>
                    </a>
                    <a href="company-profile.php" class="action-btn">
                        <i class="fas fa-building"></i>
                        <span>Company Profile</span>
                    </a>
                </div>
            </div>

            <div class="dashboard-content">
                <!-- Recent Job Listings -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-clock"></i> Recent Job Postings</h3>
                        <a href="manage-jobs.php" class="view-all">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if ($recent_jobs->num_rows > 0): ?>
                            <div class="recent-list">
                                <?php while($job = $recent_jobs->fetch_assoc()): ?>
                                    <div class="recent-item">
                                        <div class="item-main">
                                            <h4><?php echo htmlspecialchars($job['job_title']); ?></h4>
                                            <span class="status-badge status-<?php echo strtolower($job['status']); ?>">
                                                <?php echo htmlspecialchars($job['status']); ?>
                                            </span>
                                        </div>
                                        <div class="item-details">
                                            <span><i class="fas fa-users"></i> <?php echo $job['slots_available']; ?> slots</span>
                                            <span><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($job['posted_date'])); ?></span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-briefcase"></i>
                                <p>No jobs posted yet</p>
                                <a href="post-a-job.php" class="btn btn-primary">Post Your First Job</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Applications -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-check"></i> Recent Applications</h3>
                        <a href="applicants.php" class="view-all">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if ($recent_applications->num_rows > 0): ?>
                            <div class="recent-list">
                                <?php while($application = $recent_applications->fetch_assoc()): ?>
                                    <div class="recent-item">
                                        <div class="item-main">
                                            <h4><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></h4>
                                            <span class="status-badge status-<?php echo strtolower($application['status']); ?>">
                                                <?php echo htmlspecialchars($application['status']); ?>
                                            </span>
                                        </div>
                                        <div class="item-details">
                                            <span><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($application['job_title']); ?></span>
                                            <span><i class="fas fa-clock"></i> <?php echo date('M d, Y', strtotime($application['application_date'])); ?></span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-file-alt"></i>
                                <p>No applications yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once('../../includes/employer-footer.php') ?>
    <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>