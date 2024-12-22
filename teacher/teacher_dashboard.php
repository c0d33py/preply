<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    die('User not logged in or session expired');
}

// Get teacher's user_id from session
$user_id = $_SESSION['user_id'];

include 'Connection.php'; // Ensure your database connection is included
include 'contact_us.php';
// Sanitize the user input
$user_id = mysqli_real_escape_string($conn, $user_id);

// Get current date and time for comparison
$current_datetime = date('Y-m-d H:i:s');

// Query to fetch lessons that are scheduled for today or onward, considering the current time
$selectquery_today = "
    SELECT schedule.*, CONCAT(s.first_name, ' ', s.last_name) AS student_name
    FROM lesson_schedule schedule
    LEFT JOIN students s ON schedule.student_id = s.id
    WHERE schedule.teacher_id = $user_id
    AND (schedule.lesson_date = CURDATE() AND schedule.start_time >= CURTIME())
    AND schedule.lesson_status = 'scheduled'
    ORDER BY schedule.lesson_date, schedule.start_time";

$query_today = mysqli_query($conn, $selectquery_today);

// Query to fetch upcoming lessons (scheduled for the future)
$selectquery_upcoming = "
    SELECT schedule.*, CONCAT(s.first_name, ' ', s.last_name) AS student_name
    FROM lesson_schedule schedule
    LEFT JOIN students s ON schedule.student_id = s.id
    WHERE schedule.teacher_id = $user_id
    AND schedule.lesson_date > CURDATE()
    AND schedule.lesson_status = 'scheduled'
    ORDER BY schedule.lesson_date, schedule.start_time
    LIMIT 2"; // Limit to 2 upcoming lessons

$query_upcoming = mysqli_query($conn, $selectquery_upcoming);

// Check for query execution errors
if (!$query_today || !$query_upcoming) {
    die("Query failed: " . mysqli_error($conn));
}

// Check if the queries return any rows
$num_today = mysqli_num_rows($query_today);
$num_upcoming = mysqli_num_rows($query_upcoming);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preply</title>

    <!-- Link to Google Fonts for custom font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Roboto', sans-serif;
        }



        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f3f3f3;
            /* Optional: Set a light background for better contrast */
        }

        /* Card background color set to light grey */
        .card {
            background-color: #E9B9D6 !important;
        }

        /* Remove background color from the card header */
        .card-header {
            font-weight: bold;
            font-size: 1.25rem;
            background-color: #E9B9D6 !important;
        }

        .card-header.bg-secondary {
            background-color: #E9B9D6 !important;
        }

        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }

        .container.mt-4 {
            margin-top: 40px;
        }

        .container {
            padding-left: 15px;
            padding-right: 15px;
            margin-top: 100px;
        }

        .col-9 {
            margin: 0 auto;
        }

        .lesson-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #C084B1;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .lesson-info {
            flex: 1;
            font-size: 0.9rem;
            /* Smaller text size */
        }

        .lesson-info strong {
            font-size: 1.1rem;
            /* Slightly larger for the student name */
        }

        .dropdown-container {
            margin-left: 10px;
        }

        .dropdown-menu li a {
            color: #6c3d66;
            /* Matching color for options */
        }

        .dropdown-toggle {
            background: transparent;
            border: none;
            color: #6c3d66;
            /* Matching color for the icon */
        }

        .dropdown-toggle:focus {
            box-shadow: none;
        }

        .dropdown-menu {
            border-radius: 8px;
        }

        /* Upcoming lesson card styles */
        .upcoming-lessons-card {
            background-color: #FFFFFF !important;
            /* Same as body background */
            border: none !important;
            box-shadow: non !important;
            /* Border with matching color */
            border-radius: 8px;
            padding: 10px;
        }

        .upcoming-lessons-card .card-header {
            background-color: #FFFFFF !important;
            /* Make header white */
            color: black;
            /* Ensure text is visible */
        }

        .see-all-link {
            color: black;
            /* Matching color for the link */
            font-size: 1rem;
            text-decoration: none;
        }

        .see-all-link:hover {
            text-decoration: underline;
            /* Underline on hover */
        }

        .upcoming-lesson-item {
            padding: 8px;
            background-color: #FFFFFF;
            /* Light Lilac */
            border: solid black 1px;
            margin-top: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 8px;
        }

        .lesson-info {
            font-size: 0.9rem;
            /* Smaller text size for lesson details */
        }

        .lesson-info strong {
            font-size: 1.1rem;
            /* Slightly larger for student name */
        }
    </style>
</head>

<body>

    <!-- Include the header and other page content -->
    <?php include 'Header.php'; ?>

    <div class="container-fluid d-flex justify-content-center">
        <div class="col-9">
            <!-- Greeting message -->
            <div class="container mt-4">
                <h2>Good morning, <?php echo $_SESSION['username']; ?>!</h2>
                <p>Thanks for all you do for your learners 🙌</p>
            </div>

            <!-- Card to show lessons for today -->
            <div class="container-fluid mt-4">
                <div class="card">
                    <div class="card-header bg-secondary text-black">
                        Lessons for Today
                    </div>
                    <div class="card-body">

                        <?php if ($num_today > 0): ?>
                            <?php while ($row = mysqli_fetch_array($query_today)): ?>
                                <div class="lesson-item">
                                    <div class="lesson-info">
                                        <!-- Display Student Name -->
                                        <strong><?php echo $row['student_name']; ?> </strong>
                                        <br> <?php echo $row['start_time']; ?> - <?php echo $row['end_time']; ?>
                                        <br>

                                        <!-- Time left calculation -->
                                        <?php
                                        // Calculate how much time is left until the lesson starts
                                        $current_time = new DateTime();
                                        $lesson_time = new DateTime($row['lesson_date'] . ' ' . $row['start_time']);
                                        $interval = $current_time->diff($lesson_time);
                                        $time_left = $interval->format('%h hours %i minutes');
                                        ?>
                                        <?php echo $time_left; ?><span> left</span>
                                    </div>

                                    <!-- Dropdown for options with FontAwesome icon -->
                                    <div class="dropdown-container">
                                        <button class="btn btn-secondary " type="button" id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i> <!-- Three dots icon -->
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item" href="#">Cancel</a></li>
                                            <li><a class="dropdown-item" href="#">Reschedule</a></li>
                                        </ul>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>You have no lessons scheduled for today.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Card to show upcoming lessons -->

            <!-- Upcoming Lessons Card -->
            <div class="container-fluid mt-4">
                <div class=" upcoming-lessons-card text-black">
                    <div class="card-header text-black d-flex justify-content-between align-items-center">
                        <span>Upcoming Lessons</span>
                        <a href="all_upcoming_lessons.php" class="see-all-link">See All</a>
                    </div>
                    <div class="card-body">
                        <?php if ($num_upcoming > 0): ?>
                            <?php while ($row = mysqli_fetch_array($query_upcoming)): ?>
                                <div class="upcoming-lesson-item">
                                    <div class="lesson-info">
                                        <strong><?php echo $row['student_name']; ?></strong>
                                        <br> <strong><?php echo $row['lesson_date']; ?></strong> -
                                        <?php echo $row['start_time']; ?> - <?php echo $row['end_time']; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No upcoming lessons scheduled.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>





    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>