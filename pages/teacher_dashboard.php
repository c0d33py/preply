<?php
// Ensure error reporting is enabled
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../header.php';
include '../connections.php';

$user_id = $_SESSION['user_id'];

// Fetch lessons
$sql = "SELECT 
    l.id,
    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
    CONCAT(t.first_name, ' ', t.last_name) AS teacher_name,
    l.lesson_date,
    l.start_time,
    l.end_time,
    l.lesson_status,
    l.created_at,
    l.price,
    l.duration
FROM 
    lessons l
JOIN 
    users s ON l.student_id = s.id
JOIN 
    users t ON l.teacher_id = t.id
WHERE 
     l.teacher_id = ?
ORDER BY 
    l.lesson_date DESC;";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
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
                    <h5 class="mb-4">Lessons</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
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
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['student_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['teacher_name']); ?>
                                        </td>
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
$conn->close();
include '../footer.php';
?>