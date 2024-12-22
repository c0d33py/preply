<?php
// Start session to verify if the user is logged in
session_start();
include '../header.php';
include '../db_config.php';

// // Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Get the teacher ID from the session
$teacher_id = $_SESSION['user_id'];

// Fetch lessons created by this teacher from the database
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
                    <h3>Teacher Dashboard

                        <?php echo $teacher_id; ?>
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Display Lessons -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lesson Title</th>
                                <th>Description</th>
                                <th>Video URL</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                $counter = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $counter++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row['lesson_title']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['lesson_description']) . "</td>";
                                    echo "<td><a href='" . htmlspecialchars($row['video_url']) . "' target='_blank'>View Video</a></td>";
                                    echo "<td>" . $row['created_at'] . "</td>";
                                    echo "<td>
                                            <a href='edit_lesson.php?lesson_id=" . $row['lesson_id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='delete_lesson.php?lesson_id=" . $row['lesson_id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this lesson?\")'>Delete</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No lessons found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../footer.php';
?>