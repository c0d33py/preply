<?php
// Start session
session_start();

include '../header.php';
include '../db_config.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Handle form submission to add a new lesson
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lesson_title = $_POST['lesson_title'];
    $lesson_description = $_POST['lesson_description'];
    $video_url = $_POST['video_url'];
    $teacher_id = $_SESSION['user_id'];

    // Prepare and execute the SQL query
    $sql = "INSERT INTO lessons (lesson_title, lesson_description, video_url, teacher_id) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $lesson_title, $lesson_description, $video_url, $teacher_id);

    if ($stmt->execute()) {
        //     echo "<script>alert('Lesson added successfully'); window.location.href='dashboard.php';</script>";
        // } else {
        echo "<script>alert('Error adding lesson');</script>";
    }
    echo $teacher_id;

}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center bg-dark text-white">
                    <h3>Add New Lesson
                        <?php echo $teacher_id; ?>

                    </h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <input type="text" name="lesson_title" class="form-control" placeholder="Lesson Title"
                                required>
                        </div>
                        <div class="mb-3">
                            <textarea name="lesson_description" class="form-control" placeholder="Lesson Description"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="url" name="video_url" class="form-control" placeholder="YouTube Video URL"
                                required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Add Lesson</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>