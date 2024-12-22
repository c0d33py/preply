<?php

include '../connections.php';
session_start();
// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user ID

// Fetch the teacher ID from the teachers table
// $teacher_query = $conn->query("SELECT teacher_id FROM teachers WHERE user_id = $user_id");
// $teacher = $teacher_query->fetch_assoc();

// if (!$teacher) {
//     $error = "Only teachers can delete lessons.";
//     header("Location: student_dashboard.php"); // Redirect to a safe page
//     exit();
// }

// $teacher_id = $teacher['teacher_id']; // Use the teacher ID

// Fetch the lesson ID from the URL
if (!isset($_GET['lesson_id']) || empty($_GET['lesson_id'])) {
    $error = "Lesson ID is missing.";
    header("Location: teacher_dashboard.php"); // Redirect to the teacher dashboard if no lesson ID is provided
    exit();
}

$lesson_id = $_GET['lesson_id'];

// Fetch the lesson data to ensure it exists and belongs to the logged-in teacher
$lesson_query = $conn->query("
    SELECT * FROM lessons WHERE id = $lesson_id AND teacher_id = $user_id
");

$lesson = $lesson_query->fetch_assoc();

if (!$lesson) {
    $error = "Lesson not found or you don't have permission to delete it.";
    header("Location: teacher_dashboard.php"); // Redirect to the teacher dashboard
    exit();
}

// Delete the lesson
$delete_query = $conn->prepare("DELETE FROM lessons WHERE id = ?");
$delete_query->bind_param("i", $lesson_id);

if ($delete_query->execute()) {
    $success = "Lesson deleted successfully!";
    header("Location: teacher_dashboard.php"); // Redirect to the teacher dashboard after successful deletion
    exit();
} else {
    $error = "Error deleting lesson: " . $delete_query->error;
}

?>