<?php
// db_connect.php
$host = "localhost";     // Database host
$user = "root";          // Database username
$pass = "";              // Database password
$db   = "mstip_db";         // Database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>