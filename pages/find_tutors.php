<?php
session_start();

// Set default referrer if not already set
if (!isset($_SESSION['referrer'])) {
    $_SESSION['referrer'] = '../find_tutors.php';
}

// Store the selected teacher ID in the session if provided
if (isset($_POST['teacher_id'])) {
    $_SESSION['teacher_id'] = $_POST['teacher_id'];
}

// Include the connection file
include '../Connection.php';

// Initialize filter variables
$subject = isset($_GET['subject']) ? $_GET['subject'] : '';
$country = isset($_GET['country']) ? $_GET['country'] : '';
$price_range = isset($_GET['price']) ? $_GET['price'] : '';
$video = isset($_GET['video']) ? $_GET['video'] : '';
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';

// Fetch subjects and countries dynamically based on selection

// Fetch subjects for the dropdown based on the selected country
$subjects_query = "SELECT DISTINCT subject 
                   FROM teachers 
                   JOIN countries ON teachers.country_id = countries.id
                   WHERE is_active = 1";

if ($country) {
    $subjects_query .= " AND countries.name = '" . $conn->real_escape_string($country) . "'";
}

$subjects_result = $conn->query($subjects_query);
$subjects = [];
if ($subjects_result && $subjects_result->num_rows > 0) {
    while ($row = $subjects_result->fetch_assoc()) {
        $subjects[] = $row['subject'];
    }
}

// Fetch countries for the dropdown based on the selected subject
$countries_query = "SELECT DISTINCT countries.name 
                    FROM teachers 
                    JOIN countries ON teachers.country_id = countries.id
                    WHERE is_active = 1";

if ($subject) {
    $countries_query .= " AND teachers.subject = '" . $conn->real_escape_string($subject) . "'";
}

$countries_result = $conn->query($countries_query);
$countries = [];
if ($countries_result && $countries_result->num_rows > 0) {
    while ($row = $countries_result->fetch_assoc()) {
        $countries[] = $row['name'];
    }
}

// Prepare SQL query for filtering tutors with JOIN
$sql = "SELECT teachers.*, countries.name AS country_name, users.first_name, users.last_name 
        FROM teachers 
        JOIN countries ON teachers.country_id = countries.id
        JOIN users ON teachers.user_id = users.id
        WHERE is_active = 1";

