<?php
include "../header.php";
include '../connections.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user ID

$user_name = $_SESSION[''];
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_lesson'])) {
    $student_id = $_POST['student_id'];
    $lesson_date = $_POST['lesson_date'];
    $start_time = $_POST['start_time'];
    // $end_time = $_POST['end_time'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $lesson_status = $_POST['lesson_status'];

    // Validate input fields
    if (empty($student_id) || empty($lesson_date) || empty($start_time) || empty($price) || empty($duration)) {
        $error = "All fields are required!";
    } else {
        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO lessons (student_id, teacher_id, lesson_date, start_time, end_time, price, duration, lesson_status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        if ($stmt) {
            $stmt->bind_param("iisssdis", $student_id, $user_id, $lesson_date, $start_time, $start_time, $price, $duration, $lesson_status);

            if ($stmt->execute()) {
                $success = "Lesson added successfully!";
            } else {
                $error = "Error adding lesson: " . $stmt->error;
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
                    <h3>Add Lesson</h3>
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
                                    <option value="<?= $student['id']; ?>">
                                        <?= htmlspecialchars($student['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="lesson_date" id="lesson_date" class="form-control" required
                                placeholder="Lesson Date">
                        </div>
                        <div class="col-md-6">
                            <input type="time" name="start_time" id="start_time" class="form-control" required
                                placeholder="Start Time">
                        </div>
                        <!-- <div class="col-md-6">
                            <input type="time" name="end_time" id="end_time" class="form-control" required
                                placeholder="End Time">
                        </div> -->
                        <div class="col-md-6">
                            <input type="number" name="price" id="price" class="form-control" step="0.01" required
                                placeholder="Price">
                        </div>
                        <div class="col-md-6">
                            <input type="number" name="duration" id="duration" class="form-control" required
                                placeholder="Duration (in minutes)">
                        </div>
                        <div class="col-md-6">
                            <select name="lesson_status" id="lesson_status" class="form-select" required>
                                <option value="scheduled">Scheduled</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="add_lesson" class="btn btn-primary">Add Lesson</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../footer.php"); ?>