<?php
// Start session to verify if the user is logged in
include '../header.php';
include '../connections.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Retrieve the teacher ID from the teachers table
$teacher_id = null;
$stmt = $conn->prepare("SELECT teacher_id FROM teachers WHERE user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($teacher_id);
    $stmt->fetch();
    $stmt->close();
}

if (!$teacher_id) {
    echo "<p>Error: Teacher ID not found for the logged-in user.</p>";
    exit();
}

// Fetch lessons created by this teacher
$sql = "SELECT * FROM lessons WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header text-center bg-dark text-white">
                    <h3>Teacher Dashboard</h3>
                </div>
                <div class="card-body">
                    <h5 class="mb-4">Lessons for Teacher ID: <?php echo htmlspecialchars($teacher_id); ?></h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lesson ID</th>
                                <th>Student ID</th>
                                <th>Teacher ID</th>
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
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['teacher_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['lesson_date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                                        <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                                        <td><?php echo htmlspecialchars($row['lesson_status']); ?></td>
                                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                        <td><?php echo number_format($row['price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($row['duration']); ?> minutes</td>
                                        <td>
                                            <a href="edit_lesson.php?lesson_id=<?php echo $row['id']; ?>"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <a href="delete_lesson.php?lesson_id=<?php echo $row['id']; ?>"
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
include '../footer.php';
?>