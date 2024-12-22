<?php
include 'Connection.php';

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $country_of_birth = htmlspecialchars($_POST['country_of_birth']);
    $country = htmlspecialchars($_POST['country']);
    $subject = htmlspecialchars($_POST['subject']);
    $languages = htmlspecialchars($_POST['languages']);
    $level = htmlspecialchars($_POST['level']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $certification = htmlspecialchars($_POST['certification']);
    $education = htmlspecialchars($_POST['education']);
    $intro = htmlspecialchars($_POST['intro']);
    $experience = htmlspecialchars($_POST['experience']);
    $motivation = htmlspecialchars($_POST['motivation']);
    $headline = htmlspecialchars($_POST['headline']);
    $timezone = htmlspecialchars($_POST['timezone']);
    $pricing = htmlspecialchars($_POST['pricing']);
    $email = htmlspecialchars($_POST['email']);

    // Schedule times
    $monday_start_time = $_POST['monday_start_time'];
    $monday_end_time = $_POST['monday_end_time'];
    $tuesday_start_time = $_POST['tuesday_start_time'];
    $tuesday_end_time = $_POST['tuesday_end_time'];
    $wednesday_start_time = $_POST['wednesday_start_time'];
    $wednesday_end_time = $_POST['wednesday_end_time'];
    $thursday_start_time = $_POST['thursday_start_time'];
    $thursday_end_time = $_POST['thursday_end_time'];
    $friday_start_time = $_POST['friday_start_time'];
    $friday_end_time = $_POST['friday_end_time'];
    $saturday_start_time = $_POST['saturday_start_time'];
    $saturday_end_time = $_POST['saturday_end_time'];
    $sunday_start_time = $_POST['sunday_start_time'];
    $sunday_end_time = $_POST['sunday_end_time'];

    // File uploads
    $photo = $_FILES['photo'];
    $video = $_FILES['video'];

    // Check if the email already exists in the teachers table
    $checkQuery = "SELECT * FROM teachers WHERE email = '$email'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>
                alert('Email already exists. Please login.');
                window.location.href = 'login.php';
              </script>";
        exit();
    }

    // Handle file uploads
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $photo_name = time() . "_" . basename($photo['name']);
    $photo_target = $upload_dir . $photo_name;
    move_uploaded_file($photo['tmp_name'], $photo_target);

    $video_name = time() . "_" . basename($video['name']);
    $video_target = $upload_dir . $video_name;
    move_uploaded_file($video['tmp_name'], $video_target);

    // Insert teacher data into the database
    $query = "INSERT INTO teachers 
        (first_name, last_name, country_of_birth, country, subject, languages, level, phone_number, photo, certification, education, intro, experience, motivation, headline, video, timezone, pricing,
        monday_start_time, monday_end_time, tuesday_start_time, tuesday_end_time, wednesday_start_time, wednesday_end_time, thursday_start_time, thursday_end_time, friday_start_time, friday_end_time, saturday_start_time, saturday_end_time, sunday_start_time, sunday_end_time, created_at, updated_at, email) 
        VALUES ('$first_name', '$last_name', '$country_of_birth', '$country', '$subject', '$languages', '$level', '$phone_number', '$photo_name', '$certification', '$education', '$intro', '$experience', '$motivation', '$headline', '$video_name', '$timezone', '$pricing', 
        '$monday_start_time', '$monday_end_time', '$tuesday_start_time', '$tuesday_end_time', '$wednesday_start_time', '$wednesday_end_time', '$thursday_start_time', '$thursday_end_time', '$friday_start_time', '$friday_end_time', '$saturday_start_time', '$saturday_end_time', '$sunday_start_time', '$sunday_end_time', NOW(), NOW(), '$email')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Signup successful! Please login.');
                window.location.href = 'login.php';
              </script>";
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
    <title>Teacher Signup</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>Teacher Signup</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group mb-3">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="country_of_birth">Country of Birth</label>
                                <select name="country_of_birth" id="country_of_birth" class="form-control" required>
                                    <option value="" disabled selected>Select your country</option>
                                    <option value="USA">USA</option>
                                    <option value="UK">UK</option>
                                    <option value="Canada">Canada</option>
                                    <option value="India">India</option>
                                    <option value="Australia">Australia</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="country">Country</label>
                                <select name="country" id="country" class="form-control" required>
                                    <option value="" disabled selected>Select your country</option>
                                    <option value="USA">USA</option>
                                    <option value="UK">UK</option>
                                    <option value="Canada">Canada</option>
                                    <option value="India">India</option>
                                    <option value="Australia">Australia</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="subject">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="languages">Languages</label>
                                <select name="languages" id="languages" class="form-control" required>
                                    <option value="" disabled selected>Select languages</option>
                                    <option value="English">English</option>
                                    <option value="Spanish">Spanish</option>
                                    <option value="French">French</option>
                                    <option value="German">German</option>
                                    <option value="Mandarin">Mandarin</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="level">Education Level</label>
                                <select name="level" id="level" class="form-control" required>
                                    <option value="" disabled selected>Select education level</option>
                                    <option value="High School">High School</option>
                                    <option value="Bachelor's Degree">Bachelor's Degree</option>
                                    <option value="Master's Degree">Master's Degree</option>
                                    <option value="Doctorate">Doctorate</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="certification">Certification</label>
                                <textarea name="certification" id="certification" class="form-control" required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="education">Education</label>
                                <textarea name="education" id="education" class="form-control" required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="intro">Introduction</label>
                                <textarea name="intro" id="intro" class="form-control" required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="experience">Experience</label>
                                <textarea name="experience" id="experience" class="form-control" required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="motivation">Motivation</label>
                                <textarea name="motivation" id="motivation" class="form-control" required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="headline">Headline</label>
                                <input type="text" name="headline" id="headline" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="timezone">Timezone</label>
                                <select name="timezone" id="timezone" class="form-control" required>
                                    <option value="" disabled selected>Select timezone</option>
                                    <option value="UTC-12:00">UTC-12:00</option>
                                    <option value="UTC-11:00">UTC-11:00</option>
                                    <option value="UTC-10:00">UTC-10:00</option>
                                    <option value="UTC-09:00">UTC-09:00</option>
                                    <option value="UTC-08:00">UTC-08:00</option>
                                    <option value="UTC-07:00">UTC-07:00</option>
                                    <option value="UTC-06:00">UTC-06:00</option>
                                    <option value="UTC-05:00">UTC-05:00</option>
                                    <option value="UTC-04:00">UTC-04:00</option>
                                    <option value="UTC+00:00">UTC+00:00</option>
                                    <option value="UTC+01:00">UTC+01:00</option>
                                    <option value="UTC+02:00">UTC+02:00</option>
                                    <option value="UTC+03:00">UTC+03:00</option>
                                    <option value="UTC+04:00">UTC+04:00</option>
                                    <option value="UTC+05:00">UTC+05:00</option>
                                    <option value="UTC+06:00">UTC+06:00</option>
                                    <option value="UTC+07:00">UTC+07:00</option>
                                    <!-- Add other timezones as needed -->
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="pricing">Pricing</label>
                                <input type="text" name="pricing" id="pricing" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="photo">Profile Photo</label>
                                <input type="file" name="photo" id="photo" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="video">Introduction Video</label>
                                <input type="file" name="video" id="video" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <!-- Schedule inputs -->
                            <div class="form-group mb-3">
                                <h5>Schedule</h5>
                                <?php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                foreach ($days as $day) {
                                    echo "
                                    <div class='row'>
                                        <div class='col'>
                                            <label>{$day} Start Time</label>
                                            <input type='time' name='" . strtolower($day) . "_start_time' class='form-control' required>
                                        </div>
                                        <div class='col'>
                                            <label>{$day} End Time</label>
                                            <input type='time' name='" . strtolower($day) . "_end_time' class='form-control' required>
                                        </div>
                                    </div>";
                                }
                                ?>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary w-50">Sign Up</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
