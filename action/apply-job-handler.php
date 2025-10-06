<?php
include_once('../includes/db_connect.php');
session_start();

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
    return true;
}

loadEnv('../.env');

date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

if (!isset($_SESSION['user_code'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_code'];
$job_id = $_POST['job_id'] ?? null;

if (!$job_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid job ID']);
    exit;
}

try {
    // Check duplicate application
    $check = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
    $check->bind_param("si", $user_id, $job_id);
    $check->execute();
    $res = $check->get_result();
    
    if ($res->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You have already applied for this job.']);
        exit;
    }
    
    // Get user information
    $user_sql = "SELECT gi.first_name, gi.last_name, u.email_address 
                 FROM graduate_information gi
                 JOIN users u ON gi.user_id = u.user_id
                 WHERE gi.user_id = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("s", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    
    if (!$user_data) {
        echo json_encode(['success' => false, 'message' => 'User information not found']);
        exit;
    }
    
    // Get job information
    $job_sql = "SELECT jl.job_title, c.company_name 
                FROM job_listings jl
                JOIN companies c ON jl.company_id = c.company_id
                WHERE jl.job_id = ?";
    $job_stmt = $conn->prepare($job_sql);
    $job_stmt->bind_param("i", $job_id);
    $job_stmt->execute();
    $job_result = $job_stmt->get_result();
    $job_data = $job_result->fetch_assoc();
    
    if (!$job_data) {
        echo json_encode(['success' => false, 'message' => 'Job information not found']);
        exit;
    }
    
    // Insert new application
    $stmt = $conn->prepare("INSERT INTO applications (user_id, job_id, application_date, status) VALUES (?, ?, NOW(), 'Pending')");
    $stmt->bind_param("si", $user_id, $job_id);
    
    if ($stmt->execute()) {
        // Send email confirmation
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'];
            
            $mail->setFrom('noreply@mstipjobsearch.com', 'MSTIP Job Search');
            $mail->addAddress($user_data['email_address'], $user_data['first_name'] . ' ' . $user_data['last_name']);
            $mail->addReplyTo('hr@mstipjobsearch.com', 'HR Department');
            
            $mail->isHTML(true);
            $mail->Subject = "Application Confirmation - " . $job_data['job_title'];
            
            $message = "
            <html>
            <head>
                <title>Application Confirmation</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #08135c, #0077ff); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
                    .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px; }
                    .highlight { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
                    .footer { text-align: center; margin-top: 30px; padding: 20px; color: #666; font-size: 0.9em; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Application Submitted Successfully!</h1>
                    </div>
                    <div class='content'>
                        <h2>Dear " . htmlspecialchars($user_data['first_name']) . " " . htmlspecialchars($user_data['last_name']) . ",</h2>
                        
                        <p>Thank you for your interest in joining our team! We're excited to let you know that your application has been successfully submitted.</p>
                        
                        <div class='highlight'>
                            <h3>Application Details:</h3>
                            <p><strong>Position:</strong> " . htmlspecialchars($job_data['job_title']) . "</p>
                            <p><strong>Company:</strong> " . htmlspecialchars($job_data['company_name']) . "</p>
                            <p><strong>Application Date:</strong> " . date('F j, Y g:i A') . "</p>
                            <p><strong>Status:</strong> Pending Review</p>
                        </div>
                        
                        <h3>What Happens Next?</h3>
                        <ul>
                            <li><strong>Review Process:</strong> Our hiring team will carefully review your application and qualifications</li>
                            <li><strong>Initial Screening:</strong> If your profile matches our requirements, we'll contact you within 3-5 business days</li>
                            <li><strong>Interview Process:</strong> Qualified candidates will be invited for an interview</li>
                            <li><strong>Final Decision:</strong> We'll notify you of our decision regardless of the outcome</li>
                        </ul>
                        
                        <div class='highlight'>
                            <h3>Stay Connected:</h3>
                            <p>We'll keep you updated on your application status via this email address. Please ensure you check your inbox regularly, including your spam folder.</p>
                        </div>
                        
                        <p><strong>Questions?</strong> If you have any questions about your application or the position, feel free to reply to this email.</p>
                        
                        <p>We appreciate your interest in " . htmlspecialchars($job_data['company_name']) . " and look forward to potentially working with you!</p>
                        
                        <p>Best regards,<br>
                        <strong>The Hiring Team</strong><br>
                        " . htmlspecialchars($job_data['company_name']) . "</p>
                    </div>
                    
                    <div class='footer'>
                        <p>This is an automated message. Please do not reply directly to this email unless you have specific questions about your application.</p>
                        <p>&copy; " . date('Y') . " MSTIP Job Search. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail->Body = $message;
            
            if ($mail->send()) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Application submitted successfully! A confirmation email has been sent to your email address.'
                ]);
            } else {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Application submitted successfully! However, we could not send a confirmation email. Please check your application status in your profile.'
                ]);
            }
        } catch (Exception $e) {
            error_log("Email sending failed: " . $mail->ErrorInfo);
            echo json_encode([
                'success' => true, 
                'message' => 'Application submitted successfully! However, we could not send a confirmation email. Please check your application status in your profile.'
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit application.']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
} finally {
    if (isset($check)) $check->close();
    if (isset($user_stmt)) $user_stmt->close();
    if (isset($job_stmt)) $job_stmt->close();
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>