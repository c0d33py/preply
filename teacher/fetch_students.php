<?php
include 'Connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student names from the database
$sql = "SELECT CONCAT(s.first_name, ' ', s.last_name) AS username FROM students"; // Assuming 'students' is the table with student info
$result = $conn->query($sql);

$students = [];
if ($result->num_rows > 0) {
    // Fetch all student usernames
    while ($row = $result->fetch_assoc()) {
        $students[] = $row['username'];
    }
} else {
    $students = ['No students found'];
}

// Return student list as JSON
echo json_encode($students);
?>
