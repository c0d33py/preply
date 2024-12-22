<?php
session_start();
include '../connections.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: login.php");  // Redirect to login if not logged in or not a teacher
    exit();
}

$teacher_id = $_SESSION['user_id']; // Get teacher ID from session

// Handle lesson deletion
if (isset($_GET['id'])) {
    $lesson_id = $_GET['id'];

    // Prepare the SQL query to delete the lesson
    $sql = "DELETE FROM lessons WHERE lesson_id = ? AND teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $lesson_id, $teacher_id);

    if ($stmt->execute()) {
        echo "<script>alert('Lesson deleted successfully'); window.location.href='view_lessons.php';</script>";
    } else {
        echo "<script>alert('Error deleting lesson');</script>";
    }
}
?>