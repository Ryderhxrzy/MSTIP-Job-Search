<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear remember me cookie
if (isset($_COOKIE['employer_remember'])) {
    setcookie('employer_remember', '', time() - 3600, '/');
}

// Redirect to login page
header('Location: employer-login.php');
exit();
?>