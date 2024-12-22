<?php
include '../connections.php';
// Include the database configuration file
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$upload_dir = '../profiles/';
$photo_path = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the photo is being uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['photo']['tmp_name'];
        $file_name = basename($_FILES['photo']['name']);
        $target_path = $upload_dir . $file_name;

        // Ensure the uploads directory exists, create it if not
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create the directory with proper permissions
        }

        // Ensure the directory is writable
        if (!is_writable($upload_dir)) {
            die("The upload directory is not writable. Please check the permissions.");
        }

        // Move the uploaded file to the target path
        if (move_uploaded_file($file_tmp_path, $target_path)) {
            $photo_path = $target_path;
        } else {
            die("Failed to move uploaded file.");
        }
    }

    // Prepare the SQL query to update the teacher's profile
    $query = $conn->prepare("UPDATE teachers SET 
        subject = ?, 
        language = ?, 
        youtube_video_url = ?, 
        level = ?, 
        phone = ?, 
        photo = IFNULL(?, photo), 
        charge_per_hour = ?, 
        country_id = ?, 
        available_in_week = ? 
        WHERE user_id = ?");

    if (!$query) {
        die("Failed to prepare query: " . $conn->error);
    }

    // Get the form data
    $subject = $_POST['subject'];
    $language = $_POST['language'];
    $youtube_video_url = $_POST['youtube_video_url'];
    $level = $_POST['level'];
    $phone = $_POST['phone'];
    $charge_per_hour = $_POST['charge_per_hour'];
    $country_id = $_POST['country_id'] ?: null;
    $available_in_week = $_POST['available_in_week'];

    // Bind parameters and execute the query
    $query->bind_param(
        "ssssssdssi",
        $subject,
        $language,
        $youtube_video_url,
        $level,
        $phone,
        $photo_path,
        $charge_per_hour,
        $country_id,
        $available_in_week,
        $user_id,
    );

    // Execute the query and handle the result
    if ($query->execute()) {
        header("Location: teacher_dashboard.php?success=1");
        exit();
    } else {
        die("Failed to update profile: " . $query->error);
    }
}
?>