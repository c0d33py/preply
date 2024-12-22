<?php
// Database configuration
$host = '127.0.0.1';
$db_name = 'lms';
$username = 'root';
$password = '';

// Create a connection
$conn = mysqli_connect($host, $username, $password, $db_name);

// mysqli_connect

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>