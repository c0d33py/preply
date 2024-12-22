<?php
session_start();
include 'Connection.php';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing
    $role = $_POST['role'];

    // Check if email already exists for teacher
    $checkQuery = "SELECT u.id, u.role, t.approval_status 
                   FROM users u 
                   LEFT JOIN teachers t ON u.id = t.user_id 
                   WHERE u.email = '$email'";
    $result = mysqli_query($conn, $checkQuery);

    // Check if email already exists for student
    $checkQuery2 = "SELECT u.id, u.role, s.profile_status 
                   FROM users u 
                   LEFT JOIN students s ON u.id = s.user_id 
                   WHERE u.email = '$email'";
    $result2 = mysqli_query($conn, $checkQuery2);

    // Check if email already exists
    if (mysqli_num_rows($result) > 0 || mysqli_num_rows($result2) > 0) {
        // Fetch teacher data if it exists
        $user = mysqli_fetch_assoc($result);

        // Fetch student data if it exists
        $user2 = mysqli_fetch_assoc($result2);

        // If it's a teacher
        if (isset($user) && $user['role'] === 'teacher') {
            if ($user['approval_status'] === 'approved') {
                // Redirect to teacher's dashboard if approved
                echo "<script>
                        alert('Your account has been approved. Redirecting to teacher dashboard.');
                        window.location.href = 'teacher_dashboard.php'; // Redirect to teacher's dashboard
                      </script>";
                exit();
            } else {
                // If teacher's approval is not complete
                echo "<script>
                        alert('Your teacher account is not approved yet.');
                        window.location.href = 'signup1.php'; // Stay on signup page for teacher approval
                      </script>";
                exit();
            }
        }

        // If it's a student
        if (isset($user2) && $user2['role'] === 'student') {
            if ($user2['profile_status'] === 'complete') {
                // Redirect to student's dashboard if profile is complete
                echo "<script>
                        alert('Your account has been approved. Redirecting to student dashboard.');
                        window.location.href = '../student/student_dashboard.php'; // Redirect to student dashboard
                      </script>";
                exit();
            } else {
                // If student profile is not complete
                echo "<script>
                        alert('Complete your profile.');
                        window.location.href = '../student/student_sign_up.php'; // Redirect to student profile completion page
                      </script>";
                exit();
            }
        }
    }

    // Insert user into the database if email does not exist
    $userQuery = "INSERT INTO users (first_name, last_name, email, password, role, created_at, updated_at)
                  VALUES ('$fname', '$lname', '$email', '$password', '$role', NOW(), NOW())";
    if (mysqli_query($conn, $userQuery)) {
        $user_id = mysqli_insert_id($conn); // Get the last inserted user ID

        // If role is teacher, insert into teachers table
        if ($role === 'teacher') {
            $teacherQuery = "INSERT INTO teachers (user_id, approval_status, created_at, updated_at)
                             VALUES ('$user_id', 'not approved', NOW(), NOW())";
            mysqli_query($conn, $teacherQuery);
        }

        // Start session and store data
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;
        $_SESSION['username'] = $fname . " " . $lname;

        // Redirect based on role after registration
        if ($role === 'student') {
            if (isset($_GET['redirect'])) {
                $redirectUrl = $_GET['redirect'];
                // Redirect the user back to the page they came from
                header("Location: $redirectUrl");
                exit();
            } else {
                // Default redirect to the student profile page
                header("Location: ../student/student_sign_up.php");
                exit();
            }
        } elseif ($role === 'teacher') {
            if (isset($_GET['redirect'])) {
                $redirectUrl = $_GET['redirect'];
                // Redirect the user back to the page they came from
                header("Location: $redirectUrl");
                exit();
            } else {
                // Default redirect to teacher's signup page
                header("Location: signup1.php");
                exit();
            }
        }
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>Signup</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group mb-3">
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" id="fname" class="form-control"
                                    placeholder="Enter your first name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" id="lname" class="form-control"
                                    placeholder="Enter your last name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Enter your email" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter your password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="role">Sign up as</label>
                                <div class="input-group">
                                    <select name="role" id="role" class="form-select" required>
                                        <option value="student">Student</option>
                                        <option value="teacher" selected>Teacher</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary w-50">Sign Up</button>
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
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>