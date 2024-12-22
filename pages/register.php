<?php include("../header.php");

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
        // Insert user into the database
        $sql = "INSERT INTO users (role, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('sssss', $role, $first_name, $last_name, $email, $password);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>User registered successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            }

            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
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