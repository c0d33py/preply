<?php
session_start();
include '../connections.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");  // Redirect to login if not logged in or not a teacher
    exit();
}

$teacher_id = $_SESSION['user_id']; // Get teacher ID from session

// Fetch the lesson to edit
if (isset($_GET['id'])) {
    $lesson_id = $_GET['id'];

    // Fetch the lesson details
    $sql = "SELECT * FROM lessons WHERE lesson_id = ? AND teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $lesson_id, $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $lesson = $result->fetch_assoc();
    } else {
        echo "<script>alert('Lesson not found'); window.location.href='view_lessons.php';</script>";
        exit();
    }
}

// Handle form submission to update the lesson
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lesson_title = $_POST['lesson_title'];
    $lesson_description = $_POST['lesson_description'];
    $video_url = $_POST['video_url'];

    // Prepare the SQL query to update the lesson
    $sql = "UPDATE lessons SET lesson_title = ?, lesson_description = ?, video_url = ? WHERE lesson_id = ? AND teacher_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssii", $lesson_title, $lesson_description, $video_url, $lesson_id, $teacher_id);

        if ($stmt->execute()) {
            echo "<script>alert('Lesson updated successfully'); window.location.href='view_lessons.php';</script>";
        } else {
            echo "<script>alert('Error updating lesson');</script>";
        }
    } else {
        echo "<script>alert('Error preparing the statement');</script>";
    }
}
?>

<!-- Edit Lesson Form -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center bg-dark text-white">
                    <h3>Edit Lesson</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <input type="text" name="lesson_title" class="form-control"
                                value="<?= $lesson['lesson_title'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="lesson_description" class="form-control"
                                required><?= $lesson['lesson_description'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="url" name="video_url" class="form-control" value="<?= $lesson['video_url'] ?>"
                                required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update Lesson</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>