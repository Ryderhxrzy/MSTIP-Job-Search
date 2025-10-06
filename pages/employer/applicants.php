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
$success_msg = $error_msg = '';

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

// Get company_id
$company_id = null;
$stmt = $conn->prepare("SELECT company_id FROM companies WHERE user_id = ?");
$stmt->bind_param("s", $userCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $company = $result->fetch_assoc();
    $company_id = $company['company_id'];
}

// Handle application status update with remarks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'])) {
    $application_id = intval($_POST['application_id']);
    $new_status = $_POST['status'];
    $remarks = trim($_POST['remarks'] ?? '');

    // Verify that the application belongs to the employer's company
    $verify_stmt = $conn->prepare("SELECT a.* FROM applications a 
                                  JOIN job_listings j ON a.job_id = j.job_id 
                                  WHERE a.application_id = ? AND j.company_id = ?");
    $verify_stmt->bind_param("ii", $application_id, $company_id);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();

    if ($verify_result->num_rows > 0) {
        $update_stmt = $conn->prepare("UPDATE applications SET status = ?, remarks = ? WHERE application_id = ?");
        $update_stmt->bind_param("ssi", $new_status, $remarks, $application_id);

        if ($update_stmt->execute()) {
            $_SESSION['application_success'] = "Application updated to {$new_status} with remarks.";
        } else {
            $_SESSION['application_error'] = "Error updating application status. Please try again.";
        }
    } else {
        $_SESSION['application_error'] = "Application not found or you don't have permission to update it.";
    }

    header("Location: applicants.php");
    exit();
}

