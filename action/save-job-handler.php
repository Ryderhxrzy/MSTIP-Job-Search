<?php
include_once('../includes/db_connect.php');
session_start();

header('Content-Type: application/json');

// Check if user is logged in as Graduate
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_type'] !== 'Graduate') {
    echo json_encode(['success' => false, 'message' => 'Please login to save jobs.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $userCode = $_SESSION['user_code'];
    $job_id = intval($_POST['job_id']);
    
    // Check if job is already saved
    $check_stmt = $conn->prepare("SELECT saved_id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
    $check_stmt->bind_param("si", $userCode, $job_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Job already saved!']);
        exit();
    }
    
    // Save the job
    $save_stmt = $conn->prepare("INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)");
    $save_stmt->bind_param("si", $userCode, $job_id);
    
    if ($save_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Job saved successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving job. Please try again.']);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>