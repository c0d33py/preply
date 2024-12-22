<?php
// Database connection
include 'Connection.php';  // Add the missing semicolon here

// Check connection
if ($conn->connect_error) {
    // Secure error handling to avoid exposing sensitive data
    error_log("Database connection failed: " . $conn->connect_error); // Log the error
    die("Connection failed, please try again later.");
}

// Start the session to get the sender's ID (Assuming the teacher's ID is stored in the session)
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Please log in first.";
    exit();
}

$sender_id = $_SESSION['user_id']; // Assuming the teacher's ID is stored in session

// Get data from POST request and sanitize inputs
$receiver_id = isset($_POST['receiver_id']) ? htmlspecialchars($_POST['receiver_id']) : '';
$message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

// Validate inputs
if (empty($receiver_id) || empty($message)) {
    echo "Receiver ID and message cannot be empty.";
    exit();
}

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $sender_id, $receiver_id, $message); // Bind variables to placeholders

// Execute the statement
if ($stmt->execute()) {
    echo "Message sent";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
