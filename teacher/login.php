<?php
session_start(); // Start the session

include 'Connection.php'; // Include your connection file

echo "Referrer: " . ($_SESSION['referrer'] ?? 'Not Set') . "<br>";
var_dump(isset($_SESSION['referrer']) ? $_SESSION['referrer'] : 'Not Set');
var_dump(empty($_SESSION['referrer']));

// Initialize error message
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement using a prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // "s" means the parameter is a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password using password_verify (assuming passwords are hashed in the database)
        if (password_verify($password, $user['password'])) {
            // Store the username and other user data in session
            $_SESSION['username'] = $user['first_name'];  // Store the 'name' from the database
            $_SESSION['user_id'] = $user['id'];     // Store user ID for later use (if needed)
            $_SESSION['role'] = $user['role'];      // Store the role to redirect to correct dashboard

            if (!empty($_SESSION['referrer'])) { // Check if referrer is set and not empty
                $redirect_url = $_SESSION['referrer']; // Use the referrer URL
                unset($_SESSION['referrer']); // Clear referrer after use
            } else {
                // Default dashboard redirect based on role
                $redirect_url = ($user['role'] === 'teacher') ? 'teacher_dashboard.php' : '../student/student_dashboard.php';
            }


            header("Location: $redirect_url");
            exit();


            header("Location: $redirect_url");
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "User not found. Please sign up.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger text-center">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="POST">
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
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </div>
                        </form>
                        <!-- Sign Up Option -->
                        <div class="mt-3 text-center">
                            <p>Don't have an account? <a href="Sign_up.php">Sign Up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>