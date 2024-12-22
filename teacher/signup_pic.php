<?php
session_start();
include 'Connection.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header('Location: login.php');
    exit();
}

// Get the user_id from session
$user_id = $_SESSION['user_id'];

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data from the teachers table
$sql_fetch = "SELECT first_name, last_name, country_of_birth, subject, languages, level, photo 
              FROM teachers WHERE user_id = ?";
$stmt_fetch = $conn->prepare($sql_fetch);
if ($stmt_fetch === false) {
    die("Error preparing the statement: " . $conn->error);
}

$stmt_fetch->bind_param("i", $user_id);
if (!$stmt_fetch->execute()) {
    die("Error executing the statement: " . $stmt_fetch->error);
}

$result = $stmt_fetch->get_result();

if ($result->num_rows > 0) {
    $teacher = $result->fetch_assoc();
} else {
    echo "No teacher data found.";
}

$stmt_fetch->close();

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $target_dir = "uploads/"; // Ensure this folder exists and has proper permissions
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a real image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size
    if ($_FILES["photo"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // If upload is successful, move the file and update the database
    if ($uploadOk == 1) {
        // Delete old photo if it exists
        if (!empty($teacher['photo']) && file_exists($teacher['photo'])) {
            unlink($teacher['photo']);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Update the teacher's photo in the database
            $sql_update = "UPDATE teachers SET photo = ? WHERE user_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $target_file, $user_id);
            if ($stmt_update->execute()) {
                echo "The file " . htmlspecialchars(basename($_FILES["photo"]["name"])) . " has been uploaded.";
                // Redirect to the same page to refresh the profile with the new image
                header("Location: signup_pic.php");
                exit();
            } else {
                echo "Error updating photo.";
            }
            $stmt_update->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Handle 'Save and Continue' button
if (isset($_POST['save_and_continue'])) {
    // Redirect to another page (for example, description.php)
    header("Location: description.php");
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Header Steps</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .header {
            display: flex;
            align-items: center;
            background-color: #f4f4f6; /* Light gray background */
            padding: 10px 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            justify-content: space-between;
        }

        .step {
            display: flex;
            align-items: center;
            margin-right: 20px;
            color: #6c757d; /* Gray text color */
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
        }

        .step.active {
            color: #ffffff; /* White text for active */
            background-color: #343a40; /* Dark background for active */
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .step .circle {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            text-align: center;
            margin-right: 10px;
            font-size: 0.8rem;
        }

        .step.active .circle {
            background-color: #343a40; /* Dark background for active step */
            color: white; /* White number for active */
        }

        .step.inactive .circle {
            background-color: #e9ecef; /* Light background for inactive */
            color: #6c757d; /* Gray number for inactive */
        }

        .step span {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header" id="header">
        <div class="step inactive" >
            <div class="circle">1</div>
            <span>About</span>
        </div>
        <div class="step active" >
            <div class="circle">2</div>
            <span>Photo</span>
        </div>

        <div class="step inactive" >
            <div class="circle">5</div>
            <span>Description</span>
        </div>
        <div class="step inactive" >
            <div class="circle">6</div>
            <span>Video</span>
        </div>
        <div class="step inactive">
            <div class="circle">7</div>
            <span>Availability</span>
        </div>
        <div class="step inactive" >
            <div class="circle">8</div>
            <span>Pricing</span>
        </div>
    </div>


</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .profile-container {
            width: 400px;
            margin: 50px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .profile-photo {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .profile-name {
            font-size: 22px;
            font-weight: bold;
            margin: 5px 0;
        }
        .profile-info {
            color: #555;
            margin: 10px 0;
        }
        .upload-btn {
            margin-top: 15px;
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #333;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            cursor: pointer;
        }
        .upload-btn:hover {
            background-color: #333;
            color: #fff;
        }
        input[type="file"] {
            margin-top: 10px;
        }
        .save-btn {
            margin-top: 15px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .save-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <img src="<?php echo htmlspecialchars($teacher['photo'] ?? 'default_profile.jpg'); ?>" 
         alt="Profile Photo" class="profile-photo">
    <div class="profile-name">
        <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?>
    </div>
    <div class="profile-info">
        Country: <?php echo htmlspecialchars($teacher['country_of_birth']); ?><br>
        Subject: <?php echo htmlspecialchars($teacher['subject']); ?><br>
        Speaks: <?php echo htmlspecialchars($teacher['languages']); ?><br>
        Level: <?php echo htmlspecialchars($teacher['level']); ?>
    </div>
    <div>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="photo" accept="image/*" required>
            <button type="submit" class="upload-btn" name="submit">Upload new photo</button>
        </form>
    </div>
    <div>
        <form action="" method="POST">
            <button type="submit" class="save-btn" name="save_and_continue">Save and Continue</button>
        </form>
    </div>
</div>

</body>
</html>
