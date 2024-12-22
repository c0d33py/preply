<?php
session_start(); // Start the session to use session variables

// Check if the user is logged in (user_id is in session)
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from session

// Database connection (Update these with your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input values to prevent SQL injection
    $intro = $conn->real_escape_string($_POST['intro']);
    $experience = $conn->real_escape_string($_POST['experience']);
    $motivation = $conn->real_escape_string($_POST['motivation']);
    $headline = $conn->real_escape_string($_POST['headline']);

    // Update query to insert the form data into the table
    $sql = "UPDATE teachers SET intro = '$intro', experience = '$experience', motivation = '$motivation', headline = '$headline' WHERE user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

// Close the database connection
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
            justify-content: space-between;
        }

        /* Header Styles */
        .header-container {
            display: flex;
            align-items: center;
            background-color: #f4f4f6; /* Light gray background */
            padding: 10px 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            justify-content: space-between;
        }

        .header-step {
            display: flex;
            align-items: center;
            margin-right: 20px;
            color: #6c757d; /* Gray text color */
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
        }

        .header-step.active {
            color: #ffffff; /* White text for active */
            background-color: #343a40; /* Dark background for active */
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .header-step .circle {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            text-align: center;
            margin-right: 10px;
            font-size: 0.8rem;
        }

        .header-step.active .circle {
            background-color: #343a40; /* Dark background for active step */
            color: white; /* White number for active */
        }

        .header-step.inactive .circle {
            background-color: #e9ecef; /* Light background for inactive */
            color: #6c757d; /* Gray number for inactive */
        }

        .header-step span {
            display: inline-block;
        }

        /* Form Styles */
        form{
            background-color: #fff;
            border-radius: 10px;
            padding: 20px 30px;
            width: 400px;
            margin-top: 30px;
            margin-left: 400px;
        }

        .form-container h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #333;
        }

        .form-step {
            margin-bottom: 15px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            position: relative;
        }

        .form-step::after {
            content: '▼';
            font-size: 0.9rem;
            position: absolute;
            right: 0;
            top: 0;
        }

        .form-step.completed::after {
            content: '✔';
            color: green;
        }

        .step-details {
            display: none;
            font-size: 0.9rem;
            color: #555;
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .form-step.active .step-details {
            display: block;
        }

        .form-step.active::after {
            content: '▲';
            color: #333;
        }

        .counter {
            font-size: 0.9rem;
            margin-top: 10px;
            color: #555;
        }

        button {
            background-color: #ff007f;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #e60072;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-container" id="header">
        <div class="header-step inactive">
            <div class="circle">1</div>
            <span>About</span>
        </div>
        <div class="header-step inactive">
            <div class="circle">2</div>
            <span>Photo</span>
        </div>
        <div class="header-step active">
            <div class="circle">5</div>
            <span>Description</span>
        </div>
        <div class="header-step inactive">
            <div class="circle">6</div>
            <span>Video</span>
        </div>
        <div class="header-step inactive">
            <div class="circle">7</div>
            <span>Availability</span>
        </div>
        <div class="header-step inactive">
            <div class="circle">8</div>
            <span>Pricing</span>
        </div>
    </div>

    <!-- Form Section -->
    <form method="POST" action="video.php">
        <h1>Profile Description</h1><br>
        <div class="form-step">
            <span>1. Introduce yourself</span>
            <div class="step-details">
                Show potential students who you are! Share your teaching experience and passion for education. Mention your interests and hobbies.
                <textarea name="intro" id="intro" rows="5" style="width: 100%; margin-top: 10px;"></textarea>
                <p class="alert" style="margin-top: 10px; color: #888;">Don't include your last name or use a CV format.</p>
            </div>
        </div>
        <div class="form-step">
            <span>2. Teaching experience</span>
            <div class="step-details">
                Share your journey as a teacher and the subjects you specialize in.
                <textarea name="experience" id="experience" rows="5" style="width: 100%; margin-top: 10px;"></textarea>
            </div>
        </div>
        <div class="form-step">
            <span>3. Motivate potential students</span>
            <div class="step-details">
                Describe how your teaching can inspire students to achieve their goals.
                <textarea name="motivation" id="motivation"  rows="5" style="width: 100%; margin-top: 10px;"></textarea>
            </div>
        </div>
        <div class="form-step">
            <span>4. Write a catchy headline</span>
            <div class="step-details">
                A short and engaging headline that highlights your unique teaching style.
                <textarea  name="headline" id="headline" rows="5" style="width: 100%; margin-top: 10px;"></textarea>
            </div>
        </div>
        <div class="counter" id="counter">0 / 400</div>
        <button>Save and continue</button>
    </form>

    <script>
        // Add event listener for all steps in form
        document.querySelectorAll('.form-step').forEach(step => {
            step.addEventListener('click', (event) => {
                // Only toggle step if the click is not on the textarea
                if (!event.target.closest('textarea')) {
                    step.classList.toggle('active');
                }
            });
        });

        const counter = document.getElementById('counter');
        const textAreas = document.querySelectorAll('textarea');

        // Function to calculate the total number of characters
        const updateCharacterCount = () => {
            let totalCharacters = 0;
            textAreas.forEach(textArea => {
                totalCharacters += textArea.value.length;
            });

            // Update the counter display
            counter.textContent = `${totalCharacters} / 400`;

            // Add styling for the minimum limit
            if (totalCharacters >= 400) {
                counter.style.color = 'green';
            } else {
                counter.style.color = '#555';
            }
        };

        textAreas.forEach(textArea => {
            textArea.addEventListener('input', updateCharacterCount);
        });
    </script>
</body>
</html>
