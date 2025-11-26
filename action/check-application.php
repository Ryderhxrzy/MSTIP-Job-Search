<?php
include_once '../includes/db_connect.php';
session_start();

header('Content-Type: application/json');

// Debug logging
error_log("check-application.php called");
error_log("Session user_code: " . ($_SESSION['user_code'] ?? 'not set'));
error_log("GET job_id: " . ($_GET['job_id'] ?? 'not set'));

if (!isset($_SESSION['user_code'])) {
    error_log("User not logged in");
    echo json_encode(['already_applied' => false]);
    exit;
}

$user_id = $_SESSION['user_code'];
$job_id = $_GET['job_id'] ?? null;

if (!$job_id) {
    error_log("No job_id provided");
    echo json_encode(['already_applied' => false]);
    exit;
}

try {
    // Check if user has already applied for this job
    $check = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
    $check->bind_param("si", $user_id, $job_id);
    $check->execute();
    $res = $check->get_result();
    
    $already_applied = $res->num_rows > 0;
    error_log("Application check result: " . ($already_applied ? 'already applied' : 'not applied'));
    
    echo json_encode(['already_applied' => $already_applied]);
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['already_applied' => false]);
} finally {
    if (isset($check)) $check->close();
    $conn->close();
}
?>