// Get all applications for this company
$stmt = $conn->prepare("SELECT a.*, j.job_id, j.job_title, j.job_position, g.first_name, g.last_name, g.phone_number, g.course, g.year_graduated, g.skills, g.resume, g.linkedin_profile
          FROM applications a 
          JOIN job_listings j ON a.job_id = j.job_id 
          JOIN graduate_information g ON a.user_id = g.user_id 
          WHERE j.company_id = ?
          ORDER BY a.application_date DESC");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$applications = $stmt->get_result();

// Get job listings for filter dropdown
$jobs_stmt = $conn->prepare("SELECT job_id, job_title FROM job_listings WHERE company_id = ? ORDER BY job_title");
$jobs_stmt->bind_param("i", $company_id);
$jobs_stmt->execute();
$jobs = $jobs_stmt->get_result();

// Store applications data for JavaScript
$applications_data = [];
while($app = $applications->fetch_assoc()) {
    $applications_data[] = $app;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications - MSTIP Seek Employee</title>
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
        .applications-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }


        .remarks-section {
            margin-bottom: 15px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #e5e7eb;
        }

        .remarks-section h4 {
            margin: 0 0 8px 0;
            color: #374151;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remarks-content {
            color: #4b5563;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .no-remarks {
            color: #9ca3af;
            font-style: italic;
        }

        .filters {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .filter-form {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 15px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: 600;
            color: #374151;
        }

        .form-control {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .application-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-left: 4px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .application-card.pending { border-left-color: #f59e0b; }
        .application-card.reviewed { border-left-color: #3b82f6; }
        .application-card.accepted { border-left-color: #10b981; }
        .application-card.rejected { border-left-color: #ef4444; }

        .application-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .applicant-info h3 {
            margin: 0 0 5px 0;
            color: #1f2937;
            font-size: 1.25rem;
        }

        .applicant-info p {
            margin: 0;
            color: #6b7280;
        }

        .application-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .meta-item i {
            color: #9ca3af;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-reviewed { background: #dbeafe; color: #1e40af; }
        .status-accepted { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }

        .application-details {
            margin-bottom: 20px;
        }

        .skills-section, .education-section {
            margin-bottom: 15px;
        }

        .skills-section h4, .education-section h4 {
            margin: 0 0 8px 0;
            color: #374151;
            font-size: 1rem;
        }

        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-tag {
            background: #f3f4f6;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: #4b5563;
        }

        .application-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .status-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
            display: none;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #d1d5db;
        }

        .empty-state h3 {
            margin: 0 0 10px 0;
            color: #374151;
        }

        .empty-state p {
            margin: 0 0 20px 0;
        }

        .application-count {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            color: #374151;
        }

        .application-count span {
            color: var(--primary-color);
        }

        @media (max-width: 1024px) {
            .filter-form {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .filter-form {
                grid-template-columns: 1fr;
            }
            
            .application-header {
                flex-direction: column;
                gap: 10px;
            }
            
            .application-actions {
                flex-direction: column;
            }
            
            .status-form {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <?php include_once('../../includes/log-employer-header.php') ?>

    <main>
        <section class="advice-hero">
            <div class="container">
                <h1 class="section-titles">View Applications</h1>
                <p class="section-subtitle">Manage and review all job applications from candidates.</p>
            </div>
            <svg class="advice-pattern" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 400" preserveAspectRatio="none">
                <path fill="rgba(255,123,0,0.05)" d="M0,160 C480,280 960,40 1440,160 L1440,400 L0,400 Z"></path>
            </svg>
        </section>

        <div class="applications-container">
            <!-- Filters -->
            <div class="filters">
                <div class="filter-form">
                    <div class="form-group">
                        <label for="search_applicant">Search Applicant:</label>
                        <input type="text" id="search_applicant" class="form-control" placeholder="Type applicant name...">
                    </div>
                    <div class="form-group">
                        <label for="job_filter">Filter by Job:</label>
                        <select id="job_filter" class="form-control">
                            <option value="">All Jobs</option>
                            <?php 
                            $jobs->data_seek(0);
                            while($job = $jobs->fetch_assoc()): ?>
                                <option value="<?php echo $job['job_id']; ?>">
                                    <?php echo htmlspecialchars($job['job_title']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status_filter">Filter by Status:</label>
                        <select id="status_filter" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="Pending">Pending</option>
                            <option value="Reviewed">Reviewed</option>
                            <option value="Accepted">Accepted</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" id="clear_filters" class="btn btn-outline" style="height: 40px; align-self: end;">
                            <i class="fas fa-times"></i> Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Application Count -->
            <div class="application-count">
                Showing <span id="visible-count">0</span> out of <span id="total-count"><?php echo count($applications_data); ?></span> applications
            </div>

            <!-- Applications List -->
            <div class="applications-list" id="applications-list"></div>

            <!-- Empty State -->
            <div class="empty-state" id="empty-state">
                <i class="fas fa-file-alt"></i>
                <h3>No Applications Found</h3>
                <p>There are no job applications matching your criteria.</p>
                <a href="manage-jobs.php" class="btn btn-primary">View Your Jobs</a>
            </div>
        </div>
    </main>

    <?php include_once('../../includes/employer-footer.php') ?>
    <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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

        // Applications data from PHP
        let applicationsData = <?php echo json_encode($applications_data); ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search_applicant');
            const jobFilter = document.getElementById('job_filter');
            const statusFilter = document.getElementById('status_filter');
            const clearFiltersBtn = document.getElementById('clear_filters');
            const applicationsList = document.getElementById('applications-list');
            const emptyState = document.getElementById('empty-state');
            const visibleCount = document.getElementById('visible-count');
            const totalCount = document.getElementById('total-count');

            displayApplications(applicationsData);
            updateApplicationCount();

            searchInput.addEventListener('input', filterApplications);
            jobFilter.addEventListener('change', filterApplications);
            statusFilter.addEventListener('change', filterApplications);
            clearFiltersBtn.addEventListener('click', clearFilters);

            function filterApplications() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedJob = jobFilter.value;
                const selectedStatus = statusFilter.value;
                
                const filteredApplications = applicationsData.filter(application => {
                    const applicantName = (application.first_name + ' ' + application.last_name).toLowerCase();
                    const jobMatch = selectedJob === '' || application.job_id == selectedJob;
                    const statusMatch = selectedStatus === '' || application.status === selectedStatus;
                    const searchMatch = searchTerm === '' || applicantName.includes(searchTerm);
                    return jobMatch && statusMatch && searchMatch;
                });
                
                displayApplications(filteredApplications);
                updateApplicationCount(filteredApplications.length);
            }

            function displayApplications(applications) {
                if (applications.length === 0) {
                    applicationsList.style.display = 'none';
                    emptyState.style.display = 'block';
                } else {
                    applicationsList.style.display = 'block';
                    emptyState.style.display = 'none';
                    
                    applicationsList.innerHTML = applications.map(application => `
                        <div class="application-card ${application.status.toLowerCase()}">
                            <div class="application-header">
                                <div class="applicant-info">
                                    <h3>${escapeHtml(application.first_name)} ${escapeHtml(application.last_name)}</h3>
                                    <p>Applied for: <strong>${escapeHtml(application.job_title)}</strong> (${escapeHtml(application.job_position)})</p>
                                </div>
                                <span class="status-badge status-${application.status.toLowerCase()}">
                                    ${application.status}
                                </span>
                            </div>

                            <div class="application-meta">
                                <div class="meta-item"><i class="fas fa-phone"></i><span>${escapeHtml(application.phone_number)}</span></div>
                                <div class="meta-item"><i class="fas fa-graduation-cap"></i><span>${escapeHtml(application.course)} (${application.year_graduated})</span></div>
                                <div class="meta-item"><i class="fas fa-calendar"></i><span>Applied: ${formatDate(application.application_date)}</span></div>
                            </div>

                            <div class="application-details">
                                ${application.skills ? `
                                <div class="skills-section">
                                    <h4>Skills:</h4>
                                    <div class="skills-list">
                                        ${application.skills.split(',').map(skill => 
                                            skill.trim() ? `<span class="skill-tag">${escapeHtml(skill.trim())}</span>` : ''
                                        ).join('')}
                                    </div>
                                </div>` : ''}
                                
                                <div class="education-section">
                                    <h4>Education:</h4>
                                    <p>${escapeHtml(application.course)} - Graduated ${application.year_graduated}</p>
                                </div>

                                <div class="remarks-section">
                                    <h4><i class="fas fa-comment"></i> Remarks</h4>
                                    <div class="remarks-content">
                                        ${application.remarks ? escapeHtml(application.remarks) : '<span class="no-remarks">No remarks added yet</span>'}
                                    </div>
                                </div>
                            </div>

                            <div class="application-actions">
                                ${application.resume ? `<a href="../../${escapeHtml(application.resume)}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> View Resume</a>` : ''}
                                ${application.linkedin_profile ? `<a href="${escapeHtml(application.linkedin_profile)}" target="_blank" class="btn btn-secondary btn-sm"><i class="fab fa-linkedin"></i> LinkedIn</a>` : ''}
                                <button type="button" class="btn btn-warning btn-sm update-status-btn" 
                                        data-application-id="${application.application_id}" 
                                        data-current-status="${application.status}"
                                        data-current-remarks="${application.remarks || ''}">
                                    <i class="fas fa-sync-alt"></i> Update Status
                                </button>
                            </div>
                        </div>
                    `).join('');
                }
            }

            function clearFilters() {
                searchInput.value = '';
                jobFilter.value = '';
                statusFilter.value = '';
                filterApplications();
            }

            function updateApplicationCount(visibleCountValue = null) {
                if (visibleCountValue !== null) {
                    visibleCount.textContent = visibleCountValue;
                } else {
                    const visibleCards = applicationsList.querySelectorAll('.application-card');
                    visibleCount.textContent = visibleCards.length;
                }
                totalCount.textContent = applicationsData.length;
            }

            function escapeHtml(unsafe) {
                if (unsafe === null || unsafe === undefined) return '';
                return unsafe.toString().replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'});
            }

            // Handle status update with remarks
            applicationsList.addEventListener('click', function(e) {
    if (e.target.classList.contains('update-status-btn') || e.target.closest('.update-status-btn')) {
        const button = e.target.classList.contains('update-status-btn') ? e.target : e.target.closest('.update-status-btn');
        const applicationId = button.getAttribute('data-application-id');
        const currentStatus = button.getAttribute('data-current-status');
        const currentRemarks = button.getAttribute('data-current-remarks');
        
        Swal.fire({
            title: 'Update Application Status',
            html: `
                <div style="text-align: left;">
                    <label for="status-select" style="display: block; margin-bottom: 8px; font-weight: 600;">Status:</label>
                    <select id="status-select" class="swal2-input" style="width: 100%; margin-bottom: 15px;">
                        <option value="Pending" ${currentStatus === 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value="Reviewed" ${currentStatus === 'Reviewed' ? 'selected' : ''}>Reviewed</option>
                        <option value="Accepted" ${currentStatus === 'Accepted' ? 'selected' : ''}>Accepted</option>
                        <option value="Rejected" ${currentStatus === 'Rejected' ? 'selected' : ''}>Rejected</option>
                    </select>
                    <label for="remarks-input" style="display: block; font-weight: 600;">Remarks:</label>
                    <input type="text"  style="margin-left: -2px; width: 100%;" id="remarks-input" class="swal2-input" placeholder="Add remarks..." value="${currentRemarks}" style="width: 100%;">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update Status',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            preConfirm: () => {
                const status = document.getElementById('status-select').value;
                const remarks = document.getElementById('remarks-input').value;
                
                if (!status) {
                    Swal.showValidationMessage('Please select a status');
                    return false;
                }
                
                return { status, remarks };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { status, remarks } = result.value;
                
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const applicationIdInput = document.createElement('input');
                applicationIdInput.type = 'hidden';
                applicationIdInput.name = 'application_id';
                applicationIdInput.value = applicationId;
                
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = status;
                
                const remarksInput = document.createElement('input');
                remarksInput.type = 'hidden';
                remarksInput.name = 'remarks';
                remarksInput.value = remarks;
                
                form.appendChild(applicationIdInput);
                form.appendChild(statusInput);
                form.appendChild(remarksInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});
        });
    </script>
</body>
</html>