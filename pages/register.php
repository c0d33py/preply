<?php
include("../header.php");
include '../connections.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    // Get form inputs
    $role = $_POST['role'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Validate required fields
    if (empty($role) || empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        echo "<div class='alert alert-danger'>All fields are required!</div>";
    } else {
        // Begin transaction
        $conn->begin_transaction();
        try {
            // Insert user into the users table
            $sql = "INSERT INTO users (role, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param('sssss', $role, $first_name, $last_name, $email, $password);
            if (!$stmt->execute()) {
                throw new Exception("Error inserting user: " . $stmt->error);
            }

            $user_id = $stmt->insert_id; // Get the inserted user ID
            $stmt->close();

            // Insert into the appropriate table based on the role
            if ($role === 'teacher') {
                $teacher_sql = "INSERT INTO teachers (user_id, subject, language, charge_per_hour, is_active) VALUES (?, 'Subject Placeholder', 'Language Placeholder', 0.00, 1)";
                $teacher_stmt = $conn->prepare($teacher_sql);
                if (!$teacher_stmt) {
                    throw new Exception("Error preparing teacher statement: " . $conn->error);
                }

                $teacher_stmt->bind_param('i', $user_id);
                if (!$teacher_stmt->execute()) {
                    throw new Exception("Error inserting teacher: " . $teacher_stmt->error);
                }
                $teacher_stmt->close();

            } elseif ($role === 'student') {
                $student_sql = "INSERT INTO students (user_id, balance, profile_status) VALUES (?, 0.00, 'inactive')";
                $student_stmt = $conn->prepare($student_sql);
                if (!$student_stmt) {
                    throw new Exception("Error preparing student statement: " . $conn->error);
                }

                $student_stmt->bind_param('i', $user_id);
                if (!$student_stmt->execute()) {
                    throw new Exception("Error inserting student: " . $student_stmt->error);
                }
                $student_stmt->close();
            }

            // Commit the transaction
            $conn->commit();

            echo "<div class='alert alert-success'>User registered successfully!</div>";
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }
}

$conn->close();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center bg-dark text-white">
                    <h3>Signup</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="" autocomplete="off">
                        <div class="row mb-3">
                            <div class="mb-3">
                                <select name="role" id="role" class="form-select" required>
                                    <option value="student">Student</option>
                                    <option value="teacher" selected>Teacher</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="first_name" placeholder="First Name"
                                    autocomplete="off" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="last_name" placeholder="Last Name"
                                    autocomplete="off" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password"
                                    required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="signup" class="btn btn-primary">Sign up</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../footer.php"); ?>