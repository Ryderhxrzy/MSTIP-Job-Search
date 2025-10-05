<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear remember me cookie
if (isset($_COOKIE['graduate_remember'])) {
    setcookie('graduate_remember', '', time() - 3600, '/');
}

// Redirect to login page
header('Location: login.php');
exit();
?>