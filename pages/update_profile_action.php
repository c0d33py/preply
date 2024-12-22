<?php
include '../connections.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get form data
$subject = $_POST['subject'];
$language = $_POST['language'];
$youtube_video_url = $_POST['youtube_video_url'];
$level = $_POST['level'];
$phone = $_POST['phone'];
$charge_per_hour = $_POST['charge_per_hour'];
$country_id = $_POST['country_id'];
$available_in_week = $_POST['available_in_week'];

// Handle photo upload
$photo = null;
if (!empty($_FILES['photo']['name'])) {
    $photo = "uploads/" . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
}

// Check if teacher profile exists
$query = $conn->prepare("SELECT * FROM teachers WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    // Profile exists, update the existing record
    $query = $conn->prepare("UPDATE teachers SET subject = ?, language = ?, youtube_video_url = ?, level = ?, phone = ?, photo = COALESCE(?, photo), charge_per_hour = ?, country_id = ?, available_in_week = ? WHERE user_id = ?");
    $query->bind_param("ssssssdssi", $subject, $language, $youtube_video_url, $level, $phone, $photo, $charge_per_hour, $country_id, $available_in_week, $user_id);

    if ($query->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
    } else {
        $_SESSION['error_message'] = "Error updating profile!";
    }
} else {
    // Profile does not exist, insert a new record
    $query = $conn->prepare("INSERT INTO teachers (user_id, subject, language, youtube_video_url, level, phone, photo, charge_per_hour, country_id, available_in_week) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("issssssdsis", $user_id, $subject, $language, $youtube_video_url, $level, $phone, $photo, $charge_per_hour, $country_id, $available_in_week);

    if ($query->execute()) {
        $_SESSION['success_message'] = "Profile created successfully!";
    } else {
        $_SESSION['error_message'] = "Error creating profile!";
    }
}

header("Location: update_profile.php");
exit();
?>