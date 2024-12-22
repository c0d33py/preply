<?php
include '../header.php';
include '../db_config.php';

session_start();

// Initialize error variable
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input (only if $conn is valid)
    if ($conn) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Query the database to check for the user
        $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect user to dashboard or home page
                header("Location: teacher_dashboard.php");
                exit;
            } else {
                $error = 'Invalid password!';
            }
        } else {
            $error = 'No user found with this email!';
        }
    } else {
        $error = 'Database connection failed!';
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-5">
            <div class="card shadow">
                <div class="card-header text-center bg-dark text-white">
                    <h3>Sign In</h3>
                </div>
                <div class="card-body">
                    <?php if ($error != ''): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="row mb-5">
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password"
                                    required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Sign in</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p>Don't have an account? <a href="register.php">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>