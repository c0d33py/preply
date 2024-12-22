<?php
// Ensure error reporting is enabled
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../header.php';
include '../connections.php';

// Start session and verify user
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    die("Access denied. Please log in as a teacher.");
}

$user_id = $_SESSION['user_id'];

// Get teacher ID
$teacher_id = null;
$stmt = $conn->prepare("SELECT teacher_id FROM teachers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($teacher_id);
$stmt->fetch();
$stmt->close();

if (!$teacher_id) {
    die("Teacher ID not found.");
}

// Fetch lessons
$sql = "
    SELECT 
        l.id AS lesson_id, 
        t.teacher_id, 
        u1.first_name AS teacher_first_name, 
        u1.last_name AS teacher_last_name, 
        l.student_id AS student_user_id, 
        u2.first_name AS student_first_name, 
        u2.last_name AS student_last_name, 
        l.lesson_date,
        l.start_time, 
        l.end_time, 
        l.lesson_status, 
        l.created_at, 
        l.price, 
        l.duration
    FROM lessons l
    JOIN teachers t ON l.teacher_id = t.teacher_id  -- Get teacher_id from teachers table
    JOIN users u1 ON t.user_id = u1.id  -- Teacher's name from users table
    JOIN users u2 ON l.student_id = u2.id  -- Student's name from users table
    WHERE 
        t.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if results are available
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Lesson ID: " . $row['lesson_id'] . "<br>";
        echo "Teacher: " . $row['teacher_first_name'] . " " . $row['teacher_last_name'] . "<br>";
        echo "Student: " . $row['student_first_name'] . " " . $row['student_last_name'] . "<br>";
        echo "Lesson Date: " . $row['lesson_date'] . "<br>";
        echo "Start Time: " . $row['start_time'] . "<br>";
        echo "End Time: " . $row['end_time'] . "<br>";
        echo "Status: " . $row['lesson_status'] . "<br>";
        echo "Price: " . $row['price'] . "<br>";
        echo "Duration: " . $row['duration'] . " minutes<br>";
        echo "<hr>";
    }
} else {
    echo "No lessons found.";
}

// Close the statement
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header text-center bg-dark text-white">
                    <h3>Teacher Dashboard</h3>
                </div>
                <div class="card-body">
                    <h5 class="mb-4">Lessons></h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lesson ID</th>
                                <th>Student Name</th>
                                <th>Teacher Name</th>
                                <th>Lesson Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Lesson Status</th>
                                <th>Created At</th>
                                <th>Price</th>
                                <th>Duration (Minutes)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php $counter = 1; ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $counter++; ?></td>
                                        <td><?php echo htmlspecialchars($row['lesson_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['student_first_name'] . ' ' . $row['student_last_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['teacher_first_name'] . ' ' . $row['teacher_last_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['lesson_date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                                        <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                                        <td><?php echo htmlspecialchars($row['lesson_status']); ?></td>
                                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                        <td><?php echo number_format($row['price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($row['duration']); ?> minutes</td>
                                        <td>
                                            <a href="edit_lesson.php?lesson_id=<?php echo $row['lesson_id']; ?>"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <a href="delete_lesson.php?lesson_id=<?php echo $row['lesson_id']; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this lesson?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="12" class="text-center">No lessons found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
include '../footer.php';
?>