<?php
include_once('../includes/db_connect.php');
header('Content-Type: application/json');

// Simple helper function
function json_response($status, $message, $user_id = null) {
    $response = ['status' => $status, 'message' => $message];
    if ($user_id) {
        $response['user_id'] = $user_id;
    }
    echo json_encode($response);
    exit;
}

function json_error($msg) {
    json_response('error', $msg);
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Invalid request method.');
}

// Fetch and validate form data
$fullname = trim($_POST['fullname'] ?? '');
$company_name = trim($_POST['company_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password_raw = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

// Basic validation
if (empty($fullname) || empty($company_name) || empty($email) || empty($password_raw) || empty($confirmPassword)) {
    json_error('Please fill all required fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_error('Invalid email address.');
}

if ($password_raw !== $confirmPassword) {
    json_error('Passwords do not match.');
}

if (strlen($password_raw) < 6) {
    json_error('Password must be at least 6 characters long.');
}

// Check if email already exists
$checkEmailStmt = $conn->prepare("SELECT id FROM users WHERE email_address = ? LIMIT 1");
$checkEmailStmt->bind_param('s', $email);
$checkEmailStmt->execute();
$checkEmailStmt->store_result();

if ($checkEmailStmt->num_rows > 0) {
    $checkEmailStmt->close();
    json_error('Email is already registered.');
}
$checkEmailStmt->close();

// Hash password
$hashed_password = password_hash($password_raw, PASSWORD_BCRYPT);

// Generate unique employer ID with retry logic for race conditions
$maxAttempts = 5;
$attempt = 0;
$inserted = false;

while ($attempt < $maxAttempts && !$inserted) {
    $attempt++;

    // Get current maximum numeric part for 'E' user_ids
    $sql = "SELECT MAX(CAST(SUBSTRING(user_id, 2) AS UNSIGNED)) AS max_id 
            FROM users 
            WHERE user_id LIKE 'E%'";
    $res = $conn->query($sql);
    
    if (!$res) {
        json_error('Database error: ' . $conn->error);
    }
    
    $row = $res->fetch_assoc();
    $maxId = isset($row['max_id']) && $row['max_id'] !== null ? intval($row['max_id']) : 0;
    $nextNum = $maxId + 1;
    $newUserId = 'E' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);

    // Start transaction for atomic operations
    $conn->begin_transaction();

    try {
        // Insert into users table
        $userStmt = $conn->prepare("INSERT INTO users (user_id, email_address, password, user_type, status) VALUES (?, ?, ?, 'Employer', 'Active')");
        if (!$userStmt) {
            throw new Exception('Prepare failed for users: ' . $conn->error);
        }
        
        $userStmt->bind_param('sss', $newUserId, $email, $hashed_password);
        if (!$userStmt->execute()) {
            // If it's a duplicate key error, retry
            if ($conn->errno === 1062) {
                $userStmt->close();
                $conn->rollback();
                usleep(100000); // 100ms delay before retry
                continue;
            }
            throw new Exception('User insert failed: ' . $conn->error);
        }
        $userStmt->close();

        // Insert into companies table
        $companyStmt = $conn->prepare("INSERT INTO companies (user_id, company_name, contact_person) VALUES (?, ?, ?)");
        if (!$companyStmt) {
            throw new Exception('Prepare failed for companies: ' . $conn->error);
        }
        
        $companyStmt->bind_param('sss', $newUserId, $company_name, $fullname);
        if (!$companyStmt->execute()) {
            throw new Exception('Company insert failed: ' . $conn->error);
        }
        $companyStmt->close();

        // Commit transaction
        $conn->commit();
        $inserted = true;
        
        json_response('success', 'Registration successful! You can now login with your credentials.', $newUserId);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        if ($attempt === $maxAttempts) {
            json_error('Registration failed after multiple attempts. Please try again. Error: ' . $e->getMessage());
        }
        
        // If it's a duplicate error, retry
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            usleep(100000); // 100ms delay before retry
            continue;
        }
        
        json_error($e->getMessage());
    }
}

if (!$inserted) {
    json_error('Failed to complete registration. Please try again.');
}
?>