// Apply filters dynamically based on user input
if ($subject) {
    $sql .= " AND subject = '" . $conn->real_escape_string($subject) . "'";
}
if ($video) {
    $sql .= " AND youtube_video_url = '" . $conn->real_escape_string($video) . "'";
}
if ($country && $country != 'Any country') {
    $sql .= " AND countries.name = '" . $conn->real_escape_string($country) . "'";
}
if ($price_range) {
    if (strpos($price_range, '+') !== false) {
        $min_price = (float) str_replace('+', '', $price_range);
        $sql .= " AND pricing >= $min_price";
    } else {
        list($min_price, $max_price) = explode('-', $price_range);
        $sql .= " AND pricing BETWEEN " . (float) $min_price . " AND " . (float) $max_price;
    }
}
if ($availability && $availability !== 'Any time') {
    //$sql .= " AND FIND_IN_SET('" . $conn->real_escape_string($availability) . "', availability_time)";
}
// Execute query for filtering tutors
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Search</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);

        }

        h1 {
            color: #343a40;
        }

        .filter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-item {
            flex: 1;
            min-width: 200px;
        }

        .tutor-card {

            border-radius: 8px;
            overflow: hidden;
            display: flex;
            margin-bottom: 30px;
        }

        .tutor-card-body {

            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .tutor-header {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tutor-header .tutor-name {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .tutor-img {
            width: 150px;
            height: auto;
            border-radius: 8px;
        }

        .tutor-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .tutor-info .tutor-subject-language {
            margin-top: px;
        }

        .tutor-info .tutor-subject-language div {
            font-size: 0.7rem;
            font-weight: 500;
        }

        .tutor-rating-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: column;
            margin-top: 10px;

        }

        .tutor-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .btn-book {
            background-color: #ff6f61;
            color: white;
            border: none;
        }

        .btn-message {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .video-preview {
            width: 300px;
            height: 150px;
            background-color: #ccc;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1rem;
            color: #555;
            position: relative;
        }

        .video-preview iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5 ">
        <h1>Find a Tutor</h1>
        <form method="GET" action="" id="filterForm">
            <div class="filter-container">
                <!-- Subject Dropdown -->
                <div class="filter-item">
                    <label for="subject">I want to learn:</label>
                    <select class="form-control" id="subject" name="subject" onchange="this.form.submit()">
                        <option value="">Select a subject...</option>
                        <?php foreach ($subjects as $sub): ?>
                            <option value="<?php echo htmlspecialchars($sub); ?>" <?php echo ($subject == $sub) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sub); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Country Dropdown -->
                <div class="filter-item">
                    <label for="country">Country of birth:</label>
                    <select class="form-control" id="country" name="country">
                        <option value="">Select a country...</option>
                        <?php foreach ($countries as $cnt): ?>
                            <option value="<?php echo htmlspecialchars($cnt); ?>" <?php echo ($country == $cnt) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cnt); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Availability Dropdown -->
                <div class="filter-item">
                    <label for="availability">I'm available:</label>
                    <select class="form-control" id="availability" name="availability">
                        <option value="Any time">Any time</option>
                        <option value="Morning" <?php echo ($availability == 'Morning') ? 'selected' : ''; ?>>Morning
                        </option>
                        <option value="Afternoon" <?php echo ($availability == 'Afternoon') ? 'selected' : ''; ?>>
                            Afternoon</option>
                        <option value="Evening" <?php echo ($availability == 'Evening') ? 'selected' : ''; ?>>Evening
                        </option>
                    </select>
                </div>
            </div>
        </form>




        <h2 class="mt-5">Available Tutors</h2>
        <div class="row align-items-start mb-4">

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-12 d-flex">';  // One tutor per row, full width container
            
                    // Tutor card (left side)
                    echo '<div class="col-md-9 d-flex tutor-card mb-4">';
                    echo '<a href="trial_lesson_page.php" class=" d-flex tutor-card " 
                style="text-decoration: none; color: inherit;  width: 100%; height: 100%;border: 1px solid #dee2e6;  "
                onclick="storeTeacherId(' . htmlspecialchars($row['teacher_id']) . ')">';

                    // Tutor image
                    echo '<div class="col-md-3 d-flex justify-content-center align-items-center">';
                    echo '<img class="tutor-img" src="../teacher/' . htmlspecialchars($row['photo']) . '" alt="Tutor Image">';
                    echo '</div>';

                    // Tutor info: name, subject, language
                    echo '<div class="col-md-6 tutor-info">';
                    echo '<div class="tutor-header">';
                    echo '<div class="tutor-name">' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</div>';
                    echo '</div>';
                    echo '<div class="tutor-subject-language">';
                    echo '<div><strong>From:</strong> ' . htmlspecialchars($row['country_name']) . '</div>';

                    echo '<div><strong>Subject:</strong> ' . htmlspecialchars($row['subject']) . '</div>';
                    echo '<div><strong>Speaks:</strong> ' . htmlspecialchars($row['language']) . '</div>';
                    echo '</div>';

                    echo '</div>';

                    // Tutor price and buttons
                    echo '<div class="col-md-3 tutor-rating-price">';
                    echo '<div class="tutor-price">US$' . htmlspecialchars($row['charge_per_hour']) . '/hr</div>';
                    echo '<div class="tutor-buttons">';

                    // Form to store teacher ID in session and redirect to trial lesson page
                    echo '<form action="store_teacher_id.php" method="POST">';
                    echo '<input type="hidden" name="teacher_id" value="' . htmlspecialchars($row['teacher_id']) . '">';
                    echo '<button type="submit" name="book_trial" class="btn btn-book">Book trial lesson</button>';
                    echo '</form>';

                    // Form to store teacher ID in session and redirect to message page
                    echo '<form action="store_teacher_id.php" method="POST">';
                    echo '<input type="hidden" name="teacher_id" value="' . htmlspecialchars($row['teacher_id']) . '">';
                    echo '<button type="submit" name="send_message" class="btn btn-message">Send message</button>';
                    echo '</form>';

                    echo '</div>';
                    echo '</div>';
                    echo '</div>'; // End of the tutor card
            

                    // Video preview (right side)
                    echo '<div class="col-md-3 d-flex justify-content-center align-items-center">';
                    echo '<div class="video-preview">';
                    echo '<iframe src=" ' . htmlspecialchars($row['youtube_video_url']) . '" allowfullscreen></iframe>';
                    echo '</div>';
                    echo '</div>';

                    echo '</div>';  // End of row
                }
            } else {
                echo '<p>No tutors found matching your criteria.</p>';
            }
            ?>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // JavaScript to automatically submit the form on change  
        document.querySelectorAll('#filterForm select').forEach((select) => {
            select.addEventListener('change', function () {
                document.getElementById('filterForm').submit();
            });
        });
        function updateCountries() {
            const subject = document.getElementById('subject').value;
            const countryDropdown = document.getElementById('country');

            // Clear existing options
            countryDropdown.innerHTML = '<option value="">Select a country...</option>';

            if (subject) {
                // Send AJAX request to fetch countries for the selected subject
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch_countries.php?subject=' + encodeURIComponent(subject), true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const countries = JSON.parse(xhr.responseText);
                        countries.forEach(function (country) {
                            const option = document.createElement('option');
                            option.value = country;
                            option.textContent = country;
                            countryDropdown.appendChild(option);
                        });
                    }
                };
                xhr.send();
            }
        }
    </script>

</body>

</html>

<?php
$conn->close();
?>
<?php


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, show the modal
    $showModal = true;
} else {
    // User is logged in, no need to show the modal
    $showModal = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Tutor</title>
</head>

<body>


    <?php if ($showModal): ?>
        <!-- Modal asking for login -->
        <div id="modal" style="display: block;">
            <div class="modal-content">
                <h2>Please Log In to Proceed</h2>
                <p>You need to log in to book a tutor.</p>
                <a href="login.php" id="loginBtn">
                    <button>Login</button>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // If modal is shown, you may need to handle closing it
        const modal = document.getElementById('modal');
        // For now, modal automatically shows when not logged in
    </script>

    <style>
        /* Ensure the modal appears in the center of the page */
        body {
            margin: 0;
            height: 100vh;
            /* Full screen height */
        }

        #modal {
            position: fixed;

            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Transparent black background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            /* Ensure the modal is above other content */
        }

        .modal-content {
            margin: 200px;
            margin-left: 450px;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            max-width: 400px;
            width: 80%;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</body>

</html>