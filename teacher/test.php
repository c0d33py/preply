<?php
session_start();
include 'Connection.php'; // Ensure your database connection is included
include 'contact_us.php';

// Start session to access logged-in teacher ID
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Logged-in teacher's ID from the session
$teacher_id = $_SESSION['user_id'];

// Query to fetch lessons row by row for the logged-in teacher
$query = "
    SELECT 
        s.id AS student_id, 
        CONCAT(s.first_name, ' ', s.last_name) AS student_name, 
        COUNT(CASE WHEN l.lesson_status = 'scheduled' THEN l.id END) AS total_lessons,
        IFNULL(SUM(l.price), 0) AS price
    FROM students s
    LEFT JOIN lesson_schedule l ON s.id = l.student_id AND l.teacher_id = $teacher_id
    WHERE s.id IN (SELECT student_id FROM lesson_schedule WHERE teacher_id = $teacher_id)
    GROUP BY s.id, s.first_name, s.last_name
    ORDER BY s.first_name
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Lessons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'Header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Students</h2>
    
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Unique collapse ID for each student
            $collapseId = 'collapse_' . $row['student_id'];

            echo '<div class="mb-3">';
            echo '    <div class="card p-3">';
            echo '        <div class="d-flex justify-content-between align-items-center">';
            echo '            <p class="mb-0"><strong>' . htmlspecialchars($row['student_name']) . '</strong></p>';
            echo '            <p class="mb-0">$' . htmlspecialchars($row['price']) . '</p>';
            echo '            <p class="mb-0">' . htmlspecialchars($row['total_lessons']) . ' lessons</p>';
            echo '            <button class="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="false" aria-controls="' . $collapseId . '">View Lessons</button>';
            echo '        </div>';
            echo '    </div>';

            // Collapsible section
            echo '    <div class="collapse" id="' . $collapseId . '">';
            
            // Query to fetch lessons for the current student
            $lessonQuery = "
                SELECT 
                    l.lesson_date,
                    l.start_time,
                    l.end_time,
                    l.lesson_status,
                    l.price
                FROM lesson_schedule l
                WHERE l.student_id = " . $row['student_id'] . " AND l.teacher_id = $teacher_id AND l.lesson_status = 'scheduled'
                ORDER BY l.lesson_date, l.start_time
            ";

            $lessonResult = mysqli_query($conn, $lessonQuery);

            if ($lessonResult && mysqli_num_rows($lessonResult) > 0) {
                while ($lesson = mysqli_fetch_assoc($lessonResult)) {
                    echo '<div class="card mt-2">';
                    echo '    <div class="card-body">';
                    echo '        <p><strong>Lesson Date:</strong> ' . htmlspecialchars($lesson['lesson_date']) . '</p>';
                    echo '        <p><strong>Start Time:</strong> ' . htmlspecialchars($lesson['start_time']) . '</p>';
                    echo '        <p><strong>End Time:</strong> ' . htmlspecialchars($lesson['end_time']) . '</p>';
                    echo '        <p><strong>Status:</strong> ' . htmlspecialchars($lesson['lesson_status']) . '</p>';
                    echo '        <p><strong>Price:</strong> $' . htmlspecialchars($lesson['price']) . '</p>';
                    echo '    </div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-muted p-3">No lessons scheduled for this student.</p>';
            }

            mysqli_free_result($lessonResult);

            echo '    </div>'; // Close collapsible section
            echo '</div>';
        }
    } else {
        echo '<p class="text-center">No students found.</p>';
    }

    mysqli_free_result($result);
    mysqli_close($conn);
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
