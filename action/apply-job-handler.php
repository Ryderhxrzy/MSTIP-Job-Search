<?php
include_once('../includes/db_connect.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$job_id = $_POST['job_id'] ?? null;

if (!$job_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid job ID']);
    exit;
}

// Check duplicate application
$check = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
$check->bind_param("si", $user_id, $job_id);
$check->execute();
$res = $check->get_result();
if ($res->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'You have already applied for this job.']);
    exit;
}

// Insert new application
$stmt = $conn->prepare("INSERT INTO applications (user_id, job_id) VALUES (?, ?)");
$stmt->bind_param("si", $user_id, $job_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit application.']);
}
