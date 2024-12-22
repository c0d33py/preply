<?php
include 'Connection.php';
session_start(); // Start session to access logged-in teacher ID

// Check if the user is logged in by verifying if 'user_id' is set
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Logged-in teacher's ID from the session
$teacher_id = $_SESSION['user_id'];

// Check if the form is submitted to update lesson
if (isset($_POST['submit_reschedule'])) {
    $lesson_id = $_POST['lesson_id'];
    $lesson_date = $_POST['lesson_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Update the lesson schedule in the database
    $update_query = "
        UPDATE lesson_schedule 
        SET lesson_date = '$lesson_date', start_time = '$start_time', end_time = '$end_time' 
        WHERE id = $lesson_id AND teacher_id = $teacher_id
    ";

    if (mysqli_query($conn, $update_query)) {
        echo "Lesson rescheduled successfully!";
    } else {
        echo "Error updating lesson: " . mysqli_error($conn);
    }
}

// Fetch the lessons of the logged-in teacher to allow rescheduling
$query = "
    SELECT 
        l.id AS lesson_id, 
        s.first_name, s.last_name, 
        l.lesson_date, l.start_time, l.end_time 
    FROM lesson_schedule l
    JOIN students s ON l.student_id = s.id
    WHERE l.teacher_id = $teacher_id
    ORDER BY l.lesson_date, l.start_time
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reschedule Lesson</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="section-heading">Reschedule Lesson</h2>

    <!-- Display the list of lessons -->
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        echo '<form action="reschedule.php" method="POST">';
        echo '<div class="list-group">';

        // Loop through lessons and display them
        while ($row = mysqli_fetch_assoc($result)) {
            $lesson_id = $row['lesson_id'];
            echo '<div class="list-group-item">';
            echo '    <div><strong>Student:</strong> ' . htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']) . '</div>';
            echo '    <div><strong>Lesson Date:</strong> ' . htmlspecialchars($row['lesson_date']) . '</div>';
            echo '    <div><strong>Start Time:</strong> ' . htmlspecialchars($row['start_time']) . '</div>';
            echo '    <div><strong>End Time:</strong> ' . htmlspecialchars($row['end_time']) . '</div>';
            echo '    <button type="button" class="btn btn-info" data-bs-toggle="collapse" data-bs-target="#lesson_' . $lesson_id . '" aria-expanded="false">Reschedule</button>';
            echo '    <div class="collapse" id="lesson_' . $lesson_id . '">';
            echo '        <div class="mt-3">';
            echo '            <input type="hidden" name="lesson_id" value="' . $lesson_id . '">';
            echo '            <div class="form-group">';
            echo '                <label for="lesson_date">New Lesson Date:</label>';
            echo '                <input type="date" name="lesson_date" class="form-control" required>';
            echo '            </div>';
            echo '            <div class="form-group">';
            echo '                <label for="start_time">New Start Time:</label>';
            echo '                <input type="time" name="start_time" class="form-control" required>';
            echo '            </div>';
            echo '            <div class="form-group">';
            echo '                <label for="end_time">New End Time:</label>';
            echo '                <input type="time" name="end_time" class="form-control" required>';
            echo '            </div>';
            echo '            <button type="submit" name="submit_reschedule" class="btn btn-primary mt-3">Save Changes</button>';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</form>';
    } else {
        echo '<p>No lessons found to reschedule.</p>';
    }

    mysqli_free_result($result);
    mysqli_close($conn);
    ?>

</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
