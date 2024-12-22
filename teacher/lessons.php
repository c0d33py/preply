<?php
session_start();

// Start session to access logged-in teacher ID

// Check if the user is logged in by verifying if 'user_id' is set
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Logged-in teacher's ID from the session
$teacher_id = $_SESSION['user_id'];
include 'Connection.php'; // Ensure your database connection is included
include 'contact_us.php';

// Query to fetch lessons row by row for the logged-in teacher
$query = "
    SELECT 
        s.id AS student_id, 
        CONCAT(s.first_name, ' ', s.last_name) AS student_name, 
        COUNT(CASE WHEN l.lesson_status = 'scheduled' THEN l.id END) AS total_lessons,  -- Counting lessons with 'scheduled' status
        IFNULL(SUM(l.price), 0) AS price  -- Summing up the prices of scheduled lessons, defaulting to 0 if NULL
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
    <title>Preply</title>
    <link rel="stylesheet" href="styles.css">   
    <!-- Bootstrap CSS -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f3f3;
        }
        .section-heading {
            text-align: center;
            margin: 20px 0;
            color: #6C3D66;
        }
        .student-card {
            border: 1px solid #D8A7CA;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: #fff;
            display: flex;
            justify-content: space-between; /* Distribute content horizontally */
            align-items: center;
        }
        .student-card p {
            margin: 0;
            color: #4d3346;
        }
        .student-header {
            background-color: #D8A7CA;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .student-header h4 {
            color: #6C3D66;
            margin-bottom: 10px;
        }
        .card-container {
            display: flex;
            flex-direction: column; /* Stack the cards vertically */
            gap: 15px;
        }
        .card-info {
            font-weight: bold;
            color: #6C3D66;
        }
        .card-price {
            font-weight: bold;
            color: #9B59B6;
        }

        /* Styling for lesson cards */
        .lesson-card {
            border: 1px solid #D8A7CA;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: #fff;
            margin-bottom: 10px;
        }
        .lesson-card p {
            margin: 0;
            color: #4d3346;
        }
        .lesson-header {
            background-color: #D8A7CA;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .lesson-header h4 {
            color: #6C3D66;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?php include 'Header.php'; ?>
<div class="container mt-5">
    <h2 class="section-heading">Students</h2>
    
    <!-- Display student details (price per lesson and prepaid balance) -->
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Create a unique ID for the collapse section based on student_id
            $collapseId = 'collapse_' . $row['student_id'];

            // Display student info with a collapsible button for lessons
            echo '<div class="card-container">';
            echo '    <div class="student-card">';
            echo '        <p class="card-info">' . htmlspecialchars($row['student_name']) . '</p>';
            echo '        <p class="card-price">$' . htmlspecialchars($row['price']) . '</p>';
            echo '        <p class="card-info">' . htmlspecialchars($row['total_lessons']) . ' lessons</p>';
            echo '        <!-- Button to toggle collapse -->';
            echo '        <button class="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="false" aria-controls="' . $collapseId . '">View Lessons</button>';
            echo '    </div>';

            // Collapsible section for lesson details
            echo '    <div class="collapse" id="' . $collapseId . '">' ;

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

            // Check if lessons exist and display them
            if ($lessonResult && mysqli_num_rows($lessonResult) > 0) {
                while ($lesson = mysqli_fetch_assoc($lessonResult)) {
                    // Lesson card styled like student card
                    echo '<div class="lesson-card">';
                    echo '    <p><strong>Lesson Date:</strong> ' . htmlspecialchars($lesson['lesson_date']) . '</p>';
                    echo '    <p><strong>Start Time:</strong> ' . htmlspecialchars($lesson['start_time']) . '</p>';
                    echo '    <p><strong>End Time:</strong> ' . htmlspecialchars($lesson['end_time']) . '</p>';
                    echo '    <p><strong>Status:</strong> ' . htmlspecialchars($lesson['lesson_status']) . '</p>';
                    echo '    <p><strong>Price:</strong> $' . htmlspecialchars($lesson['price']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No lessons scheduled for this student.</p>';
            }

            mysqli_free_result($lessonResult);

            echo '    </div>';
            echo '</div>';
        }
    } else {
        echo '<p>No students found.</p>';
    }

    // Free result set
    mysqli_free_result($result);

    // Close the connection
    mysqli_close($conn);
    ?>
</div>

<!-- Bootstrap JS and dependencies (Popper and Bootstrap JS for collapse to work) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
