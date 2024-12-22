<?php
session_start(); // Start the session
var_dump($_POST);

// Check if the teacher_id is sent via POST
if (isset($_POST['teacher_id'])) {
    // Store the teacher ID in the session
    $_SESSION['selected_teacher_id'] = $_POST['teacher_id'];

    // Redirect to a relevant page (depending on the action)
    if (isset($_POST['book_trial'])) {
        header("Location: trial_lesson_page.php");
        exit();
    } elseif (isset($_POST['send_message'])) {
        header("Location: message_page.php");
        exit();
    }
} else {
    // If no teacher ID was sent, redirect to the home page or error page
    header("Location: find_tutors.php");
    exit();
}
?>
