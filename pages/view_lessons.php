<?php
session_start();
include '../connections.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");  // Redirect to login if not logged in or not a teacher
    exit();
}

$teacher_id = $_SESSION['user_id']; // Get teacher ID from session

// Fetch lessons from the database
$sql = "SELECT * FROM lessons WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!-- Display Lessons -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h3>Your Lessons</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Lesson Title</th>
                        <th>Lesson Description</th>
                        <th>Video URL</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($lesson = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $lesson['lesson_title'] ?></td>
                            <td><?= $lesson['lesson_description'] ?></td>
                            <td><a href="<?= $lesson['video_url'] ?>" target="_blank">Watch</a></td>
                            <td>
                                <a href="edit_lesson.php?id=<?= $lesson['lesson_id'] ?>" class="btn btn-warning">Edit</a>
                                <a href="delete_lesson.php?id=<?= $lesson['lesson_id'] ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>