<?php
include "../header.php";
include '../connections.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user ID

// Get the lesson id from the URL
if (isset($_GET['lesson_id'])) {
    $lesson_id = $_GET['lesson_id'];

    // Fetch the lesson details to populate the form
    $lesson_query = $conn->prepare("SELECT * FROM lessons WHERE id = ? AND (teacher_id = ? OR student_id = ?)");
    $lesson_query->bind_param("iii", $lesson_id, $user_id, $user_id);
    $lesson_query->execute();
    $lesson_result = $lesson_query->get_result();

    if ($lesson_result->num_rows > 0) {
        $lesson = $lesson_result->fetch_assoc();
    } else {
        die("Lesson not found or you don't have permission to edit this lesson.");
    }
} else {
    die("Lesson ID is required.");
}

// Fetch students for the dropdown
$students_query = $conn->query("
    SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name
    FROM students s
    INNER JOIN users u ON s.user_id = u.id
    WHERE u.role = 'student';
");

if (!$students_query) {
    die("Error fetching students: " . $conn->error);
}

// Handle form submission to update the lesson
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_lesson'])) {
    $student_id = $_POST['student_id'];
    $lesson_date = $_POST['lesson_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $lesson_status = $_POST['lesson_status'];

    // Validate input fields
    if (empty($student_id) || empty($lesson_date) || empty($start_time) || empty($price) || empty($duration)) {
        $error = "All fields are required!";
    } else {
        // Prepare and bind the SQL statement for updating the lesson
        $stmt = $conn->prepare("UPDATE lessons SET student_id = ?, lesson_date = ?, start_time = ?, end_time = ?, price = ?, duration = ?, lesson_status = ? WHERE id = ? AND teacher_id = ?");

        if ($stmt) {
            $stmt->bind_param("isssdisii", $student_id, $lesson_date, $start_time, $start_time, $price, $duration, $lesson_status, $lesson_id, $user_id);

            if ($stmt->execute()) {
                $success = "Lesson updated successfully!";
            } else {
                $error = "Error updating lesson: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Error preparing query: " . $conn->error;
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h3>Edit Lesson</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?= $success; ?></div>
                    <?php endif; ?>
                    <form method="post" action="" class="row g-3">
                        <div class="col-md-6">
                            <select name="student_id" id="student_id" class="form-select" required>
                                <option value="">Select Student</option>
                                <?php while ($student = $students_query->fetch_assoc()): ?>
                                    <option value="<?= $student['id']; ?>" <?= ($student['id'] == $lesson['student_id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($student['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="lesson_date" id="lesson_date" class="form-control" required
                                value="<?= $lesson['lesson_date']; ?>" placeholder="Lesson Date">
                        </div>
                        <div class="col-md-6">
                            <input type="time" name="start_time" id="start_time" class="form-control" required
                                value="<?= $lesson['start_time']; ?>" placeholder="Start Time">
                        </div>
                        <div class="col-md-6">
                            <input type="number" name="price" id="price" class="form-control" step="0.01" required
                                value="<?= $lesson['price']; ?>" placeholder="Price">
                        </div>
                        <div class="col-md-6">
                            <input type="number" name="duration" id="duration" class="form-control" required
                                value="<?= $lesson['duration']; ?>" placeholder="Duration (in minutes)">
                        </div>
                        <div class="col-md-6">
                            <select name="lesson_status" id="lesson_status" class="form-select" required>
                                <option value="scheduled" <?= ($lesson['lesson_status'] == 'scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                                <option value="completed" <?= ($lesson['lesson_status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?= ($lesson['lesson_status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="edit_lesson" class="btn btn-primary">Update Lesson</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../footer.php"); ?>